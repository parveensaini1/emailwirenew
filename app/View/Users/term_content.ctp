<?php 
$term="<div class='term-content'>".Configure::read('Site.PR.agreement')."</div>";
 echo $term.$this->Form->input('tos', array('type'=>'checkbox',"class"=>"custom_check tosfield",'div' =>'hide tosdiv','id'=>"PressReleaseTos",'label'=>"I Agree",'required'=>"required"));
?>