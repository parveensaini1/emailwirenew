<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since 1.0.0
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="profile" href="https://gmpg.org/xfn/11" />
	
	<!-- Chrome, Firefox OS and Opera -->
	<meta name="theme-color" content="#333333">
	<!-- Windows Phone -->
	<meta name="msapplication-navbutton-color" content="#333333">
	<!-- iOS Safari -->
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
		
	<link rel="apple-touch-icon" sizes="57x57" href="<?php bloginfo('template_url'); ?>/favicon/apple-touch-icon-57x57.png" />
	<link rel="apple-touch-icon" sizes="114x114" href="<?php bloginfo('template_url'); ?>/favicon/apple-touch-icon-114x114.png" />
	<link rel="apple-touch-icon" sizes="72x72" href="<?php bloginfo('template_url'); ?>/favicon/apple-touch-icon-72x72.png" />
	<link rel="apple-touch-icon" sizes="144x144" href="<?php bloginfo('template_url'); ?>/favicon/apple-touch-icon-144x144.png" />
	<link rel="apple-touch-icon" sizes="60x60" href="<?php bloginfo('template_url'); ?>/favicon/apple-touch-icon-60x60.png" />
	<link rel="apple-touch-icon" sizes="120x120" href="<?php bloginfo('template_url'); ?>/favicon/apple-touch-icon-120x120.png" />
	<link rel="apple-touch-icon" sizes="76x76" href="<?php bloginfo('template_url'); ?>/favicon/apple-touch-icon-76x76.png" />
	<link rel="apple-touch-icon" sizes="152x152" href="<?php bloginfo('template_url'); ?>/favicon/apple-touch-icon-152x152.png" />
	<link rel="icon" type="image/png" href="<?php bloginfo('template_url'); ?>/favicon/favicon-196x196.png" sizes="196x196" />
	<link rel="icon" type="image/png" href="<?php bloginfo('template_url'); ?>/favicon/favicon-96x96.png" sizes="96x96" />
	<link rel="icon" type="image/png" href="<?php bloginfo('template_url'); ?>/favicon/favicon-32x32.png" sizes="32x32" />
	<link rel="icon" type="image/png" href="<?php bloginfo('template_url'); ?>/favicon/favicon-16x16.png" sizes="16x16" />
	<link rel="icon" type="image/png" href="<?php bloginfo('template_url'); ?>/favicon/favicon-128.png" sizes="128x128" />
	<meta name="application-name" content="<?php bloginfo( 'name' ); ?>"/>
	<meta name="msapplication-TileColor" content="#333333" />
	<meta name="msapplication-TileImage" content="<?php bloginfo('template_url'); ?>/favicon/mstile-144x144.png" />
	<meta name="msapplication-square70x70logo" content="<?php bloginfo('template_url'); ?>/favicon/mstile-70x70.png" />
	<meta name="msapplication-square150x150logo" content="<?php bloginfo('template_url'); ?>/favicon/mstile-150x150.png" />
	<meta name="msapplication-wide310x150logo" content="<?php bloginfo('template_url'); ?>/favicon/mstile-310x150.png" />
	<meta name="msapplication-square310x310logo" content="<?php bloginfo('template_url'); ?>/favicon/mstile-310x310.png" />
	
	<?php wp_head(); ?>
	
	<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/plugin/fontawesome/css/all.css">
	<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/plugin/bootstrap/css/bootstrap.css">
	
	
	<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/style.css?v=31">	
	<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/responsive.css?v=31">
</head>
<?php 
if(is_page('thank-you')){
	setcookie('_Wmpci_Popup','true', time() + (86400 * 60),"/");

}
?>
<body <?php body_class(); ?>>
<?php wp_body_open();
	
 ?>
<div id="page" class="site">

<header id="masthead" class="<?php echo is_singular() && twentynineteen_can_show_post_thumbnail() ? 'site-header featured-images' : 'site-header'; ?>">
		<div class="site-branding-container">
		<?php 

		get_template_part( 'template-parts/header/site', 'branding' );
			
		 ?>
	</div>
</header>	

<div id="content" class="site-content">
