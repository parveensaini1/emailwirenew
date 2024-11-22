

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">    
            <div class="modal-header">
                <h5 class="modal-title" name="send_title" id="send_title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
            </div>
            <div class="modal-body">
                <form id="sendmail" action="#" method="POST">
                    <div class="form-input">
                            <label>Client Mail</label> <br>
                            <input class="form-control" type="text" disabled value="" name="registered_email" id="registered_email">
                        </div>
                        <div class="email_add_msg">Enter the email adresses using comma "," separator. For example demo1@gmail.com,demo2@gmail.com </div>
                        <div class="form-input hide_form"><input type="hidden" value="" name="pr_id" id="pr_id"></div>
                        <div class="form-input add_email_new ">
                            <label>Add Email ID</label><br>
                            <input class="form-control" type="text" value="" name="other_email"  id="other_email" autocomplete="off" placeholder="Enter the email address."></div>
                        </form>   
                </div>
                <div class="modal-footer"> 
                    <span class="mail_processing"></span>
                    <input class="btn btn-md btn-info" type="submit" value="Send Clipping Report" name="sendmailbtn" id="sendmailbtn">
                </div>
        </div>
    </div>
</div>
<script>
    $(".sendcls").click(function(event){
        event.preventDefault();
        var pr_id=$(this).attr('id');
        var clientemail=$(this).attr('useremail'); 
        $('#registered_email').val(clientemail);
        $('#send_title').text($(this).attr('sendtitle'));
        $('#pr_id').val(pr_id);
        $('#myModal').modal('show');
    });
    $("#sendmailbtn").click(function(){
        $("#sendmailbtn").attr('disabled','disabled');
        $(".mail_processing").text(''); 
        var other_email=$('#other_email').val();
        var registered_email=$('#registered_email').val();
        var pr_id=$('#pr_id').val();
       if(other_email.length<1)
       { 
        other_email=registered_email;
       }
       
       $.ajax({
        type: 'POST',
        url: '<?php echo SITEURL; ?>ajax/sendmail',
        data:{other_email:other_email,pr_id:pr_id,registered_email:registered_email},
        success: function (response) {
            var obj=JSON.parse(response); 
            if(obj.status !="failed"){
        	   $(".mail_processing").text('Mail sent.');
        	   setTimeout(function () { location.reload(true);},5000);
        	 }else{
        	 	$(".mail_processing").text('Sorry ,Mail could not be sent.');
        	 }
             message_box(obj.status,obj.message,obj.status);
             $("#sendmailbtn").removeAttr('disabled'); 
         }}
        );                          
        return false;
       
       });  

    $(document).ajaxStart(function(){
        $(".mail_processing").text('Please wait while processing');
    }); 
    </script>