<?php App::uses('CakeTime', 'Utility'); 

?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<url>
    <loc><?php echo SITEURL; ?></loc>
    <changefreq>daily</changefreq>
</url>

<?php foreach ($data_array as $slug => $release_date){
 $siteurl=SITEURL.'release/'.$slug;
 ?>
<url>
    <loc><?php echo $siteurl; ?></loc>
    <lastmod><?php echo $this->Time->toAtom($release_date); ?></lastmod>
    <changefreq>weekly</changefreq>
</url>
<?php } ?>

<url>
    <loc><?php echo SITEURL.'company'; ?></loc>
    <lastmod><?php echo $this->Time->toAtom(date("Y-m-d h:i:s")); ?></lastmod>
    <changefreq>daily</changefreq>
</url>
<url>
    <loc><?php echo SITEURL.'latest-news'; ?></loc>
    <lastmod><?php echo $this->Time->toAtom(date("Y-m-d h:i:s")); ?></lastmod>
    <changefreq>daily</changefreq>
</url>
<url>
    <loc><?php echo SITEURL.'country'; ?></loc>
    <lastmod><?php echo $this->Time->toAtom(date("Y-m-d h:i:s")); ?></lastmod>
    <changefreq>daily</changefreq>
</url>
<url>
    <loc><?php echo SITEURL.'msa'; ?></loc>
    <lastmod><?php echo $this->Time->toAtom(date("Y-m-d h:i:s")); ?></lastmod>
    <changefreq>daily</changefreq>
</url>
<url>
    <loc><?php echo SITEURL.'category'; ?></loc>
    <lastmod><?php echo $this->Time->toAtom(date("Y-m-d h:i:s")); ?></lastmod>
    <changefreq>daily</changefreq>
</url>
<url>
    <loc><?php echo SITEURL.'news-feeds-by-categories'; ?></loc>
    <lastmod><?php echo $this->Time->toAtom(date("Y-m-d h:i:s")); ?></lastmod>
    <changefreq>daily</changefreq>
</url>


<url>
    <loc><?php echo SITEURL.'news-feeds-by-companies'; ?></loc>
    <lastmod><?php echo $this->Time->toAtom(date("Y-m-d h:i:s")); ?></lastmod>
    <changefreq>daily</changefreq>
</url>


<url>
    <loc><?php echo SITEURL.'news-feeds-by-msa'; ?></loc>
    <lastmod><?php echo $this->Time->toAtom(date("Y-m-d h:i:s")); ?></lastmod>
    <changefreq>daily</changefreq>
</url>

<url>
    <loc><?php echo SITEURL.'news-feeds-by-countries'; ?></loc>
    <lastmod><?php echo $this->Time->toAtom(date("Y-m-d h:i:s")); ?></lastmod>
    <changefreq>daily</changefreq>
</url>

<?php 
	if(!empty($plancategory)){
	  foreach ($plancategory as $pcatslug => $pcategory) {
	  	$catsiteurl=SITEURL.'plans/'.$pcatslug;
	  ?>
	  	<url>
		    <loc><?php echo $catsiteurl; ?></loc>
		    <lastmod><?php echo $this->Time->toAtom(date("Y-m-d h:i:s")); ?></lastmod>
		    <changefreq>yearly</changefreq>
		</url>

<?php  }
	}
?>


<?php foreach ($pages as $page_slug => $created){
 $siteurl=SITEURL.$page_slug;
 ?>
<url>
    <loc><?php echo $siteurl; ?></loc>
    <lastmod><?php echo $this->Time->toAtom($created); ?></lastmod>
    <changefreq>yearly</changefreq>
</url>
<?php } ?>


</urlset>