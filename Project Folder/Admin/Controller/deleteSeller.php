<?php
session_start();

if (!isset($_SESSION['isLoggedIn']) || $_SESSION['user_type'] !== 'Admin') {
    header('Location: ../../Login and Signup/View/login.php');
    exit();
}

include "../Model/DBConnectr.php";

$sellerEmail = $_POST['sellerEmail'];

$db = new DBConnectr();
$connection = $db->openConnection();

$sellerCheck = $db->getSellerByEmail($connection, $sellerEmail);

if ($sellerCheck->num_rows == 0) {
    $_SESSION['sellerError'] = 'Seller not found';
    header('Location: ../View/ManSeller.php');
    exit();
}

$sellerData = $sellerCheck->fetch_assoc();

$photoPath = "../../Seller/" . $sellerData['Photo'];
if (!empty($sellerData['Photo']) && file_exists($photoPath)) {
    unlink($photoPath);
}

$result1 = $db->deleteSellerFromSeller($connection, $sellerEmail);
$result2 = $db->deleteSellerFromLogin($connection, $sellerEmail);

if ($result1 && $result2) {
    $_SESSION['sellerSuccess'] = 'Seller removed successfully';
} else {
    $_SESSION['sellerError'] = 'Failed to remove seller';
}

$db->closeConnection($connection);

header('Location: ../View/ManSeller.php');
exit();
?>