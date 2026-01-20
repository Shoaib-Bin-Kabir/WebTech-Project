<?php
session_start();

if (!isset($_SESSION['isLoggedIn']) || !$_SESSION['isLoggedIn']) {
    header('Location: ../../Login and Signup/View/login.php');
    exit();
}

if ($_SESSION['user_type'] !== 'Seller') {
     header('Location: ../../Login and Signup/View/login.php');
    exit();
}

include "../Model/DBConnectr.php";

$userEmail = $_SESSION['email'];
$userType = $_SESSION['user_type'];

$db = new DBConnectr();
$connection = $db->openConnection();
$historyResult = $db->getHistoryByEmail($connection, $userEmail);
$db->closeConnection($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller History</title>
</head>
<body>
    <h1>My Action History</h1>
    
    <div>
        <nav>
            <ul>
                <li><a href="SHomePage.php">Home Page</a></li>
                <li><a href="addProduct.php">Add Product</a></li>
                <li><a href="editInventory.php">Edit Inventory</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="../../Login and Signup/Controller/logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>

    <div>
        <?php if ($historyResult->num_rows > 0): ?>
            <table border="1" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Action</th>
                        <th>Change</th>
                        <th>Value</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($history = $historyResult->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($history['id']); ?></td>
                            <td><?php echo htmlspecialchars($history['user_name'] ?? $history['user_email']); ?></td>
                            <td><?php echo htmlspecialchars($history['action_type']); ?></td>
                            <td><?php echo htmlspecialchars($history['target']); ?></td>
                            <td>
                                <?php 
                                if ($history['old_value'] !== NULL && $history['new_value'] !== NULL) {
                                    echo 'From ' . htmlspecialchars($history['old_value']) . ' to ' . htmlspecialchars($history['new_value']);
                                } else {
                                    echo '-';
                                }
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($history['created_at']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No history found. Your actions will appear here.</p>
        <?php endif; ?>
    </div>
</body>
</html>