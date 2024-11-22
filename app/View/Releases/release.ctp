<?php
echo $this->Html->css(array('/plugins/lightslider/css/lightslider'));
echo $this->Html->script(array('/plugins/lightslider/js/lightslider'));
echo $this->Html->css(array('/plugins/lightslider/css/lightgallery'));
echo $this->Html->script(array('/plugins/lightslider/js/lightgallery'));
echo $this->Html->css(array('/plugins/owlcarousel/owl.carousel.min'));
echo $this->Html->script(array('/plugins/owlcarousel/owl.carousel.min'));
?>
<style type="text/css">
	#PressReleaseform section:not(:first-of-type) {
		display: none;
	}

	#PressReleaseform .action-button {
		width: 100px;
		background: #27AE60;
		font-weight: bold;
		color: white;
		border: 0 none;
		border-radius: 1px;
		cursor: pointer;
		padding: 10px 5px;
		margin: 10px 5px;
	}

	#PressReleaseform .action-button:hover,
	#PressReleaseform .action-button:focus {
		box-shadow: 0 0 0 2px white, 0 0 0 3px #27AE60;
	}
</style>

<?php

// echo urlencode('hitesh@netleon.com');
if (isset($this->request->query['e']) && !empty($this->request->query['e']) && isset($this->request->query['usr']) && $this->request->query['usr'] == 'subscriber') {
	$is_subscriber = false;
	$splitEmail = explode("@", trim($this->request->query['e']));
	$hostname = $splitEmail[1];
	if (empty($hostname) && parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) != $_SERVER['HTTP_HOST']) {
		$hostname = $this->Custom->checkSocialDomain(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST));
	}
	$this->Custom->emailUserClippingReport($data[$model]['id'], $hostname, $this->request->query['e'], 'mail_feed');
}

if (!empty($_SERVER['HTTP_REFERER'])) {
	if (parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) != $_SERVER['HTTP_HOST']) {
		$domain = $_SERVER['HTTP_REFERER'];
		$this->Custom->updateClippingReport($data[$model]['id'], $domain, 'network_feed');
	}
}
?>

<?php $current_pr = $data['PressRelease']['id']; ?>

