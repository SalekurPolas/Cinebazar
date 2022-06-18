<?php
	session_start();
	$conn = mysqli_connect('localhost', 'root', '');
	$db = mysqli_select_db($conn, 'test');

	$check_users_table = mysqli_query($conn, 'select 1 from `users`');
	$check_movies_table = mysqli_query($conn, 'select 1 from `movies`');
	$check_theaters_table = mysqli_query($conn, 'select 1 from `theaters`');
	$check_complains_table = mysqli_query($conn, 'select 1 from `complains`');


	if ( $check_users_table == FALSE ) {
		$create_users_table = "CREATE TABLE users (
			id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			name VARCHAR(30) NOT NULL,
			email VARCHAR(50) NOT NULL,
			birth VARCHAR(20) NOT NULL,
			type VARCHAR(30) NOT NULL,
			phone VARCHAR(20) NOT NULL,
			password VARCHAR(30) NOT NULL,
			image VARCHAR(250),
			details VARCHAR(250)
		)";

		if (!mysqli_query($conn, $create_users_table)) {
			echo "Error found on User! Please try again later. <br>";
		}
	} if ( $check_movies_table == FALSE ) {
		$create_movies_table = "CREATE TABLE movies (
			id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			admin INT(10) NOT NULL,
			status VARCHAR(50) NOT NULL,
			date VARCHAR(20) NOT NULL,
			title VARCHAR(80) NOT NULL,
			theater VARCHAR(80) NOT NULL,
			genre VARCHAR(50) NOT NULL,
			country VARCHAR(50) NOT NULL,
			actor VARCHAR(80) NOT NULL,
			review INT(20),
			reviewer INT(20),
			image VARCHAR(250),
			details VARCHAR(250)
		)";

		if (!mysqli_query($conn, $create_movies_table)) {
			echo "Error found on Movie! Please try again later in movies. <br>";
		}
	} if ( $check_theaters_table == FALSE ) {
		$create_theaters_table = "CREATE TABLE theaters (
			id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			admin INT(10) NOT NULL,
			name VARCHAR(50) NOT NULL,
			address VARCHAR(30) NOT NULL, 
			notice VARCHAR(250)
		)";

		if (!mysqli_query($conn, $create_theaters_table)) {
			echo "Error found on Theater! Please try again later. <br>";
		}
	} if ( $check_complains_table == FALSE ) {
		$create_complains_table = "CREATE TABLE complains (
			id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
			type VARCHAR(30) NOT NULL,
			name VARCHAR(50) NOT NULL,
			content VARCHAR(30) NOT NULL,
			details VARCHAR(250)
		)";

		if (!mysqli_query($conn, $create_complains_table)) {
			echo "Error found on Complain! Please try again later. <br>";
		}
	}
?>