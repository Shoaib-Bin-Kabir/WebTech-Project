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

$sellerEmail = $_SESSION['email'];
$userType = $_SESSION['user_type'];

// Fetch all products for this seller
$db = new DBConnectr();
$connection = $db->openConnection();
$products = $db->getAllProducts($connection);
$db->closeConnection($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Inventory</title>
    <link rel="stylesheet" href="Design/seller.css">
    <script src="../Controller/JS/inventoryEdit.js"></script>
    <script src="../Controller/JS/filterInventory.js"></script>
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="profile-section">
                <div class="profile-placeholder">S</div>
                <div class="welcome-text">Seller Dashboard</div>
                <div class="user-email"><?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?></div>
            </div>
            <div class="actions-section">
                <a class="action-btn" href="SHomePage.php">Home</a>
                <a class="action-btn" href="addProduct.php">Add Product</a>
                <a class="action-btn" href="editInventory.php">Edit Inventory</a>
                <a class="action-btn" href="History.php">History</a>
                <a class="action-btn" href="profile.php">Profile</a>
                <a class="action-btn logout" href="../../Login and Signup/Controller/logout.php">Logout</a>
            </div>
        </aside>

        <main class="main-content">
            <h1>Edit Inventory</h1>
            
            <!-- Filter and Sort Controls -->
            <div style="margin: 20px 0; padding: 15px; background-color: #f5f5f5; border: 1px solid #ddd;">
                <table>
                    <tr>
                        <td style="padding-right: 20px;">
                            <label for="sortBy"><strong>Sort By:</strong></label>
                            <select id="sortBy" name="sortBy" onchange="filterInventory()" style="padding: 5px; margin-left: 10px;">
                                <option value="">-- Default --</option>
                                <option value="price_high">Price: High to Low</option>
                                <option value="price_low">Price: Low to High</option>
                                <option value="quantity_high">Quantity: High to Low</option>
                                <option value="quantity_low">Quantity: Low to High</option>
                            </select>
                        </td>
                        <td>
                            <label for="categoryFilter"><strong>Category:</strong></label>
                            <select id="categoryFilter" name="categoryFilter" onchange="filterInventory()" style="padding: 5px; margin-left: 10px;">
                                <option value="">-- All Categories --</option>
                                <optgroup label="Women's Bags">
                                    <option value="Women's Handbag">Women's Handbag</option>
                                    <option value="Women's Shoulder Bag">Women's Shoulder Bag</option>
                                    <option value="Women's Crossbody Bag">Women's Crossbody Bag</option>
                                    <option value="Women's Tote Bag">Women's Tote Bag</option>
                                    <option value="Women's Satchel">Women's Satchel</option>
                                    <option value="Women's Clutch">Women's Clutch</option>
                                    <option value="Women's Evening Bag">Women's Evening Bag</option>
                                    <option value="Women's Backpack">Women's Backpack</option>
                                    <option value="Women's Bucket Bag">Women's Bucket Bag</option>
                                    <option value="Women's Belt Bag">Women's Belt Bag (Fanny Pack)</option>
                                    <option value="Women's Hobo Bag">Women's Hobo Bag</option>
                                    <option value="Women's Frame Bag">Women's Frame Bag</option>
                                </optgroup>
                                <optgroup label="Men's Bags">
                                    <option value="Men's Messenger Bag">Men's Messenger Bag</option>
                                    <option value="Men's Laptop Bag">Men's Laptop Bag</option>
                                    <option value="Men's Backpack">Men's Backpack</option>
                                </optgroup>
                                <optgroup label="Unisex">
                                    <option value="Travel Bag">Travel Bag</option>
                                    <option value="Gym Bag">Gym Bag</option>
                                    <option value="Duffel Bag">Duffel Bag</option>
                                </optgroup>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>
            
            <?php
        
            if (isset($_SESSION['inventoryError'])) {
                echo '<p style="color: red; font-weight: bold;">' . htmlspecialchars($_SESSION['inventoryError']) . '</p>';
                unset($_SESSION['inventoryError']);
            }
            if (isset($_SESSION['inventorySuccess'])) {
                echo '<p style="color: green; font-weight: bold;">' . htmlspecialchars($_SESSION['inventorySuccess']) . '</p>';
                unset($_SESSION['inventorySuccess']);
            }
            ?>

            <!-- Products Container -->
            <div id="productsContainer">
        <?php if ($products->num_rows > 0): ?>
            <?php while ($product = $products->fetch_assoc()): ?>
                <div style="border: 1px solid #000; padding: 15px; margin: 10px 0;">
                    <table>
                        <!-- Product Photo (Editable) -->
                        <tr>
                            <td><strong>Product Photo:</strong></td>
                            <td>
                                <div id="photoDisplay_<?php echo $product['id']; ?>">
                                    <?php if (!empty($product['product_photo']) && file_exists("../../" . $product['product_photo'])): ?>
                                        <img src="<?php echo htmlspecialchars("../../" . $product['product_photo']); ?>" 
                                             width="100" 
                                             height="100" 
                                             alt="Product Photo">
                                    <?php else: ?>
                                        <p>No photo</p>
                                    <?php endif; ?>
                                </div>
                                
                                <div id="photoForm_<?php echo $product['id']; ?>" style="display: none;">
                                  <form id="photoFormElement_<?php echo $product['id']; ?>" 
                                   method="post" 
                                   action="../Controller/updateInventory.php" 
                                   enctype="multipart/form-data">
                                   <input type="hidden" name="updateType" value="photo">
                                   <input type="hidden" name="productId" value="<?php echo $product['id']; ?>">
                                   <div id="photoPreview_<?php echo $product['id']; ?>" style="width: 100px; height: 100px; border: 1px dashed #ccc; margin-bottom: 5px;"></div>
                                   <input type="file" 
                                     name="productPhoto" 
                                     accept="image/*" 
                                     onchange="previewProductPhoto(this, <?php echo $product['id']; ?>)">
                                  </form>
                                </div>
                            </td>
                            <td>
                                <input type="button" 
                                       id="photoBtn_<?php echo $product['id']; ?>" 
                                       value="Edit Photo" 
                                       onclick="togglePhotoEdit(<?php echo $product['id']; ?>)">
                            </td>
                        </tr>

                        <!-- Product Name (Read Only) -->
                        <tr>
                            <td><strong>Product Name:</strong></td>
                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                            <td></td>
                        </tr>

                        <!-- Category (Read Only) -->
                        <tr>
                            <td><strong>Category:</strong></td>
                            <td><?php echo htmlspecialchars($product['product_category']); ?></td>
                            <td></td>
                        </tr>

                        <!-- Price (Editable) -->
                        <tr>
                            <td><strong>Price:</strong></td>
                            <td>
                                <span id="priceDisplay_<?php echo $product['id']; ?>">
                                    $<?php echo htmlspecialchars(number_format($product['product_price'], 2)); ?>
                                </span>
                                <form id="priceForm_<?php echo $product['id']; ?>" 
                                      method="post" 
                                      action="../Controller/updateInventory.php" 
                                      style="display: none;">
                                    <input type="hidden" name="updateType" value="price">
                                    <input type="hidden" name="productId" value="<?php echo $product['id']; ?>">
                                    $<input type="number" 
                                           name="price" 
                                           value="<?php echo htmlspecialchars($product['product_price']); ?>" 
                                           min="0" 
                                           step="0.01"
                                           required>
                                </form>
                            </td>
                            <td>
                                <input type="button" 
                                       id="priceBtn_<?php echo $product['id']; ?>" 
                                       value="Edit Price" 
                                       onclick="toggleEdit('price', <?php echo $product['id']; ?>)">
                            </td>
                        </tr>

                        <!-- Quantity (Editable) -->
                        <tr>
                            <td><strong>Quantity:</strong></td>
                            <td>
                                <span id="quantityDisplay_<?php echo $product['id']; ?>">
                                    <?php echo htmlspecialchars($product['product_quantity']); ?>
                                </span>
                                <form id="quantityForm_<?php echo $product['id']; ?>" 
                                      method="post" 
                                      action="../Controller/updateInventory.php" 
                                      style="display: none;">
                                    <input type="hidden" name="updateType" value="quantity">
                                    <input type="hidden" name="productId" value="<?php echo $product['id']; ?>">
                                    <input type="number" 
                                           name="quantity" 
                                           value="<?php echo htmlspecialchars($product['product_quantity']); ?>" 
                                           min="0" 
                                           required>
                                </form>
                            </td>
                            <td>
                                <input type="button" 
                                       id="quantityBtn_<?php echo $product['id']; ?>" 
                                       value="Edit Quantity" 
                                       onclick="toggleEdit('quantity', <?php echo $product['id']; ?>)">
                            </td>
                        </tr>

                        <!-- Created Date (Read Only) -->
                        <tr>
                            <td><strong>Created:</strong></td>
                            <td><?php echo htmlspecialchars($product['created_at']); ?></td>
                            <td></td>
                        </tr>

                        <!-- Delete Button -->
                        <tr>
                            <td colspan="3">
                                <form id="deleteForm_<?php echo $product['id']; ?>" 
                                      method="post" 
                                      action="../Controller/updateInventory.php" 
                                      style="display: inline;">
                                    <input type="hidden" name="updateType" value="delete">
                                    <input type="hidden" name="productId" value="<?php echo $product['id']; ?>">
                                </form>
                                <input type="button" 
                                       value="Remove Product" 
                                       style="background-color: red; color: white;"
                                       onclick="confirmDelete(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars(addslashes($product['product_name'])); ?>')">
                            </td>
                        </tr>
                    </table>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No products found. <a href="addProduct.php">Add your first product</a></p>
        <?php endif; ?>
            </div>
            </div>
        </main>
    </div>
</body>
</html>