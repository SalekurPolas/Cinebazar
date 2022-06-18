<?php include_once('api/app.php');
    if(isset($_SESSION['email'])) {
        header('location: ./');
    } else if(isset($_GET['recAuth'])) {
        $response = json_decode(GetCredentialById($_GET['recAuth']));
        if(!$response->status) {
            header("location: 404?title=invalid_request&message=credential_not_found");
        }
    } else {
        header("location: 404?title=invalid_request&message=credential_not_found");
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
    <title>Create Password - Cinebazar</title>
    
    <!-- css, js and font design-->
    <link rel="icon" href="assets/res/images/logo/icon_circle.png">
    <link rel="stylesheet" href="assets/fonts/fontawesome/css/all.css">
    <link rel="stylesheet" href="assets/css/auth.css">
    <link rel="stylesheet" href="assets/css/modal.css">
</head>
<body>
    <!-- change password page -->
    <div id="wrapper">
        <div class="content">
            <div class="auth-content">
                <div class="logo"><a href="./"><img src="assets/res/images/logo/cinebazar.png" alt="icon"></a></div>
                <div class="text lang" key="create_new_password">Create new password</div>
                <form id="verifyrecoverfor" method="post">
                    <div class="auth-field">
                        <input type="password" name="verifyRecPass" required>
                        <span class="fas fa-lock"></span>
                        <label class="lang" key="new_password">New password</label>
                    </div>
                    <div class="auth-field">
                        <input type="password" name="verifyRecRePass" required>
                        <span class="fas fa-lock"></span>
                        <label class="lang" key="re_enter_password">Re enter password</label>
                    </div>
                    <button class="auth-btn lang" key="update">Update</button>
                    <div class="auth-link">
                        <a href="support" class="lang" key="contact_us">Contact Us</a>
						<span class="lang" key="or"></span>
						<a href="login" class="lang" key="login">Login</a>
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
        var auth = '<?php echo $_GET['recAuth'];?>';
    </script>
</body>
</html>