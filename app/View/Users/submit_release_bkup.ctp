<?php  echo $this->Html->script(array('/plugins/ckeditor/ckeditor')); ?> 
<?php echo $this->Html->script(array('jquery.validate.min','additional-methods.min'));?> 
<style type="text/css">
#PressReleaseform section:not(:first-of-type) { display: none; }
#PressReleaseform .action-button {
  width: 100px;
  background: #27AE60;
  font-weight: bold;
  color: white;
  border: 0 none;
  border-radius: 1px;
  cursor: pointer;
  padding: 10px 5px;
  margin: 10px 5px;
}
#PressReleaseform .action-button:hover, #PressReleaseform .action-button:focus {
  box-shadow: 0 0 0 2px white, 0 0 0 3px #27AE60;
} 
</style>
<div id="main-content" class="row">
    <div id="content" class="col-lg-9 content">
        <div class="panel panel-default"> 
            <div class="panel-body">
                <?php //include 'menu.ctp'; 
                if($is_plan_paid==1){ ?>
                <div class="dataTable_wrapper">
                    <?php 
                    if($this->Session->read('Auth.User.staff_role_id')==3&&isset($newsroomcount)&&$newsroomcount==0){ ?>
                        <div class="col-sm-6">
                        <?php echo $this->Form->input('check_company_name', array('div' => 'form-group', 'class' => 'form-control', 'id' => 'check_company_name',"label"=>'Search here to take over company')); ?>
                        <a href="javascript:void(0);" onclick="search_company();" class="label label-info search_company">Search</a>
                          <div style="display: none;" id="check_company_message"></div>
                        </div>
                    <?php
                    }else{
                       echo $this->Form->create('PressRelease', array('id' => 'release_form', 'type' => 'file', 'novalidate' => 'novalidate','id'=>"PressReleaseform",'inputDefaults' => array('div' => 'form-group', 'class' => 'form-control')));
                       
                        if(isset($this->data[$model]['id'])&&!empty($this->data[$model]['id'])){
                              echo $this->Form->input('plan_id',array("type"=>'hidden',"value"=>$selectedplan));
                           }else{
                            if(empty($company_list))
                                $plan_list=[]; ?>
                            <label>
                                Select PR plan
                            </label>   
                            <a href="javascript:void(0)" data-toggle="tooltip" title="Select any plan"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                            <?php 
                              echo $this->Form->input('plan_id', array('class' => 'form-control ', 'onchange' =>"redirect_selectedplan(this.value);", 'empty' => '-Select-', 'options' => $plan_list,"default"=>$selectedplan,'label'=>false));
                            }
                   if(!empty($company_list)){         
                    // if(!empty($selectedplan)&&($remaingPR>0|| ($action==4&&$action==$this->data[$model]['status']))){
                    
                    if(!empty($selectedplan)&&$remaingPR>0){
                        echo $this->Form->input('id');
                        echo $this->Form->input('submittype',array("type"=>"hidden",'value'=>'','id'=>"submittype")); ?> 
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="block-title" style="border-bottom: 2px solid #00a65a;"><span style="background-color: #00a65a;">Company Detail</span></h4>
                            </div>                        
                        </div>
                        <div class="row">                        
                            <div class="col-sm-3">
                             <label>
                            Select Newsroom
                            </label>   
                            <a href="javascript:void(0)" data-toggle="tooltip" title="Select any newsroom"><i class="fa fa-question-circle" aria-hidden="true"></i> </a>
                       
                            <?php echo $this->Form->input('company_id', array('class' => 'form-control ', 'onchange' =>"load_company_detail(this.value);", 'empty' => '-Select-', 'options' => $company_list,'label'=>false)); 
                            ?>

                            </div> 
                            <div class="col-sm-12 add-company-btn">
                                <p>If your company is not listed here click here to <?php echo $this->Html->link('Add new company',array('controller'=>'users','action'=>'create-newsroom'));?></p>
                                
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="block-title" style="border-bottom: 2px solid #00a65a;"><span style="background-color: #00a65a;">Contact Detail</span></h4>
                            </div>                        
                        </div>
                        <div class="row">                        
                            <div class="col-sm-3">
                                <label>
                                Contact Name
                                </label>   
                                <a href="javascript:void(0)" data-toggle="tooltip" title="Fill contact name here"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                                <?php echo $this->Form->input('contact_name', array('class' => 'form-control ', 'id' => 'contact_name', 'type' => 'text',"required"=>"required","maxLength"=>"50",'label'=>false)); ?>
                            </div>
                            <div class="col-sm-3">
                                <label>
                                Email
                                </label>   
                                <a href="javascript:void(0)" data-toggle="tooltip" title="Fill email here"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                                <?php echo $this->Form->input('email', array('class' => 'form-control ', 'id' => 'email', 'type' => 'text',"required"=>"required",'label'=>false)); ?>
                            </div> 
                        </div> 
                         <div class="row">                                                
                            <div class="col-sm-3">
                                <label>
                                Phone
                                </label>   
                                <a href="javascript:void(0)" data-toggle="tooltip" title="Fill phone number here"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                                <?php echo $this->Form->input('phone', array('class' => 'form-control', 'id' => 'phone', 'type' => 'text',"required"=>"required",'minLength'=>"10",'maxLength'=>"15",'onkeypress'=>"return isNumber(event)",'label'=>false)); ?>
                            </div> 
                            <div class="col-sm-3">
                                 <label>
                                Zip Code
                                </label>   
                                <a href="javascript:void(0)" data-toggle="tooltip" title="Fill zip code here"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                                <?php echo $this->Form->input('zip_code', array('class' => 'form-control', 'id' => 'zip_code', 'type' => 'text','minLength'=>"5",'maxLength'=>"6","required"=>"required",'onkeypress'=>"return isNumber(event)",'label'=>false)); ?>
                            </div> 
                        </div> 
                        
                        <div class="row">
                            <div class="col-sm-12">
                               
                                <h4 class="block-title" style="border-bottom: 2px solid #00a65a;"><span style="background-color: #00a65a;">Press Release Detail</span></h4>
                            </div>                        
                        </div>
                        <div class="row">                        
                            <div class="col-sm-12">
                                <label>
                                Title
                                </label>   
                                <a href="javascript:void(0)" data-toggle="tooltip" title="Fill title here"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                                <?php 
                                echo $this->Form->input('title', array('class' => 'form-control ', 'type' => 'text','maxlength'=>"255",'onkeydown'=>'countword(this.value,"title_count");','onchange'=>"countword(this.value,'prsummary'); checktotalwords();",'autocomplete'=>"off",'label'=>false));

                                if($planDetail['Plan']['plan_type']=='single'&&$planDetail['PlanCategory']['word_limit']>0) 
                                echo "Total word <span wrd='0' id='title_count'></span>";
                            ?>
                            </div> 
                        </div> 
                        <div class="row">     
                            <div class="col-sm-12">
                                <label>
                                Subtitle
                                </label>   
                                <a href="javascript:void(0)" data-toggle="tooltip" title="Fill sub-title here"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                                <?php
                            echo $this->Form->input('summary', array('class' => 'form-control ', 'type' => 'textarea','label'=>"Subtitle",'maxlength'=>"350",'onchange'=>"countword(this.value,'prsummary'); checktotalwords();",'autocomplete'=>"off",'label'=>false));

                            if($planDetail['Plan']['plan_type']=='single'&&$planDetail['PlanCategory']['word_limit']>0)
                              echo "Total word <span wrd='0' id='prsummary'></span>";
                                 ?>
                            </div> 
                        </div> 
                        <div class="row">                        
                            <div class="col-sm-12"> 
                                <label>
                                Body
                                </label>   
                                <a href="javascript:void(0)" data-toggle="tooltip" title="Fill release details here"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                                <?php echo $this->Form->input('body', array('class' => 'ckeditor form-control ', 'type' => 'textarea', 'rows' => 10,'autocomplete'=>"off",'label'=>false));
                                    $bodycontent='';
                                   if(!empty($this->request->data['PressRelease']['body'])){
                                        $content=str_replace(array("<li>","</li>"),array(" "," "),$this->request->data['PressRelease']['body']);
                                        $bodycontent= strip_tags($content);
                                    } 

                                 echo $this->Form->input('bodyhidden', array('class' => 'form-control ', 'type' => 'hidden','value'=>$bodycontent,'id'=>"PressReleaseBodyHidden"));

                                if($planDetail['Plan']['plan_type']=='single'&&$planDetail['PlanCategory']['word_limit']>0)
                                  echo "Total word <span wrd='0' id='prbody'></span>";
                                 ?>
                            </div>
                            <?php  
                        echo $this->Form->input('remaing_pr',array("type"=>'hidden',"value"=>$remaingPR,'id'=>"remaing_pr"));    
                        if($planDetail['Plan']['plan_type']=='single'&&$planDetail['PlanCategory']['word_limit']>0){
                            echo $this->Form->input('word_limit',array("type"=>'hidden',"value"=>$planDetail['PlanCategory']['word_limit'],'id'=>"pr_word_limit"));

                           

                           // echo $this->Form->input('add_word_amount',array("type"=>'hidden',"value"=>$planDetail['Plan']['add_word_amount'],'id'=>"pr_add_word_amount"));
                        }
                        echo $this->Form->input('category_limit',array("type"=>'hidden',"value"=>$planDetail['Plan']['category_limit'],'id'=>"pr_category_limit"));
                        echo $this->Form->input('msa_limit',array("type"=>'hidden',"value"=>$planDetail['Plan']['msa_limit'],'id'=>"pr_msa_limit"));

                        echo $this->Form->input('state_limit',array("type"=>'hidden',"value"=>$planDetail['Plan']['state_limit'],'id'=>"pr_state_limit"));
                        
                        ?>  

                        </div> 

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
                                    <label>Keyword <?php echo $label;?></label>
                                    <a href="javascript:void(0)" data-toggle="tooltip" title="Fill seo keywords here"><i class="fa fa-question-circle" aria-hidden="true"></i></a> 
                                <?php 
                                if(isset($this->data['PressSeo'][$kloop]['id'])&&!empty($this->data['PressSeo'][$kloop]['id'])){
                                    echo $this->Form->input("PressSeo.$kloop.id",array("type"=>'hidden',"value"=>$this->data['PressSeo'][$kloop]['id']));
                                }
                                echo $this->Form->input("PressSeo." .$kloop. ".keyword", array('class' => 'form-control ','label' => false, 'type' => 'text','id'=>"PressSeoKeyword$kloop"));
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
                             <a class='btn btn-info' id='keybtn' href="javascript:void(0)" fnum='<?php echo $countk;?>' onclick="addmoref('skeyword-','keybtn','rkeybtn','PressSeoKeyword');">Add More Keywords</a>
                        <a class='btn btn-info btn-danger' id='rkeybtn' href="javascript:void(0)" style="<?php if($countk<=1){ echo 'display: none;'; }?>" onclick="removefield('skeyword-','keybtn','rkeybtn','PressSeoKeyword');">Remove</a>
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
                                <label>Release Date *</label>
                                <a href="javascript:void(0)" data-toggle="tooltip" title="Please choose date and time"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                                <br />
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
                                <label>MSA</label>
                                <a href="javascript:void(0)" data-toggle="tooltip" title="Fill msa here">
                                    <i class="fa fa-question-circle" aria-hidden="true"></i>
                                </a>
                                <?php echo $this->Form->input('Msa.Msa', array('class' => 'form-control ', 'label' => false, 'options' => $mas_list,'empty' => '-Select-','multiple'=>true,'class'=>"bootselect form-control",'id'=>"prmsaid","required"=>"required",'onchange'=>"msachanges();")); ?>
                            </div>
                        <?php } ?>

                        <?php if($planDetail['PlanCategory']['is_country_allowed']){  ?>                 
                            <div class="col-sm-4">
                                <label>Country</label>
                                <a href="javascript:void(0)" data-toggle="tooltip" title="Please select country">
                                    <i class="fa fa-question-circle" aria-hidden="true"></i>
                                </a>
                                <?php echo $this->Form->input('country_id', array('class' => 'form-control ', 'onchange' => "get_state(this.value)", 'options' => $country_list, 'empty' => '-Select-','label'=>false,"required"=>"required")); ?>
                            </div> 
                         <?php } ?>   
                         <?php if($planDetail['PlanCategory']['is_state_allowed']){  ?>  
                          <div class="col-sm-4" id="state_div">
                                 <label>State</label>
                            <a href="javascript:void(0)" data-toggle="tooltip" title="Please select state">
                                <i class="fa fa-question-circle" aria-hidden="true"></i>
                            </a>
                                <?php echo $this->Form->input('State.State', array('class' => 'form-control ', 'type' => 'select','options' => $state_list, 'div' => 'form-group', 'class' => 'form-control state-select','multiple'=>true,'onchange'=>'statechange();','label'=>false)); ?>
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
                            <div class="col-sm-4">
                            <?php 
                            if(isset($this->data['PressImage'][$i]['id'])&&!empty($this->data['PressImage'][$i]['id'])){ ?>
                                <?php 
                                $imgurl='files/company/press_image/'.$this->data['PressImage'][$i]['image_path'].'/'.$this->data['PressImage'][$i]['image_name'];

                                $removeFun="removeUploadedImage('PressImageName$i',".$this->data['PressImage'][$i]['id'].");"; 
                                echo "<div id='remove-PressImageName$i'>".$this->Html->image(SITEURL.$imgurl, array('id'=>"imgprev-PressImageName$i"))."<a href='javascript:void(0)' class='btn btn-remove btn-danger' onclick=$removeFun>X</a></div>";

                                $imgname=(!empty($this->data['PressImage'][$i]['image_name']))?$this->data['PressImage'][$i]['image_name']:""; 
                                ?> 
                            <?php  } 
                            $fileType=((!isset($this->data['PressImage'][$i]['id']) && $i=='0') )?"file":"hidden";
                            $fieldId="PressImageName$i";
                            ?>
                            <label>Image <?php echo $label;?></label>
                            <a href="javascript:void(0)" data-toggle="tooltip" title="Choose image">
                                <i class="fa fa-question-circle" aria-hidden="true"></i>
                            </a>
                            <?php 
                            echo $this->Form->input("PressImage.$i.image_name", array('label' => false, 'type' =>$fileType,'id'=>$fieldId,"onchange"=>"uploadImage(this,this.value,'$fieldId')"));

                            echo $this->Form->input("PressImage.$i.image_path", array('label' =>false, 'type' => 'hidden','id'=>$fieldId.'-image_path'));
                                
                                ?>
                            </div>
                            <div class="<?php echo "col-sm-4";?>">
                                <label>Caption <?php echo $label;?></label>
                                <a href="javascript:void(0)" data-toggle="tooltip" title="Fill caption here">
                                    <i class="fa fa-question-circle" aria-hidden="true"></i>
                                </a>
                                <?php echo $this->Form->input("PressImage.$i.describe_image", array('label' => false, 'type' => 'text','id'=>"PressImageDesc$i")); ?>
                            </div>
                            <div class="<?php echo "col-sm-4";?>">
                                 <label>Alt Text <?php echo $label;?></label>
                                <a href="javascript:void(0)" data-toggle="tooltip" title="fill alt text here">
                                    <i class="fa fa-question-circle" aria-hidden="true"></i>
                                </a>
                                <?php echo $this->Form->input("PressImage.$i.image_text", array('type' => 'text', 'label' =>false,'id'=>"PressImageAltName$i")); ?>
                            </div>  
                        </div> 
                    <?php } ?> 
                        <?php
                        /*
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

                                    $imgname=(!empty($this->data['PressImage'][$i]['image_name']))?$this->data['PressImage'][$i]['image_name']:"";
                                    echo $this->Form->input("PressImage.$i.oldimage_name", array('value' =>$imgname, 'type' => 'hidden','id'=>"PressImageOldName$i"));
                                    
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
                        <?php }  */?>

                        <div class="row primages-btns">
                            <a class='btn btn-info' id='pimagebtn' href="javascript:void(0)" fnum='<?php echo $countk;?>' onclick="addmoref('pimage-','pimagebtn','rimgbtn','PressImageName');">Add More Images</a>
                        <a class='btn btn-info btn-danger' id='rimgbtn' href="javascript:void(0)" style="<?php if($countk<=1){ echo 'display: none;'; }?>" onclick="removefield('pimage-','pimagebtn','rimgbtn','PressImageName','PressImageDesc','PressImageAltName','PressImageOldName');">Remove</a>
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
                                     <label>Youtube URL <?php echo $label;?></label>
                                        <a href="javascript:void(0)" data-toggle="tooltip" title="fill youtube url here">
                                            <i class="fa fa-question-circle" aria-hidden="true"></i>
                                        </a>
                                    <?php echo $this->Form->input("PressYoutube.$yloop.url", array('label' => false, 'type' => 'text','id'=>"PressYoutubeUrl$yloop")); ?>
                                </div>
                                <div class="col-sm-3">
                                    <label>Describe video <?php echo $label;?></label>
                                    <a href="javascript:void(0)" data-toggle="tooltip" title="fill youtube url here">
                                        <i class="fa fa-question-circle" aria-hidden="true"></i>
                                    </a>
                                    <?php echo $this->Form->input("PressYoutube.$yloop.description", array('label' => false, 'type' => 'text','id'=>"PressYoutubeDesc$yloop")); ?>
                                </div>                   
                            </div> 
                        <?php } ?>
                        <div class="row ylink-btns">
                             <a class='btn btn-info' id='ylinkbtn' href="javascript:void(0)" fnum='<?php echo $countk;?>' onclick="addmoref('ylinks-','ylinkbtn','rlinkbtn','PressYoutubeUrl');">Add More Youtube Links</a>
                        <a class='btn btn-info btn-danger' id='rlinkbtn' href="javascript:void(0)" style="<?php if($countk<=1){ echo 'display: none;'; }?>" onclick="removefield('ylinks-','ylinkbtn','rlinkbtn','PressYoutubeUrl','PressYoutubeDesc');">Remove</a>
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
                                <div class="col-sm-3">
                                     <label>Podcast Embed code<?php echo $label;?></label>
                                        <a href="javascript:void(0)" data-toggle="tooltip" title="fill podcast embed code here">
                                            <i class="fa fa-question-circle" aria-hidden="true"></i>
                                        </a>
                                    <?php echo $this->Form->input("PressPoadcast.$yloop.url", array('label' => false, 'type' => 'textarea','id'=>"PressPoadcastUrl$yloop")); ?>
                                </div>
                                <div class="col-sm-3">
                                     <label>Describe poadcast<?php echo $label;?></label>
                                    <a href="javascript:void(0)" data-toggle="tooltip" title="fill describe poadcast here">
                                        <i class="fa fa-question-circle" aria-hidden="true"></i>
                                    </a>
                                    <?php echo $this->Form->input("PressPoadcast.$yloop.description", array('label' => false, 'type' => 'text','id'=>"PressPoadcastDesc$yloop")); ?>
                                </div>                   
                            </div> 
                        <?php } ?>
                        <div class="row ylink-btns">
                             <a class='btn btn-info' id='podlinkbtn' href="javascript:void(0)" fnum='<?php echo $countk;?>' onclick="addmoref('podlink-','podlinkbtn','rpodlinkbtn','PressPoadcastUrl');">Add More Podcasts</a>
                        <a class='btn btn-info btn-danger' id='rpodlinkbtn' href="javascript:void(0)" style="<?php if($countk<=1){ echo 'display: none;'; }?>" onclick="removefield('podlink-','podlinkbtn','rpodlinkbtn','PressPoadcastUrl','PressPoadcastDesc');">Remove</a>
                        </div>

                        <?php if($planDetail['PlanCategory']['is_translated']){?>
                        <div class="row">
                             <div class="col-sm-12"> 
                            <?php 
                           echo "<div class='form-group'> ";
                            $options = array('1' => 'Yes','0' => 'No');
                            $attributes = array(
                                'legend' => "Add translate page", 
                                'class'=>'pr_trans',
                                'onchange'=>"praddtocart()",
                            );
                            echo $this->Form->radio('translated_page',$options, $attributes);
                            echo "</div>";
                             ?>  
                         </div>

                        </div>
                       <?php  } ?>

                       
                       

                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="block-title" style="border-bottom: 2px solid #00a65a;"><span style="background-color: #00a65a;">Additional feature</span></h4>

                            </div>                        
                        </div>
                    
                        <div class="row">
                             <div class="col-sm-12">
                                 <label>Additional Features</label>
                                <a href="javascript:void(0)" data-toggle="tooltip" title="fill describe poadcast here">
                                    <i class="fa fa-question-circle" aria-hidden="true"></i>
                                </a>
                            <?php  
                            echo $this->Form->input('Distribution.Distribution', array('class' => 'form-control ', 'type' => 'select','options' => $distribution_list, 'div' => 'form-group additional-features', 'class' => 'form-control','multiple'=>'checkbox','label'=>false,'id'=>'additional-feature'));
                             ?>  
                         </div>
                         <?php 
                            $email_list_divClass="display: none;";
                            if(isset($this->request->data['Distribution'])&&!empty($this->request->data['Distribution'])){
                                foreach($this->request->data['Distribution'] as $key => $distribution) {
                                    if($distribution['id']==8)
                                        $email_list_divClass="display: block;";
                                }
                            }   
                         
                            if(!empty($email_list)){
                                foreach($email_list['Own media list'] as $listId =>$title){
                                    $count=$this->Custom->countMediaEmails($listId);
                                    $email_list['Own media list'][$listId]=$title." (".$count.")";
                                }
                                foreach($email_list['Target Email List'] as $listId =>$title){
                                    $count=$this->Custom->countMediaEmails($listId);
                                    $email_list['Target Email List'][$listId]=$title." (".$count.")";
                                }
                            }   
                         ?>
                        <div class="col-sm-12" id="email_list_div" style="<?php echo $email_list_divClass;?>">
                            <?php echo $this->Form->input('PressRelease.list_id', array('class' => 'form-control ', 'type' => 'select','options' =>$email_list, 'div' => 'form-group', 'class' => 'form-control email-select','multiple'=>false,'onchange'=>'selectemaillistchange(this.value);','empty'=>"Select email list",'label'=>"Please select email list *")); 
                            $emCounts=0;
                            if(isset($this->request->data['PressRelease']['list_id'])&&!empty($this->request->data['PressRelease']['list_id'])){
                                $emCounts=$this->Custom->countMediaEmails($this->request->data['PressRelease']['list_id']);
                            }
                            ?>
                            <p id="totalemails" class="<?php if($emCounts > 0){ echo "show"; }else{ echo "hide"; }?>">Total media emails : <strong><?php echo $emCounts; ?></strong></p>

                            <p>If your Email list is not listed here click here to <?php echo $this->Html->link('Add new email list',array('controller'=>'users','action'=>'add-email-list'));?></p>
                        </div>   


                        </div> 
                         <div class="row">
                            <div class="col-sm-12">
                                <h4 class="block-title" style="border-bottom: 2px solid #00a65a;"><span style="background-color: #00a65a;">Agreement</span></h4>
                                <label>Read: Agree or disagree with Terms of Services</label>
                                <a href="javascript:void(0)" data-toggle="tooltip" title="" data-original-title="Scroll to read the terms of services agreement, then click on “Agree” before proceeding to submit news release.">
                                    <i class="fa fa-question-circle" aria-hidden="true"></i>
                                </a>
                            </div>                        
                        </div>
                        <div class="row">                        
                            <div class="col-sm-12 termcontentbox">
                            <?php 
                                include 'term_content.ctp';
                            ?>
                            </div> 
                        </div>
                        <div class="error-message toserrormsg">* Please read agreement carefully to proceed to post your PR.</div>
                    <?php  echo $this->Form->end();
                        } // end if remaing PR condition
                    } // end if Company List condition
                }
                    ?>
                </div>
            <?php } ?>
             <?php 
