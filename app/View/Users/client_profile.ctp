<?php echo $this->Html->script(array('jquery.validate.min','additional-methods.min'));?> 
<div class="row">
    <div class="col-lg-12"><div class="ew-title full"><?php echo $title_for_layout;?></div></div>
    <div class="col-lg-12">
        <div class="panel panel-default"> 
            <!-- /.panel-heading -->
            <div class="panel-body">

                <div class="dataTable_wrapper">

                    <?php
                    //  echo $this->Html->link('Dashboard', array(
                    //     'controller' =>'users',
                    //     'action' => 'dashboard'
                    //         ), array('class' => 'btn btn-primary')
                    // );
                    echo $this->Form->create('StaffUser', array('type'=>'file','inputDefaults' => array('div' => 'form-group', 'class' => 'form-control'),"id"=>"register_from", 'novalidate'));
                    echo $this->Form->input('id');
                    echo $this->Form->hidden('staff_role_id',array('value'=>$this->session->read('Auth.User.staff_role_id')));
                    ?>
                    <div class="row">
                        <div class="col-sm-6">
                            <?php
                            echo $this->Form->input('first_name', array("type" => 'text', 'class' => 'form-control',"required"=>"required"));
                            ?>
                        </div>
                        <div class="col-sm-6">
                            <?php
                            echo $this->Form->input('last_name', array("type" => 'text', 'class' => 'form-control',"required"=>"required"));
                            ?>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-sm-6">
                            <?php
                                
                            echo $this->Form->input('email', array("type" => 'email', 'class' => 'form-control',"required"=>"required"));
                            //'onblur'=>"check_email(this.value,'".$this->request->data['StaffUser']['email']."');"
                            ?>
                        </div>
                        <div class="col-sm-6">
                            <?php
                                 echo $this->Form->input('phone', array("type" => 'text','maxlength'=>"15",'minlength'=>"10",'class'=>'form-control',"required"=>"required","onkeypress"=>"return isNumber(event)",'autocomplete' => 'off'));
                            
                            ?>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="row">
                            <div class="col-sm-8">
                                <?php
                            echo $this->Form->hidden('profile_image',array('value'=>$this->Session->read('Auth.User.profile_image')));
                            
                            echo $this->Form->input('new_profile_image', array('type' => 'file', 'class' =>'new_profileimage','id'=>'new_profile_image','accept'=>'image/*','onchange'=>"imagevalidation('new_profile_image',10,10,'both_less_greater',1500,1500)")); ?>
                            <label style="display: none;" id="new_profile_image-error"></label>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="StaffUserEmail">&nbsp;</label>
                                    <?php if ($this->Session->read('Auth.User.profile_image') != '') {
                                        echo $this->Html->image(SITEADMIN . '/files/profile_image/' . $this->Session->read('Auth.User.profile_image'), array('class' => 'img','width' => '200px;'));
                                    } else {
                                        echo $this->Html->image(SITEURL.'img/no_image.jpeg', array('class' => 'img','width' => '200px;'));
                                    } ?>
                                </div>
                            </div>
                            </div>
                        </div> 

                        
                    </div> 
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-12">
                            <button id="submit-btn" type="submit" class="btn btn-info">Update Now </button>
                            </div>
                        </div>
                    </div>
                    <?php echo $this->Form->end(); ?>    
                </div>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
input#new_profile_image {
    opacity: 1;
}   
</style>

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
                "data[StaffUser][phone]": {
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

function check_email(email,old_email){

    if(email != old_email){

        $.ajax({
            type: 'POST',
            url: SITEURL+'users/check_email',
            data: {
                email: email,
            },
             async: false,
            success: function (response) {
                
                $("#AjaxLoading").hide();
                var obj=JSON.parse(response);
                if(obj.status=="false"){
                    $("#StaffUserConfirmEmail-error").remove();
                    $("#submit-btn").attr('disabled','disabled');
                    $("#StaffUserEmail").after("<label id='StaffUserConfirmEmail-error' class='error' for='StaffUserConfirmEmail'>"+obj.message+"</label>");
                }else{
                    $("#StaffUserConfirmEmail-error").remove();
                    $("#submit-btn").removeAttr('disabled');
                }
            } 
         });

    }

}
</script>