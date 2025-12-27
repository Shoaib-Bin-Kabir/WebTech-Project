<?php
session_start();

if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn']) {
    header('Location: ./dashboard.php');
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
    <script src="../Controller/JS/SUPJSval.php"></script>
</head>
<body>
    <form id="signupForm" method="post" action="../Controller/SUPphpVal.php" onsubmit="return valForm(event)">
        <h2>Signup Form</h2>
        <table>
        <tr>
         <td><label for="email">Email:</label></td>
         <td><input type="email" id="email" name="email" ></td>
         <td><p id="emailError" style="color: red;"><?php echo $errors["email"] ?? ''; ?></p></td>
         
        </tr>
        <tr>
        <td><label for="password">Password:</label></td>
        <td><input type="password" id="password" name="password" ></td>
        <td><p id="passwordError" style="color: red;"> <?php echo $errors["password"] ?? ''; ?></p></td>
        <tr>
           <td></td>
           <td style="color: red;"><?php echo $signupErr; ?></td>
        </tr>
        
        </tr>

        <tr>
            <td><Label>Confirm Password</Label></td>
            <td>
            <input type="password" id="confirm_password" name="confirm_password" ></td>
            <td><p id="confirmPasswordError" style="color: red;"><?php echo $errors["confirmPassword"] ?? ''; ?></p></td>
           
        </tr>

        <tr>
        <td colspan="2"><input type="submit" value="Sign Up"></td>
        </tr>

        <tr>
        <td colspan="2"><a href="login.php">Already have an account? Login here.</a></td>
        </tr>


        </table>
    </form>
</body>
</html>