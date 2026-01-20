<?php
session_start();

if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn']) {
    $userType = $_SESSION['user_type'] ?? 'Customer';
    
    if ($userType === 'Admin') {
        header('Location: ../../Admin/View/AHomePage.php');
    } elseif ($userType === 'Seller') {
        header('Location: ../../Seller/View/SHomePage.php');
    } else {
        header('Location: ./login.php');
    }
    exit();
}

$errors = $_SESSION["errors"] ?? [];
$previousValues = $_SESSION["previousValues"] ?? [];
$loginErr = $_SESSION["loginErr"] ?? "";

unset($_SESSION['errors']);
unset($_SESSION['previousValues']);
unset($_SESSION['loginErr']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="Design/auth.css">
    <script src="../Controller/JS/LOGJSval.php"></script>
</head>
<body>
    <div class="page">
        <div class="split">
            <aside class="marketing">
                <div class="marketing-inner">
                    <div class="brand">Zip &amp; Go</div>
                    <div class="motto">Your bag marketplace — carry your style, shop fast.</div>
                    <div class="welcome-text">
                        Discover backpacks, handbags, travel bags, laptop bags, and more — all in one place.
                        Find the right fit for your daily commute, university, office, or next trip.
                    </div>
                    <ul class="welcome-list">
                        <li>Browse trending collections and new arrivals</li>
                        <li>Shop securely and get order updates</li>
                        <li>Quality styles for every budget</li>
                    </ul>
                </div>
            </aside>

            <main class="auth">
                <div class="auth-card">
                    <form id="loginForm" method="post" action="../Controller/LOGphpVal.php" onsubmit="return valloginForm(event)">
                        <h2 class="auth-title">Login</h2>
                        <table class="auth-table">
                            <tr>
                                <td><label for="email">Email:</label></td>
                                <td><input type="email" id="email" name="email" value="<?php echo $previousValues['email'] ?? ''; ?>"></td>
                            </tr>
                            <tr>
                                <td><label for="password">Password:</label></td>
                                <td>
                                    <input type="password" id="password" name="password">
                                    <p id="passwordError" class="error-text"><?php echo $errors["password"] ?? ''; ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="error-text"><?php echo $loginErr; ?></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="submit-row"><input type="submit" value="Login"></td>
                            </tr>
                            <tr>
                                <td colspan="2"><a class="helper-link" href="signup.php">Don't have an account? Sign up here.</a></td>
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