<?php
/**
 * Displays header site branding
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since 1.0.0
 */
?>
<div class="site-branding">
	<div class="dm-topbar">
		<div class="container">			 
			<div class="dm-topbar-left">
				<a href="<?php echo str_replace('blog/','',site_url('/')); ?>users/create-newsroom">CREATE NEWSROOM</a>
			</div>

			<div class="right_header-block">
				<div class="dm-top-bar-center">
					<ul>
						<li><a href="<?php echo str_replace('blog/','',site_url('/')); ?>users/login">Log In</a></li>
						<li><a href="<?php echo str_replace('blog/','',site_url('/')); ?>users/signup">Register</a></li>
						<li><a href="<?php echo str_replace('blog/','',site_url('/')); ?>contact-us/">Contact</a></li>
						<li><a href="<?php echo str_replace('blog/','',site_url('/')); ?>users/become-subscriber">Subscribe</a></li>
						<li><a href="<?php echo str_replace('blog/','',site_url('/')); ?>blog/">Blog</a></li>    
					</ul>			
				</div>

				<div class="dm-topbar-right">		  

					<a href="<?php echo str_replace('blog/','',site_url('/')); ?>plans/online-distribution">SUBMIT A PRESS RELEASE</a>
				</div>
			</div>
			
	 	</div>
	</div>
	
	<div class="email_wire_header">
		<div class="container">

			<div class="logo">
				<?php if ( has_custom_logo() ) : ?>
					<div class="logo_inner"><?php the_custom_logo(); ?></div>
				<?php endif; ?>		
			</div>

			<div class="email_wire_navi">
				<?php dynamic_sidebar( 'sidebar-1' ); ?>
			</div>
			<div class="email_wire_search">
				<form class="navbar-form" action="<?php echo str_replace('blog/','/',site_url('/')); ?>blog/" role="search" method="get">
					<div class="custom-header-search input-group add-on">
						<input class="form-control"  placeholder="Search …" value="" name="s" id="srch-term" type="search">
						<div class="input-group-btn">
							<button class="btn btn-default" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
						</div>
					</div>
				</form>
			</div>
		</div>	
	</div>
	
	<div class="dm-navmenu">
	   <?php if ( has_nav_menu( 'menu-1' ) ) : ?>
			<div class="container">
				<nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'Top Menu', 'twentynineteen' ); ?>">	
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'menu-1',
							'menu_class'     => 'main-menu',
							'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
						)
					);
					?>
				</nav><!-- #site-navigation -->
			</div>
		<?php endif; ?>
	</div>
</div><!-- .site-branding -->
