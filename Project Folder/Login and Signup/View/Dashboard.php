<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['isLoggedIn']) || !$_SESSION['isLoggedIn']) {
    // Not logged in, send back to login
    header('Location: ./login.php');
    exit();
}

$userEmail = $_SESSION['email'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome to Dashboard!</h1>
    <h2>Hello, <?php echo htmlspecialchars($userEmail); ?></h2>
    
    <p>You are successfully logged in.</p>
    
    <a href="../Controller/logout.php">Logout</a>
</body>
</html>