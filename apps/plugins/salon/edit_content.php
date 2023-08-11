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
$page = new Dbobjects();
$page->tableName = "content";
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
                                <a class="nav-link" target="_blank" href="/<?php echo home; ?>/admin/services/services-by-salon/?vendor_id=<?php echo $page['created_by']; ?>&salon_id=<?php echo $page['id']; ?>"> <i class="fa-solid fa-rectangle-list"></i> Services </a>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h3>Category</h3>
                                        <?php
                                        $catData = multilevel_categories($parent_id = 0, $radio = true); ?>
                                        <select required class="update_page form-control" name="parent_id" id="cats">
                                            <option value="0" selected>Category</option>
                                            <?php echo display_option($nested_categories = $catData, $mark = ''); ?>
                                        </select>
                                        <script>
                                            var exists = false;
                                            $('#cats option').each(function() {
                                                if (this.value == '<?php echo $page['parent_id']; ?>') {
                                                    // exists = true;
                                                    // return false;
                                                    $("#cats").val("<?php echo $page['parent_id']; ?>");
                                                }
                                            });
                                        </script>
                                    </div>
                                    <div class="col-md-4 hide">
                                        <h3>Content Type</h3>
                                        <select name="page_content_type" class="update_page form-control mb-2 mt-2">
                                            <option <?php if ($page['content_type'] == 'page') {
                                                        echo "selected";
                                                    } ?> value="page">Page</option>
                                            <option <?php if ($page['content_type'] == 'post') {
                                                        echo "selected";
                                                    } ?> value="post">Post</option>
                                            <option <?php if ($page['content_type'] == 'service') {
                                                        echo "selected";
                                                    } ?> value="service">Service</option>
                                            <option <?php if ($page['content_type'] == 'slider') {
                                                        echo "selected";
                                                    } ?> value="slider">Slider</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <h3>Status</h3>
                                        <select name="page_status" class="update_page form-control mb-2 mt-2">
                                            <option <?php if ($page['status'] == 'draft') {
                                                        echo "selected";
                                                    } ?> value="draft">Draft</option>
                                            <option <?php if ($page['status'] == 'listed') {
                                                        echo "selected";
                                                    } ?> value="listed">Listed</option>
                                            <option <?php if ($page['status'] == 'trash') {
                                                        echo "selected";
                                                    } ?> value="trash">Trash</option>
                                        </select>
                                    </div>
                                </div>

                                <h3 class="text-dark"><?php echo ucwords($content_group); ?> Name</h3>
                                <input type="text" name="page_title" class="form-control mb-2 update_page" value="<?php echo $page['title']; ?>">
                                <!-- <h1>Vendor search</h1> -->
                                <div class="form-group hide">
                                    <label for="searchSellerInput">Search:</label>
                                    <input type="text" class="form-control seller-search" id="searchSellerInput" placeholder="Search...">
                                </div>
                                <div class="form-group hide">
                                    <label for="searchDropdown">Select an option:</label>
                                    <select class="form-control" id="searchDropdown" name="searchDropdown"></select>
                                </div>

                                <?php
                                // pkAjax("#searchSellerInput", "/admin/search-seller-ajax", ".seller-search", '#searchDropdown', 'keyup');
                                ?>
                                <div class="row hide">
                                    <div class="col">
                                        <h3 class="text-dark">Seller Comapny</h3>
                                        <select name="company_id" class="form-select update_page my-3">
                                            <?php
                                            $comp = get_content_by_seler_comapny($created_by = $page['created_by']);
                                            foreach ($comp as $k => $comp) {
                                                $comp = (object) $comp;
                                            ?>
                                                <option <?php echo $page['company_id'] == $comp->id ? "selected" : null; ?> value="<?php echo $comp->id; ?>"><?php echo $comp->title; ?></option>
                                            <?php     }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <?php
                                $price_per_blk = $page['price'] * $page['bulk_qty'];
                                $dic_per_blk = $page['discount_amt'] * $page['bulk_qty'];
                                $sale_per_blk = $price_per_blk - $dic_per_blk;
                                $sale_per_blk = $sale_per_blk + round($sale_per_blk * ($page['tax'] / 100), 2);
                                ?>
                                <div class="row hide">
                                    <div class="col">
                                        <b>Price/Bulk Qty.</b>
                                        <input type="text" name="price_bulk_qty" class="form-control mb-2 update_page" value="<?php echo $price_per_blk; ?>">
                                    </div>
                                    <div class="col">
                                        <b>Disc. Amt./Bulk Q.</b>
                                        <input type="text" name="discount_amt" class="form-control mb-2 update_page" value="<?php echo $dic_per_blk; ?>">
                                    </div>
                                    <div class="col">
                                        <b>Vat %</b>
                                        <input type="text" name="tax" class="form-control mb-2 update_page" value="<?php echo $page['tax']; ?>">
                                    </div>
                                    <div class="col">
                                        <b>Sale Price</b> <br>
                                        <b>= <?php echo $sale_per_blk; ?></b>
                                    </div>
                                    <div class="col">
                                        <b>Bulk Qty to sell</b>
                                        <input type="text" name="bulk_qty" class="form-control mb-2 update_page" value="<?php echo $page['bulk_qty']; ?>">
                                    </div>

                                </div>
                                <div class="row hide">

                                    <div class="col-3">
                                        <b>Stock Qty</b>
                                        <input type="text" name="qty" class="form-control mb-2 update_page" value="<?php echo $page['qty']; ?>">
                                    </div>

                                </div>

                                <!-- <h5>Title in English </h5>
                    <input type="text" name="page_content_info" class="form-control mb-2 update_page" value="<?php //echo $page['content_info']; 
                                                                                                                ?>"> -->
                                <input type="checkbox" <?php matchData($page['show_title'], 1, "checked"); ?> name="page_show_title" class="update_page">
                                <?php matchData($page['show_title'], 0, "Check to show Page Title"); ?>
                                <?php matchData($page['show_title'], 1, "Uncheck to hide Page Title"); ?> &nbsp;
                                <a target="_blank" href='<?php echo "/" . home . "/product/?pid={$page['id']}"; ?>'>View</a> &nbsp;
                                <?php $var = "/" . home . "/page/delete/" . $page['id'];
                                $dltlink = "<a style='color: red;' href='{$var}'>Delete Page</a>";
                                matchData($page['status'], 'trash', $dltlink); ?> &nbsp;
                                <!-- <a data-bs-toggle="modal" data-bs-target="#GalleryModel">Add Image</a> -->

                                <h4>Details <i class="fas fa-arrow-down"></i></h4>
                                <textarea name="page_content" class=" form-control mb-2 update_page" rows="10"><?php echo $page['content']; ?></textarea>
                                <!-- <h4>Content in English <i class="fas fa-arrow-down"></i></h4>
                    <textarea name="page_other_content" class="tiny_textarea form-control mb-2 update_page" rows="10"><?php //echo $page['other_content']; 
                                                                                                                        ?></textarea> -->
                                <input type="text" onkeyup="createSlug('page_slug_edit', 'page_slug_edit');" id="page_slug_edit" name="slug" class="form-control mb-2 update_page" value="<?php echo $page['slug']; ?>">
                                <input type="hidden" name="page_id" class="form-control mb-2 update_page" value="<?php echo $page['id']; ?>">
                                <input type="hidden" name="update_page" class="form-control mb-2 update_page" value="update_page">
                                <h3>Packages</h3>
                                <!-- Attribute  Details -->
                                <div style="max-height: 300px; overflow-y: scroll;">

                                    <?php
                                    $isServsChecked = false;
                                    $db = new Model('content_details');
                                    $imgs  = $db->filter_index(array('content_group' => 'product_more_detail', "content_id" => $page['id'], "is_active" => 1));
                                    if ($imgs == false) {
                                        $imgs = array();
                                    }
                                    foreach ($imgs as $key => $fvl) :
                                        $ftrd = $fvl['is_featured'] ? 'checked' : null;
                                        $select_minute = $fvl['duration_unit'] == 'minute' ? 'selected' : null;
                                        $select_hr = $fvl['duration_unit'] == 'hr' ? 'selected' : null;

                                        $jsn = json_decode($fvl['json_obj']);
                                        $selected_servs = [];
                                        if (isset($jsn->services)) {
                                            $selected_servs = $jsn->services;
                                        }

                                        $plobj = new Model('content');
                                        $srvs = $plobj->filter_index(['content_group' => 'service', 'created_by' => $page['created_by'], 'company_id' => $page['id']]);
                                        $srvsssss = null;
                                        foreach ($srvs as $cnt) :
                                            $cnt = obj($cnt);
                                            $isServsChecked = false;
                                            // Check if the current item is selected
                                            foreach ($selected_servs as $servid) {
                                                if (in_array($cnt->id, array($servid))) {
                                                    $isServsChecked = true;
                                                    break;
                                                }
                                            }
                                            $srvcdhckd =  $isServsChecked ? 'checked' : null;
                                    ?>
                                            <input <?php echo $srvcdhckd;
                                                    ?> class="pointer" type="checkbox" name="services[]" value="<?php echo $cnt->id; ?>">
                                            <?php echo $cnt->title; ?> [Duration: <?php echo $cnt->duration; ?> <?php echo $cnt->duration_unit; ?>, Price: <?php echo $cnt->price; ?> (Discount: <?php echo $cnt->discount_amt; ?>)] <br>


                                        <?php
                                            $srvsssss .= <<<SRV
                                           <input $srvcdhckd class="pointer edit-more-detail{$fvl['id']}" type="checkbox" name="services[]" value="$cnt->id">$cnt->title [Duration: $cnt->duration $cnt->duration_unit, Price: $cnt->price (Discount: $cnt->discount_amt)] <br>
                                          SRV;

                                            $isServsChecked = false;
                                        endforeach;

                                        $body = <<<BDY
                                        
                                        $srvsssss
                                    <input class='edit-more-detail{$fvl['id']}' type='hidden' name='content_detail_id' value='{$fvl['id']}'>
                                    
                                    <label>Service Name</label>
                                    <input class='form-control mb-1 edit-more-detail{$fvl['id']}' type='text' name='heading' value='{$fvl['heading']}'>
                                    <div class="row hide">
                                    <div class="col-md-5">
                                            <input name="duration" value='{$fvl['duration']}' type="number" class="my-1 form-control edit-more-detail{$fvl['id']}" placeholder="Service duration">
                                        </div>
                                    <div class="col-md-2">
                                        <select name="duration_unit" class="form-control my-1 edit-more-detail{$fvl['id']}">
                                            <option $select_minute value="min">Minute</option>
                                            <option $select_hr value="hr">Hour</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input name="price" value="{$fvl['price']}" type="number" class="my-1 form-control edit-more-detail{$fvl['id']}" placeholder="Price">
                                    </div>
                                    <div class="col-md-2">
                                        <span class="my-1">DH</span>
                                    </div>
                                </div>

                            
                                <textarea class='form-control hide mb-1 edit-more-detail{$fvl['id']}' name='content'>{$fvl['content']}</textarea>
                                BDY;
                                        $ajax = pkAjax("#edit-this-detail{$fvl['id']}", "/admin/$plugin_dir/update-content-details-ajax", ".edit-more-detail{$fvl['id']}", "#res-delt", 'click', 'post', false, true);
                                        $body .= $ajax;
                                        ?>

                                        <div class="row container">
                                            <div class="col" style="background-color: lightgrey;">
                                                <table class="table table-bordered border-primary my-3">
                                                    <tr>
                                                        <th colspan="4" class="text-end">
                                                            <i data-bs-toggle="modal" data-bs-target="#more-detail<?php echo $fvl['id']; ?>" class="fas fa-edit pk-pointer"></i>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th>Package name</th>
                                                        <th class="hide">Duration</th>
                                                        <th class="hide">Price</th>
                                                        <th class="hide">Description</th>
                                                    </tr>
                                                    <tr>
                                                        <td>

                                                            <p class="title">

                                                                <?php echo $fvl['heading']; ?>
                                                            </p>
                                                        </td>
                                                        <td class="hide">
                                                            <p class="title"><?php echo "{$fvl['duration']} {$fvl['duration_unit']}"; ?></p>
                                                        </td>
                                                        <td class="hide">
                                                            <p><?php echo $fvl['price']; ?> DH</p>
                                                        </td>
                                                        <td class="hide">
                                                            <p><?php echo $fvl['content']; ?></p>
                                                        </td>
                                                    </tr>
                                                </table>

                                                <div class="text-end text-danger">
                                                    <i id="delete-this-detail<?php echo $fvl['id']; ?>" class="fas fa-trash pk-pointer"></i>
                                                    <input class="delete-data-detail<?php echo $fvl['id']; ?>" type="hidden" name="content_details_delete_id" value="<?php echo $fvl['id']; ?>">
                                                </div>
                                                <hr>
                                            </div>

                                        </div>
                                        <?php echo bsmodal("more-detail{$fvl['id']}", "Edit", $body, "edit-this-detail{$fvl['id']}", "Update", "btn btn-primary", "modal-xl"); ?>
                                        <?php pkAjax("#delete-this-detail{$fvl['id']}", "/admin/$plugin_dir/delete-content-details", ".delete-data-detail{$fvl['id']}", "#res-delt"); ?>
                                    <?php
                                        $select_minute = null;
                                        $select_hr = null;
                                    endforeach;  ?>

                                </div>
                                <form action="/<?php echo home; ?>/admin/<?php echo $plugin_dir; ?>/add-more-detail" id="add-more-detail-form">
                                    <!-- <div class="progress">
                                <div class="progress-bar"></div>
                            </div> -->
                                    <input type="hidden" name="content_id" value="<?php echo $page['id']; ?>">
                                    <input type="hidden" name="content_group" value="product_more_detail">
                                    <input name="add_more_heading" type="text" class="my-1 form-control" placeholder="Package name">
                                    <div class="row">

                                        <div class="col-md-12">
                                            <?php
                                            // $json_data = file_get_contents("./jsondata/country.json");
                                            // $countries = json_decode($json_data);
                                            $plobj = new Model('content');
                                            $srvs = $plobj->filter_index(['content_group' => 'service', 'created_by' => $page['created_by'], 'company_id' => $page['id']]);
                                            foreach ($srvs as $cnt) :
                                                $cnt = obj($cnt);
                                                $isCountryChecked = false;

                                            ?>

                                                <input class="pointer" type="checkbox" name="services[]" value="<?php echo $cnt->id; ?>">
                                                <?php echo $cnt->title; ?> [Duration: <?php echo $cnt->duration; ?> <?php echo $cnt->duration_unit; ?>, Price: <?php echo $cnt->price; ?> (Discount: <?php echo $cnt->discount_amt; ?>)] <br>
                                            <?php endforeach; ?>
                                        </div>
                                        <div class="col-md-5 hide">
                                            <input name="duration" type="number" class="form-control my-1" placeholder="Service duration">
                                        </div>
                                        <div class="col-md-2 hide">
                                            <select name="duration_unit" class="form-control my-1">
                                                <option value="min">Minute</option>
                                                <option value="hr">Hour</option>
                                            </select>
                                        </div>
                                        <div class="col-3 hide">
                                            <input name="price" type="number" class="my-1 form-control" placeholder="Price">
                                        </div>
                                        <div class="col-2 hide">
                                            <span class="my-1">DH</span>
                                        </div>
                                    </div>

                                    <!-- <input name="is_featured" type="checkbox"> <b>Is featured?</b> -->
                                    <textarea name="add_more_detail" class="form-control my-3 hide" placeholder="Descriptions">...</textarea>
                                </form>
                                <button id="add-more-detail-btn" class="btn btn-primary btn-sm my-1">Add More Detail</button>
                                <?php pkAjax_form("#add-more-detail-btn", "#add-more-detail-form", "#more-img-res", "click", true) ?>
                                <!-- Attribute end -->
                                <section>
                                    <?php
                                    $days = json_decode($page['jsn'], true);
                                    $mydays = isset($days['openings']) ? $days['openings'] : [];
                                    // myprint($mydays);

                                    $mon = (object)day_check($mydays, $day = "monday");
                                    $tue = (object)day_check($mydays, $day = "tuesday");
                                    $wed = (object)day_check($mydays, $day = "wednesday");
                                    $thu = (object)day_check($mydays, $day = "thursday");
                                    $fri = (object)day_check($mydays, $day = "friday");
                                    $sat = (object)day_check($mydays, $day = "saturday");
                                    $sun = (object)day_check($mydays, $day = "sunday");
                                    ?>
                                    <h3>Opening and Closing Times</h3>

                                    <div class="row g-3">

                                        <div class="col-md-3">
                                            <label for="monday">Monday</label>

                                            <input type="checkbox" <?php echo $mon->open_day == false ? 'checked' : null; ?> id="monday_closed" name="monday_closed" class="form-check-input update_page">
                                            <label class="form-check-label" for="monday_closed">Closed</label>
                                            <input type="time" id="monday_open" name="monday_open" value="<?php echo $mon->open_day == true ? $mon->open_time : null; ?>" class="form-control update_page" <?php echo $mon->open_day == false ? 'disabled' : null; ?>>
                                            <span class="input-group-text">to</span>
                                            <input type="time" id="monday_close" name="monday_close" value="<?php echo $mon->open_day == true ? $mon->close_time : null; ?>" class="form-control update_page" <?php echo $mon->open_day == false ? 'disabled' : null; ?>>

                                        </div>

                                        <div class="col-md-3">
                                            <label for="tuesday">Tuesday</label>

                                            <input type="checkbox" <?php echo $tue->open_day == false ? 'checked' : null; ?> id="tuesday_closed" name="tuesday_closed" class="form-check-input  update_page">
                                            <label class="form-check-label" for="tuesday_closed">Closed</label>
                                            <input type="time" id="tuesday_open" name="tuesday_open" value="<?php echo $tue->open_day == true ? $tue->open_time : null; ?>" class="form-control  update_page" <?php echo $tue->open_day == false ? 'disabled' : null; ?>>
                                            <span class="input-group-text">to</span>
                                            <input type="time" id="tuesday_close" name="tuesday_close" value="<?php echo $tue->open_day == true ? $tue->close_time : null; ?>" class="form-control  update_page" <?php echo $tue->open_day == false ? 'disabled' : null; ?>>

                                        </div>

                                        <!-- Add the same pattern for other days -->
                                        <div class="col-md-3">
                                            <label for="wednesday">Wednesday</label>

                                            <input type="checkbox" <?php echo $wed->open_day == false ? 'checked' : null; ?> id="wednesday_closed" name="wednesday_closed" class="form-check-input  update_page">
                                            <label class="form-check-label" for="wednesday_closed">Closed</label>
                                            <input type="time" id="wednesday_open" name="wednesday_open" value="<?php echo $wed->open_day == true ? $wed->open_time : null; ?>" class="form-control  update_page" <?php echo $wed->open_day == false ? 'disabled' : null; ?>>
                                            <span class="input-group-text">to</span>
                                            <input type="time" id="wednesday_close" name="wednesday_close" value="<?php echo $wed->open_day == true ? $wed->close_time : null; ?>" class="form-control  update_page" <?php echo $wed->open_day == false ? 'disabled' : null; ?>>

                                        </div>

                                        <div class="col-md-3">
                                            <label for="thursday">Thursday</label>

                                            <input type="checkbox" <?php echo $thu->open_day == false ? 'checked' : null; ?> id="thursday_closed" name="thursday_closed" class="form-check-input  update_page">
                                            <label class="form-check-label" for="thursday_closed">Closed</label>
                                            <input type="time" id="thursday_open" name="thursday_open" value="<?php echo $thu->open_day == true ? $thu->open_time : null; ?>" class="form-control  update_page" <?php echo $thu->open_day == false ? 'disabled' : null; ?>>
                                            <span class="input-group-text">to</span>
                                            <input type="time" id="thursday_close" name="thursday_close" value="<?php echo $thu->open_day == true ? $thu->close_time : null; ?>" class="form-control  update_page" <?php echo $thu->open_day == false ? 'disabled' : null; ?>>

                                        </div>

                                        <div class="col-md-3">
                                            <label for="friday">Friday</label>

                                            <input type="checkbox" <?php echo $fri->open_day == false ? 'checked' : null; ?> id="friday_closed" name="friday_closed" class="form-check-input  update_page">
                                            <label class="form-check-label" for="friday_closed">Closed</label>
                                            <input type="time" id="friday_open" name="friday_open" value="<?php echo $fri->open_day == true ? $fri->open_time : null; ?>" class="form-control  update_page" <?php echo $fri->open_day == false ? 'disabled' : null; ?>>
                                            <span class="input-group-text">to</span>
                                            <input type="time" id="friday_close" name="friday_close" value="<?php echo $fri->open_day == true ? $fri->close_time : null; ?>" class="form-control  update_page" <?php echo $fri->open_day == false ? 'disabled' : null; ?>>

                                        </div>

                                        <div class="col-md-3">
                                            <label for="saturday">Saturday</label>

                                            <input type="checkbox" <?php echo $sat->open_day == false ? 'checked' : null; ?> id="saturday_closed" name="saturday_closed" class="form-check-input  update_page">
                                            <label class="form-check-label" for="saturday_closed">Closed</label>
                                            <input type="time" id="saturday_open" name="saturday_open" value="<?php echo $sat->open_day == true ? $sat->open_time : null; ?>" class="form-control  update_page" <?php echo $sat->open_day == false ? 'disabled' : null; ?>>
                                            <span class="input-group-text">to</span>
                                            <input type="time" id="saturday_close" name="saturday_close" value="<?php echo $sat->open_day == true ? $sat->close_time : null; ?>" class="form-control  update_page" <?php echo $sat->open_day == false ? 'disabled' : null; ?>>

                                        </div>

                                        <div class="col-md-3">
                                            <label for="sunday">Sunday</label>

                                            <input type="checkbox" <?php echo $sun->open_day == false ? 'checked' : null; ?> id="sunday_closed" name="sunday_closed" class="form-check-input  update_page">
                                            <label class="form-check-label" for="sunday_closed">Closed</label>
                                            <input type="time" id="sunday_open" name="sunday_open" value="<?php echo $sun->open_day == true ? $sun->open_time : null; ?>" class="form-control  update_page" <?php echo $sun->open_day == false ? 'disabled' : null; ?>>
                                            <span class="input-group-text">to</span>
                                            <input type="time" id="sunday_close" name="sunday_close" value="<?php echo $sun->open_day == true ? $sun->close_time : null; ?>" class="form-control  update_page" <?php echo $sun->open_day == false ? 'disabled' : null; ?>>

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
                                <section id="add-review">
                                    <form id="add-review-form" action="/<?php echo home . "/admin/$plugin_dir/add-review-ajax"; ?>">
                                        <label for="">Name</label>
                                        <input type="text" name="name_of_user" class="form-control">
                                        <label for="">Rating Point</label>
                                        <select name="star_point" class="form-select">
                                            <option value="5">5</option>
                                            <option value="4">4</option>
                                            <option value="3">3</option>
                                            <option value="2">2</option>
                                            <option value="1">1</option>
                                        </select>
                                        <label for="">Review Message</label>
                                        <input type="hidden" name="salon_id" value="<?php echo $page['id']; ?>">
                                        <textarea name="review_message" class="form-control"></textarea>
                                        <button id="add-review-btn" class="btn btn-primary" type="button">Add review</button>
                                    </form>
                                    <?php pkAjax_form("#add-review-btn", "#add-review-form", "#res"); ?>
                                    <h3>Reviews by admin</h3>
                                    <table class="table table-hover" style="max-height: 200px; overflow-y:scroll;">
                                        <tr>
                                            <th>Action</th>
                                            <th>Rating Point</th>
                                            <th>Message</th>
                                            <th>Cust. Name</th>
                                        </tr>
                                        <tr style="background-color: dodgerblue; color:white;">
                                            <th colspan="10">
                                            </th>
                                        </tr>
                                        <?php
                                        // Dummy 

                                        $rvdb = new Dbobjects;
                                        $rvdb->tableName = "review";
                                        $arrv = null;
                                        $arrv['item_id'] = $page['id'];
                                        $arrv['item_group'] = 'salon';
                                        $arrv['status'] = "published";
                                        $rvdta = $rvdb->filter($arrv);

                                        foreach ($rvdta as $key => $dmrv) :
                                            $dmrv = obj($dmrv);
                                            $rtstar = showStars($rating = $dmrv->rating);
                                        ?>
                                            <tr>
                                                <td>
                                                    <input type="radio" class="remove-this-dm-review<?php echo $dmrv->id; ?>" name="dm_review_id" value="<?php echo $dmrv->id; ?>">
                                                    <button id="<?php echo "remove-this-dm-review{$dmrv->id}"; ?>" type="button" class="btn btn-danger btn-sm">Delete</a>
                                                </td>
                                                <td>
                                                    <b><?php echo $dmrv->rating . " " . $rtstar; ?></b>
                                                </td>
                                                <td><?php echo $dmrv->name; ?></td>
                                                <td><?php echo $dmrv->message; ?></td>
                                                <td class="text-end">
                                                    <?php pkAjax("#remove-this-dm-review{$dmrv->id}", "/admin/$plugin_dir/remove-this-dm-review-ajax", ".remove-this-dm-review{$dmrv->id}", "#res");
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php endforeach;

                                        ?>



                                    </table>
                                </section>
                                <section id="reviews">
                                    <h3>Reviews by users</h3>
                                    <table class="table table-hover" style="max-height: 200px; overflow-y:scroll;">
                                        <tr>
                                            <th>Action</th>
                                            <th>Rating Point</th>
                                            <th>Message</th>
                                            <th>Cust. Name</th>
                                            <th>Cust. Mobile</th>
                                            <th>Vendor Name</th>
                                            <th>Vendor Mobile</th>
                                        </tr>
                                        <tr style="background-color: dodgerblue; color:white;">
                                            <th colspan="10">
                                            </th>
                                        </tr>
                                        <?php
                                        $bkmrks = new Model('bookmarks');
                                        $reviews = $bkmrks->filter_index(['content_group' => 'star-rating', 'content_id' => $page['id']]);


                                        foreach ($reviews as $key => $pv) :
                                            $bk = obj($pv);
                                            $user = obj(getData('pk_user', $bk->user_id));

                                            $salon = obj(getData('content', $bk->content_id));
                                            $vendor = obj(getData('pk_user', $salon->created_by));
                                            $star = showStars($rating = $bk->detail);
                                        ?>
                                            <tr>
                                                <td>
                                                    <input type="radio" class="remove-this-review<?php echo $bk->id; ?>" name="review_id" value="<?php echo $bk->id; ?>">
                                                    <button id="<?php echo "remove-this-review{$bk->id}"; ?>" type="button" class="btn btn-danger btn-sm">Delete</a>
                                                </td>
                                                <td>
                                                    <b><?php echo $bk->detail . " " . $star; ?></b>
                                                </td>
                                                <td><?php echo $user->name; ?></td>
                                                <td><?php echo $bk->message; ?></td>
                                                <td><?php echo $user->mobile; ?></td>

                                                <td><?php echo $vendor->name; ?></td>
                                                <td><?php echo $vendor->mobile; ?></td>
                                                <td class="text-end">
                                                    <?php pkAjax("#remove-this-review{$bk->id}", "/admin/$plugin_dir/remove-this-review-ajax", ".remove-this-review{$bk->id}", "#res");
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php endforeach;  ?>
                                    </table>
                                   

                                </section>
                            </div>
                            <div class="col-md-4">
                                <a class="btn btn-dark mb-4" href="/<?php echo home; ?>/admin/<?php echo $plugin_dir; ?>">Back</a>
                                <form action="/<?php echo home; ?>/admin/<?php echo $plugin_dir; ?>/edit/<?php echo $page['id']; ?>" method="post" enctype="multipart/form-data">
                                    <h3>Featured Image</h3>
                                    <div class="card mb-2">
                                        <img id="banner-img" style="max-height: 200px; width: 100%; object-fit: contain;" src="/<?php echo media_root; ?>/images/pages/<?php echo $page['banner']; ?>" alt="">
                                    </div>
                                    <h4>Change Featured Image</h4>
                                    <input required id="select-banner-img" accept="image/*" type="file" name="banner" class="update_page form-control mb-2">
                                    <input type="hidden" name="update_banner" value="update_banner">
                                    <input type="hidden" name="update_banner_page_id" value="<?php echo $page['id']; ?>">
                                    <input type="hidden" name="update_banner_page_slug" value="<?php echo $page['slug']; ?>">
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-secondary">Change Image</button>
                                    </div>
                                </form>
                                <textarea class="hide update_page" id="base64-textarea" name="banner_base64"></textarea>
                                <!-- <div class="d-grid">
                    <button class="btn btn-primary mb-1" data-bs-target="#GalleryModel" data-bs-toggle="modal">Select From Gallery</button>
                    </div> -->
                                <input id="banner-input" type="text" name="page_banner" class="hide form-control mb-2 update_page" value="<?php echo $page['banner']; ?>">
                                <!-- Attribute color -->
                                <style>
                                    .related-product {
                                        max-height: 200px;
                                        overflow-y: scroll;
                                    }
                                </style>
                                <!-- <h4>Add New Color</h4>
                     <input type="text" name="color" class="form-control mb-2 update_page" placeholder="Write a product color name and update the page">
                     <h4>Available Colors -->

                                <div id="colr-loading" class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                </h4>

                                <div class="related-product">
                                    <?php
                                    ajaxActive("#colr-loading");
                                    // $color_list= json_decode($page['color_list'],true);
                                    // $clri=0;
                                    //foreach ($color_list as $clrv): 
                                    ?>
                                    <!-- <i id="colorDeeletBtn<?php //echo $clri; 
                                                                ?>" class="fas fa-trash text-danger pk-pointer"></i> 
                          <input class="color<?php //echo $clri; 
                                                ?>" type="hidden" name="color_delete" value="<?php //echo $clrv; 
                                                                                                ?>"> 
                          <input class="color<?php //echo $clri; 
                                                ?>" type="hidden" name="pid" value="<?php //echo $page['id']; 
                                                                                    ?>"> 
                          <span><?php //echo $clrv 
                                ?></span> <br> -->
                                    <?php
                                    // pkAjax("#colorDeeletBtn{$clri}","/admin/$plugin_dir/color-delete-ajax",".color{$clri}","#res");
                                    //$clri++;
                                    ///endforeach; 
                                    ?>
                                    <div id="res"></div>
                                </div>
                                <br>
                                <!-- Attribute  images -->

                                <div id="res-delt"></div>
                                <div id="more-img-res"></div>
                                <div style="max-height: 100px; overflow-y: scroll;">
                                    <hr>
                                    <ol>
                                        <?php
                                        $db = new Model('content_details');
                                        $imgs  = $db->filter_index(array('content_group' => 'product_more_img', "content_id" => $page['id'], "is_active" => 1));
                                        if ($imgs == false) {
                                            $imgs = array();
                                        }
                                        foreach ($imgs as $key => $fvl) : ?>
                                            <li>
                                                <div class="row container my-2">

                                                    <div class="col">
                                                        <img style="width: 50px; height: 50px; object-fit: cover;" src="/<?php echo media_root; ?>/images/pages/<?php echo $fvl['content']; ?>" alt="">
                                                    </div>
                                                    <div class="col my-auto">
                                                        <?php echo $fvl['color']; ?>
                                                    </div>
                                                    <div class="col text-end my-auto text-danger">
                                                        <i id="delete-this-img<?php echo $fvl['id']; ?>" class="fas fa-trash pk-pointer"></i>
                                                        <input class="delete-data<?php echo $fvl['id']; ?>" type="hidden" name="content_details_delete_id" value="<?php echo $fvl['id']; ?>">
                                                    </div>
                                                </div>
                                            </li>
                                            <?php pkAjax("#delete-this-img{$fvl['id']}", "/admin/$plugin_dir/delete-content-details", ".delete-data{$fvl['id']}", "#res-delt"); ?>
                                        <?php endforeach;  ?>
                                        </ul>
                                </div>
                                <hr>
                                <h4>Add more image </h4>
                                <form action="/<?php echo home; ?>/admin/<?php echo $plugin_dir; ?>/add-more-img" id="add-more-img-form">
                                    <div class="progress">
                                        <div class="progress-bar"></div>
                                    </div>
                                    <input type="hidden" name="content_id" value="<?php echo $page['id']; ?>">
                                    <input type="hidden" name="content_group" value="product_more_img">
                                    <label for="">Image *</label>
                                    <input accept=".jpg,.png,.jpeg" type="file" name="add_more_img" class="form-control">
                                    <!-- <label for="">Image color *</label> -->
                                    <div id="more-img-with_clr"></div>
                                    <!-- <select name="image_color" id="" class="form-select my-2">
                                <option value="">Select color</option>
                                <?php //$cls = json_decode($page['color_list'],true);
                                //foreach ($cls as $cl): 
                                ?>
                                    <option value="<?php // echo $cl 
                                                    ?>"><?php //echo $cl 
                                                        ?></option>
                                <?php ///endforeach; 
                                ?>
                            </select> -->
                                </form>
                                <button id="add-more-img-btn" class="btn btn-primary btn-sm my-1">Add More Image</button>
                                <?php pkAjax_form("#add-more-img-btn", "#add-more-img-form", "#more-img-with_clr", "click", true) ?>
                                <p><b>Vendor Name: </b><?php echo getTableRowById("pk_user", $page['created_by'])['name']; ?>
                                    <br>
                                    <b>Vendor Mobile: </b><?php echo getTableRowById("pk_user", $page['created_by'])['mobile']; ?>
                                </p>
                                <input type="text" name="page_author" class="hide form-control mb-2 update_page" value="<?php echo $page['author']; ?>">


                                <div class="my-3">
                                    <label for="">Salon Address: </label>
                                    <textarea name="address" rows="3" class="form-control update_page" placeholder="Full Address"><?php echo $page['address']; ?></textarea>
                                </div>


                                <p>Publish Date : <?php echo $page['pub_date']; ?></p>
                                <p>Update Date : <?php echo $page['update_date']; ?></p>

                                <input id="post_cat_radio" type="radio" name="cat">
                                <input id="post_cat_add" type="text" disabled=true name="post_category" class="form-control mb-2 update_page" value="<?php echo $page['post_category']; ?>">
                                <input id="post_cat_radio_select" type="radio" name="cat">
                                <select id="post_cat_select" name="post_category" class="form-control update_page">
                                    <?php $dbcat = new Mydb('content');
                                    $cts = $dbcat->filterDistinctWhr($col = "post_category", ['content_group' => 'product'], $ord = '', $limit = 100);
                                    foreach ($cts as $key => $ctsvl) : ?>
                                        <option <?php matchData($page['post_category'], $ctsvl['post_category'], 'selected'); ?> value="<?php echo $ctsvl['post_category']; ?>"><?php echo $ctsvl['post_category']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <script>
                                    var activrad = document.getElementById("post_cat_radio");
                                    var postcat = document.getElementById("post_cat_add");
                                    var postcatselect = document.getElementById("post_cat_select");
                                    var postcatselectrad = document.getElementById("post_cat_radio_select");
                                    activrad.addEventListener('click', activeElAd);
                                    postcatselectrad.addEventListener('click', activeElSel);

                                    function activeElAd() {
                                        postcat.disabled = false;
                                        postcatselect.disabled = true;
                                    }

                                    function activeElSel() {
                                        postcat.disabled = true;
                                        postcatselect.disabled = false;
                                    }
                                </script>

                                <div class="d-grid mb-5">
                                    <button id="update_page_btn" class="mt-3 btn btn-lg btn-secondary">Update</button>
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