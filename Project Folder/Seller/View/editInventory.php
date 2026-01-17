<<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['isLoggedIn']) || !$_SESSION['isLoggedIn']) {
    // Not logged in, send back to login
    header('Location: ../../Login and Signup/View/login.php');
    exit();
}

// Check if user is a Seller (not a Customer)
if ($_SESSION['user_type'] !== 'Seller') {
    // Customer trying to access seller pages - redirect them
     header('Location: ../../Login and Signup/View/login.php');
    exit();
}

$userEmail = $_SESSION['email'];
$userType = $_SESSION['user_type'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Inventory Seller</title>
</head>
<body>
     <div>
    <nav>
        <ul>
            <li><a href="SHomePage.php">Home Page</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="addProduct.php">Add Product</a></li>
            <li><a href="History.php">History</a></li>
            <li><a href="../../Login and Signup/Controller/logout.php">Logout</a></li>
        </ul>
    </nav>

    </div>

    <div>
        <p>Edit Inventory Page</p>
    </div>

    <div>
        <p>Future Edits Seller Product Inventory</p>
    </div>


</body>
</html>