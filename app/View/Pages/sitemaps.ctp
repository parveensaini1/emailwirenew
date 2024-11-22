<?php App::uses('CakeTime', 'Utility'); 

?>
<div class="sitemap-custom sitemap-custom-pages">
    <h2><i class="fa fa-sitemap"></i> Sitemap Pages</h2>
  <ul>
    <li><a href="<?php echo SITEURL; ?>" title="">Home</a></li> 
    <li><a href="<?php echo SITEURL.'latest-news'; ?>" title="">Latest news</a></li>
    <li><a href="<?php echo SITEURL.'country'; ?>" title="">News by country</a></li> 
    <li><a href="<?php echo SITEURL.'msa'; ?>" title="">News by msa</a></li>
    <li><a href="<?php echo SITEURL.'category'; ?>" title="">News by category</a></li> 
    <li><a href="<?php echo SITEURL.'company'; ?>" title="">News by company</a></li> 
    <li><a href="<?php echo SITEURL.'news-feeds-by-categories'; ?>" title="">Newsfeed by Categories</a></li> 
    <li><a href="<?php echo SITEURL.'news-feeds-by-newsroom'; ?>" title="">Newsfeed by Newsroom</a></li> 
    <li><a href="<?php echo SITEURL.'news-feeds-by-msa'; ?>" title="">Newsfeed by MAS</a></li> 
    <li><a href="<?php echo SITEURL.'news-feeds-by-countries'; ?>" title="">Newsfeed by country</a></li> 
    <?php foreach ($pages as $page_slug => $name){
     $SITEURL=SITEURL.$page_slug;
     ?> 
        <li><a href="<?php echo $SITEURL; ?>" ><?php echo $name; ?></a> </li>
    <?php } ?>
    <?php 
    if(!empty($plancategory)){
      foreach ($plancategory as $pcatslug => $pcategory) {
        $catSITEURL=SITEURL.'plans/'.$pcatslug;
      ?> 
            <li><a href="<?php echo $catSITEURL; ?>" title=""><?php echo $pcategory; ?></a></li>  

<?php  }
    }
?> 
	</ul>  
</div>
<div class="sitemap-custom sitemap-custom-pressrelease">
    <h2><i class="fa fa-newspaper-o"></i> Press Releases</h2>
    <?php foreach ($data_array as $slug => $data){  
 $SITEURL=SITEURL.'release/'.$data['PressRelease']['slug'];
 ?>
<ul>
    <li><a href="<?php echo $SITEURL; ?>" title=""><?php echo $data['PressRelease']['title'];?></a> </li>  
</ul>
<?php } ?>
<?php 
        $paginatorInformation = $this->Paginator->params();
    if($paginatorInformation['pageCount']>1){ ?>
        <div class="row sitemap-pagination" id="paging">
            <?php echo $this->element('pagination'); ?>
        </div>
    <?php } ?>
</div>
 

<!-- <ul>
    <li><?php echo SITEURL.'company'; ?></li>
    <lastmod><?php echo $this->Time->toAtom(date("Y-m-d h:i:s")); ?></lastmod>
    <changefreq>daily</changefreq>
</ul> -->
    

 






 