<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['isLoggedIn']) || !$_SESSION['isLoggedIn']) {
    // Not logged in, send back to login
    header('Location: ../../Login and Signup/View/login.php');
    exit();
}

// Check if user is a Seller (not a Customer)
if ($_SESSION['user_type'] !== 'Seller') {
    // Customer trying to access seller pages - redirect them
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
<title>Seller Profile</title>
</head>
<body>
    <div>
    <nav>
        <ul>
             <li><a href="HomePage.php">Home Page</a></li>
            <li><a href="addProduct.php">Add Product</a></li>
            <li><a href="History.php">History</a></li>
            <li><a href="editInventory.php">Edit Inventory</a></li>
            <li><a href="../../Login and Signup/Controller/logout.php">Logout</a></li>
        </ul>
    </nav>

    </div>

    <div>
        <tr>
            <td>
            <p>Here The seller Profile Photo will be shown</p>
            </td>
        </tr>

        <tr>
           <td colspan="2"><input type="submit" value="Edit Profile Photo"></td>
        </tr>
        
        
    </div>

    <div>
        <table>
        <tr>
            <td><p>Here The seller Name will be shown</p></td>

           <td colspan="2"><input type="submit" value="Edit Name"></td>
        </tr>

        <tr>
            <td>
            <p>Here The seller National ID will be shown</p>
            </td>

        </tr>

        <tr>
            <td>
            <p>Here The seller Email will be shown</p>

            </td>

            <td colspan="2"><input type="submit" value="Edit Email"></td>
        </tr>

        <tr>
            <td>
            <p>Here The seller Phone Number will be shown</p>
            </td>

            <td colspan="2"><input type="submit" value="Edit Phone Number"></td>
        </tr>

        <tr>
            <td>
            <p>Here The seller Address will be shown</p>
            </td>

            <td colspan="2"><input type="submit" value="Edit Address"></td>
        </tr>
        
       

        <tr>
        
                 <td colspan="2"><input type="submit" value="Logout"></td>
            
        </tr>
        </table>
    </div>



</body>
</html>