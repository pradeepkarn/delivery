<?php
function signup_before_checkout_api($email, $pass, $cnfpass)
{
    if (isset($email) && isset($pass) && isset($cnfpass)) {
        if ($pass === $cnfpass) {
            $account = new Account();
            $user = $account->register($email, $pass);
            if ($user != false) {
                $cookie_name = "remember_token";
                $cookie_value = bin2hex(random_bytes(32)) . "_uid_" . $user['id'];
                setcookie($cookie_name, $cookie_value, time() + (86400 * 30 * 12), "/"); // 86400 = 1 day
                $db = new Mydb('pk_user');
                $db->pk($_SESSION['user_id']);
                $arr = null;
                $arr['remember_token'] = $cookie_value;
                $db->updateData($arr);
                $arr = null;
                return $user;
            } else {
                $_SESSION['msg'][] = "Sorry something went wrong";
                return false;
            }
        } else {
            $_SESSION['msg'][] = "Sorry, Password did not match";
            return false;
        }
    }
}


function save_cart_to_db_api_v2($customer_email, $cart_arr, $colors = array('color' => 'mixed', 'qty' => 5))
{
    if (isset($cart_arr)) {
        $lastid = array();
        foreach (array_keys($cart_arr) as $key => $pid) {
            $id =  $cart_arr[$pid]['id'];
            $qty = $cart_arr[$pid]['qty'];
            $db = new Model('content');
            $prod = $db->show($id);
            if ($prod != false) {
                $cartObj = new Model('customer_order');
                $store_ary['item_id'] = $prod['id'];
                $store_ary['status'] = 'cart';
                $store_ary['customer_email'] = $customer_email;
                //check item in cart
                $if_exists = $cartObj->filter_index($store_ary);
                $discount_amt = $prod['discount_amt'] == null ? 0 : $prod['discount_amt'];
                // $is_sale = (($prod['sale_price'])!="" && ($prod['sale_price'])>0)?true:false;
                $net_price = $prod['price'] - $discount_amt;
                $store_ary['price'] = $net_price;
                $store_ary['color_list'] = json_encode($colors);
                if ($if_exists == false) {
                    $store_ary['qty'] = $qty;
                    $lastid[] = $cartObj->store($store_ary);
                } else if (count($if_exists) > 0) {
                    // $update_ary['qty'] = $qty + $if_exists[0]['qty'];
                    $update_ary['qty'] = $qty;
                    $update_ary['color_list'] = json_encode($colors);
                    $cartObj->update($if_exists[0]['id'], $update_ary);
                    $lastid[] = $if_exists[0]['id'];
                }
                $update_ary = null;
                $store_ary = null;
            }
        }

        $cartObj = new Model('customer_order');
        $get_cart['customer_email'] = $customer_email;
        $get_cart['status'] = 'cart';
        $get_cart_arr = $cartObj->filter_index($get_cart);
        $resp = array();
        foreach ($get_cart_arr as $key => $cv) {
            $resp[] = array(
                "id" => $cv['id'],
                "payment_id" => $cv['payment_id'],
                "item_id" => $cv['item_id'],
                "qty" => $cv['qty'],
                "price" => $cv['price'],
                "status" => $cv['status'],
                "customer_email" => $cv['customer_email'],
                "shipping_status" => $cv['shipping_status'],
                "shipping_id" => $cv['shipping_id'],
                "remark" => $cv['remark'],
                "is_paid" => $cv['is_paid'],
                "color_list" => json_decode($cv['color_list'])
            );
        }
        return $resp;
    } else {
        return false;
    }
}
function save_cart_to_db_api($customer_email, $cart_arr, $colors = array('color' => 'mixed', 'qty' => 5))
{
    if (isset($cart_arr)) {
        $lastid = array();
        foreach (array_keys($cart_arr) as $key => $pid) {
            $id =  $cart_arr[$pid]['id'];
            $qty = $cart_arr[$pid]['qty'];
            $db = new Model('content');
            $prod = $db->show($id);
            if ($prod != false) {
                $cartObj = new Model('customer_order');
                $store_ary['item_id'] = $prod['id'];
                $store_ary['status'] = 'cart';
                $store_ary['customer_email'] = $customer_email;
                //check item in cart
                $if_exists = $cartObj->filter_index($store_ary);
                $discount_amt = $prod['discount_amt'] == null ? 0 : $prod['discount_amt'];
                $vat = $prod['tax'];
                // $is_sale = (($prod['sale_price'])!="" && ($prod['sale_price'])>0)?true:false;
                $net_price = $prod['price'] - $discount_amt;
                $store_ary['price'] = $net_price+($net_price*($vat/100)); //vat implemented 28-02-2023
                $store_ary['color_list'] = json_encode($colors);
                if ($if_exists == false) {
                    $store_ary['qty'] = $qty;
                    $lastid[] = $cartObj->store($store_ary);
                } else if (count($if_exists) > 0) {
                    $update_ary['qty'] = $qty + $if_exists[0]['qty'];
                    // $update_ary['qty'] = $qty;


                    $cols = update_colors($colors, json_decode($if_exists[0]['color_list'], true));
                    //    $update_ary['color_list'] = json_encode($colors);
                    $update_ary['color_list'] = json_encode($cols);
                    $cartObj->update($if_exists[0]['id'], $update_ary);
                    $lastid[] = $if_exists[0]['id'];
                }
                $update_ary = null;
                $store_ary = null;
            }
        }

        $cartObj = new Model('customer_order');
        $get_cart['customer_email'] = $customer_email;
        $get_cart['status'] = 'cart';
        $get_cart_arr = $cartObj->filter_index($get_cart);
        $resp = array();
        foreach ($get_cart_arr as $key => $cv) {
            $resp[] = array(
                "id" => $cv['id'],
                "payment_id" => $cv['payment_id'],
                "item_id" => $cv['item_id'],
                "qty" => $cv['qty'],
                "price" => $cv['price'],
                "status" => $cv['status'],
                "customer_email" => $cv['customer_email'],
                "shipping_status" => $cv['shipping_status'],
                "shipping_id" => $cv['shipping_id'],
                "remark" => $cv['remark'],
                "is_paid" => $cv['is_paid'],
                "color_list" => json_decode($cv['color_list'])
            );
        }
        return $resp;
    } else {
        return false;
    }
}
function update_colors($colors, $add_array)
{
    foreach ($add_array as $add) {
        $color_found = false;
        foreach ($colors as &$color) {
            if ($color['color'] == $add['color']) {
                $color['qty'] += $add['qty'];
                $color_found = true;
                break;
            }
        }
        if (!$color_found) {
            $colors[] = $add;
        }
    }
    return $colors;
}
function create_payment_obj_api($token, $req_post)
{
    $data['is_success'] = false;
    $data['payment_id'] = 0;

    $user = new Model('pk_user');
    $arr['app_login_token'] = $token;
    $user = $user->filter_index($arr);
    if (!count($user) > 0) {
        return false;
    } else {
        $user = $user[0];
    }
    $db2 = new Dbobjects;
    $db2->tableName = "customer_order";
    $arr = null;
    $arr['status'] = "hold";
    $arr['customer_email'] = $user['email'];
    if (count($db2->filter($arr)) > 0) {
        $mycart = $db2->filter($arr, "DESC", $limit = 10000);
        foreach ($mycart as $key => $crtv) {
            $qty = $crtv['qty'];
            $price = $crtv['price'];
            $amts[] = $qty * $price;
        }
        $tamt = array_sum($amts);
        $payment_id = strtoupper(uniqid("ORD"));
        // $payment_id = strtoupper(uniqid("O"))."M".sanitize_remove_tags($req_post['mobile']) ."T" . time();
        //table 1
        $db = new Dbobjects;
        $db->tableName = "customer_payment";
        // $db->dbpdo()->beginTransaction();
        $final_amt = $tamt-$req_post['discount_amt'];
        $arr = array(
            'unique_id' => $payment_id,
            'amount' => $final_amt, 
            'status' => 'initiated',
            'payment_method' => $req_post['payment_method'],
            'customer_email' => $user['email'],
            'name' => $req_post['name'],
            'company' => $req_post['company'],
            'isd_code' => $req_post['isd_code'],
            'mobile' => $req_post['mobile'],
            'latitude' => $req_post['latitude'],
            'longitude' => $req_post['longitude'],
            'delivery_instruction' => $req_post['delivery_instruction'],
            'receiver_house_no' => $req_post['receiver_house_no'],
            'house_no' => $req_post['house_no'],
            'address_type' => $req_post['address_type'],
            'salesperson_id' => $req_post['salesperson_id'],
            'deliver_via' => $req_post['deliver_via'],
            'near_by' => $req_post['near_by'],
            'discount_type' => $req_post['discount_type'],
            'discount_amt' => $req_post['discount_amt'],
            'discount_ref' => $req_post['discount_ref']
        );
        try {
            $db->insertData = $arr;
            $id = $db->create();
            $data['is_success'] = true;
            $data['payment_id'] = $id;
            $db2->insertData['payment_id'] = $id;
            $db2->insertData['status'] = 'processing';
            $db2->update();
            // $db->dbpdo()->commit();
            return $data;
        } catch (Exception $e) {
            $data['is_success'] = false;
            $data['payment_id'] = 0;
            // $db2->insertData['payment_id'] = 0;
            // $db2->insertData['status'] = 'failed';
            cancel_hold_items($user['email'],$new_status='failed');
            // $db2->update();
            // $db->dbpdo()->rollBack();
            return $data;
        }
    }

}

