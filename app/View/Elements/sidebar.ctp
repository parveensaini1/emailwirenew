 <?php 
$ads= $this->Post->getads();
if(!empty($ads)){

foreach ($ads as $advertise){
    $googleAddClass=($advertise['Advertisement']['is_google_ads'])?"google_ads":"";
    echo "<section class='custom-ad ad-".$advertise['Advertisement']['id']." $googleAddClass'>";
    if(!$advertise['Advertisement']['is_google_ads']){

?>
    <?php if(!empty($advertise['Advertisement']['title'])){?><h3 class="ad-title"><?php echo $advertise['Advertisement']['title']; ?></h3><?php  } ?>
    <?php if(!empty($advertise['Advertisement']['image'])){?>
        <?php
        $imageUrl=SITEURL.'files/ads/'.$advertise['Advertisement']['image'];
        echo $this->Html->image($imageUrl, array('class' =>"ad-image","width"=>"100%","alt"=>$advertise['Advertisement']['title'])); ?>
    <?php  } ?>
    <?php if(!empty($advertise['Advertisement']['description'])){?><?php echo $advertise['Advertisement']['description']; ?><?php }?>
    <?php if(!empty($advertise['Advertisement']['url'])){?>
        <a class="orange-btn" href="<?php echo $advertise['Advertisement']['url']; ?>"><?php echo (!empty($advertise['Advertisement']['button_label']))?$advertise['Advertisement']['button_label']:"Learn more" ?></a>
    <?php  } ?>

<?php 
    }else{ ?>
      <?php if(!empty($advertise['Advertisement']['google_ads'])){?><?php echo $advertise['Advertisement']['google_ads']; ?><?php }?>  
<?php }
echo "</section>";
 } 
}?>
