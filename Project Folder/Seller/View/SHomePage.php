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


$db = new DBConnectr();
$connection = $db->openConnection();
$result = $db->getSellerByEmail($connection, $sellerEmail);

$sellerData = null;
$sellerName = "Not provided";
$sellerPhone = "Not provided";
$sellerPhoto = "";
$sellerNID = "Not provided";
$sellerId = "";

if ($result->num_rows > 0) {
    $sellerData = $result->fetch_assoc();
    $sellerId = $sellerData['ID'] ?? "";
    $sellerName = $sellerData['Name'] ?? "Not provided";
    $sellerPhone = $sellerData['Phone_Number'] ?? "Not provided";
    $sellerPhoto = $sellerData['Photo'] ?? "";
    $sellerNID = $sellerData['NID'] ?? "Not provided";
}




$db->closeConnection($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Home Page</title>
</head>
<body>
    <h1>Welcome to the Seller Home Page</h1>
    
    <div>
        <nav>
            <ul>
                <li><a href="addProduct.php">Add Product</a></li>
                <li><a href="History.php">History</a></li>
                <li><a href="editInventory.php">Edit Inventory</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="../../Login and Signup/Controller/logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>

    <div>
        <?php if (!empty($sellerPhoto) && file_exists("../" . $sellerPhoto)): ?>
            <img src="<?php echo htmlspecialchars("../" . $sellerPhoto); ?>" 
                 alt="Profile Photo" 
                 width="150" 
                 height="150">
        <?php else: ?>
            <div style="width: 150px; height: 150px; border: 1px solid #ccc;">
                <p>No profile photo</p>
            </div>
        <?php endif; ?>
    </div>

    <div>
        <table>
            <tr>
                <td><strong>Name:</strong></td>
                <td>
                    <?php 
                    if ($sellerData && !empty($sellerData['Name'])) {
                        echo htmlspecialchars($sellerData['Name']);
                    } else {
                        echo "Name not provided";
                    }
                    ?>
                </td>
            </tr>

            <tr>
                <td><strong>Seller ID:</strong></td>
                <td>
                    <?php 
                    if (!empty($sellerId)) {
                        echo htmlspecialchars($sellerId);
                    } else {
                        echo "ID not available";
                    }
                    ?>
                </td>
            </tr>

            <tr>
                <td><strong>Email:</strong></td>
                <td><?php echo htmlspecialchars($sellerEmail); ?></td>
            </tr>

            <tr>
                <td><strong>Phone:</strong></td>
                <td>
                    <?php 
                    if ($sellerData && !empty($sellerData['Phone_Number'])) {
                        echo htmlspecialchars($sellerData['Phone_Number']);
                    } else {
                        echo "Phone not provided";
                    }
                    ?>
                </td>
            </tr>

            <tr>
                <td><strong>NID:</strong></td>
                <td>
                    <?php 
                    if ($sellerData && !empty($sellerData['NID'])) {
                        echo htmlspecialchars($sellerData['NID']);
                    } else {
                        echo "NID not provided";
                    }
                    ?>
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <button onclick="window.location.href='profile.php'">Edit Profile</button>
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <a href="../../Login and Signup/Controller/logout.php">
                        <button>Logout</button>
                    </a>
                </td>
            </tr>     
        </table>
    </div>

    

</body>
</html>