// function create_payment_obj_api_old($token, $req_post)
// {
//     $user = new Model('pk_user');
//     $arr['app_login_token'] = $token;
//     $user = $user->filter_index($arr);
//     if (!count($user) > 0) {
//         return false;
//     } else {
//         $user = $user[0];
//     }
//     $db2 = new Dbobjects;
//     $db2->tableName = "customer_order";
//     $arr = null;
//     $arr['status'] = "hold";
//     $arr['customer_email'] = $user['email'];
//     if (count($db2->filter($arr)) > 0) {
//         $mycart = $db2->filter($arr, "DESC", $limit = 10000);
//         foreach ($mycart as $key => $crtv) {
//             $qty = $crtv['qty'];
//             $price = $crtv['price'];
//             $amts[] = $qty * $price;
//         }
//         $tamt = array_sum($amts);
//         $payment_id = "MBL" . sanitize_remove_tags($req_post['mobile']) . "TME" . time() . uniqid("UID_");
//         //table 1
//         $order_note = null;
//         $db = new Dbobjects;
//         $db->tableName = "customer_payment";
//         $arr = array(
//             'unique_id' => $payment_id,
//             'amount' => $tamt, 
//             'status' => 'initiated',
//             'payment_method' => $req_post['payment_method'],
//             'customer_email' => $user['email'],
//             'name' => $req_post['name'],
//             'company' => $req_post['company'],
//             'isd_code' => $req_post['isd_code'],
//             'mobile' => $req_post['mobile'],
//             'latitude' => $req_post['latitude'],
//             'longitude' => $req_post['longitude'],
//             'delivery_instruction' => $req_post['delivery_instruction'],
//             'receiver_house_no' => $req_post['receiver_house_no'],
//             'house_no' => $req_post['house_no'],
//             'address_type' => $req_post['address_type'],
//             'near_by' => $req_post['near_by']
//         );
//         $db->insertData = $arr;
//         $sql = $db->create_sql();
//         print_r($sql);
//         //table 2   
//         try {
//             //start transaction
//             $db->dbpdo()->beginTransaction();
//             $db->dbpdo()->exec($sql);
//             //connection between table 1 and 2
//             $paynetid = $db->dbpdo()->lastInsertId();
//             echo $paynetid;
//             $arrpm['payment_id'] = $paynetid;
//             $arrpm['is_paid'] = 1;
//             $db2->insertData = $arrpm;
//             $sqlpm = $db2->update_sql(); //payment sql for new user
//             //work for any transaction sql
//             print_r($sqlpm);
//             $db->dbpdo()->exec($sqlpm);
//             // if everything is fine end transaction
//             $db->dbpdo()->commit();
//             // $_SESSION['payment_id'] = $arrpm['payment_id'];
//             $data['is_success'] = true;
//             $data['payment_id'] = $arrpm['payment_id'];
//             $db->dbpdo()->commit();
//             return $data;
//         } catch (Exception $e) {
//             // if everything is not ok
//             // echo $e;
//             $db->dbpdo()->rollBack();
//             // $db2->delete();
//             $data['is_success'] = false;
//             $data['payment_id'] = 0;
//             return $data;
//         }
//     }