if(!empty($company_list)){
    if(!empty($selectedplan)&&$remaingPR>0){

 ?>
 <div class="ew-cart-btns-block full row">
<div class="button_pr col-sm-3">
 <a href="javascript:void(0)" onclick="submitform('preview');" class="btn btn-info">Preview Press Release</a>
  <p>Before you submit PR check preview of this PR.</p>
</div>
     <!-- <div class="button_pr col-sm-3">
         <a href="javascript:void(0)" onclick="submitform('indraft');" class="btn btn-primary">Save PR in draft</a>
         <p>If you do not want to submit this PR or want to edit letter then save in draft so you will not lost your filled PR content.</p>
    </div> -->
</div>
<?php } } ?>
            </div>
        </div>
    </div>
    <div id="sidebar" class="col-lg-3 forse-customwidth">
        <div class="sidebar__inner">
        <?php  echo $this->element('pr_cart');?>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>

<script>  
$('#PressReleaseTos').click(function(){
  if($("#PressReleaseTos").prop("checked")==true){
       $(".button_pr a.btn").removeClass('disabled');
       $(".button_pr a.btn").removeAttr('disabled');
       $(".toserrormsg").text("* Please read agreement carefully to proceed to post your PR.").hide();
    }else{
        $(".button_pr a.btn").addClass('disabled');
        $(".button_pr a.btn").attr('disabled');
        $(".toserrormsg").show();

    }

});
$('.term-content').scroll(function () {
    if($("#PressReleaseTos").prop("checked")==true){
       $(".button_pr a.btn").removeClass('disabled');
       $(".button_pr a.btn").removeAttr('disabled');
       $(".toserrormsg").text("* Please read agreement carefully to proceed to post your PR.").hide();
    }else{
          if ($(this).scrollTop() == $(this)[0].scrollHeight - $(this).height()) {
            $(".tosdiv").removeClass("hide");
            $(".toserrormsg").text("* Please click on the I Agree checkbox above, then proceed to post your PR.").show();

         }
         $(".button_pr a.btn").addClass('disabled');
         $(".button_pr a.btn").attr('disabled');   
    }
});


