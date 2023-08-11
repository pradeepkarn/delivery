<?php
import("apps/api/v1/api.cart/fn.cart.php");
import("apps/api/v1/api.cart/fn.checkout.php");

$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST') {
    $token = isset($_POST['token']) ? $_POST['token'] : null;
    $address_id = isset($_POST['address_id']) ? $_POST['address_id'] : null;
    $payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : 'NA';
    $salesperson_id = isset($_POST['salesperson_id']) ? $_POST['salesperson_id'] : 0;
    $discount_type = isset($_POST['discount_type']) ? $_POST['discount_type'] : "NA";
    $discount_amt = isset($_POST['discount_amt']) ? $_POST['discount_amt'] : 0;
    $discount_ref = isset($_POST['discount_ref']) ? $_POST['discount_ref'] : 0;
    $salesperson_id = $salesperson_id==null ? 0 : $salesperson_id;
    if ($salesperson_id>0) {
        $delivery_method="salesman";
    }else{
        $delivery_method="courier";
    }
    // Do something with the data
} elseif ($method === 'GET') {
    $res['msg'] = "Wrong method to send data";
    $res['data'] = null;
    echo json_encode($res);
    return;
}

if ($token == null) {
    $res['msg'] = "Empty token not allowed";
    $res['data'] = null;
    echo json_encode($res);
    return;
}
if ($address_id == null) {
    $res['msg'] = "Invalid address id";
    $res['data'] = null;
    echo json_encode($res);
    return;
}

$adrs = getData('address', $address_id);
if ($adrs == false) {
    $res['msg'] = "Address not found";
    $res['data'] = null;
    echo json_encode($res);
    return;
}
$req_post = $adrs;

$req_arr = array(
    'name' => $adrs['name'],
    'company' => $adrs['company'],
    'isd_code' => $adrs['isd_code'],
    'mobile' => $adrs['mobile'],
    'latitude' => $adrs['latitude'],
    'longitude' => $adrs['longitude'],
    'delivery_instruction' => $adrs['delivery_instruction'],
    'receiver_house_no' => $adrs['receiver_house_no'],
    'house_no' => $adrs['house_no'],
    'address_type' => $adrs['address_type'],
    'near_by' => $adrs['near_by'],
    'salesperson_id' => $salesperson_id,
    'deliver_via' => $delivery_method,
    'payment_method' => $payment_method,
    'discount_type' => $discount_type,
    'discount_amt' => $discount_amt,
    'discount_ref' => $discount_ref
);

$email = user_data_by_token($token, 'email');
hold_customer_cart_api($email);

$payment = create_payment_obj_api($token, $req_arr);
if ($payment!=false) {
    if (confirm_pay($payment['payment_id'])) {
        $cart = get_my_processing_api($token);
        if (count($cart) > 0) {
            $res['msg'] = "success";
            $res['data'] = $cart['carts'];
            echo json_encode($res);
            return;
        } else {
            $res['msg'] = "No data found";
            $res['data'] = null;
            echo json_encode($res);
            return;
        }
    }
}else{
    $res['msg'] = "Invalid token";
    $res['data'] = null;
    echo json_encode($res);
    return;
    
}


