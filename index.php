<?php
require_once(__DIR__."/config.php");
import("/includes/class-autoload.inc.php");
import("apps/account/function.php");
import("functions.php");
import('/vendor/autoload.php');

$url = explode("/", $_SERVER["QUERY_STRING"]);
$path = $_SERVER["QUERY_STRING"];
define("direct_access", 1);
$GLOBALS['row_id'] = end($url);
$GLOBALS['tableName'] = prev($url);
$GLOBALS['url_last_param'] = end($url);
$GLOBALS['url_2nd_last_param'] = prev($url);

define('RELOAD',js("location.reload();"));
define('URL',$url);
$acnt = new Account;
$acnt = $acnt->getLoggedInAccount();
define('USER',$acnt);
$checkaccess = ['admin','subadmin','salesman','whmanager'];
if (authenticate()==true) {
  if (isset(USER['user_group'])) {
    $pass = in_array(USER['user_group'],$checkaccess);
    define('PASS',$pass);
  }else{
    $pass = false;
    define('PASS',$pass);
  }
}else{
  $pass = false;
  define('PASS',$pass);
}

$GLOBALS['bk_sts'] = $status = ['new order', 'accepted', 'pending', 'assigned', 'delivered', 'cancelled', 'rejected', 'returned', 'completed'];
// $inst = new Dbobjects;
// $inst->tableName = "pk_user";

// myprint($inst->all("DESC",10));
// myprint($inst->sql);

// myprint((new Model('content'))->index());
// myprint($$inst->tables());
// return;


$context= array();
// Login via cookie
  if (isset($_COOKIE['remember_token'])) {
  $acc = new Account;
  $acc->loginWithCookie($_COOKIE['remember_token']);
  }

import("apps/controllers/AttendanceCtrl.php");
import("apps/controllers/LeaveCtrl.php");
import("apps/controllers/UserProfileUpdateCtrl.php");
  //login via cookie ends
  
