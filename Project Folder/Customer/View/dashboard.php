<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['isLoggedIn']) || !$_SESSION['isLoggedIn']) {
    header('Location: ../../Login and Signup/View/login.php');
    exit();
}

if ($_SESSION['user_type'] !== 'Customer') {
    header('Location: ../../Login and Signup/View/login.php');
    exit();
}

include "../Model/DBConnectr.php";

$db = new DBConnectr();
$connection = $db->openConnection();

$email = $_SESSION['email'];

// Get customer data
$result = $db->getCustomerByEmail($connection, $email);
$customer = $result->fetch_assoc();

$db->closeConnection($connection);

$displayName = (!empty($customer['Name'])) ? $customer['Name'] : $email;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Dashboard</title>
</head>
<body>
    <h1>Customer Dashboard</h1>
    <h2>Welcome, <?php echo htmlspecialchars($displayName); ?>!</h2>
    
    <div>
        <h3>Your Information:</h3>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($customer['Email']); ?></p>
        <p><strong>Name:</strong> <?php echo !empty($customer['Name']) ? htmlspecialchars($customer['Name']) : 'Not set'; ?></p>
        <p><strong>Phone:</strong> <?php echo !empty($customer['Phone_Number']) ? htmlspecialchars($customer['Phone_Number']) : 'Not set'; ?></p>
        <p><strong>NID:</strong> <?php echo !empty($customer['NID']) ? htmlspecialchars($customer['NID']) : 'Not set'; ?></p>
        <?php if (!empty($customer['Photo'])): ?>
            <p><strong>Photo:</strong></p>
            <img src="<?php echo htmlspecialchars($customer['Photo']); ?>" alt="Profile Photo" width="150">
        <?php else: ?>
            <p><strong>Photo:</strong> Not uploaded</p>
        <?php endif; ?>
    </div>
    
    <div>
        <h3>Actions:</h3>
        <ul>
            <li><a href="editProfile.php">Edit Profile</a></li>
            <li><a href="../../Login and Signup/Controller/logout.php">Logout</a></li>
        </ul>
    </div>
</body>
</html>
