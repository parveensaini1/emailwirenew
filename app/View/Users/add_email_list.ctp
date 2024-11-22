<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="ew-title full"><?php echo $title_for_layout;?></div>
        <div class="panel panel-default"> 
            <!-- /.panel-heading -->
            <div class="panel-body">  
                <div class="dataTable_wrapper">
                    <?php echo $this->Form->create("List", array('novalidate', 'inputDefaults' => array('div' => 'form-group', 'class' => 'form-control'))); ?>
                    <div class="row">
                        <div class="col-sm-6">
                        <?php
                            echo $this->Form->input('name', array("class" =>"form-control","empty" =>"","label"=>"Enter email list name"));
                            echo $this->Form->input('staff_user_id',array("type"=>"hidden","class" => "form-control","value" =>$user_id));
                            echo $this->Form->input('app', array("type"=>"hidden","class" =>"form-control","value" =>"1"));
                       ?>                    
                        </div>
                    </div>
					<div class="row">
						<div class="col-md-6">
                            <?php
                                echo $this->Form->submit('Submit', array('class' => 'btn btn-info', 'div' => false));
                            ?>
                        </div>
					</div>  	
                    <?php echo $this->Form->end(); ?> 
                </div> 

            </div>
        </div>
    </div>
</div>
 