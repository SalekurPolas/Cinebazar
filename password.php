<?php include_once('assets/php/database.php');
    if(!isset($_SESSION['recover_email'])) {
        header('location: recover.php');
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Change Password - Cinebazar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="assets/css/login.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Bree+Serif">
</head>
<body>
    <div class="container">
        <div class="password_form">
            <form action="" method="post">
                <h2>Create Password</h2>
                <p>New Password *</p>
                <input type="Password" name="password_user_password" placeholder="Enter new password" required>
                <p>Re-Enter Password *</p>
                <input type="Password" name="password_re_password" placeholder="Re enter password" required>
                <input type="submit" name="password_submit_button" value="Change Password">
                <p class="extra_link">Remember Password? <a href="login.php">Login</a></p>
            </form>
            
            <?php
                if(isset($_SESSION['recover_email']) && isset($_POST['password_user_password']) && isset($_POST['password_re_password'])) {
                    $password_email = $_SESSION['recover_email'];
                    $newPassword = $_POST['password_user_password'];
                    $rePassword = $_POST['password_re_password'];

                    if($newPassword == $rePassword) {
                        $sql = "UPDATE users SET password = '$newPassword' WHERE email = '$password_email'";
                        mysqli_query($conn, $sql);
                        header('location: assets/php/logout.php');
                    } else {
                        echo "<p style='text-align:center; color:black; padding: 5px; margin-top: 10px; border-radius: 4px; background-color: red;'>Passwords Didn't Match</p>";
                    }
                }
            ?>
        </div>
    </div>
</body>
</html>