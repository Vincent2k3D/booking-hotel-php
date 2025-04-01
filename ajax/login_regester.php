<?php
require('../admin/database/db_config.php');
require('../admin/shares/essentials.php');
require('../shares/sendgrid-php-main/sendgrid-php.php'); // Include the SendGrid library


function send_mail($uemail, $token, $type) // Function to send email using SendGrid
{
    if ($type == "email_confirmation") {
        $page = 'email_confirm.php?';
        $subject = "Account Verification";
        $content = "Click to confirm you email";
    } else {
        $page = 'index.php?';
        $subject = "Account Reset Link";
        $content = "Reset  your account";
    }
    $email = new \SendGrid\Mail\Mail(); // Create a new SendGrid Mail object
    $email->setFrom(SENDGRID_NAME, SENDGRID_EMAIL); // Set the sender email address and name
    $email->setSubject($subject); // Set the email subject

    $email->addTo($email); // Add the recipient email address

    $email->addContent(
        "text/html",
        "Click the link to $content:<br>
        <a href='" . SITE_URL . "$page?$type  email_confirmtion & email=$uemail&token=$token" . "'>
        CLICK ME
        </a> 
        "
    );

    $sendgrid = new \SendGrid(getenv(SENDGRID_API_KEY)); // Create a new SendGrid object with your API key
    try {
        if ($sendgrid->send($email)) { // Send the email
            return 1;
        } else {
            return 0;
        }
    } catch (Exception $e) {
        return 0;
    }
}

if (isset($_POST['register'])) {
    $data = filteration($_POST);
    // match password and confirm password

    if ($data['pass'] != $data['cpass']) {
        echo 'pass_mismatch';
        exit;
    }

    //check user exits or not

    $u_exists = select("SELECT * FROM `users` WHERE `email` = ? OR `phonenum` = ? LIMIT", [$data['email'], $data['phonenum']], "ii");

    if (mysqli_num_rows($u_exists) != 0) {
        $u_exists_fetch = mysqli_fetch_assoc($u_exists);
        echo ($u_exists_fetch['email'] == $data['email']) ? 'email_exists' : 'phonenum_already';
        exit;
    }

    //send confirmation code to user email

    $token = bin2hex(random_bytes(16)); // Generate a random token

    if (!send_mail($data['email'], $token, "account_recovery")) {
        echo 'mail_failed';
    } else {
        $date = date("Y-m-d");

        $query = mysqli_query($con, "UPDATE `user_cred` SET `token` ='$token',`t_expire`='$date' WHERE `id`='$u_fetch[id]'");
    }

    $enc_pass = password_hash($data['pass'], PASSWORD_BCRYPT); // Hash the password

    $query = "INSERT INTO `user_cred`(`name`, `email`, `address`, `phonenum`, `dob`, `password`,`token`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $values = [$data['name'], $data['email'], $data['address'], $data['phonenum'], $data['dob'], $enc_pass, $token]; // Prepare the values for the query
    if (insert($query, $values, "ssssssss")) {
        echo 1;
    } else {
        echo 'ins_failed'; // If the insert fails, return an error message
    } // Insert the user data into the database
}

if (isset($_POST['login'])) {
    $data = filteration($_POST);

    $u_exists = select("SELECT * FROM `users` WHERE `email` = ? OR `phonenum` = ? LIMIT", [$data['email_mob'], $data['email_mob']], "ii");

    if (mysqli_num_rows($u_exists) == 0) {
        echo 'inv_email_mob';
    } else {
        $u_fetch = mysqli_fetch_assoc($u_exists);
        if ($u_fetch['is_verified'] == 0) {
            echo 'not_verified';
        } else if ($u_fetch['status'] == 0) {
            echo 'inactive';
        } else {
            if (!password_verify($data['pass'], $u_fetch['password'])) {
                echo 'invalid_pass';
            } else {
                session_start();
                $_SESSION['login'] = true;
                $_SESSION['uId'] = $u_fetch['id'];
                $_SESSION['uName'] = $u_fetch['name'];
                $_SESSION['uPic'] = $u_fetch['picture'];
                $_SESSION['uphone'] = $u_fetch['phonenum'];
                echo 1;
            }
        }
    }
}

if (isset($_POST['forgot_pass'])) {
    $data = filteration($_POST);
    $u_exists = select("SELECT * FROM `user_cred` WHERE `email` = ? LIMIT 1", [$data['email']], "s");
    if (mysqli_num_rows($u_exists) == 0) {
        echo 'inv_email';
    } else {
        $u_fetch = mysqli_fetch_assoc($u_exists);
        if ($u_fetch['is_verified'] == 0) {
            echo 'not_verified';
        } else if ($u_fetch['status'] == 0) {
            echo 'inactive';
        } else {
            //send reset link to email
            $token = bin2hex(random_bytes(16)); // Generate a random token
            if (!send_mail($data['email'], $token, 'account_recovery')) {
                echo 'mail_failed';
            } else {

                $date = date("Y-m-d");

                $query = mysqli_query($con, "UPDATE `user_cred` SET `token`='$token', `t_expire`='$date' WHERE `id`='$u_fetch[id]'");

                if ($query) {
                    echo 1;
                } else {
                    echo 'upd_failed';
                }
            }
        }
    }
}

if (isset($_POST['recovery_user'])) {
    $data = filteration($_POST);

    $enc_pass = password_hash($data['new_pass'], PASSWORD_BCRYPT); // Hash the new password

    $query = "UPDATE `user_cred` SET `password`=?, `token` =?, `t_expire`=? WHERE `email`=? AND `token`=?";

    $values = [$enc_pass, null, null, $data['email'], $data['token']];

    if (update($query, $values, 'sssss')) {
        echo 1;
    } else {
        echo 'failed';
    }
}
