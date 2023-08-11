<?php
import("apps/api/v1/api.address/fn.address.php");
if (
    isset($_POST['token'])
    && isset($_POST['company'])
    && isset($_POST['house_no'])
    && isset($_POST['near_by'])
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
  
    if (isset($_POST['latitude'])) {
        $arr['latitude'] = $_POST['latitude'];
    }
    if (isset($_POST['longitude'])) {
        $arr['longitude'] = $_POST['longitude'];
    }
    if (isset($_POST['delivery_instruction'])) {
        $arr['delivery_instruction'] = $_POST['delivery_instruction'];
    }
    if (isset($_POST['address_type'])) {
        $arr['address_type'] = $_POST['address_type'];
    }
    if (isset($_POST['name'])) {
        $arr['name'] = $_POST['name'];
    }
    if (isset($_POST['receiver_house_no'])) {
        $arr['receiver_house_no'] = $_POST['receiver_house_no'];
    }
    
    
 
    $addrObj = new Model('address');

    $arr['user_id'] = $user['id'];

    $arr['isd_code'] = $user['isd_code'];
    $arr['house_no'] = $_POST['house_no'];
    $arr['mobile'] = $user['mobile'];
    $arr['near_by'] = $_POST['near_by'];
 
    $arr['company'] = $_POST['company'];
    
    // print_r($arr);
    $addressid = $addrObj->store($arr);
    //if evrything valid then

    if ($addressid == false) {
        $data['msg'] = "Address not created";
        $data['data'] = null;
        echo json_encode($data);
        die();
    }
    if ($addressid == 0) {
        $data['msg'] = "Address not created";
        $data['data'] = null;
        echo json_encode($data);
        die();
    }
    $data['msg'] = "Address created successfully";
    $data['data'] =  address_detail($token,$addressid);
    echo json_encode($data);
    return;
} else {
    $data['msg'] = "Missing required field";
    $data['data'] = null;
    echo json_encode($data);
    die();
}
