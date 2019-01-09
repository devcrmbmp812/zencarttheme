<?php
/**
 * Page Template
 *
 * @package templateSystem
 * @copyright Copyright 2003-2013 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version GIT: $Id: Author: DrByte  Wed Dec 18 14:20:36 2013 -0500 Modified in v1.5.3 $
 */
?>
<div class="centerColumn" id="timeoutDefault">
<?php
    if ($_SESSION['customer_id']) {
?>
<div class="heading"><h1><?php echo HEADING_TITLE_LOGGED_IN; ?></h1></div>
<div id="timeoutDefaultContent" class="content"><?php echo TEXT_INFORMATION_LOGGED_IN; ?></div>
<?php
  } else {
?>
<h1 id="timeoutDefaultHeading"><?php echo HEADING_TITLE; ?></h1>

<div id="timeoutDefaultContent" class="content"><?php echo TEXT_INFORMATION; ?></div>
<?php echo zen_draw_form('login', zen_href_link(FILENAME_LOGIN, 'action=process', 'SSL')); ?>

<fieldset>
	<div class="row">
        <div class="col-xs-12 col-sm-12">
			<legend><?php echo HEADING_RETURNING_CUSTOMER; ?></legend>
		</div>
	</div>
	<div class="row">
		<div class="log_label">
		 	<div class="col-xs-2 col-sm-2">
				<label class="inputLabel" for="login-email-address"><?php echo ENTRY_EMAIL_ADDRESS; ?></label>
			</div>
			<div class="col-xs-8 col-sm-8">
				<?php echo zen_draw_input_field('email_address', '', zen_set_field_length(TABLE_CUSTOMERS, 'customers_email_address', '40') . ' id="login-email-address"'); ?>
			</div>
		</div>
		<div class="log_label">
			<div class="col-xs-2 col-sm-2">
				<label class="inputLabel" for="login-password"><?php echo ENTRY_PASSWORD; ?></label>
			</div>
			<div class="col-xs-8 col-sm-8">
				<?php echo zen_draw_password_field('password', '', zen_set_field_length(TABLE_CUSTOMERS, 'customers_password') . ' id="login-password"'); ?>
				<?php echo zen_draw_hidden_field('securityToken', $_SESSION['securityToken']); ?>
			</div>
		</div>
	</div>
</fieldset>

	<div class="form_btn"><?php echo zen_image_submit(BUTTON_IMAGE_LOGIN, BUTTON_LOGIN_ALT); ?></div>
	<div class="form_btn1"><?php echo '<a class="forgot_pass" href="' . zen_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . '">' . TEXT_PASSWORD_FORGOTTEN . '</a>'; ?></div>
</form>
<br class="clearBoth" />
<?php
 }
 ?>
</div>
