<div class="row">
    <div class="col-lg-12">
        <div class="card card-default"> 
            <!-- /.card-heading -->
            <div class="card-body">

                <div class="dataTable_wrapper">
                    <?php

                    echo $this->Form->create($model, array('inputDefaults' => array('div' => 'form-group', 'class' => 'form-control'), 'novalidate', 'type' => 'file'));
                    echo $this->Form->input('first_name',['required'=>"required"]);
                    echo $this->Form->input('last_name',['required'=>"required"]);
                    echo $this->Form->input('email',array("type" => 'text', 'onblur'=>"check_email(this.value,'".$this->request->data['StaffUser']['email']."');",'required'=>"required"));
                    echo $this->Form->hidden('profile_image');
                    echo $this->Form->input('new_profile_image', array('type' => 'file', 'class' => ''));
                    ?>
                    <div class="form-group">
                        <label for="StaffUserEmail">&nbsp;</label>
                        <?php echo $this->Html->image('/files/profile_image/' . $this->Session->read('Auth.User.profile_image'),array('width'=>'200px;')); ?>
                    </div>
                    <?php
                    echo $this->Form->input('phone', array("type" => 'text', 'class' => 'form-control','maxlength'=>"15",'minlength'=>"15",'onkeypress'=>"return isNumber(event)",'required'=>"required"));
                    echo $this->Form->submit('Submit', array('class' => 'btn btn-info', 'div' => false));
                    ?>
                    <?php
                    echo $this->Form->end();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$("#StaffUserProfileForm").validate({debug: true});
function check_email(email,old_email){
    
    if(email != old_email){

        $.ajax({
            type: 'POST',
            url: SITEURL+'staffUsers/check_email',
            data: {
                email: email,
            },
            // async: false,
            success: function (response) {
                
                $("#AjaxLoading").hide();
                var obj=JSON.parse(response);
                if(obj.status=="false"){
                    $("#StaffUserConfirmEmail-error").remove();
                    $(".btn-info").attr('disabled','disabled');
                    $("#StaffUserEmail").after("<label id='StaffUserConfirmEmail-error' class='error' for='StaffUserConfirmEmail'>"+obj.message+"</label>");
                }else{
                    $("#StaffUserConfirmEmail-error").remove();
                    $(".btn-info").removeAttr('disabled');
                }
            } 
         });

    }

}
</script>