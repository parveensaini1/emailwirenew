<?php
echo $this->Html->css(array('/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min'));
echo $this->Html->script(array('/plugins/ckeditor/ckeditor'));

?>

 
<section class="content-section">
    <div class="box">
      <div class="box-header with-border"> 
        <div class="row">
            <div class="col-sm-4 text-left view-btn"><button onclick="goBack()" class="btn btn-xs btn-primary">Go Back</button></div>
          <?php if($this->request->data['PressRelease']['status']==4 &&$this->request->data['PressRelease']['disapproval_reason']){ ?>
           <div class="col-sm-8">
             <?php 
               echo "<p class='text-danger'>".$this->request->data['PressRelease']['disapproval_reason']."</p>";
             ?>
           </div>
         <?php } ?> 
          </div>
        </div>
      </div>
</section>


 

<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default"> 
            <!-- /.panel-heading -->
            <div class="panel-body"> 
                <div class="dataTable_wrapper">
                    <?php echo $this->Form->create($model, array('type'=>'file','novalidate', 'inputDefaults' => array('div' => 'form-group', 'class' => 'form-control'))); ?>
                    <div class="row">
                        <div class="col-sm-9">
                        <?php
                        echo $this->Form->input('id'); 
                        echo $this->Form->input('title', array("class" => "form-control",'maxlength'=>"255","empty" => ""));
                        echo $this->Form->input('summary', array('type'=>'textarea',"class" => "form-control",'row'=>"3",'col'=>"50",'maxlength'=>"350"));


                        $bodycontent='';
                        if(!empty($this->request->data['PressRelease']['body'])){
                            $content=str_replace(array("<li>","</li>"),array(" "," "),$this->request->data['PressRelease']['body']);
                            $bodycontent= strip_tags($content);
                        }  
 

                        echo $this->Form->input('body', array("class" => "form-control editor","id"=>'editor1' ,'label' => 'Body','maxLength'=>strlen($bodycontent)));
                        

                        ?>

                         <div class="row" style="margin-bottom: 10px;">                        
                            <div class="col-sm-6">
                                <label>SEO Keywords</label><br />
                            </div>  
                        </div> 
                        <?php 
                        $countk=(isset($this->data['PressSeo'])&&count($this->data['PressSeo'])>0)?count($this->data['PressSeo']):1;
                        if($countk>5)
                            $countk=5;
                        
                        for ($kloop = 0; $kloop < 5; $kloop++) {
                            $label = $kloop + 1;
                            if(isset($this->data['PressSeo'][$kloop]['id'])||$kloop=='0'){ 
                                $stylekey= "display:block;"; 
                            }else{ 
                                $stylekey= "display:none;"; 
                            }
                            ?>

                            <div id="<?php echo "skeyword-".$kloop; ?>" style="<?php echo $stylekey; ?>" class="row">      
                                <div class="col-sm-3">
                                <?php 
                                if(isset($this->data['PressSeo'][$kloop]['id'])&&!empty($this->data['PressSeo'][$kloop]['id'])){
                                    echo $this->Form->input("PressSeo.$kloop.id",array("type"=>'hidden',"value"=>$this->data['PressSeo'][$kloop]['id']));
                                }
                                echo $this->Form->input("PressSeo." .$kloop. ".keyword", array('class' => 'form-control ','label' => 'Keyword ' . $label, 'type' => 'text','id'=>"PressSeoKeyword$kloop"));
                                   ?>
                                </div> 
                                <!-- <div class="col-sm-3">
                                    <?php 
                                   // $resId="PressSeo".$i."Keyword";
                                   // echo $this->Form->input("PressSeo." . $i . ".urls", array('class' => 'form-control ', 'label' => 'Url ' . $label, 'type' => 'text')); 
                                   //,'onchange'=>"replace_tags(this.value,'".$resId."')"
                                    ?>
                                </div> -->
                            </div> 
                        <?php }  ?>
                        <div class="row keyword-btns">
                            <div class="col-sm-12">
                            <a class='btn btn-info' id='keybtn' href="javascript:void(0)" fnum='<?php echo $countk;?>' onclick="addmoref('skeyword-','keybtn','rkeybtn','PressSeoKeyword');">Add More Keywords</a>
                            <a class='btn btn-info btn-danger' id='rkeybtn' href="javascript:void(0)" style="<?php if($countk<=1){ echo 'display: none;'; }?>" onclick="removefield('skeyword-','keybtn','rkeybtn','PressSeoKeyword');">Remove</a>
                            </div>
                        </div>
                        
                    
                    
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="block-title" style="border-bottom: 2px solid #00a65a;"><span style="background-color: #00a65a;">Category</span></h4>
                            </div>                        
                        </div> 
                    <div class="category_section">
                        <?php 
                            echo $this->Form->input('Category.Category', array(
                                    'type' => 'select',
                                    'multiple' =>'checkbox',
                                    'options' => $categories,
                                    'label' => false,
                                    'class' => 'form-group category_checkbox'
                                ));

                        ?> 
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="block-title" style="border-bottom: 2px solid #00a65a;"><span style="background-color: #00a65a;">Other Information</span></h4>
                            </div>                        
                        </div>
                        <!-- <div class="row">                        
                            <div class="col-sm-6">
                                <?php // echo $this->Form->input('stock_ticker', array('class' => 'form-control ', 'type' => 'text',"required"=>"required")); ?>
                            </div>
                        </div>  -->
                        <div class="row">                        
                            <div class="col-sm-6">
                                <label>Release Date *</label><br />
                                Select tomorrow's date. Allow one day. 
                                or select today's date for $75 for same day distribution"                            
                            </div> 
                        </div> 
                        <div class="row">                        
                            <div class="col-sm-3">                                                      
                                <?php 
                                $currentdate=(isset($this->data[$model]['release_date'])&&!empty($this->data[$model]['release_date']))?$this->data[$model]['release_date']:date('d-m-Y');
                                echo $this->Form->input('release_date', array('class' => 'release_date form-control ', 'type' => 'text', 'label' => false,"required"=>"required","value"=>$currentdate)); ?>
                            </div> 
                        </div> 
                        <div class="row">
                        <?php if($planDetail['PlanCategory']['is_msa_allowed']){  ?>                 
                            <div id="msadropdn" class="col-sm-4">
                                <?php echo $this->Form->input('Msa.Msa', array('class' => 'form-control ', 'label' => 'MSA', 'options' => $mas_list,'empty' => '-Select-','multiple'=>true,'class'=>"bootselect form-control",'id'=>"prmsaid",'onchange'=>"msachanges();")); ?>
                            </div>
                        <?php } ?>

                        <?php if($planDetail['PlanCategory']['is_country_allowed']){  ?>                 
                            <div class="col-sm-4">
                                <?php echo $this->Form->input('country_id', array('class' => 'form-control ', 'onchange' => "get_state(this.value)", 'options' => $country_list, 'empty' => '-Select-')); ?>
                            </div> 
                         <?php } ?>  
                          <?php if($planDetail['PlanCategory']['is_state_allowed']){  ?>  
                        <div class="col-sm-4" id="state_div">
                                <?php echo $this->Form->input('State.State', array('class' => 'form-control ', 'type' => 'select','options' => $state_list, 'div' => 'form-group', 'class' => 'form-control state-select','multiple'=>true,'onchange'=>'statechange();')); ?>
                            </div> 
                         <?php } ?> 
                        </div>


                        <!-- <div class="row">    
                                               
                            <div class="col-sm-6">            
                                <label>IFrame URL</label><br />URL must be complete. (e.g: 'http://www.example.com')
                                <?php // echo $this->Form->input('iframe_url', array('class' => 'form-control ', 'type' => 'text', 'label' => false)); ?>
                            </div> 
                        </div>  -->
                        
                   
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="block-title" style="border-bottom: 2px solid #00a65a;"><span style="background-color: #00a65a;">Image Detail</span></h4>
                            </div>                        
                        </div>
                        <div class="row">                        
                            <div class="col-sm-8">            
                                Each file cannot be larger than 1MB. Thumbnail images will be created automatically.
                            </div> 
                        </div>  

                        <?php
                        $countk=(isset($this->data['PressImage'])&&count($this->data['PressImage'])>0)?count($this->data['PressImage']):1;
                        if($countk>5)
                            $countk=5;
                        
                        for ($i = 0; $i < 5; $i++) {
                            $label = $i + 1;
                            if(isset($this->data['PressImage'][$i]['id'])||$i=='0'){ 
                                $stylekey= "display:block;"; 
                            }else{ 
                                $stylekey= "display:none;"; 
                            }
                             $saved='';
                            if(isset($this->data['PressImage'][$i]['id'])&&!empty($this->data['PressImage'][$i]['id'])){
                                $saved='saved';
                                echo $this->Form->input("PressImage.$i.id",array("type"=>'hidden',"value"=>$this->data['PressImage'][$i]['id']));
                            }
                            ?>
                            <div id="<?php echo "pimage-".$i; ?>" style="<?php echo $stylekey; ?>" class="row"> 
                                <?php  if(isset($this->data['PressImage'][$i]['image_name'])&&!empty($this->data['PressImage'][$i]['image_name'])){ ?>
                                <div class="col-sm-3">
                                    <?php 
                                    $imgurl='files/company/press_image/'.$this->data['PressImage'][$i]['image_path'].'/'.$this->data['PressImage'][$i]['image_name'];
                                    echo $this->Html->image(SITEURL.$imgurl, array('id'=>"prev_banner_image"));

                                    
                                    echo $this->Form->input("PressImage.$i.oldimage_name", array('value' =>$this->data['PressImage'][$i]['image_name'], 'type' => 'hidden','id'=>"PressImageOldName$i"));

                                      echo $this->Form->input("PressImage.$i.url", array('value' =>$imgurl, 'type' => 'hidden'));
                                    ?>
                                </div> 
                                <?php  } ?>    
                                <div class="col-sm-3">
                                    <?php 
                                    echo $this->Form->input("PressImage.$i.image_name", array('label' => 'Image ' . $label, 'type' => 'file','id'=>"PressImageName$i"."$saved"));
                                    
                                    ?>
                                </div>
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input("PressImage.$i.describe_image", array('label' => 'Caption ' . $label, 'type' => 'text','id'=>"PressImageDesc$i")); ?>
                                </div>
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input("PressImage.$i.image_text", array('label' => 'Image text ' . $label, 'type' => 'text', 'label' =>'Alt text','id'=>"PressImageAltName$i")); ?>
                                </div>  
                            </div> 
                        <?php } ?>
                        <div class="row primages-btns">
                            <div class="col-sm-12">
                            <a class='btn btn-info' id='pimagebtn' href="javascript:void(0)" fnum='<?php echo $countk;?>' onclick="addmoref('pimage-','pimagebtn','rimgbtn','PressImageName');">Add More Images</a>
                            <a class='btn btn-info btn-danger' id='rimgbtn' href="javascript:void(0)" style="<?php if($countk<=1){ echo 'display: none;'; }?>" onclick="removefield('pimage-','pimagebtn','rimgbtn','PressImageName','PressImageDesc','PressImageAltName','PressImageOldName');">Remove</a>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="block-title" style="border-bottom: 2px solid #00a65a;"><span style="background-color: #00a65a;">Youtube Links</span></h4>
                            </div>                        
                        </div>
                        <?php
                         $countk=(isset($this->data['PressYoutube'])&&count($this->data['PressYoutube'])>0)?count($this->data['PressYoutube']):1;
                        if($countk>5)
                            $countk=5;
                        
                        for ($yloop = 0; $yloop < 5; $yloop++) {
                            $label = $yloop + 1;
                            if(isset($this->data['PressYoutube'][$yloop]['id'])||$yloop=='0'){ 
                                $stylekey= "display:block;"; 
                            }else{ 
                                $stylekey= "display:none;"; 
                            }
                            if(isset($this->data['PressYoutube'][$yloop]['id'])&&!empty($this->data['PressYoutube'][$yloop]['id'])){
                               echo $this->Form->input("PressYoutube.$yloop.id",array("type"=>'hidden',"value"=>$this->data['PressYoutube'][$yloop]['id']));
                            }
                            ?>
                            <div id="<?php echo "ylinks-".$yloop; ?>" style="<?php echo $stylekey; ?>" class="row">      
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input("PressYoutube.$yloop.url", array('label' => 'Youtube URL ' . $label, 'type' => 'text','id'=>"PressYoutubeUrl$yloop")); ?>
                                </div>
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input("PressYoutube.$yloop.description", array('label' => 'Describe video ' . $label, 'type' => 'text','id'=>"PressYoutubeDesc$yloop")); ?>
                                </div>                   
                            </div> 
                        <?php } ?>
                        <div class="row ylink-btns">
                            <div class="col-sm-12">
                             <a class='btn btn-info' id='ylinkbtn' href="javascript:void(0)" fnum='<?php echo $countk;?>' onclick="addmoref('ylinks-','ylinkbtn','rlinkbtn','PressYoutubeUrl');">Add More Youtube Links</a>
                        <a class='btn btn-info btn-danger' id='rlinkbtn' href="javascript:void(0)" style="<?php if($countk<=1){ echo 'display: none;'; }?>" onclick="removefield('ylinks-','ylinkbtn','rlinkbtn','PressYoutubeUrl','PressYoutubeDesc');">Remove</a>
                            </div>
                        </div>



                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="block-title" style="border-bottom: 2px solid #00a65a;"><span style="background-color: #00a65a;">Poadcast</span></h4>
                            </div>                        
                        </div>
                        <?php
                         $countk=(isset($this->data['PressPoadcast'])&&count($this->data['PressPoadcast'])>0)?count($this->data['PressPoadcast']):1;
                        if($countk>5)
                            $countk=5;
                        
                        for ($yloop = 0; $yloop < 5; $yloop++) {
                            $label = $yloop + 1;
                            if(isset($this->data['PressPoadcast'][$yloop]['id'])||$yloop=='0'){ 
                                $stylekey= "display:block;"; 
                            }else{ 
                                $stylekey= "display:none;"; 
                            }
                            if(isset($this->data['PressPoadcast'][$yloop]['id'])&&!empty($this->data['PressYoutube'][$yloop]['id'])){
                               echo $this->Form->input("PressPoadcast.$yloop.id",array("type"=>'hidden',"value"=>$this->data['PressPoadcast'][$yloop]['id']));
                            }
                            ?>
                            <div id="<?php echo "podlink-".$yloop; ?>" style="<?php echo $stylekey; ?>" class="row">      
                                <div class="col-sm-6">
                                    <?php echo $this->Form->input("PressPoadcast.$yloop.url", array('label' => 'Podcast Embed code ' . $label, 'type' => 'textarea','id'=>"PressPoadcastUrl$yloop")); ?>
                                </div>
                                <div class="col-sm-6">
                                    <?php echo $this->Form->input("PressPoadcast.$yloop.description", array('label' => 'Describe poadcast ' . $label, 'type' => 'text','id'=>"PressPoadcastDesc$yloop")); ?>
                                </div>                   
                            </div> 
                        <?php } ?>
                        <div class="row ylink-btns">
                            <div class="col-sm-12">
                            <a class='btn btn-info' id='podlinkbtn' href="javascript:void(0)" fnum='<?php echo $countk;?>' onclick="addmoref('podlink-','podlinkbtn','rpodlinkbtn','PressPoadcastUrl');">Add More Podcasts</a>
                            <a class='btn btn-info btn-danger' id='rpodlinkbtn' href="javascript:void(0)" style="<?php if($countk<=1){ echo 'display: none;'; }?>" onclick="removefield('podlink-','podlinkbtn','rpodlinkbtn','PressPoadcastUrl','PressPoadcastDesc');">Remove</a>
                            </div>
                        </div>
                        <br/>
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
 $(function () {
    $(".bootselect,.state-select,.distribute-select").select2({
        tags: true
    }); 

});  
var editor = CKEDITOR.replace('editor1',{showWordCount: true,}); 

