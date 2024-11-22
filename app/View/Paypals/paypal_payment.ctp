<div id="main">
	<div id="container">
		<div id="book_container">
			<?php
			$url = array('controller' => 'Payments', 'action' => 'process_payment');
			echo $this->Form->create("Payment", array('url'=>$url, 'method' => 'post', 'inputDefaults' => array('div' => 'form-group', 'class' => 'form-control')));
			$plan_options = array('Daily' => 'Daily', 'Weekly' => 'Weekly', 'Monthly' => 'Monthly', 'Yearly' => 'Yearly');
			echo $this->Form->input('select_plan', array("options" => $plan_options, "empty" => "--select--", "type"=>"select", "id" => "select_plan"));
			echo $this->Form->input('Pay', array("type"=>"submit", "label" => false, "name" => "submit", "id" => "subscribe"));
			?>
<!-- 			<form action="https://fairobserver.staging.wpengine.com/paypal/process.php" method="POST">
				<div class="fgrow">
					<span>Select a Plan :-</span>
					<select id="select_plan" name="select_plan">
						<option value="Daily">Daily</option>
						<option value="Weekly">Weekly</option>
						<option value="Monthly">Monthly</option>
						<option value="Yearly">Yearly</option>
					</select>
				</div>
				<input type="submit" value="Pay" name="submit" id="subscribe"> -->
			</form>
		</div>
	</div>
</div>