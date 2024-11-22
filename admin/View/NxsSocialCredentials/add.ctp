<script type="text/javascript"><?php echo "var ajaxurl = '".$nxs_snapSetPgURL."';"; ?></script>
<?php echo $this->Html->script(array('nxs-js')); ?>
 <div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default"> 
            <!-- /.panel-heading -->
            <div class="panel-body">
                <?php echo $this->element('submenu'); ?>
                <div class="dataTable_wrapper">
                    <?php echo $this->Form->create($model, array('novalidate', 'inputDefaults' => array('div' => 'form-group', 'class' => 'form-control'))); ?>
                    <div class="row">
                        <div class="col-sm-9">
                        <?php 
                         echo $this->Form->input('social_media', array("type" => "text","class" => "form-control", "empty" => "",'maxLenght'=>"50",'required'=>"required"));

                         echo $this->Form->input('social_site', array("type" => "text","class" => "form-control", "empty" => "",'maxLenght'=>"100","label"=>"Social site url",'required'=>"required"));
                        echo $this->Form->input('social_media_short', array('options' => "",'empty' =>"Select social media","onchange"=>"redirecturl(this.value, 'N');","id"=>"nxs_ntType")); 
 
                         ?>
                         <?php if(isset($clName)&&!empty($clName)){ ?>
                         <div id="addsocialmediaform" class="socialmedia">
                         	<?php  
                         		if(class_exists($clName)){
					            	$ntClInst = new $clName(); 
	           						$ntClInst->showNewNTSettings(0);
           						}else{
           							echo "Form not found";
           						}
            			 	?>
                         </div>
                     <?php }?>

                        <div id="submitbuttonsection" class="row" style="<?php  if(empty($clName)){ echo "display: none;"; } ?>">
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
var nt="<?php echo $nt?>";
$.ajax({
        url: SITEFRONTURL  + 'nxs-snap/nxs-social-media-list.php?def='+nt,
        type: 'GET',
        async: false,
        success: function (response) {
        	$("#nxs_ntType").append(response);
        }
});


function redirecturl(selected,$isNew) { 
  window.location.replace(SITEURL+"NxsSocialCredentials/add/"+selected+'/'+$isNew);
}
</script>