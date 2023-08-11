<?php $GLOBALS["title"] = "Checkout"; ?>
<?php import("apps/view/inc/header.php"); ?>

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
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $mycart = new Model('customer_order');
                        $mycart = $mycart->filter_index(array('customer_email'=>$_SESSION['customer_email'],'status'=>'hold'));
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
                                JOD <?php echo $cart['price']; ?>/-
                            </td>
                            <td class="text-end">
                            <?php echo $cost; ?>
                            </td>
                        
                        </tr>
                        
                        <?php } ?>
                        <tr class="text-end">
                            <td></td>
                            <td></td>
                            <th>= <?php echo array_sum($total_qty); ?> Nos.</th>
                            <td></td>
                            <th>= JOD <?php echo array_sum($total_cost); ?>/-</th>
                         
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
           <div class="col-md-8"></div>
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
                                     
                                    <div id="qty-aval-response"></div>
                                        <button class="btn btn-lg btn-success update-cart-icon" id="proceed_to_confirm" type="button">
                                                Confirm
                                        </button>
                                        <button class="btn btn-lg btn-basic update-cart-icon" id="proceed_to_cancel" type="button">
                                                Cancel
                                        </button>
                                        <input type="hidden" class="cancel-payment" name="payment_id" value="<?php echo isset($_SESSION['payment_id'])?$_SESSION['payment_id']:""; ?>">
                                        <input type="hidden" class="confirm-payment" name="payment_id" value="<?php echo isset($_SESSION['payment_id'])?$_SESSION['payment_id']:""; ?>">
                                        <script>
                                            $(document).ready(function(){
                                                $("#proceed_to_confirm").click(function(){
                                                    $.ajax({
                                                        url: "/<?php echo home; ?>/checkout/proceed-to-confirm",
                                                        method: "post",
                                                        data: $(".confirm-payment").serializeArray(),
                                                        dataType: "html",
                                                        success: function(resultValue) {
                                                            $('#qty-aval-response').html(resultValue)
                                                            //location.reload();
                                                            
                                                        }
                                                    });
                                                });
                                            });
                                           
                                            </script>
                                            <script>
                                            $(document).ready(function(){
                                                $("#proceed_to_cancel").click(function(){
                                                    $.ajax({
                                                        url: "/<?php echo home; ?>/checkout/proceed-to-cancel",
                                                        method: "post",
                                                        data: $(".cancel-payment").serializeArray(),
                                                        dataType: "html",
                                                        success: function(resultValue) {
                                                            $('#qty-aval-response').html(resultValue)
                                                            //location.reload();
                                                            
                                                        }
                                                    });
                                                });
                                            });
                                           
                                            </script>
                                
                                   
                                    
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
