<?php 
$file_min_size = 1;
$file_max_size = 500;
 
$this->assign('payactive', 'active');

 $this->start('content-header');?>
 

 <?php $this->end();?> 

<div class="panel panel-primary">
<div class="panel-heading">
<h3 class="panel-title">Custom Script<?php //echo Configure::read('Site.box_title_recruiter');?></h3>
</div>
<div class="panel-body">

<?php echo $this->Form->create('State',array('class'=>'form-horizontal1','type'=>'file','id'=>'MasterReportForm'));?>	


<div class="row">

	<div class="col-xs-12 col-sm-12">
		 <?php $fld = 'script'; $lbl = __('Custom Script'); ?>
		 <div class="form-group <?php echo ($this->Form->error($model . "." . $fld)) ? "has-error" : ""; ?>">
		<?php echo $this->Form->label($model . "." . $fld, $lbl . ':  <span class="mandatory">*</span>');?>
		<?php echo  $this->Form->textarea($model . "." . $fld,array('class' => 'form-control input-sm',  'rows' => '10','cols' => '2000','required' => '','autocomplete' => 'off','placeholder'=> 'Enter '. $lbl . ' (SELECT * FROM `itday_qualifications`) '));
		if ($this->Form->isFieldError($fld)) {
			echo '<span class="help-block">' . $this->Form->error($fld) . '</span>';
		}
		?>
	 </div>
	</div>  
</div> 

<div class="form-group">
<div class="col-sm-2 col-sm-offset-10">
	<button type="submit" class="btn btn-lg btn-primary check"   value="Search" ><i class="fa fa-save"></i> Submit</button>
</div>
</div>
 <?php echo $this->Form->end();?> 
  
 
  

</div>
</div>
 