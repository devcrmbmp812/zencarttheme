<?php
/**
* Finds related products based on on field
*					TABLE_PRODUCTS.products_family
* @date 2009-12-12
* @author: Joe McFrederick
* @license: GPL v2.0
* @package: Zencart
* @require: Field `products_family` VARCHAR(50) must be added to TABLE_PRODUCTS
*
*/

	$relatedProducts = $db->Execute("SELECT products_family FROM " . TABLE_PRODUCTS . " WHERE  products_id = '" . (int)$_GET['products_id'] ."'", 1);
	$title = '<h2 class="centerBoxHeading">'.BOX_HEADING_RELATED_PRODUCTS.'</h2>';	
	$products_family = '';
	if ($relatedProducts->RecordCount() > 0 AND !empty($relatedProducts->fields['products_family'])) 
	{
		$related_string = explode('|', $relatedProducts->fields['products_family']);

		foreach ($related_string as $family)
		{
			$products_family .= "OR p.products_family REGEXP '" . $family . "' ";
		}

		$products_family = " AND (" . substr($products_family, 2) . ") ";
		


		//Build query string to find related products
		$sql = "select p.products_id, pd.products_name,

					  pd.products_description, p.products_model,

					  p.products_quantity, p.products_image,

					  pd.products_url, p.products_price,

					  p.products_tax_class_id, p.products_date_added,

					  p.products_date_available, p.manufacturers_id, p.products_quantity,

					  p.products_weight, p.products_priced_by_attribute, p.product_is_free,

					  p.products_qty_box_status,

					  p.products_quantity_order_max,

					  p.products_discount_type, p.products_discount_type_from, p.products_sort_order, p.products_price_sorter

			   from   " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd

			   where  p.products_status = '1' 

			   " . $products_family . " 

			   and    p.products_id != '" . (int)$_GET['products_id'] . "'

			   and    pd.products_id = p.products_id

			   and    pd.language_id = '" . (int)$_SESSION['languages_id'] . "' 

			   LIMIT 9";

		//Run Query and check for related products
		$relatedResult = $db->Execute($sql);
		
		if($relatedResult->RecordCount() > 0)
		{
			?>
			<div class="centerBoxWrapper" id="relatedProducts">
            <?php echo $title;	?>
            <div class="row">
			<?php
				
				$row = 0;

				$col = 0;

				$list_box_contents = array();
				
				$title = '';

				//Build infoBox
				$list_box_contents[0] = array('params' => 'class="productListing-heading"',
											  'align' => 'center',
											  'text' => 'Related Products');
				
				// show only when 1 or more and equal to or greater than minimum set in admin

					
					$col_width =  ($relatedResult->RecordCount() < SHOW_PRODUCT_INFO_COLUMNS_RELATED_PRODUCTS) ? floor(100/$relatedResult->RecordCount()) : floor(100/SHOW_PRODUCT_INFO_COLUMNS_RELATED_PRODUCTS);
				
								
					while (!$relatedResult->EOF)
					{

					 $list_box_contents[$row][$col] = array('params' => 'class="col-xs-12 col-sm-3"',

					  'text' => (($relatedResult->fields['products_image'] == '' and PRODUCTS_IMAGE_NO_IMAGE_STATUS == 0) ? '' : '<div data-match-height="items-e" class="product-col"><div class="img"><a href="' . zen_href_link(zen_get_info_page($relatedResult->fields['products_id']), 'products_id=' . $relatedResult->fields['products_id']) . '">' . zen_image(DIR_WS_IMAGES . $relatedResult->fields['products_image'], $relatedResult->fields['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></div>') . '<div class="prod-info"><a class="product-name name" href="' . zen_href_link(zen_get_info_page($relatedResult->fields['products_id']), 'products_id=' . $relatedResult->fields['products_id']) . '">' . $relatedResult->fields['products_name'] . '</a></div></div>');



						$col ++;

						if ($col > (SHOW_PRODUCT_INFO_COLUMNS_RELATED_PRODUCTS - 1))
						{

							$col = 0;

							$row ++;

						}

					$relatedResult->MoveNext();

					}
				
				
				

				require($template->get_template_dir('tpl_columnar_display.php',DIR_WS_TEMPLATE, $current_page_base,'common'). '/tpl_columnar_display.php');
			?>
			</div>
            </div>

		<?php
		}
	}
/* End of File */