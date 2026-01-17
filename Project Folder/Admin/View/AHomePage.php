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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Home Page</title>
</head>
<body>
    <h1>Welcome to the Admin Home Page</h1>
    <div>
    <nav>
        <ul>
            <li><a href="allHistory.php">See History</a></li>
            <li><a href="ManInventory.php">Manage Inventory</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="ManSeller.php">Manage Seller</a></li>
            <li><a href="../../Login and Signup/Controller/logout.php">Logout</a></li>
        </ul>
    </nav>

    </div>


    <div>
        <p>Here The Admin Profile Photo will be shown</p>
    </div>

    <div>
        <table>
        <tr>
            <td><p>Here The Admin Name will be shown</p></td>
        </tr>

        <tr>
            <td>
            <p>Here The Admin ID will be shown</p>
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

    <div>
        <p>Here The seller Products will be shown</p>
    </div>

</body>
</html>