<?php

$uId = ($this->session->read('Auth.User.id')) ? $this->session->read('Auth.User.id') : "0";
$staff_role_id = ($this->session->read('Auth.User.staff_role_id')) ? $this->session->read('Auth.User.staff_role_id') : "0";
$registerfrom = ($this->session->read('ClientUser.signup')) ? $this->session->read('ClientUser.signup') : "backend";
$signupClass = ($this->session->read('ClientUser.signup')) ? "signup-from-" . $this->session->read('ClientUser.signup') : "";

   ?>
<!DOCTYPE html>
	<!-- <html dir="rtl" lang="ar"> -->
 <!-- <html dir="ltr" lang="en"> -->
	<html>
	<head>
		<meta charset="UTF-8">
		<?php
		$meta_des = (isset($meta_description) && !empty($meta_description)) ? $meta_description : "";
		$meta_kywrd = (isset($meta_keyword) && !empty($meta_keyword)) ? $meta_keyword : "";
		$meta_title = (isset($meta_title) && !empty($meta_title)) ? $meta_title : $title_for_layout; ?>
		<title><?php echo $meta_title; ?></title>
		<meta name="keywords" content="<?php echo $meta_kywrd; ?>">
		<meta name="description" content="<?php echo $meta_des; ?>">
		<meta name="robots" content="max-snippet:-1, max-image-preview:large, max-video-preview:-1" />
		<link rel="canonical" href="<?php echo SITEURL . $this->request->url; ?>" />
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">


		<link rel="alternate" type="application/rss+xml" title="Headlines News" href="<?php echo SITEURL; ?>news/rss/headlines.xml" />
		<link rel="alternate" type="application/rss+xml" title="Press Release Services" href="<?php echo SITEURL . 'latest-news'; ?>" />
		<?php
		echo $this->Html->css(
			array(
				'/plugins/jqueryui/jquery-ui.min',
				'/plugins/fontawesome/css/all.min',
				'/website/css/bootstrap-grid.min',
				'/website/css/bootstrap-reboot.min',
				// '/website/css/bootstrap.min', 
				'/plugins/bootstrap/css/bootstrap.min',
				'/website/css/custom',
				'/plugins/toastr/toastr.min',
				'responsive',
				'/website/css/custom2',
				'/plugins/owlcarousel/owl.carousel.min',
				'customloader.css',
			)
		);
		?>
		<?php
		echo $this->Html->script(
			array(
				// 'https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js',
				'/plugins/jquery/jquery.min',
				'/plugins/jqueryui/jquery-ui.min',
				'/website/js/popper.min',
				'/plugins/bootstrap/js/bootstrap.min', 
				// 'https://code.jquery.com/ui/1.11.4/jquery-ui.min.js',
				// 'https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js', 
				'/plugins/jquery-validation/jquery.validate.min',
				'/plugins/jquery-validation/additional-methods.min',
				'sticky-sidebar.min',
				'/plugins/toastr/toastr.min',
				'/plugins/owlcarousel/owl.carousel.min',
				'bootbox.min',
				'/plugins/lazyload/jquery.lazy.min',
				'/plugins/lazyload/jquery.lazy.plugins.min',
				'custom',
			)
		);
		echo $this->Js->writeBuffer(array('cache' => true));
		if ($controller != "releases" && $action != "release") {
			$bodyClass = $this->Custom->bodyclass($this->here);
		} else {
			$bodyClass = "pr-single-page";
		}
		$uId = ($this->session->read('Auth.User.id')) ? $this->session->read('Auth.User.id') : "0";

		?>
		<!-- Latest compiled and minified CSS -->
		<script>
			var SITEURL = '<?php echo SITEURL; ?>';
			var CONTROLLER = '<?php echo $this->params->controller; ?>';
			var currency = <?php echo Configure::read('Site.currency'); ?>;
			var CURRENT_URL = '<?php echo SITEURL . $this->params->url; ?>';
			var uId = '<?php echo $uId; ?>';
		</script>
		<?php if ($controller == "releases" && $action == "release") {
			$imageUrl = "";
			if (!empty($singleImage)) {
				$image_path = $singleImage[0]['image_path'];
				$image_name = $singleImage[0]['image_name'];
				$imageUrl = SITEURL . 'files/company/press_image/' . $image_path . '/' . $image_name;
			}
		?>
			<script type="application/ld+json">
				{
					"@context": "https://schema.org",
					"@type": "NewsArticle",
					"mainEntityOfPage": {
						"@type": "WebPage",
						"@id": "<?php echo SITEURL; ?>"
					},
					"headline": "<?php echo $title_for_layout; ?>",
					<?php if (!empty($imageUrl)) { ?> "image": "<?php echo $imageUrl; ?>",
					<?php } ?> "author": {
						"@type": "Organization",
						"name": "<?php echo $contact_name; ?>"
					},
					"publisher": {
						"@type": "Organization",
						"name": "<?php echo $company; ?>",
						"logo": {
							"@type": "ImageObject",
							"url": "<?php echo $companylogo; ?>",
							"width": <?php echo $width ?>,
							"height": <?php echo $height ?>
						}
					},
					"datePublished": "<?php echo $release_date; ?>",
					"dateModified": "<?php echo $release_date; ?>"
				}
			</script>
		<?php } else { ?>
			<script type="application/ld+json">
				{
					"@context": "https://schema.org",
					"@type": "Organization",
					"name": "<?php echo $siteName; ?>",
					"url": "<?php echo SITEURL ?>",
					"logo": "<?php echo SITEURL; ?>website/img/group-web-media-logo.png",
					"sameAs": [
						"<?php echo strip_tags(Configure::read('Social.link.facebook')); ?>",
						"<?php echo strip_tags(Configure::read('Social.link.twitter')); ?>",
						"<?php echo strip_tags(Configure::read('Social.link.youtube')); ?>",
						"<?php echo strip_tags(Configure::read('Social.link.linkedIn')); ?>"
					]
				}, {
					"@type": "WebSite",
					"name": "<?php echo $siteName; ?>",
					"url": "<?php echo SITEURL; ?>",
					"potentialAction": {
						"@type": "SearchAction",
						"target": "<?php echo SITEURL; ?>search?srch-term{search_term_string}",
						"query-input": "required name=search_term_string"
					}
				}
			</script> 
		<?php } ?>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
		<link rel="apple-touch-icon" sizes="57x57" href="<?php echo SITEURL . 'img/favicon'; ?>/apple-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="<?php echo SITEURL . 'img/favicon'; ?>/apple-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="<?php echo SITEURL . 'img/favicon'; ?>/apple-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="<?php echo SITEURL . 'img/favicon'; ?>/apple-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="<?php echo SITEURL . 'img/favicon'; ?>/apple-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="<?php echo SITEURL . 'img/favicon'; ?>/apple-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="<?php echo SITEURL . 'img/favicon'; ?>/apple-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="<?php echo SITEURL . 'img/favicon'; ?>/apple-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="<?php echo SITEURL . 'img/favicon'; ?>/apple-icon-180x180.png">
		<!-- <link rel="icon" type="image/png" sizes="192x192" href="<?php echo SITEURL . 'img/favicon'; ?>/android-icon-192x192.png"> -->
		<link rel="icon" type="image/png" sizes="32x32" href="<?php echo SITEURL . 'img/favicon'; ?>/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="<?php echo SITEURL . 'img/favicon'; ?>/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="<?php echo SITEURL . 'img/favicon'; ?>/favicon-16x16.png">
		<!-- <link rel="manifest" href="<?php echo SITEURL . 'img/favicon'; ?>/manifest.json"> -->
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="<?php echo SITEURL . 'img/favicon'; ?>/ms-icon-144x144.png">
		<meta name="theme-color" content="#ffffff">
        <style>  
   
   .ew-home-mid {
       padding-top: 0px;
   } 

 
   .modal-header-notification{
       padding: 10px 0px 10px;
       display: block;
       clear: both;
       background: #fa7d07;
       float: left;
       width: 100%;
       color: #fff;
       margin-bottom: 9px;
   }
