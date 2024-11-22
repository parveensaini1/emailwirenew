<?php
	/*
Plugin Name: Responsive menu & navigation sidebar
Description: Responsive header top menu & navigation sidebar plugin for Wordpress. It is a perfect website header menu. More than 50 Customizeable settings. Flawless design. It is beautiful and works everywhere.You can choose every single color, so you can build your own custom design. You can also choose yourself what screen sizes it should be shown on.
Version: 1.2
Author: Hmg Designs
License: GPL2
*/	function hmg_responsivemenu_settings_link($links) {
		$settings_link = '<a href="options-general.php?page=responsive-menu-navigation/responsivemenu.php">Settings</a>, <a style="font-weight:900;" href="http://ahogenhaven.com/stuff/responsive-menu/">Upgrade to $10 version</a>, <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6GWCAZKFHX2NC">Buy the developer a cup of coffee!</a>';
		array_unshift($links, $settings_link);
		return $links;
	}

	$plugin = plugin_basename(__FILE__);
	add_filter("plugin_action_links_$plugin", 'hmg_responsivemenu_settings_link' );
	function hmg_responsivemenu_admin_menu() {
		add_submenu_page('options-general.php', 'responsivemenu Plugin Settings', 'Responsive menu', 'administrator', __FILE__, 'hmg_responsivemenu_page');
	}

	
	function hmg_responsivemenu_page() {
		
		if ( isset ($_POST['update_hmg_responsivemenu']) == 'true' ) {
			hmg_responsivemenu_update();
		}

		?>
<div class="commercial-banner" style="background: #3ecdbb; background: -moz-linear-gradient(left, #3ecdbb 0%, #37b6bc 100%); background: -webkit-linear-gradient(left, #3ecdbb 0%,#37b6bc 100%); background: linear-gradient(to right, #3ecdbb 0%,#37b6bc 100%); color: #fff; padding: 20px; margin-left: -20px; font-size: 18px; line-height: 140%;">
	<h2 style="font-size:30px;color:#fff;">Upgrade to $10 version for these features:</h2>
	<ul>
		<li>- 40+ more settings!</li>
		<li>- Add all the widgets to the sidebar you want!</li>
		<li>- Customize colors on literally everything!</li>
		<li>- Unlimited Support!</li>
		<a style="background-color: #fff; color: #6f6f6f; text-decoration: none; padding: 15px 30px; display: inline-block; border-radius: 3px; margin-top: 10px;" href="http://ahogenhaven.com/stuff/responsive-menu/" target="_blank">Buy now, it's only 10 dollars!</a>
	
		<p>You can also buy me a <a style="color:#fff;font-weight:900;" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6GWCAZKFHX2NC">cup of coffe here</a>, that might make you give you the 10 dollar version as well :)</p>
	</ul>
</div>


	<div class="hmg-responsivemenu-settings">
		<h1>Responsive Menu Settings (Requires $10 Version)</h1>
		<h2 style="font-size: 21px;font-weight: 300;color: #585858;margin: 5px 0;">How to change colors:</h2>
		<ol>
			<li>Go to Appearance</li>
			<li>Go to Customize</li>
			<li>Go to Responsive Menu Plugin Colors</li>
			<li>Choose which colors you want</li>
		</ol>
		<hr style="margin:30px 0">
		<form method="POST" action="">
			<input type="hidden" name="update_hmg_responsivemenu" value="true" />
			<div id="animations"></div>
			<h2 style="font-size: 21px;font-weight: 300;color: #585858;margin: 5px 0;">Menu button design</h2>
			<p><em>Choose a design that fits your website!</em></p>
			<?php  $responsivemenu_button_animation = get_option('hmg_responsivemenu_button_animation'); ?>
			<label><input value="responsivemenunoanimation" type="radio" name="responsivemenu_button_animation" <?php 
		
		if ($responsivemenu_button_animation=='responsivemenunoanimation') {
			echo 'checked';
		}

		?>> Standard Design: 3 lines</label><br>
			<label><input value="responsivemenuminusanimation" type="radio" name="responsivemenu_button_animation" <?php 
		
		if ($responsivemenu_button_animation=='responsivemenuminusanimation') {
			echo 'checked';
		}

		?>> Minus Icon</label><br>
			<label><input value="responsivemenuxanimation" type="radio" name="responsivemenu_button_animation" <?php 
		
		if ($responsivemenu_button_animation=='responsivemenuxanimation') {
			echo 'checked';
		}

		?>> X Icon</label><br>
			<div id="general-options"></div>
			<hr style="margin:30px 0">
			<h2 style="font-size: 21px;font-weight: 300;color: #585858;margin: 5px 0;">Menu behaviour when scrolling</h2>
			<p><em>If you want the menu icon to not stay on the screen while scrolling, check this option.</em></p>
			<label><input type="checkbox" name="responsivemenu_absolute_position" id="responsivemenu_absolute_position" <?php  echo get_option('hmg_responsivemenu_absolute_position'); ?> /> Position the button absolute.</label>
			<hr style="margin:30px 0">
			<h2 style="font-size: 21px;font-weight: 300;color: #585858;margin: 5px 0;">Make menu button invisible</h2>
			<p><em>
				Hiding the menu button can be used if you want to create your own button. <br>
				To give a custom element the button fuction give it the CSS class: "responsivemenu-main-menu-activator"
			</em>
		</p>
		<label><input type="checkbox" name="responsivemenu_hide_main_menu_button" id="responsivemenu_hide_main_menu_button" <?php  echo get_option('hmg_responsivemenu_hide_main_menu_button'); ?> /> Hide Button</label><br>
		<hr style="margin:30px 0">
		<h2 style="font-size: 21px;font-weight: 300;color: #585858;margin: 5px 0;">Make search button invisible</h2>
		<p><em>Responsive Menu has it's own build in search function, if you don't want to use it you can hide the search icon</em></p>
		<label><input type="checkbox" name="responsivemenu_hide_search" id="responsivemenu_hide_search" <?php  echo get_option('hmg_responsivemenu_hide_search'); ?> /> Make search button invisible</label><br>
		<hr style="margin:30px 0">
		<h2 style="font-size: 21px;font-weight: 300;color: #585858;margin: 5px 0;">Make secondary menu invisible</h2>
		<p><em>If you only want one menu, simply check the field below</em></p>
		<label><input type="checkbox" name="responsivemenu_hide_secondary_menu" id="responsivemenu_hide_secondary_menu" <?php  echo get_option('hmg_responsivemenu_hide_secondary_menu'); ?> /> Make second menu invisible.</label><br>
<hr style="margin:30px 0">
		<h2 style="font-size: 21px;font-weight: 300;color: #585858;margin: 5px 0;">Black background opacity</h2>
		<p><em>The default is 0.3 - If you want to change it then give it a value from 0.1 to 1</em></p>
		<input style="max-width:35px;" type="text" name="responsivemenu_background_overlay_opacity" id="responsivemenu_background_overlay_opacity" value="<?php  echo get_option('hmg_responsivemenu_background_overlay_opacity'); ?>"/>
		<hr style="margin:30px 0">
		<h2 style="font-size: 21px;font-weight: 300;color: #585858;margin: 5px 0;">Fontawesome loading</h2>
		<label><input type="checkbox" name="responsivemenu_fa_no_load" id="responsivemenu_fa_no_load" <?php  echo get_option('hmg_responsivemenu_fa_no_load'); ?> /> Don't load the FontAwesome icons.</label><br>
		<div id="heading-options"></div>
		<hr style="margin:30px 0">
		<h2 style="font-size: 21px;font-weight: 300;color: #585858;margin: 5px 0;">Heading headline title</h2>
		<p><em>Type in what you want your heading text to be, if you don't want one just leave it blank.</em></p>
		<input style="width:100%;max-width:400px;" type="text" name="responsivemenu_heading_text" id="responsivemenu_heading_text" value="<?php  echo get_option('hmg_responsivemenu_heading_text'); ?>"/>
		<hr style="margin:30px 0">
		<h2 style="font-size: 21px;font-weight: 300;color: #585858;margin: 5px 0;">Heading headline tagline</h2>
		<p><em>The headline tagline will be displayed below your heading title</em></p>
		<input style="width:100%; max-width:400px;" type="text" name="responsivemenu_subheading_text" id="responsivemenu_subheading_text" value="<?php  echo get_option('hmg_responsivemenu_subheading_text'); ?>"/>
		<hr style="margin:30px 0">
		<h2 style="font-size: 21px;font-weight: 300;color: #585858;margin: 5px 0;">Heading Height</h2>
		<p><em>If you don't like the normal heading height you can change it here, the default one is 200 pixels</em></p>
		<label><input style="max-width:35px;" type="text" name="responsivemenu_heading_height" id="responsivemenu_heading_height" value="<?php  echo get_option('hmg_responsivemenu_heading_height'); ?>"/> px heading height</label>
		<hr style="margin:30px 0">
		<h2 style="font-size: 21px;font-weight: 300;color: #585858;margin: 5px 0;">Heading black overlay opacity</h2>
		<p><em>The default is 0.2 - you can change it from 0.1 to 1</em></p>
		<input style="max-width:35px;" type="text" name="responsivemenu_heading_overlay" id="responsivemenu_heading_overlay" value="<?php  echo get_option('hmg_responsivemenu_heading_overlay'); ?>"/>
		Background opacity
		<hr style="margin:30px 0">
		<h2 style="font-size: 21px;font-weight: 300;color: #585858;margin: 5px 0;">Heading Image</h2>
		<p><em>Display your logo or the like in the heading for better branding - Simply enter the full URL of the image.</em></p>
		<input style="width:100%; max-width:400px;" type="text" name="responsivemenu_heading_image" id="responsivemenu_heading_image" value="<?php  echo get_option('hmg_responsivemenu_heading_image'); ?>"/>
		<hr style="margin:30px 0">
		<h2 style="font-size: 21px;font-weight: 300;color: #585858;margin: 5px 0;">Heading image pattern</h2>
		<p><em>If your heading image is a pattern then click the box, if it's unchecked the heading image will be full sized</em></p>
		<label><input type="checkbox" name="responsivemenu_heading_image_pattern" id="responsivemenu_heading_image_pattern" <?php  echo get_option('hmg_responsivemenu_heading_image_pattern'); ?> /> My heading image is a pattern</label>
		<div id="hide-at-certain-width-resolution"></div>
		<hr style="margin:30px 0">
		<h2 style="font-size: 21px;font-weight: 300;color: #585858;margin: 5px 0;">Hide at certain width</h2>
		<p><em>If you have another menu for desktop for example, then you can hide this one on big screens.</em></p>
		Hide menu if browser width or is between <input style="width:50px;" type="text" name="responsivemenu_smaller_than" id="responsivemenu_smaller_than" value="<?php  echo get_option('hmg_responsivemenu_smaller_than'); ?>"/> and <input style="width:50px;" type="text" name="responsivemenu_larger_than" id="responsivemenu_larger_than" value="<?php  echo get_option('hmg_responsivemenu_larger_than'); ?>"/> pixels (fill both fields).
<hr style="margin:30px 0">
		<h2 style="font-size: 21px;font-weight: 300;color: #585858;margin: 5px 0;">Save changes</h2>
		<p><input type="submit" name="search" value="Save Options" class="button button-primary" /></p>
	</form>
</div>
<?php 
	}

	
	function hmg_responsivemenu_update() {
		/* menu button animation */
		
		if ( isset ($_POST['responsivemenu_button_animation']) == 'true' ) {
			update_option('hmg_responsivemenu_button_animation', $_POST['responsivemenu_button_animation']);
		}

		/* absolute/fixed positioning */
		
		if ( isset ($_POST['responsivemenu_absolute_position'])=='on') {
			$display = 'checked';
		} else {
			$display = '';
		}

		update_option('hmg_responsivemenu_absolute_position', $display);
		/* hide main menu button */
		
		if ( isset ($_POST['responsivemenu_hide_main_menu_button'])=='on') {
			$display = 'checked';
		} else {
			$display = '';
		}

		update_option('hmg_responsivemenu_hide_main_menu_button', $display);
		/* hide search */
		
		if ( isset ($_POST['responsivemenu_hide_search'])=='on') {
			$display = 'checked';
		} else {
			$display = '';
		}

		update_option('hmg_responsivemenu_hide_search', $display);
		/* hide secondary menu */
		
		if ( isset ($_POST['responsivemenu_hide_secondary_menu'])=='on') {
			$display = 'checked';
		} else {
			$display = '';
		}

		update_option('hmg_responsivemenu_hide_secondary_menu', $display);
		/* background overlay opacity */
		update_option('hmg_responsivemenu_background_overlay_opacity', $_POST['responsivemenu_background_overlay_opacity']);
		/* don't load FontAwesome */
		
		if ( isset ($_POST['responsivemenu_fa_no_load'])=='on') {
			$display = 'checked';
		} else {
			$display = '';
		}

		update_option('hmg_responsivemenu_fa_no_load', $display);
		/* heading text */
		update_option('hmg_responsivemenu_heading_text', $_POST['responsivemenu_heading_text']);
		/* subheading text */
		update_option('hmg_responsivemenu_subheading_text', $_POST['responsivemenu_subheading_text']);
		/* custom heading height */
		update_option('hmg_responsivemenu_heading_height', $_POST['responsivemenu_heading_height']);
		/* heading overlay opacity */
		update_option('hmg_responsivemenu_heading_overlay', $_POST['responsivemenu_heading_overlay']);
		/* heading image */
		update_option('hmg_responsivemenu_heading_image', $_POST['responsivemenu_heading_image']);
		/* heading pattern */
		
		if ( isset ($_POST['responsivemenu_heading_image_pattern'])=='on') {
			$display = 'checked';
		} else {
			$display = '';
		}

		update_option('hmg_responsivemenu_heading_image_pattern', $display);
		/* larger than, lower than */
		update_option('hmg_responsivemenu_larger_than', $_POST['responsivemenu_larger_than']);
		update_option('hmg_responsivemenu_smaller_than', $_POST['responsivemenu_smaller_than']);
	}

	add_action('admin_menu', 'hmg_responsivemenu_admin_menu');
	?>
