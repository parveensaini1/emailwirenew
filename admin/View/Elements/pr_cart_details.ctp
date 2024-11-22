<?php  
if($cartdata['totals']['subtotal']>0){
$promo_code=''; 
//echo "<pre>";print_r($cartdata);die;
$total_amount=(isset($cartdata['totals']['total'])&&$cartdata['totals']['total']>0)?$cartdata['totals']['total']:"0.00"; 
$subtotal=(isset($cartdata['totals']['subtotal'])&&$cartdata['totals']['subtotal']>0)?$cartdata['totals']['subtotal']:"0.00";
$discount=(isset($cartdata['totals']['discount'])&&$cartdata['totals']['discount']>0)?$cartdata['totals']['discount']:"0.00";
$tax=(isset($cartdata['totals']['tax'])&&$cartdata['totals']['tax']>0)?$cartdata['totals']['tax']:"0.00";

?>
<section class='content-section'>
  <div class='box'>
<!-- <div style="display: none;"id="buy-plan-error" class="text-danger col-lg-12 buy-plan-error-msg">Your cart is empty.</div> -->
<div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title">Items in cart</h3>

      <!-- <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
        </button>
      </div> -->
    </div>
    <!-- /.box-header -->
    <div class="box-body">
      <ul id="cartplanlist" class="products-list product-list-in-box">
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
                 echo "<li class='item'><div class='product-info'><div class='product-title'>".$feature['name']."<span class=' pull-right'>$".$feature['price']."</span></div></div></li>";
                }
            }

        ?>

        <li class="totals item"><div class="product-info"><div class="product-title pull-right">Subtotal : <span class="pull-right">$<?php echo $subtotal;?></span></div></div></li>
        <li class="totals item"><div class="product-info"><div class="product-title pull-right">Discount : <span class="pull-right">$<?php echo $discount;?></span></div></div></li>
        <li class="totals item"><div class="product-info"><div class="product-title pull-right">Tax : <span class="pull-right">$<?php echo $tax;?></span></div></div></li>
        <li class="totals item"><div class="product-info"><div class="product-title pull-right">Total : <span class="pull-right">$<?php echo $total_amount;?></span></div></div></li>
      </ul>
    </div> 
</div>
</div>
</section>
<?php } ?>