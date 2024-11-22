<div class="row">
    <div class="col-lg-12">
        <div class="card card-default"> 
            <!-- /.card-heading -->
            <div class="card-body">
               <?php echo $this->element('submenu'); ?>
                <div class="dataTable_wrapper">
                    <?php
                     echo $this->Form->create('Setting', array('inputDefaults' => array('div' => 'form-group', 'class' => 'form-control', 'required' => false)));
                      echo $this->Form->input('key');
                     echo $this->Form->input('value', array('id' => 'textarea_id', 'row' => 20, 'class' => 'ckeditor'));
                    echo $this->Form->submit('Submit', array('class' => 'btn btn-info', 'div' => false));
                    ?>
                    <?php
                    echo $this->Html->link('Cancel', array(
                                'controller' => 'settings',
                                'action' => 'index'
                            ),array('class'=>'btn btn-danger')
                    );
                    echo $this->Form->end();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
  
  <?php
echo $this->Html->css(array('/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min'));
echo $this->Html->script(array('/plugins/ckeditor/ckeditor'));
?>