function submitform(submittype){
    $("#submittype").val(submittype);
    $("#PressReleaseform").submit();
}
 $(function () {
    $(".bootselect,.state-select,.distribute-select").select2({
        tags: true
    }); 

});  
$(document).ready(function(){ 
    if($("#PressReleaseTos").prop("checked")==true){
        $(".tosdiv").removeClass("hide");
       $(".button_pr a.btn").removeClass('disabled');
       $(".button_pr a.btn").removeAttr('disabled');
       $(".toserrormsg").text("* Please read agreement carefully to proceed to post your PR.").hide();
    }else{
        $(".button_pr a.btn").addClass('disabled');
        $(".button_pr a.btn").attr('disabled','disabled');
        $(".toserrormsg").hide();

    }

    $(".release_date").datepicker({
        dateFormat: "dd-mm-yy",
        changeMonth: true,
        changeYear: true,
        minDate: 0,
    });

    var category_limit=$("#pr_category_limit").val();
    var $catCheckboxes = $('.category_checkbox input[type="checkbox"]');
    $catCheckboxes.change(function(){
    var countCatCheckboxes = $catCheckboxes.filter(':checked').length;
    var checkCartItem=$("#cartplanlist li").length; 
        if(countCatCheckboxes>category_limit){
            praddtocart(countCatCheckboxes);
        }else if(countCatCheckboxes<=category_limit&&checkCartItem>0&&$("#cartplanlist li").hasClass("category_charges")){
            praddtocart(countCatCheckboxes);
        }
    });
});



