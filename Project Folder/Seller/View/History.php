<?php
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
    <title>Seller History</title>
    <link rel="stylesheet" href="Design/seller.css">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="profile-section">
                <div class="profile-placeholder">S</div>
                <div class="welcome-text">Seller Dashboard</div>
                <div class="user-email"><?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?></div>
            </div>
            <div class="actions-section">
                <a class="action-btn" href="SHomePage.php">Home</a>
                <a class="action-btn" href="addProduct.php">Add Product</a>
                <a class="action-btn" href="editInventory.php">Edit Inventory</a>
                <a class="action-btn" href="History.php">History</a>
                <a class="action-btn" href="profile.php">Profile</a>
                <a class="action-btn logout" href="../../Login and Signup/Controller/logout.php">Logout</a>
            </div>
        </aside>

        <main class="main-content">
            <h1>History</h1>
            <p>Future Edits Seller Product Update History</p>
        </main>
    </div>
</body>
</html>