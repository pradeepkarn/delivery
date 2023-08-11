<?php
import("apps/api/v1/api.cart/fn.cart.php");

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
    $res['msg'] = "No user found";
    $res['data'] = null;
    echo json_encode($res);
    return;
}
$cart = get_my_cart_api($token);
if(count($cart)>0){
    $res['msg'] = "success";
    $res['data'] = isset($cart['carts'])?$cart['carts']:null;
    echo json_encode($res);
    return;
}else{
    $res['msg'] = "No data found";
    $res['data'] = null;
    echo json_encode($res);
    return;
}