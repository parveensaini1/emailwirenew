<?php 
switch ($role_id) {
    case '3':
    include 'client_dashboard.ctp';
    break;
    default:
    include 'subscriber_dashboard.ctp';
    break;
}

?>
