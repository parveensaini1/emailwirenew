<div class="row">
    <div class="col-lg-12"><div class="ew-title full"><?php echo $title_for_layout; ?> </div></div>
</div>
<div id="main-content" class="row">
  	<div id="content" class="col-lg-9 content">
	    <div class="panel panel-default"> 
	     	<div class="panel-body">
	     		<p>Select the date range to search:</p>
	      		<?php 
	      		  echo $this->Form->create($model, array("url"=>SITEURL."news-by-date",'type'=>"get",'novalidate' => 'novalidate', 'inputDefaults' => array('autocomplete'=>"off",'div' => 'form-group', 'class' => 'form-control')));

	      		?>
  		        <div class="row">
                  <div class="col-sm-4">
                     <?php echo $this->Form->input('sd',array('type' =>'text','class'=>'cdatepicker form-control','label'=>'From','autocomplete'=>"off" ));?>
                  </div>
                  <div class="col-sm-4">
                     <?php echo $this->Form->input('ed',array('type' =>'text','class'=>'cdatepicker form-control','label'=>'To','autocomplete'=>"off" ));?>
                  </div>
                   <div class="col-sm-2"><label for="PressReleaseSd">&nbsp; </label><?php echo $this->Form->submit('Search by date', array('class' => 'btn btn-info', 'div' => false)); ?></div>
              </div>
            <?php 
             echo $this->Form->end(); ?>    
	      	</div>
	  	</div>
	</div>
</div>
<script type="text/javascript">
 $(function () {
    $(".cdatepicker").datepicker({
        dateFormat: "yy-mm-dd",
         maxDate : 0,
        changeMonth: true,
        changeYear: true,
    });
    //   $('.timepicker').timepicker();
});

</script>