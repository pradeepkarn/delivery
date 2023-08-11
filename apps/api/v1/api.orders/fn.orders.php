<?php
function list_orders($token)
{
    $userobj = new Model('pk_user');
    $user = $userobj->filter_index(array('app_login_token' => $token));
    if (count($user) > 0) {
        $user = $user[0];
    } else {
        return array();
    }
    $paymentObj = new Model('customer_payment');
    $paments = $paymentObj->filter_index(array('customer_email' => $user['email']));
    $pmntsarr = array();
    foreach ($paments as $pmt) {
        $purchased_items = purchased_items_by_payment($pmt['id']);
        // $cartObj = new Model('customer_cart');
        // $cartlist = $cartObj->filter_index(array('payment_id'=>$pmt['id']));
        // foreach ($cartlist as $cart) {
        //     array(
        //         'cart_id'=>$cart['id'],
        //         'item_id'=>$cart['item_id'],
        //     );
        // }
        $pmntsarr[] = array(
            'id' => $pmt['id'],
            'unique_id' => $pmt['unique_id'],
            'amount' => $pmt['amount'],
            'payment_status' => $pmt['status'],
            'order_status' => $pmt['order_status'],
            'wh_status' => $pmt['wh_status'],
            'whmanager_id' => $pmt['whmanager_id'],
            'name' => $pmt['name'],
            'mobile' => $pmt['mobile'],
            'company' => $pmt['company'],
            'house_no' => $pmt['house_no'],
            'delivery_instruction' => $pmt['delivery_instruction'],
            'latitude' => $pmt['latitude'],
            'longitude' => $pmt['longitude'],
            'address_type' => $pmt['address_type'],
            'house_no' => $pmt['house_no'],
            'receiver_house_no' => $pmt['receiver_house_no'],
            'near_by' => $pmt['near_by'],
            'created_at' => $pmt['created_at'],
            'last_action_on' => $pmt['last_action_on'],
            'purchased_items' => $purchased_items
        );
    }
    return $pmntsarr;
}
function order_detail($token, $payment_id)
{
    $userobj = new Model('pk_user');
    $user = $userobj->filter_index(array('app_login_token' => $token));
    if (count($user) > 0) {
        $user = $user[0];
    } else {
        return array();
    }
    $paymentObj = new Model('customer_payment');
    $paments = $paymentObj->filter_index(array('customer_email' => $user['email'], 'id' => $payment_id));
    if (count($paments) > 0) {
        $pmt = $paments[0];
        $purchased_items = purchased_items_by_payment($pmt['id']);
        $pmntsarr = array(
            'id' => $pmt['id'],
            'unique_id' => $pmt['unique_id'],
            'amount' => $pmt['amount'],
            'payment_status' => $pmt['status'],
            'order_status' => $pmt['order_status'],
            'wh_status' => $pmt['wh_status'],
            'whmanager_id' => $pmt['whmanager_id'],
            'name' => $pmt['name'],
            'mobile' => $pmt['mobile'],
            'company' => $pmt['company'],
            'house_no' => $pmt['house_no'],
            'delivery_instruction' => $pmt['delivery_instruction'],
            'latitude' => $pmt['latitude'],
            'longitude' => $pmt['longitude'],
            'address_type' => $pmt['address_type'],
            'house_no' => $pmt['house_no'],
            'receiver_house_no' => $pmt['receiver_house_no'],
            'near_by' => $pmt['near_by'],
            'created_at' => $pmt['created_at'],
            'last_action_on' => $pmt['last_action_on'],
            'purchased_items' => $purchased_items
        );
        return $pmntsarr;
    } else {
        return false;
    }
    return false;
}


