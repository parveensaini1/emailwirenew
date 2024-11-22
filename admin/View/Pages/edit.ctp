<?php
echo $this->Html->css(array('/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min'));
echo $this->Html->script(array('/plugins/ckeditor/ckeditor'));
?>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="card card-default"> 
            <!-- /.card-heading -->
            <div class="card-body">
                <?php echo $this->element('submenu'); ?>
                <div class="dataTable_wrapper">
                    <?php echo $this->Form->create($model, array('novalidate', 'inputDefaults' => array('div' => 'form-group', 'class' => 'form-control')));
                        echo $this->Form->input('id'); 
                        echo $this->Form->input('old_slug',array('type'=>'hidden','value'=>$slug));
                     ?>
                    <div class="row">
                        <div class="col-sm-9">
                        <?php
                        echo $this->Form->input('title', array("class" => "form-control", "empty" => ""));
                        echo $this->Form->input('slug', array("class" => "form-control", "readonly" => "readonly"));
                        echo $this->Form->input('description', array("class" => "country_dd form-control","id"=>'editor1' ,'label' => 'Description'));
                        echo $this->Form->input('meta_title', array("class" => "country_dd form-control", 'label' => 'Meta Title'));   

                        echo $this->Form->input('meta_keyword', array("class" => "form-control", "empty" => ""));
                        echo $this->Form->input('meta_description', array("class" => "form-control", "empty" => "")); 
                        ?>
                        <div class="row">

                        <div class="col-md-6">
                            <label>&nbsp;</label>
                            <?php
                            echo $this->Form->input('status', array('div' => 'form-group',"class"=>"status-checkbox"));
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
                        <div class="col-sm-3">
                            <div class="box box-info">
                                <div class="box-header with-border">
                                  <h3 class="box-title">Select Template</h3>
                                  <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                  </div>
                                  <!-- /.box-tools -->
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body" style="">
                                  <?php     echo $this->Form->input('page_template_id',array('options'=>$page_template_list,'label'=>false,'id'=>'page_template_id','class'=>'page_template_id form-control')); ?>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <div class="box box-info">
                                <div class="box-header with-border">
                                  <h3 class="box-title">Banner Image</h3>
                                  <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                  </div>
                                </div>
                                <div class="box-body" style="">
                                <?php  
                                        $fieldId="bannerimg";
                                        $fileType=(!isset($this->data['Page']['banner_image']))?"file":"hidden";
                                        echo $this->Form->input("banner_image", array('label' => false, 'type' =>$fileType,'id'=>$fieldId,"onchange"=>"uploadImage(this,this.value,'$fieldId')"));

                                        echo $this->Form->input("banner_path", array('label' =>false, 'type' => 'hidden','id'=>$fieldId.'-image_path'));

                                        if($this->data['Page']['banner_image']){
                                            $imgurl='files/company/press_image/'.$this->data['Page']['banner_path'].'/'.$this->data['Page']['banner_image'];

                                            $removeFun="removeUploadedImage('bannerimg',".$this->data['Page']['id'].",'Page');"; 
                                            echo "<div id='remove-bannerimg'>".$this->Html->image(SITEFRONTURL.$imgurl, array('id'=>"imgprev-bannerimg"))."<a href='javascript:void(0)' class='btn btn-remove btn-danger' onclick=$removeFun>X</a></div>";
                                        }
                                  ?>
                                  <span class="">Note: Please upload width should be minimum 1500px and height should be maximum 400px;</span>
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
<script>
var editor1 = CKEDITOR.replace('editor1',{showWordCount: true, filebrowserUploadUrl: SITEFRONTURL+"ajax/mediafileupload?typ=1"});

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

function removeUploadedImage(inputId,prImgId="",model="") {
    var oldimage=$("#imgprev-"+inputId).attr("src"); 
    $.ajax({
        type: 'POST',
        url: '<?php echo SITEFRONTURL; ?>ajax/removeBannerImage',
        data: {oldimage:oldimage,prImgId:prImgId,model:model},
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
              $("#"+inputId).attr("type","file").val("").show();
              messgae_box("Banner image removed successfully.","Success","success");
            }else{
               $("#"+inputId).after('<p class="error">'+obj.message+'</p>');
               messgae_box(obj.message,"Failed","failed");

            }
        }
    });
    
}


function uploadImage(image,imageTempPath,inputId){
    $("#"+inputId).after('<div id="imagespiner"><i class="fa fa-spinner fa-spin" style="font-size:24px"></i> Uploading...</div>');
    readURL(image,inputId);
    $(".button_pr a.btn").addClass('disabled');
    $(".button_pr a.btn").attr('disabled','disabled');
    var filePath=$('#'+inputId).val(); 
    var file = image.files[0];
    var formData = new FormData();
    formData.append('formData', file);
    $.ajax({
        type: 'POST',
        url: '<?php echo SITEFRONTURL; ?>ajax/pruploadimage',
        contentType: false,
        processData: false,
        data: formData,
        success: function (data) {
            if($("#PressReleaseTos").prop("checked")==true){
                $(".button_pr a.btn").removeClass('disabled');
                $(".button_pr a.btn").removeAttr('disabled');
            }
            $("#imagespiner").remove();
            var obj = JSON.parse(data);
            if(obj.status=='success'){

            messgae_box("Banner image successfully uploaded.","Success","success");
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