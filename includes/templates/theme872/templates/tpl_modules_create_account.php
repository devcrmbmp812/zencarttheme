<?php
/**
 * Page Template
 *
 * Loaded automatically by index.php?main_page=create_account.<br />
 * Displays Create Account form.
 *
 * @package templateSystem
 * @copyright Copyright 2003-2014 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version GIT: $Id: Author: DrByte  Sun Aug 19 09:47:29 2012 -0400 Modified in v1.5.1 $
 */
?>

<?php if ($messageStack->size('create_account') > 0) echo $messageStack->output('create_account'); ?>
<div class="alert forward"><?php echo FORM_REQUIRED_INFORMATION; ?></div>
<br class="clearBoth" />

<?php
  if (DISPLAY_PRIVACY_CONDITIONS == 'true') {
?>
<fieldset>
  <legend><?php echo TABLE_HEADING_PRIVACY_CONDITIONS; ?></legend>
  <div class="information"><?php echo TEXT_PRIVACY_CONDITIONS_DESCRIPTION;?></div>
  <?php echo zen_draw_checkbox_field('privacy_conditions', '1', false, 'id="privacy"');?>
  <label class="checkboxLabel" for="privacy"><?php echo TEXT_PRIVACY_CONDITIONS_CONFIRM;?></label>
</fieldset>
<?php
  }
?>

<?php
  if (ACCOUNT_COMPANY == 'true') {
?>
<fieldset class="company">
  <legend><?php echo CATEGORY_COMPANY; ?></legend>
</fieldset>
<div class="form-group">
    <label class="inputLabel" for="company"><?php echo ENTRY_COMPANY; ?></label>
    <?php echo zen_draw_input_field('company', '', zen_set_field_length(TABLE_ADDRESS_BOOK, 'entry_company', '40') . ' id="company" class="form-control"') . (zen_not_null(ENTRY_COMPANY_TEXT) ? '<span class="alert">' . ENTRY_COMPANY_TEXT . '</span>': ''); ?>
</div>
<?php
  }
?>

<fieldset class="address">
  <legend><?php echo TABLE_HEADING_ADDRESS_DETAILS; ?></legend>
</fieldset>
<?php
  if (ACCOUNT_GENDER == 'true') {
?>
<div class="gender">
  <?php echo zen_draw_radio_field('gender', 'm', '', 'id="gender-male"') . '<label class="radioButtonLabel" for="gender-male">' . MALE . '</label>&nbsp;' . zen_draw_radio_field('gender', 'f', '', 'id="gender-female"') . '<label class="radioButtonLabel" for="gender-female">' . FEMALE . '</label>' . (zen_not_null(ENTRY_GENDER_TEXT) ? '<span class="alert">' . ENTRY_GENDER_TEXT . '</span>': ''); ?></div>
<br class="clearBoth">
<?php
  }
?>
<div class="form-group">
    <label class="inputLabel" for="firstname"><?php echo ENTRY_FIRST_NAME.'  '.(zen_not_null(ENTRY_FIRST_NAME_TEXT) ? '<small>' . ENTRY_FIRST_NAME_TEXT . '</small>': '');?></label>
    <?php echo zen_draw_input_field('firstname', '', zen_set_field_length(TABLE_CUSTOMERS, 'customers_firstname', '40') . ' id="firstname" class="form-control"') ?>
</div>
<div class="form-group">
    <label class="inputLabel" for="lastname"><?php echo ENTRY_LAST_NAME.'  '.(zen_not_null(ENTRY_LAST_NAME_TEXT) ? '<small>' . ENTRY_LAST_NAME_TEXT . '</small>': ''); ?></label>
    <?php echo zen_draw_input_field('lastname', '', zen_set_field_length(TABLE_CUSTOMERS, 'customers_lastname', '40') . ' id="lastname" class="form-control"') ?>
</div>
<div class="form-group">
    <label class="inputLabel" for="street-address"><?php echo ENTRY_STREET_ADDRESS.'  '. (zen_not_null(ENTRY_STREET_ADDRESS_TEXT) ? '<small>' . ENTRY_STREET_ADDRESS_TEXT . '</small>': ''); ?></label>
    <?php echo zen_draw_input_field('street_address', '', zen_set_field_length(TABLE_ADDRESS_BOOK, 'entry_street_address', '40') . ' id="street-address" class="form-control"') ?>
</div>