// define("VERSION","v2");
// $v2= VERSION;
//cart count close
switch ($path) {
    case '':
      if (authenticate()===false) {
        header("Location:/".home."/login");
        return;
      }else{
        if ($pass===true) {
          header("Location:/".home."/admin");
          return;
        }else{
          header("Location:/".home."/logout");
        }
      }
      
      break;

    // case 'contact':
    //   import("apps/view/screens/contact.index.php");
    //   break;
    // case 'login':
         
      
    //   break;
    case 'register':
        if (authenticate() ==false) {
          import("apps/view/screens/register.index.php");
          return;
        }
        else{
          header("Location:/".home);
          return;
        }
    break;
    case 'logout':
          if (authenticate()==true) {
          setcookie("remember_token", "", time() - (86400 * 30*12), "/"); // 86400 = 1 day
          // Finally, destroy the session.
          if (session_status() !== PHP_SESSION_NONE) {
            session_destroy();
          }
        }
        if (isset($_COOKIE['remember_token'])) {
            unset($_COOKIE['remember_token']);
        }
        header("Location:/".home."/login");
      break;
  default:
          if ($url[0] == "login") {
              if (isset($_POST['login_my_account'])) {
                login();
              }
              if (authenticate() === true) {
                  if ((new Account)->getLoggedInAccount()['role']=="superuser") {
                      header("Location:/".home."/admin");
                      return;
                  }
                  else if ((new Account)->getLoggedInAccount()['user_group']=="salesman") {
                      header("Location:/".home."/admin");
                      return;
                    }
                  else if ((new Account)->getLoggedInAccount()['user_group']=="whmanager") {
                      header("Location:/".home."/admin");
                      return;
                    }
                    else{
                      header("Location:/".home."/logout");
                      return;
                    }
              }
            if (authenticate() === false) {
              import("apps/view/screens/login.index.php");
              return;
            }
            else{
              header("Location:/".home."/admin");
              return;
            }
  
          }
          if ($url[0] == "admin") {
            if (!PASS) {
              $_SESSION['msg'][] = "You are not authorised person to logged in this account";
              header("Location:/".home."/logout");
              return;
            }
            // if (!$pass) {
            //   header("Location:/".home);
            // }
            import("apps/admin/index.php");
            return;
          }
          if ($url[0] == "api") {
            import("apps/api/index.php");
            return;
          }
          if ($url[0] == "contact") {
            import("apps/view/screens/contact.index.php");
            return;
          }
        
          // if ($url[0] == "profile") {
          //   if (authenticate()===false) {
          //     header("Location:/".home."/login/");
          //   }
          //   import("apps/view/screens/profile.index.php");
          //   return;
          // }
          
          // if ($url[0] == "dashboard") {
          //   if (authenticate()===false) {
          //     header("Location:/".home."/login/");
          //   }
          //   if (is_superuser()===true) {
          //     import("apps/view/screens/home.index.php");
          //     break;
          //   }else{
          //     import("apps/view/screens/home.index.php");
          //     break;
          //   }
          // }
          if ($url[0] == "send-enquiry-ajax") {
            if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['message'])){
              if(!filter_input(INPUT_POST,"email",FILTER_VALIDATE_EMAIL)){
                echo "Invalid email";
                die();
              }
              if((str_replace(" ","",$_POST['message']))==""){
                echo "Please write your message";
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
              $dbcreate = new Model('contact');
              $enq_id = $dbcreate->store($arr);
              if ($enq_id==false) {
                  echo "Something went wrong";
                  die();
              }
              echo js_alert("success");
              echo RELOAD;
              return;
            }
            return;
          }
          // elseif($url[0] == "mark-attendance-ajax"){
          //   $atndnce = new AttendanceCtrl;
          //   $atndnce->make_me_present();
          //   msg_ssn();
          //   echo RELOAD;
          //   return;
          // }
          // if ($url[0] == "leaves") {
          //   if (authenticate()===false) {
          //     header("Location:/".home."/login/");
          //   }
          //   import("apps/view/screens/request-for-leave.index.php");
          //   return;
          // }
          // elseif($url[0] == "request-for-leave-ajax"){
          //   $leave = new LeaveCtrl;
          //   $arr=null;
          //   if (!(strtotime($_POST['from_date'])>=strtotime(date('Y-m-d')))) {
          //     $_SESSION['msg'][] = "Invalid from date";
          //     msg_ssn();
          //     return;
          //   }
          //   if (!(strtotime($_POST['to_date'])>=strtotime(date('Y-m-d')))) {
          //     $_SESSION['msg'][] = "Invalid to date";
          //     msg_ssn();
          //     return;
          //   }
          //   $arr['from_date'] = $_POST['from_date'];
          //   $arr['to_date'] = $_POST['to_date'];
          //   $arr['start_time'] = $_POST['start_time'];
          //   $arr['leave_group'] = $_POST['leave_group'];
          //   $arr['end_time'] = $_POST['end_time'];
          //   $rply = $leave->request_for_leave($arr);
          //   msg_ssn();
          //   if ($rply==true) {
          //     echo RELOAD;
          //   }
         
          //   return;
          // }
          // elseif($url[0] == "update-my-profile-form"){
          //   if (authenticate()===false) {
          //     $_SESSION['msg'][] = "You are not logged in";
          //     msg_ssn();
          //     return;
          //   }
          //   import("apps/controllers/UserSignCtrl.php");
          //   $auth = new UserSignCtrl;
          //   $auth->sign_up();
          //   msg_ssn();
          //   return;
          // }
          // elseif($url[0] == "register-ajax-form-data"){
          //   import("apps/controllers/UserSignCtrl.php");
          //   $auth = new UserSignCtrl;
          //   $auth->sign_up();
          //   msg_ssn();
          //   return;
          // }
          // elseif($url[0] == "apply-cert-ajax"){
             
          //     import("apps/controllers/ApplyForCert.php");
          //     $appl = new ApplyForCert;
          //     $appl->save_form_data($userid=$_SESSION['user_id']);
          //     msg_ssn();
          //   return;
          // }
         
          elseif($url[0] == "signup"){
            if (authenticate()==true):
             header("Location:/".home);
            endif;
           import("apps/view/signup.php");
           return;
         }
          
          else if ($url[0] == "page") {
            if (is_superuser()===false) {
                header("Location:/".home);
                return;
            }
            import("apps/plugins/page/index.php");
            return;
          }
         
          else if ($url[0] == "gallery") {
            if (is_superuser()===false) {
                header("Location:/".home);
                return;
            }
            import("apps/plugins/gallery/index.php");
            return;
          }
          else if ($url[0] == "slider") {
            if (is_superuser()===false) {
                header("Location:/".home);
                return;
            }
            import("apps/plugins/slider/index.php");
            return;
          }
          else if ($url[0] == "product" && isset($_GET['pid'])) {
            import("apps/view/product.php");
            return;
          }
          
          else {
                if(!empty($path)){
                  $db = new Dbobjects();
                  $db->tableName = 'content';
                  $qry['slug'] = $path;
                  // $qry['status'] = 'published';
                  if (!empty($db->filter($qry))) {
                      $GLOBALS['page'] = $db->get($qry);
                      if ($GLOBALS['page']['content_group']=="page"){
                          // import("apps/view/page.php");
                          import("apps/view/screens/pages.index.php");
                          return;
                      }
                  }
                  else{
                      import("apps/view/404.php");
                      return;
                  }
              }
          }
          break;
    }


 