function redirect_selectedplan(selected) {
    $("#AjaxLoading").show();
    window.location.replace(SITEURL+"users/add-press-release/"+selected);
    /*$.ajax({
        type: 'POST',
        url: '<?php echo SITEURL; ?>ajax/checkplanincart',
        data: {plan_id:selected},
        async: false,
        success: function (data) { 
            $("#errmsg").remove();
             var obj = JSON.parse(data);
             if(obj.status!='failed'){
                window.location.replace(SITEURL+"users/add-press-release/"+selected);
             }else{
                $("#PressReleasePlanId").after("<span id='errmsg' class='error text-danger '>"+obj.message+"</p>"); 
             }
        }}
    ); */
    
}
function get_state(country_id) {
    $("#AjaxLoading").show();
    $.ajax({
        type: 'POST',
        url: '<?php echo SITEURL; ?>ajax/get_state',
        data: {country_id: country_id},
        async: false,
        success: function (data) {
            $("#AjaxLoading").hide();
            $("#state_div").html(data);
        }}
    );
}

function load_company_detail(value) {
    if (value != 0 && value != '') {
        $("#AjaxLoading").show();
        $.ajax({
            type: 'POST',
            url: '<?php echo SITEURL; ?>ajax/load_company_detail',
            data: {
                company_id: value
            },
            async: false,
            success: function (data) {
                $("#AjaxLoading").hide();
                var obj = JSON.parse(data);
                $("#contact_name").val(obj.contact_name);
                $("#email").val(obj.email);
                $("#phone").val(obj.phone_number);
                $("#zip_code").val(obj.zip_code);
            }}
        );
    }
}

