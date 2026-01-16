<?php

session_start();

include "../Model/DBConnectr.php";

error_reporting(E_ALL);
ini_set("display_errors", 1);

$semail = $_POST['semail'] ?? '';
$spassword = $_POST['spassword'] ?? '';

unset($_SESSION['errors']);
$errors = [];

if (empty($semail)) {
    $errors['semail'] = 'Seller Email is required.';
} elseif (!filter_var($semail, FILTER_VALIDATE_EMAIL)) {
    $errors['semail'] = 'Invalid email format.';
}

if (empty($spassword)) {
    $errors['spassword'] = 'Seller Password is required.';
} elseif (strlen($spassword) < 4) {
    $errors['spassword'] = 'Password must be at least 4 characters long.';
} else {
    $hasNum = preg_match('/[0-9]/', $spassword);
    $hasAlpha = preg_match('/[a-zA-Z]/', $spassword);
    if (!$hasNum || !$hasAlpha) {
        $errors['spassword'] = 'Password must contain both letters and numbers.';
    }
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    $_SESSION['previousValues'] = [
        'semail' => $semail,
        'spassword' => $spassword
    ];
    header('Location: ../View/ManSeller.php');
    exit();
}


$db = new DBConnectr();
$connection = $db->openConnection();   

$existingSeller = $db->checkEmailExists($connection, $semail);

if($existingSeller->num_rows > 0){

    $_SESSION['addSellerError'] = "Seller with this email already exists.";
    $_SESSION['previousValues'] = ['semail' => $semail];
    header('Location: ../View/ManSeller.php');
    exit();

}

  $loginResult = $db->addSeller($connection, $semail, $spassword);

if ($loginResult) {
    $sellerId = $loginResult;
    

    $sellerResult = $db->insertSeller($connection, $sellerId, $semail, $spassword);
    
    if ($sellerResult) {
        $_SESSION['addSellerSuccess'] = 'Seller added successfully!';
        unset($_SESSION['previousValues']);
        header('Location: ../View/ManSeller.php');
    } else {
        $_SESSION['addSellerErr'] = 'Error adding seller details. Please try again.';
        $_SESSION['previousValues'] = ['semail' => $semail];
        header('Location: ../View/ManSeller.php');
    }
} else {
    $_SESSION['addSellerErr'] = 'Something went wrong. Please try again.';
    $_SESSION['previousValues'] = ['semail' => $semail];
    header('Location: ../View/ManSeller.php');
}



    $db->closeConnection($connection);

?>