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

include "../Model/DBConnectr.php";

$db = new DBConnectr();
$connection = $db->openConnection();
$result = $db->getAdminByEmail($connection, $userEmail);

$adminData = null;
$adminId = "";
$adminName = "Not provided";
$adminPhone = "Not provided";
$adminPhoto = "";

if ($result && $result->num_rows > 0) {
    $adminData = $result->fetch_assoc();
    $adminId = $adminData['ID'] ?? "";
    $adminName = $adminData['Name'] ?? "Not provided";
    $adminPhone = $adminData['Phone_Number'] ?? "Not provided";
    $adminPhoto = $adminData['Photo'] ?? "";
}

$db->closeConnection($connection);
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
        <?php if (!empty($adminPhoto) && file_exists("../" . $adminPhoto)): ?>
            <img src="<?php echo htmlspecialchars("../" . $adminPhoto); ?>" alt="Profile Photo" width="150" height="150">
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
            <td><?php echo htmlspecialchars($adminName); ?></td>
        </tr>

        <tr>
            <td><strong>Admin ID:</strong></td>
            <td><?php echo htmlspecialchars($adminId); ?></td>
        </tr>

        <tr>
            <td><strong>Email:</strong></td>
            <td><?php echo htmlspecialchars($userEmail); ?></td>
        </tr>

        <tr>
            <td><strong>Phone:</strong></td>
            <td><?php echo htmlspecialchars($adminPhone); ?></td>
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
        <p>Welcome, <?php echo htmlspecialchars($adminName); ?>.</p>
    </div>

</body>
</html>