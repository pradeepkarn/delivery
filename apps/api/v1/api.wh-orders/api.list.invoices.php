<?php
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST') {
    $token = isset($_POST['token'])?$_POST['token']:null;
    $order_id = isset($_POST['order_id'])?$_POST['order_id']:null;
    // Do something with the data
} elseif ($method === 'GET') {
    $res['msg'] = "Wrong method to send data";
    $res['data'] = null;
    echo json_encode($res);
    return;
}
if($token==null){
    $res['msg'] = "Empty token not allowed";
    $res['data'] = null;
    echo json_encode($res);
    return;
}
$user = new Model('pk_user');
$arr['app_login_token'] = $token;
$user = $user->filter_index($arr);
if (!count($user)>0) {
    $res['msg'] = "Invalid token";
    $res['data'] = null;
    echo json_encode($res);
    return;
}
if ($order_id!=null) {
  $invpice = MEDIA_ROOT."/generated_docs/{$order_id}.pdf";

  if (!file_exists($invpice)) {
    $res['msg'] = "Invoice not generated";
    $res['data'] = null;
    echo json_encode($res);
    return;
  }else{
    $link = "/media/generated_docs/{$order_id}.pdf";
    $res['msg'] = "Invoice generated";
    $res['data'] = $link;
    echo json_encode($res);
    return;
  }
}
else{
    $res['msg'] = "Invalid order id";
    $res['data'] = null;
    echo json_encode($res);
    return;
}




// $pages_files = glob($invpices."*.*");
// $inv = glob($invpice."*.pdf");
// $invs = array();
// foreach ($inv as $fl) {
//   $img = explode("/",$fl);
//   $invs[] = "/media/generated_docs/".end($img);
// }

// echo json_encode($invs);