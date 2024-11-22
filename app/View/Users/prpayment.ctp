<?php
if($total_amount>0){
  $paypal_url=(strip_tags(Configure::read('Site.payment.environment'))!='live')?strip_tags(Configure::read('Site.payment.sandbox.url')):strip_tags(Configure::read('Site.payment.live.url'));
  $product_name = 'Email Wire'; 
  $country='IN'; 
  $cancel_return = SITEURL."users/paymentcancel?nocache=".rand(0,1000);
  $success_return = SITEURL."users/paymentprsuccess/?crt=$cartSessionId&pr=$prId&plan=$plan_id";
  $uName=$firstName." ".$lastName;

  $product_currency =strip_tags(Configure::read('Site.Payment.currency'));  
  $merchant_email =strip_tags(Configure::read('Site.Paypal.business.email'));  
  if((Configure::read('Site.payment.environment')!='live')){
    // $merchant_email = 'sb-vv4343t28037@business.example.com';
    $merchant_email = 'testdevlopertest123-facilitator@gmail.com';
  }
  $notify_url=SITEURL.'crons/ipnhandler'; 
?> 

<form id="paypalcheckout" action= "<?php echo $paypal_url; ?>" method="post" >
  <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="business" value="<?php echo $merchant_email; ?>">
    <input type="hidden" name="item_name" value="<?php echo $product_name; ?>">
    <input type="hidden" name="item_number" value="1">
    <input type="hidden" name="amount" value="<?php echo $total_amount; ?>">
    <input type="hidden" name="custom" value="<?php echo $user_id.",".$plan_id.",".$prId.",".$cartSessionId; ?>">
    <input type="hidden" name="no_shipping" value="1">
    <input type="hidden" name="no_note" value="1">
    <input type="hidden" name="currency_code" value="<?php echo $product_currency; ?>">
    <!-- <input type="hidden" name="lc" value="<?php echo $country; ?>"> -->
    <input type="hidden" name="bn" value="PP-BuyNowBF">
    <input style="display: none;" type="image" src="https://www.paypal.com/en_AU/i/btn/btn_buynow_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
    <img alt="" border="0" src="https://www.paypal.com/en_AU/i/scr/pixel.gif" width="1" height="1">
    <input type = "hidden" name ="cancel_return" value = "<?php echo $cancel_return; ?>">
    <input type = "hidden" name ="return" value = "<?php echo $success_return; ?>">
    <input type="hidden" name="notify_url" value="<?php echo $notify_url; ?>">
    </form>
  <script type="text/javascript">
  jQuery(document).ready(function(){
      ShowLoadingIndicator(); 
      jQuery("#paypalcheckout").submit();
  });
  </script>
<?php } ?>
