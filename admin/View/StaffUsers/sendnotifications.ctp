<div class="row">
    <div class="col-lg-12">
        <div class="card card-default"> 
            <div class="card-body">
                <div class="dataTable_wrapper">
                    <?php
                    echo $this->Form->create($model, array('inputDefaults' => array('div' =>'form-group','class' =>'form-control','required'=>false)));
                    $emailFindReplace = array(
                        '##ROLE##'=>$userRoleName,
                        '##SITE_NAME##'=>$site_name,
                        '##MESSAGE##'=>"Write message here...",
                    );
                    $title=(!empty($data['EmailTemplate']['title']))?$data['EmailTemplate']['title']:"";
                    $subject=(!empty($data['EmailTemplate']['subject']))?$data['EmailTemplate']['subject']:"";
                    $description=(!empty($data['EmailTemplate']['description']))?strtr($data['EmailTemplate']['description'],$emailFindReplace):"";
                    $from=(!empty($data['EmailTemplate']['from']))?$data['EmailTemplate']['from']:"";
                    $reply_to_email=(!empty($data['EmailTemplate']['reply_to_email']))?$data['EmailTemplate']['reply_to_email']:"";

                    echo $this->Form->input('title',['type'=>"text",'value'=>$title]);
                    echo $this->Form->input('subject',['type'=>"text",'value'=>$subject]);
                    echo $this->Form->input('from',['type'=>"email",'value'=>$from]);
                    echo $this->Form->input('reply_to_email',['type'=>"email",'value'=>$reply_to_email]);
                    echo $this->Form->input('description', array('id' => "editor1", 'class' => 'ckeditor', 'label' => false, 'rows' =>30,'value'=>$description));
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
    var editor = CKEDITOR.replace('editor1',{showWordCount: true, filebrowserUploadUrl: SITEFRONTURL+"ajax/mediafileupload?typ=1"});
</script>