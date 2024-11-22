<?php

/**

 * The template for displaying the footer

 *

 * Contains the closing of the #content div and all content after.

 *

 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials

 *

 * @package WordPress

 * @subpackage Twenty_Nineteen

 * @since 1.0.0

 */



?>



	</div><!-- #content -->

	

	<?php dynamic_sidebar( 'sidebar-6' ); ?>

	

	<footer class="ew-footer full" id="footernewsroom">

    <div class="container">   

        <!-- logo and social icon -->

        <div class="full ew-footer-logo-social margin-bottom30">

            <div class="row">

                <div class="col-sm-6 ew-footer-logo">

                    <a href="#"><img src="http://netleon.in/email_wire/website/img/group-web-media-logo.png" alt=""></a>    

                </div> 

            </div>        

        </div>



        <!-- End logo and social icon -->

        <div class="full ew-contact-map">

        <div class=""><img src="https://newsite.emailwire.com/blog/wp-content/uploads/2021/06/group-web-media-logo.png" alt=""/></div>

            <div class="row">       

                <!-- Contact Us -->

                <div class="col-sm-8 ew-contact-block">

                    <div class="row">

                        <div class="col-sm-6">



                            <div class="ew-title full">About</div>

                            <ul class="footer-menu dropdown-inner"> 

                                <li class=""><a href="https://newsite.emailwire.com/why-use-us">Why use EmailWire</a></li>

                                <li class=""><a href="https://newsite.emailwire.com/press-release-tips">Press release tips</a></li>

                                <li class=""><a href="https://newsite.emailwire.com/podcast">Podcast</a></li>

                                <li class=""><a href="https://newsite.emailwire.com/contact-us">Contact us</a></li>

                            </ul> 

                        </div>

                        <div class="col-sm-6">

                            <div class="ew-title full">RSS Feed</div>

                            <ul class="footer-menu dropdown-inner"> 

                                <li class=""><a href="https://newsite.emailwire.com/news-feeds-by-categories">Newsfeed by Categories</a></li>

                                <li class=""><a href="https://newsite.emailwire.com/news-feeds-by-newsroom">Newsfeed by Companies</a></li>

                                <li class=""><a href="https://newsite.emailwire.com/news-feeds-by-msa">Newsfeed by MSA</a></li>

                                <li class=""><a href="https://newsite.emailwire.com/news-feeds-by-countries">Newsfeed by Country</a></li>

                            </ul> 

                        </div>

                    </div>



                    <div class="ew-title full">Contact Us</div>

                    <div class="full ew-phone-wh-skype">

                    <ul>

                        <li><i class="fa fa-phone" aria-hidden="true"></i> <span>832-716-2363</span></li>

                        <li><i class="fab fa-whatsapp" aria-hidden="true"></i> <span>832-716-2363</span></li>

                        <li><a href="skype:groupwebmedia?chat"><i class="fab fa-skype" aria-hidden="true"></i> <span>groupwebmedia</span></a></li>    

                    </ul>

                    </div>

                </div>        

                <!-- End Contact Us -->   

                <!-- Address and map -->

                <div class="col-sm-4 ew-contact-address-map">

                    <!-- title -->  

                    <div class="ew-title full">Address &amp; Location</div>  

                    <!-- End title --> 

                    <p><strong>EmailWire&trade; dba GroupWeb Media LLC</strong><br>

Houston, TX 77060<br>

Tel: 832-716-2363</p>

                     <div class="col-sm-12 ew-footer-social ">

                        <ul>

                            <li class="facebook"><a target="_blank" href="https://www.facebook.com/emailwire"></a></li>

                            <li class="twitter"><a target="_blank" href="https://twitter.com/emailwire"></a></li>

                            <li class="youtube"><a target="_blank" href="https://www.youtube.com/user/EmailWire"></a></li>

                            <li class="pintrest"><a target="_blank" href="https://in.pinterest.com/emailwire/"></a></li>    

                        </ul>

                    </div> 

                   <!--  <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d221152.3731887279!2d-95.54484898232744!3d29.99362880592412!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8640ca64d6c0a605%3A0xcf9db6e2c0030ddd!2sGroupWeb+Media+LLC+(EMAILWIRE.COM)+-+Press+Release+Distribution+Services!5e0!3m2!1sen!2sin!4v1550048677693" width="600" height="300" frameborder="0" style="border:0" allowfullscreen></iframe>    --> 

                </div>    

                <!-- End Address and map --> 

            </div>        

        </div>     

    </div> 

    <!-- Footer bottom -->



    <div class="full text-center ew-footer-copyright">

        <div class="container"> 

            <div class="row">

                <div class="col-lg-12" id="newsroom_text_nsr">

EmailWire is the global newswire with press release distribution, writing and consultation services. <br>Amplify your news releases to your target audience, local or global on any media platform. Press release distribution with guaranteed results™</br>

GroupWeb Media LLC <?php auto_copyright("2006"); ?> All rights reserved.

                </div>

            </div>

        </div>    

    </div>       



    <!-- End Footer bottom -->        

</footer><!-- #site-footer -->

	

	<!-- <footer id="colophon" class="site-footer">

		

		<?php //get_template_part( 'template-parts/footer/footer', 'widgets' ); ?>

		

		<div class="site-info">			

			 

			<a class="site-name" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">EmailWire.Com™</a> is a global newswire services of GroupWeb Media LLC. Press release distribution with guaranteed reslults™ &copy; <?php auto_copyright("2019"); ?> All rights reserved.

			

		</div>

	</footer> #colophon -->



</div><!-- #page -->



<?php function auto_copyright($year = 'auto'){ ?>

   <?php if(intval($year) == 'auto'){ $year = date('Y'); } ?>

   <?php if(intval($year) == date('Y')){ echo intval($year); } ?>

   <?php if(intval($year) < date('Y')){ echo intval($year) . ' - ' . date('Y'); } ?>

   <?php if(intval($year) > date('Y')){ echo date('Y'); } ?>

<?php } ?>





<div id="searchr">	

	<form id="search-custform" role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">

		<button type="submit" class="search-submit"><span class="screen-reader-text"><?php echo _x( 'Search', 'submit button', 'twentysixteen' ); ?></span></button>

		<label>

			<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label', 'twentysixteen' ); ?></span>

			<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'twentysixteen' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />

		</label>	

	</form>

</div>



<?php if ( is_active_sidebar( 'sidebar-4' ) ) : ?>

<style>

body { margin-bottom: 115px; }

</style>

<div class="fixed-newsletter">

	<div class="container">

		<div class="row">

			<div class="col-md-12">

				<div id="close-newsletter"><i class="fas fa-times"></i></div>

				<?php dynamic_sidebar( 'sidebar-4' ); ?>

			</div>

		</div>

	</div>

</div>

<?php endif; ?>



<script type='text/javascript' src="<?php bloginfo('template_url'); ?>/plugin/jquery.min.js"></script>

<script>

// Search Overlay

$(function () {

    $('.search-menu-box').on('click', function(event) {

        event.preventDefault();

        $('#searchr').addClass('open');

        //$('#searchr > form > input[type="search"]').focus();

        //$('.search-field').focus();

    });

    

    $('#searchr, #searchr button.closer').on('click keyup', function(event) {

        if (event.target == this || event.target.className == 'closer' || event.keyCode == 27) {

            $(this).removeClass('open');

        }

    });        

	$("#close-newsletter").click(function(){

    	$(".fixed-newsletter").hide();

		$("body").css("margin-bottom", "0");

  	});

});

</script>





<?php wp_footer(); ?>





</body>

</html>