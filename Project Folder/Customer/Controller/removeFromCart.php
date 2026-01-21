<?php
require_once __DIR__ . '/customer_auth.php';

include "../Model/DBConnectr.php";

$productId = isset($_GET['product_id']) ? (int) $_GET['product_id'] : 0;

$db = new DBConnectr();
$connection = $db->openConnection();

$email = $_SESSION['email'];
$result = $db->getCustomerByEmail($connection, $email);
$customer = $result ? $result->fetch_assoc() : null;
$customerId = (int) (($customer['ID'] ?? $customer['LoginID'] ?? 0));

if ($customerId > 0 && $productId > 0) {
    $db->removeCartItem($connection, $customerId, $productId);
}

$db->closeConnection($connection);

header('Location: ../View/cart.php');
exit();
