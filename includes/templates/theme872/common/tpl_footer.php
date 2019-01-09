<?php
/**
 * Common Template - tpl_footer.php
 *
 * this file can be copied to /templates/your_template_dir/pagename<br />
 * example: to override the privacy page<br />
 * make a directory /templates/my_template/privacy<br />
 * copy /templates/templates_defaults/common/tpl_footer.php to /templates/my_template/privacy/tpl_footer.php<br />
 * to override the global settings and turn off the footer un-comment the following line:<br />
 * <br />
 * $flag_disable_footer = true;<br />
 *
 * @package templateSystem
 * @copyright Copyright 2003-2010 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_footer.php 15511 2010-02-18 07:19:44Z drbyte $
 */
require(DIR_WS_MODULES . zen_get_module_directory('footer.php'));
?>

<?php
if (!isset($flag_disable_footer) || !$flag_disable_footer) {
?>


	<div class="container">
    	<div class="row">
		<div class="footer-menu col-xs-12 col-sm-3">
            <h2 class="title_btn1"><?php echo HEADER_TITLE_QUICK_LINKS; ?></h2>
			<?php //echo '<a href="' . HTTP_SERVER . DIR_WS_CATALOG . '">'. HEADER_TITLE_CATALOG . '</a>'; ?>
			<?php if (EZPAGES_STATUS_FOOTER == '1' or (EZPAGES_STATUS_FOOTER == '2' and (strstr(EXCLUDE_ADMIN_IP_FOR_MAINTENANCE, $_SERVER['REMOTE_ADDR'])))) { ?>
			<?php require($template->get_template_dir('tpl_ezpages_bar_footer.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_ezpages_bar_footer.php'); ?>
			<?php } ?>
		</div>
        <div class="account col-xs-12 col-sm-3 mb">
        	<h2 class="title_btn2"><?php echo TITLE_CUSTOMERS; ?></h2>
             <ul class="account_list">
                <?php if ($_SESSION['customer_id']) { ?>
                <li><a href="<?php echo zen_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>"><?php echo HEADER_TITLE_MY_ACCOUNT; ?></a></li>
                <li><a href="<?php echo zen_href_link(FILENAME_LOGOFF, '', 'SSL'); ?>"><?php echo HEADER_TITLE_LOGOFF; ?></a></li>
                <li><a href="<?php echo zen_href_link(FILENAME_ACCOUNT_NEWSLETTERS, '', 'SSL'); ?>"><?php echo TITLE_NEWSLETTERS; ?></a></li>
                    <?php } else { ?>
                <li><a href="<?php echo zen_href_link(FILENAME_LOGIN, '', 'SSL'); ?>"><?php echo HEADER_TITLE_LOGIN; ?></a></li>
                <li><a href="<?php echo zen_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'); ?>"><?php echo HEADER_TITLE_CREATE_ACCOUNT; ?></a></li>
                    <?php } ?>
                    <?php if (DEFINE_PRIVACY_STATUS <= 1)  { ?>
                <li><a href="<?php echo zen_href_link(FILENAME_PRIVACY); ?>"><?php echo BOX_INFORMATION_PRIVACY; ?></a></li>
                    <?php } ?>
                    <?php if (DEFINE_CONDITIONS_STATUS <= 1) { ?>
                <li><a href="<?php echo zen_href_link(FILENAME_CONDITIONS); ?>"><?php echo BOX_INFORMATION_CONDITIONS; ?></a></li>
                <?php } ?>
             </ul>   
        </div>
        <div class="social col-xs-12 col-sm-3 mb">
        	<h2 class="title_btn3"><?php echo BOX_HEADING_SOCIAL; ?></h2>
            <ul class="social_list">
            	<li><a href="http://www.facebook.com">Facebook</a></li>
                <li><a href="http://www.twitter.com">Twitter</a></li>
                <li><a href="#">RSS</a></li>
            </ul>
        </div>
        <div class="contact-block col-xs-12 col-sm-3 mb">
        	<h2 class="title_btn4"><?php echo BOX_INFORMATION_CONTACT; ?></h2>
            <div class="contact_list">
                <p><?php echo STORE_NAME_ADDRESS; ?></p>
                <div class="phone">
				    <?php echo STORE_TELEPHONE_CUSTSERVICE; ?>
                </div>
            </div>
        </div>
		<?php
			if($this_is_home_page){
		?>
		<div><!-- {%FOOTER_LINK} --></div>
		<?php
			}
		?>
        </div>
	</div>    


<?php
} // flag_disable_footer
?>
