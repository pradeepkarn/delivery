<?php 

function upload_base64($base64string='',$uploadpath = RPATH.'/media/images/pages/',$name="bnr") {
    $uploadpath   = $uploadpath;
    $parts        = explode(";base64,", $base64string);
    $imageparts   = explode("image/", @$parts[0]);
    $imagetype    = $imageparts[1];
    $imagebase64  = base64_decode($parts[1]);
    $file         = $uploadpath . $name . '.png';
    file_put_contents($file, $imagebase64);
    return $name.".png";
}
function uploadBanner($banner_name)
{
    if (isset($_FILES['banner']) && isset($_POST['update_banner'])) {
        $file = $_FILES['banner'];
        $media_folder = "images/pages";
        $imgname = $banner_name;
        $media = new Media();
        $page = new Dbobjects();
        $page->tableName = 'content';
        $page->pk($_POST['update_banner_page_id']);
        $file_ext = explode(".",$file["name"]);
        $ext = end($file_ext);
        $page->insertData['banner'] = $imgname.".".$ext;
        $page->update();
        $media->upload_media($file,$media_folder,$imgname,$file['type']);
    }
}

function updatePage()
{
    if (isset($_POST['page_id']) && isset($_POST['update_page'])) {
        $db = new Dbobjects();
        $db->tableName = "service_category";
        $cat = $db->pk($_POST['page_id']);
        $db->insertData['name'] = $_POST['page_title'];
        return $db->update();
    }
}
function addContent()
{
    if (isset($_POST['add_new_service_category'])) {
        $db = new Dbobjects();
        $db->tableName = "service_category";
        $db->insertData['name'] = $_POST['page_title'];
        return $db->create();
    }
}
function delContent($id=null)
{
    if ($id > 0) {
        $db = new Dbobjects();
        $db->tableName = "service_category";
        $qry['id'] = $id;
        if(count($db->filter($qry))>0){
            $db->pk($id);
            return $db->delete();
        }
        else{
            return false;
        }
    }
    else{
        return false;
    }
}

function getCat($id=null)
{
    if ($id !=null) {
        $db = new Dbobjects();
        $db->tableName = "categories";
        $qry['id'] = $id;
        if(count($db->filter($qry))>0){
            return $db->pk($id)['name'];
        }
        else{
            return false;
        }
    }
    else{
        return false;
    }
}
