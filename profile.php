<?php include_once('config/config.php');
    if(!isset($_GET['username'])) {
        header("location: ./");
    } else {
        $username = $_GET['username'];
        $sql = "SELECT first_name, last_name, email, username, gender, account_status, profile_image FROM c_users WHERE username = '$username'";
        $result = mysqli_query($conn, $sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $user_first_name = $row["first_name"];
                $user_last_name = $row["last_name"];
                $user_email = $row["email"];
                $user_username = $row["username"];
                $user_gender = $row["gender"];
                $user_account_status = $row["account_status"];
                $user_profile_image = $row["profile_image"];
            }
        } else {
            header("location: ../404");
        }
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
    <title><?php if(isset($user_first_name)) echo $user_first_name. " " .$user_last_name; else echo "Profile"; ?> | Cinebazar User</title>

    <!-- css, js and font design-->
    <link rel="icon" href="../assets/res/images/logo/icon_circle.png">
    <link rel="stylesheet" href="../assets/fonts/fontawesome/css/all.css">
    <link rel="stylesheet" href="../assets/css/profile.css">
</head>
<body>
    <div class="wrapper">
        <div class="img-area">
            <div class="inner-area">
                <img src="<?php if (isset($user_profile_image) && $user_profile_image != null && file_exists('./assets/res/images/profiles/' .$user_profile_image)) {
                    echo '../assets/res/images/profiles/' .$user_profile_image;} else { echo '../assets/res/images/avatar.png'; }
                ?>" alt="<?php if(isset($user_first_name)) echo $user_first_name. " " .$user_last_name; ?>">
            </div>
        </div>
        <div class="icon arrow"><i class="fas fa-arrow-left"></i></div>
        <div class="icon dots"><i class="fas fa-ellipsis-v"></i></div>
        <div class="name"><?php if(isset($user_email)) echo $user_first_name. " " .$user_last_name; ?></div>
        <div class="about"><?php if(isset($user_email)) echo $user_email; ?></div>
        <div class="social-icons">
            <a href="#" class="fb"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="twitter"><i class="fab fa-twitter"></i></a>
            <a href="#" class="insta"><i class="fab fa-instagram"></i></a>
            <a href="#" class="yt"><i class="fab fa-youtube"></i></a>
        </div>
        <div class="buttons">
            <button>Message</button>
            <button>Follow</button>
        </div>
        <div class="social-share">
            <div class="row">
                <i class="far fa-heart"></i>
                <span>20.4k</span>
            </div>
            <div class="row">
                <i class="far fa-pencil-square"></i>
                <span>14.3k</span>
            </div>
            <div class="row">
                <i class="fas fa-share"></i>
                <span>12.8k</span>
            </div>
        </div>
    </div>
</body>
</html>