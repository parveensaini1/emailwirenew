<?php echo $this->Html->script(array('/plugins/ckeditor/ckeditor')); ?>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="card card-default"> 
            <!-- /.card-heading -->
            <div class="card-body">
                <?php  echo $this->element('submenu');; ?>
                <div class="dataTable_wrapper">
                     <?php echo $this->Form->create($model, array('novalidate','type'=>'file','inputDefaults' => array('div' => 'form-group', 'class' => 'form-control'))); ?>
                    <div class="row">                    
                        <div class="col-md-6">
                            <?php
                                  echo $this->Form->input('id');
                            echo $this->Form->input('title', array("class" => "form-control", "empty" => ""));
                            ?>
                    
                            <?php echo $this->Form->input('url', array("class" => "button-label form-control", 'label' => 'Url')); ?>
                            <?php echo $this->Form->input('button_label', array("class" => "button-label form-control", 'label' => 'Button label')); ?>
                     
                            <?php echo $this->Form->input('new_image', array('type'=>'file',"class" => "country_dd form-control", 'label' => 'Image')); ?>
                        </div>
                        <?php if(!empty($this->request->data[$model]['image'])){
                              echo $this->Form->input('image', array('type'=>'hidden'));
                          ?>
                          <div class="col-md-6">
                              <img src="<?php echo SITEFRONTURL.'files/ads/'.$this->request->data[$model]['image'];?>">
                          </div>
                      <?php } ?>
                    </div>       
                    <div class="row">                    
                        <div class="col-md-12">
                            <?php echo $this->Form->input('is_google_ads',array('type'=>'checkbox','class'=>'form-control status-checkbox','onchange'=>"isCheckedById('isGoogleAd')",'id'=>"isGoogleAd"));?>
                        </div>
                    </div>         
                    <div class="row">                    
                        <div class="col-md-12">
                            <?php 
                            $showdisciption='';
                            $gAds="hide";
                            if($this->data[$model]['is_google_ads']){
                                $showdisciption='hide';
                                $gAds='';
                            }

                            echo $this->Form->input('description', array("class" => "editor form-control","id"=>'editor1','label' => 'Description','div'=>"editor1 $showdisciption")); ?>

                            <?php echo $this->Form->input('google_ads', array("class" => "editor form-control",'label' => 'Google ads script','div'=>"google_ads $gAds")); ?>
                        </div>
                    </div> 
                    <div class="row"> 
                        <div class="col-md-4">
                            <label>&nbsp;</label>
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
<style type="text/css">
.hide{display:none;}
</style>
<script>

function isCheckedById(id) { 
    var checked =$('#'+id).is(":checked");
    if (checked) {
        $(".google_ads").removeClass("hide");
        $(".editor1").addClass("hide");;
    }else{
        $(".editor1").removeClass("hide");
        $(".google_ads").addClass("hide");;;
    }
}

var editor1 = CKEDITOR.replace('editor1',{showWordCount: true, filebrowserUploadUrl: SITEFRONTURL+"ajax/mediafileupload?typ=1"});
</script> 