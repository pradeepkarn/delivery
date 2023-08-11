<?php 
function add_to_cart_api($id,$action="add_to_cart",$qty=1)
{
    $live_stock = check_stock_api($id);
    // echo js_alert($live_stock);
    //$_SESSION['cart'] = null;
    if (isset($_SESSION['cart'][$id])) {
        if ($action=='buy_now') {
            return;
        }
        else{
            if ($live_stock>=1) {
                $_SESSION['cart'][$id]['id'] = $id;
                $_SESSION['cart'][$id]['qty'] += $qty;
                return true;
            }
            else{
                return false;
            }
            
        }
        
    }
    else{
        if ($live_stock>=1) {
            $_SESSION['cart'][$id]['id'] = $id;
            $_SESSION['cart'][$id]['qty'] = $qty;
            return true;
        }
        else{
            return false;
        }
        
    }
}
function remove_from_cart_api($id){
    if (isset($_SESSION['cart'][$id])) {
        if ($_SESSION['cart'][$id]['qty']>1) {
            $_SESSION['cart'][$id]['id'] = $id;
            $_SESSION['cart'][$id]['qty'] -= 1;
        }
        else{
            unset($_SESSION['cart'][$id]);
        }
    }
    if (count($_SESSION['cart'])==0) {
        unset($_SESSION['cart']);
    }
}
function check_stock_api($prodid){
    $item = new Model('content');
    $prod = $item->show($prodid);
    // $old_hold_obj = (new Model('customer_order'))->filter_index(array('item_id'=>$prod['id'],'status'=>'hold'));
    // $old_hold_db_qty = 0;
    // if ($old_hold_obj!=false) {
    //     foreach ($old_hold_obj as $key => $hldqty) {
    //     $hldqtyar[] = $hldqty['qty'];
    //     }
    //     $old_hold_db_qty = array_sum($hldqtyar);
    // }
    // else{
    //     $old_hold_db_qty = 0;
    // }
    // $prod_qty_minus_hold_qty = $prod['qty'] - $old_hold_db_qty;
    $prod_qty = $prod['qty'];
    return $prod_qty;
}

