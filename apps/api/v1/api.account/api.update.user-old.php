<?php
if(isset($_POST['user_id'])){
    if (filter_input(INPUT_POST, "user_id", FILTER_VALIDATE_INT)) {
        $id = $_POST['user_id'];
        $db = new Model('pk_user');
        $arr['id'] = $id;
        $user = $db->filter_index($arr);
        if ($user==false) {
            $data['msg'] = "No user found";
            $data['data'] = null;
            echo json_encode($data);
            die();
        }
        else {
            $user = $db->show($id);
            if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
                $fl = $_FILES['image'];
                $file_with_ext = $fl['name'];
                $file_type = mime_content_type($fl['tmp_name']);
                $only_file_name = filter_name($file_with_ext);
                $only_file_name =  $only_file_name."_{$id}_{$user['username']}_".random_int(1000,9999);
                $target_dir = RPATH ."/media/images/profiles/";
                $file_ext_arr = explode(".",$file_with_ext);
                $ext = end($file_ext_arr);
                $target_file = $target_dir ."{$only_file_name}.".$ext;
                if ($file_type =="image/png" || $file_type =="image/jpg" || $file_type =="image/jpeg") {
                    if (move_uploaded_file($fl['tmp_name'], $target_file)) {
                        $_SESSION['msg'][] = "File $file_with_ext has been uploaded";
                        $filename = $only_file_name.".".$ext;
                        $arr['image'] = $filename;
                        if($user['image']!=""){
                            if(file_exists($target_dir."/".$user['image'])){
                                unlink($target_dir."/".$user['image']);
                            }
                        }
                        
                      }
                }
                else{
                    $imgfiletype =  mime_content_type($fl['tmp_name']);
                    $data['msg'] = "Invalid Image File $imgfiletype";
                    $data['data'] = null;
                    echo json_encode($data);
                    die();
                }
               
            }
            if (isset($_POST['name'])) {
                $arr['name'] = sanitize_remove_tags($_POST['name']);
             }
            $db->update($id,$arr);
            $user = $db->show($id);
            $app_arr_retrun['id'] = $user['id'];
            $app_arr_retrun['name'] = $user['name'];
            $app_arr_retrun['email'] = $user['email'];
            $app_arr_retrun['username'] = $user['username'];
            if($user['image']!=""){$app_arr_retrun['image']=(file_exists(RPATH."/media/images/profiles/".$user['image'])==true)?$user['image']:null;}
            else{$app_arr_retrun['image']=null;}
            $app_arr_retrun['mobile'] = $user['mobile'];
            $app_arr_retrun['national_id'] = $user['national_id'];
            $app_arr_retrun['app_login_token'] = $user['app_login_token'];
            $app_arr_retrun['role'] = $user['role'];
            $app_arr_retrun['status'] = $user['status'];
            $app_arr_retrun['is_premium'] = ($user['access_level']>=1 && $user['access_level']<=5)?true:false;
            $data['msg'] = "success";
            $data['data'] = $app_arr_retrun;
            echo json_encode($data);
            return;
        }
    }
    else{
        $data['msg'] = "Invalid User";
        $data['data'] = null;
        echo json_encode($data);
        die();
    }
    die();
}