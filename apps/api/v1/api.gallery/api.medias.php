<?php 
  if (MY_DOMAIN!="localhost") {
    $link = "https://".MY_DOMAIN."/media/images/pages";
}
else{
    $link = "/".home."/media/images/pages/";
}
if(isset($_GET['media_file_id'])){
    if (filter_input(INPUT_GET, "media_file_id", FILTER_VALIDATE_INT)) {
        $id = $_GET['media_file_id'];
        $db = new Model('pk_media');
        $arr['id'] = $id;
        $glry = $db->filter_index($arr);
        if ($glry==false) {
            $msg['msg'] = "No media found";
            $respoonse = json_encode($msg);
            
            echo $respoonse;
            return;
        }
        $gallery_data['id'] = $glry[0]['id'];
        $gallery_data['media_file'] = $link."/".$glry[0]['media_file'];
        $gallery_data['is_app_banner'] = $glry[0]['is_app_banner'];
        $msg['msg'] = "success";
        $data['data'][] = $msg;
        $data['data'][] = $gallery_data;
        echo json_encode($data);
        return;
    }
    else{
        $msg['msg'] = "Invalid Id";
        $data['data'][] = $msg;
        $data['data'][] = null;
        echo json_encode($data);
        return;
    }
    return;
}
if(isset($_GET['filter_media'])){
    if ($_GET['filter_media']!="") {
        $str = sanitize_remove_tags($_GET['filter_media']);
        if ($str=="banner") {
            $arr['is_app_banner'] = 1;
        }
        $db = new Model('pk_media');
        $glry = $db->filter_index($arr,$ord="DESC",$limit="1000",$change_order_by_col="");
        if ($glry==false) {
            $msg['msg'] = "No media found";
            $data['data'][] = $msg;
            $data['data'][] = null;
            echo json_encode($data);
            return;
        }
        else{
            foreach ($glry as $key => $uv) {
                $gallery_data[] = array(
                    'id'=>$uv['id'],
                    'media_file'=>$link."/".$uv['media_file'],
                    'is_app_banner'=>$uv['is_app_banner']
                );
            }
            $msg['msg'] = "success";
            $data['data'][] = $msg;
            $data['data'][] = $gallery_data;
            echo json_encode($data);
            return;
        }
    }
    else{
        $msg['msg'] = "Invalid Key";
        $data['data'][] = $msg;
        $data['data'][] = null;
        echo json_encode($data);
        return;
    }
    return;
}
else{
    $db = new Model('pk_media');
    $users = $db->index($ord="DESC",$limit="1000",$change_order_by_col="");
    if ($users==false) {
        $msg['msg'] = "No media found";
        $data['data'][] = $msg;
        $data['data'][] = null;
        echo json_encode($data);
        return;
    }
    else{
        foreach ($users as $key => $uv) {
            $users_data[] = array(
                'id'=>$uv['id'],
                'media_file'=>$link."/".$uv['media_file'],
                'is_app_banner'=>$uv['is_app_banner']
            );
        }
        $msg['msg'] = "success";
        $data['data'][] = $msg;
        $data['data'][] = $users_data;
        echo json_encode($data);
        return;
    }
}