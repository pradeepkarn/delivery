<?php 
if(isset($_GET['cat_id'])){
    if (filter_input(INPUT_GET, "cat_id", FILTER_VALIDATE_INT)) {
        $id = $_GET['cat_id'];
        $db = new Model('content');
        $arr['id'] = $id;
        $arr['content_group'] = "listing_category";
        $listings = $db->filter_index($arr);
        if ($listings==false) {
            $data['msg'] = "Not found";
            $data['data'] = null;
            echo json_encode($data);
            die();
        }
        $listing_data['id'] = $listings[0]['id'];
        $listing_data['title'] = $listings[0]['title'];
        $listing_data['info'] = $listings[0]['content_info'];
        $listing_data['description'] = $listings[0]['content'];
        $listing_data['image'] = "/media/images/categories/".$listings[0]['banner'];
        $listing_data['category']  = ($listings[0]['parent_id']==0)?'general':getData('content',$listings[0]['parent_id'])['title'];
        $listing_data['status'] = $listings[0]['status'];
        $data['msg'] = "success";
        $data['data'] = $listing_data;
        echo json_encode($data);
        return;
    }
    else{
        $data['msg'] = "Invalid Id";
        $data['data'] = null;
        echo json_encode($data);
        die();
    }
}

else{
    $db = new Model('content');
    $listings = $db->filter_index(array('content_group'=>'listing_category','parent_id'=>0),$ord="DESC",$limit="1000",$change_order_by_col="id");
    if ($listings==false) {
        $data['msg'] = "No category found";
        $data['data'] = null;
        echo json_encode($data);
        die();
    }
    else{
        foreach ($listings as $key => $uv) {           
            $listing_data[] = array(
                'id'=>$uv['id'],
                'title'=>$uv['title'],
                'info'=>$uv['content_info'],
                'description'=>$uv['content'],
                'image' => "/media/images/pages/".$uv['banner'],
                'category' => ($uv['parent_id']==0)?'Main':getData('content',$uv['parent_id'])['title'],
                'status' => $uv['status'],
                'child'=>getCatTree($uv['id'])
            );
        }
        $data['msg'] = "success";
        $data['data'] = $listing_data;
        echo json_encode($data);
        return;
    }
}

