<?php
$url = explode("/", $_SERVER["QUERY_STRING"]);
$path = $_SERVER["QUERY_STRING"];
$GLOBALS['url_last_param'] = end($url);
$GLOBALS['url_2nd_last_param'] = prev($url);
$plugin_dir = "orders";
$pass = PASS;
import("apps/plugins/{$plugin_dir}/function.php");

if ("{$url[0]}/{$url[1]}" == "admin/$plugin_dir") {
    switch ($path) {
        case "admin/$plugin_dir":
            import("apps/plugins/{$plugin_dir}/order-dashboard.php");
            break;
        default:
            if (count($url) >= 3) {
                if ($url[2] == 'order-list') {
                    if (!$pass) {
                        header("Location:/" . home . "/admin");
                    } else {
                        if (isset($_GET['status'])) {
                            import("apps/plugins/$plugin_dir/order-list-by-status.php");
                            return;
                        } else {
                            header("Location:/" . home . "/admin/orders");
                            return;
                        }
                    }
                    return;
                }
                if ($url[2] == 'update-delivery-date-ajax') {
                    // print_r($_POST);
                    // return;
                    if (!$pass) {
                        echo js_alert('Invalid access');
                        return;
                    } else {
                        if (isset($_POST['delivery_date'])) {
                            $db = new Dbobjects;
                            $db->tableName = 'customer_payment';
                            $db->pk($_POST['order_id']);
                            $db->insertData['delivery_date'] = $_POST['delivery_date'];
                            $db->insertData['last_action_on'] = date('Y-m-d H:i:s');
                            if (isset($_POST['salesperson_id']) && intval($_POST['salesperson_id'])) {
                                $db->insertData['salesperson_id'] = $_POST['salesperson_id'];
                                $db->insertData['deliver_via'] = 'salesman';
                            }
                            $db->update();
                            echo RELOAD;
                        }
                    }
                    return;
                }
                if ($url[2] == 'change-order-status-update-ajax') {
                    if (!$pass) {
                        echo js_alert('Invalid access');
                        return;
                    } else {
                        if (isset($_POST['order_status'])) {
                      
                               if ($_POST['order_status']=="cancelled") {
                                if ($_POST['cancel_info']=="") {
                                    echo js_alert('Please specify the cancellation reason');
                                    echo RELOAD;
                                    return;
                                }
                               }
                            
                            $db = new Dbobjects;
                            $db->tableName = 'customer_payment';
                            $db->pk($_POST['order_id']);
                            $db->insertData['order_status'] = $_POST['order_status'];
                            $db->insertData['cancel_info'] = $_POST['cancel_info'];
                            $db->insertData['last_action_on'] = date('Y-m-d H:i:s');
                            $db->update();
                            echo RELOAD;
                        }
                    }
                    return;
                }
                if ($url[2] == 'forward-to-warehouse-ajax') {
                   
                    if (!$pass) {
                        echo js_alert('Invalid access');
                        return;
                    } else {
                        if (isset($_POST['forward_to_wh_oid'])) {
                      
                            //    if ($_POST['order_status']=="cancelled") {
                            //     if ($_POST['cancel_info']=="") {
                            //         echo js_alert('Please specify the cancellation reason');
                            //         echo RELOAD;
                            //         return;
                            //     }
                            //    }
                            if ($_POST['whmanager_id']==0) {
                                echo js_alert('Please select a warehouse');
                                return;
                            }
                            $db = new Dbobjects;
                            $db->tableName = 'customer_payment';
                            $db->pk($_POST['forward_to_wh_oid']);
                            $db->insertData['wh_status'] = "new";
                            $db->insertData['whmanager_id'] = $_POST['whmanager_id'];
                            // $db->insertData['cancel_info'] = $_POST['cancel_info'];
                            $db->insertData['last_action_on'] = date('Y-m-d H:i:s');
                            $db->update();
                            echo js_alert('Forwarded');
                            echo RELOAD;
                        }
                    }
                    return;
                }
                if ($url[2] == 'change-cart-status-update') {
                    if (!$pass) {
                        echo js_alert('Invalid access');
                        return;
                    } else {
                        if (isset($_POST['status'])) {
                            $db = new Dbobjects;
                            $db->tableName = 'customer_order';
                            $db->pk($_POST['cart_id']);
                            $db->insertData['status'] = $_POST['status'];
                            $db->update();
                            echo RELOAD;
                        }
                    }
                    return;
                }
                if ($url[2] == 'order-details') {
                    if(!$pass){
                        header("Location:/".home."/admin");
                      } else {
                        if (isset($_GET['tid'])) {
                            import("apps/plugins/$plugin_dir/order-details-dashboard.php");
                            return;
                        } else {
                            header("Location:/" . home . "/admin/orders");
                            return;
                        }
                    }
                    return;
                }
                if ($url[2] == 'print-invoice') {
                    if(!$pass){
                        header("Location:/".home."/admin");
                      } else {
                        if (isset($_GET['tid'])) {
                            // import("apps/plugins/$plugin_dir/print-invoice-index.php");
                            import("apps/plugins/$plugin_dir/components/invoice/draw-inv.php");
                            return;
                        } else {
                            header("Location:/" . home . "/admin/orders");
                            return;
                        }
                    }
                    return;
                }
            }
            import("apps/view/404.php");
            break;
    }
}
