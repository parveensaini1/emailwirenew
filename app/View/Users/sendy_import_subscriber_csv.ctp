<?php echo $this->Html->script(array('jquery.validate.min','additional-methods.min')); ?>
<div class="row">
    <div class="col-lg-12">
    	<div class="col-lg-12"><div class="ew-title full"><?php echo $title_for_layout;?></div></div>
        <div class="panel panel-default"> 
            <!-- /.panel-heading -->
            <div class="panel-body">
                 <?php  include 'sendy_submenu.ctp'; ?>
                <div class="dataTable_wrapper">
                 	<h2><?php echo _('Import via CSV file');?></h2><br/>

                	<!-- <p><a href="<?php echo SITEURL.'sendy';?>/subscribers?i=<?php echo $appId;?>&l=<?php echo $lid;?>"><span class="label label-info"><?php echo $data["List"]['name'];?></span></a> </p>-->
                	

                	<p><?php echo _('List');?>: <a href="<?php echo SENDYURL;?>subscribers?i=<?php echo $appId;?>&l=<?php echo $lid;?>"><span class="label label-info"><?php echo $data["List"]['name'];?></span></a>	</p><br/>

                    <?php echo $this->Form->create("List", array('url' => SENDYURL.'includes/subscribers/import-update-front.php','type'=>'file','novalidate', 'inputDefaults' => array('div' => 'form-group', 'class' => 'form-control'),'id'=>"import-update-form")); ?>
                    <div class="row">
                        <div class="col-sm-12">
	                	<?php if($err==1):?>
							<div class="alert alert-error">
							  <button type="button" class="close" data-dismiss="alert">×</button>
							  <strong><?php echo _('Number of columns in CSV does not match CSV format example (as shown below).');?></strong>
							</div>
							<?php elseif($err==3):?>
							<div class="alert alert-error">
							  <button type="button" class="close" data-dismiss="alert">×</button>
							  <p><strong><?php echo _('Please upload a CSV file.');?></strong></p>
							  <p><?php echo _('If you are uploading a huge CSV file, Try increasing the following values in your server\'s php.ini to larger numbers. Contact your hosting support if you\'re unsure how to do this.');?></p>
							  <ul>
							  	<li><code>upload_max_filesize</code></li>
								<li><code>post_max_size</code></li>
								<li><code>memory_limit</code></li>
								<li><code>max_input_time</code></li>
								<li><code>max_execution_time</code> <?php echo _('(set to 0 so that execution won\'t time out indefinitely)');?></li>
							  </ul>
							  <p><?php echo _('Alternatively, try splitting your huge CSV file into several smaller sized CSV files and import them one after another.');?></p>
							</div>
							<?php elseif($err==4):?>
							<div class="alert alert-error">
							  <button type="button" class="close" data-dismiss="alert">×</button>
							  <strong><?php echo _('Could not upload file. Please make sure permissions in /uploads/ folder is set to 777. Then remove the /csvs/ folder in the /uploads/ folder and try again.');?></strong>
							</div>
							<?php endif;?>
			    			<label class="control-label" for="csv_file"><em><?php echo _('CSV format');?>:</em></label>
				        	<ul>
				        		<li><?php echo _('Format your CSV the same way as the example below (without the first title row)');?></li>
				        		<li><?php echo _('Your CSV columns should be separated by commas, not semi-colons or any other characters');?></li>
				        		<li><?php echo _('The number of columns in your CSV should be the same as the example below');?></li>
				        		<!-- <li><?php echo _('If you want to import more than just name & email');?>, <a href="<?php echo SENDYURL;?>/custom-fields?i=<?php echo $appId;?>&l=<?php echo $lid;?>" title="" style="text-decoration:underline;"><?php echo _('create custom fields first');?></a></li> -->
				        	</ul>
				        	<?php $login=$this->Sendy->getSendyLoginDetail($appId); ?>
	                        <input type="hidden" name="list_id" value="<?php echo $lid;?>">
	                        <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
					        <input type="hidden" name="app" value="<?php echo $appId;?>">
					        <input type="hidden" name="cron" value="<?php echo $login['cron_csv'];?>">
					        <table class="table table-bordered table-striped table-condensed" style="width: 300px;">
					        	<tbody>
					        		<tr>
					        			<th><?php echo _('Name');?></th>
					        			<th><?php echo _('Email');?></th>
					        			<?php $custom_fields= $this->Sendy->getSendySubscriberCustomFields($lid); 
					        				if(!empty($custom_fields)){
					        					echo $custom_fields["th"];
					        				}

					        			?>
									</tr>
									    <tr>
									      <td>Philip Morris</td>
									      <td>pmorris@gmail.com</td>
									    <?php  
									    	if(!empty($custom_fields["td"])){
					        					echo $custom_fields["td"];
					        				}
										?>
									    </tr>
									    <tr>
									      <td>Jane Webster</td>
									      <td>jwebster@gmail.com</td>
									      <?php 
											if(!empty($custom_fields["td"])){
					        					echo $custom_fields["td"];
					        				}
										      ?>
									    </tr>
									  </tbody>
									</table>
									<p>Note: Click here to <a href="<?php echo SITEURL.'files/emailwire-dummy-email-list%20.csv'; ?>" download>Download Dummy CSV file</a>.</p>
									<div class="form-group">
										<label class="custom-file-upload">
										<input type="file" class="input-xlarge" id="csv_file"  name="csv_file">Browse csv file  </label>
									</div>		
							        
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
		$("#import-update-form").validate({
			rules: {
				csv_file: {
					required: true,
					 extension: "csv"
				}
			},
			messages: {
				csv_file: "File must be CSV."
			}
		});
		
	});
</script>