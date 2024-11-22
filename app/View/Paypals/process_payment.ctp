<?php
// echo "<pre>";
// print_r($this->data['Payment']);die;
if (isset($this->data['Payment'])) {

$product_name = 'Email Wire';
$product_currency = 'INR';
if ($this->data['Payment']['select_plan'] == 'Daily') {
$amount = 35;
$cycle = 'D';
} else if ($this->data['Payment']['select_plan'] == 'Weekly') {
$amount = 10;
$cycle = 'W';
} else if ($this->data['Payment']['select_plan'] == 'Monthly') {
$amount = 15;
$cycle = 'M';
} else if ($this->data['Payment']['select_plan'] == 'Yearly') {
$amount = 20;
$cycle = 'Y';
}
$paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
$merchant_email = 'sb-vv4343t28037@business.example.com';
$cancel_return = "http://netleon.in/email_wire/Payments/PaypalPayment";
$success_return = "http://netleon.in/email_wire/Payments/Success";

?>
<form name = "myform" action = "<?php echo $paypal_url; ?>" method = "post" target = "_top">
<input type="hidden" name="cmd" value="_xclick-subscriptions">
<input type = "hidden" name = "business" value = "<?php echo $merchant_email; ?>">
<input type="hidden" name="lc" value="IN">
<input type = "hidden" name = "item_name" value = "<?php echo $product_name; ?>">
<input type="hidden" name="no_note" value="1">
<input type="hidden" name="src" value="1">
<input type="hidden" name="a3" value="<?php echo $amount; ?>">
<input type="hidden" name="p3" value="1">
<input type="hidden" name="t3" value="<?php echo $cycle; ?>">
<input type="hidden" name="currency_code" value="<?php echo $product_currency; ?>">
<input type = "hidden" name = "cancel_return" value = "<?php echo $cancel_return; ?>">
<input type = "hidden" name = "return" value = "<?php echo $success_return; ?>">
<input type="hidden" name="bn" value="PP-SubscriptionsBF:btn_subscribeCC_LG.gif:NonHostedGuest">
</form>
<script type="text/javascript">
document.myform.submit();
</script>
<?php }
?>