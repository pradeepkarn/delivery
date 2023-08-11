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
            <div id="content-col" class="col-md-10 pb-5">
                <?php import("apps/admin/pages/page-nav.php"); ?>


                <div class="row">
                    <div class="col-md-12">
                        <form id="add-new-product-btn-form" action="/<?php echo home; ?>/admin/<?php echo $plugin_dir; ?>/add-new-item-ajax" method="post" enctype="multipart/form-data">
                            <div class="row">

                                <div class="col-md-8">
                                    <h3 class="text-dark">Service Category Name</h3>
                                    <input type="text" required name="page_title" placeholder="Service Name" class="form-control mb-2">
                                    
                                    <div class="d-grid mb-5">
                                        <input type="hidden" name="add_new_service_category">
                                        <button id="add-new-cat-btn" class="btn btn-lg btn-secondary">Save</button>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar"></div>
                                    </div>
                                    <div id="uploadProfileImageRes"></div>
                                </div>
                                <div class="col-md-4">

                                    <p class="bg-warning text-dark">
                                        <?php msg_ssn(); ?>
                                    </p>


                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div id="res"></div>
            <?php pkAjax_form("#add-new-cat-btn", "#add-new-product-btn-form", "#res", 'click', 'post', true); ?>
            <?php ajaxActive(".progress"); ?>


            <!-- Main Area ends-->
        </div>
    </div>
    </div>
</section>
<?php import("apps/admin/inc/footer.php"); ?>