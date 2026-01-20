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
    <link rel="stylesheet" href="../Design/admin.css">
    <script src="../Controller/JS/addSellJSval.php"></script>
    <script src="../Controller/JS/sellerManage.js"></script>
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
            <h1>Manage Seller</h1>

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

    <hr>

    <h2>All Sellers</h2>
    
    <?php
    if (isset($_SESSION['sellerError'])) {
        echo '<p style="color: red;">' . htmlspecialchars($_SESSION['sellerError']) . '</p>';
        unset($_SESSION['sellerError']);
    }
    if (isset($_SESSION['sellerSuccess'])) {
        echo '<p style="color: green;">' . htmlspecialchars($_SESSION['sellerSuccess']) . '</p>';
        unset($_SESSION['sellerSuccess']);
    }
    
    include "../Model/DBConnectr.php";
    $db = new DBConnectr();
    $connection = $db->openConnection();
    $sellers = $db->getAllSellers($connection);
    $db->closeConnection($connection);
    
    if ($sellers->num_rows > 0):
        while ($seller = $sellers->fetch_assoc()):
            $formId = str_replace(['@', '.'], '_', $seller['Email']);
    ?>
        <div style="border: 1px solid #000; padding: 15px; margin: 10px 0;">
            <table>
                <tr>
                    <td><strong>Seller Photo:</strong></td>
                    <td>
                        <?php if (!empty($seller['Photo']) && file_exists("../../Seller/" . $seller['Photo'])): ?>
                            <img src="<?php echo htmlspecialchars("../../Seller/" . $seller['Photo']); ?>" width="100" height="100">
                        <?php else: ?>
                            <p>No photo</p>
                        <?php endif; ?>
                    </td>
                </tr>

                <tr>
                    <td><strong>Seller ID:</strong></td>
                    <td><?php echo htmlspecialchars($seller['ID']); ?></td>
                </tr>

                <tr>
                    <td><strong>Email:</strong></td>
                    <td><?php echo htmlspecialchars($seller['Email']); ?></td>
                </tr>

                <tr>
                    <td><strong>Name:</strong></td>
                    <td><?php echo !empty($seller['Name']) ? htmlspecialchars($seller['Name']) : 'Not provided'; ?></td>
                </tr>

                <tr>
                    <td><strong>Phone:</strong></td>
                    <td><?php echo !empty($seller['Phone_Number']) ? htmlspecialchars($seller['Phone_Number']) : 'Not provided'; ?></td>
                </tr>

                <tr>
                    <td><strong>NID:</strong></td>
                    <td><?php echo !empty($seller['NID']) ? htmlspecialchars($seller['NID']) : 'Not provided'; ?></td>
                </tr>

                <tr>
                    <td colspan="2">
                        <form id="deleteSellerForm_<?php echo $formId; ?>" method="post" action="../Controller/deleteSeller.php" style="display: inline;">
                            <input type="hidden" name="sellerEmail" value="<?php echo htmlspecialchars($seller['Email']); ?>">
                        </form>
                        <input type="button" value="Remove Seller" style="background-color: red; color: white;" onclick="confirmDeleteSeller('<?php echo htmlspecialchars($seller['Email']); ?>', '<?php echo htmlspecialchars($seller['Name'] ?? $seller['Email']); ?>')">
                    </td>
                </tr>
            </table>
        </div>
    <?php
        endwhile;
    else:
    ?>
        <p>No sellers found.</p>
    <?php endif; ?>
        </main>
    </div>

</body>
</html>