<?php
 function hmg_responsivemenu_meta() { ?>
<meta name="msapplication-tap-highlight" content="no" /> 
<?php
	}

	add_action('wp_head','hmg_responsivemenu_meta');
	//
	// Add menu to theme
	//
	function hmg_responsivemenu_footer() {
		?>
<?php  if( get_option('hmg_responsivemenu_hide_main_menu_button') ) { ?>
<?php  } else { ?>
<div class="responsivemenu-main-menu-button-wrapper<?php  if ( is_admin_bar_showing() ) { ?> wp-toolbar-active<?php  } ?><?php  if( get_option('hmg_responsivemenu_absolute_position') ) { ?> responsivemenu-absolute<?php  } ?>">
<div class="responsivemenu-main-menu-button">
<div class="responsivemenu-main-menu-button-middle"></div>
</div>
</div>
<?php  } ?>
<div class="responsivemenu-main-wrapper<?php  if ( is_admin_bar_showing() ) { ?> wp-toolbar-active<?php  } ?>">
<div class="responsivemenu-main-wrapper-inner">
<div class="responsivemenu-main">
<div class="responsivemenu-heading-wrapper">
<div class="responsivemenu-heading-inner">
				<div class="responsivemenu-heading-text">
								<?php  echo get_option('hmg_responsivemenu_heading_text'); ?>
							</div>
							<?php  if( get_option('hmg_responsivemenu_subheading_text') ) { ?>
							<div class="responsivemenu-subheading-text">
								<?php  echo get_option('hmg_responsivemenu_subheading_text'); ?>
							</div>
							<?php  } ?>
							<?php  if( get_option('hmg_responsivemenu_hide_search') ) { ?>
							<?php  } else { ?>
							<div class="responsivemenu-search-button">
								<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="512px" height="512px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
									<path id="magnifier-3-icon" d="M208.464,363.98c-86.564,0-156.989-70.426-156.989-156.99C51.475,120.426,121.899,50,208.464,50
									c86.565,0,156.991,70.426,156.991,156.991C365.455,293.555,295.029,363.98,208.464,363.98z M208.464,103.601
									c-57.01,0-103.389,46.381-103.389,103.39s46.379,103.389,103.389,103.389c57.009,0,103.391-46.38,103.391-103.389
									S265.473,103.601,208.464,103.601z M367.482,317.227c-14.031,20.178-31.797,37.567-52.291,51.166L408.798,462l51.728-51.729
									L367.482,317.227z"/>
								</svg>
							</div>
							<?php  } ?>
							<div class="responsivemenu-search-close-wrapper">
								<div class="responsivemenu-search-close-button">
								</div>
							</div>
							<div class="responsivemenu-search-wrapper">
								<form method="get" id="searchform" action="<?php  echo esc_url( home_url('') ); ?>/">
									<input type="text" name="s" id="s">
								</form>
							</div>
							<?php  if( get_option('hmg_responsivemenu_hide_secondary_menu') ) { ?>
							<?php  } else { ?>
							<div class="responsivemenu-secondary-menu-button">
								<div class="responsivemenu-secondary-menu-wrapper">
									<?php  wp_nav_menu( array( 'container_class' => 'responsivemenu-by-hmg-secondary', 'theme_location' => 'responsivemenu-by-hmg-secondary', 'fallback_cb' => '' ) ); ?>
								</div>
								<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="512px" height="512px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
									<path id="menu-7-icon" d="M153.415,256c0,28.558-23.15,51.708-51.707,51.708C73.15,307.708,50,284.558,50,256
									s23.15-51.708,51.708-51.708C130.265,204.292,153.415,227.442,153.415,256z M256,204.292c-28.558,0-51.708,23.15-51.708,51.708
									s23.15,51.708,51.708,51.708s51.708-23.15,51.708-51.708S284.558,204.292,256,204.292z M410.292,204.292
									c-28.557,0-51.707,23.15-51.707,51.708s23.15,51.708,51.707,51.708C438.85,307.708,462,284.558,462,256
									S438.85,204.292,410.292,204.292z"/>
								</svg>
							</div>
							<?php  } ?>
						</div>
					</div>
					<div class="responsivemenu-heading-overlay"></div>
					<div class="responsivemenu-heading-image"></div>
					<div class="responsivemenu-menu-wrapper">
						<?php  wp_nav_menu( array( 'container_class' => 'responsivemenu-by-hmg', 'theme_location' => 'responsivemenu-by-hmg', 'fallback_cb' => '' ) ); ?>
						<div class="responsivemenu-widgets-wrapper">
							<?php  if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('responsivemenu Widgets') ) : ?><?php  endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="responsivemenu-main-background<?php  if ( is_admin_bar_showing() ) { ?> wp-toolbar-active<?php  } ?>">
		</div>
		<div class="responsivemenu-background-overlay"></div>
		<?php
	}

	add_action('wp_footer','hmg_responsivemenu_footer');
	//
	// ENQUEUE responsivemenu.css
	//
	function hmg_responsivemenu_css() {
		wp_register_style( 'hmg-responsivemenu-css', plugins_url( '/responsivemenu.css', __FILE__ ) . '', array(), '1', 'all' );
		wp_enqueue_style( 'hmg-responsivemenu-css' );
	}

	add_action( 'wp_enqueue_scripts', 'hmg_responsivemenu_css' );
	//
	// ENQUEUE responsivemenu-accordion.js (only if main menu not disabled)
	//
	function hmg_responsivemenu_accordion() {
		wp_register_script( 'hmg-responsivemenu-accordion', plugins_url( '/responsivemenu-accordion.js', __FILE__ ) . '', array( 'jquery' ), '1' );
		wp_enqueue_script( 'hmg-responsivemenu-accordion' );
	}

	add_action( 'wp_enqueue_scripts', 'hmg_responsivemenu_accordion' );
	//
	// ENQUEUE responsivemenu.js
	//
	function hmg_responsivemenu_js() {
		wp_register_script( 'hmg-responsivemenu-js', plugins_url( '/responsivemenu.js', __FILE__ ) . '', array( 'jquery' ), '1', true );
		wp_enqueue_script( 'hmg-responsivemenu-js' );
	}

	add_action( 'wp_enqueue_scripts', 'hmg_responsivemenu_js' );
	//
	// ENQUEUE search.js
	//
	function hmg_responsivemenu_search_js() {
		wp_register_script( 'hmg-responsivemenu-search-js', plugins_url( '/search.js', __FILE__ ) . '', array( 'jquery' ), '1', true );
		wp_enqueue_script( 'hmg-responsivemenu-search-js' );
	}

	add_action( 'wp_enqueue_scripts', 'hmg_responsivemenu_search_js' );
	//
	// Enqueue Google WebFonts
	//
	//function hmg_responsivemenu_font() {
	//	$protocol = is_ssl() ? 'https' :
	//	'http';
	//	wp_enqueue_style( 'hmg-responsivemenu-font', "$protocol://fonts.googleapis.com/css?family=Roboto:400,500' rel='stylesheet' type='text/css" );
	//}

	//add_action( 'wp_enqueue_scripts', 'hmg_responsivemenu_font' );
	//
	// Enqueue font-awesome.min.css (icons for menu, if option to hide not enabled)
	//
	
	if( get_option('hmg_responsivemenu_fa_no_load') ) {
	} else {
		function hmg_responsivemenu_fontawesome() {
			wp_register_style( 'responsivemenu-fontawesome', plugins_url( '/fonts/font-awesome/css/font-awesome.min.css', __FILE__ ) . '', array(), '1', 'all' );
			wp_enqueue_style( 'responsivemenu-fontawesome' );
		}

		add_action( 'wp_enqueue_scripts', 'hmg_responsivemenu_fontawesome' );
	}

	//
	// Register Custom Menu Function
	//
	
	if (function_exists('register_nav_menus')) {
		register_nav_menus( array('responsivemenu-by-hmg' => ( 'responsivemenu plugin (primary)' ),'responsivemenu-by-hmg-secondary' => ( 'responsivemenu plugin (secondary)' )) );
	}

	///////////////////////////////////////
	// Register Widgets
	///////////////////////////////////////

	//
	// Add color options to Appearance > Customize
	//
	add_action( 'customize_register', 'hmg_responsivemenu_customize_register' );
	function hmg_responsivemenu_customize_register($wp_customize){

	}

	//
	// Insert theme customizer options into the footer
	//
	function hmg_responsivemenu_header_customize() {
		?>
		<!-- BEGIN CUSTOM COLORS (WP THEME CUSTOMIZER) -->
		<!-- menu button -->
		<?php  $hmg_responsivemenu_menu_button_color = get_option('hmg_responsivemenu_menu_button_color'); ?>
		<?php  $hmg_responsivemenu_menu_button_hover_color = get_option('hmg_responsivemenu_menu_button_hover_color'); ?>
		<?php  $hmg_responsivemenu_menu_button_active_color = get_option('hmg_responsivemenu_menu_button_active_color'); ?>
		<?php  $hmg_responsivemenu_menu_button_active_hover_color = get_option('hmg_responsivemenu_menu_button_active_hover_color'); ?>
		<!-- secondary menu button -->
		<?php  $hmg_responsivemenu_secondary_menu_button_color = get_option('hmg_responsivemenu_secondary_menu_button_color'); ?>
		<?php  $hmg_responsivemenu_secondary_menu_button_hover_color = get_option('hmg_responsivemenu_secondary_menu_button_hover_color'); ?>
		<?php  $hmg_responsivemenu_secondary_menu_button_active_color = get_option('hmg_responsivemenu_secondary_menu_button_active_color'); ?>
		<?php  $hmg_responsivemenu_secondary_menu_button_active_hover_color = get_option('hmg_responsivemenu_secondary_menu_button_active_hover_color'); ?>
		<!-- search button -->
		<?php  $hmg_responsivemenu_search_button_color = get_option('hmg_responsivemenu_search_button_color'); ?>
		<?php  $hmg_responsivemenu_search_button_hover_color = get_option('hmg_responsivemenu_search_button_hover_color'); ?>
		<!-- search close button -->
		<?php  $hmg_responsivemenu_search_close_button_color = get_option('hmg_responsivemenu_search_close_button_color'); ?>
		<?php  $hmg_responsivemenu_search_close_button_hover_color = get_option('hmg_responsivemenu_search_close_button_hover_color'); ?>
		<!-- search field border + text -->
		<?php  $hmg_responsivemenu_search_border_color = get_option('hmg_responsivemenu_search_border_color'); ?>
		<?php  $hmg_responsivemenu_search_text_color = get_option('hmg_responsivemenu_search_text_color'); ?>
		<!-- heading + sub-heading text -->
		<?php  $hmg_responsivemenu_heading_text_color = get_option('hmg_responsivemenu_heading_text_color'); ?>
		<?php  $hmg_responsivemenu_subheading_text_color = get_option('hmg_responsivemenu_subheading_text_color'); ?>
		<!-- heading overlay -->
		<?php  $hmg_responsivemenu_heading_overlay_color = get_option('hmg_responsivemenu_heading_overlay_color'); ?>
		<!-- background overlay -->
		<?php  $hmg_responsivemenu_background_overlay_color = get_option('hmg_responsivemenu_background_overlay_color'); ?>
		<!-- main menu -->
		<?php  $hmg_responsivemenu_main_menu_background_color = get_option('hmg_responsivemenu_main_menu_background_color'); ?>
		<?php  $hmg_responsivemenu_main_menu_item_color = get_option('hmg_responsivemenu_main_menu_item_color'); ?>
		<?php  $hmg_responsivemenu_main_menu_item_hover_color = get_option('hmg_responsivemenu_main_menu_item_hover_color'); ?>
		<?php  $hmg_responsivemenu_main_menu_subitem_color = get_option('hmg_responsivemenu_main_menu_subitem_color'); ?>
		<?php  $hmg_responsivemenu_main_menu_subitem_hover_color = get_option('hmg_responsivemenu_main_menu_subitem_hover_color'); ?>
		<?php  $hmg_responsivemenu_main_menu_arrow_color = get_option('hmg_responsivemenu_main_menu_arrow_color'); ?>
		<?php  $hmg_responsivemenu_main_menu_arrow_hover_color = get_option('hmg_responsivemenu_main_menu_arrow_hover_color'); ?>
		<?php  $hmg_responsivemenu_main_menu_icon_color = get_option('hmg_responsivemenu_main_menu_icon_color'); ?>
		<?php  $hmg_responsivemenu_main_menu_icon_hover_color = get_option('hmg_responsivemenu_main_menu_icon_hover_color'); ?>
		<?php  $hmg_responsivemenu_main_menu_border_color = get_option('hmg_responsivemenu_main_menu_border_color'); ?>
		<!-- secondary menu -->
		<?php  $hmg_responsivemenu_secondary_menu_background_color = get_option('hmg_responsivemenu_secondary_menu_background_color'); ?>
		<?php  $hmg_responsivemenu_secondary_menu_border_color = get_option('hmg_responsivemenu_secondary_menu_border_color'); ?>
		<?php  $hmg_responsivemenu_secondary_menu_bottom_border_color = get_option('hmg_responsivemenu_secondary_menu_bottom_border_color'); ?>
		<?php  $hmg_responsivemenu_secondary_menu_item_color = get_option('hmg_responsivemenu_secondary_menu_item_color'); ?>
		<?php  $hmg_responsivemenu_secondary_menu_item_hover_color = get_option('hmg_responsivemenu_secondary_menu_item_hover_color'); ?>
		<!-- widgets -->
		<?php  $hmg_responsivemenu_widget_title_color = get_option('hmg_responsivemenu_widget_title_color'); ?>
		<?php  $hmg_responsivemenu_widget_text_color = get_option('hmg_responsivemenu_widget_text_color'); ?>
		<?php  $hmg_responsivemenu_widget_secondary_text_color = get_option('hmg_responsivemenu_widget_secondary_text_color'); ?>
		<?php  $hmg_responsivemenu_widget_link_color = get_option('hmg_responsivemenu_widget_link_color'); ?>
		<?php  $hmg_responsivemenu_widget_search_border_color = get_option('hmg_responsivemenu_widget_search_border_color'); ?>
		<?php  $hmg_responsivemenu_widget_search_field_color = get_option('hmg_responsivemenu_widget_search_field_color'); ?>

																<!-- END CUSTOM COLORS (WP THEME CUSTOMIZER) -->
																<?php
 }add_action('wp_footer','hmg_responsivemenu_header_customize'); ?>