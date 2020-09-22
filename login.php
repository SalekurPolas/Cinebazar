<?php include_once('assets/php/database.php');
    if(isset($_SESSION['email'])){
        header("location: index.php");
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login - Cinebazar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="assets/css/login.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Bree+Serif">
</head>
<body>
    <div class="container">
        <div class="login_form">
            <form action="" method="post">
                <h2>Login</h2>
                <p>Username </p>
                <input type="Email" name="login_user_email" placeholder="Enter email" autocomplete="on" required>
                <p>Password</p>
                <input type="Password" name="login_user_password" placeholder="Enter password" required>
                <input type="submit" value="Sign In">

                <p class="extra_link recover_link">Forget Password? <a href="recover.php">Recover</a></p>
                <p class="extra_link">Don't have account? <a href="register.php">Register</a></p>
            </form>

            <?php
                if(isset($_POST['login_user_email']) && isset($_POST['login_user_password'])){
                    $login_email = $_POST['login_user_email'];
                    $login_password = $_POST['login_user_password'];

                    $sql = "SELECT id, name, email, birth, type, phone, password, image, details FROM users WHERE email = '$login_email'";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            if($login_password == $row["password"]){
                                $user_id = $row["id"];
                                $user_name = $row["name"];
                                $user_email = $row["email"];
                                $user_birth = $row["birth"];
                                $user_type = $row["type"];
                                $user_phone = $row["phone"];
                                $user_password = $row["password"];
                                $user_image = $row["image"];
                                $user_details = $row["details"];

                                $_SESSION['id'] = $user_id;
                                $_SESSION['name'] = $user_name;
                                $_SESSION['email'] = $user_email;
                                $_SESSION['birth'] = $user_birth;
                                $_SESSION['type'] = $user_type;
                                $_SESSION['phone'] = $user_phone;
                                $_SESSION['password'] = $user_password;
                                $_SESSION['image'] = $user_image;
                                $_SESSION['details'] = $user_details;
                                
                                header("location: index.php");
                            } else {
                                echo "<p style='text-align:center; color:black; padding: 5px; margin-top: 10px; border-radius: 4px; background-color: red;'>Incorrect Password</p>";
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