function search_company() {
     $("#AjaxLoading").show();
    var company_name = $("#check_company_name").val();
    $.ajax({
        type: 'POST',
        url: '<?php echo SITEURL; ?>ajax/search_company',
        data: {
            company_name: company_name
        },
        async: false,
        success: function (response) {
            $("#AjaxLoading").hide();
            var obj=JSON.parse(response);
            $("#check_company_message").html(obj.message).show();
        }}
    );
}


  var sidebar = new StickySidebar('#sidebar', {
    topSpacing: 20,
    bottomSpacing: 20,
    containerSelector: '#main-content',
    innerWrapperSelector: '.sidebar__inner'
  });



function msachanges() {
    var msa_limit=$("#pr_msa_limit").val();
    var countmsa = $("#msadropdn .select2-selection__choice").length;
    var checkCartItem=$("#cartplanlist li").length; 
    if(countmsa>msa_limit){
        praddtocart();
    }else if(countmsa<=msa_limit&&checkCartItem>0&&$("#cartplanlist li").hasClass("msa_charges")){
        praddtocart();
    }
}

function statechange() {
    var state_limit=$("#pr_state_limit").val();
    var countstate = $("#state_div .select2-selection__choice").length;

    var checkCartItem=$("#cartplanlist li").length; 
    if(countstate>state_limit){
        praddtocart();
    }else if(countstate<=state_limit&&checkCartItem>0&&$("#cartplanlist li").hasClass("state_charges")){
        praddtocart();
    }
} 

