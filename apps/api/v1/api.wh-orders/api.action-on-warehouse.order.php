<?php
import("apps/api/v1/api.wh-orders/fn.orders.php");

$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST') {
    $token = isset($_POST['token'])?$_POST['token']:null;
    // $delivery_date = isset($_POST['delivery_date'])?$_POST['delivery_date']:null;
    $whmanager_id = isset($_POST['whmanager_id'])?$_POST['whmanager_id']:0;
    // $last_action_on = isset($_POST['last_action_on'])?$_POST['last_action_on']:null;
    $order_id = isset($_POST['order_id'])?$_POST['order_id']:null;
    $wh_info = isset($_POST['wh_info'])?$_POST['wh_info']:null;
    $wh_status = isset($_POST['wh_status'])?$_POST['wh_status']:null;
    // $wh_status = "new";
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

if ($wh_status==null) {
    $res['msg'] = "Empty warehouse status not allowed";
    $res['data'] = null;
    echo json_encode($res);
    return;
}
if ($wh_status=="rejected") {
    if ($wh_info=="") {
        $res['msg'] = "Please provide rejection reason";
        $res['data'] = null;
        echo json_encode($res);
        return;
    }
}
$up_arr['wh_status'] = $wh_status;
// if ($last_action_on!=null) {
//     $up_arr['last_action_on'] = $last_action_on;
// }
// if ($wh_status!=null) {
//     $up_arr['wh_info'] = "new";
// }

$up_arr['whmanager_id'] = $whmanager_id;
if ($whmanager_id==0) {
    $res['msg'] = "Please provide warehouse manager id";
    $res['data'] = null;
    echo json_encode($res);
    return;
}
$up_arr['wh_info'] = $wh_info;

$reply = change_warehouse_status($token, $order_id, $up_arr);

if ($reply==true) {
    $res['msg'] = "success";
    $res['data'] = array();
    echo json_encode($res);
    return;
}else{
    $res['msg'] = "Not updated";
    $res['data'] = null;
    echo json_encode($res);
    return;
}