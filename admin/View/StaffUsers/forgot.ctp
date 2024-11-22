<div><?php echo $this->Html->link('<i class="glyphicon glyphicon-circle-arrow-left" style="font-size: 25px;"></i>',array('controller'=>'staffUsers','action'=>'login'),array('escape'=>false)); ?></div>
<p class="login-box-msg">Enter email id to reset your account</p>
<?php echo $this->Form->create('StaffUser', array('inputDefaults' => array('div' => false, 'class' => 'form-control', 'label' => false, 'required' => false))); ?>            
<div class="form-group has-feedback">
    <?php echo $this->Form->input('email', array('placeholder' => "Email")); ?>
    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
</div> 
<div class="row">
    <div class="col-xs-4">
        <?php echo $this->Form->submit('Send',array('class'=>'btn btn-primary btn-block btn-flat')); ?>        
    </div><!-- /.col -->
</div>
<?php echo $this->Form->end(); ?>