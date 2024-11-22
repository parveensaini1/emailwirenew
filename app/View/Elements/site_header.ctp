<!-- <script src="https://kit.fontawesome.com/09983b0619.js"></script> -->
<?php
$newsroomBanner = "";
if ($this->params['controller'] == 'Pages' && $this->params['action'] == 'newsroom' && isset($data['Company']['banner_path']) && !empty($data['Company']['banner_path'])) {
    $newsroomBanner = (!empty($data['Company']['banner_image'])) ? SITEURL . 'files/company/banner/' . $data['Company']['banner_path'] . '/' . $data['Company']['banner_image'] : "";
}

?>
<header class="ew-header full <?php if ($action == 'newsroom' || $controller == 'newsroom') {
                                    echo "newsroomheader";
                                } ?>" <?php if (!empty($newsroomBanner)) { ?> style="background-image: url('<?php echo $newsroomBanner; ?>'); background-repeat: no-repeat; background-size: cover;  width: 100%;;" <?php } ?>>
    <!-- top menu contact strip -->
    <?php if ($action != 'newsroom' &&  $controller != 'newsroom' ) { ?>
        <div class="ew-top-strip full">
            <div class="container">
                <div class="header-inner">
                    <div class="col-s ew-top-btn-contact">
                        <div class="ew-send-release-btn float-left"><a class="orange-back" href="<?php echo SITEURL; ?>users/create-newsroom">Create Newsroom</a></div>
                        <!--div class="ew-top-contact float-left"><i class="fa fa-phone"></i> <?php //echo Configure::read('Site.phone'); 
                                                                                                ?></div-->
                    </div>
                    <div class="col-s send-release-btn">
                        <div class="ew-send-release-btn float-right">
                            <?php if (isset($role_id) && $role_id == 3) { ?>
                                <a class="orange-back" href="<?php echo SITEURL; ?>users/add-press-release">SUBMIT A PRESS RELEASE</a>
                            <?php } else { ?>
                                <!-- <a class="orange-back" href="<?php echo SITEURL; ?>plans">SUBMIT A PRESS RELEASE</a> -->
                                <a class="orange-back" href="<?php echo SITEURL; ?>plans">SUBMIT A PRESS RELEASE</a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-s ew-top-menu text-right">
                        <ul>
                            <?php if (!empty($this->Session->read('Auth.User.staff_role_id'))) { ?>
                                <li><a href="<?php echo SITEURL; ?>users/dashboard">My account</a></li>
                                <li><a href="<?php echo SITEURL; ?>users/logout">Log out</a></li>
                            <?php } else { ?>
                                <?php if ($action == 'newsroom' || $controller == 'newsroom') {  ?>
                                    <li><a href="<?php echo SITEURL; ?>"><i class="fa fa-home"></i></a></li>
                                <?php } ?>
                                <li><a href="<?php echo SITEURL; ?>users/login">Log In</a></li>
                                <li><a href="<?php echo SITEURL; ?>users/signup">Register</a></li>
                            <?php } ?>
                            <li><a href="<?php echo SITEURL; ?>contact-us/">Contact</a></li>
                            <li><a href="<?php echo SITEURL; ?>users/become-subscriber">Subscribe</a></li>
                            <li><a href="<?php echo SITEURL; ?>blog/">Blog</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <!-- End top menu contact strip -->

    <!-- logo menu search bar -->
    <?php
    if ($controller != 'newsroom') {
        if ($action != 'newsroom') { ?>
            <div class="ew-menu-logo-strip full">
                <div class="container">
                    <div class="row">
                        <div class="ew-logo col-sm-2"><a href="<?php echo SITEURL; ?>"><img src="<?php echo SITEURL; ?>website/img/emailwire-logo.jpg" alt="Emailwire Logo" /></a></div>
                        <div class="ew-menu-mid col-s">

                            <!-- desktop -->
                            <div class="navbar desktop-menu">
                                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContentDesktop" aria-controls="navbarSupportedContentDesktop" aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="navbar-toggler-icon"></span>
                                </button>
                                <div class="collapse navbar-collapse" id="navbarSupportedContentDesktop" aria-expanded="false">
                                    <ul class="nav">
                                        <!-- <li class="dropdown <?php // if(in_array($controller,['releases','pages'])&&in_array($action,array('index','newsbycategory','newsbycompany','newsbydate','newsbymsa','newsbycountry',"release"))&&!in_array($requestUrl,array('news-feeds-by-companies','news-feeds-by-msa','news-feeds-by-countries'))){echo 'active';}   
                                                                    ?>"> -->
                                        <li class="dropdown <?php if (in_array($requestUrl, array('category', 'newsrooms', 'latest-news', 'msa', 'country'))) {
                                                                echo 'active';
                                                            }   ?> <?php if (in_array($controller, ['releases', 'pages']) && in_array($action, array('newsbycategory', 'newsbycompany', 'newsbydate', 'newsbymsa', 'newsbycountry', 'newsrooms'))) {
                                                                                                                                                                                    echo 'active';
                                                                                                                                                                                } ?>">

                                            <a href="<?php echo SITEURL . 'latest-news'; ?>" class="<?php if (in_array($controller, ['releases', 'pages']) && in_array($action, array('index', 'newsbycategory', 'newsbycompany', 'newsbydate', 'newsbymsa', 'newsbycountry', 'newsrooms'))) {
                                                                                                        echo 'active';
                                                                                                    } ?> dropdown-toggle">News<b class="caret"></b></a>
                                            <ul class="dropdown-menu">
                                                <div class="container dropdown-inner">
                                                    <li class="<?php if ($requestUrl == 'latest-news') {
                                                                    echo "active";
                                                                } ?>"><a href="<?php echo SITEURL . 'latest-news'; ?>">Recent News</a></li>
                                                    <li class="<?php if ($requestUrl == 'newsrooms') {
                                                                    echo "active";
                                                                } ?>"><a href="<?php echo SITEURL . 'newsrooms'; ?>">Newsrooms</a></li>
                                                    <!--  <li class="<?php if ($requestUrl == 'company') {
                                                                            echo "active";
                                                                        } ?>"><a href="<?php echo SITEURL . 'company'; ?>">Newsrooms</a></li> -->
                                                    <li class="<?php if ($action == 'newsbycategory') {
                                                                    echo "active";
                                                                } ?>"><a href="<?php echo SITEURL . 'category'; ?>">News by Category</a></li>
                                                    <!-- <li class="<?php if ($action == 'newsbydate') {
                                                                        echo "active";
                                                                    } ?>"><a href="<?php echo SITEURL . 'news-by-date'; ?>">News by Date</a></li> -->
                                                    <li class="<?php if ($action == 'newsbymsa') {
                                                                    echo "active";
                                                                } ?>"><a href="<?php echo SITEURL . 'msa'; ?>">News by MSA</a></li>
                                                    <li class="<?php if ($action == 'newsbycountry') {
                                                                    echo "active";
                                                                } ?>"><a href="<?php echo SITEURL . 'country'; ?>">News by Country</a></li>
                                                </div>
                                            </ul>
                                        </li>

                                        <li class="dropdown <?php if ($controller == 'pages' && in_array($requestUrl, array('press-release-distribution', 'press-release-writing-services', 'press-release-service-comparison', 'press-release-tips', 'press-releases-examples', 'press-release-services', 'press-release-free', 'newsrooms-for-organizations', 'clipping-report'))) {
                                                                echo 'active';
                                                            } ?>">
                                            <a href="<?php echo SITEURL . 'press-release-distribution'; ?>" class="<?php if ($controller == 'pages' && in_array($requestUrl, array('press-release-distribution', 'press-release-tips', 'press-release-services', 'press-release-writing-services', 'press-release-free', 'newsrooms-for-organizations', 'clipping-report'))) {
                                                                                                                        echo 'active';
                                                                                                                    } ?> dropdown-toggle">Services<b class="caret"></b></a>
                                            <ul class="dropdown-menu">
                                                <div class="container dropdown-inner">
                                                    <li class="<?php if ($requestUrl == 'press-release-distribution') {
                                                                    echo "active";
                                                                } ?>"><a href="<?php echo SITEURL . 'press-release-distribution'; ?>">Press Release Distribution</a></li>
                                                    <li class="<?php if ($requestUrl == 'press-release-services') {
                                                                    echo "active";
                                                                } ?>"><a href="<?php echo SITEURL . 'press-release-services'; ?>">Press release</a></li>
                                                    <li class="<?php if ($requestUrl == 'press-release-writing-services') {
                                                                    echo "active";
                                                                } ?>"><a href="<?php echo SITEURL . 'press-release-writing-services'; ?>">Press Writing Services</a></li>
                                                </div>
                                            </ul>
                                        </li>

                                        <li style="display:none;" class="dropdown <?php if ($controller == 'pages' && in_array($requestUrl, array('press-release-distribution-services', 'press-release-tips', 'press-releases-examples', 'press-release-free', 'press-release-service-comparison'))) {
                                                                                        echo 'active';
                                                                                    } ?>">
                                            <a href="<?php echo SITEURL . 'press-release-distribution-services'; ?>" class="<?php if ($controller == 'pages' && in_array($requestUrl, array('press-release-distribution-services', 'press-release-tips', 'press-releases-examples', 'press-release-free', 'press-release-service-comparison'))) {
                                                                                                                                echo 'active';
                                                                                                                            } ?> dropdown-toggle">Resources<b class="caret"></b></a>
                                            <ul class="dropdown-menu">
                                                <div class="container dropdown-inner">
                                                    <li class="<?php if ($requestUrl == 'press-release-distribution-services') {
                                                                    echo "active";
                                                                } ?>"><a href="<?php echo SITEURL . 'press-release-distribution-services'; ?>">Press Release Distribution Services</a></li>
                                                    <li class="<?php if ($requestUrl == 'press-release-tips') {
                                                                    echo "active";
                                                                } ?>"><a href="<?php echo SITEURL . 'press-release-tips'; ?>">Press release tips</a></li>
                                                    <li class="<?php if ($requestUrl == 'press-releases-examples') {
                                                                    echo "active";
                                                                } ?>"><a href="<?php echo SITEURL . 'press-releases-examples'; ?>">Press releases examples</a></li>
                                                    <li class="<?php if ($requestUrl == 'press-release-free') {
                                                                    echo "active";
                                                                } ?>"><a href="<?php echo SITEURL . 'press-release-free'; ?>">Press release free</a></li>
                                                    <li class="<?php if ($requestUrl == 'press-release-service-comparison') {
                                                                    echo "active";
                                                                } ?>"><a href="<?php echo SITEURL . 'press-release-service-comparison'; ?>">Press release service comparison</a></li>
                                                </div>
                                            </ul>
                                        </li>

                                        <li class="dropdown <?php if (isset($this->request->pass['0']) && $this->request->pass['0'] == 'plans') {
                                                                echo 'active';
                                                            } ?>" class="<?php if (isset($this->request->pass['0']) && $this->request->pass['0'] == 'plans') {
                                                                                                                                                                            echo 'active';
                                                                                                                                                                        } ?>">
                                            <a href="<?php echo SITEURL; ?>plans" class="<?php if (isset($this->request->pass['0']) && $this->request->pass['0'] == 'plans') {
                                                                                                echo 'active';
                                                                                            } ?> dropdown-toggle">Pricing<b class="caret"></b></a>
                                            <ul class="dropdown-menu">
                                                <div class="container dropdown-inner">
                                                    <?php
                                                    if (!empty($plancategory)) {
                                                        foreach ($plancategory as $pcatslug => $pcategory) {
                                                            $actvieClass = (isset($this->request->pass['1']) && $this->request->pass['1'] == $pcatslug) ? "active" : "";
                                                            echo "<li class='$actvieClass'><a href='" . SITEURL . 'plans/' . $pcatslug . "'>$pcategory</a></li>";
                                                        }
                                                    } ?>
                                                </div>
                                            </ul>
                                        </li>

                                        <!--<li class="dropdown <?php if (($controller == 'pages' || $controller == 'users') && in_array($requestUrl, array('sales', 'support', 'contact-us'))) {
                                                                    echo 'active';
                                                                } ?>" class="<?php if ($controller == 'pages' && in_array($requestUrl, array('sales', 'support', 'contact-us'))) {
                                                                                                                                                                                                                        echo 'active';
                                                                                                                                                                                                                    } ?>">
            <a href="<?php echo SITEURL; ?>contact-us" class="<?php if ($controller == 'pages' && in_array($requestUrl, array('sales', 'support', 'contact-us'))) {
                                                                    echo 'active';
                                                                } ?> dropdown-toggle" >Contact<b class="caret"></b></a>
             <ul class="dropdown-menu">
                    <div class="container dropdown-inner">
                        <li class="<?php if ($requestUrl == 'contact-us') {
                                        echo "active";
                                    } ?>"><a href="<?php echo SITEURL; ?>contact-us">Contact</a></li>
                        <li class="<?php if ($requestUrl == 'support') {
                                        echo "active";
                                    } ?>"><a href="<?php echo SITEURL; ?>support">Support</a></li>
                    </div>
                </ul>
            </li>-->

                                        <li class="dropdown <?php if (($controller == 'pages' || $controller == 'users') && in_array($requestUrl, array('about-us-four-steps', 'client-testimonials', 'why-use-us', 'editorial-guidelines', 'support', 'contact-us'))) {
                                                                echo 'active';
                                                            } ?>" class="<?php if ($controller == 'pages' && in_array($requestUrl, array('about-us-four-steps', 'client-testimonials', 'why-use-us', 'editorial-guidelines', 'support', 'contact-us'))) {
                                                                                                                                                                                                                                                                                                echo 'active';
                                                                                                                                                                                                                                                                                            } ?>">
                                            <a href="<?php echo SITEURL; ?>about-us-four-steps" class="<?php if ($controller == 'pages' && in_array($requestUrl, array('about-us-four-steps', 'client-testimonials', 'why-use-us', 'editorial-guidelines', 'support', 'contact-us'))) {
                                                                                                            echo 'active';
                                                                                                        } ?> dropdown-toggle">About<b class="caret"></b></a>
                                            <ul class="dropdown-menu">
                                                <div class="container dropdown-inner">
                                                    <!--<li class="<?php if ($requestUrl == 'about-us-four-steps') {
                                                                        echo "active";
                                                                    } ?>"><a href="<?php echo SITEURL; ?>contact-us">About</a></li>-->
                                                    <li class="<?php if ($requestUrl == 'contact-us') {
                                                                    echo "active";
                                                                } ?>"><a href="<?php echo SITEURL; ?>contact-us">Contact Us</a></li>
                                                    <li class="<?php if ($requestUrl == 'editorial-guidelines') {
                                                                    echo "active";
                                                                } ?>"><a href="<?php echo SITEURL; ?>editorial-guidelines">Editorial Guidelines</a></li>
                                                    <li class="<?php if ($requestUrl == 'support') {
                                                                    echo "active";
                                                                } ?>"><a href="<?php echo SITEURL; ?>support">Support</a></li>
                                                    <li class="<?php if ($requestUrl == 'why-use-us') {
                                                                    echo "active";
                                                                } ?>"><a href="<?php echo SITEURL; ?>why-use-us">Why Use Us</a></li>
                                                    <li class="<?php if ($requestUrl == 'client-testimonials') {
                                                                    echo "active";
                                                                } ?>"><a href="<?php echo SITEURL; ?>client-testimonials">Testimonials</a></li>
                                                </div>
                                            </ul>
                                        </li>

                                    </ul>
                                </div>
                            </div>

                            <!-- End desktop -->

                            <!-- mobile -->

                            <div class="navbar mobile-menu">
                                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContentMobile" aria-controls="navbarSupportedContentMobile" aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="navbar-toggler-icon"></span>
                                </button>
                                <div class="collapse navbar-collapse" id="navbarSupportedContentMobile" aria-expanded="false">
                                    <ul class="nav">

                                        <li class="dropdown <?php if ($controller == 'releases' && in_array($action, array('index', 'newsbycategory', 'newsbycompany', 'newsbydate', 'newsbymsa', 'newsbycountry', "release")) && !in_array($requestUrl, array('news-feeds-by-companies', 'news-feeds-by-msa', 'news-feeds-by-countries'))) {
                                                                echo 'active';
                                                            }   ?>">
                                            <a id="news_menu" clk="0" href="<?php echo SITEURL . 'latest-news'; ?>" class="<?php if ($controller == 'releases' && in_array($action, array('index', 'newsbycategory', 'newsbycompany', 'newsbydate', 'newsbymsa', 'newsbycountry'))) {
                                                                                                                                echo 'active';
                                                                                                                            } ?> dropdown-toggle">News<b class="caret"></b></a>
                                            <ul class="dropdown-menu">
                                                <div class="container dropdown-inner">
                                                    <li class="<?php if ($action == 'index') {
                                                                    echo "active";
                                                                } ?>"><a href="<?php echo SITEURL . 'latest-news'; ?>">Recent News</a></li>
                                                    <li class="<?php if ($requestUrl == 'newsrooms') {
                                                                    echo "active";
                                                                } ?>"><a href="<?php echo SITEURL . 'newsrooms'; ?>">Newsrooms</a></li>
                                                    <li class="<?php if ($action == 'newsbycategory') {
                                                                    echo "active";
                                                                } ?>"><a href="<?php echo SITEURL . 'category'; ?>">News by Category</a></li>
                                                    <!-- <li class="<?php if ($action == 'newsbydate') {
                                                                        echo "active";
                                                                    } ?>"><a href="<?php echo SITEURL . 'news-by-date'; ?>">News by Date</a></li> -->
                                                    <li class="<?php if ($action == 'newsbymsa') {
                                                                    echo "active";
                                                                } ?>"><a href="<?php echo SITEURL . 'msa'; ?>">News by MSA</a></li>
                                                    <li class="<?php if ($action == 'newsbycountry') {
                                                                    echo "active";
                                                                } ?>"><a href="<?php echo SITEURL . 'country'; ?>">News by Country</a></li>
                                                </div>
                                            </ul>
                                        </li>

                                        <li class="dropdown <?php if ($controller == 'pages' && in_array($requestUrl, array('press-release-distribution-services', 'press-writing-services', 'newsrooms-for-organizations', 'clipping-report'))) {
                                                                echo 'active';
                                                            } ?>">
                                            <a id="service_menu" clk="0" href="<?php echo SITEURL . 'press-release-distribution-services'; ?>" class="mobile <?php if ($controller == 'pages' && in_array($requestUrl, array('press-release-distribution-services', 'press-writing-services', 'newsrooms-for-organizations', 'clipping-report'))) {
                                                                                                                                                                echo 'active';
                                                                                                                                                            } ?> dropdown-toggle">Services<b class="caret"></b></a>
                                            <ul class="dropdown-menu">
                                                <div class="container dropdown-inner">
                                                    <li class="<?php if ($requestUrl == 'press-release-distribution-services') {
                                                                    echo "active";
                                                                } ?>"><a href="<?php echo SITEURL . 'press-release-distribution-services'; ?>">Press Release Distribution Services</a></li>
                                                    <li class="<?php if ($requestUrl == 'press-writing-services') {
                                                                    echo "active";
                                                                } ?>"><a href="<?php echo SITEURL . 'press-writing-services'; ?>">Press Writing Services</a></li>
                                                    <li class="<?php if ($requestUrl == 'newsrooms-for-organizations') {
                                                                    echo "active";
                                                                } ?>"><a href="<?php echo SITEURL . 'newsrooms-for-organizations'; ?>">Newsrooms for Organizations </a></li>
                                                    <li class="<?php if ($requestUrl == 'clipping-report') {
                                                                    echo "active";
                                                                } ?>"><a href="<?php echo SITEURL . 'clipping-report'; ?>">Clipping Report</a></li>
                                                </div>
                                            </ul>
                                        </li>

                                        <li class="dropdown <?php if (isset($this->request->pass['0']) && $this->request->pass['0'] == 'plans') {
                                                                echo 'active';
                                                            } ?>" class="<?php if (isset($this->request->pass['0']) && $this->request->pass['0'] == 'plans') {
                                                                                                                                                                            echo 'active';
                                                                                                                                                                        } ?>">
                                            <a id="price_menu" clk="0" href="<?php echo SITEURL . 'plans'; ?>" class="<?php if (isset($this->request->pass['0']) && $this->request->pass['0'] == 'plans') {
                                                                                                                        echo 'active';
                                                                                                                    } ?> dropdown-toggle">Pricing<b class="caret"></b></a>
                                            <ul class="dropdown-menu">
                                                <div class="container dropdown-inner">
                                                    <?php
                                                    if (!empty($plancategory)) {
                                                        foreach ($plancategory as $pcatslug => $pcategory) {
                                                            $actvieClass = (isset($this->request->pass['1']) && $this->request->pass['1'] == $pcatslug) ? "active" : "";
                                                            echo "<li class='$actvieClass'><a href='" . SITEURL . 'plans/' . $pcatslug . "'>$pcategory</a></li>";
                                                        }
                                                    } ?>
                                                </div>
                                            </ul>
                                        </li>
                                        <li class="dropdown <?php if ($controller == 'pages' && in_array($requestUrl, array('sales', 'support', 'contact-us'))) {
                                                                echo 'active';
                                                            } ?>" class="<?php if ($controller == 'pages' && in_array($requestUrl, array('sales', 'support', 'contact-us'))) {
                                                                                                                                                                                        echo 'active';
                                                                                                                                                                                    } ?>">
                                            <a id="contact_menu" href="<?php echo SITEURL . 'contact-us'; ?>" class="mobile <?php if ($controller == 'pages' && in_array($requestUrl, array('sales', 'support', 'contact-us'))) {
                                                                                                                                echo 'active';
                                                                                                                            } ?> dropdown-toggle">Contact<b class="caret"></b></a>
                                            <ul class="dropdown-menu">
                                                <div class="container dropdown-inner">
                                                    <li class="<?php if ($requestUrl == 'sales') {
                                                                    echo "active";
                                                                } ?>"><a href="<?php echo SITEURL; ?>sales">Sales</a></li>
                                                    <li class="<?php if ($requestUrl == 'support') {
                                                                    echo "active";
                                                                } ?>"><a href="<?php echo SITEURL; ?>support">Support</a></li>
                                                </div>
                                            </ul>
                                        </li>

                                        <li><a class="orange-back" href="<?php echo SITEURL; ?>users/create-newsroom">Create Newsroom</a></li>

                                        <?php if (!empty($this->Session->read('Auth.User.staff_role_id'))) { ?>
                                            <li><a href="<?php echo SITEURL; ?>users/dashboard">My account</a></li>
                                            <li><a href="<?php echo SITEURL; ?>users/logout">Log out</a></li>
                                        <?php } else { ?>
                                            <?php if ($action == 'newsroom' || $controller == 'newsroom') {  ?>
                                                <li><a href="<?php echo SITEURL; ?>"><i class="fa fa-home"></i> </a></li>
                                            <?php } ?>
                                            <li><a href="<?php echo SITEURL; ?>users/login">Log In</a></li>
                                            <li><a href="<?php echo SITEURL; ?>users/signup">PR Firm/Client Registration</a></li>
                                        <?php } ?>
                                        <li><a href="<?php echo SITEURL; ?>users/become-subscriber">Journalists & Bloggers</a></li>
                                        <li><a href="<?php echo SITEURL; ?>blog/">Blog</a></li>

                                        <?php if (isset($role_id) && $role_id == 3) { ?>
                                            <li> <a class="orange-back" href="<?php echo SITEURL; ?>users/add-press-release">Send a Press release</a></li>
                                        <?php } else { ?>
                                            <li><a class="orange-back" href="<?php echo SITEURL; ?>plans">Send a Press release</a></li>
                                        <?php } ?>

                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="ew-top-search-form col-sm-3">
                            <form class="navbar-form" action="<?php echo SITEURL; ?>search" role="search">
                                <div class="custom-header-search input-group add-on">
                                    <input class="form-control" placeholder="Search news" name="srch-term" id="srch-term" type="search">
                                    <div class="input-group-btn">
                                        <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    <?php }
    } ?>
</header>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        $("#navbarSupportedContentMobile .dropdown a.dropdown-toggle").click(function(event) {
            event.preventDefault();
            var clk = $(this).attr('clk');
            if (clk == 1) {
                var href = $(this).attr('href');
                location.replace(href);
            } else {
                $("#navbarSupportedContentMobile .dropdown a.dropdown-toggle").attr('clk', 0)
                $(this).attr('clk', 1);
            }
        });
    });
</script>