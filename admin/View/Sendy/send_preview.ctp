<?php 
  echo $this->Html->script(array('jquery.validate.min','additional-methods.min'));

?>
<?php  include 'submenu.ctp'; ?>
<div class="row">
    <div class="col-lg-12">
        <div class="card card-default"> 
            <!-- /.card-heading -->
            <div class="card-body">
                
                <div class="dataTable_wrapper">
 
                    <?php echo $this->Form->create("Newsletter", array('url' => SENDYURL.'includes/create/send-now-admin.php','type'=>'file','novalidate', 'inputDefaults' => array('div' => 'form-group', 'class' => 'form-control'),'id'=>"line-import-form")); ?>
                    <div class="row">
                        <div class="col-sm-4">
			                <input type="hidden" name="campaign_id" value="<?php echo $cid;?>"> 
					        <input type="hidden" name="app" value="<?php echo $appId;?>">
					        <?php  $lists=$this->Sendy->getList($user_id); ?>
					        <!-- multiple="multiple" -->
					        <select id="email_list" name="email_list[]" class="form-control select">
				  				<?php 
				  					foreach($lists as $list_id => $list_name) {
			  							$lists_array=explode(',',$data['Campaign']['lists']);
			 							 $list_selected="";
										if(in_array($list_id, $lists_array)){
											$list_selected = 'selected';
										}	
										echo '<option value="'.$list_id.'" id="'.$list_id.'" '.$list_selected.'>'.$list_name.'</option>';	
									}		
				  				?>
				  			</select>		
					       

	                        <div class="row">
		                        <div class="col-md-6">
	    	                        <label>&nbsp;</label>
	        	                    <?php
	            	                    echo $this->Form->submit('Submit', array('class' => 'btn btn-info', 'div' => false));
	               		             ?>
	                        	</div>  
	                    	</div>
                        </div>
                    </div> 
                    <?php echo $this->Form->end(); ?> 
                </div> 

            </div>
        </div>
    </div>
</div>




<script type="text/javascript">
	$(document).ready(function() {
		$("#line-import-form").validate({
			rules: {
				line: {
					required: true
				}
			},
			messages: {
				line: "<?php echo addslashes(_('Please enter at least one combination of name & email'));?>"
			}
		});
	});
</script>