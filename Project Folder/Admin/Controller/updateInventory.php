<?php
session_start();

if (!isset($_SESSION['isLoggedIn']) || $_SESSION['user_type'] !== 'Admin') {
    header('Location: ../../Login and Signup/View/login.php');
    exit();
}

include "../Model/DBConnectr.php";

$updateType = $_POST['updateType'];
$productId = $_POST['productId'];

$db = new DBConnectr();
$connection = $db->openConnection();

$productCheck = $db->getProductByIdOnly($connection, $productId);

if ($productCheck->num_rows == 0) {
    $_SESSION['inventoryError'] = 'Product not found';
    header('Location: ../View/ManInventory.php');
    exit();
}

$productData = $productCheck->fetch_assoc();

if ($updateType === 'quantity') {
    $newQuantity = $_POST['quantity'];
    
    $result = $db->updateProductQuantityAll($connection, $productId, $newQuantity);
    if ($result) {
        $_SESSION['inventorySuccess'] = 'Quantity updated successfully';
    }
}

elseif ($updateType === 'price') {
    $newPrice = $_POST['price'];
    
    $result = $db->updateProductPriceAll($connection, $productId, $newPrice);
    if ($result) {
        $_SESSION['inventorySuccess'] = 'Price updated successfully';
    }
}

elseif ($updateType === 'photo') {
    $uploadFile = $_FILES['productPhoto'];
    
    if ($uploadFile && $uploadFile['size'] > 0) {
        $oldPhotoPath = "../../" . $productData['product_photo'];
        if (!empty($productData['product_photo']) && file_exists($oldPhotoPath)) {
            unlink($oldPhotoPath);
        }
        
        $fileExtension = pathinfo($uploadFile['name'], PATHINFO_EXTENSION);
        $newFileName = $productId . "_" . time() . "." . $fileExtension;
        $targetDir = "../../Product Photos/";
        
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        
        $targetPath = $targetDir . $newFileName;
        
        if (move_uploaded_file($uploadFile['tmp_name'], $targetPath)) {
            $dbPath = "Product Photos/" . $newFileName;
            $result = $db->updateProductPhotoAll($connection, $productId, $dbPath);
            if ($result) {
                $_SESSION['inventorySuccess'] = 'Photo updated successfully';
            }
        }
    }
}

elseif ($updateType === 'delete') {
    $photoPath = "../../" . $productData['product_photo'];
    if (!empty($productData['product_photo']) && file_exists($photoPath)) {
        unlink($photoPath);
    }
    
    $result = $db->deleteProductAll($connection, $productId);
    if ($result) {
        $_SESSION['inventorySuccess'] = 'Product deleted successfully';
    }
}

$db->closeConnection($connection);

header('Location: ../View/ManInventory.php');
exit();
?>