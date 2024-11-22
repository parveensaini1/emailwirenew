<div class="row">
    <div class="col-lg-12">
        <div class="card card-default"> 
            <div class="card-body">
               <?php echo $this->element('submenu'); ?>
                <div class="dataTable_wrapper">
                    <?php
                        echo $this->Form->create($model, array('type' => 'file', 'inputDefaults' => array('div' => 'form-group', 'class' => 'form-control', 'required' => false)));
                        echo $this->Form->input('title');
                        echo '<label for="SocialShareSharerUrl">Share Url</label><br>
                        <i><b>Example:</b> https://media-website.com/?url=<b>$slug</b>&media=<b>$image</b>&description=<b>$body</b></i>';
                        echo $this->Form->input('sharer_url',array('type'=>'textarea', 'label'=>false));
                        echo '<label for="SocialShareIcon">Upload Icon</label>';
                        echo $this->Form->file('icon_url',array('label'=>'Upload Icon')); 
                        
                        echo $this->Form->input('status', array('type'=>"checkbox",'div' => 'form-group',"class"=>"status-checkbox")); 
                        echo $this->Form->submit('submit',array('class'=>'btn btn-success'));

                        echo $this->Form->end();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div> 