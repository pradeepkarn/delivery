<?php if (defined("direct_access") != 1) {
    echo "Silenece is awesome";
    return;
} ?>
<?php $GLOBALS["title"] = "Home"; ?>
<?php import("apps/admin/inc/header.php"); ?>
<?php import("apps/admin/inc/nav.php"); 
$plugin_dir = "service-categories";
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
                        <!-- <a class="btn btn-dark my-4" href="/<?php //echo home; ?>/admin/salon">
                            <i class="fas fa-arrow-left"></i> Salon
                        </a> -->
                        <div class="my-4 d-flex justify-content-end">
                            <a class="btn btn-primary" href="/<?php echo home; ?>/admin/<?php echo $plugin_dir; ?>/add-new-item"> <i class="fas fa-plus"></i> Add New Service</a>
                        </div>
                        <table id="datatablesSimple" class="table-sm table table-bordered">
                            <thead>
                                <th>ID</th>
                                <th>Name</th>
                                
                                <th>Edit</th>
                                <th>Trash</th>
                            </thead>
                            <tbody>
                                <?php

                                $db = new Model('service_category');
                          
                                $prods = $db->index();
                                if ($prods != false) {
                                    foreach ($prods as $pk => $pv) { ?>
                                        <tr>
                                            <td><?php echo $pv['id']; ?></td>
                                             <td><?php echo $pv['name']; ?></td>
                                             <td><a href="/<?php echo home; ?>/admin/<?php echo $plugin_dir; ?>/edit/<?php echo $pv['id']; ?>">Edit</a></td>
                                            <td><a data-bs-toggle="modal" data-bs-target="#deltModal<?php echo $pv['id']; ?>" href="javascript:void(0);" class="text-danger">Delete</a></td>
                                            <div class="modal" id="deltModal<?php echo $pv['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel<?php echo $pv['id']; ?>" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <h3 class="bg-danger p-3 text-white">Be careful, this action can not be un done!</h3>
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <a class="btn btn-danger" href="/<?php echo home; ?>/admin/<?php echo $plugin_dir; ?>/delete/<?php echo $pv['id']; ?>">Delete</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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