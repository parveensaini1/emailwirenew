<?php
echo $this->Html->css(array('/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min'));
// echo $this->Html->script(array('/plugins/ckeditor/ckeditor'));
?>


<script src="<?php echo SITEURL; ?>plugins/tinymce/tinymce.min.js"></script>
<div class="row">
    <div class="col-lg-12">
        <div class="card card-default"> 
            <!-- /.card-heading -->
            <div class="card-body">
                <?php echo $this->element('submenu'); ?>
                <div class="dataTable_wrapper">
                    <?php
                    echo $this->Form->create('Setting', array('inputDefaults' => array('div' => 'form-group', 'class' => 'form-control', 'required' => false)));
                    echo $this->Form->input('id');
                    echo $this->Form->input('key');
                    switch ($this->data['Setting']['input_type']) {
                        case 'checkbox':
                            echo $this->Form->input('value', array('row' => 20, 'type' => $this->data['Setting']['input_type'], 'label' => false));
                            break;
                        case 'multiple':
                            $options = array('mail' => 'Mail', 'smtp' => 'Smtp');
                            echo $this->Form->input('value', array('row' => 20, 'options' => $options, 'label' => false));
                            break;
                        default:
                            echo $this->Form->input('value', array('id' => 'textarea_id', 'row' => 20, 'class' => 'ckeditor'));
                            break;
                    }
                    echo $this->Form->submit('Submit', array('class' => 'btn btn-info', 'div' => false));

                    echo $this->Html->link('Cancel', array(
                        'controller' => 'settings',
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
<!--<script>tinymce.init({
        theme: "modern",
        selector: 'textarea', force_br_newlines: false,
        force_p_newlines: false,
        forced_root_block: '', });</script>-->