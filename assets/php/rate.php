<?php ob_start(); include_once('database.php');
    if(!isset($_SESSION['email'])){
        header("location: register.php");
    } else {
        $user = $_SESSION['id'];
        $movie_id = $_POST['movie_id'];
        $movie_rate = $_POST['movie_rate'];

        if ($movie_id == null || $movie_rate == null) {
            header("location: ./../../");
        } else {
            $movie_sql = "SELECT review, reviewer FROM movies WHERE id = '$movie_id'";
            $movie_result = $conn->query($movie_sql);

            while($movie_row = $movie_result->fetch_assoc()) {
                $movie_review = $movie_row["review"] + $movie_rate;
                $movie_reviewer = $movie_row["reviewer"] + 1;
                
                $update_sql = "UPDATE movies SET review = '$movie_review', reviewer = '$movie_reviewer' WHERE id = '$movie_id'";
                if (!mysqli_query($conn, $update_sql)) {
                    header("location: ./../../");
                } else {
                    header("location: ./../../");
                }
            }
        }
    }
?>