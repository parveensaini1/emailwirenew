<?php
$promo_code = '';
$transdata = $transdata['TransactionPressRelease'];
if ($transdata['total'] > 0) {
  $total_amount = (isset($transdata['total']) && $transdata['total'] > 0) ? $transdata['total'] : "0.00";
  $subtotal = (isset($transdata['subtotal']) && $transdata['subtotal'] > 0) ? $transdata['subtotal'] : "0.00";
  $discount = (isset($transdata['discount']) && $transdata['discount'] > 0) ? $transdata['discount'] : "0.00";
  $tax = (isset($transdata['tax']) && $transdata['tax'] > 0) ? $transdata['tax'] : "0.00";
  $currency = Configure::read('Site.currency');
?>
  <section class='contensst'>
    <div class=''>
      <!-- <div style="display: none;"id="buy-plan-error" class="text-danger col-lg-12 buy-plan-error-msg">Your cart is empty.</div> -->
      <div class="orange-border">
        <div class="ew-title-price full">
          <h2>Transaction</h2>

          <!-- <div class="card-tools pull-right">
        <button type="button" class="btn btn-card-tool" data-card-widget="collapse"><i class="fa fa-minus"></i>
        </button>
      </div> -->
        </div>
        <!-- /.card-header -->
        <div class="full cart-items">
          <ul id="cartplanlist" class="products-list">
            <?php

            if ($transdata['extra_words'] > 0)
              echo "<li class='item'><div class='product-info'><div class='product-title'>Extra Word charges<span class='pull-right'>" . $currency . $transdata['word_amount'] . "</span></div></div></li>";

            if ($transdata['extra_category'] > 0)
              echo "<li class='item'><div class='product-info'><div class='product-title'>Extra Category charges<span class='pull-right'>" . $currency . $transdata['category_amount'] . "</span></div></div></li>";

            if ($transdata['extra_msa'] > 0)
              echo "<li class='item'><div class='product-info'><div class='product-title'>Extra MSA charges<span class='pull-right'>" . $currency . $transdata['msa_amount'] . "</span></div></div></li>";

            if ($transdata['extra_state'] > 0)
              echo "<li class='item'><div class='product-info'><div class='product-title'>Extra State charges<span class='pull-right'>" . $currency . $transdata['state_amount'] . "</span></div></div></li>";

            if (!empty($transdata['translate_charges']))
              echo "<li class='item'><div class='product-info'><div class='product-title'>Content translate charges<span class='pull-right'>" . $currency . $transdata['translation_amount'] . "</span></div></div></li>";

            $features = unserialize($transdata['distribution_ids']);
            if (!empty($features)) {
              foreach ($features as $index => $feature) {
                //$desc="<span class='product-description'>Samsung 32` 1080p 60Hz LED Smart HDTV.</span>";
                echo "<li  class='item'><div class='product-info'><div class='product-title'>" . $feature['name'] . "<span class=' pull-right'>" . $currency . $feature['price'] . "</span></div></div></li>";
              }
            }

            ?>   
          </ul>
          <div class="ew-cart-dis-block full">
          
            <div id="cart-subtotal-box" class="full ew-cart-row">
              <span class="float-left">Subtotal : </span>
              <span id="cartsubtotal" class="float-right text-right"><?php echo $currency . $subtotal; ?></span>
            </div>
            
            <?php if ($transdata['discount'] > 0) { ?>
            <div id="disamount-box" class="full ew-cart-row" style="display: none;">
              <span class="float-left">Discount : </span>
              <span id="disamount" class="float-right text-right"><?php echo $currency . $discount; ?></span>
            </div>
            <?php } ?>
            <?php if ($transdata['tax'] > 0) { ?>
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