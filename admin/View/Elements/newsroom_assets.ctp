<div id="newsroommedia" class="col-sm-12">
		<div class="image-assets assetbox">
			<?php
			$showTitle = "0";
			$yurlShowTitle = "0";
			if (isset($media_array) && !empty($media_array)) {
				$showTitle1 = "0";
				if ($showTitle1 == 0) {
					echo '<label class="ew-title full">Images</label>';
				}
				$imageBlankArr = [];
				echo "<ul class='row'>";
				foreach ($media_array as $key => $mediadata) {
					if (!in_array($mediadata['PI']['image_name'], $imageBlankArr)) {
						$imageBlankArr[] = $mediadata['PI']['image_name'];
						$showTitle1++;
						$imgurl = SITEFRONTURL . 'files/company/press_image/' . $mediadata['PI']['image_path'] . '/' . $mediadata['PI']['image_name'];
						$thumburl =  $this->Post->getResizedImage($mediadata['PI']['image_name'], $thumbWidth, $thumbHeight);
						$dsc = (!empty($mediadata['PI']['describe_image'])) ? $mediadata['PI']['describe_image'] : "";
						$img = '<img  width="100%" title="' . $mediadata['PI']['image_text'] . '" alt="' . $mediadata['PI']['image_text'] . '"  src="' . $thumburl . '">';
						if (isset($mediadata['PI']['image_path']) && !empty($mediadata['PI']['image_path'])) {
							echo "<li class='col-sm-3'><a class='lightitem' data-sub-html='$dsc' data-thumb='$thumburl' data-src='$imgurl'>$img</a></li>";
						}
					}

					if ($mediadata['PP']['purl']) {
						$showTitle = 1;
					}

					if ($mediadata['PY']['yurl']) {
						$yurlShowTitle = 1;
					}
				}
				echo "</ul>";
			} ?>
		</div>

		<div class="video-assets assetbox">
			<?php

			if ($yurlShowTitle == '1') {
				echo '<label class="ew-title full">Videos</label>';
			}
			echo '<ul class="row">';
			$yblankArr = [];
			foreach ($media_array as $index => $mediadata) {
				if (!empty($mediadata['PY']['yurl'])) {
					$videoUrl = $mediadata['PY']['yurl'];
					$description = (!empty($mediadata['PY']['ydesc'])) ? $mediadata['PY']['ydesc'] : "";
					$youTubeId = $this->Post->getYouTubeId($videoUrl);
					if (!in_array($youTubeId, $yblankArr)) {
						$yblankArr[] = $youTubeId;
						echo '<li class="col-sm-3"><a class="lightitem" data-sub-html="' . $description . '" data-thumb="https://i.ytimg.com/vi/' . $youTubeId . '/hqdefault.jpg" href="' . $videoUrl . '" ><img src="https://i.ytimg.com/vi/' . $youTubeId . '/hqdefault.jpg" >  </a></li>';
					}
				}
			}


			echo '</ul>';

			?>
		</div> 
		<?php 

if (!empty($presentationData['CompanyPresentation'])) { 
	$presentationHtml =""; ?>
	<div class="Company assetbox"> <label class="ew-title full">Presentation</label>
		<?php
				foreach ($presentationData['CompanyPresentation'] as $key => $presentation) {
					if(!empty($presentation['url'])){
						$presentationHtml .= '<li class="col-sm-3">'.$presentation['url'].'</li>';
					}
				}
			if ($presentationHtml) {
				echo '<ul class="row mt-2">' . $presentationHtml . '</ul>';
			} 
		
		?>
	</div> 
<?php } ?>

<?php 
if (!empty($ebookData['CompanyEbook'])) { 
	$ebooktml =""; ?>
	<div class="Company assetbox">
		<label class="ew-title full">E-books</label>
		<?php
		foreach ($ebookData['CompanyEbook'] as $key => $ebook) {
			if(!empty($ebook['embedded'])){
				$ebooktml .= '<li class="col-sm-3">'.$ebook['embedded'].'</li>';
			}
		}
			if ($ebooktml) {
				echo '<ul class="row mt-2">' . $ebooktml . '</ul>';
			} 
		
		?>
	</div> 
<?php } 

