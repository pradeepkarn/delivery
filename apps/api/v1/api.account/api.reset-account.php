<?php
if(isset($_POST['username_or_email'])){
    $if_user_exists = false;
    $username_or_email = sanitize_remove_tags($_POST['username_or_email']);
    $is_email = filter_var($username_or_email,FILTER_VALIDATE_EMAIL);
    //check if input is email
   if ($is_email==true) {
    $var = "email";
    $arr = null;
    $arr['email'] = $username_or_email;
    $db = new Model('pk_user');
    $if_user_exists = $db->exists($arr);
    if ($if_user_exists==true) {
        $user = $db->filter_index($arr)[0];
    }
   }
   //check if input is username
   elseif ($is_email==false) {
    $var = "username";
    $arr = null;
    $arr['username'] = $username_or_email;
    $db = new Model('pk_user');
    $if_user_exists = $db->exists($arr);
    if ($if_user_exists==true) {
        $user = $db->filter_index($arr)[0];
    }
   }
   //check if user exists
   if ($if_user_exists!=false) {
    $useremail = $user['email'];
    $user_id = $user['id'];
    $password_reset_token = bin2hex(random_bytes(32))."_uid_".$user['id'];
    //send mail
        $domain = MY_DOMAIN;
        $md5_email = md5($useremail);
        $home = home;
        $token = bin2hex(random_bytes(32));
        $to      = $useremail;
        $subject = 'Password reset';
        $message = "Go to reset password: <a href='https://{$domain}/account/reset-account/?token={$token}{$md5_email}'>Reset</a>";
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: '.email. "\r\n"; 
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        if(mail($to, $subject, $message, $headers)) {
            $arr= null;
            $arr['password_reset_token'] = $token.$md5_email;
            $pwd = new Model('pk_user');
            $pwd->update($user['id'],$arr);
            $email_msg['email_sent_status'] = "A password reset link sent to your registered email: {$useremail}, please check in spam folder in case of not found in inbox.";
            $msg['msg'] = "success";
            $data['data'][] = $msg;
            $data['data'][] = $email_msg;
            echo json_encode($data);
            return;
        } else {
            $msg['msg'] = "Failed";
            $email_msg['email_sent_status'] = "Failure: Email was not sent!";
            $data['data'][] = $msg;
            $data['data'][] = $email_msg;
            echo json_encode($data);
            return;
        }

   }
   else{
    $msg['msg'] = "Invalid $var";
    $data['data'][] = $msg;
    echo json_encode($data);
    die();
   }
}
else{
    die();
}



 