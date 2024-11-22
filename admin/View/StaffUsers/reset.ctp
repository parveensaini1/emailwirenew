<span class="lock"><?php echo $this->Html->image('login-lock.png', array('alt' => 'Lock')); ?></span>
<?php echo $this->Form->create('User',array('inputDefaults'=>array('div'=>false))); ?>
<div class="padding">
     <?php echo $this->Form->input('password',array('type'=>'password','label'=>'Password:','required'=>false)); ?>
</div>
<div class="padding">
     <?php echo $this->Form->input('verify_password',array('type'=>'password','label'=>'Confirm password:','required'=>false)); ?>
</div>
  
<section class="clear"></section>
<?php
echo $this->Form->input('SUBMIT',array('label'=>false,'class'=>'login','type'=>'submit'));
?>
 
<?php echo $this->Form->end(); ?>