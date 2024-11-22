<?php

/**

 * Template Name: Home

 */

get_header();

?>

<div class="first-section default-section">
	<div class="container">
		<div class="row">

			<?php 
			// get_search_form();
            $args = array ('posts_per_page' => 4, 'cat' => 24 );
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
</div>

<div class="second-section default-section">
	<div class="container">
		<div class="row">
			
			<?php 
            $args = array ('posts_per_page' => 1, 'offset' => -4, 'cat' => 24 );
            //$category_base_name = get_the_category_by_id(144);
            $myposts = get_posts( $args );
            foreach( $myposts as $index =>$post ) :	setup_postdata($post);
            $src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), false, '' );
            if(empty($src)){
              $src[0]=site_url().'/wp-content/uploads/2019/06/no-image.jpg';
            }
					  $img = thumb( $src[0], 620, 378 );
            ?>
			<div class="col-sm-6">
				<div class="post-article-two post-article-box">
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
			<div class="col-sm-6">
			<?php 
            $args = array ('posts_per_page' => 2, 'offset' => -5, 'cat' => 24 );
            //$category_base_name = get_the_category_by_id(144);
            $myposts = get_posts( $args );
            foreach( $myposts as $index =>$post ) :	setup_postdata($post);
            $src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), false, '' );
            if(empty($src)){
              $src[0]=site_url().'/wp-content/uploads/2019/06/no-image.jpg';
            }
					  $img = thumb( $src[0], 620, 174 );
            ?>
			
				<div class="post-article-two post-article-two-half post-article-box">
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
			
			<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>			

<div class="content-ad">
	<div class="container">
		<?php dynamic_sidebar( 'sidebar-2' ); ?>
	</div>	
</div>

<div class="third-section default-section">
	<div class="container">
		<div class="row">
			
			<div class="col-md-12">
				<h4><?php echo get_the_category_by_ID(14) ?></h4>
				<a class="category-link" href="<?php echo get_category_link(14) ?>">View All</a>			
			</div>	
			
			<?php 
            $args = array ('posts_per_page' => 2, 'cat' => 14 );
            //$category_base_name = get_the_category_by_id(144);
            $myposts = get_posts( $args );
            foreach( $myposts as $index =>$post ) :	setup_postdata($post);
            $src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), false, '' );
            if(empty($src)){
              $src[0]=site_url().'/wp-content/uploads/2019/06/no-image.jpg';
            }
					  $img = thumb( $src[0], 620, 378 );
            ?>
			<div class="col-sm-6">
				<div class="post-article-two post-article-box">
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
</div>

<div class="four-section default-section">
	<div class="container">
		<div class="row">
			
			<div class="col-md-12">
				<h4><?php echo get_the_category_by_ID(24) ?></h4>
				<a class="category-link" href="<?php echo get_category_link(24) ?>">View All</a>			
			</div>	
			
			<?php 
            $args = array ('posts_per_page' => 1, 'offset' => -8, 'cat' => 24 );
            //$category_base_name = get_the_category_by_id(144);
            $myposts = get_posts( $args );
            foreach( $myposts as $index =>$post ) :	setup_postdata($post);
            $src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), false, '' );
            if(empty($src)){
              $src[0]=site_url().'/wp-content/uploads/2019/06/no-image.jpg';
            }
					  $img = thumb( $src[0], 620, 378 );
            ?>
			<div class="col-sm-6">
				<div class="post-article-two post-article-box">
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
			<?php 
            $args = array ('posts_per_page' => 2, 'offset' => -1, 'cat' => 24 );
            //$category_base_name = get_the_category_by_id(144);
            $myposts = get_posts( $args );
            foreach( $myposts as $index =>$post ) :	setup_postdata($post);
            $src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), false, '' );
            if(empty($src)){
              $src[0]=site_url().'/wp-content/uploads/2019/06/no-image.jpg';
            }
					  $img = thumb( $src[0], 295, 378 );
            ?>
			<div class="col-sm-3">
				<div class="post-article-two post-article-box">
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
</div>	

<div class="five-section default-section">
	<div class="container">
		<div class="row">
			
			<div class="col-md-12">
				<h4><?php echo get_the_category_by_ID(13) ?></h4>
				<a class="category-link" href="<?php echo get_category_link(13) ?>">View All</a>			
			</div>	
			
			<?php 
            $args = array ('posts_per_page' => 3, 'cat' => 13 );
            //$category_base_name = get_the_category_by_id(144);
            $myposts = get_posts( $args );
            foreach( $myposts as $index =>$post ) :	setup_postdata($post);
            $src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), false, '' );
            if(empty($src)){
              $src[0]=site_url().'/wp-content/uploads/2019/06/no-image.jpg';
            }
					  $img = thumb( $src[0], 403, 378 );
            ?>
			<div class="col-sm-4">
				<div class="post-article-two post-article-box">
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
</div>	

<div class="six-section default-section">
	<div class="container">
		<div class="row">
			
			<div class="col-md-12">
				<h4><?php echo get_the_category_by_ID(7) ?></h4>
				<a class="category-link" href="<?php echo get_category_link(7) ?>">View All</a>			
			</div>	
			
			<?php 
            $args = array ('posts_per_page' => 2, 'cat' => 7 );
            //$category_base_name = get_the_category_by_id(144);
            $myposts = get_posts( $args );
            foreach( $myposts as $index =>$post ) :	setup_postdata($post);
            $src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), false, '' );
            if(empty($src)){
              $src[0]=site_url().'/wp-content/uploads/2019/06/no-image.jpg';
            }
					  $img = thumb( $src[0], 295, 378 );
            ?>
			<div class="col-sm-3">
				<div class="post-article-two post-article-box">
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
			<?php 
            $args = array ('posts_per_page' => 1, 'offset' => -2, 'cat' => 7 );
            //$category_base_name = get_the_category_by_id(144);
            $myposts = get_posts( $args );
            foreach( $myposts as $index =>$post ) :	setup_postdata($post);
            $src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), false, '' );
            if(empty($src)){
              $src[0]=site_url().'/wp-content/uploads/2019/06/no-image.jpg';
            }
					  $img = thumb( $src[0], 620, 378 );
            ?>
			<div class="col-sm-6">
				<div class="post-article-two post-article-box">
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
</div>

<div class="seven-section default-section">
	<div class="container">
		<div class="row">
			
			<div class="col-md-12">
				<h4><?php echo get_the_category_by_ID(24) ?></h4>
				<a class="category-link" href="<?php echo get_category_link(24) ?>">View All</a>			
			</div>	
			
			<?php 
            $args = array ('posts_per_page' => 4, 'offset' => -11, 'cat' => 24 );
            //$category_base_name = get_the_category_by_id(144);
            $myposts = get_posts( $args );
            foreach( $myposts as $index =>$post ) :	setup_postdata($post);
            $src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), false, '' );
            if(empty($src)){
              $src[0]=site_url().'/wp-content/uploads/2019/06/no-image.jpg';
            }
					  $img = thumb( $src[0], 295, 378 );
            ?>
			<div class="col-sm-3">
				<div class="post-article-two post-article-box">
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
</div>

<?php get_footer(); ?>