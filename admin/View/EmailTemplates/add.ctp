<div class="row">
    <div class="col-lg-12">
        <div class="card card-default"> 
            <!-- /.card-heading -->
            <div class="card-body">
                <?php include 'menu.ctp'; ?>
                <div class="dataTable_wrapper">
                    <?php
                    echo $this->Form->create($model, array('inputDefaults' => array('div' => 'form-group', 'class' => 'form-control', 'required' => false)));
                    echo $this->Form->input('title');
                    echo $this->Form->input('subject');
                    echo $this->Form->input('from');
                    echo $this->Form->input('reply_to_email');
                    echo $this->Form->input('description', array('id' => "editor1", 'class' => 'ckeditor', 'label' => false, 'rows' => 20));
                    echo $this->Form->submit('Submit', array('class' => 'btn btn-info', 'div' => false));
                    ?>
                    <?php
                    echo $this->Html->link('Cancel', array(
                        'controller' => $controller,
                        'action' => 'index'
                            ), array('class' => 'btn btn-danger')
                    );
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