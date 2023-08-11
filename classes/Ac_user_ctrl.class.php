<?php
class Ac_user_ctrl
{
    public $req;
    public $files;
    public function __construct()
    {
        $this->req = obj($_POST);
        if (isset($_FILES)) {
            $this->files = obj($_FILES);
        }
    }
    function register()
    {
        $req = $this->req;
        $checkfileld = [
            'first_name', 'last_name', 'username', 'email',
            'password', 'user_group', 'mobile', 'dial_code',
            'company_name',
            'company_cr',
            'company_vat'
        ];
        foreach ($checkfileld as $arv) {
            if (!isset($req->$arv)) {
                msg_set("$arv is required");
                echo js_alert(msg_ssn(return: true));
                return;
            }
        }
        if ($req->first_name == '') {
            msg_set("First name must not be empty");
            echo js_alert(msg_ssn(return: true));
            return;
        }
        $db = new Dbobjects;
        $pdo = $db->dbpdo();
        try {

            $pdo->beginTransaction();
            $db->tableName = 'pk_user';
            $emailtest = $db->filter(['email' => $req->email]);
            if (count($emailtest) > 0) {
                msg_set("This email is already registered");
                echo js_alert(msg_ssn(return: true));
                return;
            }
            if (!email_has_valid_dns($req->email)) {
                msg_set("This email has invalid dns");
                echo js_alert(msg_ssn(return: true));
                return;
            }
            if ($req->username == '') {
                $arr['username'] = generate_username_by_email($req->email);
            } else {
                $usernametest = $db->filter(['username' => $req->username]);
                if (count($usernametest) > 0) {
                    msg_set("This username is already registered please use different");
                    echo js_alert(msg_ssn(return: true));
                    return;
                }
                $arr['username'] = sanitize_remove_tags(strtolower(str_replace(" ", "", $req->username)));
                if ($arr['username'] == "") {
                    msg_set("Username must not be empty");
                    echo js_alert(msg_ssn(return: true));
                    return;
                }
            }
            if ($req->mobile != '') {
                $mobiletest = $db->filter(['mobile' => $req->mobile]);
                if (count($mobiletest) > 0) {
                    msg_set("This mobile number is already registered please use different");
                    echo js_alert(msg_ssn(return: true));
                    return;
                }
                $arr['mobile'] = intval($req->mobile);
                if (isset($req->dial_code)) {
                    $arr['isd_code'] = $req->dial_code;
                }
            }

            $arr['first_name'] = $req->first_name;
            $arr['last_name'] = $req->last_name;
            $arr['email'] = $req->email;
            $arr['user_group'] = $req->user_group;
            $arr['password'] = md5($req->password);

            $arr['company'] = $req->company_name;
            $arr['company_cr'] = $req->company_cr;
            $arr['company_vat'] = $req->company_vat;
            $arr['password'] = md5($req->password);
            $db->insertData = $arr;
            $arr = null;
            $userid = $db->create();
            msg_set("{$req->user_group} added");
            if (intval($userid)) {
                if (isset($this->files->profile_img)) {
                    $imgfl = obj($this->files->profile_img);
                    if ($imgfl->error == 0) {
                        $ext = pathinfo($imgfl->name, PATHINFO_EXTENSION);
                        $imgname = uniqid('profile_') . $userid . "." . $ext;
                        move_uploaded_file($imgfl->tmp_name, MEDIA_ROOT . "images/profiles/$imgname");
                        $db->insertData['image'] = $imgname;
                        $db->update();
                        msg_set('Profile image uploaded');
                    }
                }
                if (isset($this->files->comp_vat_doc)) {
                    $imgfl = obj($this->files->comp_vat_doc);
                    if ($imgfl->error == 0) {
                        $ext = pathinfo($imgfl->name, PATHINFO_EXTENSION);
                        $docs = uniqid('comp_vat_doc_') . $userid . "." . $ext;
                        move_uploaded_file($imgfl->tmp_name, MEDIA_ROOT . "docs/$docs");
                        $db->insertData['comp_vat_doc'] = $docs;
                        $db->update();
                        msg_set('Docs uploaded');
                    }
                }
                if (isset($this->files->comp_cr_doc)) {
                    $imgfl = obj($this->files->comp_cr_doc);
                    if ($imgfl->error == 0) {
                        $ext = pathinfo($imgfl->name, PATHINFO_EXTENSION);
                        $docs = uniqid('comp_cr_doc_') . $userid . "." . $ext;
                        move_uploaded_file($imgfl->tmp_name, MEDIA_ROOT . "docs/$docs");
                        $db->insertData['comp_cr_doc'] = $docs;
                        $db->update();
                        msg_set('Docs uploaded');
                    }
                }
                if (isset($this->files->driver_doc)) {
                    $imgfl = obj($this->files->driver_doc);
                    if ($imgfl->error == 0) {
                        $ext = pathinfo($imgfl->name, PATHINFO_EXTENSION);
                        $docs = uniqid('driver_doc_') . $userid . "." . $ext;
                        move_uploaded_file($imgfl->tmp_name, MEDIA_ROOT . "docs/$docs");
                        $db->insertData['driver_doc'] = $docs;
                        $db->update();
                        msg_set('Docs uploaded');
                    }
                }
            }
            $pdo->commit();
            msg_set("{$req->user_group} created successfully");
            echo js_alert(msg_ssn(return: true));
            return true;
        } catch (PDOException $th) {
            $pdo->rollback();
            msg_set("{$req->user_group} not created, db error");
            echo js_alert(msg_ssn(return: true));
            return false;
        }
    }


