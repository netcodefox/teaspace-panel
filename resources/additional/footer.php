
	
	    <!-- Top Clients -->
    <section class="row section-spacing3 clients bg_white">
       <div class="container">
            <div class="row">
                <ul class="nav nav-justified">
				<center>
                   <li><img src="<?= $helper->url(); ?>assets/300px-Teaspeak_Logo.png" width="100" alt=""></li>
                  </center>   
               
               
                  
                       
                </ul>
            </div>
    	</div>
    </section>
    <!-- Top Clients End-->


    <!-- Footer -->
    <footer class="row">
        <div class="subscriber">
            <div class="container">
                <div class="row beInContact ">
                    <div class="col-sm-6 ">
                        <div class="social_icos text-center">
                            <ul class="nav">
                                <?php foreach ($helper->getSocialLinks() as $social): ?>
                                <li><a href="<?= htmlspecialchars($social['url']); ?>" target="_blank" rel="noopener noreferrer"><i class="<?= htmlspecialchars($social['icon']); ?>"></i></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>
    	<div class="footer-main">
            <div class="container ">
                <?php
                $footerContent = $helper->getSiteContent()['footer'] ?? [];
                $footerAbout = trim((string) ($footerContent['about'] ?? ''));
                $extraLabel = trim((string) ($footerContent['extra_link_label'] ?? ''));
                $extraUrl = trim((string) ($footerContent['extra_link_url'] ?? ''));
                ?>
                <div class="col-sm-3 col-xs-12">
                	 <img src="<?= htmlspecialchars($helper->getLogoUrl()); ?>"  width="200"  alt="<?= htmlspecialchars($helper->getDisplayName()); ?>">
					<hr>
                    <?php if ($footerAbout !== ''): ?>
                    <p><?= nl2br(htmlspecialchars($footerAbout)); ?></p>
                    <?php endif; ?>
                </div>
                <div class="col-sm-2 col-xs-12">
                	<h4>Produkte</h4>
                    <ul>
                        
                        <li><a href="<?= $helper->url(); ?>teaspeak/order">TeaSpeak</a></li>
                        
                    </ul>
                </div>
				<div class="col-sm-2 col-xs-12">
                	<h4>Links</h4>
                    <ul>
						<?php if ($extraLabel !== '' && $extraUrl !== '' && $extraUrl !== '#'): ?>
						<li><a href="<?= htmlspecialchars($extraUrl); ?>"><?= htmlspecialchars($extraLabel); ?></a></li>
						<?php endif; ?>
                    </ul>
                </div>
                <div class="col-sm-2 col-xs-12">
                	
                      <h4>Legal</h4>
                        <ul>
								<?php foreach ($helper->getFooterLegal() as $legal):
                                    $label = trim((string) ($legal['label'] ?? ''));
                                    $url = trim((string) ($legal['url'] ?? ''));
                                    if ($label === '' || $url === '' || $url === '#' || $url === '###') {
                                        continue;
                                    }
                                    $external = !empty($legal['external']);
                                ?>
								<li>
                                    <a href="<?= htmlspecialchars($url); ?>"<?= $external ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>><?= htmlspecialchars($label); ?></a>
                                </li>
								<?php endforeach; ?>
                        
                    </ul>
                </div>
                <div class="col-sm-3 col-xs-12">
                	<h4>Zahlungsmethoden</h4>
                    <ul class="list-inline payment-icons">
                        <img src="<?= $helper->url(); ?>zahlung/ueberweisung.png" width="100" alt="">
                        <img src="<?= $helper->url(); ?>zahlung/sofort.png" width="100" alt="">
					    
						<img src="<?= $helper->url(); ?>zahlung/paysafecard.png" width="100" alt="">
						<img src="<?= $helper->url(); ?>zahlung/paypal.png" width="100" alt="">
						<img src="<?= $helper->url(); ?>zahlung/giropay.png" width="100" alt="">
						<img src="<?= $helper->url(); ?>zahlung/eps.png" width="100" alt="">
						<img src="<?= $helper->url(); ?>zahlung/IDEAL.png" width="60" alt="">
             		</ul>
                    <small>Guthaben kann nicht wieder ausgezahlt werden!</small>
                </div>
            </div>
        </div>

        <div class="copyright_line">
        	<div class="container">
            	<div class="col-sm-12">
            		<p>Copyright 2018 - <script type="text/javascript">
  document.write(new Date().getFullYear());
