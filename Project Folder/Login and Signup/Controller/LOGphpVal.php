<?php

session_start();

include "../Model/DBConnectr.php";

error_reporting(E_ALL);
ini_set("display_errors", 1);

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

unset($_SESSION['errors']);
unset($_SESSION['loginErr']);

$errors = [];


if (!$email) {
    $errors['email'] = 'Email is required';
}

if (!$password) {
    $errors['password'] = 'Password is required';
}


if (count($errors) > 0) {
    $_SESSION['errors'] = $errors;
    $_SESSION['previousValues'] = ['email' => $email];
    header('Location: ../View/login.php');
    exit();
}


$db = new DBConnectr();
$connection = $db->openConnection();


$result = $db->getUserByEmail($connection, $email, $password);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $userType = $user['user_type'];
    $_SESSION['isLoggedIn'] = true;
    $_SESSION['email'] = $email;
    $_SESSION['user_type'] = $userType; 
    
   $db->closeConnection($connection);

    if ($userType === 'Customer') {
        $cookieName = 'customer_auth';
        $maxAgeSeconds = 30 * 24 * 60 * 60; // 30 days
        $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
        setcookie($cookieName, '1', [
            'expires'  => time() + $maxAgeSeconds,
            'path'     => '/',
            'secure'   => $isHttps,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        header('Location: ../../Customer/View/dashboard.php');
        exit();
    
    } 
    elseif ($userType === 'Admin') {
        header('Location: ../../Admin/View/AHomePage.php');
        exit();
    }
    else {
        header('Location: ../../Seller/View/SHomePage.php');
        exit();
    }
} else {
    $_SESSION['loginErr'] = 'Email or Password is incorrect';
    $_SESSION['previousValues'] = ['email' => $email];
    $db->closeConnection($connection);
    header('Location: ../View/login.php');
    exit();
}
?>