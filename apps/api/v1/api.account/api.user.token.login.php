<?php 
if(isset($_POST['login_via_token'])){
    $token = sanitize_remove_tags($_POST['login_via_token']);
    $accnt = new Account();
    $user = $accnt->loginWithToken_viaApp($token);
    if ($user==false) {
        $data['msg'] = "Invalid credentials";
        $data['data'] = null;
        echo json_encode($data);
        return;
    }
    else {
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