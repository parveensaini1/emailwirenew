<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">

            <div class="panel-body">
                <?php
                echo $this->Form->create('User', array('novalidate', 'inputDefaults' => array('class' => 'form-control', 'div' => 'form-group')));
                ?>

                <div class="row">
                    <div class="col-md-6">
                        <?php
                        echo $this->Form->input('first_name');
                        ?>
                    </div>                    
                    <div class="col-md-6">
                        <?php echo $this->Form->input('last_name'); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?php echo $this->Form->input('email'); ?>
                    </div>              
                    <div class="col-md-6">
                        <?php echo $this->Form->input('mobile'); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?php
                        $options = array('M' => 'Male', 'F' => 'Female');
                        $attributes = array('legend' => false,'value'=>'M');
                        echo $this->Form->radio('gender', $options, $attributes);
                        ?>
                    </div>  
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?php
                        echo $this->Form->input('address', array('type' => 'textarea', 'rows' => 3));
                        ?>
                    </div>  
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?php echo $this->Form->input('city'); ?>
                    </div>   
                    <div class="col-md-6">
                        <?php echo $this->Form->input('state'); ?>
                    </div>
                </div> 
                <div class="row">
                    <div class="col-md-6">
                        <?php echo $this->Form->input('country_id', array('options' => $country_list)); ?>
                    </div>      
                    <div class="col-md-6">
                        <?php echo $this->Form->input('post_code'); ?>
                    </div> 
                </div> 
                <button type="submit" class="btn btn-info">Update</button>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>