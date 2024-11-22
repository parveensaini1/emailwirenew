<div class="row">
    <div class="col-lg-12">
        <div class="card card-default">
            <!-- /.card-heading -->
            <div class="card-body">
               <?php //echo $this->element('subcard'); ?>
                <div class="dataTable_wrapper">
                    <div class="row">
                        <div class="col-sm-6">
                <?php
                echo $this->Form->create($model, array('novalidate' => 'novalidate', 'inputDefaults' => array('div' => 'form-group', 'class' => 'form-control')));
               echo $this->Form->input('id');
             ?>
               

                    
                    
                  
 <div class="row">
                        
                        <div class="col-sm-12">
                            <?php echo $this->Form->input('price', array('type' => 'text','label'=>'Plan Price')); ?>
                        </div>
                   
                        
                    </div> 
                    
                  
                   

                    <?php     
                        echo $this->Form->submit('Submit', array('class' => 'btn btn-info', 'div' => false));
                     
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