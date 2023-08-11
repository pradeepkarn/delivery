<?php 
    $db = new Model('content');
    $sliders = $db->filter_index(array('content_group'=>'slider','content_type'=>'main'),$ord="DESC",$limit="1000",$change_order_by_col="id");
    if ($sliders==false) {
        $data['msg'] = "No banner";
        $data['data'] = null;
        echo json_encode($data);
        die();
    }
    else{
        foreach ($sliders as $key => $uv) { 
            $user = getData('pk_user',$uv['created_by']);          
            $slider_data[] = array(
                'id'=>$uv['id'],
                'title'=>$uv['title'],
                'description'=>pk_excerpt(sanitize_remove_tags($uv['content'])),
                'image' => "/media/images/pages/".$uv['banner'],
                'category' => ($uv['parent_id']==0)?'Main':getData('content',$uv['parent_id'])['title'],
                'status' => $uv['status']
            );
        }
        $data['msg'] = "success";
        $data['data'] = $slider_data;
        echo json_encode($data);
        return;
    }