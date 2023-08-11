<?php
if (!isset($_POST['promo_type'])) {
    $data['msg'] = "No data";
    $data['data'] = null;
    echo json_encode($data);
    return;
}
$db = new Model('promotions');
$obj = new Model('promo_category');
$promo = $obj->filter_index(array('name'=>$_POST['promo_type']));
if (count($promo)==0) {
    $data['msg'] = "Failed";
    $data['poster'] = null;
    $data['data'] = null;
    echo json_encode($data);
    return;
}
$promo = $promo[0];
$arr['content_group'] = $promo['name'];
$sliders = $db->filter_index($arr);
if ($sliders == false) {
    $data['msg'] = "No banner";
    $data['data'] = null;
    echo json_encode($data);
    die();
} else {
    foreach ($sliders as $key => $pv) {
        $uv = getData('content', $pv['content_id']);
        if ($pv['image']==null) {
            $pv['image'] = $uv['banner'];
        }
        $slider_data[] = array(
            'id' => $uv['id'],
            'title' => $uv['title'],
            'description' => pk_excerpt(sanitize_remove_tags($uv['content'])),
            'image' => "/media/images/pages/" . $pv['image'],
            'category' => ($uv['parent_id'] == 0) ? 'Main' : getData('content', $uv['parent_id'])['title'],
            'status' => $uv['status']
        );
    }
    $data['msg'] = "success";
    // $data['poster'] = "/media/images/pages/" . $promo['image'];
    $data['data'] = $slider_data;
    echo json_encode($data);
    return;
}
