<?php
session_start();

if (!isset($_SESSION['isLoggedIn']) || !$_SESSION['isLoggedIn']) {
    header('Location: ../../Login and Signup/View/login.php');
    exit();
}

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Customer') {
    header('Location: ../../Login and Signup/View/login.php');
    exit();
}


include "../Model/DBConnectr.php";

unset($_SESSION['buy_now']);
$db = new DBConnectr();
$connection = $db->openConnection();

$email = $_SESSION['email'];
$result = $db->getCustomerByEmail($connection, $email);
$customer = $result ? $result->fetch_assoc() : [];

$customerId = (int) (($customer['ID'] ?? $customer['LoginID'] ?? 0));
$cartCount = ($customerId > 0) ? $db->getCartItemCount($connection, $customerId) : 0;

$items = [];
$cartRes = ($customerId > 0) ? $db->getCartItems($connection, $customerId) : null;
if ($cartRes) {
    while ($row = $cartRes->fetch_assoc()) {
        $items[] = $row;
    }
}

$db->closeConnection($connection);

$displayName = (!empty($customer['Name'])) ? $customer['Name'] : $email;

$coupon = isset($_SESSION['cart_coupon']) ? trim((string) $_SESSION['cart_coupon']) : '';
$shippingAddress = isset($_SESSION['shipping_address']) ? trim((string) $_SESSION['shipping_address']) : '';
$discount = 0;
if ($coupon === 'Semester Over') {
    $discount = 100;
}

$total = 0.0;
foreach ($items as $it) {
    $price = (float) ($it['product_price'] ?? 0);
    $qty = (int) ($it['cart_quantity'] ?? 0);
    if ($qty < 0) {
        $qty = 0;
    }
    $total += ($price * $qty);
}

$grandTotal = $total - $discount;
if ($grandTotal < 0) {
    $grandTotal = 0;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Cart</title>
    <link rel="stylesheet" href="Design/dashboard.css?v=2">
    <link rel="stylesheet" href="Design/cart.css?v=1">
    <script src="../Controller/cartUpdate.js"></script>
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
                <a href="cart.php" class="action-btn active">Cart (<?php echo (int) $cartCount; ?>)</a>
                <a href="orderHistory.php" class="action-btn">Order History</a>
                <a href="../../Login and Signup/Controller/logout.php" class="action-btn logout">Logout</a>
            </div>
        </div>

        <div class="main-content">
            <div class="main-header">
                <h1>Your Cart</h1>
                <p>Update quantities, apply coupon, and review total</p>
            </div>

            <?php if (count($items) === 0): ?>
                <p class="products-empty">Your cart is empty.</p>
            <?php else: ?>
                <form method="post" action="../Controller/updateCart.php">
                    <div class="cart-panel">
                        <div class="cart-header-row">
                            <div class="cart-col-product">Product</div>
                            <div class="cart-col-price">Price</div>
                            <div class="cart-col-qty">Qty</div>
                            <div class="cart-col-subtotal">Subtotal</div>
                            <div class="cart-col-action">Action</div>
                        </div>

                        <?php foreach ($items as $it): ?>
                            <?php
                                $pid = (int) ($it['product_id'] ?? $it['id'] ?? 0);
                                $name = $it['product_name'] ?? 'Unnamed';
                                $price = (float) ($it['product_price'] ?? 0);
                                $qty = (int) ($it['cart_quantity'] ?? 1);
                                $stock = (int) ($it['product_quantity'] ?? 0);
                                if ($qty < 1) { $qty = 1; }
                                if ($stock > 0 && $qty > $stock) { $qty = $stock; }
                                $sub = $price * $qty;
                            ?>
                            <div class="cart-row">
                                <div class="cart-col-product">
                                    <?php echo htmlspecialchars($name); ?>
                                    <?php if ($stock <= 0): ?>
                                        <div class="cart-unavailable">Not available</div>
                                    <?php endif; ?>
                                </div>
                                <div class="cart-col-price"><?php echo number_format($price, 2); ?></div>
                                <div class="cart-col-qty">
                                   <button type="button" class="qty-btn qty-decrease" data-product-id="<?php echo $pid; ?>">-</button>
                                   <input type="text" name="qty[<?php echo $pid; ?>]" value="<?php echo (int) $qty; ?>" inputmode="numeric" pattern="[0-9]*" class="cart-qty-input" data-max="<?php echo $stock; ?>">
                                   <button type="button" class="qty-btn qty-increase" data-product-id="<?php echo $pid; ?>">+</button>
                                </div>
                                <div class="cart-col-subtotal"><?php echo number_format($sub, 2); ?></div>
                                <div class="cart-col-action">
                                    <a href="../Controller/removeFromCart.php?product_id=<?php echo (int) $pid; ?>" class="cart-remove">Remove</a>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <div class="cart-footer">
                            <div class="cart-coupon">
                                <label>Coupon:</label>
                                <input type="text" name="coupon" value="<?php echo htmlspecialchars($coupon); ?>">
                                <?php if ($coupon !== '' && $coupon !== 'Semester Over'): ?>
                                    <span class="cart-coupon-bad">Invalid coupon</span>
                                <?php elseif ($coupon === 'Semester Over'): ?>
                                    <span class="cart-coupon-good">Coupon applied</span>
                                <?php endif; ?>

                                <label style="margin-top: 10px; display: block;">Shipping Address:</label>
                                <textarea name="shipping_address" rows="3" class="cart-address"><?php echo htmlspecialchars($shippingAddress); ?></textarea>
                            </div>
                            <div class="cart-summary">
                                <div>Total: <strong><?php echo number_format($total, 2); ?></strong></div>
                                <div>Discount: <strong><?php echo number_format((float) $discount, 2); ?></strong></div>
                                <div class="cart-payable">Payable: <strong><?php echo number_format((float) $grandTotal, 2); ?></strong></div>
                                <div class="cart-actions">
                                    <button type="submit" class="product-btn cart-btn">Update Cart</button>
                                    <button type="submit" class="product-btn btn-buy cart-btn" formaction="payment.php">Pay</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