    function update()
    {
        $req = $this->req;
        $checkfileld = [
            'user_id', 'first_name', 'last_name', 'username', 'email',
            'password', 'user_group', 'mobile', 'dial_code',
            'company_name',
            'company_cr',
            'company_vat'
        ];
        foreach ($checkfileld as $arv) {
            if (!isset($req->$arv)) {
                msg_set("$arv is required");
                echo js_alert(msg_ssn(return: true));
                return;
            }
        }
        if ($req->first_name == '') {
            msg_set("First name must not be empty");
            echo js_alert(msg_ssn(return: true));
            return;
        }
        $db = new Dbobjects;
        $pdo = $db->dbpdo();
        try {

            $pdo->beginTransaction();
            $db->tableName = 'pk_user';
            $user = $db->pk($req->user_id);
            $u = obj($user);
            $emailtest = $db->filter(['email' => $req->email]);
            if (count($emailtest) == 0) {
                $arr['email'] = $req->email;
            } else {
                if ($u->email != $req->email) {
                    msg_set("This email has is registered");
                    echo js_alert(msg_ssn(return: true));
                    return;
                }
            }
            if (!email_has_valid_dns($req->email)) {
                msg_set("This email has invalid dns, please change");
                echo js_alert(msg_ssn(return: true));
                // return;
            }
            if ($req->username != '') {
                $usernametest = $db->filter(['username' => $req->username]);
                if (count($usernametest) == 0) {
                    $arr['username'] = $req->username;
                } else {
                    if ($u->username != $req->username) {
                        msg_set("This username is not available");
                        echo js_alert(msg_ssn(return: true));
                        return;
                    }
                }
            }
            if ($req->mobile != '') {
                $mobiletest = $db->filter(['mobile' => $req->mobile]);
                // myprint($mobiletest );
                if (count($mobiletest) == 0) {
                    $arr['mobile'] = intval($req->mobile);
                } else {
                    if ($u->mobile != $req->mobile) {
                        msg_set("This mobile is already registered");
                        echo js_alert(msg_ssn(return: true));
                        return;
                    }
                }
                if (isset($req->dial_code)) {
                    $arr['isd_code'] = $req->dial_code;
                }
            }
         
            $arr['first_name'] = $req->first_name;
            $arr['last_name'] = $req->last_name;

            // $arr['user_group'] = $req->user_group;
            if ($req->password != '') {
                $arr['password'] = md5($req->password);
            }

            $arr['company'] = $req->company_name;
            $arr['company_cr'] = $req->company_cr;
            $arr['company_vat'] = $req->company_vat;





            if (isset($this->files->profile_img)) {
                $imgfl = obj($this->files->profile_img);
                if ($imgfl->error == 0) {
                    $ext = pathinfo($imgfl->name, PATHINFO_EXTENSION);
                    $imgname = uniqid('profile_') . $u->id . "." . $ext;
                    if (move_uploaded_file($imgfl->tmp_name, MEDIA_ROOT . "images/profiles/$imgname")) {
                        if (file_exists(MEDIA_ROOT . "images/profiles/$u->image")) {
                            unlink(MEDIA_ROOT . "images/profiles/$u->image");
                        }
                    }
                    $arr['image'] = $imgname;
                    msg_set('Profile image uploaded');
                }
            }
            if (isset($this->files->comp_vat_doc)) {
                $imgfl = obj($this->files->comp_vat_doc);
                if ($imgfl->error == 0) {
                    $ext = pathinfo($imgfl->name, PATHINFO_EXTENSION);
                    $docs = uniqid('comp_vat_doc_') . $u->id . "." . $ext;

                    if (move_uploaded_file($imgfl->tmp_name, MEDIA_ROOT . "docs/$docs")) {
                        if (file_exists(MEDIA_ROOT . "docs/$u->comp_vat_doc")) {
                            unlink(MEDIA_ROOT . "docs/$u->comp_vat_doc");
                        }
                    }
                    $arr['comp_vat_doc'] = $docs;

                    msg_set('Docs uploaded');
                }
            }
            if (isset($this->files->comp_cr_doc)) {
                $imgfl = obj($this->files->comp_cr_doc);
                if ($imgfl->error == 0) {
                    $ext = pathinfo($imgfl->name, PATHINFO_EXTENSION);
                    $docs = uniqid('comp_cr_doc_') . $u->id . "." . $ext;

                    if (move_uploaded_file($imgfl->tmp_name, MEDIA_ROOT . "docs/$docs")) {
                        if (file_exists(MEDIA_ROOT . "docs/$u->comp_cr_doc")) {
                            unlink(MEDIA_ROOT . "docs/$u->comp_cr_doc");
                        }
                    }
                    $arr['comp_cr_doc'] = $docs;

                    msg_set('Docs uploaded');
                }
            }
            if (isset($this->files->driver_doc)) {
                $imgfl = obj($this->files->driver_doc);
                if ($imgfl->error == 0) {
                    $ext = pathinfo($imgfl->name, PATHINFO_EXTENSION);
                    $docs = uniqid('driver_doc_') . $u->id . "." . $ext;

                    if (move_uploaded_file($imgfl->tmp_name, MEDIA_ROOT . "docs/$docs")) {
                        if (file_exists(MEDIA_ROOT . "docs/$u->driver_doc")) {
                            unlink(MEDIA_ROOT . "docs/$u->driver_doc");
                        }
                    }
                    $arr['driver_doc'] = $docs;

                    msg_set('Docs uploaded');
                }
            }
            $db->pk($req->user_id);
            $db->insertData = $arr;
            // myprint($arr);s
            $db->update();
            
            $pdo->commit();
            msg_set("{$req->user_group} updated successfully");
            echo js_alert(msg_ssn(return: true));
            return true;
        } catch (PDOException $th) {
            $pdo->rollback();
            msg_set("{$req->user_group} not updated, db error");
            echo js_alert(msg_ssn(return: true));
            return false;
        }
     
    }
}