//     // (new Model('customer_payment'))->store($arr);


// }
function hold_customer_cart_api($email)
{
    $mycart = new Model('customer_order');
    $mycart = $mycart->filter_index(array('customer_email' => $email, 'status' => 'cart'));
    if ($mycart == false) {
        // echo js_alert("Your cart is empty");
        return false;
    }
    foreach ($mycart as $key => $cart) {
        $myord = new Mydb('customer_order');
        $myord->pk($cart['id']);
        $id =  $cart['item_id'];
        $qty = $cart['qty'];
        $item = new Mydb('content');
        $prod = $item->pkData($id);
        $old_hold_obj = (new Model('customer_order'))->filter_index(array('item_id' => $id, 'status' => 'hold'));
        $old_hold_db_qty = 0;
        if ($old_hold_obj != false) {
            foreach ($old_hold_obj as $key => $hldqty) {
                $hldqtyar[] = $hldqty['qty'];
            }
            $old_hold_db_qty = array_sum($hldqtyar);
        } else {
            $old_hold_db_qty = 0;
        }
        // $old_hold_db_qty = $old_hold_db_qty!=?$old_hold_db_qty:0;
        $prod_qty_minu_hold_qty = $prod['qty'] - $old_hold_db_qty;
        if ($prod_qty_minu_hold_qty >= $qty) {
            $myord->updateTransData(['qty' => $qty, 'status' => 'hold']);
            $newqty = $prod['qty'] - $qty;
            $item->updateTransData(['qty' => $newqty]);
        } elseif ((0 < $prod_qty_minu_hold_qty) && ($prod_qty_minu_hold_qty < $qty)) {
            $myord->updateTransData(['qty' => $prod['qty'], 'status' => 'hold']);
            $item->updateTransData(['qty' => 0]);
        } elseif ($prod_qty_minu_hold_qty == 0) {
            $myord->deleteData();
        }
    }
}



