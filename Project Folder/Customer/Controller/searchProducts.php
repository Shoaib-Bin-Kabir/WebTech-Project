<?php
session_start();

if (!isset($_SESSION['isLoggedIn']) || !$_SESSION['isLoggedIn']) {
    exit();
}

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'Customer') {
    exit();
}

include "../Model/DBConnectr.php";

$q = isset($_GET['q']) ? trim($_GET['q']) : '';

$db = new DBConnectr();
$connection = $db->openConnection();

$result = $db->searchProducts($connection, $q);

$products = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

$db->closeConnection($connection);

if (count($products) === 0) {
    echo '<p class="products-empty">No products found.</p>';
    exit();
}

echo '<div class="products-grid">';
foreach ($products as $product) {
    $productId = $product['id'] ?? 0;
    $name = $product['product_name'] ?? 'Unnamed';
    $cat = $product['product_category'] ?? '';
    $price = $product['product_price'] ?? '';
    $qty = $product['product_quantity'] ?? '';
    $photo = $product['product_photo'] ?? '';

    $isOutOfStock = ($qty !== '' && is_numeric($qty) && (int) $qty <= 0);

    $hasValidPhoto = false;
    if (!empty($photo)) {
        $lower = strtolower($photo);
        $hasValidPhoto = (bool) preg_match('/\.(jpg|jpeg|png|gif|webp)$/', $lower);
    }

    echo '<div class="product-card">';

    echo '<div class="product-media">';
    if ($hasValidPhoto) {
        echo '<img class="product-image" src="' . htmlspecialchars($photo) . '" alt="' . htmlspecialchars($name) . '">';
    } else {
        echo '<div class="product-image-placeholder">No Photo</div>';
    }
    echo '</div>';

    echo '<div class="product-body">';
    echo '<div class="product-name">' . htmlspecialchars($name) . '</div>';

    echo '<div class="product-meta">';
    if ($cat !== '') {
        echo '<div><span class="meta-label">Category:</span> ' . htmlspecialchars($cat) . '</div>';
    }
    if ($price !== '') {
        echo '<div><span class="meta-label">Price:</span> ' . htmlspecialchars($price) . '</div>';
    }
    if ($qty !== '') {
        echo '<div><span class="meta-label">Stock:</span> ' . ($isOutOfStock ? 'Not available' : htmlspecialchars($qty)) . '</div>';
    }
    echo '</div>';

    echo '<div class="product-actions">';
    echo '<form method="post" action="../Controller/addToCart.php" style="flex: 1;">';
    echo '<input type="hidden" name="product_id" value="' . (int) $productId . '">';
    echo '<button type="submit" class="product-btn btn-cart"' . ($isOutOfStock ? ' disabled title="Not available"' : '') . '>Add to Cart</button>';
    echo '</form>';
    echo '<form method="post" action="../Controller/buyNow.php" style="flex: 1;">';
    echo '<input type="hidden" name="product_id" value="' . (int) $productId . '">';
    echo '<button type="submit" class="product-btn btn-buy"' . ($isOutOfStock ? ' disabled title="Not available"' : '') . '>Buy Now</button>';
    echo '</form>';
    echo '</div>';

    echo '</div>';
    echo '</div>';
}
echo '</div>';
