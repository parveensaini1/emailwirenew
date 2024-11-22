<?php 
if(!empty($data_array)){?> 
<div class="row">
        <div class="col-lg-12"><div class="ew-title full"><?php echo $data_array['Page']['title']?></div></div>
              </div>
<div class="container">
    <div class="webcontent">
        <?php echo html_entity_decode($data_array['Page']['description']);?>
    </div>
</div>
    <style>
        <?php
            $icons = $this->Post->getSocialShares();
            foreach ($icons as $key => $icon_value) {
              ?>
              .ew-<?php echo strtolower($icon_value['SocialShare']['title']); ?>{
                background: url(<?php echo SITEURL.'website/img/'.$icon_value['SocialShare']['icon_url']; ?>)  no-repeat left top !important;
              }
              <?php
            }
        ?>
    </style>
    <div class="ew-pr-social col-sm-12">
        <?php
            echo $this->Post->sharelinks($data_array['Page']['title'],SITEURL.$data_array['Page']['slug'],substr(strip_tags($data_array['Page']['description']),0,250).'...',SITEURL.'website/img/emailwire-logo.jpg');
        ?>
    </div>
<?php 
}else{ 
    echo "<h2 class='text-center'>Page Not Available</h2>";
}?>
 