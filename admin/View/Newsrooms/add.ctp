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
                    <?php echo $this->Form->create($model, array('novalidate', 'inputDefaults' => array('div' => 'form-group', 'class' => 'form-control'))); ?>
                    <div class="row">
                        <div class="col-sm-9">
                        <?php
                        echo $this->Form->input('title', array("class" => "form-control", "empty" => ""));
                        echo $this->Form->input('slug', array("class" => "form-control", "empty" => "",'placehoder'=>'Please enter slug in english'));
                        echo $this->Form->input('description', array("class" => "country_dd form-control","id"=>'editor1' ,'label' => 'Description'));
                        echo $this->Form->input('meta_title', array("class" => "country_dd form-control", 'label' => 'Meta Title'));   
                        echo $this->Form->input('meta_keyword', array("class" => "form-control", "empty" => ""));
                        echo $this->Form->input('meta_description', array("class" => "form-control", "empty" => "")); 
                        ?>
                        <div class="row">

                        <div class="col-md-6">
                            <label>&nbsp;</label>
                            <?php   
                            echo $this->Form->input('status', array('div' => 'form-group',"class"=>"status-checkbox"));
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
                        <div class="col-sm-3">
                        <div class="box box-info">
                            <div class="box-header with-border">
                              <h3 class="box-title">Select Template</h3>
                              <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                </button>
                              </div>
                              <!-- /.box-tools -->
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body" style="">
                              <?php     echo $this->Form->input('page_template_id',array('options'=>$page_template_list,'label'=>false,'id'=>'page_template_id','class'=>'page_template_id')); ?>
                            </div>
                            <!-- /.box-body -->
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
    var editor = CKEDITOR.replace('editor1'); 
</script> 