<div class="container">
	<div id="main-content" class="row single-post">
		<div id="content" class="col-lg-9 content">
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="box <?php echo $this->Post->classAccordingToLanguage($data[$model]['language']); ?>" itemscope itemtype="http://schema.org/NewsArticle">


						<h1 class="box-title " itemprop="headline"><?php echo ucfirst($data[$model]['title']); ?></h1>
						<div class="row">
							<div class="company-dtails col-sm-5" itemprop="publisher" itemscope itemtype="http://schema.org/Organization">
								<?php if ($data['Company']['logo']) { ?>
									<div class="ew-comany-logo float-left">
										<div class="newsroom_inner" itemscope itemtype="http://schema.org/ImageObject">
											<?php echo $this->Post->getNewsroomLogo($data['Company']['logo_path'], $data['Company']['logo'], $data['Company']['slug'], $data['Company']['status']); ?>
										</div>
									</div>
								<?php }  ?>
								<div id="prev_company_name" class="ew-compnay ew-compnay-title-dm">
									By <span itemprop="author"><?php echo $this->Post->get_company($data['Company']['name'], $data['Company']['slug'], $data['Company']['status']); ?></span> - <date itemprop="datePublished" content="yyyy-mm-dd <?php echo date($dateformate, strtotime($data['PressRelease']['release_date'])) ?>"><?php echo date($dateformate, strtotime($data['PressRelease']['release_date'])) ?></date>
								</div>
							</div>
							<div class="ew-pr-social col-sm-7">
								<?php
								$singleImageUrl = '';
								if (!empty($data['PressImage'])) {
									$image_path = $data['PressImage'][0]['image_path'];
									$image_name = $data['PressImage'][0]['image_name'];
									$singleImageUrl = SITEURL . 'files/company/press_image/' . $image_path . '/' . $image_name;
								}
								echo $this->Post->sharelinks($data[$model]['title'], SITEURL . 'release/' . $data[$model]['slug'], $data[$model]['summary'], $singleImageUrl);  ?>
							</div>
						</div>
						<?php if (!empty($data[$model]['summary'])) { ?>
							<p class="summary <?php echo $this->Post->classAccordingToLanguage($data[$model]['language']); ?>" itemprop="description"><?php echo $data[$model]['summary']; ?></p>
						<?php } ?>
						<?php if (!empty($data['PressImage'])) { ?>
							<div class="row primageslider">
								<div class="col-sm-12">
									<?php

									echo "<div id='imageGallery'>";
									foreach ($data['PressImage'] as $index => $image) {
										$imgurl = SITEURL . 'files/company/press_image/' . $image['image_path'] . '/' . $image['image_name'];
										$thumburl =  $this->Post->getPressReleaseReseizImage($image, $thumbWidth, $thumbHeight, "resize");
										$sliderImage =  $this->Post->getPressReleaseReseizImage($image, $sliderWidth, $sliderHeight, "resize");
										$dsc = (!empty($image['describe_image'])) ? str_replace("'", "", $image['describe_image']) : "";
										$img = '<img  title="' . $image['image_text'] . '" alt="' . $image['image_text'] . '"  src="' . $sliderImage . '">';
										echo "<a class='lightitem' data-sub-html='$dsc' data-thumb='$thumburl' data-src='$imgurl'>$img<p>$dsc</p></a>";
									}

									if (!empty($data['PressYoutube'])) {
										foreach ($data['PressYoutube'] as $index => $video) {
											$videoUrl = $video['url'];
											$description = (!empty($video['description'])) ? $video['description'] : "";
											$youTubeId = $this->Post->getYouTubeId($videoUrl);
											$thumburl = "http://img.youtube.com/vi/$youTubeId/default.jpg";
											// $thumburl = $this->Post->getResizedImage("ytube-" . strtolower($youTubeId) . ".jpg", $thumbWidth, $thumbHeight);
											echo '<a class="lightitem" data-sub-html="' . $description . '" data-thumb="' . $thumburl . '" href="' . $videoUrl . '" ><img class="yvideoicon" src="' . SITEURL . '/website/img/youtube.png"><img src="https://i.ytimg.com/vi/' . $youTubeId . '/hqdefault.jpg" ><p>' . $dsc . '</p></a>';
										}
									}
									echo "</div>
                      ";
									?>
								</div>
								<a class="propensliderpop" href="javascript:void(0)" onclick="opensliderpop();"></a>
							</div>
							<meta itemprop='url' content='<?php echo $sliderImage; ?>'>
							<div id="pressreleasebody">
								<p class="release-page-content <?php echo $this->Post->classAccordingToLanguage($data[$model]['language']); ?>" itemprop="articleBody">
								<?php }
								?>
								<?php $sourceName = $this->Post->summaryPrefix($data[$model]['source_msa'], $data[$model]['source_state'], $data[$model]['source_country'], $data[$model]['is_source_manually'], $data[$model]['is_old_release']);
								echo $sourceName . $data[$model]['body']; ?>
								</p>
							</div>
							<div class="row">
								<div id="contact_info" class="col-sm-4 prcontact_details">
									<div class="inner_tag">
										<h2>Media Contact</h2>
										<ul>
											<li><strong><?php echo ucfirst($data[$model]['contact_name']); ?></strong> </li>
											<li>
												<a href="mailto:<?php echo ucfirst($data[$model]['email']); ?>"><?php echo $data[$model]['email']; ?></a>
											</li>
											<li> <?php echo $data[$model]['phone']; ?></li>
											<?php if (!empty($data[$model]['job_title']) || $data[$model]['job_title'] != '') { ?>
												<li> <?php echo $data[$model]['job_title']; ?></li>
											<?php } ?>
										</ul>
									</div>
								</div>
								<div class="col-sm-4"></div>
								<?php if (!empty($data['PressSeo'])) { ?>
									<div id="keywords_section" class="col-sm-4">
										<div class="inner_tag">
											<h2>Related Tags</h2>
											<ul id='PressSeo' class='PressSeokeywords'>
												<?php foreach ($data['PressSeo'] as $index => $keywords) {
													echo "<li class='item'><a href='" . SITEURL . "releases/tag/" . $keywords['slug'] . "'>" . $keywords['keyword'] . "</a></li>";
												} ?>
											</ul>
										</div>
									</div>
								<?php  } ?>
							</div>
							<div class="row" id="youtube_podcast">
								<?php /*if (!empty($data['PressPoadcast'])) { ?>
									<div class="col-sm-12">
										<?php
										echo "<h2>Podcasts</h2>";
										echo "<div id='poadcastslider' class='owl-carousel owl-theme'>";
										foreach ($data['PressPoadcast'] as $index => $poadcast) {
											if (!empty($poadcast['url'])) {
												echo "<div class='item'>" . $poadcast['url'] . "<p>" . $poadcast['description'] . "</p></div>";
											}
										}
										echo " </div>"; ?>

									<?php      }*/ ?>
							</div>

							<?php /*if(!empty($data[$model]['iframe_url'])){?>
                     <div class="iframe-section col-sm-12">
                       <iframe width='100%' height='300' src='<?php echo $data[$model]['iframe_url'];?>' frameborder='0'></iframe>
                     </div>
                     <?php } */ ?>
					</div>
				</div>
				<?php
				$latestPr = $this->Post->getNewsByCompany($data['PressRelease']['company_id']);
				if (!empty($latestPr)) { ?>
					<div class="full ew-latest-news-st" id="latest_news">
						<?php if (!empty($latestPr)) {
							$total = count($latestPr);
							if ($total > 1) { ?>
								<div class="row">
									<div class="col-sm-12">
										<div class="ew-title full">Read other news from <?php echo ucfirst($data['Company']['name']); ?></div>
									</div>
									<div class="col-sm-12 ew-lcn-right-news">
										<?php foreach ($latestPr as $loop1 => $latest) { ?>
											<?php if ($current_pr != $latest['PressRelease']['id']) { ?>
												<div class="full ew-lcn-right-single">
													<?php if (!empty($latest['PressImage'])) { ?>
														<div class="orange-border ew-lcn-img-single float-left">
															<a href="<?php echo SITEURL . "release/" . $latest['PressRelease']['slug']; ?>">
																<?php echo $this->Post->getPrSingleImage($latest['PressImage'], 'crop', '333', '215', '0', '0', '0'); ?>
															</a>
														</div>
													<?php } ?>
													<div class="float-left ew-lcn-right-single-content class_newsroom">
														<h2 class="post-title <?php echo $this->Post->classAccordingToLanguage($latest['Language']); ?>"><?php echo $this->Post->get_title($latest['PressRelease']['title'], $latest['PressRelease']['slug']); ?></h2>
														<div class="company_logo_name">
															<?php if ($latest['Company']['logo']) { ?>
																<div class="ew-comany-logo">
																	<div class="newsroom_inner">
																		<?php echo $this->Post->getNewsroomLogo($latest['Company']['logo_path'], $latest['Company']['logo'], $latest['Company']['slug'], $latest['Company']['status']); ?>
																	</div>
																</div>
															<?php } ?>
															<div id="prev_company_name" class="ew-compnay float-left">
																<?php echo $this->Post->get_company($latest['Company']['name'], $latest['Company']['slug'], $latest['Company']['status']); ?>
																- <?php echo date($dateformate, strtotime($latest['PressRelease']['release_date'])) ?>
															</div>
														</div>
														<div class="prsummary <?php echo $this->Post->classAccordingToLanguage($latest['Language']); ?>"><?php echo $this->Post->wordLimit($latest['PressRelease']['summary'], $latest['PressRelease']['slug']); ?></div>
													</div>
												</div>
											<?php  } ?>


								<?php }
									}
								} // end if $latestPr condition   
								?>
								<div class="row newsrooms-btns">
									<div class="browse-btn col-sm-6 text-left"><a href="<?php echo SITEURL; ?>company/<?php echo $data['Company']['slug']; ?>">View All Newsroom PR</a></div>
								</div>
									</div>
								</div>
					</div>
				<?php } ?>
			</div>
			<div id="sidebar" class="col-lg-3">
				<?php echo $this->element('sidebar'); ?>
			</div>
		</div>
	</div>
