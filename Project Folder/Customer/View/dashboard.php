<?php
require_once __DIR__ . '/../Controller/customer_auth.php';

include "../Model/DBConnectr.php";

$db = new DBConnectr();
$connection = $db->openConnection();

$email = $_SESSION['email'];


$selectedCategory = isset($_GET['category']) ? trim($_GET['category']) : '';


$result = $db->getCustomerByEmail($connection, $email);
$customer = $result->fetch_assoc();

$customerId = (int) (($customer['ID'] ?? $customer['LoginID'] ?? 0));


$products = [];
$productsResult = $db->getAllProducts($connection, $selectedCategory);
if ($productsResult) {
    while ($row = $productsResult->fetch_assoc()) {
        $products[] = $row;
    }
}

$cartCount = ($customerId > 0) ? $db->getCartItemCount($connection, $customerId) : 0;

$db->closeConnection($connection);

$displayName = (!empty($customer['Name'])) ? $customer['Name'] : $email;


$isEditing = isset($_GET['edit']) && $_GET['edit'] === '1';


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
                <div class="actions-row">
                    <a href="dashboard.php?edit=1" class="action-btn <?php echo $isEditing ? 'active' : ''; ?>">Edit Profile</a>
                    <a href="dashboard.php" class="action-btn <?php echo !$isEditing ? 'active' : ''; ?>">Home</a>
                </div>
                <a href="cart.php" class="action-btn">Cart (<?php echo (int) $cartCount; ?>)</a>
                <a href="orderHistory.php" class="action-btn">Order History</a>
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
