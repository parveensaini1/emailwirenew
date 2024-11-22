<?php
$paypal_url=(strip_tags(Configure::read('Site.payment.environment'))!='live')?strip_tags(Configure::read('Site.payment.sandbox.url')):strip_tags(Configure::read('Site.payment.live.url'));
if(!empty($newsroom_signup['TransactionPlan'])){
  $plan_title = [];
  foreach ($newsroom_signup['TransactionPlan'] as $key => $value) {
    if(!in_array($value['title'], $plan_title)){
      $plan_title[] = $value['title'];
    }
  }
  $product_name = implode(', ', $plan_title);
}else{
  $product_name = 'Newsroom';
}
$product_currency =strip_tags(Configure::read('Site.Payment.currency'));  
$cancel_return = SITEURL."users/paymentcancel"; //.rand(0,1000)
$success_return = SITEURL."users/paymentsuccess/?crt=$cartSessionId";  // ?nocacherand(0,1000)
 //$country='IN'; 
$country='US'; 
$merchant_email =strip_tags(Configure::read('Site.Paypal.business.email'));  
if(strip_tags(Configure::read('Site.payment.environment'))!='live'){
  $merchant_email = 'testdevlopertest123-facilitator@gmail.com';
}
// $notify_url=SITEURL.'Payments/RecurringResponse';
$notify_url=SITEURL.'crons/ipnhandler';
$discount=(!empty($newsroom_signup['Transaction']['discount']))?$newsroom_signup['Transaction']['discount']:"0";
$subtotal=$newsroom_signup['Transaction']['subtotal']; 
if($recurring){ ?>
    <form id="paypalcheckout" action = "<?php echo $paypal_url; ?>" method = "post" target = "_top">
    <input type="hidden" name="cmd" value="_xclick-subscriptions">
    <input type="hidden" name = "business" value = "<?php echo $merchant_email; ?>">
     <input type="hidden" name="lc" value="<?php echo "US"; ?>"> No nedd local language 
    <input type="hidden" name="no_shipping" value="1">
    <input type="hidden" name = "item_name" value = "<?php echo $product_name; ?>">
    <input type="hidden" name="no_note" value="1">
    <input type="hidden" name="src" value="<?php echo "1";?>">
    
     Set the terms of the 1st trial period. 
    <input type="hidden" name="a1" value="<?php echo $total_amount; ?>">
    <input type="hidden" name="p1" value="<?php echo "1"; ?>">
    <input type="hidden" name="t1" value="<?php echo $cycle; ?>">

    <input type="hidden" name="on2" value="<?php echo "User name"; ?>">
    <input type="hidden" name="os2" value="<?php echo $first_name.' '.$last_name; ?>">
    <input type="hidden" name="custom" value="<?php echo $user_id.",".$plan_id.",".$cartSessionId; ?>">

     Set the terms of the regular subscription. 
    <input type="hidden" name="p3" value="<?php echo $cycle_period; ?>">
    <input type="hidden" name="t3" value="<?php echo $cycle; ?>">
    <input type="hidden" name="a3" value="<?php echo $subtotal; ?>">


    <input type="hidden" name="discount_amount" value="<?php echo $discount;?>">

    <input type="hidden" name="currency_code" value="<?php echo $product_currency; ?>">
    <input type="hidden" name="cancel_return" value = "<?php echo $cancel_return; ?>">
    <input type="hidden" name="return" value = "<?php echo $success_return; ?>">
    <input type="hidden" name="notify_url" value="<?php echo $notify_url; ?>">
    <input type="hidden" name="bn" value="PP-SubscriptionsBF:btn_subscribeCC_LG.gif:NonHostedGuest">
    </form>

<?php }else{ ?>
<form id="paypalcheckout" action= "<?php echo $paypal_url; ?>" method="post" >
  <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="business" value="<?php echo $merchant_email; ?>">
    <input type="hidden" name="item_name" value="<?php echo $product_name; ?>">
    <input type="hidden" name="item_number" value="1">
    <input type="hidden" name="on2" value="<?php echo "User name"; ?>">
    <input type="hidden" name="os2" value="<?php echo $first_name.' '.$last_name; ?>">
    <input type="hidden" name="amount" value="<?php echo $subtotal; ?>">
    <input type="hidden" name="no_shipping" value="1">
    <input type="hidden" name="no_note" value="1">
    <input type="hidden" name="currency_code" value="<?php echo $product_currency;?>">
     <input type="hidden" name="lc" value="<?php echo $country; ?>"> 
    
    <input type="hidden" name="discount_amount" value="<?php echo $discount;?>">

     <input type="hidden" name="bn" value="PP-BuyNowBF">
    <input style="display: none;" type="image" src="https://www.paypal.com/en_AU/i/btn/btn_buynow_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
    <img alt="" border="0" src="https://www.paypal.com/en_AU/i/scr/pixel.gif" width="1" height="1"> -->

    <input type = "hidden" name ="cancel_return" value = "<?php echo $cancel_return; ?>">
    <input type = "hidden" name ="return" value = "<?php echo $success_return; ?>">
    <input type="hidden" name="notify_url" value="<?php echo $notify_url; ?>">
    <input type="hidden" name="custom" value="<?php echo $user_id.",".$plan_id.",".$cartSessionId; ?>">

    </form>


<?php } ?>
 <!--<button id="checkout">Proceed to Payment</button> -->
  <style>
    div#ajaxLoading_content {
        position: relative;
        top: 20%;
    }
    #ajaxLoading_content i{
      font-size: 50px;
    }
    #ajaxLoading_content p{
        font-size: 20px;
        margin-top: 25px;
    }
  </style>
  <!--<script src="https://js.stripe.com/v3/"></script>-->
  <script type="text/javascript">
  jQuery(document).ready(function(){
    ShowLoadingIndicator();
    jQuery('#ajaxLoading_content p').html('Please wait while we redirecting on payment page......');
   jQuery("#paypalcheckout").submit();
    
    // var stripe = Stripe('pk_test_fK4FLd857rrVoQ8rWarTDBKW');
    // document.getElementById('checkout').addEventListener('click', function() {
    //     stripe.redirectToCheckout({
    //         lineItems: [
    //             {
    //                 priceData: {
    //                     currency: 'usd',
    //                     productData: {
    //                         name: 'Product 1',
    //                     },
    //                     unitAmount: <?php echo $total_amount; ?>, // Amount in cents (e.g., $20.00)
    //                 },
    //                 quantity: 1
    //             }
    //         ],
    //         mode: 'payment',
    //         successUrl: "<?php echo $success_return ?>"
    //     })
    //     .then(function(result){
    //         console.log(result);
    //     });
    // });
  });
  </script>

