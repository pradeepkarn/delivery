<?php 
function signup_before_checkout($email,$pass,$cnfpass)
{
    if (isset($email) && isset($pass) && isset($cnfpass)) {
        if ($pass === $cnfpass) {
            $account = new Account();
            $user = $account->register($email,$pass);
            if ($user != false) {
                $cookie_name = "remember_token";
                $cookie_value = bin2hex(random_bytes(32))."_uid_".$user['id'];
                setcookie($cookie_name, $cookie_value, time() + (86400 * 30*12), "/"); // 86400 = 1 day
                $db = new Mydb('pk_user');
                $db->pk($_SESSION['user_id']);
                $arr = null;
                $arr['remember_token'] = $cookie_value;
                $db->updateData($arr);
                $arr = null;
                return $user;
            }
            else{
                $_SESSION['msg'][] = "Sorry something went wrong";
                return false;
            }
        }
        else{
            $_SESSION['msg'][] = "Sorry, Password did not match";
            return false;
        }
        
    }
}

function cart_session_to_db($customer_email){
    if (isset($_SESSION['cart'])) {
        foreach (array_keys($_SESSION['cart']) as $key => $pid) { 
            $id =  $_SESSION['cart'][$pid]['id'];
            $qty = $_SESSION['cart'][$pid]['qty'];
            $db = new Model('content');
            $prod = $db->show($id);
            if ($prod != false) {
                $cartObj = new Model('customer_order');
                $store_ary['item_id'] = $prod['id'];
                $store_ary['status'] = 'cart';
                $store_ary['customer_email'] = $customer_email;
                //check item in cart
                $if_exists = $cartObj->filter_index($store_ary);
                $sale_price = $prod['sale_price']==""?0:$prod['sale_price'];
                // $is_sale = (($prod['sale_price'])!="" && ($prod['sale_price'])>0)?true:false;
                $net_price = $prod['price']-$sale_price;
                $store_ary['price'] = $net_price;
                if($if_exists==false){
                    $store_ary['qty'] = $qty;
                    $lastid[] = $cartObj->store($store_ary);
                }
                $store_ary = null;
            }
            
        }
        unset($_SESSION['cart']);
        return true;
    }
    else{
        return false;
    }
}

function create_payment_obj($email){
  

    $db2 = new Dbobjects;
    $db2->tableName = "customer_order";
     $arr = null;
     $arr['status'] = "hold";
     $arr['customer_email'] = $email;
     if(count($db2->filter($arr))>0){
        $mycart = $db2->filter($arr,"DESC",$limit=10000);
        foreach ($mycart as $key => $crtv) {
            $qty = $crtv['qty'];
            $price = $crtv['price'];
            $amts[] = $qty*$price;
        }
    $tamt = array_sum($amts);
    $payment_id = "MBL".sanitize_remove_tags($_POST['mobile_number'])."TME".time().uniqid("UID_");
    //table 1
    $order_note = isset($_POST['order_note'])?sanitize_remove_tags($_POST['order_note']):"";
    $db = new Dbobjects;
    $db->tableName = "customer_payment";
    $arr = ['unique_id'=>$payment_id,
    'amount'=>$tamt,'status'=>'initiated',
    'payment_method'=>sanitize_remove_tags($_POST['payment_method']),
    'customer_email'=>$email,
    'first_name'=>sanitize_remove_tags($_POST['first_name']),
    'last_name'=>sanitize_remove_tags($_POST['last_name']),
    'isd_code'=>sanitize_remove_tags($_POST['isd_code']),
    'mobile'=>sanitize_remove_tags($_POST['mobile_number']),
    'locality'=>sanitize_remove_tags($_POST['my_location']),
    'city'=>sanitize_remove_tags($_POST['city_name']),
    'state'=>sanitize_remove_tags($_POST['state_name']),
    'country'=>sanitize_remove_tags($_POST['country_name']),
    'zipcode'=>sanitize_remove_tags($_POST['zip_code']),
    'order_note'=>$order_note
    ];
    $db->insertData = $arr;
    $sql = $db->create_sql();
    //print_r($sql);
    //table 2   
    try{
        //start transaction
    $db->dbpdo()->beginTransaction();
    $db->dbpdo()->exec($sql);
    //connection between table 1 and 2
    $arrpm['payment_id'] = $db->dbpdo()->lastInsertId();
    $arrpm['is_paid'] = 1;
    $db2->insertData = $arrpm;
    $sqlpm = $db2->update_sql(); //payment sql for new user
    //work for any transaction sql
    //print_r($sqlpm);
    $db->dbpdo()->exec($sqlpm);
    // if everything is fine end transaction
    $db->dbpdo()->commit();
    $_SESSION['payment_id'] = $arrpm['payment_id'];
    return true;
    }
    catch (Exception $e) {
        // if everything is not ok
        echo $e;
        $db->dbpdo()->rollBack();
        $db2->delete();
        return false;
      }
}
















    // (new Model('customer_payment'))->store($arr);
    

}
function hold_customer_cart($email){  
    $mycart = new Model('customer_order');
    $mycart = $mycart->filter_index(array('customer_email'=>$email,'status'=>'cart'));
    if ($mycart==false) {
        echo js_alert("Your cart is empty");
        return false;
    }
    foreach ($mycart as $key => $cart) { 
        $myord = new Mydb('customer_order');
        $myord->pk($cart['id']);
        $id =  $cart['item_id'];
        $qty = $cart['qty'];
        $item = new Mydb('content');
        $prod = $item->pkData($id);
        $old_hold_obj = (new Model('customer_order'))->filter_index(array('item_id'=>$id,'status'=>'hold'));
        $old_hold_db_qty = 0;
        if ($old_hold_obj!=false) {
           foreach ($old_hold_obj as $key => $hldqty) {
            $hldqtyar[] = $hldqty['qty'];
           }
           $old_hold_db_qty = array_sum($hldqtyar);
        }
        else{
            $old_hold_db_qty = 0;
        }
        // $old_hold_db_qty = $old_hold_db_qty!=?$old_hold_db_qty:0;
        $prod_qty_minu_hold_qty = $prod['qty'] - $old_hold_db_qty;
        if ($prod_qty_minu_hold_qty>=$qty) {
            $myord->updateTransData(['qty'=>$qty,'status'=>'hold']);
            // $newqty = $prod['qty']-$qty;
            // $item->updateTransData(['qty'=>$newqty]);
        }
        elseif (( 0 < $prod_qty_minu_hold_qty) && ($prod_qty_minu_hold_qty < $qty)) {
            $myord->updateTransData(['qty'=>$prod['qty'],'status'=>'hold']);
            // $item->updateTransData(['qty'=>0]);
        }
        elseif ($prod_qty_minu_hold_qty==0){
            $myord->deleteData();
        }
    }
   
}



