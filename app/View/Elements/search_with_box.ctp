<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default"> 
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Search area</h3>
                    </div>
                    <?php echo $this->Form->create('Search', array('type' => 'get', 'inputDefaults' => array('div' => 'form-group', 'class' => 'form-control', 'required' => false))); ?>    
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-2">
                                <?php
                                $selected = '';
                                if (isset($this->params->query['school_id'])) {
                                    $selected = $this->params->query['country_id'];
                                }
                                App::import('Component', 'Custom');
                                $theComponent = new CustomComponent(new ComponentCollection());
                                $country_list = $theComponent->get_countries();
                                echo $this->Form->input('country_id', array('selected' => $selected, 'id' => 'country_id', 'options' => $country_list, 'empty' => 'Select Country', 'class' => 'country_dd form-control'));
                                ?>                  
                            </div>
                            <div class="col-xs-2">
                                <?php echo $this->Form->input('state_id', array('id' => 'state_id', 'empty' => 'Select State')); ?>
                            </div>
                            <div class="col-xs-2">
                                <?php echo $this->Form->input('city_id', array('id' => 'city_id', 'empty' => 'Select City')); ?>
                            </div>
                            <div class="col-xs-4">
                                <?php 
                                $keyword = '';
                                 if (isset($this->params->query['keyword'])) {
                                    $keyword = $this->params->query['keyword'];
                                }
                                echo $this->Form->input('keyword',array('value'=>$keyword,'placeholder'=>'Name, Reg Number....')); ?>
                            </div>
                            <div class="col-xs-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div>
                                        <?php echo $this->Form->submit('Search', array('class' => 'btn btn-info', 'div' => false)); ?>
                                        <?php //echo $this->Form->submit('Reset', array('class' => 'btn btn-warning', 'div' => false)); ?> 
                                    </div>
                                </div>
                            </div> 
                        </div>
                    </div><!-- /.box-body -->
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div><!-- /.box-body -->
    </div>
</div>
<script>
<?php if (isset($this->params->query['state_id']) && $this->params->query['city_id'] != '') { ?>
        $(window).load(function () {
            get_states("<?php echo $this->params->query['state_id']; ?>");
            get_cities("<?php echo $this->params->query['city_id']; ?>"); 
        });
<?php } ?>
</script>