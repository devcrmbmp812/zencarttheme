<?php
/**
 * Page Template
 *
 * @package templateSystem
 * @copyright Copyright 2003-2012 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version GIT: $Id: Author: DrByte  Sun Aug 19 09:47:29 2012 -0400 Modified in v1.5.1 $
 */
?>
<div class="centerColumn" id="reviewsWrite">
    <div class="heading"><h1><?php echo $products_name?></h1></div>

    <div class="row">
    <div class="pb-left-column col-xs-12 col-sm-3 col-md-5">
        <!--bof Main Product Image -->
        <?php
           if (zen_not_null($products_image)) {
        ?>
        <?php
            /**
            * display the main product image
            */
            require($template->get_template_dir('/tpl_modules_main_product_image.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_main_product_image.php'); ?>
        <?php
          //} else {
        ?>
        
        <?php
            }
        ?>
    </div>
    <div class="pb-center-column col-xs-12 col-sm-7">
        <div class="bot-border">
        <div class="btn-group">
            <?php echo '<a class="btn-default-small" href="' . zen_href_link(zen_get_info_page($_GET['products_id']), zen_get_all_get_params()) . '">' . zen_image_button(BUTTON_IMAGE_GOTO_PROD_DETAILS , BUTTON_GOTO_PROD_DETAILS_ALT) . '</a>'; ?>
            <?php echo '<a class="btn-default-small" href="' . zen_href_link(FILENAME_REVIEWS) . '">' . zen_image_button(BUTTON_IMAGE_REVIEWS, BUTTON_REVIEWS_ALT) . '</a>'; ?>
        </div>
    </div>
        <?php echo zen_draw_form('product_reviews_write', zen_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'action=process&products_id=' . $_GET['products_id'], 'SSL'), 'post', 'onsubmit="return checkForm(product_reviews_write);"'); ?>
        <div class="name-type bot-border">
            <?php echo SUB_TITLE_FROM, zen_output_string_protected($customer->fields['customers_firstname'] . ' ' . $customer->fields['customers_lastname']); ?>
        </div>
        
        <?php if ($messageStack->size('review_text') > 0) echo $messageStack->output('review_text'); ?>
        
        <div id="reviewsWriteReviewsRate" ><?php echo SUB_TITLE_RATING; ?></div>
        
        <div class="name-type bot-border">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-4">
                    <div class="star_st">
                        <?php echo zen_draw_radio_field('rating', '1', '', 'id="rating-1"'); ?>
                        <?php echo '<label class="" for="rating-1">' . zen_image($template->get_template_dir(OTHER_IMAGE_REVIEWS_RATING_STARS_ONE, DIR_WS_TEMPLATE, $current_page_base,'images'). '/' . OTHER_IMAGE_REVIEWS_RATING_STARS_ONE, OTHER_REVIEWS_RATING_STARS_ONE_ALT) . '</label> '; ?>
                    </div>
                    <div class="star_st">
                        <?php echo zen_draw_radio_field('rating', '2', '', 'id="rating-2"'); ?>
                        <?php echo '<label class="" for="rating-2">' . zen_image($template->get_template_dir(OTHER_IMAGE_REVIEWS_RATING_STARS_TWO, DIR_WS_TEMPLATE, $current_page_base,'images'). '/' . OTHER_IMAGE_REVIEWS_RATING_STARS_TWO, OTHER_REVIEWS_RATING_STARS_TWO_ALT) . '</label>'; ?>
                    </div>
                    <div class="star_st">
                        <?php echo zen_draw_radio_field('rating', '3', '', 'id="rating-3"'); ?>
                        <?php echo '<label class="" for="rating-3">' . zen_image($template->get_template_dir(OTHER_IMAGE_REVIEWS_RATING_STARS_THREE, DIR_WS_TEMPLATE, $current_page_base,'images'). '/' . OTHER_IMAGE_REVIEWS_RATING_STARS_THREE, OTHER_REVIEWS_RATING_STARS_THREE_ALT) . '</label>'; ?>
                    </div>
                    <div class="star_st">
                        <?php echo zen_draw_radio_field('rating', '4', '', 'id="rating-4"'); ?>
                        <?php echo '<label class="" for="rating-4">' . zen_image($template->get_template_dir(OTHER_IMAGE_REVIEWS_RATING_STARS_FOUR, DIR_WS_TEMPLATE, $current_page_base,'images'). '/' . OTHER_IMAGE_REVIEWS_RATING_STARS_FOUR, OTHER_REVIEWS_RATING_STARS_FOUR_ALT) . '</label>'; ?>
                    </div>
                    <div class="star_st">
                        <?php echo zen_draw_radio_field('rating', '5', '', 'id="rating-5"'); ?>
                        <?php echo '<label class="" for="rating-5">' . zen_image($template->get_template_dir(OTHER_IMAGE_REVIEWS_RATING_STARS_FIVE, DIR_WS_TEMPLATE, $current_page_base,'images'). '/' . OTHER_IMAGE_REVIEWS_RATING_STARS_FIVE, OTHER_REVIEWS_RATING_STARS_FIVE_ALT) . '</label>'; ?>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-8">
                    <label id="textAreaReviews" for="review-text"><?php echo SUB_TITLE_REVIEW; ?></label>
                    <div id="reviewsWriteReviewsNotice" class="notice"><?php echo TEXT_NO_HTML . (REVIEWS_APPROVAL == '1' ? '<br />' . TEXT_APPROVAL_REQUIRED: ''); ?></div>
                </div>
            </div> 
        </div>
        
        
        <?php echo zen_draw_textarea_field('review_text', 60, 5, '', 'id="review-text"'); ?>
            <br>
            <br>
            <div class="buttonRow forward"><?php echo zen_image_submit('', BUTTON_SUBMIT_ALT); ?></div>
            <br>
            <br>
        </form>
    </div>
    </div>
</div>
