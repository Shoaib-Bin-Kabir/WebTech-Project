<?php

session_start();

include "../Model/DBConnectr.php";

error_reporting(E_ALL);
ini_set("display_errors", 1);

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';
$name = $_POST['name'] ?? '';
$phone = $_POST['phone'] ?? '';
$nid = $_POST['nid'] ?? '';

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

if (empty($name)) {
    $errors['name'] = 'Name is required';
} elseif (strlen($name) < 2) {
    $errors['name'] = 'Name must be at least 2 characters';
}

if (empty($phone)) {
    $errors['phone'] = 'Phone number is required';
} elseif (!ctype_digit($phone)) {
    $errors['phone'] = 'Phone number must contain only digits';
} elseif (strlen($phone) < 10 || strlen($phone) > 15) {
    $errors['phone'] = 'Phone number must be between 10 and 15 digits';
}

if (empty($nid)) {
    $errors['nid'] = 'NID is required';
} elseif (strlen($nid) < 10) {
    $errors['nid'] = 'NID must be at least 10 characters';
}

// Handle photo upload
$photoPath = '';
if (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
    if ($_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $fileType = $_FILES['photo']['type'];
        $fileSize = $_FILES['photo']['size'];
        
        if (!in_array($fileType, $allowedTypes)) {
            $errors['photo'] = 'Only JPG, JPEG, PNG, and GIF files are allowed';
        } elseif ($fileSize > 5242880) {
            $errors['photo'] = 'File size must be less than 5MB';
        } else {
            $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $fileName = 'customer_' . time() . '_' . uniqid() . '.' . $extension;

            // Save customer profile photos under Customer/Uploads so Customer dashboard can display them.
            $customerUploadsDir = __DIR__ . '/../../Customer/Uploads/';
            if (!file_exists($customerUploadsDir)) {
                mkdir($customerUploadsDir, 0777, true);
            }

            $uploadPath = $customerUploadsDir . $fileName;

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
                // Path stored in DB is used by Customer views (relative to Customer/View).
                $photoPath = '../Uploads/' . $fileName;
            } else {
                $errors['photo'] = 'Failed to upload photo';
            }
        }
    } else {
        $errors['photo'] = 'Error uploading file';
    }
}

if (count($errors) > 0) {
    $_SESSION['errors'] = $errors;
    $_SESSION['previousValues'] = ['email' => $email, 'name' => $name, 'phone' => $phone, 'nid' => $nid];
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
    // Get the newly created Login ID
    $loginID = $connection->insert_id;
    
    // Encrypt password for Customer table (same as Login)
    $encryptedPassword = $db->caesarCipher($password, 5);
    
    // Insert into Customer table
    $db->insertCustomer($connection, $loginID, $email, $encryptedPassword, $name, $phone, $nid, $photoPath);
    
    $_SESSION['signupSuccess'] = 'Account created successfully! Please login.';
    header('Location: ../View/login.php');
} else {
    $_SESSION['signupErr'] = 'Something went wrong. Please try again.';
    $_SESSION['previousValues'] = ['email' => $email, 'name' => $name, 'phone' => $phone, 'nid' => $nid];
    header('Location: ../View/signup.php');
}

$db->closeConnection($connection);


?>