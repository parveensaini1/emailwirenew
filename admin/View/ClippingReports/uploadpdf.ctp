<div id="main-content" class="row">
    <div id="content" class="col-lg-9 content">
        <div class="card card-default"> 
            <div class="card-body">
                <div class="dataTable_wrapper">
                 <?php echo $this->Html->css(array('/plugins/drop-drag/dropzone.css'));
                    echo $this->Html->script(array('/plugins/drop-drag/dropzone.js')); ?>
                    <div class="row border rounded">
                        <div class="col-sm-10"> 
                            <p>Upload pdf files</p>
                            <?php $reports= $this->Custom->getAdditionalClippingReport($prId);  ?>
                        </div>
                        <div class="col-sm-10">
                        <?php echo $this->Form->input("uploadpdf", array("label"=>false,"type" => 'file','class'=>'dropzone form-control',"pr_id"=>$prId,'id'=>"pass-input",'accept'=>'*',"multiple"=>true));?>
                        </div>
                        <div class="col-sm-10">
                             <ul style="<?php if(empty($reports)){ echo "display: none;"; }?>" id="additiona-report-1" class="documents mt-2">
                                <?php foreach ($reports as $docId => $file_name) { ?>
                                <li id='remove-document-<?php echo $docId;?>' class='ml-2'><span class='remove-cart'><a href='javascript:void(0)'onclick='removedocument("<?php echo $docId;?>")'> X </a></span><span class='float-left ml-2'><?php echo $file_name;?></span></li>
                            <?php } ?>
                            </ul>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div> 
</div>  

<script type="text/javascript">
Dropzone.autoDiscover = false;
var MaxFileSize="<?php echo ini_get('upload_max_filesize');?>";
$('.dropzone').each(function(){
    // $('.dropzone').click(function(){
    var id = $(this).attr('id');
    var dropUrl = SITEURL+'ajax/upload_pdf';
    var dropMaxFileSize = parseInt(MaxFileSize);
    var dropParamName =$(this).attr('name'); 
    var press_release_id =$(this).attr("pr_id");
    $(this).dropzone({
        url: dropUrl,
        maxFiles: 5,
        uploadMultiple: true,
        paramName: dropParamName,
        maxFilesSize: dropMaxFileSize,
        init: function() {
            this.on("sending", function(file, xhr, formData){ 
                formData.append("press_release_id",press_release_id);
            }); 
        },
        success: function(file,response){
            var obj = JSON.parse(response);
            if(obj.status!="failed"){ 
                messgae_box(obj.message,"Success","success");  
                document_list(obj.documents,1,"additiona-report");
            }else{
                messgae_box(obj.message,"Failed","failed");
            }
        }
        
    });

});

function document_list(documents,schoolId,responseId="document"){
    var html=""; 
    $.each(documents, function(index,data) {  
        html +="<li id='remove-document-"+data.id+"' class='ml-2'><span class='remove-cart'><a href='javascript:void(0)'onclick='removedocument("+data.id+")'> X </a></span><span class='float-left'>"+data.file_name+"</span></li>";
    }); 
    $("#"+responseId+"-"+schoolId).append(html).show();
}

function removedocument(docid=""){
    if(docid!=""){
        $(".ajaloading").show();
        $.ajax({
            url: "<?php echo SITEURL?>ajax/removedocument/",
            type: "POST",
            data:{id:docid},
            success: function(response){
              $(".ajaloading").hide();
              var obj = JSON.parse(response);
              if(obj.status!="failed"){ 
                messgae_box(obj.message,"Success","success");
                $("#remove-document-"+docid).remove();
              }else{
                messgae_box(obj.message,"Failed","failed");
              }
            }
        });
    }

}
</script>