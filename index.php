<?php ob_start(); include_once('assets/php/database.php');
    if(!isset($_SESSION['email'])){
        header("location: register.php");
    } else {
        if ($_SESSION['type'] == "admin") {
            header("location: admin.php");
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
    <title>Cinebazar</title>

    <!-- custom css and js -->
    <link rel="stylesheet" type="text/css" href="assets/css/basic.css">
    <link rel="stylesheet" type="text/css" href="assets/css/layout.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Bree+Serif">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">
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
                        <h3 class="fc_purple">Popular Bangla</h3>
                        <p>Result from Bangla movies</p>
                    </div>
                    <button class="bc_purple">See More</button>
                </div>
                <div class="content_body">
                    <!-- movie cards start -->
                    <?php
                        $movie_sql = "SELECT id, title, theater, date, review, reviewer, image FROM movies WHERE country = 'Bangladesh' ORDER BY id DESC";
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