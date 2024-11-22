<div class="row">
   <div class="col-lg-12">
      <div class="col-lg-12"><div class="ew-title full"><?php echo $title_for_layout;?></div></div>
      <div class="panel panel-default"> 
         <!-- /.panel-heading -->
         <div class="panel-body mb-4" style="clear: both;"> 
            <div class="row">
                 <div class="col-sm-8">
                   <div class="full ew-contact-form">
                        <?php  
                        echo $this->Form->create('Query', array("default"=>false,'id' => 'queryform','novalidate' =>'novalidate','inputDefaults' => array('div' => 'form-group', 'class' => 'form-control'))); ?>
                            <div class="row"> 
                                <div class="col-sm-4 margin-bottom15">
                                    <?php 
                                    echo $this->Form->input('contact_name', array('class' => 'form-control ', 'id' => 'contact_name', 'type' => 'text',"required"=>"required","maxLength"=>"50",'label'=>false,'placeholder'=>"Contact name","value"=>$first_name.' '.$last_name));

                                    ?> 
                                </div>
                                <div class="col-sm-4 margin-bottom15">
                                    <?php echo $this->Form->input('email', array('type'=>"email",'class' => 'form-control ',"required"=>"required","maxLength"=>"150",'label'=>false,'placeholder'=>"Contact email address","value"=>$email));?>
                                </div> 
                                <div class="col-sm-4 margin-bottom15">
                                    <?php 
                                    echo $this->Form->input('phone', array('class' => 'form-control', 'id' => 'phone', 'type' => 'text',"required"=>"required",'minLength'=>"10",'maxLength'=>"15",'onkeypress'=>"return isNumber(event)",'placeholder'=>"Phone Number",'label'=>false,"value"=>$phone));
                                    ?>
                                </div>
                                <div class="col-lg-12 ew-contact-label margin-bottom15"><label>HELP US UNDERSTAND YOUR NEEDS A LITTLE MORE.</label></div> 
                                <div class="col-sm-6 margin-bottom15">
                                    <?php 
                                    $organization_list=$this->Custom->getOrganizationList();
                                    echo $this->Form->input('organization_type_id', array('label' =>false,'options' =>$organization_list,'empty' => '-What type of organization are you?-','class'=>"custom-select bootselect form-control","required"=>"required")); 
                                ?> 
                                </div>
                                <div class="col-sm-6 margin-bottom15">
                                   <?php 
                                   echo $this->Form->input('subject', array('type'=>"text",'class' => 'form-control ',"required"=>"required","maxLength"=>"150",'label'=>false,'placeholder'=>"Subject"));
                                   ?>
                                </div> 
                                <div class="col-lg-12 margin-bottom20">
                                    <?php 

                                     echo $this->Form->input('message', array('class' => 'form-control ', 'type' => 'textarea','label'=>false,'maxlength'=>"500",'autocomplete'=>"off",'label'=>false,"placeholder"=>"Message / Comment *","required"=>"required"));
                                     ?> 
                                </div> 
                                <div class="col-lg-12">
                                    <input type="submit" name="submitbtn" id="submitbtn" value="SUBMIT NOW">
                                </div>    
                            </div> 
                        <?php  echo $this->Form->end();?>
                        <div id="querymsg" class="error" style="display: none;"></div>
                    </div>  
                 </div>
                <div id="sidebar" class="col-sm-4 ew-sidebar ">
                    <div class="sidebar__inner orange-border">
                        <!-- End Already have an account --> 
                        <div class="ew-side-gray-box full margin-bottom15">
                            <h2>Have Question?</h2>
                            <p>Feel free to contact us if you have any question or concerns.</p>
                            <div class="ew-phone ew-gray-b-text full">Call <?php echo Configure::read('Site.phone'); ?></div>
                            <div class="ew-ticket ew-gray-b-text full"><a href="<?php echo SITEURL.'users/contact-us'; ?>">Open Ticket</a></div>
                        </div> 
                        <div class="ew-side-gray-box full margin-bottom15">
                            <h2>Address & Location</h2>
                            <?php echo Configure::read('Site.address'); ?>
                        </div>
                        <div class="full margin-bottom15">
                             <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d221152.3731887279!2d-95.54484898232744!3d29.99362880592412!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8640ca64d6c0a605%3A0xcf9db6e2c0030ddd!2sGroupWeb+Media+LLC+(EMAILWIRE.COM)+-+Press+Release+Distribution+Services!5e0!3m2!1sen!2sin!4v1550048677693" width="100%" height="300" frameborder="0" style="border:0" allowfullscreen></iframe>   
                        </div>
                    </div>
                </div>
            </div>

            
         </div>
      </div>
   </div>
</div>

<script type="text/javascript">
$('#queryform').validate({
    submitHandler: function(form) {
       $("#querymsg").hide(); 
        $.ajax({
           type: "POST",
           url: SITEURL+'ajax/sendquery',
           data: $("#queryform").serialize(),
           success: function(data){
            $("#AjaxLoading").hide();
            var obj = JSON.parse(data);
            var textclass="alert-success";
            var iconclass='icon fa fa-check';
            if(obj.status=='failed'){
                textclass="alert-error";
                iconclass='icon fa fa-ban';
            }
            $("#querymsg").html('<div class="alert '+textclass+' alert-dismissable" style="margin:0px;"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><i class="'+iconclass+'"></i> '+obj.message+'</div>').show();
            $('#queryform')[0].reset();
           }
         });
    }
});

/*
    $("#queryform").validate();
    $("#queryform").submit(function(e) {
        $("#querymsg").hide();
        e.preventDefault();
        var form = $(this);
        $.ajax({
           type: "POST",
           url: SITEURL+'ajax/sendquery',
           data: form.serialize(),
           success: function(data){
            $("#AjaxLoading").hide();
            var obj = JSON.parse(data);
            var textclass="alert-success";
            var iconclass='icon fa fa-check';
            if(obj.status=='failed'){
                textclass="alert-error";
                iconclass='icon fa fa-ban';
            }
            $("#querymsg").html('<div class="alert '+textclass+' alert-dismissable" style="margin:0px;"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><i class="'+iconclass+'"></i> '+obj.message+'</div>').show();
            $('#queryform')[0].reset();
           }
         });
    });
    */
</script>