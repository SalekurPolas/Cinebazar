<?php include_once('api/app.php');
	$auth = '';
	if(isset($_GET['regEmail']) && isset($_GET['type'])) {
		$response = json_decode(GetCredentialByEmailAndType($_GET['regEmail'], $_GET['type']));
		if($response->status) {
			$auth = $response->id;
		} else {
			header("location: 404?title=invalid_request&message=credential_not_found");
		}
	} else if(isset($_GET['recEmail']) && isset($_GET['type'])) {
		$response = json_decode(GetCredentialByEmailAndType($_GET['recEmail'], $_GET['type']));
		if($response->status) {
			$auth = $response->id;
		} else {
			header("location: 404?title=invalid_request&message=credential_not_found");
		}
	} else if(isset($_GET['regAuth'])) {
		$response = json_decode(GetCredentialById($_GET['regAuth']));
		if($response->status) {
			Header("location: verify-register?regAuth=" .$_GET['regAuth']);
		} else {
			header("location: 404?title=invalid_request&message=credential_not_found");
		}
	} else if(isset($_GET['recAuth'])) {
		$response = json_decode(GetCredentialById($_GET['recAuth']));
		if($response->status) {
			Header("location: verify-recover?recAuth=" .$_GET['recAuth']);
		} else {
			header("location: 404?title=invalid_request&message=credential_not_found");
		}
	} else {
		header("location: 404?title=invalid_request&message=credential_not_found");
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
	<title>Verification - Cinebazar</title>

	<!-- icon, css, js and fonts -->
	<link rel="icon" href="assets/res/images/logo/icon_circle.png">
	<link rel="stylesheet" href="assets/fonts/fontawesome/css/all.css">
	<link rel="stylesheet" href="assets/css/auth.css">
    <link rel="stylesheet" href="assets/css/modal.css">
</head>
<body>
    <!-- verification page -->
    <div id="wrapper">
        <div class="content">
            <div class="auth-content">
                <div class="logo"><a href="./"><img src="assets/res/images/logo/cinebazar.png" alt="icon"></a></div>
                <div class="text lang" key="verify_account">Verify Account</div>
                <form id="verifyfor" method="post">
                    <div class="auth-field">
                        <input type="text" name="verifyCode" required>
                        <span class="fas fa-key"></span>
                        <label class="lang" key="enter_code">Enter verification code</label>
                    </div>
                    <button class="auth-btn lang" key="verify">Verify</button>
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
        var auth = '<?php echo $auth; ?>';
    </script>
</body>
</html>