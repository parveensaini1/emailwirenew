<?php

if(isset($this->data) && !empty($this->data)){
?>

<?php if(isset($this->data['Company']['fb_link'])&&!empty($this->data['Company']['fb_link'])){?>
	<div class="col-sm-5 socialsec fbbox">
        <div class="social_inner">
        <h4>Facebook</h4>
		<?php
		
			$facebookPageUrl=trim($this->data['Company']['fb_link']);
		?>
		<iframe src="https://www.facebook.com/plugins/page.php?href=<?php echo $facebookPageUrl;?>&tabs=timeline&width=250&height=500&small_header=false&adapt_container_width=true&hide_cover=true&show_facepile=true&appId=2215781951998619" width="250" height="500" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media"></iframe>
		
            </div>
	</div>
<?php } ?>
<?php if(isset($this->data['Company']['twitter_link'])&&!empty($this->data['Company']['twitter_link'])){?>
	<div class="col-sm-5 socialsec twitterbox">
        <div class="social_inner">
	        <h4>Twitter</h4>
			
			<?php		$twitterPageUrl=trim($this->data['Company']['twitter_link']);?>
			<a data-width="250" data-height="500" class="twitter-timeline" href="<?php echo $twitterPageUrl;?>"></a> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
			
		</div>
	</div>
<?php }
if(isset($this->data['Company']['pinterest'])&&!empty($this->data['Company']['pinterest'])){  ?>
	<div class="col-sm-5 socialsec pinterestbx">
        <div class="social_inner">
        <h4>Pinterest</h4>
		<?php 
		$pinterestUrl=trim($this->data['Company']['pinterest']); ?>
		<a data-pin-do="embedUser" data-pin-board-width="250" data-pin-scale-height="500" data-pin-scale-width="80" href="<?php echo $pinterestUrl;?>"></a>
		<script async defer data-pin-hover="true" data-pin-lang="en" src="//assets.pinterest.com/js/pinit.js"></script>
		
	</div>
        </div> 
<?php } ?> 
<?php if(isset($tumblrData)&&!empty($tumblrData)){?>
	<div class="col-sm-5 tumblrbx">
        <div class="social_inner">
        <h4>Tumblr</h4>
		<div class="tumblrheader">
			<h3 class="tumblrname"><a href="<?php echo $tumblrData['blog']['url'];?>"><?php echo $tumblrData['blog']['name'];?> <span class="showonhover">.tumblr.com</span> </a></h3>
			<div class="tumblravtar"><img src="<?php echo $tumblrData['blog']['avtar']; ?>"></div>
			<div class="subtitle"><?php echo (!empty($tumblrData['blog']['description']))?$tumblrData['blog']['description']:$tumblrData['blog']['title']; ?></div>
		</div>
		<div class="tumblrposts">
			<?php 
			if(!empty($tumblrData['posts'])){
				foreach ($tumblrData['posts'] as $key => $post) { 
				if($post['type']=='photo'){?>
					<a target="_blank" href="<?php echo $post['post_url'];?>"><img src="<?php echo $post['image_url'];?>"></a>
					<?php if(!empty($post['title'])){?><div class="tumblrcaption"><?php echo $post['title'];?></div><?php } ?>

				<?php }else if($post['type']=='video'){?>
					 <a target="_blank" href="<?php echo $post['post_url'];?>"><img src="<?php echo $post['thumbnail_url'];?>"></a>
					<?php if(!empty($post['title'])){?><div class="tumblrcaption"><?php echo $post['title'];?></div><?php } ?>
				<?php }else{?>	
					<?php if(!empty($post['title'])){?><div class="tumblrtitle"> <a target="_blank" href="<?php echo $post['post_url'];?>"> <?php echo strip_tags($post['title']);?></a> </div><?php } ?>
					<?php  if(!empty($post['excerpt'])){?>
						<p><?php echo strip_tags($post['excerpt']);?></p>
					<?php } ?>
				<?php } ?>

					
				<?php }
			}	
			?>
		</div>
            </div>
	</div>
<?php } 
/*
if(!empty($this->data['Company']['instagram'])){ ?>

<div class="col-sm-5 socialsec instagrambx">
    <div class="social_inner">
    <h4>Instagram</h4>
    <div id="instagram_feed" class="instagram_feed"></div>

		<?php
		$username = $this->Post->getInstagramUsername($this->data['Company']['instagram']);
		echo $username;die;
		?>
	</div>
	<script type="text/javascript" src="https://www.jqueryscript.net/demo/Instagram-Photos-Without-API-instagramFeed/jquery.instagramFeed.js"></script>
	<script>
	    (function($){
	        $(window).on('load', function(){
	        	var username = '<?php echo $username; ?>';
	            $.instagramFeed({
	                'username': username,
	                'container': "#instagram_feed",
	                'display_profile': false,
	                'display_biography': false,
	                'display_gallery': true,
	                'callback': null,
	                'styling': true,
	                'items': 8,
	                'items_per_row': 2,
	                'margin': 1
	            });
	        });
	    })(jQuery);

	    setTimeout(function(){
		    jQuery('body#instagram_feed a').attr('href','');
		}, 3000);
	</script>
</div>
<?php } */ ?>


<?php
}else{ 
if(isset($data['Company']['fb_link'])&&!empty($data['Company']['fb_link'])){?>
	<div class="col-sm-6 socialsec fbbox">
        <div class="social_inner">
        <h4>Facebook</h4>
		<?php 
			$facebookPageUrl=trim($data['Company']['fb_link']);
		?>
		<iframe src="https://www.facebook.com/plugins/page.php?href=<?php echo $facebookPageUrl;?>&tabs=timeline&width=465&height=500&small_header=false&adapt_container_width=true&hide_cover=true&show_facepile=true&appId=2215781951998619" width="100%" height="500" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media"></iframe>
		
            </div>
	</div>
<?php } ?>
<?php if(isset($data['Company']['twitter_link'])&&!empty($data['Company']['twitter_link'])){?>
	<div class="col-sm-6 socialsec twitterbox">
        <div class="social_inner">
	        <h4>Twitter</h4>
			
			<?php $twitterPageUrl=trim($data['Company']['twitter_link']);?>
			<a data-width="100%" data-height="500" class="twitter-timeline" href="<?php echo $twitterPageUrl;?>"></a> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
			
		</div>
	</div>
<?php }
if(isset($data['Company']['pinterest'])&&!empty($data['Company']['pinterest'])){  ?>
	<div class="col-sm-6 socialsec pinterestbx">
        <div class="social_inner">
        <h4>Pinterest</h4>
		<?php 
		$pinterestUrl=trim($data['Company']['pinterest']); ?>
		<a data-pin-do="embedUser" data-pin-board-width="20px" data-pin-scale-height="500" data-pin-scale-width="380" href="<?php echo $pinterestUrl;?>"></a>
		<script async defer data-pin-hover="true" data-pin-lang="en" src="//assets.pinterest.com/js/pinit.js"></script>
		
	</div>
        </div> 
		<style>
			#pinterest-container > span {
			width: 100% !important;
			overflow: hidden;
		}

		#pinterest-container > span > span > span > span {
			min-width: 0;
		}
		</style>
