<?php   
    if(isset($_POST['token'])){
        $token = $_POST['token'];
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
        $user = $user[0];
    // $email = generate_dummy_email($_POST['mobile']);
    // if (isset($_POST['email'])) {
    //     $email = sanitize_remove_tags($_POST['email']);
    // }
    
    // $password = sanitize_remove_tags($_POST['password']);
    // $cnf_password = sanitize_remove_tags($_POST['cnf_password']);

    
    // $arr['email'] = $email;
    // $arr['password'] = md5($password);
    //name
    if (isset($_POST['name'])) {
        if(strlen(sanitize_remove_tags($_POST['name']))<2){
            $data['msg'] = "Invalid name";
            $data['data'] = null;
            echo json_encode($data);
            return;
        }
        $arr['name'] = sanitize_remove_tags($_POST['name']);
    }
    if (isset($_POST['company'])) {
        if(strlen(sanitize_remove_tags($_POST['company']))<2){
            $data['msg'] = "Invalid name";
            $data['data'] = null;
            echo json_encode($data);
            return;
        }
        $arr['company'] = sanitize_remove_tags($_POST['company']);
    }
    if (isset($_POST['city'])) {
        if(strlen(sanitize_remove_tags($_POST['city']))<2){
            $data['msg'] = "Invalid city name";
            $data['data'] = null;
            echo json_encode($data);
            return;
        }
        $arr['city'] = sanitize_remove_tags($_POST['city']);
    }
    if (isset($_POST['area'])) {
        if(strlen(sanitize_remove_tags($_POST['area']))<2){
            $data['msg'] = "Invalid city name";
            $data['data'] = null;
            echo json_encode($data);
            return;
        }
        $arr['address'] = sanitize_remove_tags($_POST['area']);
    }
         //email
    // if(filter_var($email,FILTER_VALIDATE_EMAIL)==false){
    //     $data['msg'] = "Something went wrong, please try again";
    //     $data['data'] = null;
    //     echo json_encode($data);
    //     return;
    // }
    //check regtered email
    // $user_by_email = (new Model('pk_user'))->exists(['email'=>$email]);
    // if ($user_by_email!=false) {
    //     $data['msg'] = "Something went wrong, please try again";
    //     $data['data'] = null;
    //     echo json_encode($data);
    //     return;
    // }
        //mobile
        // if (isset($_POST['mobile'])) {
        // if(filter_var($_POST['mobile'],FILTER_VALIDATE_INT)==false){
        //     $data['msg'] = "Invalid mobile";
        //     $data['data'] = null;
        //     echo json_encode($data);
        //     return;
        // }
        // $user_by_mobile = (new Model('pk_user'))->exists(['mobile'=>$_POST['mobile']]);
        // if ($user_by_mobile!=false) {
        //     $data['msg'] = "Mobile number is already registered";
        //     $data['data'] = null;
        //     echo json_encode($data);
        //     return;
        // }
        // $arr['mobile'] = sanitize_remove_tags($_POST['mobile']);
        // }
        //national id number
        // if (isset($_POST['national_id'])) {
        //     if($_POST['national_id']==""){
        //         $data['msg'] = "Empty National Id Number";
        //         $data['data'] = null;
        //         echo json_encode($data);
        //         return;
        //     }
        //     $user_by_national_id = (new Model('pk_user'))->exists(['national_id'=>$_POST['national_id']]);
        //     if ($user_by_national_id!=false) {
        //         $data['msg'] = "Your National Id is already regsitered";
        //         $data['data'] = null;
        //         echo json_encode($data);
        //         return;
        //     }
        // $arr['national_id'] = sanitize_remove_tags($_POST['national_id']);
        // }
        //username
    
    // if (isset($_POST['username'])) {
    //     $username = str_replace(" ","",sanitize_remove_tags($_POST['username']));
    //     $arr['username'] = $username;
    //     if ((strlen($username)<3) || (strlen($username)>16)) {
    //         $data['msg'] = "Username must be between 3 to 16 characters";
    //         $data['data'] = null;
    //         echo json_encode($data);
    //         return;
    //     }
    //     //check regtered username
    //     $user_by_username = (new Model('pk_user'))->exists(['username'=>$username]);
    //     if ($user_by_username!=false) {
    //         $data['msg'] = "Username is not available";
    //         $data['data'] = null;
    //         echo json_encode($data);
    //         return;
    //     }
    // }
    // else{
    //    $arr['username'] = generate_username_by_email($email,$try=500);
    // }
    
   
    // //empty pass
    // if (($_POST['password'])=="") {
    //     $data['msg'] = "Empty password is not allowed";
    //     $data['data'] = null;
    //     echo json_encode($data);
    //     die();
    // }
    // //valid pass
    // if (($password!=$_POST['password'])) {
    //     $data['msg'] = "Invalid characters used in password";
    //     $data['data'] = null;
    //     echo json_encode($data);
    //     die();
    // }
    //pass match
    // if ($password!=$cnf_password) {
    //     $data['msg'] = "Password did not match";
    //     $data['data'] = null;
    //     echo json_encode($data);
    //     die();
    // }
        //if evrything valid then
        $dbcreate = new Model('pk_user');
        // $arr['user_group'] = 'customer';
        $userid = $dbcreate->update($user['id'],$arr);
        if ($userid==false) {
            $data['msg'] = "User not updated";
            $data['data'] = null;
            echo json_encode($data);
            die();
        }
        if ($userid==0) {
            $data['msg'] = "User not updated";
            $data['data'] = null;
            echo json_encode($data);
            die();
        }
        $accnt = new Account();
        $userReturn = $accnt->loginWithToken_viaApp($token);

        $data['msg'] = "User updated successfully";
        $data['data'] = $userReturn;
        echo json_encode($data);
        return;
    }
    else{
        $data['msg'] = "Missing required field";
        $data['data'] = null;
        echo json_encode($data);
        die();
    }
