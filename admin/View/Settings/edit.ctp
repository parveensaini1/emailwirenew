<?php echo $this->element('submenu'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default"> 
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="dataTable_wrapper">
                    <?php
                    echo $this->Form->create('Setting', array('inputDefaults' => array('div' => 'form-group', 'class' => 'form-control', 'required' => false)));
                    echo $this->Form->input('id');
                    echo $this->Form->input('key');
                    $isHtml=($this->data["Setting"]["input_type"]=="html")?"1":"";
                    $vl=$this->data["Setting"]["value"];
                    if($isHtml){
                        $vl=$this->Custom->filterPageDescription($this->data["Setting"]["value"]); 
                    }
                    echo $this->Form->input('value', array('id' => 'textarea_id', 'row' => 20, 'class' => 'ckeditor',"value"=>$vl));
                    echo $this->Form->input('is_html',array('type'=>'checkbox','class'=>'status_checkbox','div'=>'div-status',"default"=>$isHtml));
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