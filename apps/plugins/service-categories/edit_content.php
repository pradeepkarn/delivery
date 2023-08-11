<?php if (defined("direct_access") != 1) {
    echo "Silenece is awesome";
    return;
} ?>
<?php $GLOBALS["title"] = "Home"; ?>
<?php import("apps/admin/inc/header.php"); ?>
<?php import("apps/admin/inc/nav.php");
$plugin_dir = "service-categories";
?>
<?php
$page = new Dbobjects();
$page->tableName = "service_category";
$page = $page->pk($GLOBALS['url_last_param']);
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
                        <div class="row">
                            <div class="col-md-8">


                                <h5>Service Category Name</h5>
                                <input type="text" name="page_title" class="form-control mb-2 update_page" value="<?php echo $page['name']; ?>">
                                <input type="hidden" name="update_page" class="update_page">
                                <input type="hidden" name="page_id" class="update_page" value="<?php  echo $page['id']; ?>">
                                <button id="update_page_btn" class="mt-3 btn btn-lg btn-secondary">Update</button>
                            </div>
                            <div class="col-md-4">

                                <div class="my-4 d-flex justify-content-end">
                                    <a class="btn btn-primary" href="/<?php echo home; ?>/admin/<?php echo $plugin_dir; ?>"> <i class="fas fa-arrow-left"></i> Back</a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>



                <script>
                    $(document).ready(function() {
                        $('#update_page_btn').click(function(event) {
                            event.preventDefault();
                            tinyMCE.triggerSave();
                            $.ajax({
                                url: "/<?php echo home; ?>/admin/<?php echo $plugin_dir; ?>/edit/<?php echo $page['id']; ?>/update",
                                method: "post",
                                data: $('.update_page').serializeArray(),
                                dataType: "html",
                                success: function(resultValue) {
                                    $('#alertResult').html(resultValue)
                                }
                            });
                        });
                    });
                </script>
                <div id="alertResult"></div>

                <!-- Gallery -->
                <div class="modal fade" id="GalleryModel" tabindex="-1" aria-labelledby="GalleryModelLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content pk-round">
                            <div class="modal-header">
                                <a class="btn btn-primary" target="_blank" href="/<?php echo home; ?>/gallery/upload">Upload More Image</a>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div id="result"></div>
                                <div class="container">
                                    <div class="row">
                                        <?php
                                        $gldb = new Mydb('pk_media');
                                        $gal = $gldb->allData("DESC", 999999999999);
                                        foreach ($gal as $key => $galv) :
                                        ?>
                                            <div class="col-md-2">
                                                <center>
                                                    <input type="hidden" value="/<?php echo media_root; ?>/images/pages/<?php echo $galv['media_file']; ?>">
                                                    <img class="pk-pointer" onclick="setThisImage<?php echo $galv['id']; ?>();" id="galr-img-<?php echo $galv['media_file']; ?>" class="glry-img" src="/<?php echo media_root; ?>/images/pages/<?php echo $galv['media_file']; ?>" style="width: 90%; height: 90%; object-fit:scale-down;">
                                                    <script>
                                                        function setThisImage<?php echo $galv['id']; ?>() {
                                                            document.getElementById("banner-input").value = `<?php echo $galv['media_file']; ?>`;
                                                            document.getElementById("banner-img").src = "/<?php echo media_root; ?>/images/pages/<?php echo $galv['media_file']; ?>";
                                                        }
                                                    </script>
                                                </center>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- Gallery End -->

                <!-- Main Area ends-->
            </div>
        </div>
    </div>
</section>
<script src="/<?php echo static_root; ?>/js/index.js"></script>
<?php import("apps/admin/inc/footer.php"); ?>