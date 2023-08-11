<?php
import("apps/api/v1/api.address/fn.address.php");
if (
    isset($_POST['token'])
    && isset($_POST['address_id'])
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
     
 
    $addrObj = new Model('address');
    $address_detail = address_detail($token,$_POST['address_id']);
    $address = false;
    if ($address_detail!=false) {
        $address = $addrObj->destroy($_POST['address_id'],$arr);
    }
   
    //if evrything valid then

    if ($address == false) {
        $data['msg'] = "Address not found";
        $data['data'] = null;
        echo json_encode($data);
        die();
    }
    $data['msg'] = "Address deleted successfully";
    $data['data'] = null;
    echo json_encode($data);
    return;
} else {
    $data['msg'] = "Missing required field";
    $data['data'] = null;
    echo json_encode($data);
    die();
}
