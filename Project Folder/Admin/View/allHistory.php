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

include "../Model/DBConnectr.php";

$userEmail = $_SESSION['email'];
$userType = $_SESSION['user_type'];

$db = new DBConnectr();
$connection = $db->openConnection();
$historyResult = $db->getAllHistory($connection);
$ordersResult = $db->getAllOrders($connection);
$db->closeConnection($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History</title>
    <link rel="stylesheet" href="../Design/admin.css">
    <script src="../Controller/JS/searchHistory.js"></script>
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="profile-section">
                <div class="profile-placeholder">A</div>
                <div class="welcome-text">Admin Dashboard</div>
                <div class="user-email"><?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?></div>
            </div>
            <div class="actions-section">
                <a class="action-btn" href="AHomePage.php">Home</a>
                <a class="action-btn" href="allHistory.php">History</a>
                <a class="action-btn" href="ManInventory.php">Manage Inventory</a>
                <a class="action-btn" href="ManSeller.php">Manage Seller</a>
                <a class="action-btn" href="profile.php">Profile</a>
                <a class="action-btn logout" href="../../Login and Signup/Controller/logout.php">Logout</a>
            </div>
        </aside>

        <main class="main-content">
            <h1>All User History</h1>

          
            <div style="margin: 20px 0; padding: 15px; background-color: #f5f5f5; border: 1px solid #ddd;">
                <label for="searchSeller"><strong>Search by Name:</strong></label>
                <input type="text" 
                       id="searchSeller" 
                       name="searchSeller" 
                       placeholder="Type name..." 
                       onkeyup="searchHistory()" 
                       style="padding: 8px; margin-left: 10px; width: 300px; border: 1px solid #ccc; border-radius: 4px;">
                <span id="searchStatus" style="margin-left: 10px; color: #666; font-size: 14px;"></span>
            </div>

           
            <div id="historyContainer">
            <?php if ($historyResult->num_rows > 0): ?>
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
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
                                <td><?php echo htmlspecialchars($history['user_email']); ?></td>
                                <td><?php echo htmlspecialchars($history['user_name'] ?? '-'); ?></td>
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
                <p class="no-history">No history found.</p>
            <?php endif; ?>
            </div>

            <button class="action-btn" id="toggleHistoryBtn" onclick="toggleHistory()" style="width: 150px;">Hide History</button>

            <button class="action-btn" id="toggleOrdersBtn" onclick="toggleOrders()" style="margin-top: 15px; width: 150px;">Show Orders</button>

            <div id="ordersSection" style="display: none; margin-top: 20px;">
                <h2>All Customer Orders</h2>
                <?php if ($ordersResult->num_rows > 0): ?>
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer ID</th>
                                <th>Product ID</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Shipping Address</th>
                                <th>Order Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($order = $ordersResult->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($order['id']); ?></td>
                                    <td><?php echo htmlspecialchars($order['customer_id']); ?></td>
                                    <td><?php echo htmlspecialchars($order['product_id']); ?></td>
                                    <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                                    <td><?php echo htmlspecialchars($order['price']); ?> BDT</td>
                                    <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                                    <td><?php echo htmlspecialchars($order['shipping_address']); ?></td>
                                    <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="no-history">No orders found yet.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>
        function toggleHistory() {
            var historyTable = document.querySelector('.history-table');
            var noHistoryMsg = document.querySelector('.no-history');
            var btn = document.getElementById('toggleHistoryBtn');
            
            if (historyTable) {
                if (historyTable.style.display === 'none') {
                    historyTable.style.display = 'table';
                    btn.textContent = 'Hide History';
                } else {
                    historyTable.style.display = 'none';
                    btn.textContent = 'Show History';
                }
            } else if (noHistoryMsg) {
                if (noHistoryMsg.style.display === 'none') {
                    noHistoryMsg.style.display = 'block';
                    btn.textContent = 'Hide History';
                } else {
                    noHistoryMsg.style.display = 'none';
                    btn.textContent = 'Show History';
                }
            }
        }

        function toggleOrders() {
            var ordersSection = document.getElementById('ordersSection');
            var btn = document.getElementById('toggleOrdersBtn');
            
            if (ordersSection.style.display === 'none') {
                ordersSection.style.display = 'block';
                btn.textContent = 'Hide Orders';
            } else {
                ordersSection.style.display = 'none';
                btn.textContent = 'Show Orders';
            }
        }
    </script>

</body>
</html>