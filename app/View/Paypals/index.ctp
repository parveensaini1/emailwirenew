<?php
$paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
$cancel_return = "http://netleon.in/email_wire/payments/PaypalPayment";
$success_return = "http://netleon.in/email_wire/payments/Success";
$paypalUrl='https://www.sandbox.paypal.com/cgi-bin/webscr';
$paypalId = 'sb-vv4343t28037@business.example.com';
?>
 
<div class="container text-center">
	<br/>
	<h2><strong>PHP - Paypal Payment Gateway Integration</strong></h2>
	<br/>
	<div class="row">
		<div class="col-xs-6 col-sm-6 col-md-3 col-md-offset-4 col-lg-3">
		
			<!-- PRICE ITEM -->
    			<form action="<?php echo $paypalUrl; ?>" method="post" name="frmPayPal1">
					<div class="panel price panel-red">
						    <input type="hidden" name="business" value="<?php echo $paypalId; ?>">
						    <input type="hidden" name="cmd" value="_xclick">
						    <input type="hidden" name="item_name" value="It Solution Stuff">
						    <input type="hidden" name="item_number" value="2">
						    <input type="hidden" name="amount" value="20">
						    <input type="hidden" name="no_shipping" value="1">
						    <input type="hidden" name="currency_code" value="USD">
						    <input type="hidden" name="cancel_return" value="<?php echo $cancel_return; ?>">
						    <input type="hidden" name="return" value="<?php echo $success_return; ?>">  
						    
						<div class="panel-heading  text-center">
						<h3>PRO PLAN</h3>
						</div>
						<div class="panel-body text-center">
							<p class="lead" style="font-size:40px"><strong>$20 / month</strong></p>
						</div>
						<ul class="list-group list-group-flush text-center">
							<li class="list-group-item"><i class="icon-ok text-danger"></i> Personal use</li>
							<li class="list-group-item"><i class="icon-ok text-danger"></i> Unlimited projects</li>
							<li class="list-group-item"><i class="icon-ok text-danger"></i> 27/7 support</li>
						</ul>
						<div class="panel-footer">
							<button class="btn btn-lg btn-block btn-danger" href="#">BUY NOW!</button>
						</div>
					</div>
    			</form>
			<!-- /PRICE ITEM -->
			
		</div>
	</div>
</div>