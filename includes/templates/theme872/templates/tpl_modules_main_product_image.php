<?php
/**
 * Module Template
 *
 * @package templateSystem
 * @copyright Copyright 2003-2011 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_modules_main_product_image.php 18698 2011-05-04 14:50:06Z wilt $
 */
?>
<?php require(DIR_WS_MODULES . zen_get_module_directory(FILENAME_MAIN_PRODUCT_IMAGE)); ?> 


<div id="productMainImage" class="pull-left image-block">
	<span class="image"><a href="<?php echo $products_image_large; ?>">
	<?php echo zen_image($products_image_medium, addslashes($products_name), MEDIUM_IMAGE_WIDTH, MEDIUM_IMAGE_HEIGHT); ?>
	<span class="zoom"></span></a></span>
	<?php /*
	<a href="<?php echo $products_image_large; ?>"><span class="imgLink"><?php echo TEXT_CLICK_TO_ENLARGE; ?></span></a>
	*/?>

</div>