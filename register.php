<?php include_once('assets/php/database.php');
    if(isset($_SESSION['email'])){
        header("location: index.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - Cinebazar</title>

    <!-- css, js and font design-->
    <link rel="stylesheet" type="text/css" href="assets/css/register.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Bree+Serif">
    <script type="text/javascript" src="assets/js/functions.js"></script>
</head>
<body>
  	<div class="container">
    	<div class="left">
    		<div class="left_text"></div>
    	</div>
    	<div class="right">
      		<div class="register">
        		<form id="register_form" action="" method="post">
          			<h2>Create New Account</h2>
          			<p>Full Name *</p>
          			<input type="text" name="register_user_name" placeholder="Enter full name" required>
          			<p>Username *</p>
          			<input type="Email" name="register_user_email" placeholder="Enter email" required>
          			<p>Birth Date *</p>
                <input type="Date" name="register_user_date" required>
                <p>Account *</p>
                <select name="register_user_type" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
                <p>Phone No *</p>
          			<input type="text" name="register_user_phone" placeholder="Enter phone number" required>
          			<p>Password *</p>
          			<input type="Password" name="register_user_password" placeholder="Enter password" required>
          			<input id="register_submit" type="submit" value="Register">
          			<h5 style="font-size: 11px;"><i>* required</i></h5>
          			<h3 style="font-size: 16px;">Already Registered? <a href="login.php">Login</a></h3>
                    <h3 style="font-size: 13px;"><a href="" target="_blank">Terms & conditions</a></h3>
        		</form>

                <?php
                    if (isset($_POST['register_user_name'])) {
                        $name = $_POST['register_user_name'];
                        $email = $_POST['register_user_email'];
                        $birth = $_POST['register_user_date'];
                        $type = $_POST['register_user_type'];
                        $phone = $_POST['register_user_phone'];
                        $password = $_POST['register_user_password'];
                        
                        $search = "SELECT * FROM users WHERE email = '$email'";
                        $account = mysqli_query($conn, $search);

                        if(mysqli_num_rows($account) > 0) {
                            echo "<p style='text-align:center; color:black; padding: 5px; margin-top: 10px; border-radius: 4px; background-color: red;'>Account already exist</p>";
                        } else {
                            $sql = "INSERT INTO users (name, email, birth, type, phone, password) VALUES('$name', '$email', '$birth', '$type', '$phone', '$password')";
                    
                            if(mysqli_query($conn, $sql)) {
                                header("location: assets/php/logout.php");
                            } else {
                                echo "<p style='text-align:center; color:red; padding: 5px; margin-top: 10px; border-radius: 4px; background-color: #bbb;'>Registration Failed</p>";
                            }
                        }
                    }
                ?>
      		</div>
    	</div>
  	</div>
</body>
</html>