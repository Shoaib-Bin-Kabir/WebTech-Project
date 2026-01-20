<?php
session_start();

if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn']) {
    header('Location: ./login.php');
    exit();
}

$errors = $_SESSION["errors"] ?? [];
$previousValues = $_SESSION["previousValues"] ?? [];
$signupErr = $_SESSION["signupErr"] ?? "";

unset($_SESSION['errors']);
unset($_SESSION['previousValues']);
unset($_SESSION["signupErr"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMPLE SIGNUP Page</title>
    <link rel="stylesheet" href="Design/auth.css">
    <script src="../Controller/JS/SUPJSval.php"></script>
</head>
<body>
    <div class="page">
        <div class="split">
            <aside class="marketing">
                <div class="marketing-inner">
                    <div class="brand">Zip &amp; Go</div>
                    <div class="motto">Your bag marketplace — carry your style, shop fast.</div>
                    <div class="welcome-text">
                        Create your account to save your favorites, track orders, and shop faster.
                        Whether you're looking for everyday essentials or travel-ready gear, we’ve got you covered.
                    </div>
                    <ul class="welcome-list">
                        <li>Fast signup and secure login</li>
                        <li>Personalized shopping experience</li>
                        <li>Exclusive deals and updates</li>
                    </ul>
                </div>
            </aside>

            <main class="auth">
                <div class="auth-card">
                    <form id="signupForm" method="post" action="../Controller/SUPphpVal.php" enctype="multipart/form-data" onsubmit="return valForm(event)">
                        <h2 class="auth-title">Sign Up</h2>
                        <table class="auth-table">
                            <tr>
                                <td><label for="email">Email:<span style="color: red;">*</span></label></td>
                                <td><input type="email" id="email" name="email" value="<?php echo $previousValues['email'] ?? ''; ?>" required></td>
                                <td><p id="emailError" class="error-text"><?php echo $errors["email"] ?? ''; ?></p></td>
                            </tr>
                            <tr>
                                <td><label for="password">Password:<span style="color: red;">*</span></label></td>
                                <td><input type="password" id="password" name="password" required></td>
                                <td><p id="passwordError" class="error-text"><?php echo $errors["password"] ?? ''; ?></p></td>
                            </tr>
                            <tr>
                                <td><label>Confirm Password:<span style="color: red;">*</span></label></td>
                                <td><input type="password" id="confirm_password" name="confirm_password" required></td>
                                <td><p id="confirmPasswordError" class="error-text"><?php echo $errors["confirmPassword"] ?? ''; ?></p></td>
                            </tr>
                            <tr>
                                <td><label for="name">Name:<span style="color: red;">*</span></label></td>
                                <td><input type="text" id="name" name="name" value="<?php echo $previousValues['name'] ?? ''; ?>" required></td>
                                <td><p id="nameError" class="error-text"><?php echo $errors["name"] ?? ''; ?></p></td>
                            </tr>
                            <tr>
                                <td><label for="phone">Phone Number:<span style="color: red;">*</span></label></td>
                                <td><input type="text" id="phone" name="phone" value="<?php echo $previousValues['phone'] ?? ''; ?>" required></td>
                                <td><p id="phoneError" class="error-text"><?php echo $errors["phone"] ?? ''; ?></p></td>
                            </tr>
                            <tr>
                                <td><label for="nid">NID:<span style="color: red;">*</span></label></td>
                                <td><input type="text" id="nid" name="nid" value="<?php echo $previousValues['nid'] ?? ''; ?>" required></td>
                                <td><p id="nidError" class="error-text"><?php echo $errors["nid"] ?? ''; ?></p></td>
                            </tr>
                            <tr>
                                <td><label for="photo">Profile Photo:</label></td>
                                <td><input type="file" id="photo" name="photo" accept="image/*"></td>
                                <td><p id="photoError" class="error-text"><?php echo $errors["photo"] ?? ''; ?></p></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="error-text"><?php echo $signupErr; ?></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="submit-row"><input type="submit" value="Sign Up"></td>
                            </tr>
                            <tr>
                                <td colspan="3"><a class="helper-link" href="login.php">Already have an account? Login here.</a></td>
                            </tr>
                        </table>
                    </form>
                </div>
            </main>
        </div>

        <footer class="site-footer">
            <div class="footer-title">Zip &amp; Go — Uttora, Dhaka</div>
            <div class="footer-info">House 12, Road 7, Sector 10 · Hotline: +880 17XX-XXXXXX</div>
            <div class="footer-info">support@zipandgo.test · Sat–Thu 10:00–20:00</div>
        </footer>
    </div>
</body>
</html>