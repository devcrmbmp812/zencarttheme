<?php
/**
 * Module Template
 *
 * @package templateSystem
 * @copyright Copyright 2003-2007 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_modules_upcoming_products.php 6422 2007-05-31 00:51:40Z ajeh $
 */
?>
<?php

if ( sizeof($expectedItems) > 0 ) {
    
    echo '<div class="upcomingDefault centerBoxWrapper clearfix"><h2 class="centerBoxHeading">' . sprintf(TABLE_HEADING_UPCOMING_PRODUCTS, strftime('%B')) . '</h2><ul class="prod-list">';
    
	for($i=0; $i < sizeof($expectedItems); $i++) {
  
        if (!isset($productsInCategory[$expectedItems[$i]['products_id']])) 
                
            $productsInCategory[$expectedItems[$i]['products_id']] = zen_get_generated_category_path_rev($expectedItems[$i]['master_categories_id']);
    
      
        $products_img = (($expectedItems[$i]['products_image'] == '' and PRODUCTS_IMAGE_NO_IMAGE_STATUS == 0) ? '' : '<a href="' . zen_href_link(zen_get_info_page($expectedItems[$i]['products_id']), 'cPath=' . $productsInCategory[$expectedItems[$i]['products_id']] . '&products_id=' . $expectedItems[$i]['products_id']) . '">' . zen_image(DIR_WS_IMAGES . $expectedItems[$i]['products_image'], $expectedItems[$i]['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>');
    	
    	$products_name = '<h5 itemprop="name"><a  class="product-name name" href="' . zen_href_link(zen_get_info_page($expectedItems[$i]['products_id']), 'cPath=' . $productsInCategory[$expectedItems[$i]['products_id']] . '&products_id=' . $expectedItems[$i]['products_id']) . '">' . substr(strip_tags($expectedItems[$i]['products_name']), 0, 20) . '</a></h5>';
		
        
    	$products_desc = substr(strip_tags($expectedItems[$i]['products_description']), 0, 240) . '...';
        
        $products_expected_date = EXPECTED_DATE . ' : ' . zen_date_short($expectedItems[$i]['date_expected']);                
    	
        $products_price = '<strong>' . zen_get_products_display_price($expectedItems[$i]['products_id']) . '</strong>';
    	
    	$products_butt = '<a class="btn products-button" href="' . zen_href_link(zen_get_info_page($expectedItems[$i]['products_id']), 'cPath=' . $productsInCategory[$expectedItems[$i]['products_id']] . '&products_id=' . $expectedItems[$i]['products_id']) . '">' . zen_image_button(BUTTON_IMAGE_GOTO_PROD_DETAILS, BUTTON_GOTO_PROD_DETAILS_ALT) . '</a>';
      	
        $products_butt2 = '<a class="btn add-to-cart" href="' . zen_href_link($_GET["main_page"], zen_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $expectedItems[$i]["products_id"]) . '">' . zen_image_button(BUTTON_IMAGE_ADD_TO_CART, BUTTON_IN_CART_ALT) . '</a>';

		
        // $img_col_w = IMAGE_PRODUCT_NEW_WIDTH + 43;
     
    	
        echo '<li class="centeredContent"> 
    			<div class="product-col" >
					<div class="img">
						' . $products_img . '
						
					</div>
					<div class="prod-info">
						' . $products_name . '
						<span class="date">' . $products_expected_date . '</span>
					
						<div class="text">
							' . $products_desc . '
						</div>
						<div class="price">
							' . str_replace('&nbsp;', '', $products_price) . '
						</div>
						<div class="product-buttons">
							<div class="button1">' . $products_butt . '</div>
						</div>
					</div>
				</div>
            </li>';	
    			
    }
} 
echo '</ul></div>'
?>
