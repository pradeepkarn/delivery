<?php 



       //    if (isset($id) && $id>0) {
    //     $db = new Model('pk_user');
    //     $checkemail = $db->filter_index(['email'=>$email]);
    //     if (count($checkemail)>0) {
    //         $home = home;
    //         $token = bin2hex(random_bytes(32));
    //         $to      = $email;
    //         $subject = 'Password reset';
    //         $message = "Go to reset password: <a href='/{$home}/account/reset-account/?token={$token}&email={$email}'>Reset</a>";
    //         $headers  = 'MIME-Version: 1.0' . "\r\n";
    //         $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    //         $headers .= 'From: '.email. "\r\n"; 
    //         $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
    //         if(mail($to, $subject, $message, $headers)) {
    //             $arr= null;
    //             $arr['remember_token'] = $token;
    //             $db->update($id,$arr);
    //             echo "A password reset link sent to your registered email: {$email}, please check in spam folder in case of not found in inbox.";
    //         } else {
    //             echo "Failure: Email was not sent!";
    //         }
    //     }
    //     else{
    //         echo "Wrong Email";
    //     }
    //    }
        // return;