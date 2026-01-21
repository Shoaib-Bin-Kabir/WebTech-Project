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

$adminEmail = $_SESSION['email'] ?? '';
$adminName = 'Admin';
$adminResult = $db->getAdminByEmail($connection, $adminEmail);
if ($adminResult && $adminResult->num_rows > 0) {
    $adminRow = $adminResult->fetch_assoc();
    $adminName = $adminRow['Name'] ?? $adminName;
}

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
    $db->insertHistory($connection, $adminEmail, $adminName, 'Delete', 'Seller: ' . $sellerEmail, NULL, NULL);
    $_SESSION['sellerSuccess'] = 'Seller removed successfully';
} else {
    $_SESSION['sellerError'] = 'Failed to remove seller';
}

$db->closeConnection($connection);

header('Location: ../View/ManSeller.php');
exit();
?>