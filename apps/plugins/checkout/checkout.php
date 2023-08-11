<?php $GLOBALS["title"] = "Checkout"; ?>
<?php import("apps/view/inc/header.php"); ?>
<?php 
// if (isset($_SESSION['cart'])) {
//     $db = new Mydb('content');
//     $mycart = new Model('my_order');
//     $mycart = $mycart->filter_index(array('user_id'=>$_SESSION['user_id'],'status'=>'cart'));
//     $total_qty = array();
//     $total_cost = array();
//     foreach ($mycart as $key => $value) :
//         $ordarr_arr = null;
//         $id =  $value['item_id'];
//         $qty =  $value['qty'];
//         $total_qty[] = $qty;
//         $db = new Model('content');
//         $prods = $db->show($id);
//         $cost_ssn = $qty*$prods['price'];
//         $total_cost_ssn[] = $cost_ssn;
//         $orderssn = new Model('my_order');
//         $ordarr_arr['user_id'] = $_SESSION['user_id'];
//         $ordarr_arr['item_id'] = $id;
//         $ordarr_arr['status'] = 'cart';
//         $item_available = $orderssn->filter_index($ordarr_arr);
//         $ordarr_arr['qty'] = $qty;
//         $ordarr_arr['price'] = $prods['price'];
//         if (count($item_available)==0) {
//             $orderssn->store($ordarr_arr);
//         }        
//     endforeach;
//     unset($_SESSION['cart']);
//     $ordarr_arr = null;
// }

?>
<?php import("apps/view/inc/nav.php"); ?>

<section class="my-5">
    <div class="container">
    <div id="show-cart">
    <div class="card p-2">
        <div class="row">
            
            <div class="col-md-12">
                
                <h3 class="text-center">Checkout</h3>
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Name</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Price/Unit</th>
                            <th class="text-end">Amount</th>
                            <th class="text-end">
                                Add
                            </th>
                            <th class="text-end">
                                Remove
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        // if (get_cart_items($status="cart")!=0) {
                        //     $carts = get_cart_items($status="cart");
                        // }
                        // else if (get_cart_items($status="hold")!=0){
                        //     $carts = get_cart_items($status="hold");
                        // }
                        //  else {
                        //      $home = home;
                        //     echo "<script>location.href='/{$home}/categories';</script>";
                        //     return;
                        //   }
                        $mycart = new Model('my_order');
                        $mycart = $mycart->filter_index(array('user_id'=>$_SESSION['user_id'],'status'=>'cart'));
                        $total_qty = array();
                        $total_cost = array();
                        // foreach ($mycart as $key => $value) :
                            if ($mycart==false) {
                                $mycart = array();
                            }
                            foreach ($mycart as $key => $cart) { 
                                $id =  $cart['item_id'];
                                $qty = $cart['qty'];
                                $total_qty[] = $qty;
                                $cost = $qty*$cart['price'];
                                $total_cost[] = $cost;
                                $db = new Model('content');
                                $prod = $db->show($id);
                                ?>
                        <tr>
                            <td>
                                <?php echo $id; ?>
                            </td>
                            <td>
                                <?php echo $prod['title']; ?>
                            </td>
                            <td class="text-end">
                                <?php echo $qty; ?>
                            </td>
                            <td class="text-end">
                                JOD <?php echo $prod['price']; ?>/-
                            </td>
                            <td class="text-end">
                            <?php echo $cost; ?>
                            </td>
                            
                            
                            <td class="text-end">
                                <?php if ($cart['status']=='cart') { ?>
                                    <button class="btn btn-warning update-cart-icon<?php echo $prod['id']; ?>" id="add-to-cart_btn<?php echo $prod['id']; ?>" type="button"> 
                                        <i class="fas fa-plus"></i>
                                    </button>
                              
                                <script>
                                    $(document).ready(function(){
                                        $(".update-cart-icon<?php echo $prod['id']; ?>").click(function(){
                                            $.ajax({
                                                url: "/<?php echo home; ?>/cart/ajax-add-to-cart/",
                                                method: "post",
                                                data: {item_id: '<?php echo $prod['id']; ?>',action: 'add_to_cart_checkout'},
                                                dataType: "html",
                                                success: function(resultValue) {
                                                    // $('#show-cart').html(resultValue)
                                                    location.reload();
                                                }
                                            });
                                        });
                                    })
                                </script>
                                <?php }
                                else{
                                    echo "Item Hold";
                                }
                                ?>
                            </td>
                            <td class="text-end">
                            <?php if ($cart['status']=='cart') { ?>
                                    <button class="btn btn-sm btn-danger update-cart-icon-remove<?php echo $prod['id']; ?>" id="remove-my-cart<?php echo $prod['id']; ?>" type="button">
                                        <i class="fas fa-minus"></i>
                                    </button>
                               
                                <script>
                                    $(document).ready(function(){
                                        $(".update-cart-icon-remove<?php echo $prod['id']; ?>").click(function(){
                                            $.ajax({
                                                url: "/<?php echo home; ?>/cart/ajax-remove-from-cart/",
                                                method: "post",
                                                data: {item_id: '<?php echo $prod['id']; ?>'},
                                                dataType: "html",
                                                success: function(resultValue) {
                                                    // $('#show-cart').html(resultValue)
                                                    location.reload();
                                                }
                                            });
                                        });
                                    })
                                </script>
                                <?php }
                                else{
                                    echo "Item Hold";
                                }
                                ?>
                            </td>
                        
                        </tr>
                        
                        <?php } ?>
                        <tr class="text-end">
                            <td></td>
                            <td></td>
                            <th>= <?php echo array_sum($total_qty); ?> Nos.</th>
                            <td></td>
                            <th>= JOD <?php echo array_sum($total_cost); ?>/-</th>
                            <td colspan="2">
                                <!-- <div class="d-grid">
                                <button class="btn btn-sm btn-warning update-cart-icon" id="proceed_to_hold" type="button">
                                        Hold Products
                                </button>
                                </div> -->
                                
                            </td>
                        </tr>
                        
                    </tbody>
                </table>
        
            </div>
            
            </div>
        </div>
        </div>

        

            </div>
