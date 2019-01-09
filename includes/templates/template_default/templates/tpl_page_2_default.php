<?php
/**
 * tpl_page_2_default.php
 *
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_page_2_default.php 3464 2006-04-19 00:07:26Z ajeh $
 */
?>

<?php $gal = $_SERVER['REQUEST_URI']; ?>
<?php
if ($gal) { ?>
    <style>.slider{display: none;} </style>
 <?php  } ?>



<div class="centerColumn" id="pageTwo">
<!--<h1 id="pageTwoHeading"><?php echo HEADING_TITLE; ?></h1>-->
<!--=================================================================---->
  <!-- http://galleria.io//  options: http://galleria.io/docs/options/
    =================================================== -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
    <script src="includes/templates/avonlee_contempo/galleria/galleria-1.2.9.min.js"></script>
    <link href="includes/templates/avonlee_contempo/galleria/themes/classic/galleria.classic.css" rel="stylesheet" type="text/css" />
    
	<!-- Primary Page Layout
	================================================== -->
	<!-- Delete everything in this .container and get started on your own site! -->
      <div class="sixteen columns">
        	<h3>Project Samples</h3>
			<p>Here's a sampling of our latest projects. These are just a small sample of ideas and materials. Custom projects are available in many sizes, finishes and materials. Contact us to discuss your ideas today! </p>
        </div>
        
	 <script>
            Galleria.loadTheme('includes/templates/avonlee_contempo/galleria/themes/classic/galleria.classic.min.js');
            Galleria.run('#galleria', {
				transition: 'fade',
				autoplay: 3000, // will move forward every 7 seconds
				clicknext: true,
				imageCrop: 'landscape', // true, false, 'height', 'width', 'portrait', 'landscape'
				imagePan: true,
				initialTransition: 'fade',
				lightbox: true
			});
        </script>
	<div class="sixteen columns" >
        <div id="galleria">
            <img src="images/IMG_2682 (566x640).jpg" 
            	data-title="Team Plaque" 
                data-description="The is a team plaque milled from a platic substrate finished coating." />
            <img src="images/IMG_2738 (480x640).jpg"
            	data-title="Team Plaque" 
                data-description="The is the Coach's team plaque and Team Roster milled from a platic substrate finished coating." />
            <img src="images/IMG_2767 (480x640).jpg"
            	data-title="Commerative Plaque" 
                data-description="This commerative plaque celebrates the new world record set at the Nurburgring in Germany. It's milled from a metal substrate finished coating." />
            <img src="images/IMG_2769 (583x640).jpg"
            	data-title="Commerative Plaque" 
                data-description="Commerative plaque, milled from a metalic substrate finished coating." />
            <img src="images/Linkin.jpeg"
            	data-title="Linked Letters" 
                data-description="This project was a test to demonstrate the linked object techniqued that can be applied to many projects. " />
            <img src="images/P1.JPG"
            	data-title="Custom Serving Trays" 
                data-description="This 12 inch solid wood Serving Tray comes with an indent to house a 3 inch sauce bowl." />
            <img src="images/P2.jpg"
            	data-title="Custom Serving Trays" 
                data-description="This 12 inch solid wood Serving Tray comes with an indent to house a 3 inch sauce bowl." />
            <img src="images/p3.JPG"
            	data-title="Serving Tray with Black Dish" 
                data-description="Solid wood cutting boards featuring client's custome business logo, and insert for three triangular sauce bowls." />
            <img src="images/p4.JPG"
            	data-title="Serving Tray with Black Dish" 
                data-description="Solid wood cutting boards featuring client's custome business logo, and insert for three triangular sauce bowls." />
            <img src="images/p5.jpg"
            	data-title="Appetizer Tray with Wine Glass" 
                data-description="Solid wood h'orderve tray &amp; wine glass holder. Conveneint for your next party or social gathering. " />
            <img src="images/p6.jpg"
            	data-title="Appetizer Tray with Wine Glass" 
                data-description="Put a Product description here." />
            <img src="images/p7.jpg"
            	data-title="Gravity Wine Stave" 
                data-description="Put a Product description here." />
            <img src="images/p8.jpg"
            	data-title="Gravity Wine Stave" 
                data-description="Put a Product description here." />
            <img src="images/p9.jpg"
            	data-title="Business Lettering" 
                data-description="Put a Product description here." />
            <img src="images/p10.JPG"
            	data-title="Custom Shop Sign - Painted" 
                data-description="Put a Product description here." />
            <img src="images/p11.JPG"
            	data-title="Custom Shop Sign - Unpainted" 
                data-description="Put a Product description here." />
            <img src="images/p12.JPG"
            	data-title="Custom Shop Sign" 
                data-description="Put a Product description here." />
            <img src="images/Pallisandro Dry Erase Board (640x480).jpg"
            	data-title="Dry Erase Board " 
                data-description="Put a Product description here." />
            <img src="images/Peyton.JPG"
            	data-title="Project Type or title" 
                data-description="Put a Product description here." />
            <img src="images/Pops.jpeg"
            	data-title="Custom Shop Sign" 
                data-description="Put a Product description here." />
		</div>
        <div class="BannerShadow"><img src="images/BannerShadow.png"></div>
<!-- End Document
================================================== --> 
<!--***********************************************************************************************************************-->
  <!--  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
<script type="text/javascript" src="http://www.biggboss.info/products/includes/templates/avonlee_contempo/galleria/galleria-1.2.9.min.js"></script>  
<link href="http://www.biggboss.info/products/includes/templates/avonlee_contempo/galleria/themes/classic/galleria.classic.css" rel="stylesheet" type="text/css" />-->
  
<!--==================================================================-->
<?php if (DEFINE_PAGE_2_STATUS >= 1 and DEFINE_PAGE_2_STATUS <= 2) { ?>
<div id="pageTwoMainContent" class="content">
<?php
/**
 * load the html_define for the page_2 default
 */
  require($define_page);
?>
</div>
<?php } ?>

<div class="buttonRow back"><?php echo zen_back_link() . zen_image_button(BUTTON_IMAGE_BACK, BUTTON_BACK_ALT) . '</a>'; ?></div>
</div>
