<?php

session_start();


if (!isset($_SESSION['isLoggedIn']) || !$_SESSION['isLoggedIn'] || $_SESSION['user_type'] !== 'Customer') {
    header('Location: ../../Login and Signup/View/login.php');
    exit();
}

include "../Model/DBConnectr.php";

error_reporting(E_ALL);
ini_set("display_errors", 1);

$name = $_POST['name'] ?? '';
$phone = $_POST['phone'] ?? '';
$nid = $_POST['nid'] ?? '';
$removePhotoRequested = isset($_POST['remove_photo']) && $_POST['remove_photo'] === '1';

unset($_SESSION['errors']);
unset($_SESSION['successMessage']);
unset($_SESSION['previousValues']);

$errors = [];

// Validation
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


$photoPath = null;
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
            $uploadDir = '../Uploads/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $fileName = 'customer_' . time() . '_' . uniqid() . '.' . $extension;
            $uploadPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
                $photoPath = $uploadPath;
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
    $_SESSION['previousValues'] = ['name' => $name, 'phone' => $phone, 'nid' => $nid];
    header('Location: ../View/dashboard.php?edit=1');
    exit();
}


$db = new DBConnectr();
$connection = $db->openConnection();

$email = $_SESSION['email'];
$result = $db->getCustomerByEmail($connection, $email);
$customer = $result->fetch_assoc();
$customerID = $customer['ID'];
$currentPhotoPath = $customer['Photo'] ?? '';

$updateResult = $db->updateCustomer($connection, $customerID, $name, $phone, $nid);

if ($photoPath !== null) {
    $db->updateCustomerPhoto($connection, $customerID, $photoPath);

    if (!empty($currentPhotoPath) && $currentPhotoPath !== $photoPath) {
        $uploadsDir = realpath(__DIR__ . '/../Uploads');
        $oldFile = realpath(__DIR__ . '/../' . ltrim($currentPhotoPath, '/'));
        if ($uploadsDir && $oldFile && str_starts_with($oldFile, $uploadsDir . DIRECTORY_SEPARATOR) && is_file($oldFile)) {
            @unlink($oldFile);
        }
    }
} elseif ($removePhotoRequested) {
    $db->updateCustomerPhoto($connection, $customerID, '');

    if (!empty($currentPhotoPath)) {
        $uploadsDir = realpath(__DIR__ . '/../Uploads');
        $oldFile = realpath(__DIR__ . '/../' . ltrim($currentPhotoPath, '/'));
        if ($uploadsDir && $oldFile && str_starts_with($oldFile, $uploadsDir . DIRECTORY_SEPARATOR) && is_file($oldFile)) {
            @unlink($oldFile);
        }
    }
}

$db->closeConnection($connection);

if ($updateResult) {
    $_SESSION['successMessage'] = 'Profile updated successfully!';
} else {
    $_SESSION['errors'] = ['general' => 'Failed to update profile'];
    $_SESSION['previousValues'] = ['name' => $name, 'phone' => $phone, 'nid' => $nid];
}

header('Location: ../View/dashboard.php?edit=1');
exit();

?>
