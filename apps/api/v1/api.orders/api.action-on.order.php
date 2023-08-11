<?php
import("apps/api/v1/api.orders/fn.orders.php");

$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST') {
    $token = isset($_POST['token'])?$_POST['token']:null;
    $delivery_date = isset($_POST['delivery_date'])?$_POST['delivery_date']:null;
    $last_action_on = isset($_POST['last_action_on'])?$_POST['last_action_on']:null;
    $order_id = isset($_POST['order_id'])?$_POST['order_id']:null;
    $cancel_info = isset($_POST['cancel_info'])?$_POST['cancel_info']:null;
    $order_status = isset($_POST['order_status'])?$_POST['order_status']:null;
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

if ($order_status!=null) {
    $up_arr['order_status'] = $order_status;
}
if ($last_action_on!=null) {
    $up_arr['last_action_on'] = $last_action_on;
}
$up_arr['cancel_info'] = $cancel_info;
$up_arr['delivery_date'] = $delivery_date;

$reply = update_salesman_order_status($token, $order_id, $up_arr);

if ($reply==true) {
    $res['msg'] = "success";
    $res['data'] = array();
    echo json_encode($res);
    return;
}else{
    $res['msg'] = "failed";
    $res['data'] = null;
    echo json_encode($res);
    return;
}