<?php
if(isset($this->data) && !empty($this->data)){?>
	<div class="docfiles assetbox">
	    <div class="ew-title full">Other documents</div>
	    <?php
	    foreach ($nr_docfiles as $docfiles_key => $docfiles_value) {
	    	$path_info = pathinfo($docfiles_value['name']);
			if($this->Custom->docType($docfiles_value['name']) == 'image'){
				$doc_image .= '<li class="col-md-3 margin-bottom20 text-center"><a class="lightitem" data-sub-html="'.$docfiles_value['name'].'" data-thumb="'.SITEURL.'files/company/docfile/'.$docfiles_value['file_path'].'/'.$docfiles_value['name'].'" data-src="'.SITEURL.'files/company/docfile/'.$docfiles_value['file_path'].'/'.$docfiles_value['name'].'"><img width="100%" title="" alt="" src="'.SITEURL.'files/company/docfile/'.$docfiles_value['file_path'].'/'.$docfiles_value['name'].'"></a></li>';
			}elseif($this->Custom->docType($docfiles_value['name']) == 'video'){
				$doc_video .= '<li class="col-md-3 margin-bottom20 text-center"><a target="_blank" style="display:inline-block; padding: 0px 10px; text-align:center;" href="'.$this->Post->get_companyfile($docfiles_value['file_path'],$docfiles_value['name']).'">'.$this->Custom->docIcon($docfiles_value['name']).'<p>'.$path_info['filename'].'</p></a></li>';
			}else{
				$doc_files .= '<li class="col-md-3 margin-bottom20 text-center"><a target="_blank" style="display:inline-block; padding: 0px 10px; text-align:center;" href="'.$this->Post->get_companyfile($docfiles_value['file_path'],$docfiles_value['name']).'">'.$this->Custom->docIcon($docfiles_value['name']).'<p>'.$path_info['filename'].'</p></a></li>';
			}
	    }
	    	if($doc_image){
				echo '<ul class="row">'.$doc_image.'</ul>';
	    	}
	    	if($doc_video){
				echo '<ul class="row">'.$doc_video.'</ul>';
	    	}
	    	if($doc_files){
				echo '<ul class="row">'.$doc_files.'</ul>';
	    	}
	    ?>
	</div>
	<style>
		ul{
			list-style: none;
		}
	</style>
<?php }else{ ?>

<div id="newsroommedia" class="col-sm-12"> 
    <div class="image-assets assetbox">
<?php   
$showTitle="0";
$yurlShowTitle="0";
if(isset($media_array)&&!empty($media_array)){
	$showTitle1="0";	
	if($showTitle1==0){
		 echo '<div class="ew-title full">Images</div>';
  	}
  	$imageBlankArr=[];
	echo "<ul class='row'>";
	foreach ($media_array as $key => $mediadata) {
	 if(!in_array($mediadata['PI']['image_name'],$imageBlankArr)){	
		 $imageBlankArr[]=$mediadata['PI']['image_name'];
		 $showTitle1++;
		  $imgurl=SITEURL.'files/company/press_image/'.$mediadata['PI']['image_path'].'/'.$mediadata['PI']['image_name'];   
		   $thumburl=  $this->Post->getResizedImage($mediadata['PI']['image_name'],$thumbWidth,$thumbHeight);
		  $dsc=(!empty($mediadata['PI']['describe_image']))?$mediadata['PI']['describe_image']:"";
		  $img='<img  width="100%" title="'.$mediadata['PI']['image_text'].'" alt="'.$mediadata['PI']['image_text'].'"  src="'.$thumburl.'">';
		  if(isset($mediadata['PI']['image_path'])&&!empty($mediadata['PI']['image_path'])){
		  	echo "<li class='col-md-3 margin-bottom20 text-center'><a class='lightitem' data-sub-html='$dsc' data-thumb='$thumburl' data-src='$imgurl'>$img</a></li>";
		  }
	  }	   

   	  if($mediadata['PP']['purl']){
   	  	$showTitle=1;
   	  }

   	  if($mediadata['PY']['yurl']){
   	  	$yurlShowTitle=1;
   	  }
	}
	echo "</ul>";

} ?> 
</div>
    
<div class="video-assets assetbox">
<?php 

	if($yurlShowTitle=='1'){
  		 echo '<div class="ew-title full">Videos</div>';
  			 
  	}
  	echo '<ul class="row">';
  	$yblankArr=[];
  	foreach ($media_array as $index => $mediadata) {
 		if(!empty($mediadata['PY']['yurl'])){
		  $videoUrl=$mediadata['PY']['yurl'];
		  $description=(!empty($mediadata['PY']['ydesc']))?$mediadata['PY']['ydesc']:"";
		  $youTubeId=$this->Post->getYouTubeId($videoUrl);
		  if(!in_array($youTubeId,$yblankArr)){
		  	$yblankArr[]=$youTubeId;
		  echo '<li class="col-md-3 margin-bottom20 text-center"><a class="lightitem" data-sub-html="'.$description.'" data-thumb="https://i.ytimg.com/vi/'.$youTubeId.'/hqdefault.jpg" href="'.$videoUrl.'" ><img src="https://i.ytimg.com/vi/'.$youTubeId.'/hqdefault.jpg" >  </a></li>';
		  }
		  }
	  } 
	  
	
echo '</ul>';

	?>
</div>

<div class="podcast-assets assetbox">
<?php 

	if($showTitle==1){ echo '<div class="ew-title full">Poadcasts</div>'; }

  	$podblankArr=[];
  	echo '<ul class="row">';	
	foreach ($media_array as $index => $mediadata) {
	if($mediadata['PP']['purl']){
		if(!in_array($mediadata['PP']['ppid'],$podblankArr)){
		  $podblankArr[]=$mediadata['PP']['ppid'];
		  echo "<li class='col-md-3 margin-bottom20 text-center' >";
		  $videoUrl=$mediadata['PP']['purl'];
		  $description=(!empty($mediadata['PP']['pdesc']))?$mediadata['PP']['pdesc']:""; 
		  echo $videoUrl;
		  echo "</li>";  
		}
	  }
	  
	}
echo '</ul>';

	?>
</div>
<?php 
if(isset($doc_data) && !empty($doc_data)){
?>
<div class="docfiles assetbox">
    <div class="ew-title full">Other documents</div>
    <?php
    foreach ($doc_data as $key => $value) {
    	$path_info = pathinfo($value['CompanyDocument']['file_name']);
		if($this->Custom->docType($value['CompanyDocument']['file_name']) == 'image'){
			$doc_image .= '<li class="col-md-3 margin-bottom20 text-center"><a class="lightitem" data-sub-html="'.$value['CompanyDocument']['file_name'].'" data-thumb="'.SITEURL.'files/company/docfile/'.$value['CompanyDocument']['file_path'].'/'.$value['CompanyDocument']['file_name'].'" data-src="'.SITEURL.'files/company/docfile/'.$value['CompanyDocument']['file_path'].'/'.$value['CompanyDocument']['file_name'].'"><img width="100%" title="" alt="" src="'.SITEURL.'files/company/docfile/'.$value['CompanyDocument']['file_path'].'/'.$value['CompanyDocument']['file_name'].'"></a></li>';
		}elseif($this->Custom->docType($value['CompanyDocument']['file_name']) == 'video'){
			$doc_video .= '<li class="col-md-3 margin-bottom20 text-center"><a target="_blank" style="display:inline-block; padding: 0px 10px; text-align:center;" href="'.$this->Post->get_companyfile($value['CompanyDocument']['file_path'],$value['CompanyDocument']['file_name']).'">'.$this->Custom->docIcon($value['CompanyDocument']['file_name']).'<p>'.$path_info['filename'].'</p></a></li>';
		}else{
			$doc_files .= '<li class="col-md-3 margin-bottom20 text-center"><a target="_blank" style="display:inline-block; padding: 0px 10px; text-align:center;" href="'.$this->Post->get_companyfile($value['CompanyDocument']['file_path'],$value['CompanyDocument']['file_name']).'">'.$this->Custom->docIcon($value['CompanyDocument']['file_name']).'<p>'.$path_info['filename'].'</p></a></li>';
		}
    }
    	if($doc_image){
			echo '<ul class="row">'.$doc_image.'</ul>';
    	}
    	if($doc_video){
			echo '<ul class="row">'.$doc_video.'</ul>';
    	}
    	if($doc_files){
			echo '<ul class="row">'.$doc_files.'</ul>';
    	}
    ?>
</div>
<?php } ?>

</div>


 <?php 
echo $this->Html->css(array('/plugins/lightslider/css/lightgallery'));
echo $this->Html->script(array('/plugins/lightslider/js/lightgallery'));
?>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/lg-share/1.1.0/lg-share.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/lg-zoom/1.1.0/lg-zoom.min.js" ></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/lg-video/1.2.2/lg-video.min.js"></script>
<script type="text/javascript">
    $('#newsroommedia').lightGallery({
    selector: '#newsroommedia .lightitem',
    animateThumb: false,
    showThumbByDefault: false
});
</script>

<?php } ?>