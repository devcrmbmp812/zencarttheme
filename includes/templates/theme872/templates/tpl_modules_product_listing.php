<?php
/**
 * Module Template
 *
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_modules_product_listing.php 3241 2006-03-22 04:27:27Z ajeh $
 */
 
 include(DIR_WS_MODULES . zen_get_module_directory(FILENAME_PRODUCT_LISTING));
?>

<div id="productListing">

<?php if ( ($listing_split->number_of_rows > 0) && ( (PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3') ) ) {
?>
<div class="top-pg">
<div id="productsListingTopNumber" class="navSplitPagesResult fleft"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></div>
<div class="top-paginator">
    <ul id="productsListingListingTopLinks" class="pagination"><?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, zen_get_all_get_params(array('page', 'info', 'x', 'y', 'main_page'))); ?></ul>
    <ul class="listing_view hidden-xs">
        <li id="grid" class="grid">
            <a rel="nofollow" href="javascript:void(0);" title="Grid"></a>
        </li>
        <li id="list" class="list">
            <a rel="nofollow" href="javascript:void(0);" title="List"></a>
        </li>
    </ul>
</div>
</div>
<div class="clearfix"></div>
<?php
}
?>

<div class="tie tie-margin1">
	<div class="tie-indent">
		
		<?php
		// only show when there is something to submit and enabled
			if ($show_top_submit_button == true) {
		?>
		<div class="buttonRow forward" style="display: none!important;"><?php echo zen_image_submit('', BUTTON_ADD_PRODUCTS_TO_CART_ALT, 'id="submit1" name="submit1"'); ?></div>
		<?php
			} // show top submit
		?>
		
		
		<?php
		/**
		 * load the list_box_content template to display the products
		 */
		  require($template->get_template_dir('tpl_tabular_display.php',DIR_WS_TEMPLATE, $current_page_base,'common'). '/tpl_tabular_display.php');
		?>
		
		
		<?php
		// only show when there is something to submit and enabled
			if ($show_bottom_submit_button == true) {
		?>
		<div class="buttonRow forward" style="display: none!important;"><?php echo zen_image_submit('', BUTTON_ADD_PRODUCTS_TO_CART_ALT, 'id="submit2" name="submit1"'); ?></div>
		<?php
			} // show_bottom_submit_button
		?>
</div>



<?php
// if ($show_top_submit_button == true or $show_bottom_submit_button == true or ('' != 0 and $show_submit == true and $listing_split->number_of_rows > 0)) {
  if ($show_top_submit_button == true or $show_bottom_submit_button == true) {
?>
</form>
<?php } ?>
	<div class="clearfix"></div>
</div>
</div>

<?php if ( ($listing_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3')) ) {
?>
<div class="bottom-pg">
<div id="productsListingBottomNumber" class="navSplitPagesResult back"><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></div>
<div class="bottom-paginator">
    <ul id="productsListingListingBottomLinks" class="pagination"><?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, zen_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></ul>
</div>
</div>
<div class="clearfix"></div>
<?php
  }
?>