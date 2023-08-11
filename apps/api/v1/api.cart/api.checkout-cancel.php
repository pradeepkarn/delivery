<?php 
import("apps/api/v1/api.cart/fn.cart.php");
import("apps/api/v1/api.cart/fn.checkout.php");

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

$email = user_data_by_token($token,'email');

$cancel = cancel_hold_items($email);


$res['msg'] = "success";
$res['data'] = $cancel;
echo json_encode($res);
return;