function get_my_processing_api($token)
{
    $user = new Model('pk_user');
    $arr['app_login_token'] = $token;
    $user = $user->filter_index($arr);
    if (!count($user) > 0) {
        return array();
    }
    $mycart = array();
    $cartObj = new Model('customer_order');
    $get_cart['customer_email'] = $user[0]['email'];
    $get_cart['status'] = 'processing';
    $cart = $cartObj->filter_index($get_cart);
    foreach ($cart as $ck => $cv) {
        $prod = getData('content', $cv['item_id']);
        $rel_prods = array();
        if ($prod['json_obj'] != null) {
            $jsn = json_decode($prod['json_obj']);
            if (isset($jsn->related_products)) {
                $rel_prods = rel_prods($jsn->related_products);
            }
        }
        $mycart['carts'][] = array(
            'cart_id' => $cv['id'],
            'payment_id' => $cv['payment_id'],
            'item_id' => $prod['id'],
            'item_name' => $prod['title'],
            'item_image' => "/media/images/pages/{$prod['banner']}",
            'item_price' => $cv['price'],
            'item_cart_qty' => $cv['qty'],
            'is_paid' => $cv['is_paid'],
            'status' => $cv['status'],
            'remark' => $cv['remark'],
            "shipping_status" => $cv['shipping_status'],
            "shipping_id" => $cv['shipping_id'],
            "created_at" => $cv['created_at'],
            "updated_at" => $cv['updated_at']
        );
        $mycart['rel_prods'][] = array(
            "related_to" => $prod['id'],
            "related_products" => $rel_prods
        );
    }
    return $mycart;
}
// function get_my_hold_api_old($token)
// {
//     $user = new Model('pk_user');
//     $arr['app_login_token'] = $token;
//     $user = $user->filter_index($arr);
//     if (!count($user) > 0) {
//         return array();
//     }
//     $mycart = array();
//     $cartObj = new Model('customer_order');
//     $get_cart['customer_email'] = $user[0]['email'];
//     $get_cart['status'] = 'hold';
//     $cart = $cartObj->filter_index($get_cart);
//     foreach ($cart as $ck => $cv) {
//         $prod = getData('content', $cv['item_id']);
//         $rel_prods = array();
//         if ($prod['json_obj'] != null) {
//             $jsn = json_decode($prod['json_obj']);
//             if (isset($jsn->related_products)) {
//                 $rel_prods = rel_prods($jsn->related_products);
//             }
//         }
//         $mycart['carts'][] = array(
//             'cart_id' => $cv['id'],
//             'payment_id' => $cv['payment_id'],
//             'item_id' => $prod['id'],
//             'item_name' => $prod['title'],
//             'item_image' => "/media/images/pages/{$prod['banner']}",
//             'item_price' => $cv['price'],
//             'item_cart_qty' => $cv['qty'],
//             'is_paid' => $cv['is_paid'],
//             'status' => $cv['status'],
//             'remark' => $cv['remark'],
//             "shipping_status" => $cv['shipping_status'],
//             "shipping_id" => $cv['shipping_id'],
//             "created_at" => $cv['created_at'],
//             "updated_at" => $cv['updated_at']
//         );
//         $mycart['rel_prods'][] = array(
//             "related_to" => $prod['id'],
//             "related_products" => $rel_prods
//         );
//     }
//     return $mycart;
// }
function get_my_hold_api($token)
{
    $user = new Model('pk_user');
    $arr['app_login_token'] = $token;
    $user = $user->filter_index($arr);
    if (!count($user) > 0) {
        return array();
    }
    $mycart = array();
    $cartObj = new Model('customer_order');
    $get_cart['customer_email'] = $user[0]['email'];
    $get_cart['status'] = 'hold';
    $cart = $cartObj->filter_index($get_cart);
    foreach ($cart as $ck => $cv) {
        $prod = getData('content', $cv['item_id']);
        $rel_prods = array();
        if ($prod['json_obj'] != null) {
            $jsn = json_decode($prod['json_obj']);
            if (isset($jsn->related_products)) {
                $rel_prods = rel_prods($jsn->related_products);
            }
        }
        // $mycart['carts'][] = array(
        //     'cart_id'=> $cv['id'],
        //     'payment_id'=> $cv['payment_id'],
        //     'item_id'=> $prod['id'],
        //     'item_name'=> $prod['title'],
        //     'item_image'=> "/media/images/pages/{$prod['banner']}",
        //     'item_price'=> $cv['price'],
        //     'item_cart_qty'=> $cv['qty'],
        //     'is_paid'=> $cv['is_paid'],
        //     'status'=> $cv['status'],
        //     'remark'=> $cv['remark'],
        //     "shipping_status"=> $cv['shipping_status'],
        //     "shipping_id"=> $cv['shipping_id'],
        //     "created_at"=> $cv['created_at'],
        //     "updated_at"=> $cv['updated_at']
        // );
        foreach (json_decode($cv['color_list']) as $kl => $clv) {
            $mycart['carts'][] = array(
                'cart_id' => $cv['id'],
                'payment_id' => $cv['payment_id'],
                'item_id' => $prod['id'],
                'item_name' => $prod['title'],
                'bulk_qty' => $prod['bulk_qty'],
                // 'item_image'=> "/media/images/pages/{$prod['banner']}",
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
        $mycart['rel_prods'][] = array(
            "related_to" => $prod['id'],
            "related_products" => $rel_prods
        );
    }
    return $mycart;
}
if (!function_exists("img_by_color")) {
    function img_by_color($content_id, $color)
    {
        $moreobj = new Model('content_details');
        $moreimg = $moreobj->filter_index(array('content_id' => $content_id, 'content_group' => 'product_more_img', 'color' => $color));
        // $moreimg = $moreimg==false?array():$moreimg;
        if (count($moreimg) == 0) {
            $mor_imgs = null;
        } else {
            foreach ($moreimg as $key => $fvl) :
                $mor_imgs = "/media/images/pages/{$fvl['content']}";
            endforeach;
        }
        return $mor_imgs;
    }
}
function confirm_pay($payment_id)
{
    if ($payment_id != 0) {
        $pmnt = new Model('customer_payment');
        $pmnt->update($payment_id, array('is_paid' => 1, 'status' => 'paid'));
        $cartitesm = new Model('customer_order');
        $items = $cartitesm->filter_index(array('payment_id' => $payment_id));
        if ($items != false) {
            foreach ($items as $key => $iv) {
                //set cart status hold to processing
                $customer_cart = new Model('customer_order');
                $customer_cart->update($iv['id'], array('status' => 'processing'));
                //remove purchased qty fro stock
                // $product = new Model('content');
                // $prod = $product->show($iv['item_id']);
                // $atarr['qty'] = $prod['qty']-$iv['qty'];
                // $product->update($iv['item_id'],$atarr);
                $atarr = null;
            }
            return true;
        } else {
            return false;
        }
    }
}
// function cancel_hold_items_old($email)
// {
//     if ($email != null) {
//         // $pmnt = new Model('customer_payment');
//         // $pmnt->update($payment_id,array('is_paid'=>0,'status'=>$status));
//         $cartitesm = new Model('customer_order');
//         $items = $cartitesm->filter_index(array('customer_email' => $email, 'status' => 'hold'));
//         if ($items != false) {
//             foreach ($items as $key => $iv) {
//                 //set cart status hold to processing
//                 $customer_cart = new Model('customer_order');
//                 $cancelled_time = date('Y-m-d H:i:s');
//                 $customer_cart->update($iv['id'], array('status' => 'cancelled', 'updated_at' => $cancelled_time));
//                 //remove purchased qty fro stock
//                 // $product = new Model('content');
//                 // $prod = $product->show($iv['item_id']);
//                 // $atarr['qty'] = $prod['qty']-$iv['qty'];
//                 // $product->update($iv['item_id'],$atarr);
//                 $atarr = null;
//             }
//             return true;
//         } else {
//             return false;
//         }
//     }
// }


function  cancel_hold_items($email,$new_status='cancelled')
{

    try {
        $db = new Dbobjects;
        // $db->tableName = "customer_order";
        // $db->insertData = array('status'=>'cart');
        // $db->create_sql();
        $pdo = $db->dbpdo();
        // $pdo = new PDO("mysql:host=localhost;dbname=pk_mobileapp", "root", "");
        // $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->beginTransaction();
        // $db->tableName = 'customer_order';
        $sql_select_hold = "select * from customer_order where status='hold' AND customer_email = '$email'";

        $stmt = $pdo->prepare($sql_select_hold);

        $stmt->execute();
        $holddata = $stmt->fetchAll();
        foreach ($holddata as $hd) {
            $sql_select_product_qty = "select id, qty from content where content.id={$hd['item_id']}";
            $stmt = $pdo->prepare($sql_select_product_qty);
            $stmt->execute();
            $prod = $stmt->fetch();
            // print_r($prod);
            $qty = $prod['qty'] + $hd['qty'];
            $sql_increase_stock = "update content set qty=$qty where content.id={$prod['id']}";
            $pdo->exec($sql_increase_stock);
            $sql_cancel_hold = "update customer_order set status='$new_status' where customer_order.id={$hd['id']} AND status='hold' AND customer_email = '$email'";
            $pdo->exec($sql_cancel_hold);
        }
        // if everything is fine end transaction
        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        return $e;
    }
}
