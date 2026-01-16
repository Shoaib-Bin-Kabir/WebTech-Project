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
    $name = $product['product_name'] ?? 'Unnamed';
    $cat = $product['product_category'] ?? '';
    $price = $product['product_price'] ?? '';
    $qty = $product['product_quantity'] ?? '';
    $photo = $product['product_photo'] ?? '';

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
        echo '<div><span class="meta-label">Stock:</span> ' . htmlspecialchars($qty) . '</div>';
    }
    echo '</div>';

    echo '<div class="product-actions">';
    echo '<button type="button" class="product-btn btn-cart">Add to Cart</button>';
    echo '<button type="button" class="product-btn btn-buy">Buy Now</button>';
    echo '</div>';

    echo '</div>';
    echo '</div>';
}
echo '</div>';
