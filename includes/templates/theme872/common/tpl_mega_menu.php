<?php
//
// +----------------------------------------------------------------------+
// |zen-cart Open Source E-commerce                                       |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003 The zen-cart developers                           |
// |                                                                      |
// | http://www.zen-cart.com/index.php                                    |
// |                                                                      |
// | Portions Copyright (c) 2003 osCommerce                               |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.0 of the GPL license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.zen-cart.com/license/2_0.txt.                             |
// | If you did not receive a copy of the zen-cart license and are unable |
// | to obtain it through the world-wide-web, please send a note to       |
// | license@zen-cart.com so we can mail you a copy immediately.          |
// +----------------------------------------------------------------------+
// $Id: tpl_drop_menu.php  2005/06/15 15:39:05 DrByte Exp $
//

?>  
<div id="mega-wrapper" class="stickUpTop"><!-- bof mega-wrapper -->
    <ul class="mega-menu col-sm-12"><!-- bof mega-menu -->
    	<?php if(TM_MEGAMENU_CATEGORIES == 'true') { ?> 
        <li class="categories-li"><a class="drop"><?php echo BOX_HEADING_CATEGORIES; ?><span class="label"><?php echo TM_MEGAMENU_CATEGORIES_LABEL; ?></span></a><!-- bof cateories    -->
     
            <div class="dropdown col-<?php echo TM_MEGAMENU_CATEGORIES_COL_WIDTH; ?>">
				<div class="levels">
            <?php
						 // load the UL-generator class and produce the menu list dynamically from there
						 require_once (DIR_WS_CLASSES . 'categories_ul_generator.php');
						 $zen_CategoriesUL = new zen_categories_ul_generator;
						 $menulist = $zen_CategoriesUL->buildTree(true);
						 $menulist = str_replace('"level4"','"level5"',$menulist);
						 $menulist = str_replace('"level3"','"level4"',$menulist);
						 $menulist = str_replace('"level2"','"level3"',$menulist);
						 $menulist = str_replace('"level1"','"level2"',$menulist);
						 $menulist = str_replace('<li class="submenu">','<li class="submenu">',$menulist);
						 $menulist = str_replace("</li>\n</ul>\n</li>\n</ul>\n","</li>\n</ul>\n",$menulist);
						 echo $menulist;
						?>                        
          </div>
               <?php if(TM_MEGAMENU_CATEGORIES_BANNERS == 'true') { ?> 
               <div class="clearfix"></div>
               <div class="categories-banners">
				 <?php
						$new_banner_search = zen_build_banners_group(SHOW_BANNERS_GROUP_SET6);
				
						  // secure pages
						  switch ($request_type) {
							case ('SSL'):
							  $my_banner_filter=" and banners_on_ssl= " . "1";
							  break;
							case ('NONSSL'):
							  $my_banner_filter='';
							  break;
						  }
						
						  $sql = "select banners_id from " . TABLE_BANNERS . " where status = 1 " . $new_banner_search . $my_banner_filter . " order by banners_sort_order";
						  $banners_all = $db->Execute($sql);
				
						// if no active banner in the specified banner group then the box will not show
						  $banner_cnt = 0;
						  while (!$banners_all->EOF) {
							$banner_cnt++;
							$banner = zen_banner_exists('dynamic', SHOW_BANNERS_GROUP_SET6);
							echo '<div class="item_'.$banner_cnt.'">'.tm_zen_display_banner('static', $banners_all->fields['banners_id']).'</div>';
						// add spacing between banners
							if ($banner_cnt < $banners_all->RecordCount()) {
							  
							}
							$banners_all->MoveNext();
						  }
					?>
           	  </div>
              <?php } ?>   
               </div>
        </li><!-- eof categories  -->
        <?php } ?>
        <?php if(TM_MEGAMENU_SPECIALS == 'true') { ?>
	<li class="specials_p"><a class="drop"><?php echo BOX_HEADING_SPECIALS; ?><span class="label"><?php echo TM_MEGAMENU_SPECIALS_LABEL; ?></span></a>
    <!-- bof specials -->
        
            <div class="dropdown col-9">
                <?php if(TM_MEGAMENU_SPECIALS_TEXT == 'true') { ?>
            		<div class="special_text"><p><?php echo TM_MEGAMENU_SPECIALS_DESC_TEXT; ?></p></div>
                <?php } ?>
                <div class="list_carousel responsive">
                    <ul id="fcarousel">
                            <?php		 
                            $categories_products_id_list = '';
                            $list_of_products = '';
                            $specials_index_query = '';
                            $display_limit = '';
                            
                            if ( (($manufacturers_id > 0 && $_GET['filter_id'] == 0) || $_GET['music_genre_id'] > 0 || $_GET['record_company_id'] > 0) || (!isset($new_products_category_id) || $new_products_category_id == '0') ) {
                              $specials_index_query = "select p.products_id, p.products_image, pd.products_name, p.master_categories_id, pd.products_description
                                                       from (" . TABLE_PRODUCTS . " p
                                                       left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id
                                                       left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id )
                                                       where p.products_id = s.products_id
                                                       and p.products_id = pd.products_id
                                                       and p.products_status = '1' and s.status = 1
                                                       and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'";
                            } else {
                              // get all products and cPaths in this subcat tree
                              $productsInCategory = zen_get_categories_products_list( (($manufacturers_id > 0 && $_GET['filter_id'] > 0) ? zen_get_generated_category_path_rev($_GET['filter_id']) : $cPath), false, true, 0, $display_limit);
                            
                              if (is_array($productsInCategory) && sizeof($productsInCategory) > 0) {
                                // build products-list string to insert into SQL query
                                foreach($productsInCategory as $key => $value) {
                                  $list_of_products .= $key . ', ';
                                }
                                $list_of_products = substr($list_of_products, 0, -2); // remove trailing comma
                                $specials_index_query = "select distinct p.products_id, p.products_image, pd.products_name, p.master_categories_id, pd.products_description
                                                         from (" . TABLE_PRODUCTS . " p
                                                         left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id
                                                         left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id )
                                                         where p.products_id = s.products_id
                                                         and p.products_id = pd.products_id
                                                         and p.products_status = '1' and s.status = '1'
                                                         and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'
                                                         and p.products_id in (" . $list_of_products . ")";
                              }
                            }
                            if ($specials_index_query != '') $specials_index = $db->ExecuteRandomMulti($specials_index_query, MAX_DISPLAY_SPECIAL_PRODUCTS_INDEX);
                                     
                                     
                            while (!$specials_index->EOF) {
                            $products_name1 = '<a class="product-name name" href="' . zen_href_link(zen_get_info_page($specials_index->fields['products_id']), 'cPath=' . $productsInCategory[$specials_index->fields['products_id']] . '&products_id=' . $specials_index->fields['products_id']) . '">' . substr(strip_tags($specials_index->fields['products_name']), 0, 60) . '...' . '</a>';
                            $products_price = '<strong>' . zen_get_products_display_price($specials_index->fields['products_id']) . '</strong>';
                            $products_butt = '<a class="btn btn-success btn-sm add-to-cart-button" href="' . zen_href_link(zen_get_info_page($specials_index->fields['products_id']), 'cPath=' . $productsInCategory[$specials_index->fields['products_id']] . '&products_id=' . (int)$specials_index->fields['products_id']) . '">' . zen_image_button(BUTTON_IMAGE_GOTO_PROD_DETAILS, BUTTON_GOTO_PROD_DETAILS_ALT) . '</a>';
                            $products_img = (($specials_index->fields['products_image'] == '' and PRODUCTS_IMAGE_NO_IMAGE_STATUS == 0) ? '' : '<a href="' . zen_href_link(zen_get_info_page($specials_index->fields['products_id']), 'cPath=' . $productsInCategory[$specials_index->fields['products_id']] . '&products_id=' . (int)$specials_index->fields['products_id']) . '">' . zen_image(DIR_WS_IMAGES . $specials_index->fields['products_image'], $specials_index->fields['products_name'], MEDIUM_IMAGE_WIDTH, MEDIUM_IMAGE_HEIGHT) . '</a>');
                            $products_desc = substr(strip_tags($specials_index->fields['products_description']), 0, 50) . '...';
                            $num_products_count = ($specials_index_query == '') ? 0 : $specials_index->RecordCount();
                            
                            
                            ?>
                                        <li class="col-products">
                                        <div class="product-col" data-match-height="menu-carousel">
                                                <div class="img">
                                                    <?php echo $products_img; ?>
                                                </div>
                                                <div class="sale-label"><?php echo PRODUCT_LABEL_SALE;?></div>
                                            <div class="prod-info">
                                                <div class="price">
                                                	<?php echo $products_price;?>
                                                </div>
                                                <h5><?php echo $products_name1;?></h5>
                                                <div class="text">
                                                    <?php echo $products_desc;?>
                                                </div>
                                            </div>
                                          </div>
                                        </li>
                                        <?php $specials_index->MoveNextRandom();
                            } ?>
                    </ul>
				<a id="prev2" class="prev" href="#">&lt;</a>
				<a id="next2" class="next" href="#">&gt;</a>
                 </div>
            </div><!-- eof specials -->

	 </li>
	<?php } ?>
       <?php if(TM_MEGAMENU_QUICK == 'true') { ?> 
       <li class="quicklinks-li"><a class="drop"><?php echo HEADER_TITLE_QUICK_LINKS; ?></a><span class="label"><?php echo TM_MEGAMENU_QICKLINKS_LABEL; ?></span><!-- bof quick links  -->
             <div class="dropdown col-2 ">
                <div class="firstcolumn">
                    <?php if (EZPAGES_STATUS_HEADER == '1' or (EZPAGES_STATUS_HEADER == '2' and (strstr(EXCLUDE_ADMIN_IP_FOR_MAINTENANCE, $_SERVER['REMOTE_ADDR'])))) { ?>
						<?php require($template->get_template_dir('tpl_ezpages_bar_header.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_ezpages_bar_header.php'); ?>
                    <?php } ?>
                </div>
               </div>
        </li><!-- eof quick links -->
		<?php } ?>
		<?php if(TM_MEGAMENU_BRANDS == 'true') { ?> 
     	<li class="manufacturers-li"><a class="drop"><?php echo BOX_HEADING_MANUFACTURERS; ?><span class="label"><?php echo TM_MEGAMENU_BRANDS_LABEL; ?></span></a><!--bof shop by brand   -->
            <div class="dropdown col-3">
                <div class="firstcolumn">

              <ul >
              	<?php if(  TM_MEGAMENU_BRANDS_IMAGES == 'true') { ?> 
                	<?php 
                		$show_brand_images = true;
					
                    } else {
	                    $show_brand_images = false;
					?>
                <?php } ?>   
                
                
               <?php 
			    
				  $show_manufacturers= true;
				
				// for large lists of manufacturers uncomment this section
				/*
				  if (($_GET['main_page']==FILENAME_DEFAULT and ($_GET['cPath'] == '' or $_GET['cPath'] == 0)) or  ($request_type == 'SSL')) {
					$show_manufacturers= false;
				  } else {
					$show_manufacturers= true;
				  }
				*/
				
				// Set to true to display manufacturers images in place of names
				define('DISPLAY_MANUFACTURERS_IMAGES', $show_brand_images);
				
				if ($show_manufacturers) {
				
				// only check products if requested - this may slow down the processing of the manufacturers sidebox
				  if (PRODUCTS_MANUFACTURERS_STATUS == '1') {
					$manufacturer_sidebox_query = "select distinct m.manufacturers_id, m.manufacturers_name, m.manufacturers_image
											from " . TABLE_MANUFACTURERS . " m
											left join " . TABLE_PRODUCTS . " p on m.manufacturers_id = p.manufacturers_id
											where m.manufacturers_id = p.manufacturers_id and p.products_status= 1
											order by manufacturers_name";
				  } else {
					$manufacturer_sidebox_query = "select m.manufacturers_id, m.manufacturers_name, m.manufacturers_image
											from " . TABLE_MANUFACTURERS . " m
											order by manufacturers_name";
				  }
				
				  $manufacturer_sidebox = $db->Execute($manufacturer_sidebox_query);
				
				  if ($manufacturer_sidebox->RecordCount()>0) {
					$number_of_rows = $manufacturer_sidebox->RecordCount()+1;
				
				// Display a list
					$manufacturer_sidebox_array = array();
				//		kuroi: commented out to avoid starting list with text scrolling list entries such as "reset" and "please select"
				//    if (!isset($_GET['manufacturers_id']) || $_GET['manufacturers_id'] == '' ) {
				//      $manufacturer_sidebox_array[] = array('id' => '', 'text' => PULL_DOWN_ALL);
				//    } else {
				//      $manufacturer_sidebox_array[] = array('id' => '', 'text' => PULL_DOWN_MANUFACTURERS);
				//    }
				
					while (!$manufacturer_sidebox->EOF) {
					  $manufacturer_sidebox_name = ((strlen($manufacturer_sidebox->fields['manufacturers_name']) > MAX_DISPLAY_MANUFACTURER_NAME_LEN) ? substr($manufacturer_sidebox->fields['manufacturers_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . '..' : $manufacturer_sidebox->fields['manufacturers_name']);
					  $manufacturer_sidebox_image = $manufacturer_sidebox->fields['manufacturers_image'];
					  $manufacturer_sidebox_array[] =
						array('id' => $manufacturer_sidebox->fields['manufacturers_id'],
							  'text' => DISPLAY_MANUFACTURERS_IMAGES ?
								zen_image(DIR_WS_IMAGES . $manufacturer_sidebox_image, $manufacturer_sidebox_name) :
								$manufacturer_sidebox_name);
					  $manufacturer_sidebox->MoveNext();
					}
					  
				  }
				} // $show_manufacturers
								for ($i=0;$i<sizeof($manufacturer_sidebox_array);$i++) {
					  $content = '';
					  $content .= '<li ><a href="' . zen_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $manufacturer_sidebox_array[$i]['id']) . '">';
					  $content .= $manufacturer_sidebox_array[$i]['text'];
					  $content .= '</a></li>' . "\n";
					  echo $content;
					}
				?>
		    </ul>
            
		</div>
            </div>
        </li><!-- eof shop by brand    -->
        <?php } ?>
        
		<?php if(TM_MEGAMENU_INFO == 'true') { ?> 	
        <li class="information-li"><a class="drop"><?php echo HEADER_TITLE_INFORMATION; ?><span class="label"><?php echo TM_MEGAMENU_INFORMATION_LABEL; ?></span></a><!-- bof information -->
 
	    <div class="dropdown col-3">
        

            	    <h3><?php echo TITLE_GENERAL; ?></h3>
                    <ul>
                	<?php if (DEFINE_SITE_MAP_STATUS <= 1) { ?>
                	<li><a href="<?php echo zen_href_link(FILENAME_SITE_MAP); ?>"><?php echo BOX_INFORMATION_SITE_MAP; ?></a></li>
                	<?php } ?>
              		<?php if (MODULE_ORDER_TOTAL_GV_STATUS == 'true') { ?>
                	<li><a href="<?php echo zen_href_link(FILENAME_GV_FAQ, '', 'NONSSL'); ?>"><?php echo BOX_INFORMATION_GV; ?></a></li>
                	<?php } ?>
                	<?php if (MODULE_ORDER_TOTAL_COUPON_STATUS == 'true') { ?>
                	<li><a href="<?php echo zen_href_link(FILENAME_DISCOUNT_COUPON, '', 'NONSSL'); ?>"><?php echo BOX_INFORMATION_DISCOUNT_COUPONS; ?></a></li>
                	<?php } ?>
               		<?php if (SHOW_NEWSLETTER_UNSUBSCRIBE_LINK == 'true') { ?>
                	<li><a href="<?php echo zen_href_link(FILENAME_UNSUBSCRIBE, '', 'NONSSL'); ?>"><?php echo BOX_INFORMATION_UNSUBSCRIBE; ?></a></li>
                	<?php } ?>
                     </ul>   
					<div class="clearfix"></div>
                     <h3 class="second"><?php echo TITLE_CUSTOMERS; ?></h3>
                     <ul>
			<?php if ($_SESSION['customer_id']) { ?>
                	<li><a href="<?php echo zen_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>"><?php echo HEADER_TITLE_MY_ACCOUNT; ?></a></li>
                	<li><a href="<?php echo zen_href_link(FILENAME_LOGOFF, '', 'SSL'); ?>"><?php echo HEADER_TITLE_LOGOFF; ?></a></li>
                	<li><a href="<?php echo zen_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL'); ?>"><?php echo TITLE_NEWSLETTERS; ?></a></li>
                	<?php } else { ?>
                	<li><a href="<?php echo zen_href_link(FILENAME_LOGIN, '', 'SSL'); ?>"><?php echo HEADER_TITLE_LOGIN; ?></a></li>
                	<li><a href="<?php echo zen_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'); ?>"><?php echo HEADER_TITLE_CREATE_ACCOUNT; ?></a></li>
                	<?php } ?>
                    	<li><a href="<?php echo zen_href_link(FILENAME_CONTACT_US, '', 'NONSSL'); ?>"><?php echo BOX_INFORMATION_CONTACT; ?></a></li>
                        <?php if (DEFINE_SHIPPINGINFO_STATUS <= 1) { ?>
                	<li><a href="<?php echo zen_href_link(FILENAME_SHIPPING); ?>"><?php echo BOX_INFORMATION_SHIPPING; ?></a></li>
                        <?php } ?>
                        <?php if (DEFINE_PRIVACY_STATUS <= 1)  { ?>
                	<li><a href="<?php echo zen_href_link(FILENAME_PRIVACY); ?>"><?php echo BOX_INFORMATION_PRIVACY; ?></a></li>
                        <?php } ?>
                        <?php if (DEFINE_CONDITIONS_STATUS <= 1) { ?>
                	<li><a href="<?php echo zen_href_link(FILENAME_CONDITIONS); ?>"><?php echo BOX_INFORMATION_CONDITIONS; ?></a></li>
                        <?php } ?>
                     </ul>   

           </div>

  	</li><!-- eof information -->
	 <?php } ?>
    
    <?php if(TM_MEGAMENU_CUSTOM == 'true') { ?>
	<li class="customer_service"><a class="drop"><?php echo BOX_INFORMATION_SHIPPING; ?><span class="label"><?php echo TM_MEGAMENU_CUSTOM_LABEL; ?></span></a><!-- bof customer service -->
        
            <div class="dropdown col-5">
           
                <!-- <div class="col_cs">
                    <h3><?php echo TITLE_SHIPPING;?></h3>
                </div>-->

                <div class="col_cs">
      		     <p><!--<?php echo TEXT_SHIPPING_INFO;?> --></p>

<p>Most items are manufactured here. Takes time to process orders depending on the amount of stock. &nbsp;After Processing, orders will then be shipped according to shipping selected. &nbsp;Some occasions there could be times shipping provider may be changed if needed because of cost of shipping not matching shipping paid. &nbsp;We will however keep it to the same timeframe as shipping selected or better. &nbsp;Products are combined the best way for shipping so some orders could have more than one box. Shipping times are only for once the item is shipped and that is with the shipping provider. &nbsp;Please note USPS priority mail is not guaranteed.</p>

<p><strong>IMPORTANT:&nbsp;</strong><br />
Please review shipping address. &nbsp;We are not liable for a shipment going to incorrect address. &nbsp;All shipments ship to address entered or selected.</p>

<p><strong>Returns:</strong><br />
Please email customer service for returns.</p>
            
                </div>
       
               <!-- <div class="col_cs">
                      <h3><?php echo TITLE_DELIVERY;?></h3>
				</div>
				<div class="col_cs">
      		     <p><?php echo TEXT_DELIVERY_INFO;?></p>             
                </div>-->      
            </div><!-- eof customer service -->

	 </li>
	<?php } ?>
    
    </ul><!-- eof mega-menu -->
</div><!-- eof mega-wrapper -->
