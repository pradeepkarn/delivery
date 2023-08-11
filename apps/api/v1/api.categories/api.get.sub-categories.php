<?php 
if(!isset($_POST['cat_id'])){
    $data['msg'] = "Please provide category id";
    $data['data'] = null;
    echo json_encode($data);
    die();
}
if (!filter_input(INPUT_POST, "cat_id", FILTER_VALIDATE_INT)) {
    $data['msg'] = "Invalid Id category id";
    $data['data'] = null;
    echo json_encode($data);
    die();
}

$db = new Model('content');
$listings = $db->filter_index(array('content_group'=>'listing_category','parent_id'=>$_POST['cat_id']),$ord="DESC",$limit="1000",$change_order_by_col="id");
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

