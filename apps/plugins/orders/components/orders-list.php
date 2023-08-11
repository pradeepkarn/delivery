<?php if (is_superuser()) :
    if (isset($_GET['filter']) && intval($_GET['filter'])) {
        $user = getData('pk_user', $_GET['filter']);
        $delvia = null;
    } else if (isset($_GET['filter']) && $_GET['filter'] == 'courier') {
        $delvia = "courier";
        $user = USER;
    } else {
        $user = USER;
        $delvia = null;
    }
else :
    $user = USER;
    $delvia = null;
endif;
$status = $_GET['status'];
$ords = paid_orders($status, $user, $delvia);

// print_r($ords->carts);
?>

<section>
    <div class="row">
        <div class="col-md-12">
            <h3 class="my-4">
                Order Dashboard
            </h3>
        </div>
    </div>
</section>
<section>

    <div class="row">
        <div class="col-md-4 stretch-card  my-2">
            <div class="card card-tale bg-secondary text-white">
                <a class="btn btn-dark" href="/<?php echo home; ?>/admin/orders">Back</a>
                <div id="res"></div>
                <div class="card-body">
                    <p class=""><?php echo $status; ?></p>

                    <h2><?php echo $ords->ordCount; ?> <small>Order<?php echo $ords->ordCount > 1 ? "s" : null; ?></small></h2>
                    <p>With <?php echo $ords->cartQty; ?> <small>Quantity</small></p>
                </div>
                <div class="row">
                    <div class="col-md-12 my-1">
                        <label for="">From</label>
                        <input type="date" name="from_date" class="form-control">
                    </div>
                    <div class="col-md-12 my-1">
                        <label for="">To</label>
                        <input type="date" name="from_date" class="form-control">
                    </div>
                    <div class="col-md-12 my-1">
                        <button class="btn btn-dark">Download Report</button>
                    </div>
                </div>
                <!-- <a class="btn btn-light" href="/<?php // echo home; ?>/admin/generate-report/?status=<?php // echo $status; ?>">Download Report <i class="fa-solid fa-file-csv"></i> <i class="fa-solid fa-download"></i> </a> -->
            </div>
        </div>
        <div class="col-md-8">
            <table class="table table-sm table-bordered">
                <style>
                    tbody::before {
                        content: '';
                        display: block;
                        height: 15px;
                        visibility: hidden;

                    }
                </style>

                <?php foreach ($ords->cp as $cp) :
                    $tamt = $cp['amount'] + $cp['discount_amt'];
                    $whmngr = getData('pk_user', $cp['whmanager_id']);
                ?>
                    <tbody style="border: 1px dotted black;">
                        <tr>
                            <th colspan="5"><span class="text-muted">Order Number : </span><?php echo $cp['unique_id']; ?></th>
                            <th class="hide">
                                <span class="text-muted">Order Status : </span><?php echo ucfirst($cp['order_status']); ?> <br>
                                <?php
                                if ($cp['wh_status'] == "") : ?>
                                    <input type="hidden" class="forward-data-id<?php echo $cp['id']; ?>" name="forward_to_wh_oid" value="<?php echo $cp['id']; ?>">
                                    <select name="whmanager_id" class="form-select forward-data-id<?php echo $cp['id']; ?>">
                                        <option value="0">Select Warehouse</option>
                                        <?php foreach (getWhmanagerList() as $sm) { ?>
                                            <option <?php if ($cp['whmanager_id'] == $sm['id']) {
                                                        echo "selected";
                                                    } ?> value="<?php echo $sm['id']; ?>"><?php echo $sm['name']; ?> (ID:<?php echo $sm['id']; ?>)</option>
                                        <?php } ?>

                                    </select>
                                    <div class="d-grid">
                                        <button type="button" id="ftwhbtnid<?php echo $cp['id']; ?>" class="my-2 btn btn-primary btn-sm">Forward to warehouse</button>
                                    </div>
                                    <?php
                                    pkAjax("#ftwhbtnid{$cp['id']}", "/admin/orders/forward-to-warehouse-ajax", ".forward-data-id{$cp['id']}", "#res", "click");
                                    ?>

                                <?php else : ?>
                                    <span class="text-muted">Warehouse Status : </span><?php echo ucfirst($cp['wh_status']); ?><br>
                                    <span class="text-muted">Warehouse Manager : </span><?php echo $whmngr ? "{$whmngr['username']} ({$whmngr['id']})" : null; ?>
                                <?php endif; ?>

                            </th>
                            <th>
                                <div class="d-grid">
                                    <a class="btn btn-primary" href="/<?php echo home; ?>/admin/orders/order-details/?tid=<?php echo $cp['unique_id']; ?>">Open</a>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="2" scope="col">DBID</th>
                            <th colspan="2" scope="col">User Price</th>
                            <th colspan="2" scope="col">Selected Driver Price</th>

                        </tr>
                        <tr>
                            <th colspan="2"><?php echo $cp['id']; ?></th>
                            <td colspan="2">1000/-</td>
                            <td colspan="2">850/-</td>
                        </tr>
                        <tr>
                            <th>Cust. Name</th>
                            <th>Mobile</th>
                            <th>City</th>
                            <th>Pick up date</th>

                            <th>Delivery Date</th>
                            <th>Last action</th>
                        </tr>

                        <tr>
                            <td><?php echo $cp['name']; ?></td>
                            <td><?php echo $cp['mobile']; ?></td>
                            <td><?php echo $cp['city']; ?></td>

                            <td><?php echo date('Y-m-d H:i:s'); ?></td>
                            <td><?php echo $cp['delivery_date']; ?></td>
                            <td><?php echo $cp['last_action_on']; ?></td>

                        </tr>
                        <tr>
                            <th colspan="3">Consignment Dimension (LxBxH)</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Delivery Method</th>
                        </tr>
                        <tr>
                            <td colspan="3">120mmx130mmx500mm</td>
                            <td>Location1</td>
                            <td>Location2</td>
                            <td>Cab</td>
                        </tr>
                    </tbody>

                <?php endforeach; ?>

            </table>
        </div>
    </div>

</section>