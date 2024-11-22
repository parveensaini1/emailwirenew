<div class="container brandContainer" style="margin-top: 4vw">
        <div
          style="background: none"
          class="main-color text-black rounded-top-2"
        >
          <h5 class="cm_title">
            Brands we’ve published and distributed their news
          </h5>
        </div>
      </div>

    <div class="container mb-5">
        <div class="slidertwo">
          <div class="trending_slider">
            <img class="trending_image" src="<?php echo SITEURL?>/webc/img/logos (1)/1.1.png" alt="" />
          </div>
          <div class="trending_slider">
            <img class="trending_image" src="<?php echo SITEURL?>/webc/img/logos (1)/2.1.png" alt="" />
          </div>
          <div class="trending_slider">
            <img class="trending_image" src="<?php echo SITEURL?>/webc/img/logos (1)/3.1.png" alt="" />
          </div>
          <div class="trending_slider">
            <img class="trending_image" src="<?php echo SITEURL?>/webc/img/logos (1)/4.1.png" alt="" />
          </div>
          <div class="trending_slider">
            <img class="trending_image" src="<?php echo SITEURL?>/webc/img/logos (1)/5.1.png" alt="" />
          </div>
          <div class="trending_slider">
            <img class="trending_image" src="<?php echo SITEURL?>/webc/img/logos (1)/6.2.png" alt="" />
          </div>
          <div class="trending_slider">
            <img class="trending_image" src="<?php echo SITEURL?>/webc/img/logos (1)/7.2.png" alt="" />
          </div>
          <div class="trending_slider">
            <img class="trending_image" src="<?php echo SITEURL?>/webc/img/logos (1)/8.1.png" alt="" />
          </div>
        </div>
      </div>

<?php if(!empty($featured_arr)){  ?>
<!-- Featured  News Section -->
      <section class="container mt-5 latestnews">
        <div
          style="background: none"
          class="main-color text-black rounded-top-2"
        >
          <h5 class="cm_title">Featured News</h5>
        </div>
        <div class="posts">
        <?php
            $countfeaturedpr=count($featured_arr); 
            foreach($featured_arr as $index => $featurepr){ 

            if($index==0){
         ?>
          <div class="posts__item posts__item--main">
            <div class="posts__image">
              <?php  echo $this->Post->getPrSingleImage($featurepr['PressImage'],'crop','526','350');?>
            </div>
            <div
              style="position: initial; padding: 15px 20px"
              class="posts__information"
            >
              <div class="posts__date"><?php echo date($dateformate,strtotime($featurepr['PressRelease']['release_date'])) ?></div>

              <div class="posts__title">
                <a style="color: #000" class="cm_link_hyper" href="<?php echo SITEURL."release/".$featurepr['PressRelease']['slug'];?>"
                  ><?php echo $featurepr['PressRelease']['title']?></a
                >
              </div>
              <a
                style="color: black; text-decoration: none; font-size: 14px"
                class="ms-auto mb-0"
                ><?php echo $featurepr['PressRelease']['summary']?>
              </a>
              <div
                class="posts__author d-flex justify-content-between align-items-center mt-4"
              >
              <?php if($featurepr['Company']['logo']){?>
                <a href="#">
                    <?php echo $this->Post->getNewsroomLogo($featurepr['Company']['logo_path'],$featurepr['Company']['logo'],$featurepr['Company']['slug'],$featurepr['Company']['status']);?>
                </a>
            <?php } ?>
                
                <a class="see_more_btn_outline py-1 text-decoration-none" href="<?php echo SITEURL."release/".$featurepr['PressRelease']['slug'];?>" 
                  >Read press release</a
                >
              </div>
            </div>
          </div>
      <?php } if($index==1  || $index==2) { ?>

        <?php if($index==1) {?>
          <div class="posts__item1">
        <?php } ?>
            <div class="posts__item__card">
              <div class="posts__image">
                <a class="cm_link_hyper" href="<?php echo SITEURL."release/".$featurepr['PressRelease']['slug'];?>">
                    <?php  echo $this->Post->getPrSingleImage($featurepr['PressImage'],'crop','500','333');?>
                </a>
              </div>
              <div class="posts__information">
                <div class="posts__date"><?php echo date($dateformate,strtotime($featurepr['PressRelease']['release_date'])) ?></div>
                <div class="posts__title mt-auto">
                  <a class="cm_link_hyper" href="<?php echo SITEURL."release/".$featurepr['PressRelease']['slug'];?>"
                    ><?php echo $featurepr['PressRelease']['title']?></a
                  >
                </div>
                <a
                  style="color: black; text-decoration: none; font-size: 14px"
                  class="mt-2"
                  ><?php echo $featurepr['PressRelease']['summary']?>
                </a>
                <div
                  class="posts__author d-flex justify-content-between align-items-center mt-3"
                >
                  <a href="#">
                    <?php echo $this->Post->getNewsroomLogo($featurepr['Company']['logo_path'],$featurepr['Company']['logo'],$featurepr['Company']['slug'],$featurepr['Company']['status']);?>
                  </a>
                  <a class="see_more_btn_outline py-1 text-decoration-none" href="<?php echo SITEURL."release/".$featurepr['PressRelease']['slug'];?>" 
                    >Read press release</a
                  >
                </div>
              </div>
            </div>
            
            <?php if($index==1) {?>
            </div>
            <?php } ?>
      <?php } ?>

        <?php if($index>2) {?>
          <div class="posts__item">
            <div class="posts__image">
              <a class="cm_link_hyper" href="<?php echo SITEURL."release/".$featurepr['PressRelease']['slug'];?>">
                    <?php  echo $this->Post->getPrSingleImage($featurepr['PressImage'],'crop','500','333');?>
                </a>
            </div>
            <div class="posts__information">
              <div class="posts__date"><?php echo date($dateformate,strtotime($featurepr['PressRelease']['release_date'])) ?></div>
              <div class="posts__title">
                <a class="cm_link_hyper" href="<?php echo SITEURL."release/".$featurepr['PressRelease']['slug'];?>"
                    ><?php echo $featurepr['PressRelease']['title']?></a
                  >
              </div>
              <a
                style="color: black; text-decoration: none; font-size: 14px"
                class="ms-auto mb-0 mt-4"
                ><?php echo $featurepr['PressRelease']['summary']?>
              </a>
              <div
                class="posts__author d-flex justify-content-between align-items-center"
              >
                <a href="#">
                  <?php echo $this->Post->getNewsroomLogo($featurepr['Company']['logo_path'],$featurepr['Company']['logo'],$featurepr['Company']['slug'],$featurepr['Company']['status']);?>
                </a>
                <a class="see_more_btn_outline py-1 text-decoration-none" href="<?php echo SITEURL."release/".$featurepr['PressRelease']['slug'];?>" 
                  >Read press release</a
                >
              </div>
            </div>
          </div>
      <?php } } ?>
        </div>
        <div class="d-flex justify-content-end px-2">
          <p class="px-2 py-2 main-color rounded-1 d-inline-flex">
            <a href="<?php echo SITEURL.'featured-press-release'; ?>"> View More News </a>
          </p>
        </div>
      </section>
<?php } ?>


