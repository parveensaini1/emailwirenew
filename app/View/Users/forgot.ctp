<p class="login-box-msg">Sign in to start your session</p>
<?php echo $this->Form->create('StaffUser', array('inputDefaults' => array('div' => false, 'class' => 'form-control', 'label' => false, 'required' => false))); ?>            
<div class="form-group has-feedback">
    <?php echo $this->Form->input('email', array('placeholder' => "Email")); ?>
    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
</div> 
<div class="row"> 
    <div class="col-xs-4">
        <button type="submit" class="btn btn-primary btn-block btn-flat">Submit</button>
    </div><!-- /.col -->
</div>
<?php echo $this->Form->end(); ?> 