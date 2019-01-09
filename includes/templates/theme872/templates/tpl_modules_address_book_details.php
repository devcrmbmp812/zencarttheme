<?php
/**
 * Module Template
 *
 * Displays address-book details/selection
 *
 * @package templateSystem
 * @copyright Copyright 2003-2007 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_modules_address_book_details.php 5924 2007-02-28 08:25:15Z drbyte $
 */
?>
<div class="heading"><h1><?php echo HEADING_TITLE; ?></h1></div>
<fieldset>
<div class="alert forward"><?php echo FORM_REQUIRED_INFORMATION; ?></div>
<br class="clearBoth" />

<?php
  if (ACCOUNT_GENDER == 'true') {
    if (isset($gender)) {
      $male = ($gender == 'm') ? true : false;
    } else {
      $male = ($entry->fields['entry_gender'] == 'm') ? true : false;
    }
    $female = !$male;
?>
<?php echo zen_draw_radio_field('gender', 'm', $male, 'id="gender-male"') . '<label class="radioButtonLabel" for="gender-male">' . MALE . '</label>' . zen_draw_radio_field('gender', 'f', $female, 'id="gender-female"') . '<label class="radioButtonLabel" for="gender-female">' . FEMALE . '</label>' . (zen_not_null(ENTRY_GENDER_TEXT) ? '<span class="alert">' . ENTRY_GENDER_TEXT . '</span>': ''); ?>
<br class="clearBoth" />

<?php
  }
?>
<div class="form-group">
    <label class="inputLabel" for="firstname"><?php echo ENTRY_FIRST_NAME . (zen_not_null(ENTRY_FIRST_NAME_TEXT) ? '<small> ' . ENTRY_FIRST_NAME_TEXT . '</small>': ''); ?></label>
    <?php echo zen_draw_input_field('firstname', $entry->fields['entry_firstname'], zen_set_field_length(TABLE_CUSTOMERS, 'customers_firstname', '40') . ' id="firstname" class="form-control"'); ?>
</div>

<div class="form-group">
    <label class="inputLabel" for="lastname"><?php echo ENTRY_LAST_NAME . (zen_not_null(ENTRY_LAST_NAME_TEXT) ? '<small> ' . ENTRY_LAST_NAME_TEXT . '</small>': ''); ?></label>
    <?php echo zen_draw_input_field('lastname', $entry->fields['entry_lastname'], zen_set_field_length(TABLE_CUSTOMERS, 'customers_lastname', '40') . ' id="lastname" class="form-control"'); ?>
</div>

<?php
  if (ACCOUNT_COMPANY == 'true') {
?>
<div class="form-group">
    <label class="inputLabel" for="company"><?php echo ENTRY_COMPANY . (zen_not_null(ENTRY_COMPANY_TEXT) ? '<small> ' . ENTRY_COMPANY_TEXT . '</small>': ''); ?></label>
    <?php echo zen_draw_input_field('company', $entry->fields['entry_company'], zen_set_field_length(TABLE_ADDRESS_BOOK, 'entry_company', '40') . ' id="company" class="form-control"'); ?>
</div>
<?php
  }
?>
<div class="form-group">
    <label class="inputLabel" for="street-address"><?php echo ENTRY_STREET_ADDRESS . (zen_not_null(ENTRY_STREET_ADDRESS_TEXT) ? '<small> ' . ENTRY_STREET_ADDRESS_TEXT . '</small>': ''); ?></label>
    <?php echo zen_draw_input_field('street_address', $entry->fields['entry_street_address'], zen_set_field_length(TABLE_ADDRESS_BOOK, 'entry_street_address', '40') . ' id="street-address" class="form-control"'); ?>
