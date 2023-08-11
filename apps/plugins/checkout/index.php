<?php
$url = explode("/", $_SERVER["QUERY_STRING"]);
$path = $_SERVER["QUERY_STRING"];
$GLOBALS['url_last_param'] = end($url);
$GLOBALS['url_2nd_last_param'] = prev($url);
$plugin_dir = "checkout";
import("apps/plugins/{$plugin_dir}/function.php");
$home = home;
if ($url[0] == "{$plugin_dir}") {
switch ($path) {
    case "{$plugin_dir}":
        import("apps/plugins/{$plugin_dir}/checkout_details.php");
        break;    
    default:
        if ($url[1]=='ajax-place-order') {
       
        if (!isset($_POST["first_name"])) {
            echo js_alert("Please enter First name");
            die();
        }
        if (!isset($_POST["last_name"])) {
            echo js_alert("Please enter Last name");
            die();
        }
        if (!isset($_POST["my_location"])) {
            echo js_alert("Please select Country name");
            die();
        }
        if (!isset($_POST["country_name"])) {
            echo js_alert("Please select Country name");
            die();
        }
        if (!isset($_POST["state_name"])) {
            echo js_alert("Please enter Sate/County");
            die();
        }
        if (!isset($_POST["city_name"])) {
            echo js_alert("Please enter City name");
            die();
        }
        if (!filter_input(INPUT_POST,"customer_email",FILTER_VALIDATE_EMAIL)) {
            echo js_alert("Please Check email");
            die();
        }
        if (!isset($_POST["isd_code"])) {
            echo js_alert("Please select Country dial code");
            die();
        }
        if (!isset($_POST["mobile_number"])) {
            echo js_alert("Please enter Mobile number");
            die();
        }
        if (!isset($_POST["zip_code"])) {
            echo js_alert("Please enter zip code");
            die();
        }
        if ($_POST["zip_code"]=="") {
            echo js_alert("Please valid zip code");
            die();
        }
        if (!isset($_POST["payment_method"])) {
            echo js_alert("Please select payment_method");
            die();
        }
        $_SESSION['customer_email'] = $_POST['customer_email'];
        if (authenticate()==false) {
            $user = signup_before_checkout($email=$_POST['customer_email'],$pass=$_POST['pass'],$cnfpass=$_POST['cnf_pass']);
            if ($user!=false) {
                if (isset($user['id'])) {
                    $arruserupdate['first_name'] = sanitize_remove_tags($_POST["first_name"]);
                    $arruserupdate['last_name'] = sanitize_remove_tags($_POST["last_name"]);
                    $arruserupdate['address'] = sanitize_remove_tags($_POST["my_location"]);
                    $arruserupdate['zipcode'] = sanitize_remove_tags($_POST["zip_code"]);
                    $arruserupdate['isd_code'] = sanitize_remove_tags($_POST["isd_code"]);
                    $check_mobile = (new Model('pk_user'))->exists(array('mobile'=>sanitize_remove_tags($_POST["mobile_number"])));
                    if ($check_mobile==false) {
                        $arruserupdate['mobile'] = sanitize_remove_tags($_POST["mobile_number"]);
                    }
                    $arruserupdate['country'] = sanitize_remove_tags($_POST["country_name"]);
                    $arruserupdate['state'] = sanitize_remove_tags($_POST["state_name"]);
                    $arruserupdate['city'] = sanitize_remove_tags($_POST["city_name"]);
                    try {
                        (new Model('pk_user'))->update($user['id'],$arruserupdate);
                        echo js_alert('Sign up success');
                        echo js('location.reload();');
                        msg_ssn('msg');
                    } catch (\Throwable $th) {
                        msg_ssn('msg');
                    }
                }
                msg_ssn('msg');
                return;
            }
            else{
                msg_ssn('msg');
                return;
            }
            die();
        }        
        //insert cart item to database linked with email
        if(cart_session_to_db($_POST['customer_email'])==true){
            hold_customer_cart($_POST['customer_email']);
            create_payment_obj($_POST['customer_email']);
            echo js("location.href='/$home/$plugin_dir/hold-items';");
            die();
        }
        die();
        }
        if ($url[1]=='hold-items') {
            if (isset($_SESSION['customer_email'])) {
              if (isset($_SESSION['payment_id'])) {
                  $pmnt = new Model('customer_payment');
                  $pmnt->show($_SESSION['payment_id']);
                  $pmt = $pmnt->show($_SESSION['payment_id']);
                  if($pmt!=false){
                      if($pmt['is_paid']==1){
                          echo js("location.href='/$home/$plugin_dir/congratulations';");
                          die();
                      }
                  }
               }
              import("apps/plugins/{$plugin_dir}/hold_items.php");
              return;
            }
            else{
  
            }
              
          break;
          }

        if ($url[1]=='proceed-to-confirm') {
                if (isset($_SESSION['payment_id'])) {
                   $pmnt = new Model('customer_payment');
                   $pmnt->update($_SESSION['payment_id'],array('is_paid'=>1,'status'=>'paid'));
                   $cartitesm = new Model('customer_order');
                   $items = $cartitesm->filter_index(array('payment_id'=>$_SESSION['payment_id']));
                   if ($items!=false) {
                   foreach ($items as $key => $iv) {
                    //set cart status hold to processing
                    $customer_cart = new Model('customer_order');
                    $customer_cart->update($iv['id'],array('status'=>'processing'));
                    //remove purchased qty fro stock
                    $product = new Model('content');
                    $prod = $product->show($iv['item_id']);
                    $atarr['qty'] = $prod['qty']-$iv['qty'];
                    $product->update($iv['item_id'],$atarr);
                    $atarr= null;
                   }
                }
                echo js_alert("Confirmed");
                echo js("location.href='/$home/$plugin_dir/congratulations';");
                die();
                return;
                }
        break;
        }

        //cancel
        if ($url[1]=='proceed-to-cancel') {
            if (isset($_SESSION['payment_id'])) {
               $pmnt = new Model('customer_payment');
               $pmnt->update($_SESSION['payment_id'],array('is_paid'=>0,'status'=>'cancelled'));
               $cartitesm = new Model('customer_order');
               $items = $cartitesm->filter_index(array('payment_id'=>$_SESSION['payment_id']));
               if ($items!=false) {
               foreach ($items as $key => $iv) {
                //set cart status hold to processing
                $customer_cart = new Model('customer_order');
                $customer_cart->update($iv['id'],array('status'=>'cancelled'));
                //remove purchased qty fro stock
                // $product = new Model('content');
                // $prod = $product->show($iv['item_id']);
                // $atarr['qty'] = $prod['qty']-$iv['qty'];
                // $product->update($iv['item_id'],$atarr);
                // $atarr= null;
               }
            }
            echo js_alert("Cancelled");
            echo js("location.href='/$home';");
            die();
            return;
            }
    break;
    }
    
        if ($url[1]=='congratulations') {
            import("apps/plugins/{$plugin_dir}/congratulations.php");
            return;
        }
    die();
    }
}
