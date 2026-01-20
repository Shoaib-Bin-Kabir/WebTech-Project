<?php

if (!isset($products) || !is_array($products)) {
    $products = [];
}

$selectedCategory = $selectedCategory ?? '';
?>

<div class="products-section">
    <h2 class="products-title">Products</h2>

    <form method="get" action="dashboard.php" class="product-filter">
        <?php if (isset($isEditing) && $isEditing): ?>
            <input type="hidden" name="edit" value="1">
        <?php endif; ?>

        <label for="category" class="product-filter-label">Category:</label>
        <select id="category" name="category" class="product-filter-select" onchange="this.form.submit()">
            <option value="" <?php echo ($selectedCategory === '') ? 'selected' : ''; ?>>All</option>

            <optgroup label="Women's Bags">
                <option value="Women's Handbag" <?php echo ($selectedCategory === "Women's Handbag") ? 'selected' : ''; ?>>Women's Handbag</option>
                <option value="Women's Shoulder Bag" <?php echo ($selectedCategory === "Women's Shoulder Bag") ? 'selected' : ''; ?>>Women's Shoulder Bag</option>
                <option value="Women's Crossbody Bag" <?php echo ($selectedCategory === "Women's Crossbody Bag") ? 'selected' : ''; ?>>Women's Crossbody Bag</option>
                <option value="Women's Tote Bag" <?php echo ($selectedCategory === "Women's Tote Bag") ? 'selected' : ''; ?>>Women's Tote Bag</option>
                <option value="Women's Satchel" <?php echo ($selectedCategory === "Women's Satchel") ? 'selected' : ''; ?>>Women's Satchel</option>
                <option value="Women's Clutch" <?php echo ($selectedCategory === "Women's Clutch") ? 'selected' : ''; ?>>Women's Clutch</option>
                <option value="Women's Evening Bag" <?php echo ($selectedCategory === "Women's Evening Bag") ? 'selected' : ''; ?>>Women's Evening Bag</option>
                <option value="Women's Backpack" <?php echo ($selectedCategory === "Women's Backpack") ? 'selected' : ''; ?>>Women's Backpack</option>
                <option value="Women's Bucket Bag" <?php echo ($selectedCategory === "Women's Bucket Bag") ? 'selected' : ''; ?>>Women's Bucket Bag</option>
                <option value="Women's Belt Bag" <?php echo ($selectedCategory === "Women's Belt Bag") ? 'selected' : ''; ?>>Women's Belt Bag (Fanny Pack)</option>
                <option value="Women's Hobo Bag" <?php echo ($selectedCategory === "Women's Hobo Bag") ? 'selected' : ''; ?>>Women's Hobo Bag</option>
                <option value="Women's Frame Bag" <?php echo ($selectedCategory === "Women's Frame Bag") ? 'selected' : ''; ?>>Women's Frame Bag</option>
            </optgroup>

            <optgroup label="Men's Bags">
                <option value="Men's Messenger Bag" <?php echo ($selectedCategory === "Men's Messenger Bag") ? 'selected' : ''; ?>>Men's Messenger Bag</option>
                <option value="Men's Laptop Bag" <?php echo ($selectedCategory === "Men's Laptop Bag") ? 'selected' : ''; ?>>Men's Laptop Bag</option>
                <option value="Men's Backpack" <?php echo ($selectedCategory === "Men's Backpack") ? 'selected' : ''; ?>>Men's Backpack</option>
            </optgroup>

            <optgroup label="Unisex">
                <option value="Travel Bag" <?php echo ($selectedCategory === "Travel Bag") ? 'selected' : ''; ?>>Travel Bag</option>
                <option value="Gym Bag" <?php echo ($selectedCategory === "Gym Bag") ? 'selected' : ''; ?>>Gym Bag</option>
                <option value="Duffel Bag" <?php echo ($selectedCategory === "Duffel Bag") ? 'selected' : ''; ?>>Duffel Bag</option>
            </optgroup>
        </select>

        <noscript>
            <button type="submit" class="product-filter-btn">Apply</button>
        </noscript>

        <label for="searchText" class="product-filter-label">Search:</label>
        <input type="text" id="searchText" class="product-filter-select" placeholder="Search by name" onkeyup="searchProduct()">
    </form>

    <div id="productResult">
        <?php if (count($products) === 0): ?>
            <p class="products-empty">No products found.</p>
        <?php else: ?>
            <div class="products-grid">
                <?php foreach ($products as $product): ?>
                <?php
                    $productId = $product['id'] ?? 0;
                    $name = $product['product_name'] ?? 'Unnamed';
                    $category = $product['product_category'] ?? '';
                    $price = $product['product_price'] ?? '';
                    $quantity = $product['product_quantity'] ?? '';
                    $photo = $product['product_photo'] ?? '';

                    $isOutOfStock = ($quantity !== '' && is_numeric($quantity) && (int) $quantity <= 0);

                    $hasValidPhoto = false;
                    if (!empty($photo)) {
                        $lower = strtolower($photo);
                        $hasValidPhoto = (bool) preg_match('/\.(jpg|jpeg|png|gif|webp)$/', $lower);
                    }
                ?>

                <div class="product-card">
                    <div class="product-media">
                        <?php if ($hasValidPhoto): ?>
                           <img class="product-image" src="<?php echo htmlspecialchars('../../' . $photo); ?>" alt="<?php echo htmlspecialchars($name); ?>">
                        <?php else: ?>
                            <div class="product-image-placeholder">No Photo</div>
                        <?php endif; ?>
                    </div>

                    <div class="product-body">
                        <div class="product-name"><?php echo htmlspecialchars($name); ?></div>
                        <div class="product-meta">
                            <?php if ($category !== ''): ?>
                                <div><span class="meta-label">Category:</span> <?php echo htmlspecialchars($category); ?></div>
                            <?php endif; ?>
                            <?php if ($price !== ''): ?>
                                <div><span class="meta-label">Price:</span> <?php echo htmlspecialchars($price); ?></div>
                            <?php endif; ?>
                            <?php if ($quantity !== ''): ?>
                                <div><span class="meta-label">Stock:</span> <?php echo $isOutOfStock ? 'Not available' : htmlspecialchars($quantity); ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="product-actions">
                            <form method="post" action="../Controller/addToCart.php" style="flex: 1;">
                                <input type="hidden" name="product_id" value="<?php echo (int) $productId; ?>">
                                <button type="submit" class="product-btn btn-cart" <?php echo $isOutOfStock ? 'disabled title="Not available"' : ''; ?>>Add to Cart</button>
                            </form>
                            <form method="post" action="../Controller/buyNow.php" style="flex: 1;">
                                <input type="hidden" name="product_id" value="<?php echo (int) $productId; ?>">
                                <button type="submit" class="product-btn btn-buy" <?php echo $isOutOfStock ? 'disabled title="Not available"' : ''; ?>>Buy Now</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="../Controller/searchProduct.js"></script>
