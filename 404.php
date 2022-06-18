<?php include_once('config/config.php');
    $title = '';
    $message = '';
    if(isset($_SESSION['title']) && isset($_SESSION['message'])) {
        $title = $_SESSION['title'];
        $message = $_SESSION['message'];
    } else if(isset($_GET['title']) && isset($_GET['message'])){
        $title = $_GET['title'];
        $message = $_GET['message'];
    } else {
        $title = "page_not_found";
        $message = "we_are_sorry_that_page_not_found";
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
    <title>Error - Cinebazar</title>

    <!-- css, js and font design-->
    <link rel="icon" href="assets/res/images/logo/icon_circle.png">
    <link rel="stylesheet" href="assets/fonts/fontawesome/css/all.css">
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body>
    <!-- verification page -->
    <div id="wrapper">
        <div class="content">
            <div class="auth-content">
                <div class="logo"><a href="./"><img src="assets/res/images/logo/cinebazar.png" alt="icon"></a></div>
                <div class="text lang" key="<?php echo $title;?>"><?php echo $title;?></div>
                <div class="auth-info lang" key="<?php echo $message;?>"><?php echo $message;?></div>
                <button class="auth-btn"><a href="./" class="lang" key="okay">Okay</a></button>
                <div class="auth-link">
                    <a href="support" class="lang" key="contact_us">Contact Us</a>
                </div>
            </div>
        </div>
        <?php include "includes/footer.php" ?>
    </div>

    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/app.js"></script>
</body>
</html>