function addmoref(fieldId,keybtn,rbtnId,inputFieldId) {
    var fnum=$("#"+keybtn).attr('fnum');
    if(fnum>0&&fnum<5){
        var next=eval(fnum)+1;
        var previous=eval(fnum)-1;
        var previousField=$("#"+inputFieldId+previous);
        var genratePreviousfieldErrId="err-"+inputFieldId+previous;
        var previousVal=previousField.val();
        $("#"+genratePreviousfieldErrId).remove();
        if(previousVal!=''){
            $("#"+keybtn).attr('fnum',next);
            $("#"+fieldId+fnum).show();
            $("#"+rbtnId).show();
        }else{
            previousField.after("<span id='"+genratePreviousfieldErrId+"' class='text-danger '>Please enter value.</p>");
        }
        
    }
}

function removefield(fieldId,atagbtn,rbtnId,inputFieldId1='',inputFieldId2='',inputFieldId3='',inputFieldId4='') {
    var fnum=$("#"+atagbtn).attr('fnum');
    if(fnum>0&&fnum<=5){
        var prev=eval(fnum)-1;
        if(prev==1){
           $("#"+rbtnId).hide();
        }
        if(inputFieldId1!=''){
            $("#"+inputFieldId1+prev).val('');
        }
        if(inputFieldId2!=''){
            $("#"+inputFieldId2+prev).val('');
        }
        if(inputFieldId3!=''){
            $("#"+inputFieldId3+prev).val('');
        }
        if(inputFieldId4!=''){
            $("#"+inputFieldId4+prev).val('');
        }
        $("#"+atagbtn).attr('fnum',prev);
        $("#"+fieldId+prev).hide();
    }
}

</script>