</section>
<section class="pt-3">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
            <div class="row">
                        <div class="div-col-12">
                        <div class="card p-2">
                            <ul class="list-none">
                            <?php $adr = getAddress($_SESSION['user_id']); 
                            if ($adr!=false):
                                $i = 1;
                            foreach ($adr as $key => $av) : ?>
                            <li> 
                                <h4 class="py-2">
                                    <input <?php if($i==1){ echo "checked"; } ?> class="cust-rad select-address" type="radio" name="address_id" value="<?php echo $av['id']; ?>"> 
                                    Delivery Address
                                </h4> 
                            </li>
                                <li>Locality : <?php echo $av['locality']; ?></li>
                                <li>City : <?php echo $av['city']; ?></li>
                                <li>State : <?php echo $av['state']; ?></li>
                                <li>Country : <?php echo $av['country']; ?></li>
                                <li>Zip Code : <?php echo $av['zipcode']; ?></li>
                            <?php $i++;
                                endforeach; 
                            endif; ?>
                            </ul>
                            <?php if (authenticate() == true): 
                                    $_SESSION['from_checkout_page'] = "/". home . "/checkout"; 
                            endif; 
                                ?>
                            <a href="/<?php echo home; ?>/dashboard/my-profile" class="btn btn-sm custom-bg text-white col-md-3">
                                Add New Address
                            </a>
                            </div>
                        </div>
                </div>
                <div class="row">
               
                    
                </div>
            </div>
            <div class="col-md-4">
            
            <h3 class="text-center card">Details</h3>
                <table class="table table-bordered border border-primary">
                    <tbody>
                        <tr class="text-end">
                            <th>
                                Total Unique Item = 
                            </th> 
                            <td>
                            <?php echo count($total_qty); ?> Nos.
                            </td> 
                        </tr>
                        <tr class="text-end">
                            <th>
                                Total Quantity = 
                            </th> 
                            <td>
                                <?php echo array_sum($total_qty); ?> Nos.
                            </td> 
                        </tr>
                        <tr class="text-end">
                            <th>
                                Total Amount = 
                            </th> 
                            <th class="bg-warning">
                                JOD <?php echo array_sum($total_cost); ?>/-
                            </th> 
                        </tr>
                       
                        <tr class="text-end">
                        
                            <td colspan="2">
                                <div id="checkout-response"></div>
                                <div class="d-grid">
                                    <?php if (authenticate() == false): ?>
                                    <a class="btn btn-sm btn-primary update-cart-icon" href="/<?php echo home; ?>/account/login" target="_blank">
                                        Please Login or Register <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                    <?php else: ?>
                                     
                                        <div id="qty-aval-response"></div>
                                        
                                        <button class="btn btn-lg btn-warning update-cart-icon" id="proceed_to_hold" type="button">
                                                Continue
                                        </button>
                                        <script>
                                            $(document).ready(function(){
                                                $("#proceed_to_hold").click(function(){
                                                    $.ajax({
                                                        url: "/<?php echo home; ?>/checkout/proceed-to-hold",
                                                        method: "post",
                                                        data: $(".select-address").serializeArray(),
                                                        dataType: "html",
                                                        success: function(resultValue) {
                                                            $('#qty-aval-response').html(resultValue)
                                                            //location.reload();
                                                            
                                                        }
                                                    });
                                                });
                                            });
                                           
                                            </script>
                                        <!-- <script>
                                            $(document).ready(function(){
                                                $("#proceed_to_hold").click(function(){
                                                    $.ajax({
                                                        url: "/<?php // echo home; ?>/proceed-to-hold",
                                                        method: "post",
                                                        data: $("#payment_method").serializeArray(),
                                                        dataType: "html",
                                                        success: function(resultValue) {
                                                            $('#qty-aval-response').html(resultValue)
                                                            location.reload();
                                                            <?php // $home = home;
                                                           // echo "location.href='/{$home}/pay';"
                                                            ?>
                                                        }
                                                    });
                                                });
                                            });
                                           
                                            </script> -->
                                    <?php endif; ?>
                                    
                                </div>
                            </td> 
                        </tr>
                        
                    </tbody>
                </table>
            </div>
           
        </div>
    </div>
</section>

 <?php import("apps/view/inc/footer.php"); ?>
