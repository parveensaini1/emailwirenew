<?php
/**
 * Displays the footer widget area
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since 1.0.0
 */

if ( is_active_sidebar( 'sidebar-3-1' ) ) : ?>

	<aside class="widget-webarea" aria-label="<?php esc_attr_e( 'Footer', 'twentynineteen' ); ?>">
		<div class="container">
		<?php
		if ( is_active_sidebar( 'sidebar-3-1' ) ) {
			?>
					<div class="widget-column footer-widget-1">
						<?php dynamic_sidebar( 'sidebar-3-1' ); ?>
					</div>
				<?php
		}
		?>
		<?php
		if ( is_active_sidebar( 'sidebar-3-2' ) ) {
			?>
					<div class="widget-column footer-widget-2">
						<?php dynamic_sidebar( 'sidebar-3-2' ); ?>
					</div>
				<?php
		}
		?>
		</div>	
	</aside><!-- .widget-area -->

<?php endif; ?>
