<?php ob_start(); include_once('assets/php/database.php');
    if(!isset($_SESSION['email'])){
        header("location: register.php");
    } else {
        if ($_SESSION['type'] == "user") {
            header("location: ./");
        } else {
            $user = $_SESSION['id'];
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cinebazar - Admin</title>

    <!-- custom css and js -->
    <link rel="stylesheet" type="text/css" href="assets/css/basic.css">
    <link rel="stylesheet" type="text/css" href="assets/css/layout.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Bree+Serif">
    <link rel="stylesheet" href="assets/fonts/fontawesome/css/all.css">
    <script src="assets/js/functions.js"></script>
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
            <!-- create movie section -->
            <div class="item_box create_movie" id="create-movie-box">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="left">
                        <img src="assets/images/upload.jpg" alt="image" id="create-movie-image-preview">
                        <input id="create-movie-image-src" class="mt-5" type="file" name="create_movie_image" onchange="PreviewImage('create-movie-image-preview', 'create-movie-image-src')">
                    </div>
                    <div class="right">
                        <!-- display theater name start -->
                        <?php
                            $movie_theater_sql = "SELECT name FROM theaters WHERE admin = '$user'";
                            $movie_theater_result = $conn->query($movie_theater_sql);

                            if ($movie_theater_result->num_rows > 0) {
                                ?>
                                    <select name="create_movie_theater" required>
                                        <?php
                                            while($movie_theater_row = $movie_theater_result->fetch_assoc()) {
                                                $theater_name = $movie_theater_row["name"];
                                                ?>
                                                    <option><?php echo $theater_name; ?></option>
                                                <?php
                                            }
                                        ?>
                                    </select>
                                <?php
                            } else {
                                echo '<script type="text/javascript">HideContent("create-movie-box");</script>';
                            }
                        ?>
                        <!-- display theater name end -->

                        <select class="mt-5" name="create_movie_status" id="">
                            <option value="Release">Release</option>
                            <option value="Coming">Coming</option>
                        </select>
                        <select class="mt-5" name="create_movie_genre" id="">
                            <option value="Action">Action</option>
                            <option value="Romance">Romance</option>
                            <option value="Thriller">Thriller</option>
                            <option value="Comedy">Comedy</option>
                            <option value="Adventure">Adventure</option>
                            <option value="Horror">Horror</option>
                        </select>
                        <select class="mt-5" name="create_movie_country" id="">
                            <option value="Bangladesh">Bangladesh</option>
                            <option value="India">India</option>
                            <option value="United State">United State</option>
                            <option value="United Kingdom">United Kingdom</option>
                            <option value="China">China</option>
                            <option value="Japan">Japan</option>
                        </select>
                        <input class="mt-5" type="text" name="create_movie_title" placeholder="Enter movie name">
                        <input class="mt-5" type="text" name="create_movie_date" placeholder="Enter movie year">
                        <input class="mt-5" type="text" name="create_movie_actor" placeholder="Enter actors">
                        <textarea class="mt-5" name="create_movie_details" placeholder="Enter movie details"></textarea>
                        <button class="mt-5" type="submit" name="create_movie_ok">Publish Now</button>
                    </div>
                </form>

                <?php
                    if (isset($_POST['create_movie_ok'])) {
                        $upload_movie_image = $_FILES['create_movie_image']['name'];
                        $movie_theater = $_POST['create_movie_theater'];
                        $movie_status = $_POST['create_movie_status'];
                        $movie_genre = $_POST['create_movie_genre'];
                        $movie_country = $_POST['create_movie_country'];
                        $movie_title = $_POST['create_movie_title'];
                        $movie_date = $_POST['create_movie_date'];
                        $movie_actor = $_POST['create_movie_actor'];
                        $movie_details = $_POST['create_movie_details'];
                        
                        if (($upload_movie_image != null) && ($movie_title != null) && ($movie_date != null) && ($movie_actor != null) && ($movie_details != null) ) {
                            $image_extension = pathinfo($upload_movie_image, PATHINFO_EXTENSION);
                            $movie_image = 'cinebazar-movie-' .$user. '.' .$image_extension;

                            //creating movies folder
                            if(!is_dir("assets/images/movies/")) {
                                mkdir("assets/images/movies/");
                            }

                            $create_movie_sql = "INSERT INTO movies (admin, status, date, title, theater, genre, country, actor, review, reviewer, image, details) 
                            VALUES('$user', '$movie_status', '$movie_date', '$movie_title', '$movie_theater', '$movie_genre', '$movie_country', '$movie_actor', '', '', '', '$movie_details')";

                            if (!mysqli_query($conn, $create_movie_sql)) {
                                echo "<p style='text-align:center; color:black; padding: 5px; margin-top: 10px; border-radius: 4px; background-color: red;'>" .mysqli_error($conn). "</p>";
                            } else {
                                $get_movie_id_sql = "SELECT id FROM movies ORDER BY id DESC";
                                $get_movie_id_result = $conn->query($get_movie_id_sql);
                                $counter = 0;

                                while($get_movie_id_row = $get_movie_id_result->fetch_assoc()) {
                                    if ($counter == 0) {
                                        $counter = $counter + 1;
                                        $movie_id = $get_movie_id_row["id"];
                                        $movie_image = 'cinebazar-movie-' .$movie_id. '.' .$image_extension;

                                        $movie_image_sql = "UPDATE movies SET image = '$movie_image' WHERE id = '$movie_id'";

                                        if (!mysqli_query($conn, $movie_image_sql)) {
                                            echo "<p style='text-align:center; color:black; padding: 5px; margin-top: 10px; border-radius: 4px; background-color: red;'>Update Failed: </p>";
                                        } else {
                                            //copy src file in dir
                                            move_uploaded_file($_FILES['create_movie_image']['tmp_name'], "assets/images/movies/" .$movie_image);
                                            header("location: ./");
                                        }
                                    }
                                }
                            }
                        } else {
                            echo '<script type="text/javascript">ShowContent("edit-profile");</script>';
                            echo "<p style='text-align:center; color:black; padding: 5px; margin-top: 10px; border-radius: 4px; background-color: red;'>Please Insert Value</p>";
                        }
                    }
                ?>
            </div>

            <!-- movie first row -->
            <div class="search_content">
                <div class="content_header">
                    <div class="left_header">
                        <h3 class="fc_red">Populer Movies</h3>
                        <p>Result from all movies</p>
                    </div>
                    <button class="bc_red">See More</button>
                </div>
                <div class="content_body">
                    <!-- movie cards start -->
                    <?php
                        $movie_sql = "SELECT id, title, theater, date, review, reviewer, image FROM movies ORDER BY review DESC";
                        $movie_result = $conn->query($movie_sql);
                        $counter = 0;

                        while($movie_row = $movie_result->fetch_assoc()) {
                            if ($counter <= 7) {
                                $counter = $counter + 1;
                                $movie_id = $movie_row["id"];
                                $movie_title = $movie_row["title"];
                                $movie_theater = $movie_row["theater"];
                                $movie_date = $movie_row["date"];
                                $movie_review = $movie_row["review"];
                                $movie_reviewer = $movie_row["reviewer"];
                                $movie_image = $movie_row["image"];

                                ?>
                                    <div class="movie_card">
                                        <!-- best movie image -->
                                        <img src="<?php echo 'assets/images/movies/' .$movie_image; ?>" alt="Image" onclick="javascript:SendToView(<?php echo $movie_id ?>, 'movie')">

                                        <div class="details">
                                            <div class="upper">
                                                <p class="fc_green fs_16"><?php echo $movie_title ?></p>
                                                <p class="fs_12"><?php echo $movie_theater ?></p>
                                            </div>
                                            <div class="lower">
                                                <?php
                                                    if ($movie_reviewer == 0 || $movie_review == 0) {
                                                        ?>
                                                            <p class="fc_purple fs_9">
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
                                                                <p class="fc_purple fs_9">
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                </p>
                                                            <?php
                                                        } elseif ($stars >= 1 && $stars < 2) {
                                                            ?>
                                                                <p class="fc_purple fs_9">
                                                                    <i class="fas fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                </p>
                                                            <?php
                                                        } elseif ($stars >= 2 && $stars < 3) {
                                                            ?>
                                                                <p class="fc_purple fs_9">
                                                                    <i class="fas fa-star"></i>
                                                                    <i class="fas fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                </p>
                                                            <?php
                                                        } elseif ($stars >= 3 && $stars < 4) {
                                                            ?>
                                                                <p class="fc_purple fs_9">
                                                                    <i class="fas fa-star"></i>
                                                                    <i class="fas fa-star"></i>
                                                                    <i class="fas fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                </p>
                                                            <?php
                                                        } elseif ($stars >= 4 && $stars < 5) {
                                                            ?>
                                                                <p class="fc_purple fs_9">
                                                                    <i class="fas fa-star"></i>
                                                                    <i class="fas fa-star"></i>
                                                                    <i class="fas fa-star"></i>
                                                                    <i class="fas fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                </p>
                                                            <?php
                                                        } elseif ($stars >= 5) {
                                                            ?>
                                                                <p class="fc_purple fs_9">
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
                                                <p class="fc_red fs_14"><?php echo $movie_date ?></p>
                                            </div>
                                        </div>
                                    </div>  
                                <?php
                            }
                        }
                    ?>
                    <!-- movie cards end -->
                </div>
            </div>

            <!-- movie second row -->
            <div class="search_content">
                <div class="content_header">
                    <div class="left_header">
                        <h3 class="fc_green">Latest Movies</h3>
                        <p>Result from all movies</p>
                    </div>
                    <button class="bc_green">See More</button>
                </div>
                <div class="content_body">
                    <!-- movie cards start -->
                    <?php
                        $movie_sql = "SELECT id, title, theater, date, review, reviewer, image FROM movies ORDER BY id DESC";
                        $movie_result = $conn->query($movie_sql);
                        $counter = 0;

                        while($movie_row = $movie_result->fetch_assoc()) {
                            if ($counter <= 7) {
                                $counter = $counter + 1;
                                $movie_id = $movie_row["id"];
                                $movie_title = $movie_row["title"];
                                $movie_theater = $movie_row["theater"];
                                $movie_date = $movie_row["date"];
                                $movie_review = $movie_row["review"];
                                $movie_reviewer = $movie_row["reviewer"];
                                $movie_image = $movie_row["image"];

                                ?>
                                    <div class="movie_card">
                                        <!-- best movie image -->
                                        <img src="<?php echo 'assets/images/movies/' .$movie_image; ?>" alt="Image" onclick="javascript:SendToView(<?php echo $movie_id ?>, 'movie')">

                                        <div class="details">
                                            <div class="upper">
                                                <p class="fc_green fs_16"><?php echo $movie_title ?></p>
                                                <p class="fs_12"><?php echo $movie_theater ?></p>
                                            </div>
                                            <div class="lower">
                                                <?php
                                                    if ($movie_reviewer == 0 || $movie_review == 0) {
                                                        ?>
                                                            <p class="fc_purple fs_9">
                                                                <i class="far fa-star"></i>
                                                                <i class="far fa-star"></i>
                                                                <i class="far fa-star"></i>
                                                                <i class="far fa-star"></i>
                                                                <i class="far fa-star"></i>
                                                            </p>
                                                        <?php
                                                    } else {
                                                        $stars = $movie_review / $movie_reviewer;

                                                        if ($stars <= 1) {
                                                            ?>
                                                                <p class="fc_purple fs_9">
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                </p>
                                                            <?php
                                                        } elseif ($stars > 1 && $stars <= 2) {
                                                            ?>
                                                                <p class="fc_purple fs_9">
                                                                    <i class="fas fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                </p>
                                                            <?php
                                                        } elseif ($stars > 2 && $stars <= 3) {
                                                            ?>
                                                                <p class="fc_purple fs_9">
                                                                    <i class="fas fa-star"></i>
                                                                    <i class="fas fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                </p>
                                                            <?php
                                                        } elseif ($stars > 3 && $stars <= 4) {
                                                            ?>
                                                                <p class="fc_purple fs_9">
                                                                    <i class="fas fa-star"></i>
                                                                    <i class="fas fa-star"></i>
                                                                    <i class="fas fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                </p>
                                                            <?php
                                                        } elseif ($stars > 4 && $stars <= 5) {
                                                            ?>
                                                                <p class="fc_purple fs_9">
                                                                    <i class="fas fa-star"></i>
                                                                    <i class="fas fa-star"></i>
                                                                    <i class="fas fa-star"></i>
                                                                    <i class="fas fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                </p>
                                                            <?php
                                                        } elseif ($stars > 5) {
                                                            ?>
                                                                <p class="fc_purple fs_9">
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
                                                <p class="fc_red fs_14"><?php echo $movie_date ?></p>
                                            </div>
                                        </div>
                                    </div>  
                                <?php
                            }
                        }
                    ?>
                    <!-- movie cards end -->
                </div>
            </div>

            <!-- movie third row -->
            <div class="search_content">
                <div class="content_header">
                    <div class="left_header">
                        <h3 class="fc_purple">Latest from Theater</h3>
                        <p>Result from your theater</p>
                    </div>
                    <button class="bc_purple">See More</button>
                </div>
                <div class="content_body">
                    <!-- movie cards start -->
                    <?php
                        $movie_sql = "SELECT id, title, theater, date, review, reviewer, image FROM movies WHERE admin = '$user' ORDER BY id DESC";
                        $movie_result = $conn->query($movie_sql);
                        $counter = 0;

                        while($movie_row = $movie_result->fetch_assoc()) {
                            if ($counter <= 7) {
                                $counter = $counter + 1;
                                $movie_id = $movie_row["id"];
                                $movie_title = $movie_row["title"];
                                $movie_theater = $movie_row["theater"];
                                $movie_date = $movie_row["date"];
                                $movie_review = $movie_row["review"];
                                $movie_reviewer = $movie_row["reviewer"];
                                $movie_image = $movie_row["image"];

                                ?>
                                    <div class="movie_card">
                                        <!-- best movie image -->
                                        <img src="<?php echo 'assets/images/movies/' .$movie_image; ?>" alt="Image" onclick="javascript:SendToView(<?php echo $movie_id ?>, 'movie')">

                                        <div class="details">
                                            <div class="upper">
                                                <p class="fc_green fs_16"><?php echo $movie_title ?></p>
                                                <p class="fs_12"><?php echo $movie_theater ?></p>
                                            </div>
                                            <div class="lower">
                                                <?php
                                                    if ($movie_reviewer == 0 || $movie_review == 0) {
                                                        ?>
                                                            <p class="fc_purple fs_9">
                                                                <i class="far fa-star"></i>
                                                                <i class="far fa-star"></i>
                                                                <i class="far fa-star"></i>
                                                                <i class="far fa-star"></i>
                                                                <i class="far fa-star"></i>
                                                            </p>
                                                        <?php
                                                    } else {
                                                        $stars = $movie_review / $movie_reviewer;

                                                        if ($stars <= 1) {
                                                            ?>
                                                                <p class="fc_purple fs_9">
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                </p>
                                                            <?php
                                                        } elseif ($stars > 1 && $stars <= 2) {
                                                            ?>
                                                                <p class="fc_purple fs_9">
                                                                    <i class="fas fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                </p>
                                                            <?php
                                                        } elseif ($stars > 2 && $stars <= 3) {
                                                            ?>
                                                                <p class="fc_purple fs_9">
                                                                    <i class="fas fa-star"></i>
                                                                    <i class="fas fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                </p>
                                                            <?php
                                                        } elseif ($stars > 3 && $stars <= 4) {
                                                            ?>
                                                                <p class="fc_purple fs_9">
                                                                    <i class="fas fa-star"></i>
                                                                    <i class="fas fa-star"></i>
                                                                    <i class="fas fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                </p>
                                                            <?php
                                                        } elseif ($stars > 4 && $stars <= 5) {
                                                            ?>
                                                                <p class="fc_purple fs_9">
                                                                    <i class="fas fa-star"></i>
                                                                    <i class="fas fa-star"></i>
                                                                    <i class="fas fa-star"></i>
                                                                    <i class="fas fa-star"></i>
                                                                    <i class="far fa-star"></i>
                                                                </p>
                                                            <?php
                                                        } elseif ($stars > 5) {
                                                            ?>
                                                                <p class="fc_purple fs_9">
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
                                                <p class="fc_red fs_14"><?php echo $movie_date ?></p>
                                            </div>
                                        </div>
                                    </div>  
                                <?php
                            }
                        }
                    ?>
                    <!-- movie cards end -->
                </div>
            </div>
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
</body>
</html>