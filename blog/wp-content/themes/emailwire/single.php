<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since 1.0.0
 */

get_header();
?>

	<section id="primary" class="content-webarea content-blogarea">
		<main id="main" class="website-main">
			<div class="container">
				<div class="row">
				<div class="col-md-8">
				
			<?php

			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				get_template_part( 'template-parts/content/content', 'single' );

				if ( is_singular( 'attachment' ) ) {
					// Parent post navigation.
					the_post_navigation(
						array(
							/* translators: %s: parent post link */
							'prev_text' => sprintf( __( '<span class="meta-nav">Published in</span><span class="post-title">%s</span>', 'twentynineteen' ), '%title' ),
						)
					);
				} elseif ( is_singular( 'post' ) ) {
					// Previous/next post navigation.
					the_post_navigation(
						array(
							'next_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Next Post', 'twentynineteen' ) . '</span> ' .
								'<span class="screen-reader-text">' . __( 'Next post:', 'twentynineteen' ) . '</span> <br/>' .
								'<span class="post-title">%title</span>',
							'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Previous Post', 'twentynineteen' ) . '</span> ' .
								'<span class="screen-reader-text">' . __( 'Previous post:', 'twentynineteen' ) . '</span> <br/>' .
								'<span class="post-title">%title</span>',
						)
					);
				}

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) {
					//comments_template();
				}

			endwhile; // End of the loop.
			?>
					
					
					<section class="first-section default-section">
		<div class="row">
			<?php 
            $args = array ('posts_per_page' => 3 );
            //$category_base_name = get_the_category_by_id(144);
            $myposts = get_posts( $args );
            foreach( $myposts as $index =>$post ) :	setup_postdata($post);
            $src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), false, '' );
			
            if(empty($src)){
              $src[0]=site_url().'/wp-content/uploads/2019/06/no-image.jpg';
            }
					  $img = thumb( $src[0], 295, 180 );
			
            ?>
			<div class="col-md-4">
				<div class="post-article-one post-article-box">
					<div class="article-img">
						<img src="<?php echo $img['url']; ?>" alt="<?php the_title();?>">
					</div>
					<div class="article-text-box">
						<div class="article-title">
							<a href="<?php the_permalink(); ?>">
								<?php the_title();?>
							</a>
						</div>
						<div class="article-meta">
							<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ); ?>">
								<?php the_author(); ?>
							</a> - 
							<?php the_time('F j, Y') ?>
						</div>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
</section>
					
					
				</div>	
					
				<div class="col-md-4">
					<?php dynamic_sidebar( 'sidebar-5' ); ?>	
				</div>
				</div>
			</div>	
		</main><!-- #main -->
	</section><!-- #primary -->

<?php
get_footer();