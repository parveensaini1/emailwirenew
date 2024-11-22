<?php   
$promo_code=''; 
$transdata=$transdata['TransactionPressRelease'];
if($transdata['subtotal']>0){
$total_amount=(isset($transdata['total'])&&$transdata['total']>0)?$transdata['total']:"0.00"; 
$subtotal=(isset($transdata['subtotal'])&&$transdata['subtotal']>0)?$transdata['subtotal']:"0.00";
$discount=(isset($transdata['discount'])&&$transdata['discount']>0)?$transdata['discount']:"0.00";
$tax=(isset($transdata['tax'])&&$transdata['tax']>0)?$transdata['tax']:"0.00";

?>
<section class='content-section'>
  <div class='box'>
<!-- <div style="display: none;"id="buy-plan-error" class="text-danger col-lg-12 buy-plan-error-msg">Your cart is empty.</div> -->
<div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title">Transaction</h3>

      <!-- <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
        </button>
      </div> -->
    </div>
    <!-- /.box-header -->
    <div class="box-body">
      <ul id="cartplanlist" class="products-list product-list-in-box">
        <?php  

              if($transdata['extra_words']>0)
                 echo "<li class='item'><div class='product-info'><div class='product-title'>Extra Word charges<span class='pull-right'>$".$transdata['word_amount']."</span></div></div></li>";

              if($transdata['extra_category']>0)
                 echo "<li class='item'><div class='product-info'><div class='product-title'>Extra Category charges<span class='pull-right'>$".$transdata['category_amount']."</span></div></div></li>";

              if($transdata['extra_msa']>0)
                 echo "<li class='item'><div class='product-info'><div class='product-title'>Extra MSA charges<span class='pull-right'>$".$transdata['msa_amount']."</span></div></div></li>";

              if($transdata['extra_state']>0)
                 echo "<li class='item'><div class='product-info'><div class='product-title'>Extra State charges<span class='pull-right'>$".$transdata['state_amount']."</span></div></div></li>";
                 
              if(!empty($transdata['translate_charges']))
                 echo "<li class='item'><div class='product-info'><div class='product-title'>Content translate charges<span class='pull-right'>$".$transdata['translation_amount']."</span></div></div></li>";
                  

            $features=unserialize($transdata['distribution_ids']);

            if(!empty($features)){
                foreach ($features as $index => $feature) {
                  //$desc="<span class='product-description'>Samsung 32` 1080p 60Hz LED Smart HDTV.</span>";
                 echo "<li  class='item'><div class='product-info'><div class='product-title'>".$feature['name']."<span class=' pull-right'>$".$feature['price']."</span></div></div></li>";
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