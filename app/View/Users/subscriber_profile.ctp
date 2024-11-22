<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default"> 
            <div class="panel-body">
                <div class="dataTable_wrapper">
                    <?php
                    //  echo $this->Html->link('Dashboard', array(
                    //     'controller' =>'users',
                    //     'action' => 'dashboard'
                    //         ), array('class' => 'btn btn-primary')
                    // );
                    echo $this->Form->create('StaffUser', array('type'=>'file','inputDefaults' => array('div' => 'form-group', 'class' => 'form-control'), 'novalidate'));
                    echo $this->Form->input('id');
                   echo $this->Form->hidden('staff_role_id',array('value'=>$this->session->read('Auth.User.staff_role_id')));
                    ?>
                     <div class="row">
                        <div class="col-sm-6">
                             <?php 
                                $subscriber_options   =   array('1'=>'Journalist','2'=>'Bloggers','3'=>'Individuals');
                                echo $this->Form->input('subscriber_type', array("type" => 'select','empty'=>"--Select--",'options'=>$subscriber_options,'class'=>'form-control',"required"=>"required"));
                                 ?>  
                        </div>
                        
                    </div> 
                    <div class="row">
                        <div class="col-sm-6">
                            <?php
                            echo $this->Form->input('first_name', array("type" => 'text', 'class' => 'form-control'));
                            ?>
                        </div>
                        <div class="col-sm-6">
                            <?php
                            echo $this->Form->input('last_name', array("type" => 'text', 'class' => 'form-control'));
                            ?>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-sm-6">
                            <?php
                                
                            echo $this->Form->input('email', array("type" => 'text', 'class' => 'form-control',"required"=>"required",'onblur'=>"check_email(this.value,'".$this->request->data['StaffUser']['email']."');"));
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="row">
                            <div class="col-sm-8">
                                <?php
                            echo $this->Form->hidden('profile_image',array('value'=>$this->Session->read('Auth.User.profile_image')));
                            
                            echo $this->Form->input('new_profile_image', array('type' => 'file', 'class' =>'new_profileimage','id'=>'new_profile_image','accept'=>'image/*','onchange'=>"imagevalidation('new_profile_image',5,5,'both_less_greater',1500,1500)")); ?>
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
                    <!-- <div class="row">
                        <div class="col-sm-6">
                            <label>Subscribe to our newsletter?</label><br />
                            <?php
                            // $options = array('1' => 'Yes', '0' => 'No');
                            // $attributes = array('legend' => false, 'label' => 'Subscribe to our newsletter?');
                            // echo $this->Form->radio('newsletter_subscription', $options, $attributes);
                            ?>
                        </div> 
                        <div class="col-sm-6">
                            <label>Get notified by email?</label><br />
                            <?php
                            // $options = array('1' => 'Yes', '0' => 'No');
                            // $attributes = array('legend' => false, 'label' => 'Get notified by email?');
                            // echo $this->Form->radio('notified_by_email', $options, $attributes);
                            ?>
                        </div> 
                    </div> -->
                    
                    <button type="submit" id="submit-btn" class="btn btn-info"> Update Now </button>        
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
function check_email(email,old_email){

    if(email != old_email){

        $.ajax({
            type: 'POST',
            url: SITEURL+'users/check_email',
            data: {
                email: email,
            },
            // async: false,
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