<?php 
import("apps/api/v1/api.cart/fn.cart.php");
import("apps/api/v1/api.cart/fn.checkout.php");

$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST') {
    // $token = isset($_POST['token'])?$_POST['token']:null;
    $data = json_decode(file_get_contents('php://input'));
    if (!isset($data->token)) {
        $res['msg'] = "token is required";
        $res['data'] = null;
        echo json_encode($res);
        return;
    }
    if (!isset($data->carts)) {
        $res['msg'] = "Carts data is required";
        $res['data'] = null;
        echo json_encode($res);
        return;
    }
    if (count($data->carts)==0 || $data->carts==null) {
        $res['msg'] = "Empty cart is not allowed";
        $res['data'] = null;
        echo json_encode($res);
        return;
    }
    $token = $data->token;
    $carts = $data->carts;
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
// print_r($data->carts);
// return;
update_my_cart($data->carts);
hold_customer_cart_api($email);
// $req_arr = $_POST;
// $payment = create_payment_obj_api($token,$req_arr);
// if ($payment && $payment['is_success']) {
//     confirm_pay($payment['payment_id']);
// }

$cart = get_my_hold_api($token);
if(count($cart)>0){
    $res['msg'] = "success";
    $res['data'] = $cart['carts'];
    echo json_encode($res);
    return;
}else{
    $res['msg'] = "No data found";
    $res['data'] = null;
    echo json_encode($res);
    return;
}
