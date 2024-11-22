<?php

$categories = [
    1 => 'media location',
    2 => 'Media Type',
    3 => 'Media Name',
];

?>
<div class="row"> 
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Search</h3> 
        <div class="card-tools">
<?php
echo $this->Form->create($model, ['class' => 'form-inline']);
echo $this->Form->label('category', 'Search by:');
echo $this->Form->select('category', [
    'options' => $categories,
]);
?>

            
		<?php echo $this->Form->create($model, array('class'=>'-right form-','inputDefaults' => array('label' => false,'div' => false)) ); ?>  
        <div class="input-group input-group-sm" >
		  <?php echo $this->Form->input('name', array('value' => $keyword,'size'=>"50",'autocorrect' => 'off','autocapitalize' =>'off','autocomplete' =>'off','label' =>false,'class' =>'form-control',"div"=>false,'placeholder' =>"Search by name...")); ?>
            <div class="input-group-append">
              <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
            </div>
             <?php echo $this->Form->end(); ?>
          </div>
        </div>
      </div>
      </div>
    </div>
</div> 
<style>
#NetworkWebsiteCategory{
    padding: 3px;
}
</style>