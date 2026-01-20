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

$userEmail = $_SESSION['email'];
$userType = $_SESSION['user_type'];

// Get previous values and errors
$errors = $_SESSION['errors'] ?? [];
$previousValues = $_SESSION['previousValues'] ?? [];
$addProductSuccess = $_SESSION['addProductSuccess'] ?? '';
$addProductErr = $_SESSION['addProductErr'] ?? '';

unset($_SESSION['errors']);
unset($_SESSION['previousValues']);
unset($_SESSION['addProductSuccess']);
unset($_SESSION['addProductErr']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Inventory</title>
    <link rel="stylesheet" href="../Design/admin.css">
    <script src="../Controller/JS/AddProductJSval.php"></script>
    <script src="../Controller/JS/inventoryEdit.js"></script>
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
            <h1>Manage Inventory - Add Product to Shop</h1>
    
    <?php if ($addProductSuccess): ?>
        <p style="color: green; font-weight: bold;"><?php echo htmlspecialchars($addProductSuccess); ?></p>
    <?php endif; ?>
    
    <?php if ($addProductErr): ?>
        <p style="color: red; font-weight: bold;"><?php echo htmlspecialchars($addProductErr); ?></p>
    <?php endif; ?>
    
    

    <div>
        <form action="../Controller/AddProductPhpVal.php" method="POST" enctype="multipart/form-data" onsubmit="validateAddProduct(event)">  
            <table>
                <tr>
                    <td><label for="pname">Product Name:</label></td>
                    <td>
                        <input type="text" id="pname" name="pname" value="<?php echo htmlspecialchars($previousValues['pname'] ?? ''); ?>">
                        <span id="pnameErr" style="color: red;"><?php echo $errors['pname'] ?? ''; ?></span>
                    </td>
                </tr>
                
                <tr>
                    <td><label for="pdesc">Product Category:</label></td>
                    <td>
                        <select id="pdesc" name="pdesc">
                            <option value="">-- Select Category --</option>
                            <optgroup label="Women's Bags">
                                <option value="Women's Handbag" <?php echo (isset($previousValues['pdesc']) && $previousValues['pdesc'] === "Women's Handbag") ? 'selected' : ''; ?>>Women's Handbag</option>
                                <option value="Women's Shoulder Bag" <?php echo (isset($previousValues['pdesc']) && $previousValues['pdesc'] === "Women's Shoulder Bag") ? 'selected' : ''; ?>>Women's Shoulder Bag</option>
                                <option value="Women's Crossbody Bag" <?php echo (isset($previousValues['pdesc']) && $previousValues['pdesc'] === "Women's Crossbody Bag") ? 'selected' : ''; ?>>Women's Crossbody Bag</option>
                                <option value="Women's Tote Bag" <?php echo (isset($previousValues['pdesc']) && $previousValues['pdesc'] === "Women's Tote Bag") ? 'selected' : ''; ?>>Women's Tote Bag</option>
                                <option value="Women's Satchel" <?php echo (isset($previousValues['pdesc']) && $previousValues['pdesc'] === "Women's Satchel") ? 'selected' : ''; ?>>Women's Satchel</option>
                                <option value="Women's Clutch" <?php echo (isset($previousValues['pdesc']) && $previousValues['pdesc'] === "Women's Clutch") ? 'selected' : ''; ?>>Women's Clutch</option>
                                <option value="Women's Evening Bag" <?php echo (isset($previousValues['pdesc']) && $previousValues['pdesc'] === "Women's Evening Bag") ? 'selected' : ''; ?>>Women's Evening Bag</option>
                                <option value="Women's Backpack" <?php echo (isset($previousValues['pdesc']) && $previousValues['pdesc'] === "Women's Backpack") ? 'selected' : ''; ?>>Women's Backpack</option>
                                <option value="Women's Bucket Bag" <?php echo (isset($previousValues['pdesc']) && $previousValues['pdesc'] === "Women's Bucket Bag") ? 'selected' : ''; ?>>Women's Bucket Bag</option>
                                <option value="Women's Belt Bag" <?php echo (isset($previousValues['pdesc']) && $previousValues['pdesc'] === "Women's Belt Bag") ? 'selected' : ''; ?>>Women's Belt Bag (Fanny Pack)</option>
                                <option value="Women's Hobo Bag" <?php echo (isset($previousValues['pdesc']) && $previousValues['pdesc'] === "Women's Hobo Bag") ? 'selected' : ''; ?>>Women's Hobo Bag</option>
                                <option value="Women's Frame Bag" <?php echo (isset($previousValues['pdesc']) && $previousValues['pdesc'] === "Women's Frame Bag") ? 'selected' : ''; ?>>Women's Frame Bag</option>
                            </optgroup>
                            <optgroup label="Men's Bags">
                                <option value="Men's Messenger Bag" <?php echo (isset($previousValues['pdesc']) && $previousValues['pdesc'] === "Men's Messenger Bag") ? 'selected' : ''; ?>>Men's Messenger Bag</option>
                                <option value="Men's Laptop Bag" <?php echo (isset($previousValues['pdesc']) && $previousValues['pdesc'] === "Men's Laptop Bag") ? 'selected' : ''; ?>>Men's Laptop Bag</option>
                                <option value="Men's Backpack" <?php echo (isset($previousValues['pdesc']) && $previousValues['pdesc'] === "Men's Backpack") ? 'selected' : ''; ?>>Men's Backpack</option>
                            </optgroup>
                            <optgroup label="Unisex">
                                <option value="Travel Bag" <?php echo (isset($previousValues['pdesc']) && $previousValues['pdesc'] === "Travel Bag") ? 'selected' : ''; ?>>Travel Bag</option>
                                <option value="Gym Bag" <?php echo (isset($previousValues['pdesc']) && $previousValues['pdesc'] === "Gym Bag") ? 'selected' : ''; ?>>Gym Bag</option>
                                <option value="Duffel Bag" <?php echo (isset($previousValues['pdesc']) && $previousValues['pdesc'] === "Duffel Bag") ? 'selected' : ''; ?>>Duffel Bag</option>
                            </optgroup>
                        </select>
                        <span id="pdescErr" style="color: red;"><?php echo $errors['pdesc'] ?? ''; ?></span>
                    </td>
                </tr>
                
                <tr>
                    <td><label for="pprice">Product Price:</label></td>
                    <td>
                        <input type="number" id="pprice" name="pprice" step="0.01" value="<?php echo htmlspecialchars($previousValues['pprice'] ?? ''); ?>">
                        <span id="ppriceErr" style="color: red;"><?php echo $errors['pprice'] ?? ''; ?></span>
                    </td>
                </tr>
                
                <tr>
                    <td><label for="pquantity">Product Quantity:</label></td>
                    <td>
                        <input type="number" id="pquantity" name="pquantity" value="<?php echo htmlspecialchars($previousValues['pquantity'] ?? ''); ?>">
                        <span id="pquantityErr" style="color: red;"><?php echo $errors['pquantity'] ?? ''; ?></span>
                    </td>
                </tr>
                
                <tr>
                    <td><label for="pPhoto">Add Product Photo:</label></td>
                    <td>
                        <input type="file" id="pPhoto" name="pPhoto" accept="image/*">
                        <span id="pPhotoErr" style="color: red;"><?php echo $errors['pPhoto'] ?? ''; ?></span>
                        <div id="preview"></div>
                    </td>
                </tr>
                
                <tr>
                    <td colspan="2">
                        <input type="submit" value="Add Product to Shop">
                    </td>
                </tr>
            </table>
        </form>
    </div>
    
    <script>
        document.getElementById('pPhoto').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('preview');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = '<img src="' + e.target.result + '" style="width: 200px; height: 200px;">';
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
     <hr>

    <h2>Shop Inventory</h2>
    
    <?php
    if (isset($_SESSION['inventoryError'])) {
        echo '<p style="color: red;">' . htmlspecialchars($_SESSION['inventoryError']) . '</p>';
        unset($_SESSION['inventoryError']);
    }
    if (isset($_SESSION['inventorySuccess'])) {
        echo '<p style="color: green;">' . htmlspecialchars($_SESSION['inventorySuccess']) . '</p>';
        unset($_SESSION['inventorySuccess']);
    }
    
    include "../Model/DBConnectr.php";
    $db = new DBConnectr();
    $connection = $db->openConnection();
    $products = $db->getAllProducts($connection);
    $db->closeConnection($connection);
    
    if ($products->num_rows > 0):
        while ($product = $products->fetch_assoc()):
    ?>
        <div style="border: 1px solid #000; padding: 15px; margin: 10px 0;">
            <table>
                <tr>
                    <td><strong>Product Photo:</strong></td>
                    <td>
                        <div id="photoDisplay_<?php echo $product['id']; ?>">
                            <?php if (!empty($product['product_photo']) && file_exists("../../" . $product['product_photo'])): ?>
                                <img src="<?php echo htmlspecialchars("../../" . $product['product_photo']); ?>" width="100" height="100">
                            <?php else: ?>
                                <p>No photo</p>
                            <?php endif; ?>
                        </div>
                        
                        <div id="photoForm_<?php echo $product['id']; ?>" style="display: none;">
                            <form method="post" action="../Controller/updateInventory.php" enctype="multipart/form-data">
                                <input type="hidden" name="updateType" value="photo">
                                <input type="hidden" name="productId" value="<?php echo $product['id']; ?>">
                                <div id="photoPreview_<?php echo $product['id']; ?>" style="width: 100px; height: 100px; border: 1px dashed #ccc;"></div>
                                <input type="file" name="productPhoto" accept="image/*" onchange="previewProductPhoto(this, <?php echo $product['id']; ?>)">
                            </form>
                        </div>
                    </td>
                    <td>
                        <input type="button" id="photoBtn_<?php echo $product['id']; ?>" value="Edit Photo" onclick="togglePhotoEdit(<?php echo $product['id']; ?>)">
                    </td>
                </tr>

                <tr>
                    <td><strong>Product Name:</strong></td>
                    <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                    <td></td>
                </tr>

                <tr>
                    <td><strong>Category:</strong></td>
                    <td><?php echo htmlspecialchars($product['product_category']); ?></td>
                    <td></td>
                </tr>

                <tr>
                    <td><strong>Price:</strong></td>
                    <td>
                        <span id="priceDisplay_<?php echo $product['id']; ?>">
                            $<?php echo htmlspecialchars(number_format($product['product_price'], 2)); ?>
                        </span>
                        <form id="priceForm_<?php echo $product['id']; ?>" method="post" action="../Controller/updateInventory.php" style="display: none;">
                            <input type="hidden" name="updateType" value="price">
                            <input type="hidden" name="productId" value="<?php echo $product['id']; ?>">
                            $<input type="number" name="price" value="<?php echo htmlspecialchars($product['product_price']); ?>" min="0" step="0.01">
                        </form>
                    </td>
                    <td>
                        <input type="button" id="priceBtn_<?php echo $product['id']; ?>" value="Edit Price" onclick="toggleEdit('price', <?php echo $product['id']; ?>)">
                    </td>
                </tr>

                <tr>
                    <td><strong>Quantity:</strong></td>
                    <td>
                        <span id="quantityDisplay_<?php echo $product['id']; ?>">
                            <?php echo htmlspecialchars($product['product_quantity']); ?>
                        </span>
                        <form id="quantityForm_<?php echo $product['id']; ?>" method="post" action="../Controller/updateInventory.php" style="display: none;">
                            <input type="hidden" name="updateType" value="quantity">
                            <input type="hidden" name="productId" value="<?php echo $product['id']; ?>">
                            <input type="number" name="quantity" value="<?php echo htmlspecialchars($product['product_quantity']); ?>" min="0">
                        </form>
                    </td>
                    <td>
                        <input type="button" id="quantityBtn_<?php echo $product['id']; ?>" value="Edit Quantity" onclick="toggleEdit('quantity', <?php echo $product['id']; ?>)">
                    </td>
                </tr>

                <tr>
                    <td><strong>Added By:</strong></td>
                    <td><?php echo htmlspecialchars($product['seller_email']); ?></td>
                    <td></td>
                </tr>

                <tr>
                    <td><strong>Created:</strong></td>
                    <td><?php echo htmlspecialchars($product['created_at']); ?></td>
                    <td></td>
                </tr>

                <tr>
                    <td colspan="3">
                        <form id="deleteForm_<?php echo $product['id']; ?>" method="post" action="../Controller/updateInventory.php" style="display: inline;">
                            <input type="hidden" name="updateType" value="delete">
                            <input type="hidden" name="productId" value="<?php echo $product['id']; ?>">
                        </form>
                        <input type="button" value="Remove Product" style="background-color: red; color: white;" onclick="confirmDelete(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['product_name']); ?>')">
                    </td>
                </tr>
            </table>
        </div>
    <?php
        endwhile;
    else:
    ?>
        <p>No products in inventory.</p>
    <?php endif; ?>
        </main>
    </div>
</body>
</html>