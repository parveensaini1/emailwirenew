<?php 
    if(isset($blockDropdown) && count($blockDropdown)>1){
        foreach($blockDropdown as $each=>$value){ 
           echo "<option value='".$each."'>".$value."</option>";
        }
    } 
?>