<div class="row">
    <div class="col-lg-12">
        <div class="card card-default">
            <!-- /.card-heading -->
            <div class="card-body">
               <?php // echo $this->element('subcard'); ?>
                <div class="dataTable_wrapper">
                    <div class="row">
                        <div class="col-sm-6">
                <?php
                $plan_type=$this->Custom->plan_type();
                echo $this->Form->create($model, array('novalidate' => 'novalidate', 'inputDefaults' => array('div' => 'form-group', 'class' => 'form-control')));
                echo $this->Form->input('plan_type', array('options'=>$plan_type,'empty' => '-Select-','class'=>'select2 form-control','onchange'=>"redirect_plan_type(this.value)",'default'=>$selectedplan_type));
                if(!empty($selectedplan_type)){?>
                    
                    
                    <div class="row">                        
                        <!-- <div class="col-sm-4">
                            <?php echo $this->Form->input('price', array('type' => 'text')); ?>
                        </div> -->
                        <div class="col-sm-12">
                            <?php echo $this->Form->input('plan_category_id', array('options'=>$plan_cat_list,'empty' => '-Select-','class'=>'select2 form-control','onchange'=>'gettranslationfield(this.value);')); ?>
                        </div>
                        <div class="col-sm-12"><div id="translationbox"></div></div>
                    </div>
                    <?php if($selectedplan_type=='bulk'){ ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php echo $this->Form->input('number_pr', array('type' => 'text','label'=>"Number of PR allowed"));?>
                            </div> 
                        </div>  
                    <?php }else if($selectedplan_type=='subscription'){ ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <?php 
                                   echo $this->Form->input('number_pr', array('type' => 'text','label'=>"Number of PR per day"));
                                ?>
                            </div> 
                            <div class="col-sm-12">
                                <?php  
                                $cycle_period=$this->Custom->cycle_period();
                                echo $this->Form->input('cycle_period', array('options'=>$cycle_period,'empty' => '-Select-','class'=>'select2 form-control'));?>
                            </div> 
                        </div>  
                    <?php } ?>

                    <div class="row">
                        <?php if($selectedplan_type=='single'){?>
                        <div class="col-sm-12">
                            <?php echo $this->Form->input('price', array('type' => 'text','label'=>'Plan Price')); ?>
                        </div>
                    <?php } ?>
                         <?php if($selectedplan_type!='single'){?>
                        <div class="col-sm-12" id="bulk_discount_amountbox">
                            <?php echo $this->Form->input('bulk_discount_amount', array('type' => 'text')); ?>
                        </div>  
                    <?php } ?>
                        <div class="col-sm-12">
                            <?php echo $this->Form->input('add_word_amount', array('type' => 'text','label'=>"Each Addt’l 100 words/PR amount")); ?>
                        </div>  
                    </div> 
                    
                    <!-- <?php //if($selectedplan_type=='single'){?> -->
                    <?php if($selectedplan_type==''){?>
                        <div class="row">
                          <!--   <div class="col-sm-12">
                                <?php // echo $this->Form->input('words_release', array('type' => 'text')); ?>
                            </div> -->
                    
                            <div class="col-sm-12">
                                <?php echo $this->Form->input('name', array('type' => 'text','label'=>'Secondary bulk PR plan name')); ?>
                            </div>
                            <div id="bulk-in-single" class="col-sm-12">
                                <?php echo $this->Form->input('number_pr', array('type' => 'number','label'=>"Allowed # PR in secondary bulk PR plan",'min'=>'1','value'=>'1'));?>

                            </div> 
                            <!-- <a id="showbulksinglebox" href="javascript:void(0)">Add PR in bulk</a> -->
                        </div>  

                    <?php }?>

                    <div class="row">
                       <div class="col-sm-12">
                            <?php
                            $maxCategroy=$this->Custom->getCategoryCount();
                            echo '<label for="PlanCategoryLimit">Number of category allowed - (Total category '.$maxCategroy.')</label>';
                            echo $this->Form->input('category_limit', array('type' => 'number','label'=>false,'min'=>'1','max'=>$maxCategroy,'value'=>'1'));  
                            ?>
                        </div>
                        <div id="bulk-in-single" class="col-sm-12">
                            <?php echo $this->Form->input('add_category_charges', array('type' => 'number','label'=>"Additional charges for each allowed category ",'min'=>'1','value'=>'1'));?>
                        </div>
                    </div>


                    <div class="row">
                       <div class="col-sm-12">
                            <?php
                            $maxMsa=$this->Custom->getMSACount();
                            echo '<label for="PlanCategoryLimit">Number of MSA allowed - (Total MSA '.$maxMsa.')</label>';
                            echo $this->Form->input('msa_limit', array('type' => 'number','label'=>false,'min'=>'1','max'=>$maxMsa,'value'=>'1'));  
                            ?>
                        </div>
                        <div id="bulk-in-single" class="col-sm-12">
                            <?php echo $this->Form->input('add_msa_charges', array('type' => 'text','label'=>"Additional charges for each allowed MSA "));?>
                        </div>
                    </div>

                    <div class="row">
                       <div class="col-sm-12">
                            <?php
                            $maxState=$this->Custom->getStateCount();
                            echo '<label for="PlanCategoryLimit">Number of state allowed - (Total states '.$maxState.')</label>';
                            echo $this->Form->input('state_limit', array('type' => 'number','label'=>false,'min'=>'1','max'=>$maxState,'value'=>'1'));  
                            ?>
                        </div>
                        <div id="bulk-in-single" class="col-sm-12">
                            <?php echo $this->Form->input('add_state_charges', array('type' => 'text','label'=>"Additional charges for each allowed state "));?>
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

function gettranslationfield(selected) {
    $.ajax({
      url: SITEURL+'ajax/get_plan_field',
      type:'post',
      data:{cat_id:selected},
      cache: false,
      success: function(html){
        if(html!=0){
        $("#translationbox").html(html);
        $("#bulk_discount_amountbox").hide().val("0.00");
        }else{
            $("#bulk_discount_amountbox").show();
        }
    }
    });
}


   function redirect_plan_type(selected) {
     window.location.replace(SITEURL+"plans/add/"+selected);
   }
$("#showbulksinglebox").click(function(){
    $("#bulk-in-single").show();
    $(this).hide();
});
</script>