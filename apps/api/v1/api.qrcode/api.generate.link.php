<?php
if(isset($_GET['user_id'])){
$dcs = new Model('pk_docs');
$arr['user_id'] = $_GET['user_id'];
$arr['obj_group'] = "course_certificate";
$docs = $dcs->filter_index($arr, $ord='DESC',$limit = 999999,$change_order_by_col="id");
// print_r($docs);
if($docs!=false):
    $home = home; 
    // header("Content-Type: image/png");
    foreach ($docs as $key => $dv) {
    $imgsrc = "/".media_root."/docs/{$dv['media_file']}";
    $text = "<a href='$imgsrc' download>Download</a>";
    $file = RPATH."/media/qr/qr_{$dv['id']}.png";
    $imgsrc = "/".media_root."/qr/qr_{$dv['id']}.png";
    //$file = false;
    //other parameters
    $ecc = 'H';
    $pixel_size = 15;
    $frame_size = 2;
    //Generates QR Code and Save as PNG
    QRcode::png($text, $file=$file, $ecc, $pixel_size, $frame_size);
    $domain = MY_DOMAIN;
    $cert_link = "/generate-qr-code/?qrcode_docs_id={$dv['id']}&qrcode_docs_link={$dv['media_file']}";
    
    $crs = getTableRowById("service",$dv['obj_id']);
    $crs_id = ($crs!=false)?$crs['id']:false;
    $course = ($crs!=false)?$crs['title']:false;
    $course_info = ($crs!=false)?$crs['info']:false;
    $schedule_date = $dv['schedule_date'];
    $expiry_date = $dv['expiry_date'];
    $curr_date = strtotime(date("Y-m-d"));
    $exp_date = strtotime($expiry_date);
    if ($exp_date < $curr_date) {
        $cert_expired = true;
    }
    else{
        $cert_expired = false;
    }
        $cert[] = array(
            'certificate_id'=>$dv['id'],
            'qrimage'=>$cert_link,
            'course_id'=>$crs_id,
            'course'=>$course,
            'course_info'=>$course_info,
            'course_start_date'=>$schedule_date,
            'course_end_date'=>$expiry_date,
            'certificate_expired'=>$cert_expired,
        );
    }
    $msg['msg'] = "success";
    $data['data'][] = $msg;
    $data['data'][] = $cert;
    echo json_encode($data);
    return;
 
else:
    $cert['certifiactes'][] = null;
    $msg['msg'] = "No certificate";
    $data['data'][] = $msg;
    $data['data'][] = null;
    echo json_encode($data);
    return;
endif;
}