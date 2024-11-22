<?php 
include('../functions.php');
include('../_connect.php');
if(!empty($_POST)){
	// $password = mysqli_real_escape_string($mysqli,$_POST['pass']);
	$app_name = "Email Wire pvt.ltd";
	$login_email = mysqli_real_escape_string($mysqli, $_POST['login_email']);
	$username = mysqli_real_escape_string($mysqli, $_POST['name']);
	$parts=explode("@",$login_email);
	$username=ucfirst($parts[0]);
	$password =$username."!1@2#3$4@5";
	$pass_encrypted = hash('sha512', $password.'PectGtma');
	$language="en_US";
	$timezone="America/New_York";
	$uId= $_POST['id'];
	$q = 'INSERT INTO login (name, company, username, password, tied_to, app, timezone, language, staff_user_id) VALUES ("'.$username.'", "'.$app_name.'", "'.$login_email.'", "'.$pass_encrypted.'",1,1,"'.$timezone.'", "'.$language.'","'.$uId.'")';
	$r = mysqli_query($mysqli, $q); 
}