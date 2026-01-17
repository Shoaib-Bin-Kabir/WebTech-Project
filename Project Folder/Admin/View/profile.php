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

include "../Model/DBConnectr.php";

$adminEmail = $_SESSION['email'];
$userType = $_SESSION['user_type'];

$db = new DBConnectr();
$connection = $db->openConnection();
$result = $db->getAdminByEmail($connection, $adminEmail);

$adminData = null;
$adminName = "Not provided";
$adminPhone = "Not provided";
$adminPhoto = "";
$adminNID = "Not provided";

if ($result->num_rows > 0) {
    $adminData = $result->fetch_assoc();
    $adminName = $adminData['Name'] ?? "Not provided";
    $adminPhone = $adminData['Phone_Number'] ?? "Not provided";
    $adminPhoto = $adminData['Photo'] ?? "";
    $adminNID = $adminData['NID'] ?? "Not provided";
}

$db->closeConnection($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <script src="../Controller/JS/profileEdit.js"></script>
</head>
<body>
    <?php
    // Display error or success messages
    if (isset($_SESSION['updateError'])) {
        echo '<p style="color: red; font-weight: bold;">' . htmlspecialchars($_SESSION['updateError']) . '</p>';
        unset($_SESSION['updateError']);
    }
    if (isset($_SESSION['updateSuccess'])) {
        echo '<p style="color: green; font-weight: bold;">' . htmlspecialchars($_SESSION['updateSuccess']) . '</p>';
        unset($_SESSION['updateSuccess']);
    }
    ?>

    <div>
        <nav>
            <ul>
                <li><a href="AHomePage.php">Home Page</a></li>
                <li><a href="allHistory.php">See History</a></li>
                <li><a href="ManInventory.php">Manage Inventory</a></li>
                <li><a href="ManSeller.php">Manage Seller</a></li>
                <li><a href="../../Login and Signup/Controller/logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>

    <div>
        <table>
            <tr>
                <td>
                    <div id="photoDisplay">
                        <?php if (!empty($adminPhoto) && file_exists("../" . $adminPhoto)): ?>
                            <img src="<?php echo htmlspecialchars("../" . $adminPhoto); ?>" 
                                 alt="Profile Photo" 
                                 width="150" 
                                 height="150">
                        <?php else: ?>
                            <div style="width: 150px; height: 150px; border: 1px solid #ccc;">
                                <p>No profile photo uploaded</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div id="photoForm" style="display: none;">
                        <form id="photoFormElement" method="post" action="../Controller/updateProfile.php" enctype="multipart/form-data">
                            <input type="hidden" name="updateType" value="photo">
                            <div style="width: 150px; height: 150px; border: 1px solid #ccc;">
                                <div id="photoPreview"></div>
                            </div>
                            <input type="file" name="photo" accept="image/*" onchange="previewPhoto(this)" required>
                        </form>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="button" id="photoBtn" value="Edit Profile Photo" onclick="togglePhotoEdit()">
                </td>
            </tr>
        </table>
    </div>

    <div>
        <table>
            <tr>
                <td>Name:</td>
                <td>
                    <span id="nameDisplay">
                        <?php 
                        if ($adminData && !empty($adminData['Name'])) {
                            echo htmlspecialchars($adminData['Name']);
                        } else {
                            echo "Name not provided. Please update your profile.";
                        }
                        ?>
                    </span>
                    <form id="nameForm" method="post" action="../Controller/updateProfile.php" style="display: none;">
                        <input type="hidden" name="updateType" value="name">
                        <input type="text" name="name" value="<?php echo htmlspecialchars($adminName); ?>" required>
                    </form>
                </td>
                <td>
                    <input type="button" id="nameBtn" value="Edit Name" onclick="toggleEdit('name')">
                </td>
            </tr>

            <tr>
                <td>National ID:</td>
                <td>
                    <span id="nidDisplay">
                        <?php 
                        if ($adminData && !empty($adminData['NID'])) {
                            echo htmlspecialchars($adminData['NID']);
                        } else {
                            echo "NID not provided. Please update your profile.";
                        }
                        ?>
                    </span>
                    <form id="nidForm" method="post" action="../Controller/updateProfile.php" style="display: none;">
                        <input type="hidden" name="updateType" value="nid">
                        <input type="text" name="nid" value="<?php echo htmlspecialchars($adminNID); ?>" required>
                    </form>
                </td>
                <td>
                    <input type="button" id="nidBtn" value="Edit NID" onclick="toggleEdit('nid')">
                </td>
            </tr>

            <tr>
                <td>Email:</td>
                <td>
                    <span id="emailDisplay">
                        <?php echo htmlspecialchars($adminEmail); ?>
                    </span>
                    <form id="emailForm" method="post" action="../Controller/updateProfile.php" style="display: none;">
                        <input type="hidden" name="updateType" value="email">
                        <input type="email" name="email" value="<?php echo htmlspecialchars($adminEmail); ?>" required>
                    </form>
                </td>
                <td>
                    <input type="button" id="emailBtn" value="Edit Email" onclick="toggleEdit('email')">
                </td>
            </tr>

            <tr>
                <td>Phone Number:</td>
                <td>
                    <span id="phoneDisplay">
                        <?php 
                        if ($adminData && !empty($adminData['Phone_Number'])) {
                            echo htmlspecialchars($adminData['Phone_Number']);
                        } else {
                            echo "Phone number not provided. Please update your profile.";
                        }
                        ?>
                    </span>
                    <form id="phoneForm" method="post" action="../Controller/updateProfile.php" style="display: none;">
                        <input type="hidden" name="updateType" value="phone">
                        <input type="text" name="phone" value="<?php echo htmlspecialchars($adminPhone); ?>" required>
                    </form>
                </td>
                <td>
                    <input type="button" id="phoneBtn" value="Edit Phone Number" onclick="toggleEdit('phone')">
                </td>
            </tr>

            <tr>
                <td colspan="3">
                    <a href="../../Login and Signup/Controller/logout.php">
                        <button>Logout</button>
                    </a>
                </td>
            </tr>     
        </table>
    </div>
</body>
</html>