<?php
if ($controller != "releases" && $action != "release") {
  $bodyClass = $this->Custom->bodyclass($this->here);
}
/*
if ($this->params['controller'] == 'Pages' && $this->params['action'] == 'newsroom' && isset($data['Company']['banner_path']) && !empty($data['Company']['banner_path'])) {
  $banner = SITEURL . 'files/company/banner/' . $data['Company']['banner_path'] . '/' . $data['Company']['banner_image']; ?>
  <div class="header-newsroom ew-banner-newsroom full" style="background-image: url('<?php echo $banner;?>');;">
    <!-- <img src="<?php // echo $banner; ?>" style="width:100%;"> -->
  </div>
<?php } else
*/
if ($this->params['controller'] == 'Pages' && isset($bodyClass) && $bodyClass == 'home') { ?>
  <?php echo $this->Html->css(array('docs.theme.min'));
  ?>


  <section id="demos" class="desktop_slider">
    <div class="row">
      <div class="large-12 columns">
        <div class="fadeOut owl-carousel owl-theme desktopbanner">
          <div class="item" style="background:#ffecda; padding-bottom:20px;">
            <div class="banner_block" id="banner_two">
              <div class="banner_container">
                <h1>Press Release Distribution<br>with Guaranteed Results!™</h1>
                <h4>Four easy steps to publish and distribute your press release.</h4>
                <div class="banner_content">
                  <ul>
                    <li><a href="<?php echo SITEURL; ?>users/create-newsroom"><img src="<?php echo SITEURL . 'img/one.png'; ?>" /> <span>CREATE NEWSROOM</span></a></li>
                    <li><a href="<?php echo SITEURL; ?>plans/online-distribution"><img src="<?php echo SITEURL . 'img/two.png'; ?>" /> <span>PUBLISH YOUR NEWS</span></a></li>
                    <li><a href="<?php echo SITEURL; ?>users/create-newsroom"><img src="<?php echo SITEURL . 'img/three.png'; ?>" /> <span>BE DISCOVERED</span></a></li>
                    <li><a href="<?php echo SITEURL; ?>users/create-newsroom"><img src="<?php echo SITEURL . 'img/four.png'; ?>" /> <span>GET RESULTS</span></a></li>
                  </ul>
                </div>
                <div class="button_submit"><a href="<?php echo SITEURL;?>users/create-newsroom">Create Newsroom</a></div>
              </div>
            </div>
          </div>
        </div>
        <script>
          jQuery(document).ready(function($) {
            $('.desktopbanner').owlCarousel({
              items: 1,
              autoplay: true,
              // animateOut: 'fadeOut',
              autoplayTimeout: 5000,
              autoplayHoverPause: true,
              touchDrag: false,
              mouseDrag: false,
              loop: false,
              autoHeight: true,
              nav: true,
              margin: 0,
            });
          });
        </script>
      </div>
    </div>
  </section>

<?php } elseif (isset($banner) && !empty($banner)) { ?>

 
  <div style="background-image:url('<?php echo $banner; ?>'); background-size: cover !important; height: 330px;" class="header-newsroom ew-banner-newsroom full">
    <!-- <div class="ew-banner-content text-center"><?php // echo $title_for_layout; ?></div> -->
  </div>

<?php } ?>