<?php

use PHPMailer\PHPMailer\PHPMailer;

if (isset($_POST['bkid'])) {
    $obj = new Model('salon_bookings');
    $bkng = obj($obj->show($_POST['bkid']));
    $bk = $bkng;
    $cust = obj(getData('pk_user', $bkng->user_id));
    $user = $cust;
    $vendor = obj(getData('pk_user', $bkng->vendor_id));
    $salon = obj(getData('content', $bkng->salon_id));
    $st = $_POST['status'];
    $vd = $_POST['visiting_date'];
    $vt = $_POST['visiting_time'];
    $arr = null;
    $arr['status'] = $st;
    $arr['visiting_date'] = $vd;
    $arr['visiting_time'] = $vt;

    try {
        $obj->update($bkng->id, $arr);
        $_SESSION['msg'][] = "Updated with $st";
        try {

            $mail = php_mailer(new PHPMailer());
            $mail->isHTML(true);
            $mail->Subject = 'Booking Status';

            $bk = obj($obj->show($_POST['bkid']));
            // $bk = obj((new Model('salon_bookings'))->show($_POST['bkid']));

            $bkstts_cnf = $bk->status == 'confirmed' ? 'bg-success text-white' : null;
            $bkstts_cncl = $bk->status == 'cancelled' ? 'bg-basic text-muted' : null;
            $bkstts_req = $bk->status == 'requested' ? 'bg-warning text-dark' : null;
        
            
            $srobj = json_decode($bk->jsn);
            $jsn_footer = '';
            if (isset($srobj->services)) {
                foreach ($srobj->services as $key => $sr) {
           
        
                  $jsn_footer .="
                    <tr>
                        <td>$sr->title</td>
                        <td>$sr->duration</td>
                        <td>$sr->duration_unit</td>
                    </tr>
                    ";
             }
            }
        
            $body = <<<MSG
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Order Status</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css">
        </head>
        <body>
            <div class="container">
                <div class="row">
                    <div class="col-md-6 mx-auto">
                        <h1 class="text-center">Order Status</h1>
                    </div>
                </div>
                <div class="row my-5">
                <div class="row">
        
         
                <div class="col-md-12 my-3">
                 
                        <b>Booking note:</b>
                        <div class="alert alert-warning">
                        $bk->note
                        </div>
                  
        
                    <b>Booking Details:</b>
        
        
                    <table class="table table-bordered">
                        <tr>
                            <th>Cust. Name</th>
                            <th>Cust. Mobile</th>
                            <th>Salon Name</th>
                            <th>Vendor Name</th>
                            <th>Vendor Mobile</th>
                            <th>Visiting date</th>
                            <th>Visiting Time</th>
                            <th>Amount</th>
                            <th>Status</th>
        
                        </tr>
                        <tr>
                            <td>$user->name</td>
                            <td>$user->mobile</td>
                            <td>$salon->title</td>
                            <td>$vendor->name</td>
                            <td>$vendor->mobile</td>
                            <td>$bk->visiting_date</td>
                            <td>$bk->visiting_time</td>
                            <td>$bk->net_amt/-</td>
                            <td>
                                <div class="p-1 text-capt  $bkstts_req  $bkstts_cnf  $bkstts_cncl ">
                                $bk->status
                                </div>
                            </td>
                        </tr>
                    </table>
                    <b>Services:</b>
                    <table class="table table-bordered border-primary">
                        <tr>
                            <th>Name</th>
                            <th>Original Price</th>
                            <th class="text-center" colspan="2">Time</th>
                        </tr>
                         $jsn_footer
                    </table>
                </div>
        
            </div>
                </div>
                <hr>
               
            </div>
        </body>
        </html>
        MSG;

            $mail->Body = $body;
            if (!email_has_valid_dns(email)) {
                $_SESSION['msg'][] = "Please check your registerd server email: ".email;
                echo js_alert(msg_ssn(return:true));
                return;
            }  
            $mail->setFrom(email, SITE_NAME."-Order Status");          
            if (email_has_valid_dns($cust->email)) {
                $mail->addAddress($cust->email, $cust->name);
                $_SESSION['msg'][] = "Email sent to customer: $cust->email";
            }else{
                $_SESSION['msg'][] = "$cust->email is not a valid customer email";
            }
            if (email_has_valid_dns($vendor->email)) {
                $mail->addAddress($vendor->email, $vendor->name);
                $_SESSION['msg'][] = "Email sent to vendor: $vendor->email";
            }
            else{
                $_SESSION['msg'][] = "$vendor->email is not a valid vendor email";
            }
            try {
                $mail->send();
                echo js_alert(msg_ssn(return:true));
                return;
            } catch (ErrorException $e) {
                // echo 'Error sending email: ' . $mail->ErrorInfo;
                echo js_alert(msg_ssn(return:true));
                return;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        echo js_alert(msg_ssn(return: true));
        // echo RELOAD;
    } catch (PDOException $th) {
        $_SESSION['msg'][] = "Something went wrong";
        echo js_alert(msg_ssn(return: true));
    }
}
