<?php
/**
 * Page Template
 *
 * Loaded automatically by index.php?main_page=contact_us.<br />
 * Displays contact us page form.
 *
 * @package templateSystem
 * @copyright Copyright 2003-2013 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version GIT: $Id: Author: DrByte  Sun Feb 17 23:22:33 2013 -0500 Modified in v1.5.2 $
 */
?>

<div class="centerColumn" id="contactUsDefault">

<?php echo zen_draw_form('contact_us', zen_href_link(FILENAME_CONTACT_US, 'action=send', 'SSL')); ?>

<h2 class="centerBoxHeading"><?php echo HEADING_TITLE; ?></h2>

<div class="tie text2">
	<div class="tie-indent">

<div class="form-control-block">
<?php echo zen_draw_form('contact_us', zen_href_link(FILENAME_CONTACT_US, 'action=send')); ?>

<?php if (CONTACT_US_STORE_NAME_ADDRESS== '1') { ?>
<address><?php echo nl2br(STORE_NAME_ADDRESS); ?></address>
<?php } ?>

<?php
  if (isset($_GET['action']) && ($_GET['action'] == 'success')) {
?>

<div class="mainContent success"><?php echo TEXT_SUCCESS; ?></div>

<div class="buttonRow"><?php echo zen_back_link() . zen_image_button(BUTTON_IMAGE_BACK, BUTTON_BACK_ALT) . '</a>'; ?></div>

<?php
  } else {
?>

<?php if (DEFINE_CONTACT_US_STATUS >= '1' and DEFINE_CONTACT_US_STATUS <= '2') { ?>
<div id="contactUsNoticeContent" class="content">
<?php
/**
 * require html_define for the contact_us page
 */
  require($define_page);
?>
</div>
<?php } ?>



<?php
// show dropdown if set
    if (CONTACT_US_LIST !=''){
?>
<div class="form-group">
    <label class="inputLabel" for="send-to"><?php echo SEND_TO_TEXT . '<span class="alert">' . ENTRY_REQUIRED_SYMBOL . '</span>';?></label>
    <?php echo zen_draw_pull_down_menu('send_to',  $send_to_array, 0, 'id="send-to" class="form-control"'); ?>
</div>
<?php
    }
?>
    <div class="contact_fields_wrapper clearfix">
   	 <div class="row">
        <div class="contacts_left_fields col-xs-12 col-sm-5">
            <div class="form-group contact-group">
                <label for="contactname"><?php echo ENTRY_NAME ?></label>
                <?php echo zen_draw_input_field('contactname', $name, ' size="40" id="contactname" class="form-control"') ; ?>
            </div>
            <div class="form-group contact-group">
                <label for="email-address"><?php echo ENTRY_EMAIL ?></label>
                <?php echo zen_draw_input_field('email', ($email_address), ' size="40" id="email-address" class="form-control"'); ?>
            </div>
        </div>
        
        <div class="form-group contact-group-area col-xs-12 col-sm-7">
            <label for="enquiry"><?php echo ENTRY_ENQUIRY ?></label>
            <?php echo zen_draw_textarea_field('enquiry', '20', '7', $enquiry, 'id="enquiry" class="form-control"'); ?>
        </div>
    </div>
    </div>
    <br>
    <div>
        <?php echo zen_image_submit('', BUTTON_SEND_ALT); ?>
        <?php echo zen_back_link() . zen_image_button(BUTTON_IMAGE_BACK, BUTTON_BACK_ALT) . '</a>'; ?>
    </div>
<?php
  }
?>
</div>
<div class="clear"></div>
</div>
</div>

</div>