$(document).ready(function(){
    $("#PressReleaseform").validate();
    var $featureCheckboxes = $('.additional-features input[type="checkbox"]');
    $featureCheckboxes.change(function(){
    var disid=$(this).val();
    var checkCartItem=$("#cartplanlist li").length; 
        if($(this).prop("checked") == true){
            if(disid!=8){
                praddtocart("",disid);
            }else{
                $("#PressReleaseListId option:first").attr('selected','selected');
                $("#PressReleaseListId").attr("required","required");
                $("#email_list_div").show();
                $("#totalemails strong").text("0");
            }
            
        }else{
            if(disid==8){
                $("#email_list_div").hide();
            }
            praddtocart("","",disid,'1');
        } 
    });


});

function selectemaillistchange(listId){
    if(listId!=""){
        $(".emaillist-errormsg").hide();
        praddtocart("","8",'',listId);
        countemails(listId);
    }else{
        $(".emaillist-errormsg").show();
        praddtocart("","8",'8');
        $("#totalemails").addClass("hide");
        $("#totalemails strong").text("0");

    }
}

function countemails(listId){
    $.ajax({
        type: 'get',
        url: SITEURL+'ajax/countemails',
        data: {id:listId},
        success: function (count) {
           $("#AjaxLoading").hide(); 
           $("#totalemails").removeClass('hide');
           $("#totalemails strong").text(count);
        }
    });
}

 

  function getprcart() {
      $("#AjaxLoading").show(); 
        var totalword=sumofwords();
        var plan_id=$("#PressReleasePlanId").val();
        var addAmount=$("#pr_add_word_amount").val();
        var selectedfeatures = new Array();
        $(".additional-features input[type='checkbox']:checked").each(function(){
            selectedfeatures.push($(this).val());
        });
        var pressReleaseId="";
        if($("#PressReleaseId").val()){
            pressReleaseId=$("#PressReleaseId").val();
        }
        // if(selectedFeature.length){
        //     jsonSelectedFeature = JSON.stringify(selectedFeature);
        // }
        $.ajax({
            type: 'POST',
            url: SITEURL+'ajax/getprcart',
            data: {plan_id: plan_id,prId:pressReleaseId,selectedfeatures:selectedfeatures},
            async: false,
            success: function(response){
                $("#AjaxLoading").hide();
                var obj=JSON.parse(response); 
                if(obj.status=="false"){
                 $("#popuptitle").text('Cart');
                 $("#popalert-message").html(obj.message);
                 $("#popalert").modal("show");
                }else{ 
                $("#buy-plan-error").hide();
                $("#cartsubtotal").text("$"+obj.data.totals.subtotal);
                $("#carttotalamout").text("$"+obj.data.totals.total);
                $("#clear-cart").show();
                $("#applypromobox").show();
                if(obj.data.totals.discount!=""&&obj.data.totals.discount!="0.00"){
                    $("#disamount").text("$"+obj.data.totals.discount);
                    $("#disamount-box").show();
                }
                if(obj.data.totals.tax!=""&&obj.data.totals.tax!="0.00"){
                    $("#carttax").text("$"+obj.data.totals.tax);
                    $("#carttax-box").show();
                }

                if(obj.data.totals.subtotal!=""&&obj.data.totals.subtotal!="0.00"){ 
                    $("#cartsection").show();
                }else{
                    $("#cartsection").hide();
                } 
                  pr_list(obj.data.prlist);
                  pr_featurelist(obj.data.feature);
                } 
            }}
        );
  }




 