</div>

</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
<script type="text/javascript">
	/*   $('#primgslider').owlCarousel({
        loop:false,
        margin:0,
        nav:true,
        items:1,
         autoplay:true,
       autoHeight:true,
      autoplayTimeout:2000,
      autoplayHoverPause:true
    });*/
</script>

<script type="text/javascript">
	$('#poadcastslider').owlCarousel({
		loop: false,
		margin: 0,
		nav: true,
		items: 1,
		autoplay: false,
		autoplayHoverPause: true
	});
</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/lg-share/1.1.0/lg-share.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/lg-zoom/1.1.0/lg-zoom.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/lg-video/1.2.2/lg-video.min.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		$('#imageGallery').lightSlider({
			gallery: true,
			item: 1,
			loop: false,
			thumbItem: 9,
			slideMargin: 0,
			enableDrag: false,
			currentPagerPosition: 'left',
			adaptiveHeight: true,
			onSliderLoad: function(el) {
				el.lightGallery({
						selector: '#imageGallery .lightitem',
						animateThumb: false,
						showThumbByDefault: false
					}

				);
			}
		});
	});

	$(document).ready(function() {
		var company_slug = '<?php echo SITEURL . 'rss/company.rss?s=' . $data['Company']['slug']; ?>';
		$('.ew-rss a').attr('href', company_slug);
	});

	function trackClickedEvents(link = '', type = '2') {
		if (link != "") {
			$.ajax({
				type: 'POST',
				url: SITEURL + 'ajax/trackpr',
				data: {
					prId: "<?php echo base64_encode($data[$model]['id']); ?>",
					dtype: "clickthrough",
					link: link,
					type: type
				},
				async: true,
				success: function(data) {
					$("#AjaxLoading").hide();
				}
			});
		}
	}
	$("#pressreleasebody a").click(function(event) {
		// event.preventDefault()
		ShowLoadingIndicator()
		let link = $(this).attr("href");
		trackClickedEvents(link, 2);
	});
</script>