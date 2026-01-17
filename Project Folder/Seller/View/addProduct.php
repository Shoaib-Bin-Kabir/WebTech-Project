<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['isLoggedIn']) || !$_SESSION['isLoggedIn']) {
   
    header('Location: ../../Login and Signup/View/login.php');
    exit();
}

// Check if user is a Seller
if ($_SESSION['user_type'] !== 'Seller') {
   
    header('Location: ../../Login and Signup/View/login.php');
    exit();
}

$userEmail = $_SESSION['email'];
$userType = $_SESSION['user_type'];

// Get previous values and errors
$errors = $_SESSION['errors'] ?? [];
$previousValues = $_SESSION['previousValues'] ?? [];
unset($_SESSION['errors']);
unset($_SESSION['previousValues']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <script src="../Controller/JS/AddProductJSval.php"></script>
</head>
<body>
    <div>
        <nav>
            <ul>
                <li><a href="SHomePage.php">Home Page</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="History.php">History</a></li>
                <li><a href="editInventory.php">Edit Inventory</a></li>
                <li><a href="../../Login and Signup/Controller/logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>

    <div>
        <h2>Add Product Page</h2>
    </div>

    <div>
        <form action="../Controller/AddProductPhpVal.php" method="POST" enctype="multipart/form-data" onsubmit="validateAddProduct(event)">  
            <table>
                <tr>
                    <td><label for="pname">Product Name:</label></td>
                    <td>
                        <input type="text" id="pname" name="pname" >
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
                        <input type="submit" value="Add Product">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>

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