function praddtocart(catcount="0",featureId="0",isremovefeature="0",listId=""){
        var featurecount=0;
        $("#AjaxLoading").show();
        if(catcount==0){
            var catcount =$('.category_checkbox input[type="checkbox"]').filter(':checked').length;
        }
        var countmsa = $("#msadropdn .select2-selection__choice").length;

         var statecount = $("#state_div .select2-selection__choice").length;
        if(countmsa==0){
            countmsa=$("#msadropdn :selected").length;
        } 

        if(statecount==0){
            statecount=$("#StateState :selected").length;
        }

        if(featurecount==0){
          var featurecount=$('.additional-features input[type="checkbox"]').filter(':checked').length;
        }    

        var selectedfeatures = new Array();
        $(".additional-features input[type='checkbox']:checked").each(function(){
            selectedfeatures.push($(this).val());
        });


        var trans_applied=$(".pr_trans:checked").val();

        var totalword=sumofwords();
        var plan_id=$("#PressReleasePlanId").val();
        var PressReleaseId=$("#PressReleaseId").val();
        $.ajax({
            type: 'POST',
            url: SITEURL+'ajax/praddtocart',
            data: {pr_id:PressReleaseId,totalword: totalword,plan_id: plan_id,catcount:catcount,msacount:countmsa,statecount:statecount,'feature_id':featureId,featurecount:featurecount,isremovefeature:isremovefeature,trans_applied:trans_applied,list_id:listId,selectedfeatures:selectedfeatures},
            async: false,
            success: function(response){
                $("#AjaxLoading").hide();
                var obj=JSON.parse(response); 
                if(obj.status!="false"){
                $("#buy-plan-error").hide();
                $("#cartsubtotal").text("$"+obj.data.totals.subtotal);
                $("#carttotalamout").text("$"+obj.data.totals.total);
                $("#clear-cart").show();
                $("#applypromobox").show();
                if(obj.data.totals.discount!=""&&obj.data.totals.discount!="0.00"){
                    $("#disamount").text("$"+obj.data.totals.discount);
                    $("#disamount-box").show();
                }
                if(obj.data.totals.tax!=""&&obj.data.totals.tax!="0.00"){
                    $("#carttax").text("$"+obj.data.totals.tax);
                    $("#carttax-box").show();
                }

                if(obj.data.totals.subtotal!=""&&obj.data.totals.subtotal!="0.00"){ 
                    $("#cartsection").show();
                }else{
                    $("#cartsection").hide();
                } 
                pr_list(obj.data.prlist);
                pr_featurelist(obj.data.feature);
                } 
            }}
        );
    }
   function pr_list(plans) { 
        var html="";  
        $.each(plans, function(index,data) {  
             html +="<li id='plan-"+data.plan_id+"' class='"+data.class+"' ><span class='float-left'>"+data.title+"</span><span class='float-right'>"+data.amount+"</span></li>";
        }); 
        $("#cartplanlist").html(html); 
    }

     function pr_featurelist(features) { 
        var html=""; 
        $.each(features, function(index,data) {  
             html +="<li id='plan-"+data.plan_id+"' class='"+data.class+"' ><span class='float-left'>"+data.name+"</span><span class='float-right'>"+data.price+"</span></li>";
        }); 
        $("#cartfeaturelist").html(html); 
    }


function countword(values,responseId) {
    var words = $.trim(values).length ? values.match(/\S+/g).length : 0;
     $('#'+responseId).text(words); 
     // $('#'+responseId).attr('wrd',words); 
     return  words;
}    

function sumofwords() {
    var title_count=countword($("#PressReleaseTitle").val(),"title_count");
    var prsummary= countword($("#PressReleaseSummary").val(),"prsummary");
    var prbody=   countword($("#PressReleaseBodyHidden").val(),"prbody");
    var totalword=eval(title_count)+eval(prsummary)+eval(prbody);
    return totalword;
}

function checktotalwords(){
    var prWordLimit=$("#pr_word_limit").val();
    var totalword= sumofwords();
    var checkCartItem=$("#cartplanlist li").length;
    if(totalword<=prWordLimit&&checkCartItem>0&&$("#cartplanlist li").hasClass("words_charges")){ 
        praddtocart();
    }else{
        if(totalword>prWordLimit){ 
            praddtocart();
        }else{
            getprcart();
        }
    }
}
function remove_htmltags(actionType='',content='') {
    // var content=$("#PressReleaseBody").val();
    $("#AjaxLoading").show();
    $.ajax({
        type: 'POST',
        url: SITEURL+'ajax/removehtml',
        data: {content:content},
        // async: false,
        success: function (data) {
            $("#AjaxLoading").hide(); 
            $("#PressReleaseBodyHidden").val(data); 
            if(actionType=='change'){
                checktotalwords();
            }
        }}
    );
} 

