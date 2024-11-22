<?php 
    if(isset($villageDropdown) && count($villageDropdown)>1){
        foreach($villageDropdown as $each=>$value){ 
           echo "<option value='".$each."'>".$value."</option>";
        }
    } 
?>