<?php
session_start();

require_once '../Model/DBConnectr.php';

if (!isset($_SESSION['isLoggedIn']) || $_SESSION['user_type'] !== 'Admin') {
    header('Location: ../../Login and Signup/View/login.php');
    exit();
}

unset($_SESSION['errors']);
unset($_SESSION['previousValues']);
$errors = [];

$adminEmail = $_SESSION['email'];

// Validate Product Name
if (empty($_POST['pname'])) {
    $errors['pname'] = 'Product Name is required';
} elseif (strlen($_POST['pname']) < 3) {
    $errors['pname'] = 'Product Name must be at least 3 characters long';
} else {
    // Check if product name already exists in the shop
    $db = new DBConnectr();
    $connection = $db->openConnection();
    
    $existingProduct = $db->checkProductNameExists($connection, trim($_POST['pname']));
    
    if ($existingProduct->num_rows > 0) {
        $errors['pname'] = 'A product with this name already exists in the shop';
    }
    
    $db->closeConnection($connection);
}

// Validate Category
if (empty($_POST['pdesc'])) {
    $errors['pdesc'] = 'Product Category is required';
}

// Validate Price
if (empty($_POST['pprice'])) {
    $errors['pprice'] = 'Product Price is required';
} elseif (!is_numeric($_POST['pprice']) || floatval($_POST['pprice']) <= 0) {
    $errors['pprice'] = 'Product Price must be a positive number';  
}

// Validate Quantity
if (empty($_POST['pquantity'])) {
    $errors['pquantity'] = 'Product Quantity is required';
} elseif (!ctype_digit($_POST['pquantity']) || intval($_POST['pquantity']) < 0) {
    $errors['pquantity'] = 'Product Quantity must be a non-negative integer';  
}

// Validate Photo
if (!isset($_FILES['pPhoto']) || $_FILES['pPhoto']['error'] === UPLOAD_ERR_NO_FILE) {
    $errors['pPhoto'] = 'Product Photo is required';
} else {
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    $fileType = $_FILES['pPhoto']['type'];
    
    if (!in_array($fileType, $allowedTypes)) {
        $errors['pPhoto'] = 'Only JPG, JPEG, PNG, and GIF files are allowed';
    }
    
    if ($_FILES['pPhoto']['size'] > 5 * 1024 * 1024) {
        $errors['pPhoto'] = 'File size must be less than 5MB';
    }
}

// Store previous values
$_SESSION['previousValues'] = [
    'pname' => $_POST['pname'] ?? '',
    'pdesc' => $_POST['pdesc'] ?? '',
    'pprice' => $_POST['pprice'] ?? '',
    'pquantity' => $_POST['pquantity'] ?? ''
];

// If errors, redirect back
if (count($errors) > 0) {
    $_SESSION['errors'] = $errors;
    header('Location: ../View/ManInventory.php');
    exit();
}

// Upload photo
$uploadDir = '../../Product Photos/';

if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$fileExtension = pathinfo($_FILES['pPhoto']['name'], PATHINFO_EXTENSION);
$fileName = uniqid('prod_') . '_' . time() . '.' . $fileExtension;
$uploadPath = $uploadDir . $fileName;

if (!move_uploaded_file($_FILES['pPhoto']['tmp_name'], $uploadPath)) {
    $_SESSION['errors']['pPhoto'] = 'Failed to upload file';
    header('Location: ../View/ManInventory.php');
    exit();
}

// Insert product
$db = new DBConnectr();
$connection = $db->openConnection();

$productName = trim($_POST['pname']);
$category = $_POST['pdesc'];
$price = floatval($_POST['pprice']);
$quantity = intval($_POST['pquantity']);
$photoPath = 'Product Photos/' . $fileName;

$result = $db->insertProduct($connection, $adminEmail, $productName, $category, $price, $quantity, $photoPath);

$db->closeConnection($connection);

if ($result) {
    $_SESSION['addProductSuccess'] = 'Product added successfully to shop inventory!';
    unset($_SESSION['previousValues']);
    header('Location: ../View/ManInventory.php');
} else {
    $_SESSION['addProductErr'] = 'Something went wrong. Please try again.';
    header('Location: ../View/ManInventory.php');
}

exit();
?>