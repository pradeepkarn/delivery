<?php if (defined("direct_access") != 1) {
    echo "Silenece is awesome";
    return;
} ?>
<?php $GLOBALS["title"] = "Home"; ?>
<?php import("apps/admin/inc/header.php"); ?>
<?php import("apps/admin/inc/nav.php");
$plugin_dir = "salon";
$content_group = "salon";
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
                    <div class="col-md-12">
                        <form id="add-new-product-btn-form" action="/<?php echo home; ?>/admin/<?php echo $plugin_dir; ?>/add-new-item-ajax" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-8">
                                    <h3 class="text-dark"><?php echo ucwords($content_group); ?> Name</h3>
                                    <input type="text" onkeyup="createSlug('page_title', 'page_slug');" id="page_title" required name="page_title" placeholder="Name" class="form-control mb-2">
                                    <input type="text" placeholder="url-slug" onblur="createSlug(this.id, this.id);" id="page_slug" required name="slug" class="form-control">
                                    <input type="hidden" name="add_new_content" value="add_new_content">
                                    <div class="row hide">
                                        <div class="col">
                                            <h3 class="text-dark">Brand Name</h3>
                                            <input type="text" name="brand" placeholder="Brand Name" class="form-control mb-2">
                                        </div>
                                        <div class="col">
                                            <h3 class="text-dark">Seller Comapny</h3>
                                            <select name="company_id" class="form-select">
                                                <?php
                                                $comp = get_content_by_seler_comapny();
                                                foreach ($comp as $k => $comp) {
                                                    $comp = (object) $comp;
                                                ?>
                                                    <option value="<?php echo $comp->id; ?>"><?php echo $comp->title; ?></option>
                                                <?php     }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row hide">
                                        <div class="col">
                                            <b>Price/Bulk Qty.</b>
                                            <input required type="text" name="price_bulk_qty" class="form-control mb-2 update_page" value="1">
                                        </div>
                                        <div class="col">
                                            <b>Disc. Amt./Bulk Qty.</b>
                                            <input type="text" name="discount_amt" class="form-control mb-2 update_page" value="0">
                                        </div>
                                        <div class="col">
                                            <b>Bulk Qty to sell</b>
                                            <input required type="text" name="bulk_qty" class="form-control mb-2 update_page" value="5">
                                        </div>
                                        <div class="col">
                                            <b>Vat %</b>
                                            <input type="text" name="tax" class="form-control mb-2 update_page" value="15">
                                        </div>

                                    </div>
                                    <div class="row hide">

                                        <div class="col-3">
                                            <b>Stock Qty</b>
                                            <input type="text" name="qty" class="form-control mb-2 update_page" value="5">
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5>Category</h5>
                                            <?php
                                            $catData = multilevel_categories($parent_id = 0, $radio = true); ?>
                                            <select required class="update_page form-select" name="parent_id" id="cats">
                                                <?php echo display_option($nested_categories = $catData, $mark = ''); ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <h5>Status</h5>
                                            <select required class="update_page form-select" name="status" id="ststs">
                                                <option selected value="listed">List</option>
                                                <option value="draft">Draft</option>
                                            </select>
                                        </div>
                                    </div>
                                    <h5>Details <i class="fas fa-arrow-down"></i></h5>
                                    <textarea name="content" class=" form-control mb-2 update_page" rows="10"></textarea>


                                    <div class="d-grid mb-5">
                                        <button id="add-new-cat-btn" class="btn btn-lg btn-secondary">Save</button>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar"></div>
                                    </div>
                                    <div id="uploadProfileImageRes"></div>

                                    <section>

                                        <h3>Opening and Closing Times</h3>

                                        <div class="row g-3">
                                            <div class="col-md-3">
                                                <label for="monday">Monday</label>

                                                <input type="checkbox" checked id="monday_closed" name="monday_closed" class="form-check-input">
                                                <label class="form-check-label" for="monday_closed">Closed</label>
                                                <input type="time" id="monday_open" name="monday_open" class="form-control" disabled>
                                                <span class="input-group-text">to</span>
                                                <input type="time" id="monday_close" name="monday_close" class="form-control" disabled>

                                            </div>

                                            <div class="col-md-3">
                                                <label for="tuesday">Tuesday</label>

                                                <input type="checkbox" checked id="tuesday_closed" name="tuesday_closed" class="form-check-input">
                                                <label class="form-check-label" for="tuesday_closed">Closed</label>
                                                <input type="time" id="tuesday_open" name="tuesday_open" class="form-control" disabled>
                                                <span class="input-group-text">to</span>
                                                <input type="time" id="tuesday_close" name="tuesday_close" class="form-control" disabled>

                                            </div>

                                            <!-- Add the same pattern for other days -->
                                            <div class="col-md-3">
                                                <label for="wednesday">Wednesday</label>

                                                <input type="checkbox" checked id="wednesday_closed" name="wednesday_closed" class="form-check-input">
                                                <label class="form-check-label" for="wednesday_closed">Closed</label>
                                                <input type="time" id="wednesday_open" name="wednesday_open" class="form-control" disabled>
                                                <span class="input-group-text">to</span>
                                                <input type="time" id="wednesday_close" name="wednesday_close" class="form-control" disabled>

                                            </div>

                                            <div class="col-md-3">
                                                <label for="thursday">Thursday</label>

                                                <input type="checkbox" checked id="thursday_closed" name="thursday_closed" class="form-check-input">
                                                <label class="form-check-label" for="thursday_closed">Closed</label>
                                                <input type="time" id="thursday_open" name="thursday_open" class="form-control" disabled>
                                                <span class="input-group-text">to</span>
                                                <input type="time" id="thursday_close" name="thursday_close" class="form-control" disabled>

                                            </div>

                                            <div class="col-md-3">
                                                <label for="friday">Friday</label>

                                                <input type="checkbox" checked id="friday_closed" name="friday_closed" class="form-check-input">
                                                <label class="form-check-label" for="friday_closed">Closed</label>
                                                <input type="time" id="friday_open" name="friday_open" class="form-control" disabled>
                                                <span class="input-group-text">to</span>
                                                <input type="time" id="friday_close" name="friday_close" class="form-control" disabled>

                                            </div>

                                            <div class="col-md-3">
                                                <label for="saturday">Saturday</label>

                                                <input type="checkbox" checked id="saturday_closed" name="saturday_closed" class="form-check-input">
                                                <label class="form-check-label" for="saturday_closed">Closed</label>
                                                <input type="time" id="saturday_open" name="saturday_open" class="form-control" disabled>
                                                <span class="input-group-text">to</span>
                                                <input type="time" id="saturday_close" name="saturday_close" class="form-control" disabled>

                                            </div>

                                            <div class="col-md-3">
                                                <label for="sunday">Sunday</label>

                                                <input type="checkbox" checked id="sunday_closed" name="sunday_closed" class="form-check-input">
                                                <label class="form-check-label" for="sunday_closed">Closed</label>
                                                <input type="time" id="sunday_open" name="sunday_open" class="form-control" disabled>
                                                <span class="input-group-text">to</span>
                                                <input type="time" id="sunday_close" name="sunday_close" class="form-control" disabled>

                                            </div>
                                        </div>




                                        <script>
                                            // JavaScript to enable/disable time fields based on the checkbox
                                            document.addEventListener('DOMContentLoaded', function() {
                                                const days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

                                                days.forEach(day => {
                                                    const closedCheckbox = document.getElementById(`${day}_closed`);
                                                    const openTimeInput = document.getElementById(`${day}_open`);
                                                    const closeTimeInput = document.getElementById(`${day}_close`);

                                                    closedCheckbox.addEventListener('change', function() {
                                                        if (closedCheckbox.checked) {
                                                            openTimeInput.disabled = true;
                                                            closeTimeInput.disabled = true;
                                                        } else {
                                                            openTimeInput.disabled = false;
                                                            closeTimeInput.disabled = false;
                                                        }
                                                    });
                                                });
                                            });
                                        </script>

                                    </section>
                                </div>
                                <div class="col-md-4">
                                    <a class="btn btn-dark mb-4" href="/<?php echo home; ?>/admin/<?php echo $plugin_dir; ?>">Back</a>
                                    <h3>Featured Image</h3>
                                    <div class="card mb-2">
                                        <img id="banner-img" style="max-height: 200px; width: 100%; object-fit: contain;" src="/<?php echo media_root; ?>/images/pages/page.png" alt="">
                                    </div>
                                    <input required id="selectImageBtn" accept="image/*" type="file" name="banner" class="form-control mb-2">

                                    <p class="bg-warning text-dark">
                                        <?php msg_ssn(); ?>
                                    </p>
                                    <style>
                                        .related-product {
                                            max-height: 200px;
                                            overflow-y: scroll;
                                        }
                                    </style>
                                    <!-- <h4>Select Related Product</h4> -->
                                    <div class="related-product hide">
                                        <?php
                                        $prodsObj = new Model('content');
                                        $arr = null;
                                        $arr['content_group'] = $content_group;
                                        $allprods = $prodsObj->filter_index($arr);
                                        foreach ($allprods as $k => $pv) : ?>
                                            <img style="height: 80px; width: 80px; object-fit:contain;" src="/<?php echo MEDIA_URL; ?>/images/pages/<?php echo $pv['banner']; ?>" alt="<?php echo $pv['title']; ?>">
                                            <input style="height: 20px; width: 20px;" type="checkbox" class="update_page" name="related_product_id[]" value="<?php echo $pv['id']; ?>"> <?php echo $pv['title']; ?> <br>
                                        <?php endforeach; ?>
                                    </div>
                                    <br>
                                    <!-- <h4>Product Group <i data-bs-toggle="modal" data-bs-target="#grp_id" class="fas fa-plus pk-pointer"></i> </h4> -->
                                    <?php
                                    $bdy = "<input type='text' name'grp_name' class='form-control'>";
                                    echo bsmodal('grp_id', 'Group Name', $bdy, 'save-grp', "Save", "btn btn-primary", "modal-md");
                                    ?>
                                    <div class="my-3">
                                        <label for="">Salon Address: </label>
                                        <textarea name="address" rows="3" class="form-control" placeholder="Full Address"></textarea>
                                        <!-- <label for="">Latitude</label>
                                        <input type="number" name="lat" class="form-control my-2" placeholder="Lattitude">
                                        <label for="">Longitude</label>
                                        <input type="number" name="long" class="form-control my-2" placeholder="Longitude"> -->
                                    </div>
                                    <div class="col-md-12">
                                        <style>
                                            .my-input:focus {
                                                border: 1px solid dodgerblue;
                                                /* background-color:  rgb(32,32,32); */
                                                color: dodgerblue;
                                                outline: none !important;
                                            }

                                            #search-book {
                                                text-indent: 10px;
                                                color: dodgerblue;
                                                border: 1px solid dodgerblue;
                                                height: 50px;
                                                width: 100%;
                                            }

                                            /* .s-div{
                                    list-style:none; 
                                    position:absolute; 
                                    width:100%; 
                                    display:none; 
                                    height:300px; 
                                    overflow-y:scroll;
                                    background-color: black;
                                    color: white;
                                } */
                                        </style>
                                        <div id="res-usr"></div>
                                        <!-- <input checked type="radio" name="vendor_id" value="1"> <b>Default vendor is admin</b> -->
                                        <div style="position: relative;">
                                            <label for="">Search Vendor mobile number</label>
                                            <input id="search-book" class="edit-this-coupon my-input getEmpList" type="search" name="code" value="">
                                            <input type="hidden" name="ignore_id" class="getEmpList" value="<?php echo $cp['id']; ?>">
                                            <div role="search" style="position:relative;">
                                            </div>
                                            <?php pkAjax("#search-book", "/admin/$plugin_dir/get-user-list-ajax", ".getEmpList", "#res-usr", 'keyup');
                                            ajaxActive($qry = ".progress");
                                            ?>
                                        </div>
                                    </div>




                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>




            <script>
                // function selectImagee(btnId,inputfileId) {
                //   var btnId = document.getElementById(btnId);
                //   var inputfileId = document.getElementById(inputfileId);
                //   btnId.addEventListener('click',()=>{
                //     inputfileId.click();
                //   });
                // }
                // selectImagee("selectImageBtn","banner-img");
            </script>
            <div id="res"></div>
            <?php pkAjax_form("#add-new-cat-btn", "#add-new-product-btn-form", "#res", 'click', 'post', true); ?>
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