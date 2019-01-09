<?php
/**
 * @package admin
 * @copyright Copyright 2003-2011 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: collect_info.php 19330 2011-08-07 06:32:56Z drbyte $
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}
    $parameters = array('products_name' => '',
                       'products_description' => '',
                       'products_url' => '',
                       'products_id' => '',
                       'products_quantity' => '',
                       'products_model' => '',
                       'products_image' => '',
                       'products_price' => '',
                       'products_virtual' => DEFAULT_PRODUCT_PRODUCTS_VIRTUAL,
                       'products_weight' => '',
                       'products_date_added' => '',
                       'products_last_modified' => '',
                       'products_date_available' => '',
                       'products_status' => '',
                       'products_tax_class_id' => DEFAULT_PRODUCT_TAX_CLASS_ID,
                       'manufacturers_id' => '',
                       'products_quantity_order_min' => '',
                       'products_quantity_order_units' => '',
                       'products_priced_by_attribute' => '',
                       'product_is_free' => '',
                       'product_is_call' => '',
                       'products_quantity_mixed' => '',
                       'product_is_always_free_shipping' => DEFAULT_PRODUCT_PRODUCTS_IS_ALWAYS_FREE_SHIPPING,
                       'products_qty_box_status' => PRODUCTS_QTY_BOX_STATUS,
                       'products_quantity_order_max' => '0',
                       'products_sort_order' => '0',
                       'products_discount_type' => '0',
                       'products_discount_type_from' => '0',
                       'products_price_sorter' => '0',
                       'master_categories_id' => ''
                       );

    $pInfo = new objectInfo($parameters);

     if (isset($_GET['pID']) && empty($_POST) && MODULE_SHIPPING_AUCTIONINC_STATUS=='On') {
      $product = $db->Execute("select pd.products_name, pd.products_description, pd.products_url,
                                      p.products_id, p.products_quantity, p.products_model,
                                      p.products_image, p.products_price, p.products_virtual, p.products_weight,
                                      p.products_date_added, p.products_last_modified,
                                      date_format(p.products_date_available, '%Y-%m-%d') as
                                      products_date_available, p.products_status, p.products_tax_class_id,
                                      p.manufacturers_id,
                                      p.products_quantity_order_min, p.products_quantity_order_units, 
                             p.products_priced_by_attribute,                            
                                      p.product_is_free, p.product_is_call, p.products_quantity_mixed,
                                      p.product_is_always_free_shipping,p.products_qty_box_status, p.products_quantity_order_max,
                                      p.products_sort_order,
                                      p.products_discount_type, p.products_discount_type_from,
                                      p.products_price_sorter, p.master_categories_id,  
                    
                                   p.actioninc_calcmethod,p.actioninc_length, p.actioninc_width, p.actioninc_height,
                                   p.actioninc_package, p.actioninc_origincode, p.actioninc_insurable,
                                   p.actioninc_handlingcode, p.actioninc_fixeddollaramount,
                                   p.actioninc_servicecodes, p.actioninc_specialaccessorialfees, 
                                   p.actioninc_fixedfeecode,p.actioninc_fixedfee1, p.actioninc_fixedfee2
                    
                               from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd
                              where p.products_id = '" . (int)$_GET['pID'] . "'
                              and p.products_id = pd.products_id
                              and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'");

         $pInfo->objectInfo($product->fields);
    } 
  else if (isset($_GET['pID']) && empty($_POST)) {
  $product = $db->Execute("select pd.products_name, pd.products_description, pd.products_url,
                                      p.products_id, p.products_quantity, p.products_model,
                                      p.products_image, p.products_price, p.products_virtual, p.products_weight,
                                      p.products_date_added, p.products_last_modified,
                                      date_format(p.products_date_available, '%Y-%m-%d') as
                                      products_date_available, p.products_status, p.products_tax_class_id,
                                      p.manufacturers_id,
                                      p.products_quantity_order_min, p.products_quantity_order_units, 
                                   p.products_priced_by_attribute,                            
                                      p.product_is_free, p.product_is_call, p.products_quantity_mixed,
                                      p.product_is_always_free_shipping,p.products_qty_box_status,    
                                      p.products_quantity_order_max,
                                      p.products_sort_order,
                                      p.products_discount_type, p.products_discount_type_from,
                                      p.products_price_sorter, p.master_categories_id
                    
                              from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd
                              where p.products_id = '" . (int)$_GET['pID'] . "'
                              and p.products_id = pd.products_id
                              and pd.language_id = '" . (int)$_SESSION['languages_id'] . "'");

           $pInfo->objectInfo($product->fields);

    } elseif (zen_not_null($_POST)) {
      $pInfo->objectInfo($_POST);
      $products_name = $_POST['products_name'];
      $products_description = $_POST['products_description'];
      $products_url = $_POST['products_url'];
    }

    $manufacturers_array = array(array('id' => '', 'text' => TEXT_NONE));
    $manufacturers = $db->Execute("select manufacturers_id, manufacturers_name
                                   from " . TABLE_MANUFACTURERS . " order by manufacturers_name");
    while (!$manufacturers->EOF) {
      $manufacturers_array[] = array('id' => $manufacturers->fields['manufacturers_id'],
                                     'text' => $manufacturers->fields['manufacturers_name']);
      $manufacturers->MoveNext();
    }

    $tax_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
    $tax_class = $db->Execute("select tax_class_id, tax_class_title
                                     from " . TABLE_TAX_CLASS . " order by tax_class_title");
    while (!$tax_class->EOF) {
      $tax_class_array[] = array('id' => $tax_class->fields['tax_class_id'],
                                 'text' => $tax_class->fields['tax_class_title']);
      $tax_class->MoveNext();
    }

    $languages = zen_get_languages();

    if (!isset($pInfo->products_status)) $pInfo->products_status = '1';
    switch ($pInfo->products_status) {
      case '0': $in_status = false; $out_status = true; break;
      case '1':
      default: $in_status = true; $out_status = false;
        break;
    }
// set to out of stock if categories_status is off and new product or existing products_status is off
    if (zen_get_categories_status($current_category_id) == '0' and $pInfo->products_status != '1') {
      $pInfo->products_status = 0;
      $in_status = false;
      $out_status = true;
    }

// Virtual Products
    if (!isset($pInfo->products_virtual)) $pInfo->products_virtual = DEFAULT_PRODUCT_PRODUCTS_VIRTUAL;
    switch ($pInfo->products_virtual) {
      case '0': $is_virtual = false; $not_virtual = true; break;
      case '1': $is_virtual = true; $not_virtual = false; break;
      default: $is_virtual = false; $not_virtual = true;
    }
// Always Free Shipping
    if (!isset($pInfo->product_is_always_free_shipping)) $pInfo->product_is_always_free_shipping = DEFAULT_PRODUCT_PRODUCTS_IS_ALWAYS_FREE_SHIPPING;
    switch ($pInfo->product_is_always_free_shipping) {
      case '0': $is_product_is_always_free_shipping = false; $not_product_is_always_free_shipping = true; $special_product_is_always_free_shipping = false; break;
      case '1': $is_product_is_always_free_shipping = true; $not_product_is_always_free_shipping = false; $special_product_is_always_free_shipping = false; break;
      case '2': $is_product_is_always_free_shipping = false; $not_product_is_always_free_shipping = false; $special_product_is_always_free_shipping = true; break;
      default: $is_product_is_always_free_shipping = false; $not_product_is_always_free_shipping = true; $special_product_is_always_free_shipping = false; break;
    }
// products_qty_box_status shows
    if (!isset($pInfo->products_qty_box_status)) $pInfo->products_qty_box_status = PRODUCTS_QTY_BOX_STATUS;
    switch ($pInfo->products_qty_box_status) {
      case '0': $is_products_qty_box_status = false; $not_products_qty_box_status = true; break;
      case '1': $is_products_qty_box_status = true; $not_products_qty_box_status = false; break;
      default: $is_products_qty_box_status = true; $not_products_qty_box_status = false;
    }
// Product is Priced by Attributes
    if (!isset($pInfo->products_priced_by_attribute)) $pInfo->products_priced_by_attribute = '0';
    switch ($pInfo->products_priced_by_attribute) {
      case '0': $is_products_priced_by_attribute = false; $not_products_priced_by_attribute = true; break;
      case '1': $is_products_priced_by_attribute = true; $not_products_priced_by_attribute = false; break;
      default: $is_products_priced_by_attribute = false; $not_products_priced_by_attribute = true;
    }
// Product is Free
    if (!isset($pInfo->product_is_free)) $pInfo->product_is_free = '0';
    switch ($pInfo->product_is_free) {
      case '0': $in_product_is_free = false; $out_product_is_free = true; break;
      case '1': $in_product_is_free = true; $out_product_is_free = false; break;
      default: $in_product_is_free = false; $out_product_is_free = true;
    }
// Product is Call for price
    if (!isset($pInfo->product_is_call)) $pInfo->product_is_call = '0';
    switch ($pInfo->product_is_call) {
      case '0': $in_product_is_call = false; $out_product_is_call = true; break;
      case '1': $in_product_is_call = true; $out_product_is_call = false; break;
      default: $in_product_is_call = false; $out_product_is_call = true;
    }
// Products can be purchased with mixed attributes retail
    if (!isset($pInfo->products_quantity_mixed)) $pInfo->products_quantity_mixed = '0';
    switch ($pInfo->products_quantity_mixed) {
      case '0': $in_products_quantity_mixed = false; $out_products_quantity_mixed = true; break;
      case '1': $in_products_quantity_mixed = true; $out_products_quantity_mixed = false; break;
      default: $in_products_quantity_mixed = true; $out_products_quantity_mixed = false;
    }

// set image overwrite
  $on_overwrite = true;
  $off_overwrite = false;
// set image delete
  $on_image_delete = false;
  $off_image_delete = true;
?>
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
<script language="JavaScript" src="includes/javascript/auctioninc.js"></script>
<script language="javascript"><!--
  var dateAvailable = new ctlSpiffyCalendarBox("dateAvailable", "new_product", "products_date_available","btnDate1","<?php echo $pInfo->products_date_available; ?>",scBTNMODE_CUSTOMBLUE);
//--></script>
<script language="javascript"><!--
var tax_rates = new Array();
<?php
    for ($i=0, $n=sizeof($tax_class_array); $i<$n; $i++) {
      if ($tax_class_array[$i]['id'] > 0) {
        echo 'tax_rates["' . $tax_class_array[$i]['id'] . '"] = ' . zen_get_tax_rate_value($tax_class_array[$i]['id']) . ';' . "\n";
      }
    }
?>

function doRound(x, places) {
  return Math.round(x * Math.pow(10, places)) / Math.pow(10, places);
}

function getTaxRate() {
  var selected_value = document.forms["new_product"].products_tax_class_id.selectedIndex;
  var parameterVal = document.forms["new_product"].products_tax_class_id[selected_value].value;

  if ( (parameterVal > 0) && (tax_rates[parameterVal] > 0) ) {
    return tax_rates[parameterVal];
  } else {
    return 0;
  }
}

function updateGross() {
  var taxRate = getTaxRate();
  var grossValue = document.forms["new_product"].products_price.value;

  if (taxRate > 0) {
    grossValue = grossValue * ((taxRate / 100) + 1);
  }

  document.forms["new_product"].products_price_gross.value = doRound(grossValue, 4);
}

function updateNet() {
  var taxRate = getTaxRate();
  var netValue = document.forms["new_product"].products_price_gross.value;

  if (taxRate > 0) {
    netValue = netValue / ((taxRate / 100) + 1);
  }

  document.forms["new_product"].products_price.value = doRound(netValue, 4);
}
//--></script>
    <?php
//  echo $type_admin_handler;
echo zen_draw_form('new_product', $type_admin_handler , 'cPath=' . $cPath . (isset($_GET['product_type']) ? '&product_type=' . $_GET['product_type'] : '') . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '') . '&action=new_product_preview' . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '') . ( (isset($_GET['search']) && !empty($_GET['search'])) ? '&search=' . $_GET['search'] : '') . ( (isset($_POST['search']) && !empty($_POST['search']) && empty($_GET['search'])) ? '&search=' . $_POST['search'] : ''), 'post', 'enctype="multipart/form-data"');
    ?>
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo sprintf(TEXT_NEW_PRODUCT, zen_output_generated_category_path($current_category_id)); ?></td>
            <td class="pageHeading" align="right"><?php echo zen_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main" align="right"><?php echo zen_draw_hidden_field('products_date_added', (zen_not_null($pInfo->products_date_added) ? $pInfo->products_date_added : date('Y-m-d'))) . zen_image_submit('button_preview.gif', IMAGE_PREVIEW) . '&nbsp;&nbsp;<a href="' . zen_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '') . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '') . ( (isset($_GET['search']) && !empty($_GET['search'])) ? '&search=' . $_GET['search'] : '') . ( (isset($_POST['search']) && !empty($_POST['search']) && empty($_GET['search'])) ? '&search=' . $_POST['search'] : '')) . '">' . zen_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
      </tr>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
<?php
// show when product is linked
if (zen_get_product_is_linked($_GET['pID']) == 'true' and $_GET['pID'] > 0) {
?>
          <tr>
            <td class="main"><?php echo TEXT_MASTER_CATEGORIES_ID; ?></td>
            <td class="main">
              <?php
                // echo zen_draw_pull_down_menu('products_tax_class_id', $tax_class_array, $pInfo->products_tax_class_id);
                echo zen_image(DIR_WS_IMAGES . 'icon_yellow_on.gif', IMAGE_ICON_LINKED) . '&nbsp;&nbsp;';
                echo zen_draw_pull_down_menu('master_category', zen_get_master_categories_pulldown($_GET['pID']), $pInfo->master_categories_id); ?>
            </td>
          </tr>
<?php } else { ?>
          <tr>
            <td class="main"><?php echo TEXT_MASTER_CATEGORIES_ID; ?></td>
            <td class="main"><?php echo TEXT_INFO_ID . ($_GET['pID'] > 0 ? $pInfo->master_categories_id  . ' ' . zen_get_category_name($pInfo->master_categories_id, $_SESSION['languages_id']) : $current_category_id  . ' ' . zen_get_category_name($current_category_id, $_SESSION['languages_id'])); ?></td>
          </tr>
<?php } ?>
          <tr>
            <td colspan="2" class="main"><?php echo TEXT_INFO_MASTER_CATEGORIES_ID; ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '100%', '2'); ?></td>
          </tr>
<?php
// hidden fields not changeable on products page
echo zen_draw_hidden_field('master_categories_id', $pInfo->master_categories_id);
echo zen_draw_hidden_field('products_discount_type', $pInfo->products_discount_type);
echo zen_draw_hidden_field('products_discount_type_from', $pInfo->products_discount_type_from);
echo zen_draw_hidden_field('products_price_sorter', $pInfo->products_price_sorter);
?>
          <tr>
            <td colspan="2" class="main" align="center"><?php echo (zen_get_categories_status($current_category_id) == '0' ? TEXT_CATEGORIES_STATUS_INFO_OFF : '') . ($out_status == true ? ' ' . TEXT_PRODUCTS_STATUS_INFO_OFF : ''); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_STATUS; ?></td>
            <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_radio_field('products_status', '1', $in_status) . '&nbsp;' . TEXT_PRODUCT_AVAILABLE . '&nbsp;' . zen_draw_radio_field('products_status', '0', $out_status) . '&nbsp;' . TEXT_PRODUCT_NOT_AVAILABLE; ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_DATE_AVAILABLE; ?><br /><small>(YYYY-MM-DD)</small></td>
            <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;'; ?><script language="javascript">dateAvailable.writeControl(); dateAvailable.dateFormat="yyyy-MM-dd";</script></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_MANUFACTURER; ?></td>
            <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_pull_down_menu('manufacturers_id', $manufacturers_array, $pInfo->manufacturers_id); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
<?php
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <tr>
            <td class="main"><?php if ($i == 0) echo TEXT_PRODUCTS_NAME; ?></td>
            <td class="main"><?php echo zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . zen_draw_input_field('products_name[' . $languages[$i]['id'] . ']', htmlspecialchars(isset($products_name[$languages[$i]['id']]) ? stripslashes($products_name[$languages[$i]['id']]) : zen_get_products_name($pInfo->products_id, $languages[$i]['id']), ENT_COMPAT, CHARSET, TRUE), zen_set_field_length(TABLE_PRODUCTS_DESCRIPTION, 'products_name')); ?></td>
          </tr>
<?php
    }
?>

          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>

          <tr>
            <td class="main"><?php echo TEXT_PRODUCT_IS_FREE; ?></td>
            <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_radio_field('product_is_free', '1', ($in_product_is_free==1)) . '&nbsp;' . TEXT_YES . '&nbsp;&nbsp;' . zen_draw_radio_field('product_is_free', '0', ($in_product_is_free==0)) . '&nbsp;' . TEXT_NO . ' ' . ($pInfo->product_is_free == 1 ? '<span class="errorText">' . TEXT_PRODUCTS_IS_FREE_EDIT . '</span>' : ''); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCT_IS_CALL; ?></td>
            <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_radio_field('product_is_call', '1', ($in_product_is_call==1)) . '&nbsp;' . TEXT_YES . '&nbsp;&nbsp;' . zen_draw_radio_field('product_is_call', '0', ($in_product_is_call==0)) . '&nbsp;' . TEXT_NO . ' ' . ($pInfo->product_is_call == 1 ? '<span class="errorText">' . TEXT_PRODUCTS_IS_CALL_EDIT . '</span>' : ''); ?></td>
          </tr>

          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_PRICED_BY_ATTRIBUTES; ?></td>
            <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_radio_field('products_priced_by_attribute', '1', $is_products_priced_by_attribute) . '&nbsp;' . TEXT_PRODUCT_IS_PRICED_BY_ATTRIBUTE . '&nbsp;&nbsp;' . zen_draw_radio_field('products_priced_by_attribute', '0', $not_products_priced_by_attribute) . '&nbsp;' . TEXT_PRODUCT_NOT_PRICED_BY_ATTRIBUTE . ' ' . ($pInfo->products_priced_by_attribute == 1 ? '<span class="errorText">' . TEXT_PRODUCTS_PRICED_BY_ATTRIBUTES_EDIT . '</span>' : ''); ?></td>
          </tr>

          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr bgcolor="#ebebff">
            <td class="main"><?php echo TEXT_PRODUCTS_TAX_CLASS; ?></td>
            <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_pull_down_menu('products_tax_class_id', $tax_class_array, $pInfo->products_tax_class_id, 'onchange="updateGross()"'); ?></td>
          </tr>
          <tr bgcolor="#ebebff">
            <td class="main"><?php echo TEXT_PRODUCTS_PRICE_NET; ?></td>
            <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_input_field('products_price', $pInfo->products_price, 'onKeyUp="updateGross()"'); ?></td>
          </tr>
          <tr bgcolor="#ebebff">
            <td class="main"><?php echo TEXT_PRODUCTS_PRICE_GROSS; ?></td>
            <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_input_field('products_price_gross', $pInfo->products_price, 'OnKeyUp="updateNet()"'); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_VIRTUAL; ?></td>
            <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_radio_field('products_virtual', '1', $is_virtual) . '&nbsp;' . TEXT_PRODUCT_IS_VIRTUAL . '&nbsp;' . zen_draw_radio_field('products_virtual', '0', $not_virtual) . '&nbsp;' . TEXT_PRODUCT_NOT_VIRTUAL . ' ' . ($pInfo->products_virtual == 1 ? '<br /><span class="errorText">' . TEXT_VIRTUAL_EDIT . '</span>' : ''); ?></td>
          </tr>

          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main" valign="top"><?php echo TEXT_PRODUCTS_IS_ALWAYS_FREE_SHIPPING; ?></td>
            <td class="main" valign="top"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_radio_field('product_is_always_free_shipping', '1', $is_product_is_always_free_shipping) . '&nbsp;' . TEXT_PRODUCT_IS_ALWAYS_FREE_SHIPPING . '&nbsp;' . zen_draw_radio_field('product_is_always_free_shipping', '0', $not_product_is_always_free_shipping) . '&nbsp;' . TEXT_PRODUCT_NOT_ALWAYS_FREE_SHIPPING  . '<br />' . zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_radio_field('product_is_always_free_shipping', '2', $special_product_is_always_free_shipping) . '&nbsp;' . TEXT_PRODUCT_SPECIAL_ALWAYS_FREE_SHIPPING . ' ' . ($pInfo->product_is_always_free_shipping == 1 ? '<br /><span class="errorText">' . TEXT_FREE_SHIPPING_EDIT . '</span>' : ''); ?></td>
          </tr>

          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_QTY_BOX_STATUS; ?></td>
            <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_radio_field('products_qty_box_status', '1', $is_products_qty_box_status) . '&nbsp;' . TEXT_PRODUCTS_QTY_BOX_STATUS_ON . '&nbsp;' . zen_draw_radio_field('products_qty_box_status', '0', $not_products_qty_box_status) . '&nbsp;' . TEXT_PRODUCTS_QTY_BOX_STATUS_OFF . ' ' . ($pInfo->products_qty_box_status == 0 ? '<br /><span class="errorText">' . TEXT_PRODUCTS_QTY_BOX_STATUS_EDIT . '</span>' : ''); ?></td>
          </tr>

          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>

          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_QUANTITY_MIN_RETAIL; ?></td>
            <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_input_field('products_quantity_order_min', ($pInfo->products_quantity_order_min == 0 ? 1 : $pInfo->products_quantity_order_min)); ?></td>
          </tr>

          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_QUANTITY_MAX_RETAIL; ?></td>
            <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_input_field('products_quantity_order_max', $pInfo->products_quantity_order_max); ?>&nbsp;&nbsp;<?php echo TEXT_PRODUCTS_QUANTITY_MAX_RETAIL_EDIT; ?></td>
          </tr>

          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_QUANTITY_UNITS_RETAIL; ?></td>
            <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_input_field('products_quantity_order_units', ($pInfo->products_quantity_order_units == 0 ? 1 : $pInfo->products_quantity_order_units)); ?></td>
          </tr>

          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_MIXED; ?></td>
            <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_radio_field('products_quantity_mixed', '1', $in_products_quantity_mixed) . '&nbsp;' . TEXT_YES . '&nbsp;&nbsp;' . zen_draw_radio_field('products_quantity_mixed', '0', $out_products_quantity_mixed) . '&nbsp;' . TEXT_NO; ?></td>
          </tr>

          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>

<script language="javascript"><!--
updateGross();
//--></script>
<?php
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <tr>
            <td class="main" valign="top"><?php if ($i == 0) echo TEXT_PRODUCTS_DESCRIPTION; ?></td>
            <td colspan="2"><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" width="25" valign="top"><?php echo zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']); ?>&nbsp;</td>
                <td class="main" width="100%"><?php echo zen_draw_textarea_field('products_description[' . $languages[$i]['id'] . ']', 'soft', '100%', '30', htmlspecialchars((isset($products_description[$languages[$i]['id']])) ? stripslashes($products_description[$languages[$i]['id']]) : zen_get_products_description($pInfo->products_id, $languages[$i]['id']), ENT_COMPAT, CHARSET, TRUE)); //,'id="'.'products_description' . $languages[$i]['id'] . '"'); ?></td>
              </tr>
            </table></td>
          </tr>
<?php
    }
?>
          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_QUANTITY; ?></td>
            <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_input_field('products_quantity', $pInfo->products_quantity); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_MODEL; ?></td>
            <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_input_field('products_model', htmlspecialchars(stripslashes($pInfo->products_model), ENT_COMPAT, CHARSET, TRUE), zen_set_field_length(TABLE_PRODUCTS, 'products_model')); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
<?php
  $dir = @dir(DIR_FS_CATALOG_IMAGES);
  $dir_info[] = array('id' => '', 'text' => "Main Directory");
  while ($file = $dir->read()) {
    if (is_dir(DIR_FS_CATALOG_IMAGES . $file) && strtoupper($file) != 'CVS' && $file != "." && $file != "..") {
      $dir_info[] = array('id' => $file . '/', 'text' => $file);
    }
  }
  $dir->close();
  sort($dir_info);

  $default_directory = substr( $pInfo->products_image, 0,strpos( $pInfo->products_image, '/')+1);
?>

          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_black.gif', '100%', '3'); ?></td>
          </tr>

          <tr>
            <td class="main" colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main"><?php echo TEXT_PRODUCTS_IMAGE; ?></td>
                <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_file_field('products_image') . '&nbsp;' . ($pInfo->products_image !='' ? TEXT_IMAGE_CURRENT . $pInfo->products_image : TEXT_IMAGE_CURRENT . '&nbsp;' . NONE) . zen_draw_hidden_field('products_previous_image', $pInfo->products_image); ?></td>
                <td valign = "center" class="main"><?php echo TEXT_PRODUCTS_IMAGE_DIR; ?>&nbsp;<?php echo zen_draw_pull_down_menu('img_dir', $dir_info, $default_directory); ?></td>
						  </tr>
              <tr>
                <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15'); ?></td>
                <td class="main" valign="top"><?php echo TEXT_IMAGES_DELETE . ' ' . zen_draw_radio_field('image_delete', '0', $off_image_delete) . '&nbsp;' . TABLE_HEADING_NO . ' ' . zen_draw_radio_field('image_delete', '1', $on_image_delete) . '&nbsp;' . TABLE_HEADING_YES; ?></td>
	  	    	  </tr>

              <tr>
                <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15'); ?></td>
                <td colspan="3" class="main" valign="top"><?php echo TEXT_IMAGES_OVERWRITE  . ' ' . zen_draw_radio_field('overwrite', '0', $off_overwrite) . '&nbsp;' . TABLE_HEADING_NO . ' ' . zen_draw_radio_field('overwrite', '1', $on_overwrite) . '&nbsp;' . TABLE_HEADING_YES; ?>
                  <?php echo '<br />' . TEXT_PRODUCTS_IMAGE_MANUAL . '&nbsp;' . zen_draw_input_field('products_image_manual'); ?></td>
              </tr>
            </table></td>
          </tr>

          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_black.gif', '100%', '3'); ?></td>
          </tr>

<?php
    for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
?>
          <tr>
            <td class="main"><?php if ($i == 0) echo TEXT_PRODUCTS_URL . '<br /><small>' . TEXT_PRODUCTS_URL_WITHOUT_HTTP . '</small>'; ?></td>
            <td class="main"><?php echo zen_image(DIR_WS_CATALOG_LANGUAGES . $languages[$i]['directory'] . '/images/' . $languages[$i]['image'], $languages[$i]['name']) . '&nbsp;' . zen_draw_input_field('products_url[' . $languages[$i]['id'] . ']', htmlspecialchars(isset($products_url[$languages[$i]['id']]) ? $products_url[$languages[$i]['id']] : zen_get_products_url($pInfo->products_id, $languages[$i]['id']), ENT_COMPAT, CHARSET, TRUE), zen_set_field_length(TABLE_PRODUCTS_DESCRIPTION, 'products_url')); ?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_WEIGHT; ?></td>
            <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_input_field('products_weight', $pInfo->products_weight); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_SORT_ORDER; ?></td>
            <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_input_field('products_sort_order', $pInfo->products_sort_order); ?></td>
          </tr>
          <!-- AuctionInc Fields START --> 
          <?php if(MODULE_SHIPPING_AUCTIONINC_STATUS=='On') { ?>
          <tr>
                <td colspan="2"><?php echo zen_draw_separator('pixel_black.gif', '100%', '3'); ?></td>
              </tr>
          <tr>
                <td colspan="2">
          <fieldset>
          <legend><strong>AuctionInc Shipping Information</strong></legend>
          <div class="buttonRow back" style="float:left; height:20px;"><a href="javascript:AuctionIncpopupWindow('http://www.auctioninc.com/info/page/zencart_auctioninc_settings')"><strong>AuctionInc Help</strong></a></div>
          <table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr bgcolor="#ebebff">
              <td class="main" style="width:279px;"><strong><?php echo 'Calculation Method'; ?></strong></td>
              <td class="main"><?php $actioninc_calcmethod = array(array('id' => 'C', 'text' => 'Calculated Shipping'),array('id' => 'F', 'text' => 'Fixed Shipping'),array('id' => 'N', 'text' => 'Free Shipping'),array('id' => 'CI', 'text' => 'Free Domestic Shipping'));
               echo zen_draw_pull_down_menu('actioninc_calcmethod', $actioninc_calcmethod, $pInfo->actioninc_calcmethod, 'onchange="displayactionincshippingmethod(this.value)" id="actioninc_calcmethod"'); ?>
              </td>
            </tr>
          </table>
          
          <table id="actioninc_calculationmethod" style="display:<?php if($pInfo->actioninc_calcmethod=='F' || $pInfo->actioninc_calcmethod=='N') { echo 'none'; } ?>" border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
                <td class="main" style="width:250px;"><strong>Package:</strong></td>
                <td class="main"><?php $actioninc_package = array(array('id' => 'T', 'text' =>'Together with other items'),array('id' => 'S', 'text' => 'Separately'));
               echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_pull_down_menu('actioninc_package', $actioninc_package, $pInfo->actioninc_package, 'id="actioninc_package"'); ?>
          </td>
             </tr>          
          <tr>    
                <td class="main">Dimensions:</td>
                <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;'; ?><?php if($pInfo->actioninc_length) { $actioninc_length=$pInfo->actioninc_length;  } else { $actioninc_length='Length'; } echo zen_draw_input_field('actioninc_length', $actioninc_length,'id="actioninc_length" onblur="extractNumber(this,2,false); onblurtextstatuslength(this.id);" onfocus="onfocustextstatuslength(this.id);" onkeyup="extractNumber(this,2,false);" onkeypress="return blockNonNumbers(this, event, true, false);" style="width:53px;"'); ?>&nbsp;<?php if($pInfo->actioninc_width) { $actioninc_width=$pInfo->actioninc_width;  } else { $actioninc_width='Width'; } echo zen_draw_input_field('actioninc_width', $actioninc_width,'id="actioninc_width" onblur="extractNumber(this,2,false); onblurtextstatuswidth(this.id);" onfocus="onfocustextstatuswidth(this.id);" onkeyup="extractNumber(this,2,false);" onkeypress="return blockNonNumbers(this, event, true, false);" style="width:53px;"'); ?>&nbsp;<?php if($pInfo->actioninc_height) { $actioninc_height=$pInfo->actioninc_height;  } else { $actioninc_height='Height'; } echo zen_draw_input_field('actioninc_height', $actioninc_height,'id="actioninc_height" onblur="extractNumber(this,2,false); onblurtextstatusheight(this.id);" onfocus="onfocustextstatusheight(this.id);" onkeyup="extractNumber(this,2,false);" onkeypress="return blockNonNumbers(this, event, true, false);" style="width:53px;"'); ?></td>
              </tr>
          <tr>
                <td class="main"><strong>Insurable:</strong></td>
                <td class="main"><img width="24" height="15" border="0" alt="" src="images/pixel_trans.gif">&nbsp;<input type="checkbox" name="actioninc_insurable" id="actioninc_insurable" <?php if($pInfo->actioninc_insurable!='No') { ?> checked="checked" <?php } ?> value="Yes" />Yes
          </td>
              </tr>
          <tr>
                <td class="main">Origin Code:</td>
                <td class="main"><?php if($pInfo->actioninc_origincode) { $actioninc_origincode=$pInfo->actioninc_origincode;  } else { $actioninc_origincode='default'; } echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_input_field('actioninc_origincode', $actioninc_origincode,'id="actioninc_origincode" onblur="onblurtextstatusorigincode(this.id);" onfocus="onfocustextstatusorigincode(this.id);"'); ?>
          </td>
              </tr>
          <tr>
                <td class="main">Supplemental Item Handling:</td>
                <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;'; ?><?php if($pInfo->actioninc_fixeddollaramount) { $actioninc_fixeddollaramount=$pInfo->actioninc_fixeddollaramount;  } else { $actioninc_fixeddollaramount='Fixed dollar amount'; } echo zen_draw_input_field('actioninc_fixeddollaramount', $actioninc_fixeddollaramount,'id="actioninc_fixeddollaramount" onblur="handlingcode(this.id); onblurtextstatusfixeddollaramt(this.id);" onfocus="onfocustextstatusfixeddollaramt(this.id);"'); ?>&nbsp;(OR)&nbsp;<?php if($pInfo->actioninc_handlingcode) { $actioninc_handlingcode=$pInfo->actioninc_handlingcode;  } else { $actioninc_handlingcode='Handling code'; } echo zen_draw_input_field('actioninc_handlingcode', $actioninc_handlingcode,'id="actioninc_handlingcode" onblur="handlingcode(this.id); onblurtextstatushandlingcode(this.id);" onfocus="onfocustextstatushandlingcode(this.id);"'); ?>
          </td>
              </tr>
          <tr>
                <td class="main">On-Demand Service Codes:</td>
                <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_input_field('actioninc_servicecodes', $pInfo->actioninc_servicecodes,'id="actioninc_servicecodes"'); ?>&nbsp;<a href="javascript:AuctionIncpopupWindow('http://www.auctioninc.com/info/page/api_appendix#service_codes')">[code lookup]</a>
                  <br />&nbsp;<span style="padding-left:24px;font-size:10px;">Enter one or more codes, separated by commas</span>
          </td>
              </tr>
          <tr>
                <td class="main">Special Accessorial Fees:</td>
                <td class="main"><?php echo zen_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;' . zen_draw_input_field('actioninc_specialaccessorialfees', $pInfo->actioninc_specialaccessorialfees,'id="actioninc_specialaccessorialfees"'); ?>&nbsp;<a href="javascript:AuctionIncpopupWindow('http://www.auctioninc.com/info/page/api_schema#special_services_schema')">[code lookup]</a>
                  <br />&nbsp;<span style="padding-left:24px;font-size:10px;">Enter one or more codes, separated by commas</span>
          </td>
              </tr>
        </table>
        <table id="actioninc_fixedfee" style="display:<?php if($pInfo->actioninc_calcmethod=='F') { echo 'block'; } else { echo 'none'; } ?>;" border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
          <td class="main" style="width:281px;"><strong><?php echo 'Fixed Fee Code:'; ?></strong></td>
          <td class="main"><?php echo zen_draw_input_field('actioninc_fixedfeecode', $pInfo->actioninc_fixedfeecode,'id="actioninc_fixedfeecode" onblur="fixedfee(this.id);"'); ?></td>
          </tr>
          <tr><td>&nbsp;&nbsp;[OR]</td><td><?php echo zen_draw_separator('pixel_trans.gif', '24', '15')?></td></tr>
        <tr>
          <td class="main"><strong><?php echo 'Fixed Fee1:'; ?></strong></td>
          <td class="main"><?php echo zen_draw_input_field('actioninc_fixedfee1', $pInfo->actioninc_fixedfee1,'id="actioninc_fixedfee1" onblur="fixedfee(this.id);" onkeyup="extractNumber(this,2,false);" onkeypress="return blockNonNumbers(this, event, true, false);"'); ?></td>
          </tr>
        <tr>
          <td class="main"><strong><?php echo 'Fixed Fee2:'; ?></strong></td>
          <td class="main"><?php echo zen_draw_input_field('actioninc_fixedfee2', $pInfo->actioninc_fixedfee2,'id="actioninc_fixedfee2" onblur="fixedfee(this.id);" onkeyup="extractNumber(this,2,false);" onkeypress="return blockNonNumbers(this, event, true, false);"'); ?></td>
          </tr>
        </table>
        </fieldset>
        </td>
            </tr>
        <?php } ?>
        <!-- AuctionInc Fields END -->          
        </table></td>
      </tr>
      <tr>
        <td><?php echo zen_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main" align="right"><?php echo zen_draw_hidden_field('products_date_added', (zen_not_null($pInfo->products_date_added) ? $pInfo->products_date_added : date('Y-m-d'))) . ( (isset($_GET['search']) && !empty($_GET['search'])) ? zen_draw_hidden_field('search', $_GET['search']) : '') . ( (isset($_POST['search']) && !empty($_POST['search']) && empty($_GET['search'])) ? zen_draw_hidden_field('search', $_POST['search']) : '') . zen_image_submit('button_preview.gif', IMAGE_PREVIEW) . '&nbsp;&nbsp;<a href="' . zen_href_link(FILENAME_CATEGORIES, 'cPath=' . $cPath . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '') . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '') . ( (isset($_GET['search']) && !empty($_GET['search'])) ? '&search=' . $_GET['search'] : '') . ( (isset($_POST['search']) && !empty($_POST['search']) && empty($_GET['search'])) ? '&search=' . $_POST['search'] : '')) . '">' . zen_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
      </tr>
    </table></form>
