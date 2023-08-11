<?php
$uid = $_GET['tid'];
$cpo = get_order_by_uinique_id($uid);

// myprint($cpo);
?>

<section>
    <div class="row">
        <div class="col-md-12">
            <h3 class="my-4">
                Order Dashboard
            </h3>
            <div id="res"></div>
        </div>
    </div>
</section>
<section>

    <div class="row">
        <div class="col-md-12">
            <table class="text-end table table-sm table-bordered">


                <?php
                $drvrusrnam  = null;
                $driver = getData('pk_user', $cpo[0]['salesperson_id']);
                if ($driver) {
                    $drvrusrnam = "Driver: {$driver['first_name']} {$driver['first_name']} - ({$driver['email']})";
                }
                foreach ($cpo as $cp) :
                    $tamt = $cp['amount'] + $cp['discount_amt'];
                ?>
                    <thead>
                        <tr class="text-start">
                            <th colspan="7"><span class="text-muted">Order Number : </span><?php echo $cp['unique_id']; ?></th>
                        </tr>
                        <tr>
                            <th scope="col">DBID</th>
                            <th colspan="3">User Price</th>
                            <th colspan="3" scope="col">Last action on</th>

                        </tr>
                    </thead>
                    <tbody style="border: 1px dotted black;">

                        <tr>
                            <th><?php echo $cp['id']; ?></th>
                            <th colspan="3">1000/-</th>
                            <td colspan="3"><?php echo $cp['last_action_on']; ?></td>

                        </tr>
                        <tr>
                            <th>Cust. Name</th>
                            <th>Mobile</th>
                            <th>City</th>
                            <th>Driver & Consignment</th>
                            <th scope="col">Pickup details</th>
                            <th>Order Status</th>
                            <th>Order Action</th>
                        </tr>
                        <tr>
                            <td><?php echo $cp['name']; ?></td>
                            <td><?php echo $cp['mobile']; ?></td>
                            <td><?php echo $cp['city']==''?'User city name':$cp['city']; ?></td>
                            <td>
                                <b><?php echo $drvrusrnam; ?></b>
                                <table class="table table-primary table-bordered border-white">
                                    <tr>
                                    <th>Consignment Dimension (LxBxH)</th>
                                        <th>From</th>
                                        <th>To</th>
                                    </tr>
                                    <tr>
                                        <td>120mmx230mmx50mm</td>
                                        <td>Location1</td>
                                        <td>Location2</td>
                                    </tr>
                                </table>
                            </td>
                            <td>
                                <?php //if ($cp['deliver_via']=="salesman") { 
                                ?>
                                <b>Select Driver</b>
                                <select <?php if (!is_superuser()) {
                                            echo "disabled";
                                        } ?> name="salesperson_id" class="hide form-select dlvdt<?php echo $cp['id']; ?>">
                                    <?php foreach (getSalesmanList() as $sm) { ?>
                                        <option <?php if ($cpo[0]['salesperson_id'] == $sm['id']) {
                                                    echo "selected";
                                                } ?> value="<?php echo $sm['id']; ?>"><?php echo "{$sm['first_name']} {$sm['last_name']}"; ?> (ID:<?php echo $sm['id']; ?>)</option>
                                    <?php } ?>
                                </select>
                                <ul style="list-style: none;">
                                    <li> John (john 1), accepted at : 500/- <input type="radio" name="driver" value="1"></li>
                                    <li> Haider (haider 2), accepted at : 650/- <input type="radio" name="driver" value="2"></li>
                                    <li> Jermy (jermy 3), accepted at : 300/- <input type="radio" name="driver" value="3"></li>
                                    <li> Tailor (tailor 4), accepted at : 800/- <input type="radio" name="driver" value="4"></li>
                                </ul>
                                <?php if (!is_superuser()) {
                                    echo "<input type='hidden' name='salesperson_id' value='{$cpo[0]['salesperson_id']}'>";
                                } ?>
                                <?php // } 
                                ?>

                                <b>Change pickup Date and time</b>
                                <input type="datetime-local" class="form-control dlvdt<?php echo $cp['id']; ?>" value="<?php echo $cp['delivery_date']; ?>" name="delivery_date"> <br>
                                <button id="update-delv-date-btn<?php echo $cp['id']; ?>" class="btn btn-primary">Update</button>
                                <input type="hidden" class="dlvdt<?php echo $cp['id']; ?>" name="order_id" value="<?php echo $cp['id']; ?>">
                                <?php pkAjax("#update-delv-date-btn{$cp['id']}", "/admin/orders/update-delivery-date-ajax", ".dlvdt{$cp['id']}", "#res", 'click'); ?>
                            </td>
                            <td>
                                <?php echo ucfirst($cp['order_status']); ?>
                                <select id="change_order_status_select<?php echo $cp['id']; ?>" class="form-select ds<?php echo $cp['id']; ?>" name="order_status">
                                    <option disabled>Change Status</option>
                                    <option <?php echo $cp['order_status'] == "processing" ? "selected" : null; ?> value="processing">Processing</option>
                                    <option <?php echo $cp['order_status'] == "delivered" ? "selected" : null; ?> value="delivered">Delivered</option>
                                    <option <?php echo $cp['order_status'] == "cancelled" ? "selected" : null; ?> value="cancelled">Cancelled</option>
                                    <option <?php echo $cp['order_status'] == "accepted" ? "selected" : null; ?> value="accepted">Accepted</option>
                                </select>
                                <label for="">Cancellation Reason</label>
                                <textarea style="border:1px solid red; border-radius:0;" placeholder="Please specify the reason if order status is set to be cancelled" name="cancel_info" class="form-control ds<?php echo $cp['id']; ?>"><?php echo $cp['cancel_info']; ?></textarea>
                                <input type="hidden" class="ds<?php echo $cp['id']; ?>" name="order_id" value="<?php echo $cp['id']; ?>">
                                <?php pkAjax("#change_order_status_select{$cp['id']}", "/admin/orders/change-order-status-update-ajax", ".ds{$cp['id']}", "#res", 'change'); ?>
                            </td>
                            <td>
                                <div class="d-grid">
                                    <a class="btn btn-dark" href="/<?php echo home; ?>/admin/orders/order-list/?status=<?php echo $cp['order_status']; ?>">Back</a>
                                </div>
                                <div class="d-grid my-3 hide">
                                    <!-- <a class="btn btn-success" target="_blank" href="/<?php echo home; ?>/admin/orders/print-invoice/?tid=<?php echo $cp['unique_id']; ?>">Genrate Invoice</a> -->
                                    <button class="btn btn-success">Genrate Invoice</button>
                                </div>
                            </td>

                        </tr>
                        <tr>
                            <div class="card shadow my-3">
                    <tbody>
                        <!-- <tr class="text-end">
                            <th colspan="4">Item Name</th>
                            <th>Cost/Item</th>
                            <th>Qty</th>
                        </tr> -->
                        <?php
                        /*
                        foreach ($cp['purchased_items'] as $cart) :
                            $cart_uid = uniqid('cart');
                            $cart_uid = $cart_uid . rand(0, 10000) . $cart['cart_id'];
                        ?>
                            <tr class="text-end">
                                <td colspan="4"><?php echo $cart['item_name']; ?></td>
                                <td><?php echo $cart['item_price']; ?></td>
                                <td><?php echo $cart['item_cart_qty']; ?></td>
                                <td class="hide">
                                    <select id="change_cart_status_select<?php echo $cart_uid; ?>" class="form-select cart<?php echo $cart_uid; ?>" name="status">
                                        <option <?php echo $cart['cart_status'] == "processing" ? "selected" : null; ?> value="processing">Processing</option>
                                        <option <?php echo $cart['cart_status'] == "completed" ? "selected" : null; ?> value="completed">Completed</option>
                                        <option <?php echo $cart['cart_status'] == "delivered" ? "selected" : null; ?> value="delivered">Delivered</option>
                                        <option <?php echo $cart['cart_status'] == "returned" ? "selected" : null; ?> value="returned">Returned</option>
                                        <option <?php echo $cart['cart_status'] == "restocked" ? "selected" : null; ?> value="restocked">Restocked</option>
                                    </select>
                                    <input type="hidden" class="cart<?php echo $cart_uid; ?>" name="cart_id" value="<?php echo $cart['cart_id']; ?>">
                                    <?php pkAjax("#change_cart_status_select{$cart_uid}", "/admin/orders/change-cart-status-update", ".cart{$cart_uid}", "#res", 'change') ?>
                                </td>
                                <td class="hide">
                                    <select class="form-select" name="shipping_status">
                                        <option <?php echo $cart['shipping_status'] == "pending" ? "selected" : null; ?> value="pending">Pending</option>
                                        <option <?php echo $cart['shipping_status'] == "shipped" ? "selected" : null; ?> value="shipped">Shipped</option>
                                        <option <?php echo $cart['shipping_status'] == "transit" ? "selected" : null; ?> value="transit">In Transit</option>
                                        <option <?php echo $cart['shipping_status'] == "delivered" ? "selected" : null; ?> value="delivered">Delivered</option>
                                        <option <?php echo $cart['shipping_status'] == "returned" ? "selected" : null; ?> value="returned">Returned</option>
                                    </select>
                                </td>
                            </tr>
                        <?php endforeach; */ ?>
                    </tbody>
        </div>
        </tr>

        </tbody>
    <?php endforeach; ?>

    </table>
    </div>
    </div>

</section>