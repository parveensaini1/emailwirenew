<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <!-- /.panel-heading -->
            <div class="panel-body">
                <?php include 'menu.ctp'; ?>
                <div class="dataTable_wrapper">
                    <?php
                    echo $this->Form->create($model, array('novalidate' => 'novalidate', 'inputDefaults' => array('div' => 'form-group', 'class' => 'form-control')));
                    ?>
                    <div class="row">                        
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('name', array('type' => 'text')); ?>
                        </div> 
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('country_id', array('options'=> $country_list,'empty' => '-Select-','class'=>'custom_select form-control')); ?>
                        </div>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('symbol', array('type' => 'text')); ?>
                        </div> 
                    </div>  
                    <div class="row">                        
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('email', array('type' => 'text')); ?>
                        </div>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('phone_number', array('type' => 'text')); ?>
                        </div> 
                    </div> 
                    <div class="row">                        
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('fb_link', array('type' => 'text')); ?>
                        </div>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('twitter_link', array('type' => 'text')); ?>
                        </div>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('youtube_link', array('type' => 'text')); ?>
                        </div>
                        <div class="col-sm-3">
                            <?php echo $this->Form->input('instagram', array('type' => 'text')); ?>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-sm-6">
                            <?php echo $this->Form->input('address', array('type' => 'textarea')); ?>
                        </div>  
                    </div>
                    <?php
                   echo $this->Form->input('status', array('div' => 'form-group div-status', 'class' => 'custom_check','type'=>'checkbox'));
                    echo $this->Form->submit('Submit', array('class' => 'btn btn-info', 'div' => false));
                    ?>
                    <?php
                    echo $this->Html->link('Cancel', array(
                        'controller' => $controller,
                        'action' => 'index'
                            ), array('class' => 'btn btn-danger')
                    );
                    echo $this->Form->end();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>