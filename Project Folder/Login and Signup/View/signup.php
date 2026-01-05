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
    <form id="signupForm" method="post" action="../Controller/SUPphpVal.php" enctype="multipart/form-data" onsubmit="return valForm(event)">
        <h2>Signup Form</h2>
        <table>
        <tr>
         <td><label for="email">Email:<span style="color: red;">*</span></label></td>
         <td><input type="email" id="email" name="email" value="<?php echo $previousValues['email'] ?? ''; ?>" required></td>
         <td><p id="emailError" style="color: red;"><?php echo $errors["email"] ?? ''; ?></p></td>
         
        </tr>
        <tr>
        <td><label for="password">Password:<span style="color: red;">*</span></label></td>
        <td><input type="password" id="password" name="password" required></td>
        <td><p id="passwordError" style="color: red;"> <?php echo $errors["password"] ?? ''; ?></p></td>
        
        </tr>

        <tr>
            <td><Label>Confirm Password:<span style="color: red;">*</span></Label></td>
            <td><input type="password" id="confirm_password" name="confirm_password" required></td>
            <td><p id="confirmPasswordError" style="color: red;"><?php echo $errors["confirmPassword"] ?? ''; ?></p></td>
           
        </tr>

        <tr>
         <td><label for="name">Name:<span style="color: red;">*</span></label></td>
         <td><input type="text" id="name" name="name" value="<?php echo $previousValues['name'] ?? ''; ?>" required></td>
         <td><p id="nameError" style="color: red;"><?php echo $errors["name"] ?? ''; ?></p></td>
        </tr>

        <tr>
         <td><label for="phone">Phone Number:<span style="color: red;">*</span></label></td>
         <td><input type="text" id="phone" name="phone" value="<?php echo $previousValues['phone'] ?? ''; ?>" required></td>
         <td><p id="phoneError" style="color: red;"><?php echo $errors["phone"] ?? ''; ?></p></td>
        </tr>

        <tr>
         <td><label for="nid">NID:<span style="color: red;">*</span></label></td>
         <td><input type="text" id="nid" name="nid" value="<?php echo $previousValues['nid'] ?? ''; ?>" required></td>
         <td><p id="nidError" style="color: red;"><?php echo $errors["nid"] ?? ''; ?></p></td>
        </tr>

        <tr>
         <td><label for="photo">Profile Photo:</label></td>
         <td><input type="file" id="photo" name="photo" accept="image/*"></td>
         <td><p id="photoError" style="color: red;"><?php echo $errors["photo"] ?? ''; ?></p></td>
        </tr>

        <tr>
           <td></td>
           <td style="color: red;"><?php echo $signupErr; ?></td>
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