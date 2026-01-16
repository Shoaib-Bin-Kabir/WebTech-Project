<?php
session_start();


if (!isset($_SESSION['isLoggedIn']) || !$_SESSION['isLoggedIn']) {
    header('Location: ../../Login and Signup/View/login.php');
    exit();
}


if ($_SESSION['user_type'] !== 'Admin') {
   
    header('Location: ../../Login and Signup/View/Dashboard.php');
    exit();
}

$userEmail = $_SESSION['email'];
$userType = $_SESSION['user_type'];


$errors = $_SESSION['errors'] ?? [];
$previousValues = $_SESSION['previousValues'] ?? [];
$addSellerSuccess = $_SESSION['addSellerSuccess'] ?? '';
$addSellerErr = $_SESSION['addSellerErr'] ?? '';
unset($_SESSION['errors']);
unset($_SESSION['previousValues']);
unset($_SESSION['addSellerSuccess']);
unset($_SESSION['addSellerErr']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Seller Page</title>
    <script src="../Controller/JS/addSellJSval.php"></script>
</head>
<body>
    <h1>Manage Seller Page</h1>
    <div>
    <nav>
        <ul>
            <li><a href="AHomePage.php">Home Page</a></li>
            <li><a href="allHistory.php">See History</a></li>
            <li><a href="ManInventory.php">Manage Inventory</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="../../Login and Signup/Controller/logout.php">Logout</a></li>
        </ul>
    </nav>

    </div>

    <div>
          <?php
           if ($addSellerSuccess) {
             echo '<p style="color: green;">' . $addSellerSuccess . '</p>';
           }
           if ($addSellerErr) {
              echo '<p style="color: red;">' . $addSellerErr . '</p>';
           }
           ?>
       <form action="../Controller/addsellerval.php" method="POST" onsubmit="return validateAddSeller(event)"> 
            <table>
                <tr>
                    <td><label for="semail">Seller Email:</label></td>
                    <td>
                        <input type="email" id="semail" name="semail" >
                        <span id="semailErr" style="color: red;"><?php echo $errors['semail'] ?? ''; ?></span>
                    </td>
                </tr>
                
                <tr>
                    <td><label for="spassword">Seller Password:</label></td>
                    <td>
                        <input type="password" id="spassword" name="spassword" value="<?php echo htmlspecialchars($previousValues['spassword'] ?? ''); ?>">
                        <span id="spasswordErr" style="color: red;"><?php echo $errors['spassword'] ?? ''; ?></span>
                    </td>
                </tr>
                
                <tr>
                    <td colspan="2">
                        <input type="submit" value="Add Seller">
                    </td>
                </tr>
            </table>
        </form>
    </div>

   

</body>
</html>