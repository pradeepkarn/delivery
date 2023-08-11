<?php if(defined("direct_access") != 1){echo "Silenece is awesome"; return;} ?>
<?php $GLOBALS["title"] = "Home"; ?>
<?php import("apps/admin/inc/header.php"); ?>
<?php import("apps/admin/inc/nav.php"); 
$plugin_dir = "companies";
$content_group = "company";
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
.list-none li{
    font-weight: bold;
}
.menu-col{
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
                    <input type="text" placeholder="url-slug" onblur="createSlug(this.id, this.id);" id="page_slug" required  name="slug" class="form-control">
                    <input type="hidden" name="add_new_content" value="add_new_content">  
                                      
                    <div class="row">
                  
                    <div class="col-md-6">
                            <h5>Status</h5>
                        <select required class="update_page form-select" name="status" id="ststs">
                            <option selected value="listed">List</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>
                    </div>
                    <h5>Details <i class="fas fa-arrow-down"></i></h5>
                    <textarea name="content" class="tiny_textarea form-control mb-2 update_page" rows="10"></textarea>
                    
                    
                    <div class="d-grid mb-5">
                        <button id="add-new-cat-btn" class="btn btn-lg btn-secondary">Save</button>
                    </div>
                    <div class="progress">
                        <div class="progress-bar"></div>
                    </div>
                        <div id="uploadProfileImageRes"></div> 
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
<!-- user list -->
<div class="row">
                    <style>
                        .my-input:focus{
                            border: 1px solid dodgerblue;
                            /* background-color:  rgb(32,32,32); */
                            color: dodgerblue;
                            outline: none !important;
                        }
                        #search-book{
                            text-indent:10px; 
                            color: dodgerblue; 
                            border: 1px solid dodgerblue; 
                            height:50px; width:100%;
                        }
                        .s-div{
                            list-style:none; 
                            position:absolute; 
                            width:100%; 
                            display:none; 
                            height:150px; 
                            overflow-y:scroll;
                            background-color: black;
                            color: white;
                        }
                    </style>
                        <div class="col-md-12 mt-4">
                        <div class="progress my-3">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-prime" role="progressbar" aria-label="Animated striped example" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                        </div>
                        <h5>Set Registered seller</h5>
                        <small>Please let the page load completly</small>
                        <div  role="search" style="position:relative;">
                            <div class="d-flex" style="position: relative;">
                            
                            <input id="search-book" class="my-input getEmpList" type="search" placeholder="Search Seller" name="seller_keyword" aria-label="Search">
                            </div>
                            <?php pkAjax("#search-book","/admin/{$plugin_dir}/get-seller-list-ajax",".getEmpList","#divToHide",'keyup'); 
                                ajaxActive($qry=".progress");
                                ?>
                            <ul id="divToHide" class="s-div card py-3">
                            <?php 
                                $userObj = new Model('pk_user');
                                $employees = $userObj->index();
                                // $employees = $userObj->filter_index(array('user_group'=>'employee'));
                                // print_r($genres);
                                if ($employees==false) {
                                    $employees = array();
                                }
                            ?>
                            <?php foreach ($employees as $empk => $empv): ?>
                                <li id="getEmpList<?php echo $empv['id']; ?>" class="py-1 pk-pointer">
                                    <input onclick="setInSearch(`<?php echo $empv['first_name']; ?>`)"  class="update_page" type="radio" name="created_by" value="<?php echo $empv['id']; ?>"> 
                                    <?php echo $empv['first_name'] ?? $empv['first_name'] ?? "Un named"; ?> ID: <?php echo $empv['id']; ?> 
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        
                        </div>
                        </div>
                        <script>
                            function setInSearch(data){
                                var sBox = document.getElementById('search-book');
                                sBox.value = data;
                            }
                           
                                window.onload = function(){
                            var divToHide = document.getElementById('divToHide');
                            //   var searchInput = document.getElementById('search-book');
                            document.onclick = function(e){
                                if(e.target.id !== 'search-book'){
                                //element clicked wasn't the div; hide the div
                                divToHide.style.display = 'none';
                                }
                                else if(e.target.id === 'search-book'){
                                //element clicked wasn't the div; hide the div
                                divToHide.style.display = 'block';
                                }
                            };
                            };
                            </script>
                    </div>
<!-- user list end-->
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
    <?php pkAjax_form("#add-new-cat-btn","#add-new-product-btn-form","#res",'click','post',true); ?>
    <?php ajaxActive(".progress"); ?>  


<!-- Gallery -->
<div class="modal fade" id="GalleryModel" tabindex="-1" aria-labelledby="GalleryModelLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content pk-round">
      <div class="modal-header">
         <a class="btn btn-primary" target="_blank" href="/<?php echo home;?>/gallery/upload">Upload More Image</a>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      
          <div class="container">
              <div class="row">
                  <?php 
                  $gldb = new Mydb('pk_media');
                  $gal = $gldb->allData("DESC",99999999);
                  foreach ($gal as $key => $galv):
                  ?>
                  <div class="col-md-2">
                  <center>
                    <input type="hidden" value="/<?php echo media_root; ?>/images/pages/<?php echo $galv['media_file'] ;?>">
                    <img class="pk-pointer" onclick="setThisImage<?php echo $galv['id'] ;?>();" id="galr-img-<?php echo $galv['media_file'] ;?>" class="glry-img" src="/<?php echo media_root; ?>/images/pages/<?php echo $galv['media_file'] ;?>" style="width: 90%; height: 90%; object-fit:scale-down;">
               <script>
                function setThisImage<?php echo $galv['id'] ;?>() {
                   document.getElementById("banner-input").value = `<?php echo $galv['media_file'] ;?>`;
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