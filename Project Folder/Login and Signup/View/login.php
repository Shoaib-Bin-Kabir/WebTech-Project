<?php
session_start();

if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn']) {
    header('Location: ./dashboard.php');
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
    <script src="../Controller/JS/LOGJSval.php"></script>
</head>
<body>
    <form id="loginForm" method="post" action="../Controller/LOGphpVal.php" onsubmit="return valloginForm(event)">
        <h2>Login Form</h2>
        <table>
        <tr>
        <td><label for="email">Email:</label></td>
        <td><input type="email" id="email" name="email" value="<?php echo $previousValues['email'] ?? ''; ?>" ></td>
        </tr>
        <tr>
        <td><label for="password">Password:</label></td>
        <td><input type="password" id="password" name="password" ></td>
        <p id="passwordError" style="color: red;"><?php echo $errors["password"] ?? ''; ?></p>
        </tr>
        <tr>
        <td></td>
        <td style="color: red;"><?php echo $loginErr; ?></td>
        </tr>
        <tr>
        <td colspan="2"><input type="submit" value="Login"></td>
        </tr>
        <tr>
        <td colspan="2"><a href="signup.php">Don't have an account? Sign up here.</a></td>
        </tr>
        </table>
    </form>
</body>

</html>