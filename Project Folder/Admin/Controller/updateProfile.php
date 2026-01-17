<?php
session_start();

if (!isset($_SESSION['isLoggedIn']) || !$_SESSION['isLoggedIn']) {
    header('Location: ../../Login and Signup/View/login.php');
    exit();
}

if ($_SESSION['user_type'] !== 'Admin') {
    header('Location: ../../Login and Signup/View/login.php');
    exit();
}

include "../Model/DBConnectr.php";

$adminEmail = $_SESSION['email'];
$updateType = $_POST['updateType'] ?? '';

$db = new DBConnectr();
$connection = $db->openConnection();

$result = $db->getAdminByEmail($connection, $adminEmail);
$adminData = $result->fetch_assoc();
$adminId = $adminData['ID'];

$success = false;
$error = '';

if ($updateType === 'name') {
    $newName = $_POST['name'] ?? '';
    
    // Validation: minimum 3 letters, only letters
    if (empty($newName)) {
        $error = 'Name is required';
    } elseif (strlen($newName) < 3) {
        $error = 'Name must be at least 3 characters long';
    } elseif (!preg_match('/^[a-zA-Z\s]+$/', $newName)) {
        $error = 'Name can only contain letters and spaces';
    } else {
        $success = $db->updateAdminName($connection, $adminEmail, $newName);
    }
} 
elseif ($updateType === 'nid') {
    $newNID = $_POST['nid'] ?? '';
    
    // Validation: maximum 10 digits, numbers only
    if (empty($newNID)) {
        $error = 'NID is required';
    } elseif (!preg_match('/^[0-9]+$/', $newNID)) {
        $error = 'NID must contain only numbers';
    } elseif (strlen($newNID) > 10) {
        $error = 'NID cannot be greater than 10 digits';
    } else {
        $success = $db->updateAdminNID($connection, $adminEmail, $newNID);
    }
} 
elseif ($updateType === 'email') {
    $newEmail = $_POST['email'] ?? '';
    
    // Basic email validation
    if (empty($newEmail)) {
        $error = 'Email is required';
    } elseif (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format';
    } else {
        $success = $db->updateAdminEmail($connection, $adminEmail, $newEmail);
        if ($success) {
            $_SESSION['email'] = $newEmail;
        }
    }
} 
elseif ($updateType === 'phone') {
    $newPhone = $_POST['phone'] ?? '';
    
    // Validation: exactly 11 digits, must start with 013/014/015/016/017/018/019
    if (empty($newPhone)) {
        $error = 'Phone number is required';
    } elseif (!preg_match('/^[0-9]+$/', $newPhone)) {
        $error = 'Phone number must contain only numbers';
    } elseif (strlen($newPhone) != 11) {
        $error = 'Phone number must be exactly 11 digits';
    } elseif (!preg_match('/^01[3-9][0-9]{8}$/', $newPhone)) {
        $error = 'Phone number must start with 013, 014, 015, 016, 017, 018, or 019';
    } else {
        $success = $db->updateAdminPhone($connection, $adminEmail, $newPhone);
    }
} 
elseif ($updateType === 'photo') {
    $uploadFile = $_FILES['photo'] ?? null;
    
    if ($uploadFile && $uploadFile['size'] > 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = $uploadFile['type'];
        
        if (in_array($fileType, $allowedTypes)) {
            $fileExtension = pathinfo($uploadFile['name'], PATHINFO_EXTENSION);
            $newFileName = $adminId . "." . $fileExtension;
            $targetDir = "../Admin Photo/";
            
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            $targetPath = $targetDir . $newFileName;
            
            if (move_uploaded_file($uploadFile['tmp_name'], $targetPath)) {
                $dbPath = "Admin Photo/" . $newFileName;
                $success = $db->updateAdminPhoto($connection, $adminEmail, $dbPath);
            }
        } else {
            $error = 'Invalid file type. Only JPEG, PNG, and GIF are allowed';
        }
    } else {
        $error = 'Please select a photo to upload';
    }
}

$db->closeConnection($connection);

// Store error or success message in session
if (!empty($error)) {
    $_SESSION['updateError'] = $error;
} elseif ($success) {
    $_SESSION['updateSuccess'] = 'Profile updated successfully';
}

header('Location: ../View/profile.php');
exit();
?>