<?php
if(isset($_POST['username_or_email']) && isset($_POST['password'])){
    $username_or_email = sanitize_remove_tags($_POST['username_or_email']);
    $password = sanitize_remove_tags($_POST['password']);
    $accnt = new Account();
    $user = $accnt->login_viaApp($username_or_email,$password);
    if ($user==false) {
        $data['msg'] = "Invalid credentials";
        $data['data'] = null;
        echo json_encode($data);
        return;
    }
    if ($user['user_group']!="whmanager") {
        $data['msg'] = "Sorry, login not success, you are not a warehouse manager!";
        $data['data'] = null;
        echo json_encode($data);
        die();
    }
    else {
        // $msg['msg'] = "success";
        $data['msg'] = "success";
        $data['data'] = $user;
        echo json_encode($data);
        return;
    }
}
else{
    $data['msg'] = "Invalid Activity";
    $data['data'] = null;
    echo json_encode($data);
    die();
}



 