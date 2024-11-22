<?php

$uId = ($this->session->read('Auth.User.id')) ? $this->session->read('Auth.User.id') : "0";
$staff_role_id = ($this->session->read('Auth.User.staff_role_id')) ? $this->session->read('Auth.User.staff_role_id') : "0";
$registerfrom = ($this->session->read('ClientUser.signup')) ? $this->session->read('ClientUser.signup') : "backend";
$signupClass = ($this->session->read('ClientUser.signup')) ? "signup-from-" . $this->session->read('ClientUser.signup') : "";

if ($uId != 0 && $staff_role_id == 3 && $registerfrom != "fronted" && ((!empty($this->params->pass) && in_array($this->params->pass[0], array('plans'))) || in_array($this->params->action, array('create_newsroom', 'create_newsroom_preview', 'edited_newsroom_preview', 'edit_newsroom', 'payment', 'contact-us')))) {

  include 'default.ctp';

} else { ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
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


    <link rel="alternate" type="application/rss+xml" title="Headlines News" href="<?php echo SITEURL; ?>news/rss/headlines.xml" />
    <link rel="alternate" type="application/rss+xml" title="Press Release Services" href="<?php echo SITEURL . 'latest-news'; ?>" />

    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
      crossorigin="anonymous"
    />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
      rel="stylesheet"
    />
<script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
      crossorigin="anonymous"
    ></script>
    <script src="css/jquery.min.js"></script>
    <script src="css/slick.min.js"></script>
    <script src="css/slider.js"></script>
    <script src="js/index.js"></script>
  <?php
    echo $this->Html->css(
      array(
        //'/plugins/jqueryui/jquery-ui.min',
        '/plugins/fontawesome/css/all.min',
        '/website/css/bootstrap-grid.min',
        '/website/css/bootstrap-reboot.min',
        '/plugins/bootstrap/css/bootstrap.min',
        '/plugins/toastr/toastr.min',
        '/plugins/owlcarousel/owl.carousel.min',
        '/webc/src/css/style',
        '/webc/src/css/LatestNews',
        '/webc/css/slick.min',
        '/webc/css/slick-theme.min',
        '/webc/css/jquery-ui',
        '/webc/css/slick-theme.min'
      )
    );
    ?>
    <?php
    echo $this->Html->script(
      array(
        '/webc/css/jquery.min',
        '/webc/css/slick.min',
        '/webc/css/slider',
        '/webc/js/index',
        '/plugins/jqueryui/jquery-ui.min',
        '/plugins/bootstrap/js/bootstrap.min', 
        '/plugins/jquery-validation/jquery.validate.min',
        '/plugins/jquery-validation/additional-methods.min',
        '/plugins/toastr/toastr.min',
        '/plugins/owlcarousel/owl.carousel.min',
        'bootbox.min'
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

    <?php //echo $this->Html->meta('favicon.ico','img/favicon.ico',array('type' => 'icon'));
    ?>
    <script data-ad-client="ca-pub-4304602996208257" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

    
    <!-- Styles CSS -->
    
  </head>

  <body class=" <?php if (isset($banner) && !empty($banner)) {
            echo "banner";
          } else {
            echo "nobanner";
          } ?> <?php echo $controller . ' ' . $action; ?> <?php echo $bodyClass . ' ' . $signupClass; ?> <?php echo ($uId != 0) ? "logged-in" : "logged-out"; ?>">

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

<?php
$newsroomBanner = "";
if ($this->params['controller'] == 'Pages' && $this->params['action'] == 'newsroom' && isset($data['Company']['banner_path']) && !empty($data['Company']['banner_path'])) {
$newsroomBanner = (!empty($data['Company']['banner_image'])) ? SITEURL . 'files/company/banner/' . $data['Company']['banner_path'] . '/' . $data['Company']['banner_image'] : "";
}
?>

    <!-- Hearder Navigation -->
    <header class="<?php if ($action == 'newsroom' || $controller == 'newsroom') {
echo "newsroomheader";
} ?>" <?php if (!empty($newsroomBanner)) { ?> style="background-image: url('<?php echo $newsroomBanner; ?>'); background-repeat: no-repeat; background-size: cover;  width: 100%;;" <?php } ?> >

<?php if ($action != 'newsroom' &&  $controller != 'newsroom' ) { ?>
      <div class="header-top-line cm_header">
        <div class="container d-flex justify-content-between">
          <div class="cm-tp-btn d-none d-lg-block">
            <a href="<?php echo SITEURL; ?>users/create-newsroom" class="p-0 m-0"> CREATE NEWSROOM </a>
          </div>
          <ul class="d-flex top-line">

            <?php if (!empty($this->Session->read('Auth.User.staff_role_id'))) { ?>
                <li><a class="text-decoration-none text-black" href="<?php echo SITEURL; ?>users/dashboard">My account</a></li>
                <li><a  class="text-decoration-none text-black" href="<?php echo SITEURL; ?>users/logout">Log out</a></li>
            <?php } else { ?>
                <?php if ($action == 'newsroom' || $controller == 'newsroom') {  ?>
                    <li><a class="text-decoration-none text-black" href="<?php echo SITEURL; ?>"><i class="fa fa-home"></i></a></li>
                <?php } ?>
                <li><a  class="text-decoration-none text-black" href="<?php echo SITEURL; ?>users/login">Log In</a></li>
                <li><a class="text-decoration-none text-black" href="<?php echo SITEURL; ?>users/signup">Register</a></li>
            <?php } ?>

            <li>
              <a href="<?php echo SITEURL; ?>contact-us/" class="text-decoration-none text-black">
                Contact
              </a>
            </li>
            <li>
              <a href="<?php echo SITEURL; ?>users/become-subscriber" class="text-decoration-none text-black">
                Subscribe
              </a>
            </li>
            <li>
              <a href="<?php echo SITEURL; ?>blog/" class="text-decoration-none text-black">
                Blog
              </a>
            </li>
            <li>
              <div class="cm-tp-btn d-none d-lg-block">
                <?php if (isset($role_id) && $role_id == 3) { ?>
                    <a class="p-0 m-0" href="<?php echo SITEURL; ?>users/add-press-release">SUBMIT A PRESS RELEASE</a>
                <?php } else { ?>
                    <a class="p-0 m-0" href="<?php echo SITEURL; ?>plans">SUBMIT A PRESS RELEASE</a>
                <?php } ?>

              </div>
            </li>
          </ul>
        </div>
      </div>
    <?php } ?>


<?php
if ($controller != 'newsroom') {
if ($action != 'newsroom') { ?>

      <nav class="navbar navbar-expand-lg bg-body-white">
        <div class="container">
          <a class="navbar-brand" href="<?php echo SITEURL; ?>">
            <img
              src="<?php echo SITEURL; ?>/webc/src/images/emailwire-logo.jpg"
              alt=""
              width="175"
              height="51"
            />
          </a>
          <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent"
            aria-expanded="false"
            aria-label="Toggle navigation"
          >
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item <?php if (in_array($requestUrl, array('category', 'newsrooms', 'latest-news', 'msa', 'country'))) {
echo 'active';
}   ?> <?php if (in_array($controller, ['releases', 'pages']) && in_array($action, array('newsbycategory', 'newsbycompany', 'newsbydate', 'newsbymsa', 'newsbycountry', 'newsrooms'))) {
                                                                        echo 'active';
                                                                    } ?> ">
                <a
                  href="<?php echo SITEURL . 'latest-news'; ?>"
                  id="news_id"
                  class="<?php if (in_array($controller, ['releases', 'pages']) && in_array($action, array('index', 'newsbycategory', 'newsbycompany', 'newsbydate', 'newsbymsa', 'newsbycountry', 'newsrooms'))) {
echo 'active';
} ?> nav-link dropdown-toggle text-black sub_menu_item"
                  role="button"
                >
                  News
                </a>
                <div id="hover_menu_id " class="hover_menu">
                  <div class="hover_menu_wraper">
                    <div class="">
                      <a href="<?php echo SITEURL . 'latest-news'; ?>">Recent News</a>
                      <a href="<?php echo SITEURL . 'newsrooms'; ?>">Newsrooms</a>
                      <a href="<?php echo SITEURL . 'category'; ?>">News by Category</a>
                      <a href="<?php echo SITEURL . 'msa'; ?>">News by MSA</a>
                      <a href="<?php echo SITEURL . 'country'; ?>">News by Country</a>
                    </div>
                  </div>
                </div>
              </li>
              <li class="nav-item <?php if ($controller == 'pages' && in_array($requestUrl, array('press-release-distribution', 'press-release-writing-services', 'press-release-service-comparison', 'press-release-tips', 'press-releases-examples', 'press-release-services', 'press-release-free', 'newsrooms-for-organizations', 'clipping-report'))) {
echo 'active';
} ?>">
                <a
                  href="<?php echo SITEURL . 'press-release-distribution'; ?>"
                  id="news_id"
                  class="<?php if ($controller == 'pages' && in_array($requestUrl, array('press-release-distribution', 'press-release-tips', 'press-release-services', 'press-release-writing-services', 'press-release-free', 'newsrooms-for-organizations', 'clipping-report'))) {
            echo 'active';
        } ?> nav-link dropdown-toggle text-black sub_menu_item"
                  role="button"
                >
                  Services
                </a>
                <div id="hover_menu_id " class="hover_menu">
                  <div class="hover_menu_wraper">
                    <a href="<?php echo SITEURL . 'press-release-distribution'; ?>"
                      >Press Release Distribution</a
                    >
                    <a href="<?php echo SITEURL . 'press-release-services'; ?>">Press Release</a>
                    <a href="<?php echo SITEURL . 'press-release-writing-services'; ?>">Press Writing Services</a>
                  </div>
                </div>
              </li>
              <li class="nav-item <?php if (isset($this->request->pass['0']) && $this->request->pass['0'] == 'plans') {
