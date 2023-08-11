<?php $GLOBALS["title"] = "Product Details"; ?>
<?php import("apps/view/inc/header.php"); ?>
<?php import("apps/view/inc/nav.php"); ?>

<section class="my-5">
    <div class="container">
    <div id="show-cart">
    <div class="card p-2">
        <div class="row">
            
            <div class="col-md-6">
                
                <h3 class="text-center">Shipping Details</h3>
            
           <!-- Billing details -->
            <div class="row">

                <div class="col-md-6 my-2">
                <div class="form-floating">
                    <input type="text" id="fName" name="first_name" class="form-control cart-info">
                    <label for="fName">First Name *</label>
                    </div>
                </div>
                <div class="col-md-6 my-2">
                <div class="form-floating">
                    <input type="text" id="lName" name="last_name" class="form-control cart-info">
                    <label for="lName">Last Name *</label>
                    </div>
                </div>
                <div class="col-12 my-2">
                    <div class="form-floating">
                    <textarea name="my_location" class="form-control cart-info" id="myLocation" style="min-height: 100px"></textarea>
                    <label for="myLocation">Location/Landmark</label>
                    </div>
                </div>
                    <?php 
                        $json_file_cities = RPATH ."/apps/loc.json"; 
                        $cities = file_get_contents($json_file_cities); 
                        $locns = json_decode($cities);
                    ?>
                <div class="col-md-12 my-2">
                <div class="form-floating">
                <select name="country_name" class="form-select cart-info" id="cntrName" aria-label="Floating label select example">
                    <?php foreach($locns as $key => $ct) { 
                        echo "<option value='{$ct->name}'>$ct->name</option>"; ?>
                    <?php  } ?>
                </select>
                    <label for="cntrName">Country *</label>
                    </div>
                </div>

                <div class="col-md-6 my-2">
                <div class="form-floating">
                    <input type="text" id="stateName" name="state_name" class="form-control cart-info">
                    <label for="stateName">Sate/County *</label>
                    </div>
                </div>
                <div class="col-md-6 my-2">
                <div class="form-floating">
                    <input type="text" id="cityName" name="city_name" class="form-control cart-info">
                    <label for="cityName">Town/City *</label>
                    </div>
                </div>
                <div class="col-md-12 my-2">
                <div class="form-floating">
                    <input type="email" id="emailId" name="customer_email" class="form-control cart-info">
                    <label for="emailId">Email *</label>
                    </div>
                </div>
                <div class="col-md-3 my-2">
                <div class="form-floating">
                <select name="isd_code" class="form-select cart-info" id="dialCode" aria-label="Floating label select example">
                    <?php foreach($locns as $key => $ct) { 
                        echo "<option value='{$ct->dial_code}'>({$ct->code}) {$ct->dial_code}</option>"; ?>
                    <?php  } ?>
                </select>
                    <label for="dialCode">Dial Code *</label>
                    </div>
                </div>
                <div class="col-md-9 my-2">
                <div class="form-floating">
                    <input type="number" id="phoneNumber" name="mobile_number" class="form-control cart-info">
                    <label for="phoneNumber">Phone (without 0 or +) *</label>
                    </div>
                </div>
                <div class="col-md-6 my-2">
                <div class="form-floating">
                    <input type="text" id="zipCode" name="zip_code" class="form-control cart-info">
                    <label for="zipCode">ZIP/PIN *</label>
                    </div>
                </div>
                <div class="col-md-6 my-2">
                <div class="form-floating">
                    <input type="text" id="orderNote" name="order_note" class="form-control cart-info">
                    <label for="orderNote">Order Note(optional)</label>
                </div>
                </div>
            </div>
           <!-- Billing details -->
            </div>
            <div class="col-md-6 my-2">
            
                <h3 class="text-center">Details</h3>
                <!-- cart items -->
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Name</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Price/Unit</th>
                            <th class="text-end">Amount</th>
                            <th class="text-end hide">
                                Add
                            </th>
                            <th class="text-end hide">
                                Remove
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                         $total_qty = array();
                         $total_cost = array();
                        if (isset($_SESSION['cart'])) {
                            
                            // for ($i=0; $i < count($_SESSION['cart']); $i++) { 
                            //     echo $_SESSION['cart']['46']['id'] ;
                            // }
                           
                            $db = new Mydb('content');
                            foreach (array_keys($_SESSION['cart']) as $key => $value) { 
                                $id =  $_SESSION['cart'][$value]['id'];
                                $qty = $_SESSION['cart'][$value]['qty'];
                                $total_qty[] = $qty;
                                $db = new Mydb('content');
                                $prod = $db->pkData($id);
                                $sale_price = $prod['sale_price']==""?0:$prod['sale_price'];
                                $is_sale = (($prod['sale_price'])!="" && ($prod['sale_price'])>0)?true:false;
                                $net_price = $prod['price']-$sale_price;
                                $cost = $qty*$net_price;
                                $total_cost[] = $cost;
                                ?>
                           
                        <tr>
                            <td>
                                <?php echo $id; ?>
                                <input type="hidden" class="cart-info" name="item_id[]" value="<?php echo $id; ?>">
                            </td>
                            <td>
                                <?php echo $prod['title']; ?>
                            </td>
                            <td class="text-end">
                                <?php echo $qty; ?>
                                <input type="hidden" class="cart-info" name="item_qty_<?php echo $id; ?>" value="<?php echo $qty; ?>">
                            </td>
                            <td class="text-end">
                                SR <?php echo $net_price; ?>/-
                            </td>
                            <td class="text-end">
                            <?php echo $cost; ?>
                                
                            </td>
                            
                            
                            <td class="text-end hide">
                                    <button class="btn btn-warning update-cart-icon<?php echo $prod['id']; ?>" id="add-to-cart_btn<?php echo $prod['id']; ?>" type="button"> 
                                        <i class="fas fa-plus"></i>
                                    </button>
                                <script>
                                    $(document).ready(function(){
                                        $(".update-cart-icon<?php echo $prod['id']; ?>").click(function(){
                                            $.ajax({
                                                url: "/<?php echo home; ?>/cart/ajax-add-to-cart",
                                                method: "post",
                                                data: {item_id: '<?php echo $prod['id']; ?>',action: 'add_to_cart'},
                                                dataType: "html",
                                                success: function(resultValue) {
                                                    // $('#show-cart').html(resultValue)
                                                    location.reload();
                                                }
                                            });
                                        });
                                    })
                                </script>
                            </td>
                            <td class="text-end hide">
                            
                                <button class="btn btn-sm btn-danger update-cart-icon-remove<?php echo $prod['id']; ?>" id="remove-my-cart<?php echo $prod['id']; ?>" type="button">
                                    <i class="fas fa-minus"></i>
                                </button>
                              
                                <script>
                                    $(document).ready(function(){
                                        $(".update-cart-icon-remove<?php echo $prod['id']; ?>").click(function(){
                                            $.ajax({
                                                url: "/<?php echo home; ?>/cart/ajax-remove-from-cart",
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
                            </td>
                        
                        </tr>
                        
                        <?php }
                        } 
                        // $_SESSION['cart_count'] = array_sum($total_qty);
                        ?>
                        <tr class="text-end">
                            <td></td>
                            <td></td>
                            <th>= <?php echo array_sum($total_qty); ?> Nos.</th>
                            <td></td>
                            <th>= SR <?php echo array_sum($total_cost); ?>/-</th>
                            
                            <td colspan="2">
                                <!-- <div class="d-grid">
                                    <button class="btn btn-sm btn-primary update-cart-icon" id="proceed_to_checkout" type="button">
                                    Proceed to checkout <i class="fa-solid fa-arrow-right"></i>
                                    </button>
                                </div> -->
                                
                            </td>
                        </tr>
                        
                    </tbody>
                </table>
                <!-- cart items end -->
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
                                SR <?php echo array_sum($total_cost); ?>/-
                                <input type="hidden" class="cart-info" name="total_cost" value="<?php echo array_sum($total_cost); ?>">
                            </th> 
                        </tr>
                        <tr class="">
                        
                            <td colspan="2">
                                <div id="checkout-response"></div>
                                <ul class="list-none" style="line-height: 40px;">
                                    <li><input style="height: 30px; width: 30px;" class="cart-info" checked type="radio" name="payment_method" value="cod"> <span style="position: relative; top: -10px; font-size: 20px;">COD(Cash On Delivery)</span></li>
                                    <li><input style="height: 30px; width: 30px;" class="cart-info" type="radio" name="payment_method" value="rzrpay"> <span style="position: relative; top: -10px; font-size: 20px;">Razor Pay</span> </li>
                                    <li><input style="height: 30px; width: 30px;" class="cart-info" type="radio" name="payment_method" value="gpay"> <span style="position: relative; top: -10px; font-size: 20px;">Google Pay</span> </li>
                                </ul>
                                <div class="d-grid">
                                    <button class="btn btn-lg custom-btn-bg text-white update-cart-icon" id="place_order_btn" type="button">
                                        Place Order 
                                    </button>
                                    <div id="res"></div>
                                    <?php pkAjax('#place_order_btn','/checkout/ajax-place-order/','.cart-info','#res'); ?>
                                </div>
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

  
 <?php import("apps/view/inc/footer.php"); ?>

