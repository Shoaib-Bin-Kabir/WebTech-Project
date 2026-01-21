<?php
session_start();

if (!isset($_SESSION['isLoggedIn']) || !$_SESSION['isLoggedIn']) {
    echo '<p style="color: red;">Unauthorized access.</p>';
    exit();
}

if ($_SESSION['user_type'] !== 'Admin') {
    echo '<p style="color: red;">Unauthorized access.</p>';
    exit();
}

include "../Model/DBConnectr.php";

$sortBy = $_POST['sortBy'] ?? '';
$category = $_POST['category'] ?? '';

$db = new DBConnectr();
$connection = $db->openConnection();

// Build query based on filters
$query = "SELECT * FROM products WHERE 1=1";
$params = [];
$types = "";

// Add category filter if selected
if (!empty($category)) {
    $query .= " AND product_category = ?";
    $params[] = $category;
    $types .= "s";
}

// Add sorting
switch ($sortBy) {
    case 'price_high':
        $query .= " ORDER BY product_price DESC";
        break;
    case 'price_low':
        $query .= " ORDER BY product_price ASC";
        break;
    case 'quantity_high':
        $query .= " ORDER BY product_quantity DESC";
        break;
    case 'quantity_low':
        $query .= " ORDER BY product_quantity ASC";
        break;
    default:
        $query .= " ORDER BY created_at DESC";
        break;
}

// Execute query
if (!empty($params)) {
    $stmt = $connection->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $products = $stmt->get_result();
} else {
    $products = $connection->query($query);
}

// Display products
if ($products && $products->num_rows > 0):
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
                        <form id="photoFormElement_<?php echo $product['id']; ?>" method="post" action="../Controller/updateInventory.php" enctype="multipart/form-data">
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
    <p>No products found matching your criteria.</p>
<?php 
endif;

$db->closeConnection($connection);
?>