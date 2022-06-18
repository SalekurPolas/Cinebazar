<?php $root = $_SERVER['DOCUMENT_ROOT'];
	$root = $root. '/Cinebazar';
    require_once($root. '/config/config.php');
    require('mailer.php');

    // check username live availability with ajax
    if (isset($_POST['check_username'])) {
        echo CheckUsername($_POST['check_username']);
    }

    // check login perameters and call login function
    if (isset($_POST['loginEmail']) && isset($_POST['loginPass'])) {
        echo LoginUser($_POST['loginEmail'], $_POST['loginPass']);
    }

    // update user status by email
    if(isset($_POST['verify_email']) && isset($_POST['update_status'])) {
        echo UpdateUserStatusByEmail($_POST['verify_email'], $_POST['update_status']);
    }

    // create new account
    if(isset($_POST['verifyRegId']) && isset($_POST['verifyRegUserType']) && isset($_POST['verifyRegFirstName']) && isset($_POST['verifyRegLastName']) && isset($_POST['verifyRegEmail']) && isset($_POST['verifyRegUsername']) && isset($_POST['verifyRegPhone']) && isset($_POST['verifyRegPass']) && isset($_POST['verifyRegDate']) && isset($_POST['verifyRegTime'])) {
        echo CreateNewAccount($_POST['verifyRegId'], $_POST['verifyRegUserType'], $_POST['verifyRegFirstName'], $_POST['verifyRegLastName'], $_POST['verifyRegEmail'], $_POST['verifyRegUsername'], $_POST['verifyRegPhone'], $_POST['verifyRegPass'], $_POST['verifyRegDate'], $_POST['verifyRegTime']);
    }

    // check account with email to register new
    if(isset($_POST['regEmail']) && isset($_POST['regDate']) && isset($_POST['regTime'])) {
        echo CheckAccountWithEmail("register_account", $_POST['regEmail'], $_POST['regDate'], $_POST['regTime']);
    }

    // check account with email to recover password
    if(isset($_POST['recEmail']) && isset($_POST['recDate']) && isset($_POST['recTime'])) {
        echo CheckAccountWithEmail("recover_password", $_POST['recEmail'], $_POST['recDate'], $_POST['recTime']);
    }

    // update recover password
    if(isset($_POST['auth']) && isset($_POST['verifyRecPass']) && isset($_POST['verifyRecRePass'])) {
        echo UpdateRecoverPassword($_POST['auth'], $_POST['verifyRecPass'], $_POST['verifyRecRePass']);
    }

    // verify credential with code
    if(isset($_POST['auth']) && isset($_POST['verifyCode'])) {
        echo VerifyCredentialWithCode($_POST['auth'], $_POST['verifyCode']);
    }

    // function to login old user with email and password
    function LoginUser($email, $password) {
        global $conn;
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $sqlForEmail = "SELECT uid, first_name, last_name, email, username, birth_date, gender, phone, password, account_status, profile_image 
            FROM c_users WHERE email = '$email' OR phone = '$email'";
            $resultForEmail = mysqli_query($conn, $sqlForEmail);

            if ($resultForEmail->num_rows > 0) {
                while($row = $resultForEmail->fetch_assoc()) {
                    if(md5($password) == $row["password"]) {
                        if($row["account_status"] == "active") {
                            $_SESSION['uid'] = $row["uid"];
                            $_SESSION['user_type'] = $row["user_type"];
                            $_SESSION['first_name'] = $row["first_name"];
                            $_SESSION['last_name'] = $row["last_name"];
                            $_SESSION['email'] = $row["email"];
                            $_SESSION['username'] = $row["username"];
                            $_SESSION['birth_date'] = $row["birth_date"];
                            $_SESSION['gender'] = $row["gender"];
                            $_SESSION['phone'] = $row["phone"];
                            $_SESSION['account_status'] = $row["account_status"];
                            $_SESSION['profile_image'] = $row["profile_image"];
                            
                            return json_encode(
                                array(
                                    "status" => true,
                                    "message" => "login_successful",
                                    "uid" => $row["uid"],
                                    "email" => $row["email"],
                                    "user_type" => $row["user_type"]
                                )
                            );
                        } else {
                            return json_encode(
                                array(
                                    "status" => false, 
                                    "message" => "account_is_temporarily_closed"
                                )
                            );
                        }
                    } else {
                        return json_encode(
                            array(
                                "status" => false, 
                                "message" => "incorrect_password"
                            )
                        );
                    }
                }
            } else {
                return json_encode(
                    array(
                        "status" => false, 
                        "message" => "account_not_found"
                    )
                );
            }
        } else {
            return json_encode(
                array(
                    "status" => false, 
                    "message" => "invalid_email_address"
                )
            );
        }
    }

    // function to register new user
    function CreateNewAccount($uid, $user_type, $first_name, $last_name, $email, $username, $phone, $password, $register_date, $register_time) {
        global $conn;
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $searchByEmail = "SELECT * FROM c_users WHERE email = '$email'";
            $accountByEmail = mysqli_query($conn, $searchByEmail);
            if(mysqli_num_rows($accountByEmail) > 0) {
                return json_encode(
                    array(
                        "status" => false,
                        "message" => "email_already_exists"
                    )
                );
            } else {
                $searchByPhone = "SELECT * FROM c_users WHERE phone = '$phone'";
                $accountByPhone = mysqli_query($conn, $searchByPhone);
                if(mysqli_num_rows($accountByPhone) > 0) {
                    return json_encode(
                        array(
                            "status" => false,
                            "message" => "phone_already_exist"
                        )
                    );
                } else {
                    $type = "register_account";
                    $delsql = "DELETE FROM c_credentials WHERE account = '$uid' AND type = '$type'";
                    
                    if(mysqli_query($conn, $delsql)) {
                        $encrypted_password = md5($password);
                        $sql = "INSERT INTO c_users (uid, user_type, first_name, last_name, email, username, phone, password, register_date, register_time, account_status)
                        VALUES('$uid', '$user_type', '$first_name', '$last_name', '$email', '$username', '$phone', '$encrypted_password', '$register_date', '$register_time', 'active')";

                        if(mysqli_query($conn, $sql)) {
                            return json_encode(
                                array(
                                    "status" => true, 
                                    "message" => "account_created_successfully"
                                )
                            );
                        } else {
                            return json_encode(
                                array(
                                    "status" => false,
                                    "message" => "registration_failed",
                                    "error" => mysqli_error($conn)
                                )
                            );
                        }
                    } else {
                        return json_encode(
                            array(
                                "status" => false,
                                "message" => "registration_failed",
                                "error" => mysqli_error($conn)
                            )
                        );
                    }
                }
            }
        } else {
            return json_encode(
                array(
                    "status" => false,
                    "message" => "invalid_email_address",
                    "error" => mysqli_error($conn)
                )
            );
        }
    }

    // function to check account existance
    function CheckAccountWithEmail($type, $email, $date, $time) {
        global $conn;
        $searchByEmail = "SELECT * FROM c_users WHERE email = '$email'";
        $accountByEmail = mysqli_query($conn, $searchByEmail);

        if($type == "register_account") {
            if(mysqli_num_rows($accountByEmail) > 0) {
                return json_encode(
                    array(
                        "status" => false, 
                        "message" => "email_already_exists"
                    )
                );
            } else {
                return CheckCredentialsForAuth($type, $email, $date, $time);
            }
        } else if($type == "recover_password") {
            if(mysqli_num_rows($accountByEmail) > 0) {
                return CheckCredentialsForAuth($type, $email, $date, $time);
            } else {
                return json_encode(
                    array(
                        "status" => false,
                        "message" => "account_not_found"
                    )
                );
            }
        }
    }

    // check credential for auth
    function CheckCredentialsForAuth($type, $email, $date, $time) {
        global $conn;
        $searchCredentials = "SELECT id, code FROM c_credentials WHERE type='$type' AND email = '$email'";
        $credentialResults = mysqli_query($conn, $searchCredentials);

        if ($credentialResults->num_rows > 0) {
            while($row = $credentialResults->fetch_assoc()) {
                $id = $row['id'];
                $code = $row['code'];
                return VerifyAndSendEmail($type, $email, $id, $code);
            }
        } else {
            if($type == "register_account") {
                return CreateCredentialToRegister($type, $email, $date, $time);
            } else {
                return CreateCredentialToRecover($type, $email, $date, $time);
            }
        }
    }
    // create credential for registration
    function CreateCredentialToRegister($type, $email, $date, $time) {
        global $conn;
        $id = bin2hex(random_bytes(32));
        $account = uniqid();
        $code = rand(10000, 99999);
        $sql = "INSERT INTO c_credentials (id, type, account, email, date, time, code) 
        VALUES('$id', '$type', '$account', '$email', '$date', '$time', '$code')";

        if(mysqli_query($conn, $sql)) {
            return VerifyAndSendEmail($type, $email, $id, $code);

        } else {
            return json_encode(
                array(
                    "status" => false, 
                    "message" => "create_credential_failed",
                    "error" => mysqli_error($conn)
                )
            );
        }
    }

    // create credential for password recovery
    function CreateCredentialToRecover($type, $email, $date, $time) {
        global $conn;
        $accountSql = "SELECT uid FROM c_users WHERE email = '$email'";
        $accountResult = mysqli_query($conn, $accountSql);

        if ($accountResult->num_rows > 0) {
            while($row = $accountResult->fetch_assoc()) {
                $account = $row['uid'];

                $id = bin2hex(random_bytes(32));
                $code = rand(10000, 99999);
                $sql = "INSERT INTO c_credentials (id, type, account, email, date, time, code) 
                VALUES('$id', '$type', '$account', '$email', '$date', '$time', '$code')";

                if(mysqli_query($conn, $sql)) {
                    return VerifyAndSendEmail($type, $email, $id, $code);

                } else {
                    return json_encode(
                        array(
                            "status" => false, 
                            "message" => "create_credential_failed",
                            "error" => mysqli_error($conn)
                        )
                    );
                }
            }
        } else {
            return json_encode(
                array(
                    "status" => false,
                    "message" => "account_not_found"
                )
            );
        }
    }

    // function to send email verification
    function VerifyAndSendEmail($type, $email, $id, $code) {
        $subject = ""; $description = ""; $link = "";
        $response = json_decode(GetUserFullNameByEmail($email));
        $name = $response->name;

        if($type == "register_account") {
            $subject = "Registration for Cinebazar";
            $description = "Congratulations! You have successfully requested to create an account for " .$email. " on Cinebazar.";
            $link = "http://localhost/Cinebazar/verify?regAuth=" .$id;
            $button_text = "Complete Registration";
        } else if($type == "recover_password") {
            $subject = "Recover Password for Cinebazar";
            $description = "You have sent request to recover password for " .$email. " on Cinebazar.";
            $link = "http://localhost/Cinebazar/verify?recAuth=" .$id;
            $button_text = "Change Password";
        }

        return SendAuthEmail($name, $email, $subject, $description, $link, $code, $button_text);
    }

    // function to username is available or not
    function CheckUsername($username) {
        global $conn;
        $sql = "SELECT username FROM c_users WHERE username = '$username'";
        if ($result = mysqli_query($conn, $sql)) {
            $rowcount = mysqli_num_rows($result);

            if($rowcount > 0) {
                return json_encode(
                    array(
                        "status" => false, 
                        "message" => "username_already_exists"
                    )
                );
            } else if(strlen($username) < 5 || strlen($username) > 15) {
                return json_encode(
                    array(
                        "status" => false, 
                        "message" => "invalid_username_length"
                    )
                );
            } else if(!preg_match("/^[a-zA-Z1-9]+$/", $username)) {
                return json_encode(
                    array(
                        "status" => false, 
                        "message" => "invalid_username_characters"
                    )
                );
            } else {
                $special_usernames = "SELECT id, word, owner, cause FROM c_usernames WHERE word = '$username'";
                $special_usernames_result = $conn->query($special_usernames);
                
                if ($special_usernames_result->num_rows > 0) {
                    while($row = $special_usernames_result->fetch_assoc()) {
                        return json_encode(
                            array(
                                "status" => false,
                                "message" => "username_is_reserved",
                                "id" => $row['id'],
                                "owner" => $row['owner'],
                                "cause" => $row['cause']
                            )
                        );
                    }
                } else {
                    return json_encode(
                        array(
                            "status" => true,
                            "message" => "username_available"
                        )
                    );
                }
            }
        }
    }

    // function to get user name by email
    function GetUserFullNameByEmail($email) {
        global $conn;
        $sql = "SELECT first_name, last_name FROM c_users WHERE email = '$email'";
        $result = mysqli_query($conn, $sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                return json_encode(
                    array(
                        "status" => true,
                        "name" => $row['first_name']. " " .$row['last_name']
                    )
                );
            }
        } else {
            return json_encode(
                array(
                    "status" => true,
                    "name" => "Cinebazar User"
                )
            );
        }
    }

    // verify user by password
    function VerifyUserByPassword($password) {
        global $conn;
        if(isset($_SESSION['email'])) {
            $email = $_SESSION['email'];
            $sql = "SELECT password FROM c_users WHERE email = '$email'";
            $result = mysqli_query($conn, $sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    if(md5($password) == $row["password"]) {
                        return json_encode(
                            array(
                                "status" => true, 
                                "message" => "verification_successfull"
                            )
                        );
                    } else {
                        return json_encode(
                            array(
                                "status" => false, 
                                "message" => "incorrect_password"
                            )
                        );
                    }
                }
            } else {
                return json_encode(
                    array(
                        "status" => false, 
                        "message" => "account_not_found"
                    )
                );
            }
        } else {
            return json_encode(
                array(
                    "status" => false,
                    "message" => "logged_in_user_not_found"
                )
            );
        }
    }

    // function to update user status to database
    function UpdateUserStatusByEmail($email, $status) {
        global $conn;
        $sql = "UPDATE c_users SET account_status='$status' WHERE email='$email'";
        if(mysqli_query($conn, $sql)) {
            return json_encode(
                array(
                    "status" => true,
                    "message" => "status_successfully_updated"
                )
            );
        } else {
            return json_encode(
                array(
                    "status" => false,
                    "message" => "status_update_failed",
                    "error" => mysqli_error($conn)
                )
            );
        }
    }

    // get credential by email and type
    function GetCredentialByEmailAndType($email, $type) {
        global $conn;
        $sql = "SELECT id, type, account, email, date, time, code FROM c_credentials WHERE email = '$email' AND type='$type'";
        $result = mysqli_query($conn, $sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                return json_encode(
                    array(
                        "status" => true,
                        "message" => "credential_found",
                        "id" => $row['id'],
                        "type" => $row['type'],
                        "account" => $row['account'],
                        "email" => $row['email'],
                        "date" => $row['date'],
                        "time" => $row['time'],
                        "code" => $row['code']
                    )
                );
            }
        } else {
            return json_encode(
                array(
                    "status" => false,
                    "message" => "credential_not_found"
                )
            );
        }
    }

    // get credentials by id
    function GetCredentialById($id) {
        global $conn;
        $sql = "SELECT id, type, account, email, date, time, code FROM c_credentials WHERE id = '$id'";
        $result = mysqli_query($conn, $sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                return json_encode(
                    array(
                        "status" => true,
                        "message" => "credential_found",
                        "id" => $row['id'],
                        "type" => $row['type'],
                        "account" => $row['account'],
                        "email" => $row['email'],
                        "date" => $row['date'],
                        "time" => $row['time'],
                        "code" => $row['code']
                    )
                );
            }
        } else {
            return json_encode(
                array(
                    "status" => false,
                    "message" => "credential_not_found"
                )
            );
        }
    }

    // verify credentials with code
    function VerifyCredentialWithCode($id, $code) {
        global $conn;
        $sql = "SELECT id, type, account, email, date, time, code FROM c_credentials WHERE id = '$id'";
        $result = mysqli_query($conn, $sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                if($code == $row['code']) {
                    return json_encode(
                        array(
                            "status" => true,
                            "message" => "credential_found",
                            "id" => $row['id'],
                            "type" => $row['type'],
                            "account" => $row['account'],
                            "email" => $row['email'],
                            "date" => $row['date'],
                            "time" => $row['time'],
                            "code" => $row['code']
                        )
                    );
                } else {
                    return json_encode(
                        array(
                            "status" => false,
                            "message" => "incorrect_verification_code"
                        )
                    );
                }
            }
        } else {
            return json_encode(
                array(
                    "status" => false,
                    "message" => "credential_not_found"
                )
            );
        }
    }

    // function to update recover password
    function UpdateRecoverPassword($id, $password, $rePassword) {
        global $conn;
        if($password == $rePassword) {
            $readSql = "SELECT account FROM c_credentials WHERE id = '$id'";
            $result = mysqli_query($conn, $readSql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $account_id = $row['account'];
                    $encrypted_password = md5($password);
                    $updateSql = "UPDATE c_users SET password = '$encrypted_password' WHERE uid = '$account_id'";

                    if(mysqli_query($conn, $updateSql)) {
                        $delsql = "DELETE FROM c_credentials WHERE id = '$id'";

                        if(mysqli_query($conn, $delsql)) {
                            return json_encode(
                                array(
                                    "status" => true,
                                    "message" => "password_updated_successfully"
                                )
                            );
                        } else {
                            return json_encode(
                                array(
                                    "status" => true,
                                    "message" => "delete_credential_failed"
                                )
                            );
                        }
                    } else {
                        return json_encode(
                            array(
                                "status" => false,
                                "message" => "update_password_failed",
                                "error" => mysqli_error($conn)
                            )
                        );
                    }
                }
            } else {
                return json_encode(
                    array(
                        "status" => false,
                        "message" => "credential_not_found"
                    )
                );
            }
        } else {
            return json_encode(
                array(
                    "status" => false,
                    "message" => "password_did_not_match"
                )
            );
        }
    }

?>