</style>
		<?php //echo $this->Html->meta('favicon.ico','img/favicon.ico',array('type' => 'icon')); ?>
		<script data-ad-client="ca-pub-4304602996208257" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	</head>

	<body class=" <?php if (isset($banner) && !empty($banner)) { echo "banner"; } else { echo "nobanner"; } ?> <?php echo $controller . ' ' . $action; ?> <?php echo $bodyClass . ' ' . $signupClass; ?> <?php echo ($uId != 0) ? "logged-in" : "logged-out"; ?>">
		
		<div id="AjaxLoading" style="display:none;" class="spinner-container">
			<div class="loader-main">
				<div class="cssload-loader">
					<div class="cssload-inner cssload-one"></div>
					<div class="cssload-inner cssload-two"></div>
					<div class="cssload-inner cssload-three"></div>
				</div>
				<p>Please wait ...</p>
			</div>
		</div>
		<?php // echo $this->element('site_header');
		echo $this->Session->flash();   ?>

        

		<div class="full ew-home-mid"> 
            
                <div class="submitsticky my-3">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between">
                                        <h3 class="card-title mb-0">
                                            <?php echo $this->Html->link(__("Back to Edit"), array('controller' =>'users','action' =>$previewPageAction,$data[$model]['slug']), array('class' => 'p-2 btn btn-primary mr-4','escape'=>false));?>
                                            <?php echo $title_for_layout; ?>
                                        </h3>
                                        <div class="card-tools">
                                            <?php
                                            
                                            echo $this->Form->create($model, array('type' => 'file', 'inputDefaults' => array('class' => 'form-control', 'label' => false, 'div' => false,), 'class' => "-right form", "id" => "register_from", 'validate'));
                                            
                                            echo $this->Form->input("$model.id", array("type" => "hidden","value"=>$data[$model]['id']));
                                            echo $this->Form->input("$model.company_id", array("type" => "hidden","value"=>$data[$model]['id']));
                                            
                                            
                                            echo $this->Form->input('Proceed with this newsroom', array("type" => 'submit', 'id' => 'submit-btn', 'class' => "btn btn-info", "div" => "input-group input-group-sm"));
                                            echo $this->Form->end();
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>

                    <div class="container-fluid">
                        <div class="row">
                            
                        
                        <div class="col-sm-12">
                            <div class="card modal-header-notification">
                                <div class="card-body full ew-sub-page ew-newsroom-block text-center"> 
                                    <h4 class="modal-title">Newsroom Preview</h4>
                                    <p>This is how your company newsroom will be visible to the public. Please scroll to preview newsroom.<br>Check your entries carefully before submission.<br>If all details are fine, then click on the button, "Proceed with this newsroom" else click on "Go back and edit" button below.</p>
                                </div>
                            </div> 
                        </div>
                        </div>
                    </div>
                    <?php 
                    if($data){
                    $banner = SITEURL . 'files/company/banner/' . $data[$model]['banner_path'] . '/' . $data[$model]['banner_image']; ?>
                    <div style="background-image:url('<?php echo $banner; ?>'); background-size: cover !important; height: 330px;" class="header-newsroom ew-banner-newsroom full">
                        <!-- <div class="ew-banner-content text-center"><?php // echo $title_for_layout; ?></div> -->
                    </div>
                    <?php } ?>
                    <div class="container-fluid">
                        <?php  echo $this->fetch('content'); ?> 
                    </div>
		</div>
		<?php echo $this->element('site_footer'); ?>
		<script type="text/javascript">
        $(function() {
            // image layz load
            $('.lazyload').lazy();
            $(".custom_select").select2();
            $(".datepicker").datepicker({
                dateFormat: "dd-mm-yy",
                changeMonth: true,
                changeYear: true,
            });
        });
    </script>
	</body>

	</html>
 