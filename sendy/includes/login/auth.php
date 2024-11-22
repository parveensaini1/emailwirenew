<?php 
	if(isset($_COOKIE['logged_in'])) $cookie = $_COOKIE['logged_in'];
	else $cookie = '';
	
	if(
		!is_null(get_app_info('userID')) && 
		!is_null(get_app_info('email')) && 
		!is_null(get_app_info('password')) && 
		$cookie===hash('sha512', get_app_info('userID').get_app_info('email').get_app_info('password').'PectGtma')
	   )
		start_app();
	else
	{
		$request_uri = $_SERVER['REQUEST_URI'];
		$request_uri_array = explode('/', $request_uri);
		$redirect_to = $request_uri_array[count($request_uri_array)-1];
		echo '<script type="text/javascript">window.location = "'.addslashes(SITEURL."/users/login").'";</script>';
		exit;
	}
?>