<?php
	if (!empty($_REQUEST)) {
	$product_no = $_REQUEST['item_number']; 
	$product_transaction = $_REQUEST['tx']; 
	$product_price = $_REQUEST['amt'];
	$product_currency = $_REQUEST['cc'];
	$product_status = $_REQUEST['st'];

		$this->Custom->savePaymentData($product_status,$product_transaction);
	}
?>
<div id="main">
	<div id="return">
	<?php
		if ($_REQUEST['st'] == 'Completed') {
		echo "<h3 id='success'>Payment SuccessFul</h3>";
		echo "<P>Transaction Status - " . $product_status . "</P>";
		echo "<P>Transaction Id - " . $product_transaction . "</P>";
		echo "<div><a href='index.php' id='btn'><< Back</a></div>";
		} else {
		echo "<h3 id='fail'>Payment Failed</h3>";
		echo "<P>Transaction Status - Unompleted</P>";
		echo "<P>Transaction Id - " . $product_transaction . "</P>";
		}
	?>
	</div>
</div>