<?php
  if (ACCOUNT_SUBURB == 'true') {
?>
<div class="form-group">
    <label class="inputLabel" for="suburb"><?php echo ENTRY_SUBURB.'  '. (zen_not_null(ENTRY_SUBURB_TEXT) ? '<small>' . ENTRY_SUBURB_TEXT . '</small>': ''); ?></label>
    <?php echo zen_draw_input_field('suburb', '', zen_set_field_length(TABLE_ADDRESS_BOOK, 'entry_suburb', '40') . ' id="suburb" class="form-control"') ?>
</div>
<?php
  }
?>
<div class="form-group">
    <label class="inputLabel" for="city"><?php echo ENTRY_CITY.'  '. (zen_not_null(ENTRY_CITY_TEXT) ? '<small>' . ENTRY_CITY_TEXT . '</small>': ''); ?></label>
    <?php echo zen_draw_input_field('city', '', zen_set_field_length(TABLE_ADDRESS_BOOK, 'entry_city', '40') . ' id="city" class="form-control"') ?>
</div>

<?php
  if (ACCOUNT_STATE == 'true') {
    if ($flag_show_pulldown_states == true) {
?>
<div class="form-group">
<label class="inputLabel" for="stateZone" id="zoneLabel"><?php echo ENTRY_STATE; ?></label>
<?php
      echo zen_draw_pull_down_menu('zone_id', zen_prepare_country_zones_pull_down($selected_country), $zone_id, 'id="stateZone"');
      if (zen_not_null(ENTRY_STATE_TEXT)) echo '&nbsp;<small>' . ENTRY_STATE_TEXT . '</small>'; 
    }
?>
</div>
<?php if ($flag_show_pulldown_states == true) { ?>

<?php } ?>
<div class="form-group">

    <label class="inputLabel" for="state" id="stateLabel"><?php echo $state_field_label.'  '. (zen_not_null(ENTRY_STATE_TEXT) ? '<small id="stText">' . ENTRY_STATE_TEXT . '</small>' : ''); ?></label>
    <?php
        echo zen_draw_input_field('state', '', zen_set_field_length(TABLE_ADDRESS_BOOK, 'entry_state', '40') . ' id="state"  class="form-control"');
        if ($flag_show_pulldown_states == false) {
          echo zen_draw_hidden_field('zone_id', $zone_name, ' ');
        }
    ?>
</div>
<?php
  }
?>
<div class="form-group">
    <label class="inputLabel" for="postcode"><?php echo ENTRY_POST_CODE.'  '. (zen_not_null(ENTRY_POST_CODE_TEXT) ? '<small>' . ENTRY_POST_CODE_TEXT . '</small>': ''); ?></label>
    <?php echo zen_draw_input_field('postcode', '', zen_set_field_length(TABLE_ADDRESS_BOOK, 'entry_postcode', '40') . ' id="postcode"  class="form-control"') ?>
</div>
<div class="form-group">
    <label class="inputLabel" for="country"><?php echo ENTRY_COUNTRY.'  '. (zen_not_null(ENTRY_COUNTRY_TEXT) ? '<small>' . ENTRY_COUNTRY_TEXT . '</small>': ''); ?></label>
    <?php echo zen_get_country_list('zone_country_id', $selected_country, 'id="country" class="form-control" ' . ($flag_show_pulldown_states == true ? 'onchange="update_zone(this.form);"' : '')) ?>
</div>

<fieldset>
<legend><?php echo TABLE_HEADING_PHONE_FAX_DETAILS; ?></legend>
</fieldset>
<div class="form-group">
    <label class="inputLabel" for="telephone"><?php echo ENTRY_TELEPHONE_NUMBER.'  '. (zen_not_null(ENTRY_TELEPHONE_NUMBER_TEXT) ? '<small>' . ENTRY_TELEPHONE_NUMBER_TEXT . '</small>': ''); ?></label>
    <?php echo zen_draw_input_field('telephone', '', zen_set_field_length(TABLE_CUSTOMERS, 'customers_telephone', '40') . ' id="telephone" class="form-control"')  ?>
</div>

<?php
  if (ACCOUNT_FAX_NUMBER == 'true') {
?>
<div class="form-group">
    <label class="inputLabel" for="fax"><?php echo ENTRY_FAX_NUMBER.'  '. (zen_not_null(ENTRY_FAX_NUMBER_TEXT) ? '<small>' . ENTRY_FAX_NUMBER_TEXT . '</small>': ''); ?></label>
    <?php echo zen_draw_input_field('fax', '', 'id="fax"  class="form-control"') ?>
<?php
  }
?>
</div>

