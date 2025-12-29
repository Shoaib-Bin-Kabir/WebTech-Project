<?php

session_start();

include "../Model/DBConnectr.php";

error_reporting(E_ALL);
ini_set("display_errors", 1);

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

unset($_SESSION['errors']);
unset($_SESSION['previousValues']);


$errors = [];


if (!$email) {
    $errors['email'] = 'Email is required';
} elseif (strpos($email, '@') === false || strpos($email, '.') === false) {
    $errors['email'] = 'Email must contain @ and .';
}

if (!$password) {
    $errors['password'] = 'Password is required';
} elseif (strlen($password) < 4) {
    $errors['password'] = 'Password must be at least 4 characters';
} else {
    $hasNum = false;
    $hasAlpha = false;
    for ($i = 0; $i < strlen($password); $i++) {
        if (ctype_digit($password[$i])) $hasNum = true;
        if (ctype_alpha($password[$i])) $hasAlpha = true;
    }
    if (!$hasNum || !$hasAlpha) {
        $errors['password'] = 'Password must contain both letters and numbers';
    }
}

if (!$confirmPassword) {
    $errors['confirmPassword'] = 'Confirm Password is required';
}

if ($password !== $confirmPassword) {
    $errors['confirmPassword'] = 'Passwords do not match';
}

if (count($errors) > 0) {
    $_SESSION['errors'] = $errors;
    header('Location: ../View/signup.php');
    exit();
}



$db = new DBConnectr();
$connection = $db->openConnection();

$existingUser = $db->checkEmailExists($connection, $email);

if ($existingUser->num_rows > 0) {
    $_SESSION['signupErr'] = 'This email is already registered';
    $_SESSION['previousValues'] = ['email' => $email];
    header('Location: ../View/signup.php');
    exit();
}


$result = $db->insertUser($connection, $email, $password);

if ($result) {
    $_SESSION['signupSuccess'] = 'Account created successfully! Please login.';
    header('Location: ../View/login.php');
} else {
    $_SESSION['signupErr'] = 'Something went wrong. Please try again.';
    header('Location: ../View/signup.php');
}

$db->closeConnection($connection);


?>