<?php 
	//----------------------------------------------------------------------------------//	
	//                               COMPULSORY SETTINGS
	//----------------------------------------------------------------------------------//
	
	/*  Set the URL to your Sendy installation (without the trailing slash) */
	define('SITEURL', 'http://netleontech.com/email_wire/');
	define('SITEADMINURL', 'http://netleontech.com/email_wire/admin');
	define('APP_PATH', 'http://netleontech.com/email_wire/sendy');

	ini_set('session.gc_maxlifetime', 604800);
	session_set_cookie_params(604800);
	
	
	/*  MySQL database connection credentials (please place values between the apostrophes) */
	$dbHost = 'localhost'; //MySQL Hostname
	$dbUser = 'user_email_wire'; //MySQL Username
	$dbPass = 'UU]P@DlsBKr9'; //MySQL Password
	$dbName = 'email_wire'; //MySQL Database Name
	
	
	//----------------------------------------------------------------------------------//	
	//								  OPTIONAL SETTINGS
	//----------------------------------------------------------------------------------//	
	
	/* 
		Change the database character set to something that supports the language you'll
		be using. Example, set this to utf16 if you use Chinese or Vietnamese characters
	*/
	$charset = 'utf8mb4';
	
	/*  Set this if you use a non standard MySQL port.  */
	$dbPort = 3306;	
	
	/*  Domain of cookie (99.99% chance you don't need to edit this at all)  */
	define('COOKIE_DOMAIN', '');
	
	//----------------------------------------------------------------------------------//
?>