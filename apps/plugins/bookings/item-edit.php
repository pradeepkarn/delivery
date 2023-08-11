<?php if (defined("direct_access") != 1) {
    echo "Silenece is awesome";
    return;
} ?>
<?php $GLOBALS["title"] = "Home"; ?>
<?php import("apps/admin/inc/header.php"); ?>
<?php import("apps/admin/inc/nav.php");
$plugin_dir = "bookings";
$bk = (object) booking_detail($_GET['id']);
$user = obj(getData('pk_user', $bk->user_id));
$vendor = obj(getData('pk_user', $bk->vendor_id));
$salon = obj(getData('content', $bk->salon_id));
?>
<?php

// if (isset($_POST['update_banner'])) {
//     $contentid = $_POST['update_banner_page_id'];
//     $banner=$_FILES['banner'];
//     $banner_name = uniqid("banner_").time().USER['id'];
//     // print_r($_FILES);
//     change_my_banner($contentid,$banner,$banner_name);
//     msg_ssn();
// }

?>
<style>
    .list-none li {
        font-weight: bold;
    }

    .menu-col {
        min-height: 300px !important;
    }
</style>
<section>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div id="sidebar-col" class="col-md-2 <?php echo sidebar_bg; ?>">
                <?php import("apps/admin/inc/sidebar.php"); ?>
            </div>
            <!-- Main Area -->
            <div id="content-col" class="col-md-10 pb-5">
                <?php import("apps/admin/pages/page-nav.php"); ?>
                <h4>Bookings</h4>
                <a class="btn btn-dark my-2" href="/<?php echo home . "/admin/" . $plugin_dir; ?>">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
                <div class="row">
                    <div class="col-md-8">
                       

                        <div class="row">

                            <div class="col-md-12 my-3">
                                <label for="">Satus</label>
                                <select name="status" class="form-select edit-this-booking">
                                    <option <?php echo $bk->status == "requested" ? "selected" : null; ?> value="requested">Requested</option>
                                    <option <?php echo $bk->status == "confirmed" ? "selected" : null; ?> value="confirmed">Confirmed</option>
                                    <option <?php echo $bk->status == "cancelled" ? "selected" : null; ?> value="cancelled">Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-12 my-3">
                                <?php if ($bk->note != '') { ?>
                                    <b>Booking note:</b>
                                    <div class="alert alert-warning">
                                        <?php echo $bk->note; ?>
                                    </div>
                                <?php  } ?>

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
                                        <td><?php echo $user->name; ?></td>
                                        <td><?php echo $user->mobile; ?></td>
                                        <td><?php echo $salon->title; ?></td>
                                        <td><?php echo $vendor->name; ?></td>
                                        <td><?php echo $vendor->mobile; ?></td>
                                        <td><?php echo $bk->visiting_date; ?></td>
                                        <td><?php echo $bk->visiting_time; ?></td>
                                        <td><?php echo $bk->net_amt; ?>/-</td>
                                        <td>
                                            <div class="p-1 text-capt 
                                            <?php
                                            echo $bk->status == 'confirmed' ? 'bg-success text-white' : null;
                                            echo $bk->status == 'cancelled' ? 'bg-basic text-muted' : null;
                                            echo $bk->status == 'requested' ? 'bg-warning text-dark' : null;
                                            ?>">
                                                <?php echo $bk->status; ?>
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
                                    <?php
                                    $srobj = json_decode($bk->jsn);
                                    if (isset($srobj->services)) {
                                        foreach ($srobj->services as $key => $sr) {
                                    ?>

                                            <tr>
                                                <td><?php echo $sr->title; ?></td>
                                                <td> <?php echo $sr->price; ?></td>
                                                <td><?php echo $sr->duration; ?></td>
                                                <td><?php echo $sr->duration_unit; ?></td>
                                            </tr>

                                    <?php }
                                    }
                                    ?>
                                </table>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="">Visiting Date</label>
                        <input name="visiting_date" value="<?php echo $bk->visiting_date; ?>" type="date" class="edit-this-booking form-control my-3">
                        <label for="">Visiting Time</label>
                        <input name="visiting_time" value="<?php echo $bk->visiting_time; ?>" type="time" class="edit-this-booking form-control my-3">

                        <div class="d-grid">
                            <div id="res"></div>
                           
                            <b>Cust. Email : <?php echo $user->email; ?></b>
                            <br>
                            <b>Vendor Email : <?php echo $vendor->email; ?></b>
                            <button id="edit-this-booking" class="btn btn-primary btn-lg my-4">Update and send mail</button>
                            <input type="hidden" class="edit-this-booking" name="bkid" value="<?php echo $_GET['id']; ?>">
                            <?php pkAjax('#edit-this-booking', "/admin/$plugin_dir/edit-this-booking-ajax", ".edit-this-booking", "#res"); ?>
                        </div>
                        <div class="progress my-3">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-prime" role="progressbar" aria-label="Animated striped example" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <?php ajaxActive(".progress"); ?>


            <!-- Main Area ends-->
        </div>
    </div>
    </div>
</section>
<?php import("apps/admin/inc/footer.php"); ?>