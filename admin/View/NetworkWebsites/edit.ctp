<div class="row">
    <div class="col-lg-12">
        <div class="card card-default"> 
            <!-- /.card-heading -->
            <div class="card-body">
                <?php echo $this->element('submenu'); ?>
                <div class="dataTable_wrapper">
                    <?php echo $this->Form->create($model, array('type' => 'file', 'inputDefaults' => array('div' => 'form-group', 'class' => 'form-control', 'required' => false))); ?>
                    <div class="row">
                        <div class="col-sm-12">
                        <?php
                        echo $this->Form->input('website_name', array("class" => "form-control", "empty" => ""));
                        echo $this->Form->input('website_domain', array("class" => "form-control", "empty" => "",'placehoder'=>'Please enter slug in english'));
                        echo $this->Form->input('website_location', array("class" => "form-control", "empty" => ""));
                        echo $this->Form->input('website_media_type', array("class" => "form-control", "empty" => ""));
                        echo $this->Form->input('potential_audience', array("type"=>"text","class" => "form-control", "empty" => ""));
                        echo $this->Form->input('xml_link', array("type"=>"text","class" => "form-control", "empty" => ""));
                        
                        echo '<label for="SocialShareIcon">Website Logo</label>';
                        echo $this->Form->file('website_logo',array('label'=>'Website Logo'));
                        echo '<br>';
                        echo '<img width="300px" src="'.SITEFRONTURL.'files/networkwebsite/'.$this->data['NetworkWebsite']['website_logo'].'">';
                        echo '<br>';
                        echo '<br>';
                        ?>

                        <div class="row">

                        <div class="col-md-6">
                            <br>
                            <label>&nbsp;</label>
                            <?php   
                            echo $this->Form->submit('Submit', array('class' => 'btn btn-info', 'div' => false));
                            ?>
                            <?php
                            echo $this->Html->link('Cancel', array(
                                'controller' => $controller,
                                'action' => 'index'
                                    ), array('class' => 'btn btn-danger')
                            );
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