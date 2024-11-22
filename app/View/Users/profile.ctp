<?php 
switch ($role_id) {
    case '3':
    include 'client_profile.ctp';
    break;
    default:
    include 'subscriber_profile.ctp';
    break;
}

?>
