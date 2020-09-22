<?php ob_start(); include_once('assets/php/database.php');
    if(!isset($_SESSION['email'])){
        header("location: register.php");
    } else {
        $user = $_SESSION['id'];
        $view_id = $_POST['view_id'];
        $view_type = $_POST['view_type'];

        if ($view_id == null || $view_type == null) {
            header("location: ./");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Details - Cinebazar</title>

    <!-- custom css and js -->
    <link rel="stylesheet" type="text/css" href="assets/css/basic.css">
    <link rel="stylesheet" type="text/css" href="assets/css/layout.css">
    <link rel="stylesheet" type="text/css" href="assets/css/view.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Bree+Serif">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">
</head>
<body>
    <!-- navbar section -->
    <header class="sticky">
        <!-- navbar logo -->
        <a href="./"><img src="assets/images/favicon.ico" alt="logo" class="logo"/></a>

        <!-- navbar search -->
        <form action="search.php" class="nav_search" method="post">
            <input type="text" name="search" placeholder="Search movies or hall in Cinebazar">
            <button><i class="fas fa-search"></i></button>
        </form>

        <!-- navbar links -->
        <nav>
            <ul class="nav_link">
                <li><a href="">Admin</a></li>
                <li><a href="">Project</a></li>
                <li><a href="">Contact</a></li>
            </ul>

            <!-- nav links logout -->
            <a href="assets/php/logout.php"><button class="logout"><i class="fas fa-sign-out-alt"></i></button></a>
        </nav>
    </header>

    <!-- body content section -->
    <div class="content">
        <!-- left sidebar section -->
        <div class="left_sidebar sticky">
            <!-- my profile box -->
            <div class="item_box">
                <p class="text_center title mb-5">My Profile</p><hr>
                
                <!-- user profile image -->
                <img class="profile_image center mt-5 mb-20" src="
                <?php if (isset($_SESSION['image']) && ($_SESSION['image']) != null && file_exists('assets/images/profiles/' .$_SESSION['image'])) {
                        echo 'assets/images/profiles/' .$_SESSION['image'];
                    } else { echo 'assets/images/avatar.png'; } 
                ?>" alt="Image" id="user-profile-image-preview">

                <!-- user profile details -->
                <p class="text_center mb-20 ml-20 mr-20 hide fc_blue" id="user-profile-details"><?php if (isset($_SESSION['details']) && $_SESSION['details'] != null) {
                    echo '<script type="text/javascript">ShowContent("user-profile-details");</script>' .$_SESSION['details'];
                } ?></p>
                
                <!-- user profile info -->
                <p class="ml-20 mt-5 fc_green"><i class="fas fa-user-shield mr-20"></i><?php if (isset($_SESSION['type'])) { echo $_SESSION['type']; } ?></p>
                <p class="ml-20 mt-5 fc_blue"><i class="fas fa-user-alt mr-20"></i><?php if (isset($_SESSION['name'])) { echo $_SESSION['name']; } ?></p>
                <p class="ml-20 mt-5 fc_red"><i class="fas fa-envelope-open-text mr-20"></i><?php if (isset($_SESSION['email'])) { echo $_SESSION['email']; } ?></p>
                <p class="ml-20 mt-5 fc_blue"><i class="fas fa-phone-alt mr-20"></i><?php if (isset($_SESSION['phone'])) { echo $_SESSION['phone']; } ?></p>
                <p class="ml-20 mt-5 mb-20 fc_purple"><i class="fas fa-birthday-cake mr-20"></i><?php if (isset($_SESSION['birth'])) { echo $_SESSION['birth']; } ?></p>
                
                <button class="mt-5 mb-5" onclick="ShowContent('edit-profile')"><i class="fas fa-edit mr-5"></i>Edit Profile</button>
            </div>

            <!-- edit profile box -->
            <div class="item_box hide" id="edit-profile">
                <p class="text_center title mb-5">Edit Profile</p><hr>

                <form class="mt-20" action="" method="post" enctype="multipart/form-data">
                    <input type="file" name="edit_profile_image" onchange="PreviewImage('user-profile-image-preview', 'user-profile-image-src')" id="user-profile-image-src">
                    <input type="text" name="edit_profile_name" placeholder="Enter name" class="mt-5" value="<?php if (isset($_SESSION['name'])) { echo $_SESSION['name']; } ?>">
                    <input type="email" name="edit_profile_email" placeholder="Enter email" class="mt-5" value="<?php if (isset($_SESSION['email'])) { echo $_SESSION['email']; } ?>">
                    <input type="text" name="edit_profile_phone" placeholder="Enter phone" class="mt-5" value="<?php if (isset($_SESSION['phone'])) { echo $_SESSION['phone']; } ?>">
                    <input type="Date" name="edit_profile_birth" class="mt-5" value="<?php if (isset($_SESSION['birth'])) { echo $_SESSION['birth']; } ?>">
                    <textarea name="edit_profile_details" placeholder="Enter details" class="mt-5"><?php if (isset($_SESSION['details'])) { echo htmlspecialchars($_SESSION['details']); } ?></textarea>
                    <div class="horizontal mt-5">
                        <button type="submit" name="edit_profile_ok" onclick="HideContent('edit-profile')" class="mr-5">
                            <i class="fas fa-check mr-5"></i>Update
                        </button>
                        <button type="submit" name="edit_profile_cancel" onclick="HideContent('edit-profile')" class="ml-5">
                            <i class="fas fa-times mr-5"></i>Cancel
                        </button>
                    </div>
                </form>

                <?php
                    if (isset($_POST['edit_profile_ok'])) {
                        $upload_image = $_FILES['edit_profile_image']['name'];
                        $profile_name = $_POST['edit_profile_name'];
                        $profile_email = $_POST['edit_profile_email'];
                        $profile_phone = $_POST['edit_profile_phone'];
                        $profile_birth = $_POST['edit_profile_birth'];
                        $profile_details = $_POST['edit_profile_details'];

                        if (($upload_image != null) && ($profile_name != null) && ($profile_email != null) && ($profile_phone != null) && ($profile_birth != null) && ($profile_details != null)) {
                            $image_extension = pathinfo($upload_image, PATHINFO_EXTENSION);
                            $profile_image = 'cinebazar-profile-' .$user. '.' .$image_extension;

                            //creating profiles folder
                            if(!is_dir("assets/images/profiles/")) {
                                mkdir("assets/images/profiles/");
                            }

                            $profile_update_sql = "UPDATE users SET name = '$profile_name', email = '$profile_email', birth = '$profile_birth', phone = '$profile_phone', image = '$profile_image', details = '$profile_details' WHERE id = '$user'";

                            if (!mysqli_query($conn, $profile_update_sql)) {
                                echo '<script type="text/javascript">ShowContent("edit-profile");</script>';
                                echo "<p style='text-align:center; color:black; padding: 5px; margin-top: 10px; border-radius: 4px; background-color: red;'>Update Failed</p>";
                            } else {
                                //copy src file in dir
                                move_uploaded_file($_FILES['edit_profile_image']['tmp_name'], "assets/images/profiles/" .$profile_image);
                                header("location: assets/php/logout.php");
                            }

                        } else {
                            echo '<script type="text/javascript">ShowContent("edit-profile");</script>';
                            echo "<p style='text-align:center; color:black; padding: 5px; margin-top: 10px; border-radius: 4px; background-color: red;'>Please Insert Value</p>";
                        }
                    }
                ?>
            </div>

             <!-- create theater box -->
             <div class="item_box">
                <button id="item-box-admin-theater-edit" onclick="AlterDisplayContent('item-box-admin-theater-edit', 'item-box-admin-theater-form')">
                    <i class="fas fa-film mr-5"></i>Create Theater
                </button>
                <form id="item-box-admin-theater-form" class="hide" method="post">
                    <input type="text" name="admin_theater_name_edit" placeholder="Theater name">
                    <input type="text" name="admin_theater_address_edit" placeholder="Theater address" class="mt-5">
                    <div class="horizontal mt-5">
                        <button type="submit" name="admin_theater_create_button" onclick="AlterDisplayContent('item-box-admin-theater-form', 'item-box-admin-theater-edit')" class="mr-5">
                            <i class="fas fa-check mr-5"></i>Create
                        </button>
                        <button type="submit" name="admin_theater_cancel_button" onclick="AlterDisplayContent('item-box-admin-theater-form', 'item-box-admin-theater-edit')" class="ml-5">
                            <i class="fas fa-times mr-5"></i>Cancel
                        </button>
                    </div>
                </form>

                <?php
                    if (isset($_POST['admin_theater_create_button'])) {
                        $theater_name = $_POST['admin_theater_name_edit'];
                        $theater_address = $_POST['admin_theater_address_edit'];

                        if ( ($theater_name != null) && ($theater_address != null) ) {
                            $search = "SELECT * FROM theaters WHERE name = '$theater_name'";
                            $theater = mysqli_query($conn, $search);
                            if(mysqli_num_rows($theater) > 0) {
                                echo "<p style='text-align:center; color:black; padding: 5px; margin-top: 10px; border-radius: 4px; background-color: red;'>Theater already exist</p>";
                            } else {
                                $sql = "INSERT INTO theaters (admin, name, address, notice) VALUES('$user', '$theater_name', '$theater_address', '')";

                                if(mysqli_query($conn, $sql)) {
                                    header("location: ./");
                                } else {
                                    echo "<p style='text-align:center; color:red; padding: 5px; margin-top: 10px; border-radius: 4px; background-color: #bbb;'>Creation Failed</p>";
                                }
                            }
                        } else {
                            echo '<script type="text/javascript">AlterDisplayContent("item-box-admin-theater-edit", "item-box-admin-theater-form");</script>';
                            echo "<p style='text-align:center; color:black; padding: 5px; margin-top: 10px; border-radius: 4px; background-color: red;'>Please Insert Value</p>";
                        }  
                    }
                ?>
            </div>

            <!-- update notice box -->
            <div class="item_box" id="admin-update-notice">
                <button id="item-box-admin-notice-edit" onclick="AlterDisplayContent('item-box-admin-notice-edit', 'item-box-admin-notice-form')">
                    <i class="fas fa-paper-plane mr-5"></i>Update Notice
                </button>
                <form id="item-box-admin-notice-form" class="hide" method="post">
                    <?php
                        $movie_name_sql = "SELECT id, name, notice FROM theaters WHERE admin = '$user'";
                        $movie_name_result = $conn->query($movie_name_sql);

                        if ($movie_name_result->num_rows > 0) {
                            ?>
                                <select name="admin_notice_select" required>
                                    <?php
                                        while($movie_name_row = $movie_name_result->fetch_assoc()) {
                                            $theater_name = $movie_name_row["name"];
                                            ?>
                                                <option><?php echo $theater_name; ?></option>
                                            <?php
                                        }
                                    ?>
                                </select>
                            <?php
                        }
                    ?>
                    <textarea name="admin_notice_text" class="mt-5"></textarea>

                    <div class="horizontal mt-5">
                        <button type="submit" name="admin_notice_update_button" onclick="AlterDisplayContent('item-box-admin-notice-form', 'item-box-admin-notice-edit')" class="mr-5">
                            <i class="fas fa-check mr-5"></i>Send
                        </button>
                        <button type="submit" name="admin_notice_cancel_button" onclick="AlterDisplayContent('item-box-admin-notice-form', 'item-box-admin-notice-edit')" class="ml-5">
                            <i class="fas fa-times mr-5"></i>Cancel
                        </button>
                    </div>
                </form>

                <?php
                    $theater_search = $conn->query("SELECT * FROM theaters WHERE admin = '$user'");
                    if ($theater_search->num_rows == 0) {
                        echo '<style type="text/css">
                            #admin-update-notice {
                                display: none;
                            }
                        </style>';
                    }

                    if (isset($_POST['admin_notice_update_button'])) {
                        $theater_select = $_POST['admin_notice_select'];
                        $notice_text = $_POST['admin_notice_text'];

                        if (($theater_select != null) && ($notice_text != null)) {
                            $update_notice_sql = "UPDATE theaters SET notice = '$notice_text' WHERE name = '$theater_select'";

                            if (!mysqli_query($conn, $update_notice_sql)) {
                                echo "<p style='text-align:center; color:black; padding: 5px; margin-top: 10px; border-radius: 4px; background-color: red;'>Update Failed: </p>";
                            } else {
                                header("location: ./");
                            }
                        } else {
                            echo '<script type="text/javascript">AlterDisplayContent("item-box-admin-notice-edit", "item-box-admin-notice-form");</script>';
                            echo "<p style='text-align:center; color:black; padding: 5px; margin-top: 10px; border-radius: 4px; background-color: red;'>Please Insert Value</p>";
                        }
                    }
                ?>
            </div>

            <!-- inquiry box -->
            <div class="item_box">
                <p class="text_center title mb-5">Complain</p><hr>

                <form class="complain mt-5" action="" method="post">
                    <select name="edit_complain_type">
                        <option value="Theater">Theater</option>
                        <option value="Profile">Profile</option>
                        <option value="Movie">Movie</option>
                    </select>
                    <input type="text" name="edit_complain_name" placeholder="Enter name" class="mt-5">
                    <input type="text" name="edit_complain_id" placeholder="Enter id from details" class="mt-5">
                    <textarea name="edit_complain_details" placeholder="Enter complain details" class="mt-5"></textarea>
                    <div class="horizontal mt-5">
                        <button type="submit" name="edit_complain_ok" class="mr-5">
                            <i class="fas fa-check mr-5"></i>Send
                        </button>
                        <button type="submit" name="edit_complain_cancel" class="ml-5">
                            <i class="fas fa-times mr-5"></i>Cancel
                        </button>
                    </div>
                    <p class="text_center mt-20 ml-20 mr-20">You can complain anyone, any movie or any theater at this website without any worry. We do not publish your informations with others</p>
                </form>

                <?php
                    if (isset($_POST['edit_complain_ok'])) {
                        $complain_type = $_POST['edit_complain_type'];
                        $complain_name = $_POST['edit_complain_name'];
                        $complain_id = $_POST['edit_complain_id'];
                        $complain_details = $_POST['edit_complain_details'];

                        if ( $complain_name != null && $complain_id != null && $complain_details != null ) {
                            $complain_sql = "INSERT INTO complains (type, name, content, details) VALUES('$complain_type', '$complain_name', '$complain_id', '$complain_details')";

                            if(mysqli_query($conn, $complain_sql)) {
                                header("location: ./");
                            } else {
                                echo "<p style='text-align:center; color:red; padding: 5px; margin-top: 10px; border-radius: 4px; background-color: #bbb;'>Complain Failed</p>";
                            }
                        }
                    }
                ?>
            </div>
        </div>

        <!-- main content section -->
        <div class="main_content">
            <!-- details view section -->
            <div class="item_box view_box hide" id="view-box">
                <?php
                    if ($view_type == "movie") {
                        echo '<script type="text/javascript">ShowContent("view-box");</script>';

                        $movie_info_sql = "SELECT status, date, title, theater, genre, country, actor, review, reviewer, image, details FROM movies WHERE id = '$view_id'";
                        $movie_info_result = $conn->query($movie_info_sql);

                        while($movie_info_row = $movie_info_result->fetch_assoc()) {
                            $movie_status = $movie_info_row["status"];
                            $movie_date = $movie_info_row["date"];
                            $movie_title = $movie_info_row["title"];
                            $movie_theater = $movie_info_row["theater"];
                            $movie_genre = $movie_info_row["genre"];
                            $movie_country = $movie_info_row["country"];
                            $movie_actor = $movie_info_row["actor"];
                            $movie_review = $movie_info_row["review"];
                            $movie_reviewer = $movie_info_row["reviewer"];
                            $movie_image = $movie_info_row["image"];
                            $movie_details = $movie_info_row["details"];

                            ?>
                                <div class="left">
                                    <img src="<?php echo 'assets/images/movies/' .$movie_image; ?>" alt="Image">
                                    <button>
                                        <?php
                                            if ($movie_reviewer == 0 || $movie_review == 0) {
                                                ?>
                                                    <p class="fc_purple fs_14">
                                                        <i class="far fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                        <i class="far fa-star"></i>
                                                    </p>
                                                <?php
                                            } else {
                                                $stars = $movie_review / $movie_reviewer;

                                                if ($stars < 1) {
                                                    ?>
                                                        <p class="fc_purple fs_14">
                                                            <i class="far fa-star"></i>
                                                            <i class="far fa-star"></i>
                                                            <i class="far fa-star"></i>
                                                            <i class="far fa-star"></i>
                                                            <i class="far fa-star"></i>
                                                        </p>
                                                    <?php
                                                } elseif ($stars >= 1 && $stars < 2) {
                                                    ?>
                                                        <p class="fc_purple fs_14">
                                                            <i class="fas fa-star"></i>
                                                            <i class="far fa-star"></i>
                                                            <i class="far fa-star"></i>
                                                            <i class="far fa-star"></i>
                                                            <i class="far fa-star"></i>
                                                        </p>
                                                    <?php
                                                } elseif ($stars >= 2 && $stars < 3) {
                                                    ?>
                                                        <p class="fc_purple fs_14">
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="far fa-star"></i>
                                                            <i class="far fa-star"></i>
                                                            <i class="far fa-star"></i>
                                                        </p>
                                                    <?php
                                                } elseif ($stars >= 3 && $stars < 4) {
                                                    ?>
                                                        <p class="fc_purple fs_14">
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="far fa-star"></i>
                                                            <i class="far fa-star"></i>
                                                        </p>
                                                    <?php
                                                } elseif ($stars >= 4 && $stars < 5) {
                                                    ?>
                                                        <p class="fc_purple fs_14">
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="far fa-star"></i>
                                                        </p>
                                                    <?php
                                                } elseif ($stars >= 5) {
                                                    ?>
                                                        <p class="fc_purple fs_14">
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                            <i class="fas fa-star"></i>
                                                        </p>
                                                    <?php
                                                }
                                            }
                                        ?>
                                    </button>
                                    <button class="modal_open mt-5"><i class="fas fa-thumbs-up mr-5"></i>Rate Now</button>
                                </div>
                                <div class="right">
                                    <p>Name: <p class="mb-20 fs_20 fc_green"><?php echo $movie_title ?></p></p>
                                    <p>Status: <p class="mb-5 fs_16 fc_green"><?php echo $movie_status ?></p></p>
                                    <p>Year: <p class="mb-5 fs_16 fc_green"><?php echo $movie_date ?></p></p>
                                    <p>Genre: <p class="mb-5 fs_16 fc_green"><?php echo $movie_genre ?></p></p>
                                    <p>Theater: <p class="mb-5 fs_16 fc_green"><?php echo $movie_theater ?></p></p>
                                    <p>Country: <p class="mb-5 fs_16 fc_green"><?php echo $movie_country ?></p></p>
                                    <p>Stars: <p class="mb-5 fs_16 fc_green"><?php echo $movie_actor ?></p></p>
                                    <p>ID: <p class="mb-20 fs_16 fc_green"><?php echo $view_id ?></p></p>
                                    <p>Details: <p class="mb-5 fs_16 fc_green"><?php echo $movie_details ?></p></p>
                                </div>
                            <?php
                        }
                    } elseif ($view_type == "profile") {
                        echo '<script type="text/javascript">ShowContent("view-box");</script>';

                        $profile_info_sql = "SELECT name, email, type, phone, image, details FROM users WHERE id = '$view_id'";
                        $profile_info_result = $conn->query($profile_info_sql);

                        while($profile_info_row = $profile_info_result->fetch_assoc()) {
                            $profile_name = $profile_info_row["name"];
                            $profile_email = $profile_info_row["email"];
                            $profile_type = $profile_info_row["type"];
                            $prodile_phone = $profile_info_row["phone"];
                            $profile_image = $profile_info_row["image"];
                            $profile_details = $profile_info_row["details"];

                            ?>
                                <div class="left">
                                    <img src="<?php if ($profile_image != null && file_exists('assets/images/profiles/' .$profile_image)) {
                                        echo 'assets/images/profiles/' .$profile_image; } 
                                        else { echo 'assets/images/avatar.png'; } ?>" alt="Image">
                                </div>
                                <div class="right">
                                    <p>Name: <p class="mb-20 fs_20 fc_green"><?php echo $profile_name ?></p></p>
                                    <p>Type: <p class="mb-5 fs_16 fc_green"><?php echo $profile_type ?></p></p>
                                    <p>Email: <p class="mb-5 fs_16 fc_green"><?php echo $profile_email ?></p></p>
                                    <p>Phone: <p class="mb-5 fs_16 fc_green"><?php echo $prodile_phone ?></p></p>
                                    <p>ID: <p class="mb-20 fs_16 fc_green"><?php echo $view_id ?></p></p>
                                    <p>Details: <p class="mb-5 fs_16 fc_green"><?php echo $profile_details ?></p></p>
                                </div>
                            <?php
                        }
                    } elseif ($view_type == "theater") {
                        echo '<script type="text/javascript">ShowContent("view-box");</script>';

                        $theater_info_sql = "SELECT name, address, notice FROM theaters WHERE id = '$view_id'";
                        $theater_info_result = $conn->query($theater_info_sql);

                        while($theater_info_row = $theater_info_result->fetch_assoc()) {
                            $theater_name = $theater_info_row["name"];
                            $theater_address = $theater_info_row["address"];
                            $theater_notice = $theater_info_row["notice"];

                            ?>
                                <div class="left">
                                    <img src="assets/images/theater.jpg" alt="Image">
                                </div>
                                <div class="right">
                                    <p>Theater Name: <p class="mb-20 fs_20 fc_green"><?php echo $theater_name ?></p></p>
                                    <p>Theater Address: <p class="mb-5 fs_16 fc_green"><?php echo $theater_address ?></p></p>
                                    <p>Theater ID: <p class="mb-20 fs_16 fc_green"><?php echo $view_id ?></p></p>
                                    <p>Offer Now: <p class="mb-5 fs_16 fc_green"><?php echo $theater_notice ?></p></p>
                                </div>
                            <?php
                        }
                    }
                ?>
            </div>
            <!-- details view section end -->
        </div>

        <!-- right sidebar section -->
        <div class="right_sidebar sticky">
            <!-- best movie box -->
            <div class="item_box image_card">
                <p class="text_center title mb-5">Best Movie</p><hr>
                <?php
                    $best_movie_sql = "SELECT id, title, image FROM movies ORDER BY review DESC";
                    $best_movie_result = $conn->query($best_movie_sql);
                    $counter = 0;

                    while($best_movie_row = $best_movie_result->fetch_assoc()) {
                        if ($counter == 0) {
                            $best_movie_id = $best_movie_row["id"];
                            $best_movie_title = $best_movie_row["title"];
                            $best_movie_image = $best_movie_row["image"];

                            ?>
                                <!-- best movie image -->
                                <img class="mt-5" src="<?php 
                                if ($best_movie_image != null ) { 
                                    echo 'assets/images/movies/' .$best_movie_image;
                                } ?>" alt="Image" onclick="javascript:SendToView(<?php echo $best_movie_id ?>, 'movie')">

                                <!-- best movie name -->
                                <p class="text_center mt-5 fs_16"><?php echo $best_movie_title ?></p> 
                            <?php
                            $counter = $counter + 1;
                        }
                    }
                ?>
            </div>

            <!-- top movies box -->
            <div class="item_box">
                <p class="text_center fs_16 mb-5">Top 10 Movies</p><hr>
                <table class="rank_table mt-5">
                    <thead>
                        <tr>
                            <td>Rank</td>
                            <td>Name</td>
                            <td>Points</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $best_movie_sql = "SELECT id, title, review FROM movies ORDER BY review DESC";
                            $best_movie_result = $conn->query($best_movie_sql);
                            $counter = 0;

                            while($best_movie_row = $best_movie_result->fetch_assoc()) {
                                if ($counter <= 9) {
                                    $counter = $counter + 1;
                                    $best_movie_id = $best_movie_row["id"];
                                    $best_movie_title = $best_movie_row["title"];
                                    $best_movie_review = $best_movie_row["review"];

                                    ?>
                                        <tr>
                                            <td><?php echo $counter ?></td>
                                            <td><?php echo $best_movie_title ?></td>
                                            <td><?php echo $best_movie_review ?></td>
                                        </tr>
                                    <?php
                                }
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- rating modal start -->
    <div class="modal_bg">
        <div class="modal">
            <p class="fs_16">Rate this movie</p>
            <p class="fc_green fs_20">
                <span onclick="RateMovie(<?php echo $view_id ?>, '1')"><i class="far fa-star"></i></span>
                <span onclick="RateMovie(<?php echo $view_id ?>, '2')"><i class="far fa-star"></i></span>
                <span onclick="RateMovie(<?php echo $view_id ?>, '3')"><i class="far fa-star"></i></span>
                <span onclick="RateMovie(<?php echo $view_id ?>, '4')"><i class="far fa-star"></i></span>
                <span onclick="RateMovie(<?php echo $view_id ?>, '5')"><i class="far fa-star"></i></span>
            </p>
            <button class="modal_cancel mt-20">Cancel</button>
        </div>
    </div>
    <!-- rating modal end -->

    <script src="assets/js/functions.js"></script>
</body>
</html>