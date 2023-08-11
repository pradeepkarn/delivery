<?php if (defined("direct_access") != 1) {
    echo "Silenece is awesome";
    return;
} ?>
<?php $GLOBALS["title"] = "Home"; ?>
<?php import("apps/admin/inc/header.php"); ?>
<?php import("apps/admin/inc/nav.php");
$plugin_dir = "plans";
$content_group = "plan";

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
            <div id="content-col" class="col-md-10">
                <?php import("apps/admin/pages/page-nav.php"); ?>
                <div class="row">
                    <div class="col-md-12">

                        <div class="my-4 d-flex justify-content-end">
                            <a class="btn btn-primary" href="/<?php echo home; ?>/admin/<?php echo $plugin_dir; ?>/add-new-item"> <i class="fas fa-plus"></i> Add New </a>
                        </div>
                        <table id="datatablesSimple" class="table-sm table table-bordered">
                            <thead>
                                <th>Plan ID</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Vlidity</th>
                                <th>Short detail</th>
                                <th>Edit</th>
                                <th>Trash</th>
                            </thead>
                            <tbody>
                                <?php

                                $db = new Model('plans');
                                $prods = $db->filter_index(['is_active'=>1]);
                                if ($prods != false) {
                                    foreach ($prods as $pk => $pv) {
                                        $pv = obj($pv);
                                        ?>
                                        <tr>
                                            <td><?php echo $pv->id; ?></td>
                                            <td><?php echo $pv->name; ?></td>    
                                            <td><?php echo $pv->price; ?></td>    
                                            <td><?php echo $pv->duration_days; ?></td>    
                                            <td><?php echo $pv->details; ?></td>    
                                            <td>
                                                <a href="/<?php echo home; ?>/admin/plans/edit/?planid=<?php echo $pv->id; ?>">Edit</a>
                                            </td>    
                                            <td>
                                                <a class="text-danger" href="/<?php echo home; ?>/admin/plans/move-to-trash/?planid=<?php echo $pv->id; ?>">Trash</a>
                                            </td>    
                                        </tr>
                                <?php }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php import("apps/admin/inc/footer.php"); ?>