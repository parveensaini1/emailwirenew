<div class="row">
    <div class="col-lg-12">
        <div class="card card-default"> 
            <!-- /.card-heading -->
            <div class="card-body">
               <?php echo $this->element('submenu'); ?>
                <div class="dataTable_wrapper">
                    <?php
                        echo $this->Form->create($model, array('type' => 'file', 'inputDefaults' => array('div' => 'form-group', 'class' => 'form-control', 'required' => false)));
                        echo $this->Form->input('id');
                        echo $this->Form->input('title');
                        echo $this->Form->input('sharer_url',array('type'=>'textarea'));
                        echo '<label for="SocialShareIcon">Upload Icon</label>';
                        echo $this->Form->file('icon_url',array('label'=>'Upload Icon'));
                        echo '<br>';
                        echo '<img src="'.SITEFRONTURL.$this->data['SocialShare']['icon_url'].'">';  
                        echo $this->Form->input('status', array('type'=>"checkbox",'div' => 'form-group',"class"=>"status-checkbox")); 
                        echo '<br>';
                        echo $this->Form->submit('submit',array('class'=>'btn btn-success'));
                        echo $this->Form->end();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div> 