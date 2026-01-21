<?php
require_once __DIR__ . '/customer_auth.php';

include "../Model/DBConnectr.php";

$productId = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
if ($productId <= 0) {
    header('Location: ../View/dashboard.php');
    exit();
}

$db = new DBConnectr();
$connection = $db->openConnection();

$email = $_SESSION['email'];
$result = $db->getCustomerByEmail($connection, $email);
$customer = $result ? $result->fetch_assoc() : null;
$customerId = (int) (($customer['ID'] ?? $customer['LoginID'] ?? 0));

if ($customerId <= 0) {
    $db->closeConnection($connection);
    header('Location: ../View/dashboard.php');
    exit();
}

$productRes = $db->getProductById($connection, $productId);
$product = $productRes ? $productRes->fetch_assoc() : null;

if ($product && is_numeric($product['product_quantity'] ?? null) && (int) $product['product_quantity'] > 0) {
    // Keep cart unchanged; store a separate one-item checkout.
    $_SESSION['buy_now'] = [
        'product_id' => $productId,
        'qty' => 1
    ];
} else {
    unset($_SESSION['buy_now']);
    $_SESSION['orderError'] = 'Product is not available.';
}

$db->closeConnection($connection);

header('Location: ../View/payment.php');
exit();
