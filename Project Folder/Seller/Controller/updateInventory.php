<?php
session_start();

if (!isset($_SESSION['isLoggedIn']) || !$_SESSION['isLoggedIn']) {
    header('Location: ../../Login and Signup/View/login.php');
    exit();
}

if ($_SESSION['user_type'] !== 'Seller') {
    header('Location: ../../Login and Signup/View/login.php');
    exit();
}

include "../Model/DBConnectr.php";

$updateType = $_POST['updateType'] ?? '';
$productId = $_POST['productId'] ?? '';

$error = '';
$success = false;

$db = new DBConnectr();
$connection = $db->openConnection();

// Verify product exists
$productCheck = $db->getProductByIdOnly($connection, $productId);

if ($productCheck->num_rows == 0) {
    $error = 'Product not found';
} else {
    $productData = $productCheck->fetch_assoc();
    
    // Handle Quantity Update
    if ($updateType === 'quantity') {
        $newQuantity = $_POST['quantity'] ?? '';
        
        if (empty($newQuantity) && $newQuantity !== '0') {
            $error = 'Quantity is required';
        } elseif (!is_numeric($newQuantity)) {
            $error = 'Quantity must be a number';
        } elseif ($newQuantity < 0) {
            $error = 'Quantity cannot be negative';
        } elseif (!ctype_digit($newQuantity)) {
            $error = 'Quantity must be a whole number';
        } else {
            $success = $db->updateProductQuantityAll($connection, $productId, $newQuantity);
            if ($success) {
                $_SESSION['inventorySuccess'] = 'Quantity updated successfully';
            }
        }
    }
    
    // Handle Price Update
    elseif ($updateType === 'price') {
        $newPrice = $_POST['price'] ?? '';
        
        if (empty($newPrice)) {
            $error = 'Price is required';
        } elseif (!is_numeric($newPrice)) {
            $error = 'Price must be a number';
        } elseif ($newPrice < 0) {
            $error = 'Price cannot be negative';
        } else {
            $success = $db->updateProductPriceAll($connection, $productId, $newPrice);
            if ($success) {
                $_SESSION['inventorySuccess'] = 'Price updated successfully';
            }
        }
    }
    
    // Handle Photo Update
    elseif ($updateType === 'photo') {
        $uploadFile = $_FILES['productPhoto'] ?? null;
        
        if ($uploadFile && $uploadFile['size'] > 0) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileType = $uploadFile['type'];
            
            if (in_array($fileType, $allowedTypes)) {
                // Delete old photo if exists
                $oldPhotoPath = "../../" . $productData['product_photo'];
                if (!empty($productData['product_photo']) && file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath);
                }
                
                // Save new photo
                $fileExtension = pathinfo($uploadFile['name'], PATHINFO_EXTENSION);
                $newFileName = $productId . "_" . time() . "." . $fileExtension;
                $targetDir = "../../Product Photos/";
                
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                
                $targetPath = $targetDir . $newFileName;
                
                if (move_uploaded_file($uploadFile['tmp_name'], $targetPath)) {
                    $dbPath = "Product Photos/" . $newFileName;
                    $success = $db->updateProductPhotoAll($connection, $productId, $dbPath);
                    if ($success) {
                        $_SESSION['inventorySuccess'] = 'Photo updated successfully';
                    }
                } else {
                    $error = 'Failed to upload photo';
                }
            } else {
                $error = 'Invalid file type. Only JPEG, PNG, and GIF are allowed';
            }
        } else {
            $error = 'Please select a photo to upload';
        }
    }
    
    // Handle Delete Product
    elseif ($updateType === 'delete') {
        // Delete photo file first
        $photoPath = "../../" . $productData['product_photo'];
        if (!empty($productData['product_photo']) && file_exists($photoPath)) {
            unlink($photoPath);
        }
        
        // Delete from database
        $success = $db->deleteProductAll($connection, $productId);
        if ($success) {
            $_SESSION['inventorySuccess'] = 'Product deleted successfully';
        } else {
            $error = 'Failed to delete product';
        }
    }
}

$db->closeConnection($connection);

// Store error message in session
if (!empty($error)) {
    $_SESSION['inventoryError'] = $error;
}

header('Location: ../View/editInventory.php');
exit();
?>