<?php
  if (ACCOUNT_DOB == 'true') {
?>

<fieldset>
<legend><?php echo TABLE_HEADING_DATE_OF_BIRTH; ?></legend>
</fieldset>
<div class="form-group">
    <label class="inputLabel" for="dob"><?php echo ENTRY_DATE_OF_BIRTH.'  '. (zen_not_null(ENTRY_DATE_OF_BIRTH_TEXT) ? '<small>' . ENTRY_DATE_OF_BIRTH_TEXT . '</small>': ''); ?></label>
    <?php echo zen_draw_input_field('dob','', 'id="dob" class="form-control"') ?>
</div>

<?php
  }
?>

<fieldset>
    <legend><?php echo TABLE_HEADING_LOGIN_DETAILS; ?></legend>
</fieldset>
<div class="form-group">
    <label class="inputLabel" for="email-address"><?php echo ENTRY_EMAIL_ADDRESS.'  '. (zen_not_null(ENTRY_EMAIL_ADDRESS_TEXT) ? '<small>' . ENTRY_EMAIL_ADDRESS_TEXT . '</small>': ''); ?></label>
    <?php echo zen_draw_input_field('email_address', '', zen_set_field_length(TABLE_CUSTOMERS, 'customers_email_address', '40') . ' id="email-address" class="form-control"') ?>
</div>
<div class="form-group">
<?php
  if ($phpBB->phpBB['installed'] == true) {
?>
    <label class="inputLabel" for="nickname"><?php echo ENTRY_NICK.'  '. (zen_not_null(ENTRY_NICK_TEXT) ? '<small>' . ENTRY_NICK_TEXT . '</small>': ''); ?></label>
    <?php echo zen_draw_input_field('nick','','id="nickname" class="form-control"') ?>
</div>
<?php
  }
?>
<div class="form-group">
    <label class="inputLabel" for="password-new"><?php echo ENTRY_PASSWORD.'  '. (zen_not_null(ENTRY_PASSWORD_TEXT) ? '<small>' . ENTRY_PASSWORD_TEXT . '</small>': ''); ?></label>
    <?php echo zen_draw_password_field('password', '', zen_set_field_length(TABLE_CUSTOMERS, 'customers_password', '20') . ' id="password-new" class="form-control"') ?>
</div>
<div class="form-group">
    <label class="inputLabel" for="password-confirm"><?php echo ENTRY_PASSWORD_CONFIRMATION.'  ' . (zen_not_null(ENTRY_PASSWORD_CONFIRMATION_TEXT) ? '<small>' . ENTRY_PASSWORD_CONFIRMATION_TEXT . '</small>': ''); ?></label>
    <?php echo zen_draw_password_field('confirmation', '', zen_set_field_length(TABLE_CUSTOMERS, 'customers_password', '20') . ' id="password-confirm" class="form-control"') ?>
</div>

<fieldset>
    <legend><?php echo ENTRY_EMAIL_PREFERENCE; ?></legend>
</fieldset>
<div class="newsletter_block">
  <?php
    if (ACCOUNT_NEWSLETTER_STATUS != 0) {
  ?>
  <div><?php echo zen_draw_checkbox_field('newsletter', '1', $newsletter, 'id="newsletter-checkbox"') . '<label class="checkboxLabel" for="newsletter-checkbox">' . ENTRY_NEWSLETTER . '</label>' . (zen_not_null(ENTRY_NEWSLETTER_TEXT) ? '<span class="alert">' . ENTRY_NEWSLETTER_TEXT . '</span>': ''); ?></div>
  <br class="clearBoth">
  <br class="clearBoth">
  <?php } ?>
  
  <div><?php echo zen_draw_radio_field('email_format', 'HTML', ($email_format == 'HTML' ? true : false),'id="email-format-html"') . '<label class="radioButtonLabel" for="email-format-html">' . ENTRY_EMAIL_HTML_DISPLAY . '</label><br><br>' .  zen_draw_radio_field('email_format', 'TEXT', ($email_format == 'TEXT' ? true : false), 'id="email-format-text"') . '<label class="radioButtonLabel" for="email-format-text">' . ENTRY_EMAIL_TEXT_DISPLAY . '</label>'; ?></div>
  <br class="clearBoth" />


  <?php
    if (CUSTOMERS_REFERRAL_STATUS == 2) {
  ?>

<fieldset>
  <legend><?php echo TABLE_HEADING_REFERRAL_DETAILS; ?></legend>
</fieldset>
<div class="form-group">
    <label class="inputLabel" for="customers_referral"><?php echo ENTRY_CUSTOMERS_REFERRAL; ?></label>
    <?php echo zen_draw_input_field('customers_referral', '', zen_set_field_length(TABLE_CUSTOMERS, 'customers_referral', '15') . ' id="customers_referral"  class="form-control"'); ?>
</div>

><?php } ?>
</div>