<!-- Latest News Room  -->
    <section class="container mt-5">
        <div class="rounded-top-2">
          <h5 class="cm_title">Latest Newsrooms</h5>
        </div>

        <div id="cards-container" class="row mt-4">

        <?php foreach ($newsrooms as $index => $newsroom) { ?>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-5">
                <a class="d-block text-black text-decoration-none" href="<?php echo SITEURL."newsroom/".$newsroom['Company']['slug'];?>">
                  <div class="ew-latest-news-inner d-flex flex-column p-2">
                    <div>
                      <div class="ew-lastest-news-img-single full">
                        <div class="newsroom_inner">
                            <?php 
                        if ($newsroom['Company']['logo']!= '') {
                            
                          echo $this->Post->getLazyloadImage(SITEURL.'files/company/logo/'.$newsroom['Company']['logo_path'].'/'.$newsroom['Company']['logo'],['width'=>"500",'height'=>"333", 'id'=>'prev_logo_image','class'=>'object-fit-fill'],SITEURL.'newsroom/'.$newsroom['Company']['slug']);

                            } else {
                           echo $this->Html->image('no_image.jpeg', array('class' => 'object-fit-fill',"id"=>"prev_logo_image", "width"=>"100%"));
                            }
                        ?>

                          
                        </div>
                      </div>
                      <div class="ew-lastest-news-single-content d-flex flex-column justify-content-between align-items-end">
                        <div>
                          <span class="ew-link-title">
                            <?php echo $this->Post->get_company($newsroom['Company']['name'],$newsroom['Company']['slug'],$newsroom['Company']['status']); ?>

                           </span>
                          <div class="prsummary mt-2">
                            <p><?php
                                echo $description = strip_tags($this->Post->wordLimit($newsroom['Company']['description'],'',8));                                
                            ?></p>
                          </div>
                        </div>
                      </div> 
                    </div>
                  </div>
                </a>
              </div>
        <?php } ?>
        </div>

        <div>
          <h3>Why a Newsroom?</h3>
          <h6 class="fw-normal">
            A newsroom serves as a centralized repository for all
            company-related news, press releases, and updates. This ensures that
            stakeholders, including customers, investors, and the media, have
            easy access to the latest information about your company.
          </h6>
        </div>
    </section>


