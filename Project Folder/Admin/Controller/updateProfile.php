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

$adminNameForHistory = $adminData['Name'] ?? 'Admin';

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
        if ($success) {
            $db->insertHistory($connection, $adminEmail, $adminNameForHistory, 'Update', 'Profile Name', $adminData['Name'] ?? NULL, $newName);
        }
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
        if ($success) {
            $db->insertHistory($connection, $adminEmail, $adminNameForHistory, 'Update', 'Profile NID', $adminData['NID'] ?? NULL, $newNID);
        }
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
            $db->insertHistory($connection, $adminEmail, $adminNameForHistory, 'Update', 'Profile Email', $adminEmail, $newEmail);
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
        if ($success) {
            $db->insertHistory($connection, $adminEmail, $adminNameForHistory, 'Update', 'Profile Phone', $adminData['Phone_Number'] ?? NULL, $newPhone);
        }
    }
} 
elseif ($updateType === 'photo') {
    $uploadFile = $_FILES['photo'] ?? null;

    if (!$uploadFile) {
        $error = 'Please select a photo to upload';
    } elseif (($uploadFile['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        $uploadError = $uploadFile['error'] ?? UPLOAD_ERR_NO_FILE;
        if ($uploadError === UPLOAD_ERR_INI_SIZE || $uploadError === UPLOAD_ERR_FORM_SIZE) {
            $error = 'Photo is too large to upload';
        } else {
            $error = 'Upload failed. Please try again';
        }
    } elseif (($uploadFile['size'] ?? 0) <= 0) {
        $error = 'Please select a photo to upload';
    } elseif (($uploadFile['size'] ?? 0) > (5 * 1024 * 1024)) {
        $error = 'Photo size must be 5MB or less';
    } else {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        $finfo = function_exists('finfo_open') ? finfo_open(FILEINFO_MIME_TYPE) : null;
        $detectedType = $finfo ? finfo_file($finfo, $uploadFile['tmp_name']) : ($uploadFile['type'] ?? '');
        if ($finfo) {
            finfo_close($finfo);
        }

        if (!in_array($detectedType, $allowedTypes, true)) {
            $error = 'Invalid file type. Only JPEG, PNG, GIF, and WEBP are allowed';
        } else {
            $fileExtension = strtolower(pathinfo($uploadFile['name'], PATHINFO_EXTENSION));
            if ($fileExtension === '' || $fileExtension === 'jpeg') {
                $fileExtension = 'jpg';
            }
            if ($detectedType === 'image/webp') {
                $fileExtension = 'webp';
            } elseif ($detectedType === 'image/png') {
                $fileExtension = 'png';
            } elseif ($detectedType === 'image/gif') {
                $fileExtension = 'gif';
            } elseif ($detectedType === 'image/jpeg') {
                $fileExtension = 'jpg';
            }

            $newFileName = $adminId . "." . $fileExtension;
            $targetDirFs = __DIR__ . "/../Admin Photo/";

            if (!is_dir($targetDirFs)) {
                if (!mkdir($targetDirFs, 0777, true)) {
                    $error = 'Server error: cannot create upload folder';
                }
            }

            if (empty($error) && !is_writable($targetDirFs)) {
                $error = 'Server error: upload folder is not writable';
            }

            if (empty($error)) {
                $targetPathFs = $targetDirFs . $newFileName;

                if (!move_uploaded_file($uploadFile['tmp_name'], $targetPathFs)) {
                    $error = 'Server error: failed to save uploaded photo';
                } else {
                    $dbPath = "Admin Photo/" . $newFileName;
                    $success = $db->updateAdminPhoto($connection, $adminEmail, $dbPath);
                    if (!$success) {
                        $error = 'Server error: failed to update photo in database';
                    } else {
                        $db->insertHistory($connection, $adminEmail, $adminNameForHistory, 'Update', 'Profile Photo', $adminData['Photo'] ?? NULL, $dbPath);
                    }
                }
            }
        }
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