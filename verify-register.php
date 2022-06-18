<?php include_once('api/app.php');
    $email = '';
    $account = '';
    if(isset($_SESSION['email'])) {
        header("location: ./");
    } else if(isset($_GET['regAuth'])) {
        $response = json_decode(GetCredentialById($_GET['regAuth']));
        if($response->status) {
            $email = $response->email;
            $account = $response->account;
        } else {
            //header("location: 404?title=invalid_request&message=credential_not_found");
        }
    } else {
        //header("location: 404?title=invalid_request&message=credential_not_found");
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
    <div id="wrapper">
        <div class="content">
            <div class="auth-content">
                <div class="logo"><a href="./"><img src="assets/res/images/logo/cinebazar.png" alt="icon"></a></div>
                <div class="text lang" key="let_me_know_you">Let me know you</div>
                <p><?php echo $email; ?></p>
                <form id="verifyregisterfor" method="post">
                    <div class="auth-field">
                        <select name="verifyRegUserType">
                            <option value="visitor">Visitor</option>
                            <option value="owner">Owner</option>
                        </select>
                        <span class="fas fa-user-shield"></span>
                    </div>
                    <div class="auth-field">
                        <input type="text" name="verifyRegFirstName" required>
                        <span class="fas fa-user-circle"></span>
                        <label class="lang" key="first_name">First Name</label>
                    </div>
                    <div class="auth-field">
                        <input type="text" name="verifyRegLastName" required>
                        <span class="fas fa-user-circle"></span>
                        <label class="lang" key="last_name">Last Name</label>
                    </div>
                    <div class="auth-field">
                        <input type="text" name="verifyRegPhone" required>
                        <span class="fas fa-phone"></span>
                        <label class="lang" key="mobile">Mobile</label>
                    </div>
                    <div class="auth-field" required>
                        <input type="password" name="verifyRegPass" class="input-pass" required>
                        <span class="fas fa-lock"></span>
                        <label class="lang" key="password">Password</label>
                        <i class="fas fa-eye icon-pass"></i>
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
    <script type="text/javascript">
        var email = '<?php echo $email; ?>';
        var account = '<?php echo $account; ?>';
    </script>
</body>
</html>