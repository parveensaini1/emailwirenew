<?php 
if($count==1){
echo $this->Form->input($model.".translation_amount", array('type' =>'text','class' => 'form-control','label' => 'Translation amount'));
}else{
	echo "0";
}
?>