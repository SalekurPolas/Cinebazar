<?php
    function SendAuthEmail($name, $email, $subject, $description, $link, $code, $button_text) {
        $body = '
            <!DOCTYPE html>
            <html>
                <head>
                    <meta charset="UTF-8">
                    <meta content="width=device-width, initial-scale=1" name="viewport">
                    <meta name="x-apple-disable-message-reformatting">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <title>Cinebazar - Mail</title>
                    <style>
                        * {
                            margin: 0;
                            padding: 0;
                        }
                        .btn {
                            border: none;
                            color: #FFFFFF!important;
                            cursor: pointer;
                            overflow: hidden;
                            font-size: 20px;
                            margin: 10px auto;
                            padding: 10px 40px;
                            text-align: center;
                            border-radius: 7px;
                            text-decoration: none;
                            background-color: #E71E62;
                        }
                    </style>
                </head>
                <body>
                    <p>Hello '.$name.',</p>
                    <p>'.$description.'</p><br><br>
                    <a href="'.$link.'" class="btn">'.$button_text.'</a><br><br><br>
                    <p>If you want to continue, click on the button above or use this code to verify your email: <b>'.$code.'</b></p><br>
                    <p>If you think you need any help, then <a href="#"><b>contact</b></a> us.</p>
                </body>
            </html>
        ';
        
        $headers = "From: Cinebazar <salekur19@gmail.com>\r\n";
        $headers .= "Reply-To: Cinebazar <salekur19@gmail.com>\r\n";
        $headers .= "Return-Path: Cinebazar <salekur19@gmail.com>\r\n";
        $headers .= "Organization: Cinebazar\r\n";
        $headers .= "X-Priority: 3\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
        $headers .= 'MIME-Version: 1.0'."\r\n";
        $headers .= "X-Mailer: PHP". phpversion()."\r\n";

        if (mail($email, $subject, $body, $headers)) {
            return json_encode(
                array(
                    "status" => true, 
                    "message" => "email_sent_successfully"
                )
            );
        } else{
            return json_encode(
                array(
                    "status" => false, 
                    "message" => "sending_email_failed", 
                    "error" => error_get_last()['message']
                )
            );
        }
        
    }
?>