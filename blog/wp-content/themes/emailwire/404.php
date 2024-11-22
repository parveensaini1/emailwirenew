<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since 1.0.0
 */

get_header();
?>

	<section id="primary" class="content-webarea content-nopagearea">
		<main id="main" class="website-main">
			<div class="container">

			<div class="error-404 not-found">
				<header class="page-header text-center m-0 mt-5">
					<h1 class="page-title m-0 "><?php _e( 'Oops! That page can&rsquo;t be found.', 'twentynineteen' ); ?></h1>
				</header><!-- .page-header -->

				<div class="page-content text-center m-0 mt-5">
					<p><?php _e( 'are you sure this is what you looking for. Maybe try a research?', 'twentynineteen' ); ?></p>
					<?php //get_search_form(); ?>
				</div><!-- .page-content -->
			</div><!-- .error-404 -->
				
			</div>
		</main><!-- #main -->
	</section><!-- #primary -->



<section class="first-section default-section">
	<div class="container">
		<div class="row">
			<?php 
            $args = array ('posts_per_page' => 4 );
            //$category_base_name = get_the_category_by_id(144);
            $myposts = get_posts( $args );
            foreach( $myposts as $index =>$post ) :	setup_postdata($post);
            $src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), false, '' );
			
            if(empty($src)){
              $src[0]=site_url().'/wp-content/uploads/2019/06/no-image.jpg';
            }
					  $img = thumb( $src[0], 295, 180 );
			
            ?>
			<div class="col-md-3">
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
	</div>
</section>


<?php
get_footer();
