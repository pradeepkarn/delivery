<?php 
import("apps/api/v1/api.salespersons/fn.salesperson.php");
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST') {
    $token = isset($_POST['token'])?$_POST['token']:null;
    // Do something with the data
} elseif ($method === 'GET') {
    $res['msg'] = "Wrong method to send data";
    $res['data'] = null;
    echo json_encode($res);
    return;
}
if($token==null){
    $res['msg'] = "Empty token not allowed";
    $res['data'] = null;
    echo json_encode($res);
    return;
}

$user = new Model('pk_user');
$arr['app_login_token'] = $token;
$user = $user->filter_index($arr);
if (!count($user)>0) {
    $res['msg'] = "Invalid token";
    $res['data'] = null;
    echo json_encode($res);
    return;
}

$sp_list = get_salesperson_list();
if(count($sp_list)>0){
    $res['msg'] = "success";
    $res['data'] = $sp_list;
    echo json_encode($res);
    return;
}else{
    $res['msg'] = "No data found";
    $res['data'] = null;
    echo json_encode($res);
    return;
}