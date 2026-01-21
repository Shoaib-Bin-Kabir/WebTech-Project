<?php
require_once __DIR__ . '/customer_auth.php';

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
$shippingAddress = isset($_POST['shipping_address']) ? trim((string) $_POST['shipping_address']) : '';
if ($shippingAddress === '') {
    $shippingAddress = isset($_SESSION['shipping_address']) ? trim((string) $_SESSION['shipping_address']) : '';
} else {
    // Persist for subsequent steps/pages.
    $_SESSION['shipping_address'] = $shippingAddress;
}

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
$isBuyNow = false;
$buyNow = $_SESSION['buy_now'] ?? null;

if (is_array($buyNow) && isset($buyNow['product_id'])) {
    $isBuyNow = true;
    $buyNowProductId = (int) $buyNow['product_id'];
    $buyNowQty = 1;

    $productRes = $db->getProductById($connection, $buyNowProductId);
    $product = $productRes ? $productRes->fetch_assoc() : null;
    if ($product) {
        $items[] = [
            'product_id' => $buyNowProductId,
            'cart_quantity' => $buyNowQty,
            'product_name' => $product['product_name'] ?? '',
            'product_price' => $product['product_price'] ?? 0,
        ];
    }
} else {
    $cartRes = $db->getCartItems($connection, $customerId);
    if ($cartRes) {
        while ($row = $cartRes->fetch_assoc()) {
            $items[] = $row;
        }
    }
}

if (count($items) === 0) {
    $db->closeConnection($connection);
    $_SESSION['orderError'] = 'No items to checkout.';
    header('Location: ../View/payment.php');
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

$connection->begin_transaction();


foreach ($items as $it) {
    $productId = (int) ($it['product_id'] ?? $it['id'] ?? 0);
    $name = (string) ($it['product_name'] ?? '');
    $price = (float) ($it['product_price'] ?? 0);
    $qty = (int) ($it['cart_quantity'] ?? 0);

    if ($productId <= 0 || $qty <= 0) {
        continue;
    }

    $stockOk = $db->decrementProductStock($connection, $productId, $qty);
    if (!$stockOk) {
        $connection->rollback();
        $db->closeConnection($connection);
        $_SESSION['orderError'] = 'Not enough stock available for: ' . $name;
        header('Location: ../View/payment.php');
        exit();
    }

    $ok = $db->addOrderItemRow($connection, $orderGroup, $customerId, $productId, $name, $price, $qty, $shippingAddress);
    if (!$ok) {
        $connection->rollback();
        $db->closeConnection($connection);
        $_SESSION['orderError'] = 'Could not create order.';
        header('Location: ../View/payment.php');
        exit();
    }
}

if (!$isBuyNow) {
    $clearOk = $db->clearCart($connection, $customerId);
    if (!$clearOk) {
        $connection->rollback();
        $db->closeConnection($connection);
        $_SESSION['orderError'] = 'Could not clear cart after payment.';
        header('Location: ../View/payment.php');
        exit();
    }
}

$connection->commit();

$db->closeConnection($connection);

if ($isBuyNow) {
    unset($_SESSION['buy_now']);
} else {
    unset($_SESSION['cart_coupon']);
    unset($_SESSION['shipping_address']);
}

$_SESSION['orderSuccess'] = 'Payment successful. Your order has been placed.';

header('Location: ../View/orderHistory.php');
exit();
