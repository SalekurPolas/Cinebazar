<?php include_once('config/config.php');
    if(isset($_SESSION['email'])) {
        // user email found in session
        // redirect user to home page
        header("location: ./");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Salekur Rahaman"/>
    <meta name="description" content=""/>
    <meta name="keywords" content="">
    <title>Register - Cinebazar</title>
    
    <!-- css, js and font design-->
    <link rel="icon" href="assets/res/images/logo/icon_circle.png">
    <link rel="stylesheet" href="assets/fonts/fontawesome/css/all.css">
    <link rel="stylesheet" href="assets/css/auth.css">
    <link rel="stylesheet" href="assets/css/modal.css">
</head>
<body>
    <!-- register page -->
    <div id="wrapper">
        <div class="content">
            <div class="auth-content">
                <div class="logo"><a href="./"><img src="assets/res/images/logo/cinebazar.png" alt="icon"></a></div>
                <div class="text lang" key="create_new_account">Create New Account</div>
                <form id="registerfor" method="post">
                    <div class="auth-field">
                        <input type="text" name="regEmail" required>
                        <span class="fas fa-envelope"></span>
                        <label class="lang" key="enter_email">Enter Email</label>
                    </div>
                    <button class="auth-btn lang" key="register">Register</button>
                    <div class="auth-link">
                        <a href="support" class="lang" key="contact_us">Contact Us</a>
                        <span class="lang" key="or">or</span>
                        <a href="login" class="lang" key="login">Login</a>
                    </div>
                </form>
            </div>
        </div>
        <?php include "includes/footer.php" ?>
    </div>
    <?php include "includes/modal.php" ?>

    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/moment.js"></script>
    <script src="assets/js/app.js"></script>

</body>
</html>