if (!empty($podData['CompanyPodcast'])) { 
	$podtml =""; ?>
	<div class="Company assetbox">
		<label class="ew-title full">Podcast</label>
		<?php
		foreach ($podData['CompanyPodcast'] as $key => $pod) {
			if(!empty($pod['embedded'])){
				$podtml .= '<li class="col-sm-3">'.$pod['embedded'].'</li>';
			}
		}
			if ($podtml) {
				echo '<ul class="row mt-2">' . $podtml . '</ul>';
			} 
		
		?>
	</div> 
<?php } 


if (!empty($this->data['CompanyDocument'])) { ?>
	<div class="docfiles assetbox">
		<label class="ew-title full">Other documents</label>
		<?php
		foreach ($this->data['CompanyDocument'] as $docfiles_key => $docfiles_value) {
			if(!empty($docfiles_value['file_name'])){
				$path_info = pathinfo($docfiles_value['file_name']);
				if(!empty($docfiles_value['file_name'])){
					$path_info = pathinfo($docfiles_value['file_name']);
					if ($this->Custom->docType($docfiles_value['file_name']) == 'image') {
						$doc_image .= '<li class="col-sm-3"><a class="lightitem" data-sub-html="' . $docfiles_value['doc_caption'] . '" data-thumb="' . SITEFRONTURL . 'files/company/docfile/' . $docfiles_value['file_path'] . '/' . $docfiles_value['file_name'] . '" data-src="' . SITEFRONTURL . 'files/company/docfile/' . $docfiles_value['file_path'] . '/' . $docfiles_value['file_name'] . '"><img width="100%" title="" alt="" src="' . SITEFRONTURL . 'files/company/docfile/' . $docfiles_value['file_path'] . '/' . $docfiles_value['file_name']. '"></a><p class="text-center mt-1"><strong>'.$docfiles_value['doc_caption'].'</strong></p></li>';
					} elseif ($this->Custom->docType($docfiles_value['file_name']) == 'video') {
						$doc_video .= '<li class="col-sm-3"><a target="_blank" style="display:inline-block; padding: 0px 10px; text-align:center;" href="' . $this->Post->get_companyfile($docfiles_value['file_path'], $docfiles_value['file_name']) . '">' . $this->Custom->docIcon($docfiles_value['file_name']) . '<p>' . $path_info['filename'] . '</p></a><p class="text-center mt-1"><strong>'.$docfiles_value['doc_caption'].'</strong></p></li>';
					} else {
						$doc_files .= '<li class="col-sm-3"><a target="_blank" style="display:inline-block; padding: 0px 10px; text-align:center;" href="' . $this->Post->get_companyfile($docfiles_value['file_path'], $docfiles_value['file_name']) . '">' . $this->Custom->docIcon($docfiles_value['file_name']) . '<p>' . $path_info['filename'] . '</p></a><p class="text-center mt-1"><strong>'.$docfiles_value['doc_caption'].'</strong></p></li>';
					}
				}
			}
		}
			if ($doc_image) {
				echo '<ul class="row mt-2">' . $doc_image . '</ul>';
			}
			if ($doc_video) {
				echo '<ul class="row mt-2">' . $doc_video . '</ul>';
			}
			if ($doc_files) {
				echo '<ul class="row mt-2">' . $doc_files . '</ul>';
			}
		
		?>
	</div>

<?php } 
?>
	</div>


	<?php
	echo $this->Html->css(array('/plugins/lightslider/css/lightgallery'));
	echo $this->Html->script(array('/plugins/lightslider/js/lightgallery'));
	?>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/lg-share/1.1.0/lg-share.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/lg-zoom/1.1.0/lg-zoom.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/lg-video/1.2.2/lg-video.min.js"></script>
	<script type="text/javascript">
		$('#newsroommedia').lightGallery({
			selector: '#newsroommedia .lightitem',
			animateThumb: false,
			showThumbByDefault: false
		});
	</script>
<?php if (isset($media_array) && !empty($media_array)) {
	echo $this->element('newsroom_form_custom_pagination'); 
}	
?>

<style>
		ul {
			list-style: none;
		}
	</style>