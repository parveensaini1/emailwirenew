<?php  
if(!empty($cartdata)&&$cartdata['totals']['subtotal']>0){
$promo_code=''; 
//echo "<pre>";print_r($cartdata);die;
$total_amount=(isset($cartdata['totals']['total'])&&$cartdata['totals']['total']>0)?$cartdata['totals']['total']:"0.00"; 
$subtotal=(isset($cartdata['totals']['subtotal'])&&$cartdata['totals']['subtotal']>0)?$cartdata['totals']['subtotal']:"0.00";
$discount=(isset($cartdata['totals']['discount'])&&$cartdata['totals']['discount']>0)?$cartdata['totals']['discount']:"0.00";
$tax=(isset($cartdata['totals']['tax'])&&$cartdata['totals']['tax']>0)?$cartdata['totals']['tax']:"0.00";

?>
<section class=' '>
  <div class='box'>
<!-- <div style="display: none;"id="buy-plan-error" class="text-danger col-lg-12 buy-plan-error-msg">Your cart is empty.</div> -->
<div class="orange-border">
        <div class="ew-title-price full">
      <h3 class="card-title">Items in cart</h3>

      <!-- <div class="card-tools pull-right">
        <button type="button" class="btn btn-card-tool" data-card-widget="collapse"><i class="fa fa-minus"></i>
        </button>
      </div> -->
    </div>
    <!-- /.card-header -->
    <div class="full cart-items">
      <ul id="cartplanlist" class="products-list product-list-in-card">
        <?php 
            if(!empty($cartdata['prlist'])){
                foreach ($cartdata['prlist'] as $key => $list) {
                // $desc="<span class='product-description'>Samsung 32` 1080p 60Hz LED Smart HDTV.</span>";
                 echo "<li id='plan-".$list['plan_id']."' class='item'><div class='product-info'><div class='product-title'>".$list['title']."<span class='pull-right'>".$list['amount']."</span></div></div></li>";
                }
            }
            if(!empty($cartdata['feature'])){
                foreach ($cartdata['feature'] as $index => $feature) {
               //  $desc="<span class='product-description'>Samsung 32` 1080p 60Hz LED Smart HDTV.</span>";
                 echo "<li class='item'><div class='product-info'><div class='product-title'>".$feature['name']."<span class=' pull-right'>".$feature['price']."</span></div></div></li>";
                }
            }

        ?>

       </ul>
      <div class="ew-cart-dis-block full">
          
            <div id="cart-subtotal-box" class="full ew-cart-row">
              <span class="float-left">Subtotal : </span>
              <span id="cartsubtotal" class="float-right text-right"><?php echo $currency . $subtotal; ?></span>
            </div>
            
            <?php if ($discount > 0) { ?>
            <div id="disamount-box" class="full ew-cart-row" style="display: none;">
              <span class="float-left">Discount : </span>
              <span id="disamount" class="float-right text-right"><?php echo $currency . $discount; ?></span>
            </div>
            <?php } ?>
            <?php if ($tax > 0) { ?>
              <div id="carttax-box" class="full ew-cart-row" style="display: none;">
                <span class="float-left">Tax : </span>
                <span id="carttax" class="float-right text-right"><?php echo $currency . $tax; ?></span>
              </div>
            <?php } ?>
            <div class="full ew-cart-row">
              <span class="float-left">Total : </span>
              <span id="carttotalamout" class="float-right text-right"><?php echo $currency . $total_amount; ?></span>
            </div>
          </div>
    </div> 
</div>
</div>
</section>
<?php } ?>