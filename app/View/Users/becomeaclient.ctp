<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <!-- /.panel-heading -->
            <div class="panel-body"> 
            	<!-- <h2>Become a PR Firm or Client</h2> -->
                <h2>Become a Client</h2>
			    <p>If you want to publish a press release on <?php echo $siteName;?>, you need to migrate your account from subscriber to client. Click on link below to migrate your account. Once you migrate your account, you will not be able to revert. </p>
			    <a class="btn btn-success" href="javascript:void(0)" onclick="submitform();" >Migrate Account</a>
			<?php 
			   echo $this->Form->create('StaffUser', array('type' => 'post'));
			   echo $this->Form->end();
			?>

			<script type="text/javascript">
				function submitform(){ 
					$('#AjaxLoading').show();
					$('form#StaffUserBecomeaclientForm').submit();
				}
			</script>
            </div>
        </div>
    </div>
</div>
