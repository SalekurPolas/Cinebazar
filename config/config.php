<?php ob_start();
	if(session_status()== PHP_SESSION_NONE) {
		session_start();
	} if(session_id()=='') {
		session_start();
	}

	$root = $_SERVER['DOCUMENT_ROOT'];
	$root = $root. '/Cinebazar';
	$config = parse_ini_file($root. '/config/config.ini');

	$conn = mysqli_connect(
		$config['DB_HOST'],
		$config['DB_USER'],
		$config['DB_PASSWORD']
	);
	
	if ($conn) {
		$select_db = mysqli_select_db($conn, $config['DB_NAME']);
		if ($select_db) {
			$create_c_users_table = "CREATE TABLE IF NOT EXISTS c_users (
				uid VARCHAR(50) PRIMARY KEY NOT NULL,
				user_type VARCHAR(50) NOT NULL,
				first_name VARCHAR(100) NOT NULL,
				last_name VARCHAR(100) NOT NULL,
				email VARCHAR(100) NOT NULL,
				username VARCHAR(100) NOT NULL,
				birth_date VARCHAR(100),
				gender VARCHAR(100),
				phone VARCHAR(100) NOT NULL,
				password VARCHAR(32) NOT NULL,
				register_date VARCHAR(100) NOT NULL,
				register_time VARCHAR(100) NOT NULL,
				account_status VARCHAR(100) NOT NULL,
				profile_image VARCHAR(255)
			)";

			$create_c_usernames_table = "CREATE TABLE IF NOT EXISTS c_usernames (
				id VARCHAR(50) PRIMARY KEY NOT NULL,
				word VARCHAR(100) NOT NULL,
				owner VARCHAR(100),
				cause VARCHAR(100),
				date VARCHAR(100) NOT NULL,
				time VARCHAR(100) NOT NULL
			)";

			$create_c_credentials_table = "CREATE TABLE IF NOT EXISTS c_credentials (
				id VARCHAR(100) PRIMARY KEY NOT NULL,
				type VARCHAR(100) NOT NULL,
				account VARCHAR(100) NOT NULL,
				email VARCHAR(100) NOT NULL,
				date VARCHAR(100) NOT NULL,
				time VARCHAR(100) NOT NULL,
				code VARCHAR(50) NOT NULL
			)";

			if (!mysqli_query($conn, $create_c_users_table) || !mysqli_query($conn, $create_c_usernames_table) || !mysqli_query($conn, $create_c_credentials_table)) {
				echo "Error founds on database table";
			}
		} else {
			die ('No database found');
		}
	} else {
		die ('Connection Failed: ' .mysqli_connect_error());
	}
?>