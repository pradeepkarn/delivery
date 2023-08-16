<?php if (is_superuser()) : ?>
    <?php
    if (isset($_POST['salesman']) && intval($_POST['salesman'])) {
        $user = getData('pk_user', $_POST['salesman']);
        $delvia = null;
    } else if (isset($_POST['salesman']) && $_POST['salesman'] == 'courier') {
        $delvia = "courier";
        $user = USER;
    } else {
        $user = USER;
        $delvia = null;
    }
    $processing = paid_orders('processing', $user, $delvia);
    $accepted = paid_orders('accepted', $user, $delvia);
    $delivered = paid_orders('delivered', $user, $delvia);
    $completed = paid_orders('completed', $user, $delvia);
    $cancelled = paid_orders('cancelled', $user, $delvia);
    $returned = paid_orders('returned', $user, $delvia);

    // print_r($processing->carts);
    ?>
    <section>
        <form action="" method="post">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="my-4">
                        Order Dashboard
                    </h5>
                </div>
                <div class="col-md-4">
                    <h5 class="my-4">
                        Filter by All/Driver/Courier
                    </h5>
                    <select name="salesman" class="form-select">
                        <option value="all">All</option>
                        <option <?php if (isset($_POST['salesman']) && $_POST['salesman'] == "courier") {
                                    echo "selected";
                                } ?> value="courier">Courier</option>
                        <?php foreach (getSalesmanList() as $sm) { ?>
                            <option <?php if (isset($_POST['salesman']) && $_POST['salesman'] == $sm['id']) {
                                        echo "selected";
                                    } ?> value="<?php echo $sm['id']; ?>"><?php echo $sm['name']; ?> (ID:<?php echo $sm['id']; ?>)</option>
                        <?php } ?>

                    </select>
                </div>
                <div class="col-md-2">
                    <h5 class="my-4">
                        Filter
                    </h5>
                    <button type="submit" class="btn btn-primary">Go</button>
                </div>
            </div>
        </form>
    </section>
<?php else : ?>
    <?php
    $user = USER;
    $processing = paid_orders('processing', $user);
    $accepted = paid_orders('accepted', $user);
    $delivered = paid_orders('delivered', $user);
    $completed = paid_orders('completed', $user);
    $cancelled = paid_orders('cancelled', $user);
    $returned = paid_orders('returned', $user);
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
<?php endif; ?>


