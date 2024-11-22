<?php

$controller =(!empty($controller))?$controller:trim($this->params['controller']);
$action = trim($this->params['action']); 
$keyword=(isset($this->params->query['s']))?$this->params->query['s']:"";
$placeholder=(isset($placeholder))?$placeholder:"Search for ...";
?>
<div class="offset-sm-7 offset-sm-7 col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
	<?php echo $this->Form->create($controller, ['url' => SITEURL.$controller.'/'.$action,'type' => 'get']); ?>    
	<div class="input-group">
	  <?php echo $this->Form->input('s', array('value' => $keyword, 'autocorrect' => 'off','autocapitalize' =>'off','autocomplete' =>'off','label' =>false,'class' =>'form-control',"div"=>false,'placeholder' =>$placeholder)); ?>
	  <span class="input-group-btn">
	  	<?php echo $this->Form->submit('Search', array('class' => 'btn bg-teal srchbtn', 'div' => false));?>
	 </span>
	</div>
	<?php $this->Form->end(); ?>
</div>