echo 'active';
} ?>">
                <a
                  href="<?php echo SITEURL; ?>plans"
                  id="news_id"
                  class="<?php if (isset($this->request->pass['0']) && $this->request->pass['0'] == 'plans') {
echo 'active';
} ?> nav-link dropdown-toggle text-black sub_menu_item"
                  role="button"
                >
                  Pricing
                </a>
                <div id="hover_menu_id " class="hover_menu">
                  <div class="hover_menu_wraper">
                    <div class="">

                      <?php
if (!empty($plancategory)) {
foreach ($plancategory as $pcatslug => $pcategory) {?>
                      <a href="<?php echo SITEURL . 'plans/' . $pcatslug?>"><?php echo $pcategory ?></a>
                    <?php } } ?>
                      
                    </div>
                  </div>
                </div>
              </li>
              <li class="nav-item <?php if (($controller == 'pages' || $controller == 'users') && in_array($requestUrl, array('about-us-four-steps', 'client-testimonials', 'why-use-us', 'editorial-guidelines', 'support', 'contact-us'))) {
echo 'active';
} ?> ">
                <a
                  href="<?php echo SITEURL; ?>about-us-four-steps"
                  id="news_id"
                  class="<?php if ($controller == 'pages' && in_array($requestUrl, array('about-us-four-steps', 'client-testimonials', 'why-use-us', 'editorial-guidelines', 'support', 'contact-us'))) {