<section>

    <div class="row">
        <div class="col-md-4 stretch-card  my-2">
            <div class="card card-tale bg-secondary text-white">
                <a class="btn btn-light" href="/<?php echo home; ?>/admin/orders/order-list/?status=processing<?php if (isset($_POST['salesman'])) {
                                                                                                                    echo "&filter={$_POST['salesman']}";
                                                                                                                } ?>">Open</a>
                <div class="card-body">
                    <p class="">New Orders</p>
                    <h2><?php echo $processing->ordCount; ?> <small>Order<?php echo $processing->ordCount > 1 ? "s" : null; ?></small></h2>
                    <p>With <?php echo $processing->cartQty; ?> <small>Quantity</small></p>
                </div>
                <!-- <a class="btn btn-light" href="/<?php echo home; ?>/admin/generate-report/?status=processing">Download Report <i class="fa-solid fa-file-csv"></i> <i class="fa-solid fa-download"></i> </a> -->

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
            </div>
        </div>
        <div class="col-md-4 stretch-card  my-2">
            <div class="card card-tale bg-info text-dark">
                <a class="btn btn-light" href="/<?php echo home; ?>/admin/orders/order-list/?status=accepted<?php if (isset($_POST['salesman'])) {
                                                                                                                echo "&filter={$_POST['salesman']}";
                                                                                                            } ?>">Open</a>
                <div class="card-body">
                    <p class="">Running Orders</p>
                    <h2><?php echo $accepted->ordCount; ?> <small>Order<?php echo $accepted->ordCount > 1 ? "s" : null; ?></small></h2>
                    <p>With <?php echo $accepted->cartQty; ?> <small>Quantity</small></p>
                </div>
                <!-- <a class="btn btn-light" href="/<?php echo home; ?>/admin/generate-report/?status=accepted">Download Report <i class="fa-solid fa-file-csv"></i> <i class="fa-solid fa-download"></i> </a> -->

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
            </div>
        </div>
        <div class="col-md-4 stretch-card  my-2">
            <div class="card card-tale bg-success text-white">
                <a class="btn btn-light" href="/<?php echo home; ?>/admin/orders/order-list/?status=delivered<?php if (isset($_POST['salesman'])) {
                                                                                                                    echo "&filter={$_POST['salesman']}";
                                                                                                                } ?>">Open</a>
                <div class="card-body">
                    <p class="">Delivered</p>
                    <h2><?php echo $delivered->ordCount; ?> <small>Order<?php echo $delivered->ordCount > 1 ? "s" : null; ?></small></h2>
                    <p>With <?php echo $delivered->cartQty; ?> <small>Quantity</small></p>
                </div>
                <!-- <a class="btn btn-light" href="/<?php echo home; ?>/admin/generate-report/?status=delivered">Download Report <i class="fa-solid fa-file-csv"></i> <i class="fa-solid fa-download"></i> </a> -->

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
            </div>
        </div>
        <div class="col-md-4 stretch-card  my-2">
            <div class="card card-tale bg-warning text-dark">
                <a class="btn btn-light" href="/<?php echo home; ?>/admin/orders/order-list/?status=returened<?php if (isset($_POST['salesman'])) {
                                                                                                                    echo "&filter={$_POST['salesman']}";
                                                                                                                } ?>">Open</a>
                <div class="card-body">
                    <p class="">Returned</p>
                    <h2><?php echo $returned->ordCount; ?> <small>Order<?php echo $returned->ordCount > 1 ? "s" : null; ?></small></h2>
                    <p>With <?php echo $returned->cartQty; ?> <small>Quantity</small></p>
                </div>
                <!-- <a class="btn btn-light" href="/<?php echo home; ?>/admin/generate-report/?status=returened">Download Report <i class="fa-solid fa-file-csv"></i> <i class="fa-solid fa-download"></i> </a> -->

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

            </div>
        </div>
        <div class="col-md-4 stretch-card  my-2">
            <div class="card card-tale bg-danger text-white">
                <a class="btn btn-light" href="/<?php echo home; ?>/admin/orders/order-list/?status=cancelled<?php if (isset($_POST['salesman'])) {
                                                                                                                    echo "&filter={$_POST['salesman']}";
                                                                                                                } ?>">Open</a>
                <div class="card-body">
                    <p class="">Cancelled</p>
                    <h2><?php echo $cancelled->ordCount; ?> <small>Order<?php echo $cancelled->ordCount > 1 ? "s" : null; ?></small></h2>
                    <p>With <?php echo $cancelled->cartQty; ?> <small>Quantity</small></p>

                </div>
                <!-- <a class="btn btn-light" href="/<?php echo home; ?>/admin/generate-report/?status=cancelled">Download Report <i class="fa-solid fa-file-csv"></i> <i class="fa-solid fa-download"></i> </a> -->

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
            </div>
        </div>

        <div class="col-md-4 stretch-card  my-2">
            <div class="card card-tale bg-primary text-white">
                <a class="btn btn-light" href="/<?php echo home; ?>/admin/orders/order-list/?status=completed<?php if (isset($_POST['salesman'])) {
                                                                                                                    echo "&filter={$_POST['salesman']}";
                                                                                                                } ?>">Open</a>
                <div class="card-body">
                    <p class="">Completed</p>
                    <h2><?php echo $completed->ordCount; ?> <small>Order<?php echo $completed->ordCount > 1 ? "s" : null; ?></small></h2>
                    <p>With <?php echo $completed->cartQty; ?> <small>Quantity</small></p>
                </div>
                <!-- <a class="btn btn-light" href="/<?php echo home; ?>/admin/generate-report/?status=completed">Download Report <i class="fa-solid fa-file-csv"></i> <i class="fa-solid fa-download"></i> </a> -->

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
            </div>
        </div>

    </div>

</section>