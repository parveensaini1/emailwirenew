<?php 
  echo $this->Html->script(array('jquery.validate.min','additional-methods.min'));

?>
<div class="row">
    <div class="col-lg-12">
    	<div class="col-lg-12"><div class="ew-title full"><?php echo $title_for_layout;?></div></div>
        <div class="panel panel-default"> 
            <!-- /.panel-heading -->
            <div class="panel-body">
                 <?php  include 'sendy_submenu.ctp'; ?>
                <div class="dataTable_wrapper">
                 	 <h2><?php echo _('Add name and email per line');?></h2><br/>
                	<p><?php echo _('List');?>: <!-- <a href="<?php echo SITEURL.'sendy';?>/subscribers?i=<?php echo $appId;?>&l=<?php echo $lid;?>"><span class="label label-info"><?php echo $data[$model]['name'];?></span></a> -->
                	<a href="<?php echo SENDYURL;?>subscribers?i=<?php echo $appId;?>&l=<?php // echo $lid;?>"><span class="label label-info"><?php echo $data[$model]['name'];?></span></a>	</p><br/>

                    <?php echo $this->Form->create("List", array('url' => SENDYURL.'includes/subscribers/line-update-front.php','type'=>'file','novalidate', 'inputDefaults' => array('div' => 'form-group', 'class' => 'form-control'),'id'=>"line-import-form")); ?>
                    <div class="row">
                        <div class="col-sm-12">
			    			<label class="control-label" for="line"><?php echo _('Name and email');?><br/><em style="color:#A1A1A1">(<?php echo _('to import more than just name and email, import via CSV');?>)</em></label>

			    			<div class="control-group">
						    	<div class="controls">
					              <textarea class="input-xlarge" id="line" name="line" rows="10" style="width: 300px;" placeholder="Eg. Herman Miller,hermanmiller@gmail.com"></textarea>
					            </div>
					        </div>
	        

				        	<?php $login=$this->Sendy->getSendyLoginDetail($appId); ?>
	                        <input type="hidden" name="list_id" value="<?php echo $lid;?>">
	                        <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
					        <input type="hidden" name="app" value="<?php echo $appId;?>">
					        <?php 
					        $gdpr_options=$this->Sendy->getSendyGdprOption($appId);

					        ?>
					         <?php if($gdpr_options):?>
						        <div class="control-group">
							    	<div class="checkbox">
									  <label><input type="checkbox" name="gdpr_tag"><?php echo _('Apply <span class="label label-warning">GDPR</span> tag to imported subscribers?');?> <a href="javascript:void(0)" title="<?php echo _('If your data includes EU subscribers that were collected in a GDPR compliant manner, check this box to apply a \'GDPR\' tag to all of them. In your brand settings, you can turn on the \'GDPR\' safe switch\' so that Campaigns and Autoresponders will only always send to subscribers tagged with \'GDPR\'.');?>"><span class="icon icon-info-sign"></span></a></label>
									</div>
						        </div>
						        <br/>
						    <?php endif;?>

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