<?php if(!empty($latestPr)){?>
<!-- Latest news section -->
      <section class="container mt-5">
        <div class="rounded-top-2">
          <h5 class="cm_title">Latest News</h5>
        </div>

        <div class="posts">
            <?php foreach ($latestPr as $loop1=> $latest) { ?>
          <div class="posts__item">
            <div class="posts__image">
                <a href="<?php echo SITEURL."release/".$latest['PressRelease']['slug'];?>">
              <?php  echo $this->Post->getPrSingleImage($latest['PressImage'],'crop','333','215','0','0','0'); ?>
          </a>
            </div>
            <div class="posts__information">
              <div class="posts__date"><?php echo date($dateformate,strtotime($latest['PressRelease']['release_date'])) ?></div>
              <div class="posts__title">
                <a class="cm_link_hyper" href="<?php echo SITEURL."release/".$latest['PressRelease']['slug'];?>"
                  ><?php echo $latest['PressRelease']['title']; ?></a
                >
              </div>
              <a
                style="color: black; text-decoration: none; font-size: 14px"
                class="ms-auto mb-0 mt-4"
                ><?php echo $this->Post->wordLimit($latest['PressRelease']['summary'],$latest['PressRelease']['slug'],35,'homepage');?>
              </a>
              <div
                class="posts__author d-flex justify-content-between align-items-center"
              >
                <a href="#">
                  <?php echo $this->Post->getNewsroomLogo($latest['Company']['logo_path'],$latest['Company']['logo'],$latest['Company']['slug'],$latest['Company']['status']);?>
                </a>
                <a class="see_more_btn_outline py-1 text-decoration-none" href="<?php echo SITEURL."release/".$latest['PressRelease']['slug'];?>" 
                  >Read press release</a
                >
              </div>
            </div>
          </div>
      <?php } ?>
      </div>
  </section>
<?php } ?>


<div class="container pt-md-5 my-md-5">
        <div class="bg-white text-black rounded-top-2 mb-2">
          <h5 class="py-0 my-0 cm_title">Newswires Network</h5>
        </div>
        <p style="font-size: 16px">
          EmailWire uses the following newswire services to provide press
          release distribution in Africa , Asia Europe , Middle East and North
          Africa(MeNA).
        </p>
        <div class="slidertwooo">
          <div class="trending_slider">
            <img
              class="trending_image"
              src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTHsdSdfZek_uOflgdzV8eCwnLVB8Yx37xL-A&s"
              alt=""
            />
            <p class="newsWireText">
              AsiaNewswire.net carries press releases dessiminated to media in
              Asia Pasific, South and Southeast Asian countries.
            </p>
          </div>
          <div class="trending_slider">
            <img
              class="trending_image"
              src="https://i0.wp.com/africanewswire.net/wp-content/uploads/2023/01/ArabNewswire-Logo-Sized-for-Web.png?fit=430%2C237&ssl=1"
              alt=""
            />
            <p class="newsWireText">
              ArabNewswire.com publishes press resleases in Arabic, English and
              French for media in GCC/MENA region.
            </p>
          </div>
          <div class="trending_slider">
            <img class="trending_image" src="<?php echo SITEURL?>/webc/img/image8.png" alt="" />
            <p class="newsWireText">
              AfricaNewswire.net™ distributes press releases in Arabic , English
              , French and Swahili to media in Africa
            </p>
          </div>
          <div class="trending_slider">
            <img class="trending_image" src="<?php echo SITEURL?>/webc/img/image15.png" alt="" />
            <p class="newsWireText">
              Publishes news releases distributed in DACH , Benelux, UK and the
              entire Europe.
            </p>
          </div>
          <div class="trending_slider">
            <img
              class="trending_image"
              src="https://i0.wp.com/africanewswire.net/wp-content/uploads/2023/01/ArabNewswire-Logo-Sized-for-Web.png?fit=430%2C237&ssl=1"
              alt=""
            />
            <p class="newsWireText">
              ArabNewswire.com publishes press resleases in Arabic, English and
              French for media in GCC/MENA region.
            </p>
          </div>
        </div>
      </div>
