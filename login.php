<?php include_once('config/config.php');
    if(isset($_SESSION['email'])) {
        header("location: ./");
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Salekur Rahaman"/>
    <meta name="description" content=""/>
    <meta name="keywords" content="">
    <title>Login - Cinebazar</title>

    <!-- css, js and font design-->
    <link rel="icon" href="assets/res/images/logo/icon_circle.png">
    <link rel="stylesheet" href="assets/fonts/fontawesome/css/all.css">
    <link rel="stylesheet" href="assets/css/auth.css">
    <link rel="stylesheet" href="assets/css/modal.css">
</head>
<body>
    <!-- login page -->
    <div id="wrapper">
        <div class="content">
            <div class="auth-content">
                <div class="logo"><a href="./"><img src="assets/res/images/logo/cinebazar.png" alt="icon"></a></div>
                <div class="text lang" key="enter_email_and_password">Enter Email and Password</div>
                <form id="loginfor" method="post">
                    <div class="auth-field">
                        <input type="text" name="loginEmail" required>
                        <span class="fas fa-user"></span>
                        <label class="lang" key="email_or_phone">Email or Phone</label>
                    </div>
                    <div class="auth-field">
                        <input type="password" name="loginPass" class="input-pass" required>
                        <span class="fas fa-lock"></span>
                        <label class="lang" key="enter_password">Enter Password</label>
                        <i class="fas fa-eye icon-pass"></i>
                    </div>
                    <button class="auth-btn lang" key="login">Login</button>
                    <div class="auth-link">
                        <a href="recover" class="lang" key="reset_password">Reset Password</a>
                        <span class="lang" key="or">or</span>
                        <a href="register" class="lang" key="register">Register</a>
                    </div>
                </form>
            </div>
        </div>
        <?php include "includes/footer.php" ?>
    </div>
    <?php include "includes/modal.php" ?>

    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/app.js"></script>
	<script type="text/javascript">
        var backUrl = '<?php if(isset($_GET['url'])) echo $_GET['url']; ?>';
    </script>
</body>
</html>