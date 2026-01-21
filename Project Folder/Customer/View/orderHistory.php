<?php
require_once __DIR__ . '/../Controller/customer_auth.php';

include "../Model/DBConnectr.php";

$db = new DBConnectr();
$connection = $db->openConnection();

$email = $_SESSION['email'];
$result = $db->getCustomerByEmail($connection, $email);
$customer = $result ? $result->fetch_assoc() : [];

$customerId = (int) (($customer['ID'] ?? $customer['LoginID'] ?? 0));
if ($customerId <= 0) {
    $db->closeConnection($connection);
    header('Location: dashboard.php');
    exit();
}

$cartCount = $db->getCartItemCount($connection, $customerId);

$orders = [];
$ordersRes = $db->getOrderGroupsByCustomer($connection, $customerId);
if ($ordersRes) {
    while ($row = $ordersRes->fetch_assoc()) {
        $orders[] = $row;
    }
}

$db->closeConnection($connection);

$displayName = (!empty($customer['Name'])) ? $customer['Name'] : $email;
$orderSuccess = isset($_SESSION['orderSuccess']) ? (string) $_SESSION['orderSuccess'] : '';
unset($_SESSION['orderSuccess']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order History</title>
    <link rel="stylesheet" href="Design/dashboard.css?v=2">
    <link rel="stylesheet" href="Design/cart.css?v=1">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="profile-section">
                <?php if (!empty($customer['Photo'])): ?>
                    <img src="<?php echo htmlspecialchars($customer['Photo']); ?>" alt="Profile Photo" class="profile-photo">
                <?php else: ?>
                    <div class="profile-placeholder">👤</div>
                <?php endif; ?>
                <div class="welcome-text"><?php echo htmlspecialchars($displayName); ?></div>
                <div class="user-email"><?php echo htmlspecialchars($customer['Email'] ?? $email); ?></div>
            </div>

            <div class="info-section">
                <h3>Your Information</h3>
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
            </div>

            <div class="actions-section">
                <div class="actions-row">
                    <a href="dashboard.php?edit=1" class="action-btn">Edit Profile</a>
                    <a href="dashboard.php" class="action-btn">Home</a>
                </div>
                <a href="cart.php" class="action-btn">Cart (<?php echo (int) $cartCount; ?>)</a>
                <a href="orderHistory.php" class="action-btn active">Order History</a>
                <a href="../../Login and Signup/Controller/logout.php" class="action-btn logout">Logout</a>
            </div>
        </div>

        <div class="main-content">
            <div class="main-header">
                <h1>Order History</h1>
                <p>Your previous orders</p>
            </div>

            <?php if ($orderSuccess !== ''): ?>
                <p class="products-empty"><?php echo htmlspecialchars($orderSuccess); ?></p>
            <?php endif; ?>

            <?php if (count($orders) === 0): ?>
                <p class="products-empty">No orders yet.</p>
            <?php else: ?>
                <?php
                    // Re-open a connection for fetching order items (simple approach)
                    $connection = $db->openConnection();
                ?>

                <?php foreach ($orders as $o): ?>
                    <?php
                        $orderGroup = (string) ($o['order_group'] ?? '');
                        $items = [];
                        if ($orderGroup !== '') {
                            $itemsRes = $db->getOrderItemsByGroup($connection, $orderGroup);
                            if ($itemsRes) {
                                while ($r = $itemsRes->fetch_assoc()) {
                                    $items[] = $r;
                                }
                            }
                        }

                        $total = (float) ($o['total_amount'] ?? 0);
                        $discount = 0.0;
                        $payable = $total;
                        $addr = (string) ($o['shipping_address'] ?? '');
                        $createdAt = (string) ($o['created_at'] ?? '');
                    ?>

                    <div class="cart-panel" style="margin-bottom: 16px;">
                        <div style="margin-bottom: 10px;">
                            <?php if ($createdAt !== ''): ?>
                                <strong>Date:</strong> <?php echo htmlspecialchars($createdAt); ?>
                            <?php endif; ?>
                        </div>

                        <div style="margin-bottom: 10px;">
                            <strong>Total:</strong> <?php echo number_format($total, 2); ?>
                            <span style="margin-left: 10px;"><strong>Payable:</strong> <?php echo number_format($payable, 2); ?></span>
                        </div>

                        <div style="margin-bottom: 10px;">
                            <strong>Shipping Address:</strong>
                            <div><?php echo $addr !== '' ? nl2br(htmlspecialchars($addr)) : 'Not provided'; ?></div>
                        </div>

                        <?php if (count($items) === 0): ?>
                            <p class="products-empty">No items found for this order.</p>
                        <?php else: ?>
                            <div class="cart-header-row">
                                <div class="cart-col-product">Product</div>
                                <div class="cart-col-price">Price</div>
                                <div class="cart-col-qty">Qty</div>
                                <div class="cart-col-subtotal">Subtotal</div>
                                <div class="cart-col-action"></div>
                            </div>

                            <?php foreach ($items as $it): ?>
                                <?php
                                    $name = (string) ($it['product_name'] ?? '');
                                    $price = (float) ($it['price'] ?? 0);
                                    $qty = (int) ($it['quantity'] ?? 0);
                                    $sub = $price * $qty;
                                ?>
                                <div class="cart-row">
                                    <div class="cart-col-product"><?php echo htmlspecialchars($name); ?></div>
                                    <div class="cart-col-price"><?php echo number_format($price, 2); ?></div>
                                    <div class="cart-col-qty"><?php echo (int) $qty; ?></div>
                                    <div class="cart-col-subtotal"><?php echo number_format($sub, 2); ?></div>
                                    <div class="cart-col-action"></div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <?php $db->closeConnection($connection); ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
