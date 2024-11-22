<?php echo $this->element('popup_company_alert'); ?>
<div class="full ew-sub-page">
    <div class="container">
        <div class="full ew-account-page margin-bottom20">
            <div class="row">
                <!-- Email Wire Plans -->
                    <div class="col-lg-12"><div class="ew-title full"><?php echo $title_for_layout;?></div></div>
                    <div class="col-sm-12 ew-pricing-plans-block">
                        <!-- <p class="margin-bottom30">Create awareness, acquire and engage your target audience through news distribution online; reach regional journalists in local, national or global media outlets with these various plans:</p> -->
                        <div class="ew-pricing-blocks full">
                            <ul class="nav nav-tabs " id="myTab" role="tablist"> <!-- text-center -->
                                <?php 
                                    if(!empty($plancategory)){
                                        foreach ($plancategory as $pcatslug => $pcategory) {
                                            $activeClass=($categorySlug==$pcatslug)?"active":"";
                                            echo "<li class='nav-item'><a class='nav-link $activeClass' href='".SITEURL.'plans/'.$pcatslug."'>$pcategory</a></li>";
                                        }
                                        unset($activeClass);
                                    } 
                                ?>
                            </ul>
                        <div id="main-content ss" class="row">
                            <div id="content" class="col-sm-8 content">
                                <div class="tab-content" id="myTabContent">
                                   <div class="tab-pane show active" id="onlinedistribution" role="tabpanel" aria-labelledby="home-tab">
                                    <?php if(!empty($data[$model]['description'])){?>
                                        <div class="full ew-content-title margin-bottom20"><?php echo $data[$model]['description'];?>
                                        </div>
                                    <?php } ?>

                                    <?php  if(!empty($data_array)){
                                        foreach ($data_array as $key => $subcategory){
                                            if(!empty($subcategory['Plan'])){
                                        ?>

                                        <div class="ew-shadow-dive-table full">
                                            <div class="ew-pricing-text ew-pricing-plan-left">
                                                <span class="ew-psp-text-t"><?php echo $subcategory[$model]['name'];?></span>
                                                <!-- <i data-toggle="tooltip" data-placement="right" title="Pricing Text" class="fa fa-info"></i> -->
                                                <?php  if(!empty($subcategory[$model]['description'])){?>
                                                    <div class="ew-psp-sup-text full">
                                                        <?php echo $subcategory[$model]['description'];;?>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <div class="ew-pricing-plan-mid">
                                                <div class="ew-pricing-plan-table full">
                                                   <?php 
                                                   if($categorySlug=='media-planning'){
                                                    echo '<div class="ew-pricing-plan-th-head full">
                                                        <div class="ew-pricing-plan-head">Media plans</div>
                                                    </div>';
                                                   }else{
                                                    echo $this->Custom->pricePlanHeader($subcategory[$model]['word_limit'],$subcategory[$model]['is_translated']);
                                                   }
                                                    foreach ($subcategory['Plan'] as $key => $plan) {
                                                        $plan_id=$plan['id'];
                                                        echo $this->Custom->pricePlanRows($plan,$subcategory[$model]['word_limit']);
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php 
                                            }
                                        }
                                    }
                                    ?>
                                    </div>
                                </div>
                            </div>
                            <div id="sidebar" class="col-sm-4 ew-sidebar">
                                <div class="sidebar__inner">
                                    <?php echo $this->element('signup_sidebar'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $this->Html->script(array('ResizeSensor'));
?>
<div class="cart-floating">
<div class="cart-icon">
<span id="cart-count" class="cart-count"><?php echo (isset($cart_plans['plans'])&&!empty($cart_plans['plans']))?count($cart_plans['plans']):"0"; ?></span>
</div>
</div>
<script type="text/javascript">
var windowWidth = $(window).width();
if (windowWidth > 768) {
    var sidebar = new StickySidebar('#sidebar', {
        topSpacing: 40,
        bottomSpacing: 20,
        containerSelector: '#onlinedistribution',
        innerWrapperSelector: '.ew-sidebar-inner'
    });
}
    
$(document).ready(function(){
  $(".cart-floating .cart-icon").click(function(){
    $(".col-sm-4.ew-sidebar, .cart-floating .cart-icon").toggleClass("active");
  });
});
$(".close-tab span").click(function(){
  $(".ew-sidebar, .cart-floating .cart-icon").removeClass("active");
});

</script>