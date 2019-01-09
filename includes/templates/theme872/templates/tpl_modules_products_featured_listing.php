<?php
/**
 * Module Template
 *
 * Loaded automatically by index.php?main_page=featured_products.<br />
 * Displays listing of Featured Products
 *
 * @package templateSystem
 * @copyright Copyright 2003-2007 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_modules_products_featured_listing.php 6096 2007-04-01 00:43:21Z ajeh $
 */
?>
</div>
<ul class="product_list row list">
<?php
  $group_id = zen_get_configuration_key_value('PRODUCT_NEW_LIST_GROUP_ID');

  if ($featured_products_split->number_of_rows > 0) {
    $featured_products = $db->Execute($featured_products_split->sql_query);
    while (!$featured_products->EOF) { 
	
      if (PRODUCT_NEW_LIST_IMAGE != '0') {
        if ($featured_products->fields['products_image'] == '' and PRODUCTS_IMAGE_NO_IMAGE_STATUS == 0) {
          $display_products_image = str_repeat('<br clear="all" />', substr(PRODUCT_NEW_LIST_IMAGE, 3, 1));
        } else {
          $display_products_image = '<div class="img"><a href="' . zen_href_link(zen_get_info_page($featured_products->fields['products_id']), 'cPath=' . zen_get_generated_category_path_rev($featured_products->fields['master_categories_id']) . '&products_id=' . $featured_products->fields['products_id']) . '">' . zen_image(DIR_WS_IMAGES . $featured_products->fields['products_image'], $featured_products->fields['products_name'], IMAGE_PRODUCT_NEW_LISTING_WIDTH, IMAGE_PRODUCT_NEW_LISTING_HEIGHT) . '</a></div>';
        }
      } else {
        $display_products_image = '';
      }

      if (PRODUCT_NEW_LIST_NAME != '0') {
        $display_products_name = '
            <h5 itemprop="name">
                <a class="product-name name" href="' . zen_href_link(zen_get_info_page($featured_products->fields['products_id']), 'cPath=' . zen_get_generated_category_path_rev($featured_products->fields['master_categories_id']) . '&products_id=' . $featured_products->fields['products_id']) . '">
                    ' . $featured_products->fields['products_name'] . '
                </a>
            </h5>';
      } 
      else 
      {
        $display_products_name = '';
      }

      if (PRODUCT_NEW_LIST_MODEL != '0' and zen_get_show_product_switch($featured_products->fields['products_id'], 'model')) {
        $display_products_model = '<span class="model">'.'<strong>'.TEXT_PRODUCTS_MODEL.'</strong>' . $featured_products->fields['products_model'] . str_repeat('', substr(PRODUCT_NEW_LIST_MODEL, 3, 1)).'</span>';
      } else {
        $display_products_model = '';
      }

      if (PRODUCT_NEW_LIST_WEIGHT != '0' and zen_get_show_product_switch($featured_products->fields['products_id'], 'weight')) {
        $display_products_weight = '<span class="weight">' . '<strong>'.TEXT_PRODUCTS_WEIGHT.'</strong>' . $featured_products->fields['products_weight'] . TEXT_SHIPPING_WEIGHT . str_repeat(substr(PRODUCT_NEW_LIST_WEIGHT, 3, 1)).'</span>';
      } else {
        $display_products_weight = '';
      }

      if (PRODUCT_NEW_LIST_QUANTITY != '0' and zen_get_show_product_switch($featured_products->fields['products_id'], 'quantity')) {
        if ($featured_products->fields['products_quantity'] <= 0) {
          $display_products_quantity = '<span class="stock">'.TEXT_OUT_OF_STOCK . str_repeat('', substr(PRODUCT_NEW_LIST_QUANTITY, 3, 1)).'</span>';
        } else {
          $display_products_quantity = '<span class="stock">'.TEXT_PRODUCTS_QUANTITY . $featured_products->fields['products_quantity'] . str_repeat('', substr(PRODUCT_NEW_LIST_QUANTITY, 3, 1)).'</span>';
        }
      } else {
        $display_products_quantity = '';
      }

      if (PRODUCT_NEW_LIST_DATE_ADDED != '0' and zen_get_show_product_switch($featured_products->fields['products_id'], 'date_added')) {
        $display_products_date_added = '<div>'.TEXT_DATE_ADDED . ' <small>' . zen_date_long($featured_products->fields['products_date_added']) . str_repeat('<br clear="all" />', substr(PRODUCT_NEW_LIST_DATE_ADDED, 3, 1)).'</small></div>';
      } else {
        $display_products_date_added = '';
      }

      if (PRODUCT_NEW_LIST_MANUFACTURER != '0' and zen_get_show_product_switch($featured_products->fields['products_id'], 'manufacturer')) {
        $display_products_manufacturers_name = '<span class="brand">'.($featured_products->fields['manufacturers_name'] != '' ? '<strong>'.TEXT_MANUFACTURER.'</strong>' . ' ' . $featured_products->fields['manufacturers_name'] . str_repeat(substr(PRODUCT_NEW_LIST_MANUFACTURER, 3, 1)) : '').'</span>';
      } else {
        $display_products_manufacturers_name = '';
      }

      if ((PRODUCT_NEW_LIST_PRICE != '0' and zen_get_products_allow_add_to_cart($featured_products->fields['products_id']) == 'Y') and zen_check_show_prices() == true) {
        $products_price = '<span itemprop="price" class="price product-price">'.zen_get_products_display_price($featured_products->fields['products_id']).'</span><div class="clearfix"></div>';
        $display_products_price = $products_price . str_repeat(substr(PRODUCT_NEW_LIST_PRICE, 3, 1)) . (zen_get_show_product_switch($featured_products->fields['products_id'], 'ALWAYS_FREE_SHIPPING_IMAGE_SWITCH') ? (zen_get_product_is_always_free_shipping($featured_products->fields['products_id']) ? TEXT_PRODUCT_FREE_SHIPPING_ICON  : '') : '');
      } else {
        $display_products_price = '';
      }

      if (PRODUCT_NEW_LIST_DESCRIPTION > '0') {
        $disp_text = zen_get_products_description($featured_products->fields['products_id']);
        $disp_text = zen_clean_html($disp_text);

        $display_products_description = substr(strip_tags($disp_text, PRODUCT_NEW_LIST_DESCRIPTION), 0, 120) . '...';
      } else {
        $display_products_description = '';
      }

      if (PRODUCT_NEW_BUY_NOW > '0') {
      if ($featured_products->fields['products_quantity'] <= 0) {
        
        $the_button2 = '<div class="buttons"><span class="stock">'.TEXT_OUT_OF_STOCK . str_repeat('', substr(PRODUCT_NEW_LIST_QUANTITY, 3, 1)).'</span><a class="btn products-button" href="' . zen_href_link(zen_get_info_page($new_products->fields['products_id']), 'cPath=' . $productsInCategory[$products_new->fields['products_id']] . '&products_id=' . $products_new->fields['products_id']) . '">' . zen_image_button(BUTTON_IMAGE_GOTO_PROD_DETAILS, BUTTON_GOTO_PROD_DETAILS_ALT) . '</a></div>';
        } else {
          $the_button2 = '<div class="buttons"><a class="btn products-button add-to-cart" href="' . zen_href_link(FILENAME_PRODUCTS_NEW, zen_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $products_new->fields["products_id"]) . '">' . zen_image_button(BUTTON_IMAGE_ADD_TO_CART, BUTTON_IN_CART_ALT) . '</a><a class="btn products-button" href="' . zen_href_link(zen_get_info_page($new_products->fields['products_id']), 'cPath=' . $productsInCategory[$products_new->fields['products_id']] . '&products_id=' . $products_new->fields['products_id']) . '">' . zen_image_button(BUTTON_IMAGE_GOTO_PROD_DETAILS, BUTTON_GOTO_PROD_DETAILS_ALT) . '</a></div>';
        
      }
    }
	  		
		$the_button1 = '<div class="button1"><a class="btn products-button" href="' . zen_href_link(zen_get_info_page($featured_products->fields['products_id']), 'cPath=' . $productsInCategory[$featured_products->fields['products_id']] . '&products_id=' . $featured_products->fields['products_id']) . '">' . zen_image_button(BUTTON_IMAGE_GOTO_PROD_DETAILS, BUTTON_GOTO_PROD_DETAILS_ALT) . '</a></div>';
		
        
?>
 <li class="col-xs-12">
	<div class="product-container">
        <div class="row">
          <div class="left-block col-xs-4 col-xs-5 col-md-4">
              <?php
                $disp_sort_order = $db->Execute("select configuration_key, configuration_value from " . TABLE_CONFIGURATION . " where configuration_group_id='" . $group_id . "' and (configuration_value >= 1000 and configuration_value <= 1999) order by LPAD(configuration_value,11,0)");
                while (!$disp_sort_order->EOF) {
                  if ($disp_sort_order->fields['configuration_key'] == 'PRODUCT_NEW_LIST_IMAGE') {
                    echo $display_products_image;
                  }
                  if ($disp_sort_order->fields['configuration_key'] == 'PRODUCT_NEW_BUY_NOW') {
                    echo $the_button2;
                  }

                  if ($disp_sort_order->fields['configuration_key'] == 'PRODUCT_NEW_LIST_NAME') {
                    echo $display_products_name;
                  }
                  if ($disp_sort_order->fields['configuration_key'] == 'PRODUCT_NEW_LIST_MODEL') {
                    echo $display_products_model;
                  }
                  if ($disp_sort_order->fields['configuration_key'] == 'PRODUCT_NEW_LIST_MANUFACTURER') {
                    echo $display_products_manufacturers_name;
                  }
                  if ($disp_sort_order->fields['configuration_key'] == 'PRODUCT_NEW_LIST_PRICE') {
                    echo $display_products_price;
                  }
                  if ($disp_sort_order->fields['configuration_key'] == 'PRODUCT_NEW_LIST_WEIGHT') {
                    echo $display_products_weight;
                  }
                  if ($disp_sort_order->fields['configuration_key'] == 'PRODUCT_NEW_LIST_DATE_ADDED') {
                    echo $display_products_date_added.'';
                  }
                  $disp_sort_order->MoveNext();
                }
              ?>
            </div>
            <?php if (PRODUCT_NEW_LIST_DESCRIPTION > '0') { ?>
            <div class="center-block col-xs-4 col-xs-7 col-md-4">
                
                <?php
                    $disp_sort_order = $db->Execute("select configuration_key, configuration_value from " . TABLE_CONFIGURATION . " where configuration_group_id='" . $group_id . "' and (configuration_value >= 2000 and configuration_value <= 2999) order by LPAD(configuration_value,11,0)");
                    while (!$disp_sort_order->EOF) {
                        if ($disp_sort_order->fields['configuration_key'] == 'PRODUCT_NEW_LIST_NAME') {
                            echo $display_products_name;
                        }
                        $disp_sort_order->MoveNext();
                    }
                    echo '<div class="text">
                              <span class="list-desc">
                                  '.$display_products_description.'
                              </span>
                              <span class="grid-desc">
                                  '.$display_products_description.'
                              </span>
                          </div>';
                ?>
            </div>
            <?php } ?>
            <div class="right-block col-xs-4 col-xs-12 col-md-4">
                <div class="content_price col-xs-5 col-md-12">
                  <?php
                    $disp_sort_order = $db->Execute("select configuration_key, configuration_value from " . TABLE_CONFIGURATION . " where configuration_group_id='" . $group_id . "' and (configuration_value >= 2000 and configuration_value <= 2999) order by LPAD(configuration_value,11,0)");
                    while (!$disp_sort_order->EOF) {
                      if ($disp_sort_order->fields['configuration_key'] == 'PRODUCT_NEW_LIST_IMAGE') {
                        echo $display_products_image;
                      }
    				  
                      if ($disp_sort_order->fields['configuration_key'] == 'PRODUCT_NEW_LIST_QUANTITY') {
                        echo $display_products_quantity;
                      }
                      if ($disp_sort_order->fields['configuration_key'] == 'PRODUCT_NEW_BUY_NOW') {
						 echo $the_button2;
					  }
    
                      if ($disp_sort_order->fields['configuration_key'] == 'PRODUCT_NEW_LIST_MODEL') {
                        echo $display_products_model;
                      }
                      if ($disp_sort_order->fields['configuration_key'] == 'PRODUCT_NEW_LIST_MANUFACTURER') {
                        echo $display_products_manufacturers_name;
                      }
                      if ($disp_sort_order->fields['configuration_key'] == 'PRODUCT_NEW_LIST_PRICE') {
                        echo $display_products_price;
                      }
                      if ($disp_sort_order->fields['configuration_key'] == 'PRODUCT_NEW_LIST_WEIGHT') {
                        echo $display_products_weight;
                      }
                      $disp_sort_order->MoveNext();
                    }
                  ?>
                </div>
            </div>
        </div>
	</div>     
    <?php
          $featured_products->MoveNext();
    	  
    	  
        }
      } else {
    ?>
             
    <?php
      }
    ?>
</li>
</ul>