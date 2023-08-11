<?php
$url = explode("/", $_SERVER["QUERY_STRING"]);
$path = $_SERVER["QUERY_STRING"];
$GLOBALS['url_last_param'] = end($url);
$GLOBALS['url_2nd_last_param'] = prev($url);
$plugin_dir = "plans";
$content_group = "plan";
$home = home;
import("apps/plugins/{$plugin_dir}/function.php");

if ("{$url[0]}/{$url[1]}" == "admin/$plugin_dir") {
    switch ($path) {
        case "admin/$plugin_dir":
            // if (isset($_POST['add_new_content'])) {
            //     $pageid = addContent($type=$content_group);
            //     if($pageid == false){
            //         echo js_alert("Duplicate slug, Change slug");
            //     }
            // }
            import("apps/plugins/{$plugin_dir}/show_contents.php");
            break;

            // case "admin/{$plugin_dir}/edit/{$GLOBALS['url_last_param']}":
            //     if (isset($_POST['update_banner'])) {
            //         $contentid = $_POST['update_banner_page_id'];
            //         $banner=$_FILES['banner'];
            //         $banner_name = time().uniqid("_banner_").USER['id'];
            //         change_my_banner($contentid=$contentid,$banner=$banner,$banner_name=$banner_name);
            //     }
            //     import("apps/plugins/{$plugin_dir}/edit_content.php");
            //     break;
            // case "admin/{$plugin_dir}/edit/{$GLOBALS['url_2nd_last_param']}/update":
            //     if (isset($_POST['page_id']) && isset($_POST['update_page'])) {
            //         if(updatePage() === true){
            //             echo js_alert("Update");
            //             echo js("location.reload();");
            //         }
            //     }
            //     break;
        case "admin/{$plugin_dir}/delete/{$GLOBALS['url_last_param']}":
            if (is_superuser() == false) {
                header("Location:/" . home . "/admin/{$plugin_dir}");
            } else {
                if (delContent($id = $GLOBALS['url_last_param']) != false) {
                    echo js_alert("Deleted Successfully");
                    header("Location:/" . home . "/admin/{$plugin_dir}");
                } else {
                    echo js_alert("Invalid activity");
                    header("Location:/" . home . "/admin/{$plugin_dir}");
                }
            }
            break;
        default:
            if (count($url) >= 3) {
                if ("{$url[1]}/{$url['2']}" == "{$plugin_dir}/add-more-img") {
                    if (isset($_FILES['add_more_img']) && $_FILES['add_more_img']['name'] != "") {
                        import("apps/controllers/ContentDetailsCtrl.php");
                        $listObj = new ContentDetailsCtrl;
                        if ($listObj->add_more_img() == true) {
                            echo js_alert('Uploaded');
                            msg_ssn("msg");
                            echo js('location.reload();');
                            return;
                        } else {
                            echo js_alert('Not updated');
                            msg_ssn("msg");
                            return;
                        }
                        msg_ssn("msg");
                        return;
                    }
                    break;
                }
                if ($url[2] == 'add-new-item') {
                    import("apps/plugins/{$plugin_dir}/add-new-item.php");
                    return;
                }
                if ($url[2] == 'edit') {
                    if (isset($_GET['planid']) && intval($_GET['planid'])) {
                        import("apps/plugins/{$plugin_dir}/edit-item.php");
                        exit;
                    } else {
                        header("Location:/$home/admin/$plugin_dir");
                        exit;
                    }
                    return;
                }
                if ($url[2] == 'add-new-item-ajax') {
                    if ($_POST['plan_name'] == "") {
                        echo js_alert('Empty name is not allowed');
                        return;
                    }
                    if (!is_numeric($_POST['plan_price'])) {
                        echo js_alert('Plan price must be numeric');
                        return;
                    }
                    if (!is_numeric($_POST['plan_duration']) || !isset($_POST['plan_duration'])) {
                        echo js_alert('Please provide plan duration, and it must be numeric');
                        return;
                    }
                    if (isset($_POST['key']) && isset($_POST['value'])) {
                        $k = $_POST['key'];
                        $v = $_POST['value'];
                        $features = [];
                        for ($i = 0; $i < count($k); $i++) {
                            $features[] = array(
                                $k[$i] => $v[$i]
                            );
                        }
                        $arr['data'] = json_encode($features);
                    } else {
                        $arr['data'] = json_encode(array());
                    }
                    $arr['name'] = $_POST['plan_name'];
                    $arr['details'] = $_POST['plan_details'];
                    $arr['duration_days'] = $_POST['plan_duration'];
                    $arr['price'] = $_POST['plan_price'];
                    $arr['created_at'] = date('Y-m-d H:i:s');
                    // echo json_encode($features);
                    $planid = (new Model('plans'))->store($arr);
                    if (filter_var($planid, FILTER_VALIDATE_INT)) {
                        echo js_alert('added');
                        $home = home;
                        echo js("location.href='/$home/admin/$plugin_dir';");
                    }
                    return;

                    return;
                }
                if ($url[2] == 'update-item-ajax') {
                    if (!isset($_POST['plan_id']) || !intval($_POST['plan_id'])) {
                        echo js_alert('Invalid plan id');
                        return;
                    }
                    if ($_POST['plan_name'] == "") {
                        echo js_alert('Empty name is not allowed');
                        return;
                    }
                    if (!is_numeric($_POST['plan_price'])) {
                        echo js_alert('Plan price must be numeric');
                        return;
                    }
                    if (!is_numeric($_POST['plan_duration']) || !isset($_POST['plan_duration'])) {
                        echo js_alert('Please provide plan duration, and it must be numeric');
                        return;
                    }
                    if (isset($_POST['key']) && isset($_POST['value'])) {
                        $k = $_POST['key'];
                        $v = $_POST['value'];
                        $features = [];
                        for ($i = 0; $i < count($k); $i++) {
                            $features[] = array(
                                $k[$i] => $v[$i]
                            );
                        }
                        $arr['data'] = json_encode($features);
                    } else {
                        $arr['data'] = json_encode(array());
                    }
                    $planid = intval($_POST['plan_id']);
                    $arr['name'] = $_POST['plan_name'];
                    $arr['details'] = $_POST['plan_details'];
                    $arr['price'] = $_POST['plan_price'];
                    $arr['duration_days'] = $_POST['plan_duration'];
                    $arr['updated_at'] = date('Y-m-d H:i:s');
                    // echo json_encode($features);
                    $planupdate = (new Model('plans'))->update($planid, $arr);
                    if ($planupdate) {
                        echo js_alert('Updated');
                        $home = home;
                        echo RELOAD;
                        exit;
                    } else {
                        echo js_alert('Not Updated');
                        exit;
                    }
                    return;
                }
                // move to trash
                if ($url[2] == 'move-to-trash') {
                    if (!isset($_GET['planid']) || !intval($_GET['planid'])) {
                        header("Location:/$home/admin/$plugin_dir");
                        return;
                    }
                    $planid = intval($_GET['planid']);
                    $arr['is_active'] = 0;
                    $planupdate = (new Model('plans'))->update($planid, ['is_active' => 0]);
                    if ($planupdate) {
                        header("Location:/$home/admin/$plugin_dir");
                        exit;
                    } else {
                        header("Location:/$home/admin/$plugin_dir");
                        exit;
                    }
                    return;
                }
                if ("{$url[1]}/{$url['2']}" == "{$plugin_dir}/add-more-detail") {
                    if (isset($_POST['add_more_detail']) && isset($_POST['add_more_heading']) && isset($_POST['content_id']) && isset($_POST['content_group'])) {
                        import("apps/controllers/ContentDetailsCtrl.php");
                        $listObj = new ContentDetailsCtrl;
                        if ($listObj->add_more_detail() == true) {
                            echo js_alert('Added');
                            echo js('location.reload();');
                            return;
                        } else {
                            echo js_alert('Not updated');
                            return;
                        }
                        msg_ssn("msg");
                        return;
                    }
                    break;
                }
                if ("{$url[1]}/{$url['2']}" == "{$plugin_dir}/delete-content-details") {
                    if (isset($_POST['content_details_delete_id'])) {
                        import("apps/controllers/ContentDetailsCtrl.php");
                        $listObj = new ContentDetailsCtrl;
                        if ($listObj->destroy($_POST['content_details_delete_id']) == true) {
                            echo js('location.reload();');
                        } else {
                            echo js_alert('Not Deleted');
                        }
                        msg_ssn("msg");
                        return;
                    }
                    break;
                }
                if ("{$url[1]}/{$url['2']}" == "{$plugin_dir}/update-content-details-ajax") {
                    if (isset($_POST['content_detail_id'])) {
                        import("apps/controllers/ContentDetailsCtrl.php");
                        $listObj = new ContentDetailsCtrl;
                        if ($listObj->update_more_detail($_POST['content_detail_id']) == true) {
                            echo js('location.reload();');
                        } else {
                            echo js_alert('Not Deleted');
                        }
                        msg_ssn("msg");
                        return;
                    }
                    break;
                }
                if ("{$url[1]}/{$url['2']}" == "{$plugin_dir}/color-delete-ajax") {
                    if (isset($_POST['pid'])) {
                        if (removeColorList($_POST['pid'], $_POST['color_delete']) == true) {
                            echo js('location.reload();');
                        } else {
                            echo js_alert('Not Deleted');
                        }
                        msg_ssn("msg");
                        return;
                    }
                    break;
                }
            }
            // if ($url[1]=='delete') {
            //     if (is_superuser()===false) {
            //         header("Location:/".home);
            //       }
            //       else{
            //         if(delContent($id=$GLOBALS['url_last_param']) != false){
            //             // echo js_alert("Deleted Successfully");
            //             if ($GLOBALS['url_2nd_last_param']!='page') {
            //                 header("Location:/".home."/{$plugin_dir}/{$GLOBALS['url_2nd_last_param']}");
            //                 // echo js('location.href=/'.home.'/'.$GLOBALS['url_2nd_last_param']);
            //             }
            //             else{
            //                 header("Location:/".home."/{$plugin_dir}");
            //             }

            //         }
            //         else{
            //             echo js_alert("Invalid activity");
            //             header("Location:/".home."/{$plugin_dir}");
            //         }
            //       }
            //     break;
            // }
            import("apps/view/404.php");
            break;
    }
}