<?php } ?> 
<?php if(isset($tumblrData)&&!empty($tumblrData)){?>
	<div class="col-sm-6 tumblrbx">
        <div class="social_inner">
        <h4>Tumblr</h4>
		<div class="tumblrheader">
			<h3 class="tumblrname"><a href="<?php echo $tumblrData['blog']['url'];?>"><?php echo $tumblrData['blog']['name'];?> <span class="showonhover">.tumblr.com</span> </a></h3>
			<div class="tumblravtar"><img src="<?php echo $tumblrData['blog']['avtar']; ?>"></div>
			<div class="subtitle"><?php echo (!empty($tumblrData['blog']['description']))?$tumblrData['blog']['description']:$tumblrData['blog']['title']; ?></div>
		</div>
		<div class="tumblrposts">
			<?php 
			if(!empty($tumblrData['posts'])){
				foreach ($tumblrData['posts'] as $key => $post) { 
				if($post['type']=='photo'){?>
					<a target="_blank" href="<?php echo $post['post_url'];?>"><img src="<?php echo $post['image_url'];?>"></a>
					<?php if(!empty($post['title'])){?><div class="tumblrcaption"><?php echo $post['title'];?></div><?php } ?>

				<?php }else if($post['type']=='video'){?>
					 <a target="_blank" href="<?php echo $post['post_url'];?>"><img src="<?php echo $post['thumbnail_url'];?>"></a>
					<?php if(!empty($post['title'])){?><div class="tumblrcaption"><?php echo $post['title'];?></div><?php } ?>
				<?php }else{?>	
					<?php if(!empty($post['title'])){?><div class="tumblrtitle"> <a target="_blank" href="<?php echo $post['post_url'];?>"> <?php echo strip_tags($post['title']);?></a> </div><?php } ?>
					<?php  if(!empty($post['excerpt'])){?>
						<p><?php echo strip_tags($post['excerpt']);?></p>
					<?php } ?>
				<?php } ?>

					
				<?php }
			}	
			?>
		</div>
            </div>
	</div>
<?php } 

/*
if(!empty($data['Company']['instagram'])){ ?>

<div class="col-sm-5 socialsec instagrambx">
    <div class="social_inner">
    <h4>Instagram</h4>
    <div id="instagram_feed" class="instagram_feed"></div>

		<?php
		$username = $this->Post->getInstagramUsername($data['Company']['instagram']);
		?>
	</div>
	<script type="text/javascript" src="https://www.jqueryscript.net/demo/Instagram-Photos-Without-API-instagramFeed/jquery.instagramFeed.js"></script>
	<script>
	    (function($){
	        $(window).on('load', function(){
	        	var username = '<?php echo $username; ?>';
	            $.instagramFeed({
	                'username': username,
	                'container': "#instagram_feed",
	                'display_profile': false,
	                'display_biography': false,
	                'display_gallery': true,
	                'callback': null,
	                'styling': true,
	                'items': 8,
	                'items_per_row': 2,
	                'margin': 1
	            });
	        });
	    })(jQuery);

	    setTimeout(function(){
		    jQuery('body#instagram_feed a').attr('href','');
		}, 3000);
	</script>
</div>
<?php }*/

} ?>