echo 'active';
} ?>  nav-link dropdown-toggle text-black sub_menu_item"
                  role="button"
                >
                  About
                </a>
                <div id="hover_menu_id " class="hover_menu">
                  <div class="hover_menu_wraper">
                    <div class="container">
                      <a href="<?php echo SITEURL; ?>contact-us">Contact Us</a>
                      <a href="<?php echo SITEURL; ?>editorial-guidelines">Editorial Guidlines</a>
                      <a href="<?php echo SITEURL; ?>support">Support</a>
                      <a href="<?php echo SITEURL; ?>why-use-us">Why Use Us</a>
                      <a href="<?php echo SITEURL; ?>client-testimonials">Testimonials</a>
                    </div>
                  </div>
                </div>
                
              </li>
            </ul>
            <!-- Phone Icon -->

            <div class="d-flex text-center gap-2 align-items-end">
              <i class="bi bi-telephone text-warning fw-bold fs-5"></i>
              <a href="tel:+18888809539"><h6 class="fw-medium me-5">+1 888-880-9539</h6></a>
            </div>

            <!-- Search bar -->
            <form class="d-flex mb-2" role="search" action="<?php echo SITEURL; ?>search">
              <input
                class="form-control me-2"
                type="search"
                placeholder="Search"
                aria-label="Search" name="srch-term" id="srch-term" 
              />
              <button class="btn p-0" type="submit">
                <img
                  src="<?php echo SITEURL; ?>/webc/src/images/svg/search-svgrepo-com.svg"
                  alt=""
                  width="30"
                />
              </button>
            </form>
          </div>
        </div>
      </nav>

   <?php }
} ?>               


    </header>

    <!-- Hearder Navigation -->

  <?php  echo $this->Session->flash();  ?>


      <div class="mt-5 homeTitle container">
        <h1 class="highlight">
          Press Release Distribution with Guaranteed Results™
        </h1>
        <div class="">
          Amplify your story with online press release distribution in the
          Unites Satates, Europe, Middle East, Africa, Asia(EMEAA). Reach local,
          national and global media.
        </div>
      </div>
      <div
        id="carouselExampleInterval"
        class="carousel slide container my-5"
        data-bs-ride="carousel"
      >
        <div class="carousel-indicators">
          <button
            type="button"
            data-bs-target="#carouselExampleInterval"
            data-bs-slide-to="0"
            class="active"
            aria-current="true"
            aria-label="Slide 1"
          ></button>
          <button
            type="button"
            data-bs-target="#carouselExampleInterval"
            data-bs-slide-to="1"
            aria-label="Slide 2"
          ></button>
          <button
            type="button"
            data-bs-target="#carouselExampleInterval"
            data-bs-slide-to="2"
            aria-label="Slide 3"
          ></button>
          <button
            type="button"
            data-bs-target="#carouselExampleInterval"
            data-bs-slide-to="3"
            aria-label="Slide 4"
          ></button>
          <button
            type="button"
            data-bs-target="#carouselExampleInterval"
            data-bs-slide-to="4"
            aria-label="Slide 5"
          ></button>
          <button
            type="button"
            data-bs-target="#carouselExampleInterval"
            data-bs-slide-to="5"
            aria-label="Slide 6"
          ></button>
          <button
            type="button"
            data-bs-target="#carouselExampleInterval"
            data-bs-slide-to="6"
            aria-label="Slide 7"
          ></button>
          <button
            type="button"
            data-bs-target="#carouselExampleInterval"
            data-bs-slide-to="7"
            aria-label="Slide 8"
          ></button>
        </div>
        <div class="carousel-inner">
          <!-- Banner 2 -->
          <div class="carousel-item active" data-bs-interval="1000">
            <div class="cm_carousel">
              <div class="cardInfo">
                <h1 class="mb-2">Online Distribution</h1>
                <p class="mb-3">
                  Submit your press release for distribution on GroupWeb Media
                  network, <br />
                  news sites, and major search engines.
                </p>
                <div class="mt-4">
                  <a
                    href="<?php echo SITEURL?>"
                    class="main-color text-decoration-none text-uppercase fw-bold rounded-1"
                  >
                    Submit a press release
                  </a>
                </div>
              </div>
              <div class="image-wrapper">
                <img
                  src="<?php echo SITEURL?>/webc/img/Banner/online media.png"
                  alt="Online Distribution"
                  class="banner-image"
                />
              </div>
            </div>
          </div>
          <!-- Banner 3 -->
          <div class="carousel-item" data-bs-interval="2000">
            <div class="cm_carousel">
              <div class="cardInfo">
                <h1 class="mb-2">United States Distribution</h1>
                <p class="mb-3">
                  Send press release for distribution to local, state and
                  national media.
                </p>
                <div class="mt-4">
                  <a
                    href="<?php echo SITEURL?>"
                    class="main-color text-decoration-none text-uppercase fw-bold rounded-1"
                  >
                    Submit a press release
                  </a>
                </div>
              </div>
              <div class="image-wrapper">
                <img
                  src="<?php echo SITEURL?>/webc/img/Banner/United States Media.png"
                  alt="United States Distribution"
                  class="banner-image"
                />
              </div>
            </div>
          </div>
          <!-- Banner 4 -->
          <div class="carousel-item" data-bs-interval="3000">
            <div class="cm_carousel">
              <div class="cardInfo">
                <h1 class="mb-2">Africa News Distribution</h1>
                <p class="mb-3">
                  Reach African media outlets by country or regions in Arabic,
                  English, French and Swahili
                </p>
                <div class="mt-4">
                  <a
                    href="<?php echo SITEURL?>"
                    class="main-color text-decoration-none text-uppercase fw-bold rounded-1"
                  >
                    Submit a press release
                  </a>
                </div>
              </div>
              <div class="image-wrapper">
                <img
                  src="<?php echo SITEURL?>/webc/img/Banner/African Media.png"
                  alt="Africa News Distribution"
                  class="banner-image"
                />
              </div>
            </div>
          </div>
          <!-- Banner 5 -->
          <div class="carousel-item">
            <div class="cm_carousel">
              <div class="cardInfo">
                <h1 class="mb-2">Asia Press Release Distribution</h1>
                <p class="mb-3">
                  Submit press release to be distributed in Asia Pacific, South
                  and Southeast Asian countries
                </p>
                <div class="mt-4">
                  <a
                    href="<?php echo SITEURL?>"
                    class="main-color text-decoration-none text-uppercase fw-bold rounded-1"
                  >
                    Submit a press release
                  </a>
                </div>
              </div>
              <div class="image-wrapper">
                <img
                  src="<?php echo SITEURL?>/webc/img/Banner/Asian Media.png"
                  alt="Asia Press Release Distribution"
                  class="banner-image"
                  style="object-position: right"
                />
              </div>
            </div>
          </div>
          <!-- Banner 6 -->
          <div class="carousel-item">
            <div class="cm_carousel">
              <div class="cardInfo">
                <h1 class="mb-2">Europe Press Release Distribution</h1>
                <p class="mb-3">
                  Reach media outlets by country in Europe or in DACH, Benelux
                  or UK regions.
                </p>
                <div class="mt-4">
                  <a
                    href="<?php echo SITEURL?>"
                    class="main-color text-decoration-none text-uppercase fw-bold rounded-1"
                  >
                    Submit a press release
                  </a>
                </div>
              </div>
              <div class="image-wrapper">
                <img
                  src="<?php echo SITEURL?>/webc/img/Banner/European Media.png"
                  alt="Europe Press Release Distribution"
                  class="banner-image"
                />
              </div>
            </div>
          </div>
          <!-- Banner 7 -->
          <div class="carousel-item">
            <div class="cm_carousel">
              <div class="cardInfo">
                <h1 class="mb-2">MENA Press Release Distribution</h1>
                <p class="mb-3">
                  Send a press release in Arabic and English for distribution in
                  the GCC/MENA regions.
                </p>
                <div class="mt-4">
                  <a
                    href="<?php echo SITEURL?>"
                    class="main-color text-decoration-none text-uppercase fw-bold rounded-1"
                  >
                    Submit a press release
                  </a>
                </div>
              </div>
              <div class="image-wrapper">
                <img
                  src="<?php echo SITEURL?>/webc/img/Banner/Middle East North Africa.png"
                  alt="MENA Press Release Distribution"
                  class="banner-image"
                  style="filter: contrast(100%)"
                />
              </div>
            </div>
          </div>
          <!-- Banner 8 -->
          <div class="carousel-item">
            <div class="cm_carousel">
              <div class="cardInfo">
                <h1 class="mb-2">Global News Distribution</h1>
                <p class="mb-3">
                  Submit press release to reach a worldwide audience in the
                  language of your choice.
                </p>
                <div class="mt-4">
                  <a
                    href="<?php echo SITEURL?> "
                    class="main-color text-decoration-none text-uppercase fw-bold rounded-1"
                  >
                    Submit a press release
                  </a>
                </div>
              </div>
              <div class="image-wrapper">
                <img
                  src="<?php echo SITEURL?>/webc/img/Banner/Global_Media--.png"
                  alt="Global News Distribution"
                  class="banner-image"
                />
              </div>
            </div>
          </div>
          <!-- banner 9 -->
          <div class="carousel-item">
            <div class="cm_carousel">
              <div class="cardInfo">
                <h1 class="mb-2">Industry Distribution</h1>
                <p class="mb-3">
                  Submit a press release for distribution to media in vertical
                  industries.
                </p>
                <div class="mt-4">
                  <a
                    href="<?php echo SITEURL?>plans/industries "
                    class="main-color text-decoration-none text-uppercase fw-bold rounded-1"
                  >
                    Submit a press release
                  </a>
                </div>
              </div>
              <div class="image-wrapper">
                <img
                  src="<?php echo SITEURL?>/webc/img/Banner/Industrie (1).png"
                  alt="Global News Distribution"
                  class="banner-image"
                />
              </div>
            </div>
          </div>
        </div>
        <button
          class="carousel-control-prev"
          type="button"
          data-bs-target="#carouselExampleInterval"
          data-bs-slide="prev"
        >
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button
          class="carousel-control-next"
          type="button"
          data-bs-target="#carouselExampleInterval"
          data-bs-slide="next"
          style="width: 5vw"
        >
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>


  <div class="full ew-home-mid">
      <div class="<?php echo (!empty($isFullwidth))?"container-fluid":"container"; ?>">
        <?php echo $this->fetch('content'); ?>
      </div>
    </div>
    
    <?php echo $this->element('site_footer'); ?>
    <script type="text/javascript">
        $(function() {
            // image layz load
            // $('.lazyload').lazy();
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

    <?php } ?>