function purchased_items_by_payment($payemntid)
{
    $mycart = array();
    $cartObj = new Model('customer_order');
    $get_cart['payment_id'] = $payemntid;
    $cart = $cartObj->filter_index($get_cart);
    foreach ($cart as $ck => $cv) {
        $clrloop = array();
        $prod = getData('content', $cv['item_id']);
        foreach (json_decode($cv['color_list']) as $clv) {
            $mycart[] = array(
                'cart_id' => $cv['id'],
                'payment_id' => $cv['payment_id'],
                'item_id' => $prod['id'],
                'item_name' => $prod['title'],
                'bulk_qty' => $prod['bulk_qty'],
                'item_image' => img_by_color($prod['id'], $clv->color) != null ? img_by_color($prod['id'], $clv->color) : "/media/images/pages/{$prod['banner']}",
                'item_price' => $cv['price'],
                'item_cart_qty' => $cv['qty'],
                'is_paid' => $cv['is_paid'],
                'color' => $clv->color,
                'color_cart_qty' => $clv->qty,
                'remark' => $cv['remark'],
                "shipping_status" => $cv['shipping_status'],
                "shipping_id" => $cv['shipping_id'],
                "created_at" => $cv['created_at'],
                "updated_at" => $cv['updated_at']
            );
        }
    }
    return $mycart;
}

function img_by_color($content_id,$color)
{
    $moreobj = new Model('content_details');
    $moreimg = $moreobj->filter_index(array('content_id'=>$content_id,'content_group'=>'product_more_img','color'=>$color));
    // $moreimg = $moreimg==false?array():$moreimg;
    if (count($moreimg)==0) {
        $mor_imgs = null;
    }
    else{
        foreach ($moreimg as $key => $fvl):
        $mor_imgs = "/media/images/pages/{$fvl['content']}";
        endforeach;
    }  
    return $mor_imgs;
}

// Salesman
function salesman_orders($token)
{
    $userobj = new Model('pk_user');
    $user = $userobj->filter_index(array('app_login_token' => $token));
    if (count($user) > 0) {
        $user = $user[0];
    } else {
        return array();
    }
    $paymentObj = new Model('customer_payment');
    $paments = $paymentObj->filter_index(array('deliver_via'=>'salesman','salesperson_id'=>$user['id'],'order_status'=>'processing'));
    $pmntsarr = array();
    foreach ($paments as $pmt) {
        $purchased_items = purchased_items_by_payment($pmt['id']);
        $pmntsarr[] = array(
            'id' => $pmt['id'],
            'unique_id' => $pmt['unique_id'],
            'amount' => $pmt['amount'],
            'payment_status' => $pmt['status'],
            'order_status' => $pmt['order_status'],
            'wh_status' => $pmt['wh_status'],
            'whmanager_id' => $pmt['whmanager_id'],
            'name' => $pmt['name'],
            'mobile' => $pmt['mobile'],
            'company' => $pmt['company'],
            'house_no' => $pmt['house_no'],
            'delivery_instruction' => $pmt['delivery_instruction'],
            'latitude' => $pmt['latitude'],
            'longitude' => $pmt['longitude'],
            'address_type' => $pmt['address_type'],
            'house_no' => $pmt['house_no'],
            'receiver_house_no' => $pmt['receiver_house_no'],
            'near_by' => $pmt['near_by'],
            'created_at' => $pmt['created_at'],
            'delivery_date' => $pmt['delivery_date'],
            'cancel_info' => $pmt['cancel_info'],
            'last_action_on' => $pmt['last_action_on'],
            'purchased_items' => $purchased_items
        );
    }
    return $pmntsarr;
}
function salesman_orders_his($token)
{
    $userobj = new Model('pk_user');
    $user = $userobj->filter_index(array('app_login_token' => $token));
    if (count($user) > 0) {
        $user = $user[0];
    } else {
        return array();
    }
    $paymentObj = new Model('customer_payment');
    $paments = $paymentObj->filter_index(
        array('deliver_via'=>'salesman','salesperson_id'=>$user['id'],)
    );
    $pmntsarr = array();
    foreach ($paments as $pmt) {
        $purchased_items = purchased_items_by_payment($pmt['id']);
        if ($pmt['order_status']=="rejected" || $pmt['wh_status']=="rejected" || $pmt['wh_status']=="dispatched") {
        $pmntsarr[] = array(
            'id' => $pmt['id'],
            'unique_id' => $pmt['unique_id'],
            'amount' => $pmt['amount'],
            'payment_status' => $pmt['status'],
            'order_status' => $pmt['order_status'],
            'wh_status' => $pmt['wh_status'],
            'whmanager_id' => $pmt['whmanager_id'],
            'name' => $pmt['name'],
            'mobile' => $pmt['mobile'],
            'company' => $pmt['company'],
            'house_no' => $pmt['house_no'],
            'delivery_instruction' => $pmt['delivery_instruction'],
            'latitude' => $pmt['latitude'],
            'longitude' => $pmt['longitude'],
            'address_type' => $pmt['address_type'],
            'house_no' => $pmt['house_no'],
            'receiver_house_no' => $pmt['receiver_house_no'],
            'near_by' => $pmt['near_by'],
            'created_at' => $pmt['created_at'],
            'delivery_date' => $pmt['delivery_date'],
            'cancel_info' => $pmt['cancel_info'],
            'last_action_on' => $pmt['last_action_on'],
            'purchased_items' => $purchased_items
        );
    }
}
    return $pmntsarr;
}

