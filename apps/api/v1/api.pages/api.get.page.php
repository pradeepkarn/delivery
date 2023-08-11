<?php
if (!isset($_POST['slug'])) {
    $msg = "Please provide slug";
    $content = null;
} else {
    $page = get_content_by_slug($slug = $_POST['slug']);
    if ($page == false) {
        $msg = "Page not found";
        $content = null;
    } else {
        $msg = "success";
        $content = array(
            "title" => $page['title'],
            "content" => $page['content']
        );
    }
}

$data['msg'] = $msg;
$data['data'] = $content;
echo json_encode($data);
return;
