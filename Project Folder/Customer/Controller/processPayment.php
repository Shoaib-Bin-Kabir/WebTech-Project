<?php
session_start();

if (!isset($_SESSION['isLoggedIn']) || !$_SESSION['isLoggedIn']) {
    header('Location: ../../Login and Signup/View/login.php');
    exit();
}

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Customer') {
    header('Location: ../../Login and Signup/View/login.php');
    exit();
}

include "../Model/DBConnectr.php";

$method = isset($_POST['method']) ? trim((string) $_POST['method']) : '';
$ref = isset($_POST['ref']) ? trim((string) $_POST['ref']) : '';

if ($method === '' || $ref === '') {
    $_SESSION['orderError'] = 'Please provide payment method and reference.';
    header('Location: ../View/payment.php');
    exit();
}

$db = new DBConnectr();
$connection = $db->openConnection();

$email = $_SESSION['email'];
$result = $db->getCustomerByEmail($connection, $email);
$customer = $result ? $result->fetch_assoc() : [];
$customerId = (int) (($customer['ID'] ?? $customer['LoginID'] ?? 0));

if ($customerId <= 0) {
    $db->closeConnection($connection);
    header('Location: ../View/cart.php');
    exit();
}

$coupon = isset($_SESSION['cart_coupon']) ? trim((string) $_SESSION['cart_coupon']) : '';
$shippingAddress = isset($_SESSION['shipping_address']) ? trim((string) $_SESSION['shipping_address']) : '';

if ($shippingAddress === '') {
    $db->closeConnection($connection);
    $_SESSION['orderError'] = 'Please provide a shipping address.';
    header('Location: ../View/payment.php');
    exit();
}

$discount = 0;
if ($coupon === 'Semester Over') {
    $discount = 100;
}

$items = [];
$cartRes = $db->getCartItems($connection, $customerId);
if ($cartRes) {
    while ($row = $cartRes->fetch_assoc()) {
        $items[] = $row;
    }
}

if (count($items) === 0) {
    $db->closeConnection($connection);
    header('Location: ../View/cart.php');
    exit();
}

$total = 0.0;
foreach ($items as $it) {
    $price = (float) ($it['product_price'] ?? 0);
    $qty = (int) ($it['cart_quantity'] ?? 0);
    if ($qty < 0) {
        $qty = 0;
    }
    $total += ($price * $qty);
}

$grandTotal = $total - $discount;
if ($grandTotal < 0) {
    $grandTotal = 0;
}

$orderGroup = $db->createOrderGroup();

// Create order item rows (single table)
foreach ($items as $it) {
    $productId = (int) ($it['product_id'] ?? $it['id'] ?? 0);
    $name = (string) ($it['product_name'] ?? '');
    $price = (float) ($it['product_price'] ?? 0);
    $qty = (int) ($it['cart_quantity'] ?? 0);

    if ($productId <= 0 || $qty <= 0) {
        continue;
    }

    $ok = $db->addOrderItemRow($connection, $orderGroup, $customerId, $productId, $name, $price, $qty, $shippingAddress);
    if (!$ok) {
        $db->closeConnection($connection);
        $_SESSION['orderError'] = 'Could not create order.';
        header('Location: ../View/payment.php');
        exit();
    }
}

// Clear cart
$db->clearCart($connection, $customerId);

$db->closeConnection($connection);

unset($_SESSION['cart_coupon']);
unset($_SESSION['shipping_address']);

$_SESSION['orderSuccess'] = 'Payment successful. Your order has been placed.';

header('Location: ../View/orderHistory.php');
exit();
