<?php
echo $this->Html->css(array('/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min'));
echo $this->Html->script(array('/plugins/ckeditor/ckeditor'));
?>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="card card-default"> 
            <!-- /.card-heading -->
            <div class="card-body">
                <?php echo $this->element('submenu'); ?>
                <div class="dataTable_wrapper">
                    <?php echo $this->Form->create($model, array('novalidate', 'inputDefaults' => array('div' => 'form-group', 'class' => 'form-control')));
                        echo $this->Form->input('id'); 
                        echo $this->Form->input('old_slug',array('type'=>'hidden','value'=>$slug));
                     ?>
                    <div class="row">
                        <div class="col-sm-9">
                        <?php
                        echo $this->Form->input('title', array("class" => "form-control", "empty" => ""));
                        echo $this->Form->input('slug', array("class" => "form-control", "readonly" => "readonly"));
                        echo $this->Form->input('description', array("class" => "country_dd form-control","id"=>'editor1' ,'label' => 'Description'));
                        ?>
                        <div class="row">

                        <div class="col-md-6">
                            <label>&nbsp;</label>
                            <?php
                            echo $this->Form->submit('Submit', array('class' => 'btn btn-info', 'div' => false));
                            ?>
                            <?php
                            echo $this->Html->link('Cancel', array(
                                'controller' => $controller,
                                'action' => 'index'
                                    ), array('class' => 'btn btn-danger')
                            );
                            ?>
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
<script>
var editor1 = CKEDITOR.replace('editor1',{showWordCount: true, filebrowserUploadUrl: SITEFRONTURL+"ajax/mediafileupload?typ=1"});
</script> 