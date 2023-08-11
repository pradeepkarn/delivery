<?php 
$locListObj = new Model('locations');
// $locListObj->json_obj = true;
$allloc = $locListObj->index();
$allloc = count($allloc)?$allloc:null;
$msg = count($allloc)?"Location list":"No location found";
$data['msg'] = $msg;
$data['data'] = $allloc;
echo json_encode($data);
return;

?>