<?php
/* Braintree payment code
?>

<div class="checkout">
    <div id="ajax-loader" class="">
        <!-- <img class="text-center" src="<?php echo SITEURL; ?>img/ajax-loader.gif" alt="ajax-loader"/> -->
        <p class="text-center">Please wait while we are redirecting on payment page.</p>
    </div>
    <form style="display: none;"  method="post" id="payment-form" action="<?php echo SITEURL;?>users/payment">
        <section>
            <label for="amount"> 
                <div class="input-wrapper amount-wrapper">
                    <input id="amount" name="amount" type="hidden" value="<?php echo $total_amount;?>" >
                </div>
            </label>
            <div class="bt-drop-in-wrapper">
                <div id="bt-dropin"></div>
            </div>
        </section>
        <input id="nonce" name="payment_method_nonce" type="hidden" />
        <button id="submitbtn" class="button" type="submit"><span>Pay now</span></button>
    </form>
</div>
<script src="https://js.braintreegateway.com/web/dropin/1.16.0/js/dropin.min.js"></script>
<script>
    ShowLoadingIndicator();
    var form = document.querySelector('#payment-form');
    var client_token = "<?php echo($gateway->ClientToken()->generate()); ?>";
    braintree.dropin.create({
      authorization: client_token,
      selector: '#bt-dropin',
      paypal: {
        flow: 'vault'
      }
    }, function (createErr, instance) {
      if (createErr) {
        console.log('Create Error', createErr);
        return;
      }
      $("#payment-form").show();
      HideLoadingIndicator()
      // $("#ajax-loader").hide();
      form.addEventListener('submit', function (event) {
        event.preventDefault();
        instance.requestPaymentMethod(function (err, payload) {
          if (err) {
            console.log('Request Payment Method Error', err);
            return;
          }
          document.querySelector('#nonce').value = payload.nonce;
          ShowLoadingIndicator();
          form.submit();
            $("#submitbtn").hide();
        });
      });
    });
</script>
<?php 
*/
?>