function get_my_cart_api($token)
{
    $user = new Model('pk_user');
    $arr['app_login_token'] = $token;
    $user = $user->filter_index($arr);
    if (!count($user)>0) {
        return array();
    }
    $mycart = array();
    $cartObj = new Model('customer_order');
    $get_cart['customer_email'] = $user[0]['email'];
    $get_cart['status'] = 'cart';
    $cart = $cartObj->filter_index($get_cart);
    // myprint($cart);
    foreach ($cart as $ck => $cv) {
        $clrloop = array();
        $prod = getData('content',$cv['item_id']);
        $rel_prods = array();
        if ($prod['json_obj']!=null) {
            $jsn = json_decode($prod['json_obj']);
            if (isset($jsn->related_products)) {
                $rel_prods = rel_prods($jsn->related_products) ;
            }
        }    
        // $mycart['carts'][] = array(
        //     'cart_id'=> $cv['id'],
        //     'payment_id'=> $cv['payment_id'],
        //     'item_id'=> $prod['id'],
        //     'item_name'=> $prod['title'],
        //     'bulk_qty'=> $prod['bulk_qty'],
        //     'item_image'=> "/media/images/pages/{$prod['banner']}",
        //     'item_price'=> $cv['price'],
        //     'item_cart_qty'=> $cv['qty'],
        //     'is_paid'=> $cv['is_paid'],
        //     "color_list"=> json_decode($cv['color_list']),
        //     'remark'=> $cv['remark'],
        //     "shipping_status"=> $cv['shipping_status'],
        //     "shipping_id"=> $cv['shipping_id'],
        //     "created_at"=> $cv['created_at'],
        //     "updated_at"=> $cv['updated_at']
        // );
        foreach (json_decode($cv['color_list']) as $kl => $clv) {
            $mycart['carts'][] = array(
                'cart_id'=> $cv['id'],
                'payment_id'=> $cv['payment_id'],
                'item_id'=> $prod['id'],
                'item_name'=> $prod['title'],
                'bulk_qty'=> $prod['bulk_qty'],
                // 'item_image'=> "/media/images/pages/{$prod['banner']}",
                'item_image'=> img_by_color($prod['id'],$clv->color)!=null?img_by_color($prod['id'],$clv->color):"/media/images/pages/{$prod['banner']}",
                'item_price'=> $cv['price'],
                'item_cart_qty'=> $cv['qty'],
                'is_paid'=> $cv['is_paid'],
                'color'=>$clv->color,
                'color_cart_qty'=>$clv->qty,
                'remark'=> $cv['remark'],
                "shipping_status"=> $cv['shipping_status'],
                "shipping_id"=> $cv['shipping_id'],
                "created_at"=> $cv['created_at'],
                "updated_at"=> $cv['updated_at']
            );
        }
        $mycart['rel_prods'][] = array(
            "related_to"=> $prod['id'],
            "related_products"=> $rel_prods
        );
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

function rel_prods($ids)
{
    $rel_prods = array();
    if ($ids) {
        foreach ($ids as $rpid) {
            if (getData('content',$rpid)) {
                $rp = getData('content',$rpid);
                $rel_prods[] = array(
                    'id'=>$rp['id'],
                    'title'=>$rp['title'],
                    'currency'=>$rp['currency'],
                    'price'=>$rp['price'],
                    'brand'=>$rp['brand'],
                    'color'=>$rp['color'],
                    'qty'=>$rp['qty'],
                    'bulk_qty'=>$rp['bulk_qty'],
                    'info'=>$rp['content_info'],
                    'description'=>pk_excerpt(sanitize_remove_tags($rp['content'])),
                    'image' => "/media/images/pages/".$rp['banner'],
                    'category_id' => $rp['parent_id'],
                    'category' => ($rp['parent_id']==0)?'Uncategoriesed':getData('content',$rp['parent_id'])['title']
                );
            }
        }
        
    }
    return $rel_prods;
}

function update_my_cart($carts)
{
    $cartObj = new Model('customer_order');
    foreach ($carts as $cv) {
        $cartdata = $cartObj->filter_index(array('status'=>'cart','id'=>$cv->id));
        if (count($cartdata)>0) {
            if ($cv->qty>0) {
                $colr_list = array();
                foreach ($cv->color_list as $clv) {
                    $colr_list[] = array(
                        'color'=>$clv->color,
                        'qty'=>$clv->qty,
                    );
                }
                $colr_list_jsn = json_encode($colr_list);
                $cartObj->update($cv->id,array('qty'=> $cv->qty,'color_list'=>$colr_list_jsn));
            }else if($cv->qty==0){
                $cartObj->destroy($cv->id);
            }
        }
    }
}

function order_by_status($token, $status = 'cart', $ord = 'DESC')
{
    $user = new Model('pk_user');
    $arr['app_login_token'] = $token;
    $user = $user->filter_index($arr);
    if (!count($user)>0) {
        return array();
    }
    $mycart = array();
    $cartObj = new Model('customer_order');
    $get_cart['customer_email'] = $user[0]['email'];
    $get_cart['status'] = $status;
    $cart = $cartObj->filter_index($get_cart,$ord);
    foreach ($cart as $ck => $cv) {
        $clrloop = array();
        $prod = getData('content',$cv['item_id']);
        $rel_prods = array();
        if ($prod['json_obj']!=null) {
            $jsn = json_decode($prod['json_obj']);
            if (isset($jsn->related_products)) {
                $rel_prods = rel_prods($jsn->related_products) ;
            }
        }    
        foreach (json_decode($cv['color_list']) as $kl => $clv) {
            $mycart['carts'][] = array(
                'cart_id'=> $cv['id'],
                'payment_id'=> $cv['payment_id'],
                'item_id'=> $prod['id'],
                'item_name'=> $prod['title'],
                'bulk_qty'=> $prod['bulk_qty'],
                'item_image'=> img_by_color($prod['id'],$clv->color)!=null?img_by_color($prod['id'],$clv->color):"/media/images/pages/{$prod['banner']}",
                'item_price'=> $cv['price'],
                'item_cart_qty'=> $cv['qty'],
                'is_paid'=> $cv['is_paid'],
                'color'=>$clv->color,
                'color_cart_qty'=>$clv->qty,
                'remark'=> $cv['remark'],
                "shipping_status"=> $cv['shipping_status'],
                "shipping_id"=> $cv['shipping_id'],
                "created_at"=> $cv['created_at'],
                "updated_at"=> $cv['updated_at']
            );
        }
        $mycart['rel_prods'][] = array(
            "related_to"=> $prod['id'],
            "related_products"=> $rel_prods
        );
    }
    return $mycart;
}