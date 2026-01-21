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
    header('Location: cart.php');
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantities = $_POST['qty'] ?? [];
    if (is_array($quantities)) {
        foreach ($quantities as $pid => $qty) {
            $productId = (int) $pid;
            $qty = is_numeric($qty) ? (int) $qty : 0;

            if ($productId <= 0) {
                continue;
            }

            $productRes = $db->getProductById($connection, $productId);
            $product = $productRes ? $productRes->fetch_assoc() : null;
            if ($product && is_numeric($product['product_quantity'] ?? null)) {
                $stock = (int) $product['product_quantity'];
                if ($stock <= 0) {
                    $qty = 0;
                } elseif ($qty > $stock) {
                    $qty = $stock;
                }
            }

            $db->updateCartItemQuantity($connection, $customerId, $productId, $qty);
        }
    }

    $coupon = isset($_POST['coupon']) ? trim((string) $_POST['coupon']) : '';
    $_SESSION['cart_coupon'] = $coupon;

    $shippingAddress = isset($_POST['shipping_address']) ? trim((string) $_POST['shipping_address']) : '';
    $_SESSION['shipping_address'] = $shippingAddress;
}

$coupon = isset($_SESSION['cart_coupon']) ? trim((string) $_SESSION['cart_coupon']) : '';
$shippingAddress = isset($_SESSION['shipping_address']) ? trim((string) $_SESSION['shipping_address']) : '';

$discount = 0;
if ($coupon === 'Semester Over') {
    $discount = 100;
}

$items = [];
$buyNow = $_SESSION['buy_now'] ?? null;
if (is_array($buyNow) && isset($buyNow['product_id'])) {
    $buyNowProductId = (int) $buyNow['product_id'];
    $buyNowQty = 1;

    $productRes = $db->getProductById($connection, $buyNowProductId);
    $product = $productRes ? $productRes->fetch_assoc() : null;

    if ($product) {
        $items[] = [
            'product_id' => $buyNowProductId,
            'cart_quantity' => $buyNowQty,
            'product_name' => $product['product_name'] ?? '',
            'product_price' => $product['product_price'] ?? 0,
            'product_quantity' => $product['product_quantity'] ?? 0,
        ];
    } else {
        $_SESSION['orderError'] = 'Product is not available.';
        unset($_SESSION['buy_now']);
    }
} else {
    $cartRes = $db->getCartItems($connection, $customerId);
    if ($cartRes) {
        while ($row = $cartRes->fetch_assoc()) {
            $items[] = $row;
        }
    }
}

$cartCount = $db->getCartItemCount($connection, $customerId);

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

$db->closeConnection($connection);

$displayName = (!empty($customer['Name'])) ? $customer['Name'] : $email;
$orderError = isset($_SESSION['orderError']) ? (string) $_SESSION['orderError'] : '';
unset($_SESSION['orderError']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment</title>
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
                <a href="orderHistory.php" class="action-btn">Order History</a>
                <a href="../../Login and Signup/Controller/logout.php" class="action-btn logout">Logout</a>
            </div>
        </div>

        <div class="main-content">
            <div class="main-header">
                <h1>Payment</h1>
                <p>Confirm your payment details</p>
            </div>

            <?php if ($orderError !== ''): ?>
                <p class="products-empty"><?php echo htmlspecialchars($orderError); ?></p>
            <?php endif; ?>

            <?php if (count($items) === 0): ?>
                <p class="products-empty">Your cart is empty.</p>
            <?php else: ?>
                <div class="cart-panel">
                    <div style="margin-bottom: 10px;">
                        <strong>Shipping Address:</strong>
                        <div><?php echo $shippingAddress !== '' ? nl2br(htmlspecialchars($shippingAddress)) : 'Not provided'; ?></div>
                    </div>

                    <div style="margin-bottom: 10px;">
                        <strong>Total:</strong> <?php echo number_format($total, 2); ?><br>
                        <strong>Discount:</strong> <?php echo number_format((float) $discount, 2); ?><br>
                        <strong>Payable:</strong> <?php echo number_format((float) $grandTotal, 2); ?>
                        <?php if ($coupon === 'Semester Over'): ?>
                            <div>Coupon applied</div>
                        <?php endif; ?>
                    </div>

                    <form method="post" action="../Controller/processPayment.php">
                        <div style="margin-bottom: 10px;">
                            <label style="font-weight: 700;">Shipping Address:</label>
                            <textarea name="shipping_address" rows="3" class="product-filter-select" required><?php echo htmlspecialchars($shippingAddress); ?></textarea>
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label style="font-weight: 700;">Payment Method:</label>
                            <select name="method" class="product-filter-select">
                                <option value="Card">Card</option>
                                <option value="Bkash">Bkash</option>
                                <option value="Cash">Cash</option>
                            </select>
                        </div>

                        <div style="margin-bottom: 10px;">
                            <label style="font-weight: 700;">Reference:</label>
                            <input type="text" name="ref" class="product-filter-select">
                        </div>

                        <button type="submit" class="product-btn btn-buy">Confirm Payment</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
