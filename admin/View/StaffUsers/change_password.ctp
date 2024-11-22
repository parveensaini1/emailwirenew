<div class="row">
    <div class="col-lg-12">
        <div class="card card-default">
            <?php 
              /*  if($this->request->pass[1]=='users'){
                    include 'user-menu.ctp';    
                    }else{
                        include 'menu.ctp';    
                    }*/

                 ?>

            <!-- /.card-heading -->
            <div class="card-body">
                <div class="dataTable_wrapper">
                    <?php
                    echo $this->Form->create($model, array('inputDefaults' => array('div' => 'form-group', 'class' => 'form-control', 'required' => false)));
                     echo "<div class='row'>";
                    echo $this->Form->input('password',array('type'=>'password',"div"=>"col-sm-5"));
                    echo $this->Form->input('verify_password',array('type'=>'password',"div"=>"col-sm-5"));
                     
                    echo $this->Form->submit('Submit', array('class' => 'inline-submit btn btn-info', 'div' => "col-sm-2"));
                    echo "</div>";
                    echo $this->Form->end();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->Html->script(array('jquery.validate.min','additional-methods.min'));?>
<script type="text/javascript">
    $(document).ready(function(){ 
 
        $("#StaffUserChangePasswordForm").validate({
            debug: false,
            rules: {
                "data[StaffUser][password]": {
                    required: true,
                    minlength: 8,  
                }, 
                "data[StaffUser][verify_password]": {
                    required: true,
                    minlength: 8, 
                    equalTo: "#StaffUserPassword"
                }, 
            },
            messages: {  
                "data[StaffUser][password]": {
                    required: "Please enter password.",
                    minlength:"Password should be minimum 8 character.", 
                }, 
                "data[StaffUser][verify_password]": {
                    required: "Please enter verify password.",
                    minlength:"Password should be minimum 8 character.", 
                    equalTo:"Password not matched.", 
                },    
            }
        });
    }); 

</script>