<?php
import("apps/api/v1/api.cart/fn.cart.php");
import("apps/api/v1/api.cart/fn.checkout.php");

$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST') {
    $dv = json_decode(file_get_contents('php://input'));
    // Do something with the data
} elseif ($method === 'GET') {
    die('Wrong method to send data');
}

if(isset($_SESSION['cart'])){
    unset($_SESSION['cart']);
}
$cart_data = null;
    $email = $dv->email;
    $pid = $dv->pid;
    if (isset($dv->colors)) {
        $product = array();
        $qty=0;
        // print_r($dv->colors);
        foreach ($dv->colors as $clk => $clv) {
                $color_arr[] = array('color'=>$clv->color,'qty'=>$clv->items);
                $qty = $clv->items;
                add_to_cart_api($pid,'add_to_cart',$qty);
        }
    }
    // print_r($color_arr);
    if(isset($_SESSION['cart'])){
        $cart_data = save_cart_to_db_api($email,$_SESSION['cart'],$color_arr);
        unset($_SESSION['cart']);
        $res['msg'] = "Added to cart";
        $res['data'] = $cart_data;
        echo json_encode($res);
        return;
    }else{
        $res['msg'] = "No data found";
        $res['data'] = null;
        return;
    }
$res['data'] = $cart_data;
echo json_encode($res);
    // If product is without group

// return;

// if (isset($_POST['pid'])) {
//     add_to_cart_api($id=$_POST['pid'],$action="add_to_cart");
//     if (isset($_SESSION['cart'])) {
//         echo json_encode($_SESSION['cart']);
//     }
// }