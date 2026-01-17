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

$userEmail = $_SESSION['email'];
$userType = $_SESSION['user_type'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History</title>
</head>
<body>
    <h1>History</h1>
    <div>
    <nav>
        <ul>
            <li><a href="AHomePage.php">Home Page</a></li>
            <li><a href="ManInventory.php">Manage Inventory</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="ManSeller.php">Manage Seller</a></li>
            <li><a href="../../Login and Signup/Controller/logout.php">Logout</a></li>
        </ul>
    </nav>

    </div>


   

</body>
</html>