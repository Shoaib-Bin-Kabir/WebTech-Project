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
$result = $db->getCustomerByEmail($connection, $email);
$customer = $result->fetch_assoc();

$db->closeConnection($connection);

// Get errors and messages
$errors = $_SESSION['errors'] ?? [];
$successMessage = $_SESSION['successMessage'] ?? '';
$previousValues = $_SESSION['previousValues'] ?? [];

unset($_SESSION['errors']);
unset($_SESSION['successMessage']);
unset($_SESSION['previousValues']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
</head>
<body>
    <h1>Edit Profile</h1>
    
    <?php if ($successMessage): ?>
        <div style="color: green; border: 1px solid green; padding: 10px; margin-bottom: 10px;">
            <?php echo htmlspecialchars($successMessage); ?>
        </div>
    <?php endif; ?>
    
    <form action="../Controller/updateProfile.php" method="POST" enctype="multipart/form-data">
        
        <div>
            <label for="email">Email (Cannot be changed):</label><br>
            <input type="text" id="email" value="<?php echo htmlspecialchars($customer['Email']); ?>" readonly disabled>
        </div>
        <br>
        
        <div>
            <label for="name">Name:<span style="color: red;">*</span></label><br>
            <input type="text" id="name" name="name" value="<?php echo isset($previousValues['name']) ? htmlspecialchars($previousValues['name']) : htmlspecialchars($customer['Name'] ?? ''); ?>" required>
            <?php if (isset($errors['name'])): ?>
                <span style="color: red;"><?php echo $errors['name']; ?></span>
            <?php endif; ?>
        </div>
        <br>
        
        <div>
            <label for="phone">Phone Number:<span style="color: red;">*</span></label><br>
            <input type="text" id="phone" name="phone" value="<?php echo isset($previousValues['phone']) ? htmlspecialchars($previousValues['phone']) : htmlspecialchars($customer['Phone_Number'] ?? ''); ?>" required>
            <?php if (isset($errors['phone'])): ?>
                <span style="color: red;"><?php echo $errors['phone']; ?></span>
            <?php endif; ?>
        </div>
        <br>
        
        <div>
            <label for="nid">NID:<span style="color: red;">*</span></label><br>
            <input type="text" id="nid" name="nid" value="<?php echo isset($previousValues['nid']) ? htmlspecialchars($previousValues['nid']) : htmlspecialchars($customer['NID'] ?? ''); ?>" required>
            <?php if (isset($errors['nid'])): ?>
                <span style="color: red;"><?php echo $errors['nid']; ?></span>
            <?php endif; ?>
        </div>
        <br>
        
        <div>
            <label for="photo">Profile Photo:</label><br>
            <?php if (!empty($customer['Photo'])): ?>
                <img src="<?php echo htmlspecialchars($customer['Photo']); ?>" alt="Current Photo" width="100"><br>
                <small>Current Photo</small><br>
            <?php endif; ?>
            <input type="file" id="photo" name="photo" accept="image/*">
            <?php if (isset($errors['photo'])): ?>
                <span style="color: red;"><?php echo $errors['photo']; ?></span>
            <?php endif; ?>
        </div>
        <br>
        
        <div>
            <button type="submit">Update Profile</button>
            <a href="dashboard.php"><button type="button">Cancel</button></a>
        </div>
    </form>
    
    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
