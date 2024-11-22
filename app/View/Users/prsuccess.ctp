<div class="row">
<div class="col-sm-1"></div>
	<div class="col-sm-10">
		<section class="pr-not-found-message" style="min-height:auto; margin:10px 0;">
			<div class="alert alert-success" style="margin:0px;">
            	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            	<i class="icon fa fa-warning" aria-hidden="true"></i>
            	Your PR has been successfully submited. click here to view <?php echo $this->Html->link("Press release", array('controller' =>'users', 'action' => 'view',$data['PressRelease']['plan_id'],$prId)) ?>.
        	</div>
        </section>
	 	<?php 
			if(!empty($data['TransactionPressRelease'])){
				$transdata['TransactionPressRelease']=$data['TransactionPressRelease'];
				include_once "invoice_view.ctp";
			 	// echo $this->Custom->getPrInvoiceHtml(array_merge($transdata['TransactionPressRelease'],$tx_id,$staff_data));
			}
		?>
	</div>
</div>