</div>
<?php
  if (ACCOUNT_SUBURB == 'true') {
?>
<div class="form-group">
    <label class="inputLabel" for="suburb"><?php echo ENTRY_SUBURB . (zen_not_null(ENTRY_SUBURB_TEXT) ? '<small> ' . ENTRY_SUBURB_TEXT . '</small>': ''); ?></label>
    <?php echo zen_draw_input_field('suburb', $entry->fields['entry_suburb'], zen_set_field_length(TABLE_ADDRESS_BOOK, 'entry_suburb', '40') . ' id="suburb" class="form-control"'); ?>
</div>
<?php
  }
?>
<div class="form-group">
    <label class="inputLabel" for="city"><?php echo ENTRY_CITY . (zen_not_null(ENTRY_CITY_TEXT) ? '<small> ' . ENTRY_CITY_TEXT . '</small>': ''); ?></label>
    <?php echo zen_draw_input_field('city', $entry->fields['entry_city'], zen_set_field_length(TABLE_ADDRESS_BOOK, 'entry_city', '40') . ' id="city" class="form-control"'); ?>
</div>

<?php
  if (ACCOUNT_STATE == 'true') {
    if ($flag_show_pulldown_states == true) {
?>
<div class="form-group">
    <label class="inputLabel" for="stateZone" id="zoneLabel"><?php echo ENTRY_STATE; if (zen_not_null(ENTRY_STATE_TEXT)) echo '&nbsp;<small>' . ENTRY_STATE_TEXT . '</small>'; ?></label>
    <?php
          echo zen_draw_pull_down_menu('zone_id', zen_prepare_country_zones_pull_down($selected_country), $zone_id, 'id="stateZone" class="form-control"');
          
        }
    ?>
</div>

<?php if ($flag_show_pulldown_states == true) { ?>
<br class="clearBoth" id="stBreak" />
<?php } ?>
<div class="form-group">
    <label class="inputLabel" for="state" id="stateLabel"><?php echo $state_field_label; if (zen_not_null(ENTRY_STATE_TEXT)) echo '&nbsp;<small>' . ENTRY_STATE_TEXT . '</small>'; ?></label>
    <?php
        echo zen_draw_input_field('state', zen_get_zone_name($entry->fields['entry_country_id'], $entry->fields['entry_zone_id'], $entry->fields['entry_state']), zen_set_field_length(TABLE_ADDRESS_BOOK, 'entry_state', '40') . ' id="state" class="form-control"');
        if ($flag_show_pulldown_states == false) {
          echo zen_draw_hidden_field('zone_id', $zone_name, ' ');
        }
    ?>
</div>
<?php
  }
?>
<div class="form-group">
    <label class="inputLabel" for="postcode"><?php echo ENTRY_POST_CODE . (zen_not_null(ENTRY_POST_CODE_TEXT) ? '<small> ' . ENTRY_POST_CODE_TEXT . '</small>': ''); ?></label>
    <?php echo zen_draw_input_field('postcode', $entry->fields['entry_postcode'], zen_set_field_length(TABLE_ADDRESS_BOOK, 'entry_postcode', '40') . ' id="postcode" class="form-control"'); ?>
</div>

<div class="form-group">
    <label class="inputLabel" for="country"><?php echo ENTRY_COUNTRY . (zen_not_null(ENTRY_COUNTRY_TEXT) ? '<small> ' . ENTRY_COUNTRY_TEXT . '</small>': ''); ?></label>
    <?php echo zen_get_country_list('zone_country_id', $entry->fields['entry_country_id'], 'id="country" class="form-control" ' . ($flag_show_pulldown_states == true ? 'onchange="update_zone(this.form);"' : '')); ?>
</div>

<?php
  if ((isset($_GET['edit']) && ($_SESSION['customer_default_address_id'] != $_GET['edit'])) || (isset($_GET['edit']) == false) ) {
?>
<?php echo zen_draw_checkbox_field('primary', 'on', false, 'id="primary"') . ' <label class="checkboxLabel" for="primary">' . SET_AS_PRIMARY . '</label>'; ?>
<?php
  }
?>
</fieldset>
