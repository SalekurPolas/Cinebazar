<?php include_once('assets/php/database.php');
    if(isset($_SESSION['email'])){
        header("location: index.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forget Password - Cinebazar</title>

    <!--css, js and font-->
    <link rel="stylesheet" type="text/css" href="assets/css/login.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Bree+Serif">
    <script type="text/javascript" src="assets/js/functions.js"></script>
</head>
<body>
    <div class="container">
        <div class="recover_form">
            <form action="" method="post">
                <h2>Forget Password?</h2>
                <p>Username </p>
                <input type="Email" name="recover_user_email" placeholder="Enter email" required>
                <p>Birth Date </p>
                <input type="Date" name="recover_birth_date" onchange="ChangeDateFormat(this, 'y');" required>
                <input type="submit" name="recover_submit_button" onclick="ChangePasswordRequest()" value="Send Request">
                <p class="extra_link login_link">Remember Password? <a href="login.php">Login</a></p>
                <p class="extra_link">Don't have account? <a href="register.php">Register</a></p>
            </form>

            <?php
                $url = "password.php";
                if(isset($_POST['recover_user_email']) && isset($_POST['recover_birth_date'])) {
                    $recover_email = $_POST['recover_user_email'];
                    $recover_date = $_POST['recover_birth_date'];
                    $RecoverSql = "SELECT birth FROM users WHERE email = '$recover_email'";
                    $RecoverResult = $conn->query($RecoverSql);

                    if ($RecoverResult->num_rows > 0) {
                        while($row = $RecoverResult->fetch_assoc()) {
                            if($recover_date == $row["birth"]){
                                $_SESSION['recover_email'] = $recover_email;
                                header('Location: '.$url);
                            } else {
                                echo "<p style='text-align:center; color:black; padding: 5px; margin-top: 10px; border-radius: 4px; background-color: red;'>Incorrect Birth Date</p>";
                            }
                        }
                    } else {
                        echo "<p style='text-align:center; color:red; padding: 5px; margin-top: 10px; border-radius: 4px; background-color: #bbb;'>Account Does not Exist</p>";
                    }
                }
            ?>
        </div>
    </div>
</body>
</html>