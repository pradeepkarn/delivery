<?php   
    if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['message'])){
        if(!filter_input(INPUT_POST,"email",FILTER_VALIDATE_EMAIL)){
            $data['msg'] = "Invalid email";
            $data['data'] = null;
            echo json_encode($data);
            die();
        }
        if((str_replace(" ","",$_POST['message']))==""){
            $data['msg'] = "Empty message is not allowed";
            $data['data'] = null;
            echo json_encode($data);
            die();
        }
        if(isset($_POST['name'])){
            $arr['name'] = sanitize_remove_tags($_POST['name']);
        }
        if(isset($_POST['email'])){
            $arr['email'] = sanitize_remove_tags($_POST['email']);
        }
        if(isset($_POST['message'])){
            $arr['message'] = sanitize_remove_tags($_POST['message']);
        }
        if(isset($_POST['company'])){
            $arr['company'] = sanitize_remove_tags($_POST['company']);
        }
        if(isset($_POST['mobile'])){
            $arr['mobile'] = sanitize_remove_tags($_POST['mobile']);
        }
        if(isset($_POST['subject'])){
            $arr['subject'] = sanitize_remove_tags($_POST['subject']);
        }
        if(isset($_POST['type'])){
            $arr['type'] = sanitize_remove_tags($_POST['type']);
        }
        else{
            $arr['type'] = "app_enquiry";
        }

        if(isset($_POST['obj_id'])){
            $arr['obj_id'] = sanitize_remove_tags($_POST['obj_id']);
        }
        if(isset($_POST['obj_group'])){
            $arr['obj_group'] = strtolower(sanitize_remove_tags($_POST['obj_group']));
        }
        if(isset($_POST['obj_owner_user_id'])){
            $arr['obj_owner'] = sanitize_remove_tags($_POST['obj_owner_user_id']);
        }

        $arr['status'] = "new";
       
        //if evrything valid then
        $dbcreate = new Model('contact');
        $enq_id = $dbcreate->store($arr);
        if ($enq_id==false) {
            $data['msg'] = "Something went wrong";
            $data['data'] = null;
            echo json_encode($data);
            die();
        }
        if ($enq_id==0) {
            $data['msg'] = "Something went wrong";
            $data['data'] = null;
            echo json_encode($data);
            die();
        }
        $data['msg'] = "success";
        $data['data'] = null;
        echo json_encode($data);
        return;
    }
    else{
        $data['msg'] = "Missing required field";
        $data['data'] = null;
        echo json_encode($data);
        die();
    }
