<?php
function list_address($token)
{
    $userobj = new Model('pk_user');
    $user = $userobj->filter_index(array('app_login_token' => $token));
    if (count($user)>0) {
        $user = $user[0];
    }else{
        return array();
    }
    $addrobj = new Model('address');
    $addr = $addrobj->filter_index(array('user_id'=>$user['id']));
    $addrar = array();
    foreach ($addr as $adr) {
        $addrarr[] = array(
            'id'=>$adr['id'],
            'address_type'=>$adr['address_type'],
            'name'=>$adr['name'],
            'near_by'=>$adr['near_by'],
            'company'=>$adr['company'],
            'house_no'=>$adr['house_no'],
            'delivery_instruction'=>$adr['delivery_instruction'],
            'latitude'=>$adr['latitude'],
            'longitude'=>$adr['longitude']
        );
    }
    return $addrarr;
}
function address_detail($token,$addressid)
{
    $userobj = new Model('pk_user');
    $user = $userobj->filter_index(array('app_login_token' => $token));
    if (count($user)>0) {
        $user = $user[0];
    }else{
        return array();
    }
    $addrobj = new Model('address');
    $addr = $addrobj->filter_index(array('user_id'=>$user['id'],'id'=>$addressid));
    if (count($addr)>0) {
        $adr = $addr[0];
        $addrarr = array(
            'id'=>$adr['id'],
            'address_type'=>$adr['address_type'],
            'name'=>$adr['name'],
            'near_by'=>$adr['near_by'],
            'company'=>$adr['company'],
            'house_no'=>$adr['house_no'],
            'delivery_instruction'=>$adr['delivery_instruction'],
            'latitude'=>$adr['latitude'],
            'longitude'=>$adr['longitude']
        );
        return $addrarr;
    }else{
        return false;
    }
    return false;
}