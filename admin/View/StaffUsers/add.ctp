<div class="row">
    <div class="col-lg-12">
        <div class="card card-default"> 
            <!-- /.card-heading -->
            <div class="card-body">              
                <div class="dataTable_wrapper">
                    <?php
                    echo $this->Form->create($model, array('inputDefaults' => array('div' => 'form-group', 'class' => 'form-control', 'required' => false)));
                    ?>
                    <div class="row">
                        <div class="col-sm-3">
                            <?php
                            echo $this->Form->input('staff_role_id', array('options' => $role,'empty' =>false)); //'empty' => 'Select Role'
                            ?>
                        </div>
                        <div class="col-sm-3">
                            <?php
                            echo $this->Form->input('first_name',['required'=>"required"]);
                            ?>
                        </div>
                        <div class="col-sm-3">
                            <?php
                            echo $this->Form->input('last_name',['required'=>"required"]);
                            ?>
                        </div>
                        <div class="col-sm-3">
                            <?php
                             echo $this->Form->input('email',array("type" => 'text', 'onblur'=>"check_email(this.value);",'required'=>"required"));
                            ?>
                        </div>
                    </div>
                    <div class="row"> 
                        
                        <div class="col-sm-3">
                            <?php
                           echo $this->Form->input('phone', array("type" => 'text', 'class' => 'form-control','maxlength'=>"15",'minlength'=>"10",'onkeypress'=>"return isNumber(event)",'required'=>"required",'required'=>"required"));
                            ?>
                        </div>
                        <div class="col-sm-3">
                            <?php
                            echo $this->Form->input('password', array('type' => 'password','minlength'=>"8",'maxlength'=>"20",'required'=>"required"));
                            ?>
                        </div>
                        <div class="col-sm-3">
                            <?php
                            echo $this->Form->input('verify_password', array('type' => 'password','minlength'=>"8",'maxlength'=>"20",'required'=>"required"));
                            ?>
                        </div>
                    </div>
                    <?php                                                             
                   // echo $this->Form->input($model.'.status',array('type'=>'checkbox','class'=>'form-control status-checkbox',"label"=>"Active"));
                    echo $this->Form->hidden($model.'.status',array('value'=>"1"));
                    echo $this->Form->submit('Submit', array('class' => 'btn btn-info'));
                   
                    echo $this->Form->end();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$("#StaffUserAddForm").validate({debug: false});

function check_email(email){
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
</script>