<?php

session_start();

include "../Model/DBConnectr.php";

error_reporting(E_ALL);
ini_set("display_errors", 1);

$name = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';
$uploadFile = $_FILES['picture'] ?? null;

unset($_SESSION['errors']);
unset($_SESSION['previousValues']);


$errors = [];


if (!$name) {
    $errors['email'] = 'Email is required';
}
if (!$password) {
    $errors['password'] = 'Password is required';
}

if (!$confirmPassword) {
    $errors['confirmPassword'] = 'Confirm Password is required';
}

if ($password !== $confirmPassword) {
    $errors['confirmPassword'] = 'Passwords do not match';
}


if ($uploadFile && $uploadFile['size'] > 0) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $fileType = $uploadFile['type'];
    
    if (!in_array($fileType, $allowedTypes)) {
        $errors['file'] = 'Only image files are allowed';
    }
}

if (count($errors) > 0) {
    $_SESSION['errors'] = $errors;
    header('Location: ../View/signup.php');
    exit();
}



$db = new DBConnectr();
$connection = $db->openConnection();

$existingUser = $db->checkEmailExists($connection, $name);

if ($existingUser->num_rows > 0) {
    $_SESSION['signupErr'] = 'This email is already registered';
    $_SESSION['previousValues'] = ['email' => $name];
    header('Location: ../View/signup.php');
    exit();
}


$filePath = '';
if ($uploadFile && $uploadFile['size'] > 0) {
    $targetDir = "../Uploads/";
    $fileName = basename($uploadFile['name']);
    $filePath = $targetDir . $fileName;
    
    if (!move_uploaded_file($uploadFile['tmp_name'], $filePath)) {
        $_SESSION['signupErr'] = 'File upload failed';
        header('Location: ../View/signup.php');
        exit();
    }
}


$result = $db->insertUser($connection, $name, $password, $filePath);

if ($result) {
    $_SESSION['signupSuccess'] = 'Account created successfully! Please login.';
    header('Location: ../View/login.php');
} else {
    $_SESSION['signupErr'] = 'Something went wrong. Please try again.';
    header('Location: ../View/signup.php');
}

$db->closeConnection($connection);


?>