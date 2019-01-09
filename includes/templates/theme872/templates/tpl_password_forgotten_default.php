<?php
/**
 * Page Template
 *
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_password_forgotten_default.php 3712 2006-06-05 20:54:13Z drbyte $
 */
?>
<div class="centerColumn" id="passwordForgotten">
<?php echo zen_draw_form('password_forgotten', zen_href_link(FILENAME_PASSWORD_FORGOTTEN, 'action=process', 'SSL')); ?>

<?php if ($messageStack->size('password_forgotten') > 0) echo $messageStack->output('password_forgotten'); ?>
<div class="heading"><h1><?php echo HEADING_TITLE; ?></h1></div>
<div class="form-control-block">
    <fieldset>    
        <div id="passwordForgottenMainContent" class="content"><?php echo TEXT_MAIN; ?></div>
    </fieldset>
    <div class="alert forward"><?php echo FORM_REQUIRED_INFORMATION; ?></div>
    <div class="form-group">
        <label for="email-address"><?php echo ENTRY_EMAIL_ADDRESS.'  '. (zen_not_null(ENTRY_EMAIL_ADDRESS_TEXT) ? '<small>' . ENTRY_EMAIL_ADDRESS_TEXT . '</small>': ''); ?></label>
        <?php echo zen_draw_input_field('email_address', '', zen_set_field_length(TABLE_CUSTOMERS, 'customers_email_address', '40') . ' id="email-address" class="form-control"') ?>
    </div>
    <div class="mr_t"><?php echo zen_image_submit('', BUTTON_SUBMIT_ALT); ?></div> 
    <div class="btn-default mr_t"><?php echo zen_back_link() . zen_image_button(BUTTON_IMAGE_BACK, BUTTON_BACK_ALT) . '</a>'; ?></div>
</div>
</form>
</div>