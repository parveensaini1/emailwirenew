<?php echo $this->Html->script(array('jquery.validate.min','additional-methods.min'));?> 
<div class="row">
    <div class="col-lg-12">
        <div class="card card-default"> 
            <!-- /.card-heading -->
            <div class="card-body">
                 <div class="row">
                    <div class="col-lg-12">
                        <div class="row table-header-row">
                            <div class="card-heading datatable-heading">
                            <?php echo $this->Html->link('<i class="icon-list"></i> All client companies', array('controller' => $controller, 'action' => 'clientcompanies',$this->data['Company']['staff_user_id']), array('class' => 'btn btn-primary', 'escape' => false)); 
                            ?> 
                        </div>    
                        </div>    
                    </div>
                </div>
                <div class="dataTable_wrapper">
                    <?php
                    echo $this->Form->create('Company', array('type'=>'file','inputDefaults' => array('div' => 'form-group', 'class' => 'form-control'),"id"=>"register_from", 'novalidate'));
                    include "user_company.ctp";
                    echo "<div class='col-sm-12'>".$this->Form->submit('Submit', array('class' => 'btn btn-info',"div"=>"col-sm-1","id"=>"submit-btn")) ."</div>";
                    echo $this->Form->end();
                    ?>
                 </div>
            </div>
        </div>
    </div>
</div>
 
 

 <script type="text/javascript">
    // validate signup form on keyup and submit
    $(document).ready(function(){ 
        $("#register_from").validate({
            debug: false,
            rules: {
                "data[StaffUser][first_name]": "required",
                "data[StaffUser][last_name]": "required", 
                
                "data[StaffUser][email]": {
                    required: true,
                    email: true
                }, 
                "data[StaffUser][confirm_email]": {
                    required: true,
                    email: true,
                    equalTo: "#StaffUserEmail"
                },
                "data[StaffUser][phone_number]": {
                    required: true,
                    Number: true,
                    minlength: 10,
                    maxlength: 15
                }, 
                rules: {
                "data[StaffUser][new_profile_image]": {
                    required: false, 
                    accept: "jpg|jpeg|png", 
                },
              },
              rules: {
                "data[StaffUser][newlogo]": {
                    required: false, 
                    accept: "jpg|jpeg|png", 
                },
              },
              rules: {
                "data[StaffUser][newbanner_image]": {
                    required: false, 
                    accept: "jpg|jpeg|png", 
                },
              },
            },
            messages: {
                "data[StaffUser][first_name]": "Please enter your first name",
                "data[StaffUser][last_name]": "Please enter your last name", 
               
                "data[StaffUser][email]": "Please enter a valid email address", 
                "data[StaffUser][new_profile_image]": { 
                    filesize:"File size must be less than 2000 KB.",
                    accept:"Please upload .jpg or .png or .jpeg file.",
                },
                "data[StaffUser][newlogo]": {
                    required: "Please upload file.",
                    filesize:"File size must be less than 2000 KB.",
                    accept:"Please upload .jpg or .png or .jpeg file.",
                },
                "data[StaffUser][newbanner_image]": {
                    required: "Please upload file.",
                    filesize:"File size must be less than 2000 KB.",
                    accept:"Please upload .jpg or .png or .jpeg file.",
                },
                "data[StaffUser][phone_number]": {
                    required: "Please Enter phone Number.",
                    Number: "Please Enter valid phone Number.",
                    minlength:"Phone Number Enter valid 10 digit phone Number.",
                    maxlength:"Phone Number Enter valid 10 digit phone Number.",
                },
            }
        });
    });



window.URL = window.URL || window.webkitURL;
function imagevalidation(imageSelector,getImgwidth='',getImgheight='',condtion='equal',maxthenImgwidth='',
maxthenImghight='') {
      //e.preventDefault(); 
    var getImgwidth=(getImgwidth!='')?getImgwidth:80;
    var getImgheight=(getImgheight!='')?getImgheight:80;
    var form=$("#register_from");
    var image_err=$("#"+imageSelector+"-error");
    var selector="#"+imageSelector;
    var flag=0;
        var fileInput = $("#register_from").find(selector)[0],
        file = fileInput.files && fileInput.files[0];
        if(file){
            if(file.size<=1048576){
                var img = new Image();
                img.src = window.URL.createObjectURL(file);
                img.onload = function() {
                    var width = img.naturalWidth,height = img.naturalHeight;
                    window.URL.revokeObjectURL( img.src );
                    if(condtion=="equal"&& width == getImgwidth && height == getImgheight) {
                        flag=1;
                    }else if(condtion=="less"&& width <= getImgwidth && height <= getImgheight) {
                        flag=1;
                    }else if(condtion=="greater"&& width >= getImgwidth && height >= getImgheight) {
                        flag=1;
                    }else if(condtion=="both_less_greater"&& (width >= getImgwidth && height >= getImgheight)&&(width <= maxthenImgwidth && height <= maxthenImghight)) {
                        flag=1;
                    } 
                    if(flag==0){
                        $("#submit-btn").attr('disabled','disabled');
                        $con_text=(condtion!="equal")?condtion+' Or equal':condtion;
                        if(condtion!="both_less_greater"){
                            image_err.replaceWith("<label id='"+imageSelector+"-error' class='error' for='StaffUserProfileImage'>The Image Size should be "+$con_text+" "+getImgwidth+" X "+getImgheight+" (width X height).</label>");
                        }else{
                            image_err.replaceWith("<label id='"+imageSelector+"-error' class='error' for='StaffUserProfileImage'>The Image width should be "+getImgwidth+"px to "+maxthenImgwidth+" and height should be "+getImgheight+" to "+maxthenImghight+"px.</label>");  
                        }
                        return false;
                    }else{
                        image_err.hide();
                        $("#submit-btn").removeAttr('disabled');
                        return true;
                    }
                }; 
            }else{
                image_err.replaceWith("<label id='"+imageSelector+"-error' class='error' for='StaffUserProfileImage'>The Image Size should be less than 1Mb.</label>");
            }
        }

    }
</script>