<?php
require_once __DIR__ . '/customer_auth.php';

unset($_SESSION['buy_now']);

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
    $db->addToCart($connection, $customerId, $productId, 1);
}

$db->closeConnection($connection);

$redirect = $_SERVER['HTTP_REFERER'] ?? '../View/dashboard.php';
header('Location: ' . $redirect);
exit();
