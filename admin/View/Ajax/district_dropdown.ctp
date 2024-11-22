<?php 
    if(isset($districtDropdown) && count($districtDropdown)>1){
        foreach($districtDropdown as $each=>$value){ 
           echo "<option value='".$each."'>".$value."</option>";
        }
    } 
?>