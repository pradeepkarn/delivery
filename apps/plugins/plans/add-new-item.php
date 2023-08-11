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


                <div class="row">
                    <div class="col-md-8">
                        <div id="res"></div>
                        <form id="add-new-product-btn-form" action="/<?php echo home; ?>/admin/<?php echo $plugin_dir; ?>/add-new-item-ajax" method="POST">
                            <div class="form-group">
                                <label for="plan_name">Plan Name</label>
                                <input type="text" class="form-control" id="plan_name" name="plan_name" required>
                            </div>
                            <div class="form-group">
                                <label for="plan_price">Plan Price</label>
                                <input type="number" class="form-control" id="plan_price" name="plan_price" required>
                            </div>
                            <div class="form-group">
                                <label for="plan_description">Plan Details</label>
                                <textarea class="form-control" id="plan_details" name="plan_details" rows="3" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="plan_duration">Plan Duration(days)</label>
                                <input type="number" class="form-control" id="plan_duration" name="plan_duration" required>
                            </div>
                            <table id="data-table" class="table">
                                <thead>
                                    <tr>
                                        <th>Feature name</th>
                                        <th>Feature value</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" class="form-control" name="key[]" required></td>
                                        <td><input type="text" class="form-control" name="value[]" required></td>
                                        <td><button type="button" class="btn btn-danger disabled remove-row">Remove</button></td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button" id="add-row" class="btn btn-primary btn-sm">Add Feature</button>


                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button id="add-new-plan-btn" type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>

                    </div>

                    <script>
                        let table = document.getElementById('data-table');
                        let addRowButton = document.getElementById('add-row');

                        addRowButton.addEventListener('click', function() {
                            let newRow = table.insertRow(-1); // append new row to end of table

                            let keyCell = newRow.insertCell(0);
                            let keyInput = document.createElement('input');
                            keyInput.type = 'text';
                            keyInput.className = 'form-control';
                            keyInput.name = 'key[]';
                            keyInput.required = true;
                            keyCell.appendChild(keyInput);

                            let valueCell = newRow.insertCell(1);
                            let valueInput = document.createElement('input');
                            valueInput.type = 'text';
                            valueInput.className = 'form-control';
                            valueInput.name = 'value[]';
                            valueInput.required = true;
                            valueCell.appendChild(valueInput);

                            let actionCell = newRow.insertCell(2);
                            let removeButton = document.createElement('button');
                            removeButton.type = 'button';
                            removeButton.className = 'btn btn-danger remove-row';
                            removeButton.textContent = 'Remove';
                            actionCell.appendChild(removeButton);

                            // listen for "Remove" button click event
                            removeButton.addEventListener('click', function() {
                                table.deleteRow(newRow.rowIndex);
                            });
                        });
                    </script>
                    <div id="res"></div>
                    <?php pkAjax_form("#add-new-plan-btn", "#add-new-product-btn-form", "#res", 'click', 'post', true); ?>
                    <?php ajaxActive(".progress"); ?>


                    <!-- Gallery -->
                    <div class="modal fade" id="GalleryModel" tabindex="-1" aria-labelledby="GalleryModelLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content pk-round">
                                <div class="modal-header">
                                    <a class="btn btn-primary" target="_blank" href="/<?php echo home; ?>/gallery/upload">Upload More Image</a>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                    <div class="container">
                                        <div class="row">
                                            <?php
                                            $gldb = new Mydb('pk_media');
                                            $gal = $gldb->allData("DESC", 99999999);
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
<?php import("apps/admin/inc/footer.php"); ?>