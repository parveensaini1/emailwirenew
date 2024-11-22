<div class="row">
    <div class="col-lg-12">
        <div class="card card-default"> 
            <!-- /.card-heading -->
            <div class="card-body"> 
                <div class="dataTable_wrapper">
                    <?php echo $this->Form->create("Campaign", array('novalidate', 'inputDefaults' => array('div' => 'form-group', 'class' => 'form-control'),"id"=>"sendinmailForm")); ?>
                    <div class="row">
                        <div class="col-sm-9">
                            <?php
                        echo $this->Form->input('list_id', array("type"=>"select","options"=>$lists,"empty"=>"Select Email list", "class" => "form-control","onchange"=>"checkuserlistsent(this.value,$user_id,$prId)","required"=>"required"));
                    
                           ?>
                        <div class="row">
                            <div class="col-md-6">
                                <?php echo $this->Form->submit('Submit', array('class' => 'btn btn-info', 'div' => false)); ?>
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
 <script type="text/javascript">
     $("#sendinmailForm").validate();


function checkuserlistsent(lid,uid,pId){ 
    if(lid !=""){
        $("#AjaxLoading").show();
        $.ajax({
            type: 'POST',
            url: SITEFRONTURL+'ajax/getsentmail',
            data: { pid: pId,uid:uid,lid:lid },
            async: false,
            success: function (response) {
                $("#AjaxLoading").hide();
                if(response>0){
                    confirmSendPRInMailAction("CampaignListId","Are you sure? You want to send this PR again to this mail list.");
                }
            } 
         });

    }

}
 </script>