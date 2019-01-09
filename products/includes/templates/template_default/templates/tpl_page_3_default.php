<?php
/**
 * Page Template
 *
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_page_3_default.php 3464 2006-04-19 00:07:26Z ajeh $
 */
?>
<div class="centerColumn" id="pageThree">
<!--<h1 id="pageThreeHeading"><?php //echo HEADING_TITLE; ?></h1>-->
<h1 id="pageThreeHeading">About</h1>

<?php if (DEFINE_PAGE_3_STATUS >= 1 and DEFINE_PAGE_3_STATUS <= 2) { ?>
<div id="pageThreeMainContent" class="content">
<?php
/**
 * require the html_define for the page_3 page
 */
  require($define_page);
?>
</div>
<?php } ?>
 <div id="contactUsNoticeContent" class="content">
<p>Boscoyo Studio produces custom Promotional Products, signs and crafted items. We designed, cut, and finished with a wide range of products and materials. You just tell us what you would like! </p>
<p> Be sure to view our Product Gallery page for samples of a few past projects. </p>
<p>We offer custom promotional products and specialized products to meet the needs of Christmas Lighting Enthusiasts. You can shop our products and we can custom make anything to fit your needs, we can also take your ideas and turn them into custom made art!</p>
</div>
<div class="buttonRow back"><?php echo zen_back_link() . zen_image_button(BUTTON_IMAGE_BACK, BUTTON_BACK_ALT) . '</a>'; ?></div>
</div>