function update_salesman_order_status($token, $payment_id, $up_arr){
    $userobj = new Model('pk_user');
    $user = $userobj->filter_index(array('app_login_token' => $token));
    if (count($user) > 0) {
        $user = $user[0];
    } else {
        return false;
    }
 
    $paymentObj = new Model('customer_payment');
    $ord = $paymentObj->filter_index(array('id' => $payment_id,'salesperson_id'=>$user['id']));
    if ($ord!=false) {
        if (!isset($up_arr['id'])) {
            return $paymentObj->update($payment_id,$up_arr);
        }else{
            return false;
        }
    }
    return false;
    
}
function forward_to_warehouse($token, $payment_id, $up_arr){
    $userobj = new Model('pk_user');
    $user = $userobj->filter_index(array('app_login_token' => $token));
    if (count($user) > 0) {
        $user = $user[0];
    } else {
        return false;
    }
 
    $paymentObj = new Model('customer_payment');
    $ord = $paymentObj->filter_index(array('id' => $payment_id,'salesperson_id'=>$user['id']));
    if ($ord!=false && ($ord[0]['wh_status']=="ordered" || $ord[0]['wh_status']=="")) {
        if (!isset($up_arr['id'])) {
            return $paymentObj->update($payment_id,$up_arr);
        }else{
            return false;
        }
    }
    return false;
    
}

function salesman_pending_orders_his($token,$not_order_status="processing")
{
    $userobj = new Model('pk_user');
    $user = $userobj->filter_index(array('app_login_token' => $token));
    if (count($user) > 0) {
        $user = $user[0];
    } else {
        return array();
    }
    $paymentObj = new Model('customer_payment');
    $paments = $paymentObj->filter_where_not(
        array('deliver_via'=>'salesman','salesperson_id'=>$user['id'],),
        array('order_status'=>$not_order_status,'wh_status'=>'dispatched'),"!="
    );
    $pmntsarr = array();
    foreach ($paments as $pmt) {
        $purchased_items = purchased_items_by_payment($pmt['id']);
        if ($pmt['order_status']=="accepted" || $pmt['order_status']=="ready" || $pmt['order_status']=="pending") {
            $pmntsarr[] = array(
                'id' => $pmt['id'],
                'unique_id' => $pmt['unique_id'],
                'amount' => $pmt['amount'],
                'payment_status' => $pmt['status'],
                'order_status' => $pmt['order_status'],
                'wh_status' => $pmt['wh_status'],
                'whmanager_id' => $pmt['whmanager_id'],
                'name' => $pmt['name'],
                'mobile' => $pmt['mobile'],
                'company' => $pmt['company'],
                'house_no' => $pmt['house_no'],
                'delivery_instruction' => $pmt['delivery_instruction'],
                'latitude' => $pmt['latitude'],
                'longitude' => $pmt['longitude'],
                'address_type' => $pmt['address_type'],
                'house_no' => $pmt['house_no'],
                'receiver_house_no' => $pmt['receiver_house_no'],
                'near_by' => $pmt['near_by'],
                'created_at' => $pmt['created_at'],
                'delivery_date' => $pmt['delivery_date'],
                'cancel_info' => $pmt['cancel_info'],
                'last_action_on' => $pmt['last_action_on'],
                'purchased_items' => $purchased_items
            );
        }
        
    }
    return $pmntsarr;
}