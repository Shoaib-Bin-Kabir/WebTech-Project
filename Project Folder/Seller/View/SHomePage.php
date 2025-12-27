<?php
session_start();


if (!isset($_SESSION['isLoggedIn']) || !$_SESSION['isLoggedIn']) {
    header('Location: ../../Login and Signup/View/login.php');
    exit();
}


if ($_SESSION['user_type'] !== 'Seller') {
   
    header('Location: ../../Login and Signup/View/Dashboard.php');
    exit();
}

$userEmail = $_SESSION['email'];
$userType = $_SESSION['user_type'];
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
        <p>Here The seller Profile Photo will be shown</p>
    </div>

    <div>
        <table>
        <tr>
            <td><p>Here The seller Name will be shown</p></td>
        </tr>

        <tr>
            <td>
            <p>Here The seller ID wii be shown</p>
            </td>
        </tr>
        
        <tr>
        
                 <td colspan="2"><input type="submit" value="Edit Profile"></td>
            
        </tr>

        <tr>
        
                 <td colspan="2"><input type="submit" value="Logout"></td>
            
        </tr>        





        </table>
    </div>

    <div>
        <p>Here The seller Products will be shown</p>
    </div>

</body>
</html>