<?php
/**
 * Page Template
 *
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_product_reviews_info_default.php 2993 2006-02-08 07:14:52Z birdbrain $
 */
?>
<div class="centerColumn" id="reviewsInfoDefault">
    <h2 class="centerBoxHeading"><?php echo (strip_tags($products_name)) ?></h2>
    <div class="tie">
    	<div class="tie-indent">
    		<div class="page-content">
                <div class="row">
                    <div class="pb-left-column col-xs-12  col-sm-5">
                    <?php if (zen_not_null($products_image)) { ?>
                        <span class="image"><?php require($template->get_template_dir('/tpl_modules_main_product_image.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_main_product_image.php'); ?></span>
            		    <?php
                            }
            		    ?>
                    </div>
                    <div class="pb-center-column col-xs-12 col-sm-7">
                        <div class="date-added"><?php echo sprintf(TEXT_REVIEW_DATE_ADDED, zen_date_short($review_info->fields['date_added'])); ?>&nbsp;<?php echo sprintf(TEXT_REVIEW_BY, zen_output_string_protected($review_info->fields['customers_name'])); ?></div>
                        <div class="name-type"><h5><?php echo $products_name . $products_model; ?></h5></div>
                        <h3 class="rating"><?php echo zen_image(DIR_WS_TEMPLATE_IMAGES . 'stars_' . $review_info->fields['reviews_rating'] . '.gif', sprintf(TEXT_OF_5_STARS, $review_info->fields['reviews_rating'])), sprintf(TEXT_OF_5_STARS, $review_info->fields['reviews_rating']); ?></h3>
                        
            				<div id="reviewsInfoDefaultMainContent" class="content"><?php echo zen_break_string(nl2br(zen_output_string_protected(stripslashes($review_info->fields['reviews_text']))), 60, '-<br />'); ?></div>
            				<div class="button-padding">
                               
                                <div class="clearfix"></div>
            					<?php
            							// more info in place of buy now
            							if (zen_has_product_attributes($review_info->fields['products_id'] )) {
            							  //   $link = '<p>' . '<a href="' . zen_href_link(zen_get_info_page($review_info->fields['products_id']), 'products_id=' . $review_info->fields['products_id'] ) . '">' . MORE_INFO_TEXT . '</a>' . '</p>';
            							  $link = '';
            							} else {
            							   $link= '<a class="btn add-to-cart" href="' . zen_href_link($_GET['main_page'], zen_get_all_get_params(array('action', 'reviews_id')) . 'action=buy_now') . '">' . zen_image_button(BUTTON_IMAGE_IN_CART, BUTTON_IN_CART_ALT) . '</a>&nbsp;&nbsp;';
            							}
            					
            							$the_button = $link;
            							$products_link = '';
            							echo zen_get_buy_now_button($review_info->fields['products_id'], $the_button, $products_link) . ' ' . zen_get_products_quantity_min_units_display($review_info->fields['products_id']);
            						  ?>
            					
            					<?php echo '<a class="btn" href="' . zen_href_link(zen_get_info_page($_GET['products_id']), zen_get_all_get_params(array('reviews_id'))) . '">' . zen_image_button(BUTTON_IMAGE_GOTO_PROD_DETAILS , BUTTON_GOTO_PROD_DETAILS_ALT) . '</a>&nbsp;&nbsp;'; ?>
            					<?php echo ($reviews_counter > 1 ? '<a class="btn products-button" href="' . zen_href_link(FILENAME_PRODUCT_REVIEWS, zen_get_all_get_params(array('reviews_id'))) . '">' . zen_image_button(BUTTON_IMAGE_MORE_REVIEWS , BUTTON_MORE_REVIEWS_ALT) . '</a>&nbsp;&nbsp;' : ''); ?>
            					<?php echo '<a class="btn" href="' . zen_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, zen_get_all_get_params(array('reviews_id'))) . '">' . zen_image_button(BUTTON_IMAGE_WRITE_REVIEW, BUTTON_WRITE_REVIEW_ALT) . '</a>'; ?>
            				</div>
            			</div>
                    </div>
                </div>	
    		</div>
    	</div>
    </div>
