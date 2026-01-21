<?php
require_once __DIR__ . '/customer_auth.php';

include "../Model/DBConnectr.php";

$db = new DBConnectr();
$connection = $db->openConnection();

$email = $_SESSION['email'];
$result = $db->getCustomerByEmail($connection, $email);
$customer = $result ? $result->fetch_assoc() : null;
$customerId = (int) (($customer['ID'] ?? $customer['LoginID'] ?? 0));

if ($customerId <= 0) {
    $db->closeConnection($connection);
    header('Location: ../View/cart.php');
    exit();
}

$quantities = $_POST['qty'] ?? [];
if (is_array($quantities)) {
    foreach ($quantities as $pid => $qty) {
        $productId = (int) $pid;
        $qty = is_numeric($qty) ? (int) $qty : 0;

        if ($productId <= 0) {
            continue;
        }

       
        $productRes = $db->getProductById($connection, $productId);
        $product = $productRes ? $productRes->fetch_assoc() : null;
        if ($product && is_numeric($product['product_quantity'] ?? null)) {
            $stock = (int) $product['product_quantity'];
            if ($stock <= 0) {
                $qty = 0;
            } elseif ($qty > $stock) {
                $qty = $stock;
            }
        }

        $db->updateCartItemQuantity($connection, $customerId, $productId, $qty);
    }
}


$coupon = isset($_POST['coupon']) ? trim((string) $_POST['coupon']) : '';
$_SESSION['cart_coupon'] = $coupon;


$shippingAddress = isset($_POST['shipping_address']) ? trim((string) $_POST['shipping_address']) : '';
$_SESSION['shipping_address'] = $shippingAddress;

$db->closeConnection($connection);

header('Location: ../View/cart.php');
exit();
