<?php
import("apps/api/v1/api.orders/fn.orders.php");

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
// if (isset($_POST['payment_id'])) {
//     $address_detail = order_detail($token,$_POST['payment_id']);
//     if ($address_detail!=false) {
//         $res['msg'] = "success";
//         $res['data'] = $address_detail;
//         echo json_encode($res);
//         return;
//     }else{
//         $res['msg'] = "No data found";
//         $res['data'] = null;
//         echo json_encode($res);
//         return;
//     }
// }
$order_list = salesman_orders_his($token);
if(count($order_list)>0){
    $res['msg'] = "success";
    $res['data'] = $order_list;
    echo json_encode($res);
    return;
}else{
    $res['msg'] = "No data found";
    $res['data'] = array();
    echo json_encode($res);
    return;
}