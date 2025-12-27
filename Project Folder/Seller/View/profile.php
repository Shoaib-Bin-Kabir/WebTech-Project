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
<title>Seller Profile</title>
</head>
<body>
    <div>
    <nav>
        <ul>
             <li><a href="SHomePage.php">Home Page</a></li>
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
            <td>Here The seller Name will be shown</td>

           <td colspan="2"><input type="submit" value="Edit Name"></td>
        </tr>

        <tr>
            <td>
            Here The seller National ID will be shown
            </td>

        </tr>

        <tr>
            <td>
            Here The seller Email will be shown

            </td>

            <td colspan="2"><input type="submit" value="Edit Email"></td>
        </tr>

        <tr>
            <td>
            Here The seller Phone Number will be shown
            </td>

            <td colspan="2"><input type="submit" value="Edit Phone Number"></td>
        </tr>

        <tr>
            <td>
            Here The seller Address will be shown
            </td>

            <td colspan="2"><input type="submit" value="Edit Address"></td>
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