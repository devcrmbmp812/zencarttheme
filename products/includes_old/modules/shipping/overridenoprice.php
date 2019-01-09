<?php
/**
 * @package shippingMethod
 * @copyright Copyright 2003-2013 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version GIT: $Id: Author: DrByte  Thu Feb 14 23:03:50 2013 -0500 Modified in v1.5.2 $
 */

class overridenoprice extends base {
  /**
   * $code determines the internal 'code' name used to designate "this" shipping module
   *
   * @var string
   */
  var $code;
  /**
   * $title is the displayed name for this shipping method
   *
   * @var string
   */
  var $title;
  /**
   * $description is a soft name for this shipping method
   *
   * @var string
   */
  var $description;
  /**
   * module's icon
   *
   * @var string
   */
  var $icon;
  /**
   * $enabled determines whether this module shows or not... during checkout.
   *
   * @var boolean
   */
  var $enabled;
  /**
   * constructor
   *
   * @return overridenoprice
   */
  function __construct() {
    $this->code = 'overridenoprice';
    $this->title = MODULE_OVERRIDESHIPPING_NO_PRICE_TITLE;
    $this->description = MODULE_OVERRIDESHIPPING_NO_PRICE_DESCRIPTION;
    $this->sort_order = MODULE_SHIPPING_OVERRIDE_NOPRICE_SORT_ORDER;
    $this->icon = ''; // add image filename here; must be uploaded to the /images/ subdirectory
    $this->tax_class = MODULE_SHIPPING_OVERRIDE_NOPRICE_TAX_CLASS;
    $this->tax_basis = MODULE_SHIPPING_OVERRIDE_NOPRICE_TAX_BASIS;
    $this->enabled = ((MODULE_SHIPPING_OVERRIDE_NOPRICE_STATUS == 'True' && $_SESSION['shipping_overide'] == 1) ? true : false);
  }
  function update_status() {
    global $order, $db;
    if ( ($this->enabled == true) && ((int)MODULE_SHIPPING_OVERRIDE_NOPRICE_ZONE > 0) ) {
      $check_flag = false;
      $check = $db->Execute("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . "
                             where geo_zone_id = '" . MODULE_SHIPPING_OVERRIDE_NOPRICE_ZONE . "'
                             and zone_country_id = '" . $order->delivery['country']['id'] . "'
                             order by zone_id");
      while (!$check->EOF) {
        if ($check->fields['zone_id'] < 1) {
          $check_flag = true;
          break;
        } elseif ($check->fields['zone_id'] == $order->delivery['zone_id']) {
          $check_flag = true;
          break;
        }
        $check->MoveNext();
      }

      if ($check_flag == false) {
        $this->enabled = false;
      }
    }
    if($_SESSION['shipping_overide'] != 1){
        $this->enabled = false;
    }
  }
  /**
   * Obtain quote from shipping system/calculations
   *
   * @param string $method
   * @return array
   */
  function quote($method = '') {
    global $order;

    // this code looks to see if there's a language-specific translation for the available shipping locations/methods, to override what is entered in the Admin (since the admin setting is in the default language)
    $ways_translated = (defined('MODULE_SHIPPING_OVERRIDE_NOPRICE_MULTIPLE_WAYS')) ? trim(MODULE_SHIPPING_OVERRIDE_NOPRICE_MULTIPLE_WAYS) : '';
    $ways_default = trim(MODULE_SHIPPING_OVERRIDE_NOPRICE_LOCATIONS_LIST);
    $methodsToParse = ($ways_translated == '') ? $ways_default : $ways_translated;

    if ($methodsToParse == '') {
      $this->methodsList[] = array('id' => $this->code,
                                   'title' => trim((string)MODULE_OVERRIDESHIPPING_NO_PRICE_WAY),
                                   'cost' => MODULE_SHIPPING_OVERRIDE_NOPRICE_COST);
    } else {
      $this->locations = explode(';', (string)$methodsToParse);
      $this->methodsList = array();
      foreach ($this->locations as $key => $val)
      {
        if ($method != '' && $method != $this->code . (string)$key) continue;
        $cost = MODULE_SHIPPING_OVERRIDE_NOPRICE_COST;
        $title = $val;
        if (strstr($val, ',')) {
          list($title, $cost) = explode(',', $val);
        }
        $this->methodsList[] = array('id' => $this->code . (string)$key,
                                     'title' => trim($title),
                                     'cost' => $cost);
      }
    }

    $this->quotes = array('id' => $this->code,
                          'module' => MODULE_OVERRIDESHIPPING_NO_PRICE_TITLE,
                          'methods' => $this->methodsList);

    if ($this->tax_class > 0) {
      $this->quotes['tax'] = zen_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
    }

    if (zen_not_null($this->icon)) $this->quotes['icon'] = zen_image($this->icon, $this->title);

    return $this->quotes;
  }
  /**
   * Check to see whether module is installed
   *
   * @return boolean
   */
  function check() {
    global $db;
    if (!isset($this->_check)) {
      $check_query = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_OVERRIDE_NOPRICE_STATUS'");
      $this->_check = $check_query->RecordCount();
    }
    if ($this->_check > 0 && !defined('MODULE_SHIPPING_OVERRIDE_NOPRICE_LOCATIONS_LIST')) $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Quote Options', 'MODULE_SHIPPING_OVERRIDE_NOPRICE_LOCATIONS_LIST', 'Contact Us', 'Enter a list of locations, separated by semicolons (;).<br>Optionally you may specify a fee/surcharge for each option by adding a comma and an amount. If no amount is specified, then the generic Shipping Cost amount from the next setting will be applied.For multilanguage use, see the define-statement in the language file for this module.', '6', '0', now())");
    return $this->_check;
  }
  /**
   * Install the shipping module and its configuration settings
   *
   */
  function install() {
    global $db;
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Override No Price Shipping', 'MODULE_SHIPPING_OVERRIDE_NOPRICE_STATUS', 'True', 'Do you want to offer In Override No Price shipping?', '6', '0', 'zen_cfg_select_option(array(\'True\', \'False\'), ', now())");
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Override Options', 'MODULE_SHIPPING_OVERRIDE_NOPRICE_LOCATIONS_LIST', 'Walk In', 'Enter a list of locations, separated by semicolons (;).<br>Optionally you may specify a fee/surcharge for each location by adding a comma and an amount. If no amount is specified, then the generic Shipping Cost amount from the next setting will be applied.<br><br>Examples:<br>121 Main Street;20 Church Street<br>Sunnyside,4.00;Lee Park,5.00;High Street,0.00<br>Dallas;Tulsa,5.00;Phoenix,0.00<br>For multilanguage use, see the define-statement in the language file for this module.', '6', '0', now())");
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Shipping Cost', 'MODULE_SHIPPING_OVERRIDE_NOPRICE_COST', '0.00', 'The shipping cost for all orders using this shipping method.', '6', '0', now())");
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_SHIPPING_OVERRIDE_NOPRICE_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'zen_get_tax_class_title', 'zen_cfg_pull_down_tax_classes(', now())");
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Tax Basis', 'MODULE_SHIPPING_OVERRIDE_NOPRICE_TAX_BASIS', 'Shipping', 'On what basis is Shipping Tax calculated. Options are<br />Shipping - Based on customers Shipping Address<br />Billing Based on customers Billing address<br />Store - Based on Store address if Billing/Shipping Zone equals Store zone', '6', '0', 'zen_cfg_select_option(array(\'Shipping\', \'Billing\'), ', now())");
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Shipping Zone', 'MODULE_SHIPPING_OVERRIDE_NOPRICE_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'zen_get_zone_class_title', 'zen_cfg_pull_down_zone_classes(', now())");
    $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SHIPPING_OVERRIDE_NOPRICE_SORT_ORDER', '0', 'Sort order of display.', '6', '0', now())");
  }
  /**
   * Remove the module and all its settings
   *
   */
  function remove() {
    global $db;
    $db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key like 'MODULE\_SHIPPING\_OVERRIDE\_NOPRICE\_%'");
  }
  /**
   * Internal list of configuration keys used for configuration of the module
   *
   * @return array
   */
  function keys() {
    return array('MODULE_SHIPPING_OVERRIDE_NOPRICE_STATUS', 'MODULE_SHIPPING_OVERRIDE_NOPRICE_LOCATIONS_LIST', 'MODULE_SHIPPING_OVERRIDE_NOPRICE_COST', 'MODULE_SHIPPING_OVERRIDE_NOPRICE_TAX_CLASS', 'MODULE_SHIPPING_OVERRIDE_NOPRICE_TAX_BASIS', 'MODULE_SHIPPING_OVERRIDE_NOPRICE_ZONE', 'MODULE_SHIPPING_OVERRIDE_NOPRICE_SORT_ORDER');
  }
}

