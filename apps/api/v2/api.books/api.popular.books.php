<?php
$v = API_V;
$method = $_SERVER['REQUEST_METHOD'];
import("apps/api/$v/api.books/fn.relprods.php");
if ($method === 'POST') {
    $req = json_decode(file_get_contents('php://input'));
} else {
    $popular_books = most_read_books();
    $db = new Model('content');
    $listings = $db->filter_index(array('content_group' => 'book'), $ord = "DESC", $limit = "1000", $change_order_by_col = "id");
    if ($listings == false) {
        $data['msg'] = "No Listing";
        $data['data'] = null;
        echo json_encode($data);
        die();
    } else {
        foreach ($popular_books as $key => $popbk) {
            $rel_prods = array();

            // $rel_prods = array();
            $uv = getData('content', $popbk['book_id']);
            $user = getData('pk_user', $uv['created_by']);
            $moreobj = new Model('content_details');
            $moreimg = $moreobj->filter_index(array('content_id' => $uv['id'], 'content_group' => 'product_more_img'));
            $moreimg = $moreimg == false ? array() : $moreimg;
            $moredetail = $moreobj->filter_index(array('content_id' => $uv['id'], 'content_group' => 'product_more_detail'));
            $moredetail = $moredetail == false ? array() : $moredetail;

            if (count($moreimg) == 0) {
                $mor_imgs = array();
                $mor_imgs[] = "/media/images/pages/{$uv['banner']}";
            } else {
                foreach ($moreimg as $key => $fvl) :
                    $mor_imgs[] = "/media/images/pages/{$fvl['content']}";
                endforeach;
            }
            if ($uv['json_obj'] != null) {
                $jsn = json_decode($uv['json_obj']);
                if (isset($jsn->related_products)) {
                    $rel_prods = rel_prods($jsn->related_products);
                }
            }
            $listing_data[] = array(
                'id' => $uv['id'],
                'title_en' => $uv['title'],
                'content_en' => $uv['content'],
                'title_ar' => $uv['content_info'],
                'content_ar' => $uv['other_content'],
                'image' => "/media/images/pages/" . $uv['banner'],
                'category_id' => $uv['parent_id'],
                'category_en' => ($uv['parent_id'] == 0) ? 'Uncategoriesed' : getData('content', $uv['parent_id'])['title'],
                'category_ar' => ($uv['parent_id'] == 0) ? 'Uncategoriesed' : getData('content', $uv['parent_id'])['content_info'],
                'genre' => json_decode($uv['genre']),
                'status' => $uv['status'],
                'more_img' => $mor_imgs,
                'more_detail' => $moredetail,
                'view' => view_count($book_id = $uv['id']),
                'view' => $popbk['view'],
                'author' => $uv['author']
            );
            $mor_imgs = null;
        }
        $data['msg'] = "success";
        $data['data'] = $listing_data;
        echo json_encode($data);
        return;
    }
}
