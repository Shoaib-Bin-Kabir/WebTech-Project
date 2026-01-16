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

// Category filter (empty means All)
$selectedCategory = isset($_GET['category']) ? trim($_GET['category']) : '';

// Get customer data
$result = $db->getCustomerByEmail($connection, $email);
$customer = $result->fetch_assoc();

// Get product data
$products = [];
$productsResult = $db->getAllProducts($connection, $selectedCategory);
if ($productsResult) {
    while ($row = $productsResult->fetch_assoc()) {
        $products[] = $row;
    }
}

$db->closeConnection($connection);

$displayName = (!empty($customer['Name'])) ? $customer['Name'] : $email;

// Sidebar edit mode
$isEditing = isset($_GET['edit']) && $_GET['edit'] === '1';

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
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="Design/dashboard.css?v=2">
</head>
<body>
    <div class="container">
        <!-- Left Sidebar -->
        <div class="sidebar">
            <div class="profile-section">
                <?php if (!empty($customer['Photo'])): ?>
                    <img src="<?php echo htmlspecialchars($customer['Photo']); ?>" alt="Profile Photo" class="profile-photo">
                <?php else: ?>
                    <div class="profile-placeholder">👤</div>
                <?php endif; ?>
                <div class="welcome-text"><?php echo htmlspecialchars($displayName); ?></div>
                <div class="user-email"><?php echo htmlspecialchars($customer['Email']); ?></div>
            </div>

            <div class="info-section">
                <h3><?php echo $isEditing ? 'Edit Profile' : 'Your Information'; ?></h3>

                <?php if ($successMessage): ?>
                    <div class="success-message">
                        <?php echo htmlspecialchars($successMessage); ?>
                    </div>
                <?php endif; ?>

                <?php if ($isEditing): ?>
                    <?php include __DIR__ . '/sidebarEditProfile.php'; ?>
                <?php else: ?>
                    <div class="info-item">
                        <div class="info-label">Name</div>
                        <div class="info-value"><?php echo !empty($customer['Name']) ? htmlspecialchars($customer['Name']) : 'Not set'; ?></div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Phone Number</div>
                        <div class="info-value"><?php echo !empty($customer['Phone_Number']) ? htmlspecialchars($customer['Phone_Number']) : 'Not set'; ?></div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">NID</div>
                        <div class="info-value"><?php echo !empty($customer['NID']) ? htmlspecialchars($customer['NID']) : 'Not set'; ?></div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="actions-section">
                <a href="dashboard.php?edit=1" class="action-btn <?php echo $isEditing ? 'active' : ''; ?>">Edit Profile</a>
                <a href="../../Login and Signup/Controller/logout.php" class="action-btn logout">Logout</a>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="main-content">
            <div class="main-header">
                <h1>Welcome Back!</h1>
                <p>Explore our products and find what you need</p>
            </div>

            <?php include __DIR__ . '/productShowcase.php'; ?>
        </div>
    </div>
</body>
</html>
