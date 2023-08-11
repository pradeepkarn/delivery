<?php
import("apps/api/v1/api.coupons/fn.coupons.php");
if (
    isset($_POST['token'])
    && isset($_POST['amount'])
    && isset($_POST['cpcode'])
) {
    $arr = null;
    $token = $_POST['token'];
    $userobj = new Model('pk_user');
    $user = $userobj->filter_index(array('app_login_token' => $token));
    if (count($user)>0) {
        $user = $user[0];
    }else{
        $data['msg'] = "User not found, token expired";
        $data['data'] = null;
        echo json_encode($data);
        die();
    }
    $amt = get_discounted_amt($_POST['amount'],$_POST['cpcode']);
    // if ($amt==false) {
    //     $data['msg'] = "Invalid coupon";
    //     $data['data'] =  null;
    //     echo json_encode($data);
    //     die();
    // }
    $data['msg'] = $amt['msg'];
    $data['data'] = $amt['amt'];
    echo json_encode($data);
    die();
}else {
    $data['msg'] = "Missing required field";
    $data['data'] = null;
    echo json_encode($data);
    die();
}