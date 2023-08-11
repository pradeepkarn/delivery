<?php
import("apps/api/v1/api.address/fn.address.php");

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
    $res['msg'] = "No data found";
    $res['data'] = null;
    echo json_encode($res);
    return;
}
if (isset($_POST['address_id'])) {
    $address_detail = address_detail($token,$_POST['address_id']);
    if ($address_detail!=false) {
        $res['msg'] = "success";
        $res['data'] = $address_detail;
        echo json_encode($res);
        return;
    }else{
        $res['msg'] = "No data found";
        $res['data'] = null;
        echo json_encode($res);
        return;
    }
}
$address_list = list_address($token);
if(count($address_list)>0){
    $res['msg'] = "success";
    $res['data'] = $address_list;
    echo json_encode($res);
    return;
}else{
    $res['msg'] = "No data found";
    $res['data'] = null;
    echo json_encode($res);
    return;
}