/*$('#PressReleaseBody').wysihtml5({
     supportTouchDevices: true,
     toolbar: {
        "font-styles": true, //Font styling, e.g. h1, h2, etc. Default true
        "emphasis": true, //Italics, bold, etc. Default true
        "lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
        "html": true, //Button which allows you to edit the generated HTML. Default false
        "link": true, //Button to insert a link. Default true
        "image": false, //Button to insert an image. Default true,
        "color": false, //Button to change color of font  
        "blockquote": false, //Blockquote  
        
      },
      parserRules:  wysihtml5ParserRules,
      "stylesheets": [SITEURL+"css/customeditor.css"],
      "events": {
        "change:composer": function() {  
            remove_htmltags('change'); 
        },
    }
});
*/

 
var editor = CKEDITOR.replace('PressReleaseBody',{showWordCount: true, filebrowserUploadUrl: SITEURL+"ajax/mediafileupload?typ=1"});
 
editor.on('change',function(){
    //var bodycontent = editor.getData();
        var content = editor.getData();

     remove_htmltags('change',content); 
});


function replace_tags(url,tagId) {
    $("#AjaxLoading").show();
    var seotag=$("#"+tagId).val(); 
    var content=$("#PressReleaseBody").val();
    $.ajax({
        type: 'POST',
        url: SITEURL+'ajax/addseotaginbodycontent',
        data: {seotag: seotag,tag_url:url,content:content},
       // async: false,
        success: function (data) {
            $("#AjaxLoading").hide(); 
            $("#PressReleaseBody").val(data);
        }}
    );
}

$(document).ready(function(){ 
    checktotalwords(); 
}); 

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
            if(inputFieldId=="PressImageName"){
                $("#"+inputFieldId+fnum).attr('type',"file");
            }
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
        if(inputFieldId1=="PressImageName"){
            $("#"+fieldId+prev).attr('type',"file");
            var inputId=inputFieldId1+prev;
            var PrImgId=$('#PressImage'+prev+'Id').val();
                if(PrImgId==undefined){
                    PrImgId="";
                }   
            removeUploadedImage(inputId,PrImgId)
        }
    }
}

function readURL(input,inputId) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
      $('#blah').attr('src', e.target.result);
      $("#"+inputId).hide();
      var removeFun="removeUploadedImage('"+inputId+"');"; 
      $("#"+inputId).after('<div id="remove-'+inputId+'"><img id="imgprev-'+inputId+'" src='+e.target.result+' /><a class="btn btn-remove btn-danger" href="javascript:void(0)" onclick="'+removeFun+'">X</a></div>');
    }
    reader.readAsDataURL(input.files[0]);
  }
}

function removeUploadedImage(inputId,prImgId="") {
     
    var oldimage=$("#imgprev-"+inputId).attr("src"); 
    $.ajax({
        type: 'POST',
        url: SITEURL+'ajax/removePrImage',
        data: {oldimage:oldimage,prImgId:prImgId},
        success: function (data) {
            if($("#PressReleaseTos").prop("checked")==true){
                $(".button_pr a.btn").removeClass('disabled');
                $(".button_pr a.btn").removeAttr('disabled');
            }
            $("#imagespiner").remove();
            var obj = JSON.parse(data);
            if(obj.status=='success'){ 
              $("#remove-"+inputId).remove();  
              $("#"+inputId).attr("type","file").val("");
              $("#"+inputId+'-image_path').val("");
              if(prImgId!=''){
                $("#"+inputId+'Id').val("");
              }
              messgae_box("PR image removed successfully.","Success","success");
              $("#"+inputId).attr("type","file").val("").show();
            }else{
               $("#"+inputId).after('<p class="error">'+obj.message+'</p>');
               messgae_box(obj.message,"Failed","failed");

            }
        }
    });
    
}

function uploadImage(image,imageTempPath,inputId){
    //$("#"+inputId).after('<div id="imagespiner"><i class="fa fa-spinner fa-spin" style="font-size:24px"></i> Uploading...</div>');
    readURL(image,inputId);
    $(".button_pr a.btn").addClass('disabled');
    $(".button_pr a.btn").attr('disabled','disabled');
    var filePath=$('#'+inputId).val(); 
    var file = image.files[0];
    var formData = new FormData();
    formData.append('formData', file);
    $.ajax({
        type: 'POST',
        url: SITEURL+'ajax/pruploadimage',
        contentType: false,
        processData: false,
        data: formData,
        success: function (data) {
           if($("#PressReleaseTos").prop("checked")==true){
                $(".button_pr a.btn").removeClass('disabled');
                $(".button_pr a.btn").removeAttr('disabled');
            }
            //$("#imagespiner").remove();
            var obj = JSON.parse(data);
            if(obj.status=='success'){
            messgae_box("PR image successfully uploaded.","Success","success");
            var img_url=obj.img_url;
              $("#imgprev-"+inputId).attr("src",img_url); 
              $("#"+inputId).attr("type","hidden").val(obj.image_name);
              $("#"+inputId+'-image_path').val(obj.image_path); 
            }else{
               $("#"+inputId).after('<p class="error">'+obj.message+'</p>');
                messgae_box(obj.message,"Failed","failed");
  
            }
        }}
    );   
}
</script>
<style type="text/css">
    a.disabled {
      pointer-events: none;
      cursor: default;
    }
</style>