 <ul class="newsroomsocial">
 	<?php /*if(!empty($data['Company']['web_site'])){ ?>
    <li class="ew-web"><a id="prev-web" target="_blank" href="<?php echo $data['Company']['web_site']; ?>"></a></li>
	<?php } */?>
    <?php if(!empty($data['Company']['blog_url'])){ ?>
    <li class="ew-blog"><a id="prev-blog" target="_blank" href="<?php echo $data['Company']['blog_url']; ?>"></a></li>
    <?php } ?>
    <?php if(!empty($data['Company']['fb_link'])){ ?>
    <li class="ew-facebook"><a id="prev-facebook" target="_blank" href="<?php echo $data['Company']['fb_link']; ?>"></a></li>
    <?php } ?>
    <?php if(!empty($data['Company']['twitter_link'])){ ?>
    <li class="ew-twitter"><a id="prev-twitter" target="_blank" href="<?php echo $data['Company']['twitter_link']; ?>"></a></li>
    <?php } ?>
    <?php if(!empty($data['Company']['linkedin'])){ ?>
    <li class="ew-linkedin"><a id="prev-linkedin" target="_blank" href="<?php echo $data['Company']['linkedin']; ?>"></a></li>
    <?php } ?>
    <?php if(!empty($data['Company']['instagram'])){ ?>
    <li class="ew-instagram"><a id="prev-instagram" target="_blank" href="<?php echo $data['Company']['instagram']; ?>"></a></li>
    <?php } ?>
    <?php if(!empty($data['Company']['pinterest'])){ ?>
    <li class="ew-pintrest"><a id="prev-pintrest" target="_blank" href="<?php echo $data['Company']['pinterest']; ?>"></a></li>
    <?php } ?>
    <?php if(!empty($data['Company']['tumblr'])){ ?>
    <li class="ew-tumbler"><a id="prev-tumbler" target="_blank" href="<?php echo $data['Company']['tumblr']; ?>"></a></li>  
    <?php } ?> 
</ul>