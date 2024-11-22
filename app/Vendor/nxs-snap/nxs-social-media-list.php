<?php 
 global $nxs_snapAvNts; 
require_once "inc/nxs-networks-class.php";  require_once "nxs-snap-class.php"; if (file_exists("nxs-user-functions.php")) require_once "nxs-user-functions.php"; 

foreach ($nxs_snapAvNts as $avNt){

echo $avNt['code'].":".$avNt['name']; 	
} 

?>