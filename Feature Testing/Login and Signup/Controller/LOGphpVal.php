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

// If validation failed, redirect back
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
    $_SESSION['isLoggedIn'] = true;
    $_SESSION['email'] = $email;
    header('Location: ../View/Dashboard.php');
} else {
    $_SESSION['loginErr'] = 'Email or Password is incorrect';
    $_SESSION['previousValues'] = ['email' => $email];
    header('Location: ../View/login.php');
}

$db->closeConnection($connection);

?>