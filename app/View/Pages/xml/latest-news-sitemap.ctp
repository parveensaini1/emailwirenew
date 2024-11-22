<?php App::uses('CakeTime', 'Utility'); 

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">
	<?php foreach ($data_array as $key => $value){
?>
	<url>
		<loc><?php echo $value['PressRelease']['slug']; ?></loc>
		<news:news>
		<news:publication>
		  <news:name><?php echo $this->Post->company_name($value['PressRelease']['company_id']); ?></news:name>
		  <?php if($value['PressRelease']['language']=="Arabic"){ ?>
		  <news:language>ar</news:language>
		   <?php } else {  ?>
		   <news:language>en</news:language>
		   <?php } ?>
		  
		</news:publication>
		<news:publication_date><?php echo date('Y-m-d', strtotime($value['PressRelease']['release_date'])); ?></news:publication_date>
		  <news:title><?php echo $value['PressRelease']['title']; ?></news:title>
		</news:news>
	</url>
	<?php } ?>
</urlset>