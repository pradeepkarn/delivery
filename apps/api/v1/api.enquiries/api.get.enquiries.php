<?php 
if(isset($_POST['user_id']) && isset($_POST['login_token'])){
    if (!filter_input(INPUT_POST, "user_id", FILTER_VALIDATE_INT)) {
        $data['msg'] = "Invalid user id";
        $data['data'] = null;
        echo json_encode($data);
        die();
    }
    $user_id = sanitize_remove_tags($_POST['user_id']);
    $login_token = sanitize_remove_tags($_POST['login_token']);
    
   $user = new Model('pk_user');
   $userarr['id'] = $user_id;
   $userarr['app_login_token'] = $login_token;
   if($user->exists($userarr)==false){
            $data['msg'] = "Please login first";
            $data['data'] = null;
            echo json_encode($data);
            die();
   }
   

    $db = new Model('contact');
    $enqs = $db->filter_index(array('type'=>'app_enquiry','obj_owner'=>$user_id),$ord="DESC",$limit="1000",$change_order_by_col="id");
    if ($enqs==false) {
        $data['msg'] = "No enquiry";
        $data['data'] = null;
        echo json_encode($data);
        die();
    }
    else{
        foreach ($enqs as $key => $uv) { 
            // $user = getData('pk_user',$uv['created_by']);          
            $enq_data[] = array(
                'id'=>$uv['id'],
                'message'=>$uv['message'],
                'name'=>$uv['name'],
                'email'=>$uv['email'],
                'status'=>$uv['status']                
            );
        }
        $data['msg'] = "success";
        $data['data'] = $enq_data;
        echo json_encode($data);
        return;
    }
}