</script> <?= htmlspecialchars($helper->getDisplayName()); ?>.  Alle Rechte vorbehalten.</p>
					<b>Gem. §19 UStG wird die Mehrwertsteuer in der Rechnung nicht ausgewiesen.</b><br>
					<b><?= htmlspecialchars($helper->getDisplayName()); ?> ist ein Projekt von Nico Jeffrey Bary.</b><br>
					<b>Made with ? in Heinsberg - Germany</b><br>
					<b><i class="fas fa-cog fa-spin fa-1x" style="font-size:16px"></i> Version: 1.0.7 (29.05.2021)</b><br>
                </div>
            </div>
        </div>
    </footer>

    <!--  Back to Top-->
    <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

    <!--jQuery-->
    <script src="<?= $helper->url(); ?>assets/font/js/jquery-2.2.4.min.js"></script>

    <!--Bootstrap JS-->
    <script src="<?= $helper->url(); ?>assets/font/js/bootstrap.min.js"></script>

    <!--Magnific Popup-->
    <script src="<?= $helper->url(); ?>assets/font/js/jquery.magnific-popup.min.js"></script>

    <!--Owl Carousel-->
    <script src="<?= $helper->url(); ?>assets/font/vendors/owl.carousel/owl.carousel.min.js"></script>

    <!--Waypoints-->
    <script src="<?= $helper->url(); ?>assets/font/vendors/waypoints/waypoints.min.js"></script>

    <!--Counter Up-->
    <script src="<?= $helper->url(); ?>assets/font/vendors/counterup/jquery.counterup.min.js"></script>

    <!--Isotope-->
    <script src="<?= $helper->url(); ?>assets/font/vendors/isotope/isotope.min.js"></script>

    <!--Infinite Scroll-->
    <script src="<?= $helper->url(); ?>assets/font/vendors/infinitescrol/jquery.infinitescroll.min.js"></script>

	<!--Video-popup-->
	<script src="<?= $helper->url(); ?>assets/font/vendors/video-popup/video-popup.js"></script>
    
    <!--Contact Form-->
    <script src="<?= $helper->url(); ?>assets/font/form/js/contact-form.js"></script>

    <!--Parallax-->
    <script src="<?= $helper->url(); ?>assets/font/vendors/parallax/jquery.parallax-1.1.3.js"></script>

    <!--Circliful-->
    <script src="<?= $helper->url(); ?>assets/font/vendors/circliful/jquery.circliful.min.js"></script>

    <!-- Slick Slider -->
    <script src="<?= $helper->url(); ?>assets/font/vendors/slick/js/slick.min.js"></script>

    <!-- equalize -->
    <script type="<?= $helper->url(); ?>assets/font/text/javascript" src="js/equalize.min.js"></script>

    <!--Theme JS-->
    <script src="<?= $helper->url(); ?>assets/font/js/theme.js"></script>
	<script>
	jQuery(document).ready(function() {
		// ______________ VIDEOPOPUP
		$("a.autoplay").VideoPopUp();
		$("a.noautoplay").VideoPopUp({
			autoplay: 0
		}); // Disable autoplay
		// ______________ PARALLAX
		$('.section-parallax').parallax("50%", 0.4);
		// ______________ STATS
		$('.statistics').waypoint(function() {
		 $('#myStat1').circliful();
		 $('#myStat2').circliful();
		 $('#myStat3').circliful();
		 $('#myStat4').circliful();
		}, { offset: 800, triggerOnce: true });
	});
    </script>

 <!--jQuery-->
    <script src="<?= $helper->url(); ?>assets/font/js/jquery-2.2.4.min.js"></script>

    <!--Bootstrap JS-->
    <script src="<?= $helper->url(); ?>assets/font/js/bootstrap.min.js"></script>

    <!--Magnific Popup-->
    <script src="<?= $helper->url(); ?>assets/font/js/jquery.magnific-popup.min.js"></script>

    <!--Bootstrap Select-->
    <script src="<?= $helper->url(); ?>assets/font/vendors/bootstrap-select/js/bootstrap-select.min.js"></script>
 
 
 
 <!--Owl Carousel-->
    <script src="<?= $helper->url(); ?>assets/font/vendors/owl.carousel/owl.carousel.min.js"></script>

    <!--Waypoints-->
    <script src="<?= $helper->url(); ?>assets/font/vendors/waypoints/waypoints.min.js"></script>

    <!--Counter Up-->
    <script src="<?= $helper->url(); ?>assets/font/vendors/counterup/jquery.counterup.min.js"></script>

    <!--Isotope-->
    <script src="<?= $helper->url(); ?>assets/font/vendors/isotope/isotope.min.js"></script>

    <!--Infinite Scroll-->
    <script src="<?= $helper->url(); ?>assets/font/vendors/infinitescrol/jquery.infinitescroll.min.js"></script>


 <!--QS Cloud Slider-->
    <script src="<?= $helper->url(); ?>assets/font/vendors/cloud-pricing-slider-master/jquery-ui.min.js" type="text/javascript"></script>
    <script src="<?= $helper->url(); ?>assets/font/vendors/cloud-pricing-slider-master/qs.slider.js" type="text/javascript"></script>
    <script src="<?= $helper->url(); ?>assets/font/vendors/cloud-pricing-slider-master/qs.slider.init.js" type="text/javascript"></script>
    <!--Contact Form-->
    <script src="<?= $helper->url(); ?>assets/font/form/js/contact-form.js"></script>
    <!-- Slick Slider -->
    <script src="<?= $helper->url(); ?>assets/font/vendors/slick/js/slick.min.js"></script>

    <!-- equalize -->
    <script type="text/javascript" src="<?= $helper->url(); ?>assets/font/js/equalize.min.js"></script>

	<!--<script src="<?= $helper->cdnUrl(); ?>js/jquery.min.js"></script>
<script src="<?= $helper->cdnUrl(); ?>js/headroom.min.js"></script>
<script src="<?= $helper->cdnUrl(); ?>js/js.cookie.min.js"></script>
<script src="<?= $helper->cdnUrl(); ?>js/imagesloaded.min.js"></script>
<script src="<?= $helper->cdnUrl(); ?>js/bricks.min.js"></script>
-->


</body>
</html>