<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <!-- /.panel-heading -->
            <div class="panel-body">
                <?php echo $this->element('subcard'); ?>
                <div class="dataTable_wrapper">
                     <div class="row">
                        <div class="col-sm-6">
                <?php
                $plan_type=$this->Custom->plan_type();
                echo $this->Form->create($model, array('novalidate' => 'novalidate', 'inputDefaults' => array('div' => 'form-group', 'class' => 'form-control')));
                echo $this->Form->input('id');
                echo $this->Form->input('plan_type', array('type'=>"hidden"));

                if(!empty($this->data[$model]['plan_type'])){?>
                    <div class="row">                        
                        <div class="col-sm-12">
                            <?php echo $this->Form->input('name', array('type' => 'text')); ?>
                        </div> 
                        <div class="col-sm-12">
                            <?php echo $this->Form->input('plan_category_id', array('options'=>$plan_cat_list,'empty' => '-Select-','class'=>'custom_select form-control')); ?>
                        </div>
                        
                    </div>
                    <?php if($this->data[$model]['plan_type']=='single'){?>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php //echo $this->Form->input('words_release', array('type' => 'text')); ?>
                            </div>
                            <div class="col-sm-12">
                                <?php echo $this->Form->input('price', array('type' => 'text','label'=>"Plan Price")); ?>
                            </div>
                            <div id="bulk-in-single" class="col-sm-12" <?php if($this->data[$model]['number_pr']<=1){?> style="display: none;" <?php } ?>>
                                <?php echo $this->Form->input('number_pr', array('type' => 'number','label'=>"Number of PR in bulk",'min'=>'1'));?>
                        </div>
                        </div>  

                    <?php }else if($this->data[$model]['plan_type']=='bulk'){ ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php echo $this->Form->input('number_pr', array('type' => 'text','label'=>"Number of PR in bulk"));?>
                            </div> 
                        </div>  
                    <?php }else if($this->data[$model]['plan_type']=='subscription'){ ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php echo $this->Form->input('number_pr', array('type' => 'text','label'=>"Number of PR per day"));?>
                            </div> 
                            <div class="col-sm-12">
                                <?php 
                                $cycle_period=$this->Custom->cycle_period();
                                echo $this->Form->input('cycle_period', array('options'=>$cycle_period,'empty' => '-Select-','class'=>'custom_select form-control'));?>
                            </div> 
                         <!--    <div class="col-sm-3">
                                <?php 
                            //    $cycle_number=array("1"=>"1","2"=>"2","3"=>"3","4"=>"4");
                            //    echo $this->Form->input('cycle_number', array('options'=>$cycle_number,'empty' => '-Select-','class'=>'custom_select form-control'));?>
                            </div>  -->

                        </div>  
                    <?php } ?>

                    <div class="row">
                        <div class="col-sm-12">
                            <?php echo $this->Form->input('add_word_limit', array('type' => 'text','label'=>"Each Addt’l 100 words/PR")); ?>
                        </div>
                        <div class="col-sm-12">
                            <?php echo $this->Form->input('add_word_amount', array('type' => 'text','label'=>"Each Addt’l 100 words/PR amount")); ?>
                        </div> 
                    </div>
                    <div class="row">
                        <?php if($this->data[$model]['plan_type']!='single'){?>
                         <div class="col-sm-12">
                            <?php echo $this->Form->input('price', array('type' => 'text','label'=>'Plan Price')); ?>
                        </div> 
                    <?php } ?>
                        <div class="col-sm-12">
                            <?php echo $this->Form->input('bulk_discount_amount', array('type' => 'text')); ?>
                        </div> 
                    </div>
                    
                    <?php
                        echo $this->Form->input('status', array('div' => 'form-group div-status', 'class' => 'custom_check','type'=>'checkbox'));
                        echo $this->Form->submit('Submit', array('class' => 'btn btn-info', 'div' => false));
                     
                        echo $this->Html->link('Cancel', array(
                            'controller' => $controller,
                            'action' => 'index'
                                ), array('class' => 'btn btn-danger')
                        );
                        echo $this->Form->end();
                }        
                    ?>
                </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
   function redirect_plan_type(selected) {
     window.location.replace(SITEURL+"plans/add/"+selected);
   }


</script>