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
