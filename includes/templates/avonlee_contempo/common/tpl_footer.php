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
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_footer.php 4821 2006-10-23 10:54:15Z drbyte $
 */
require(DIR_WS_MODULES . zen_get_module_directory('footer.php'));
?>

<?php
if (!isset($flag_disable_footer) || !$flag_disable_footer) {
?>

<!--<div id="footer">-->

<!--bof-navigation display -->
<div id="navSuppWrapper">


<!--BOF footer menu display-->
<?php //require($template->get_template_dir('tpl_footer_menu.php',DIR_WS_TEMPLATE, $current_page_base,'common'). '/tpl_footer_menu.php');?>
<!--EOF footer menu display-->
</div>
<!--eof-navigation display -->
<!--bof- site copyright display -->
<div id="siteinfoLegal" class="legalCopyright"><?php //echo FOOTER_TEXT_BODY; ?></div>
<!--eof- site copyright display -->
</div>

<!--bof-ip address display -->
<?php
if (SHOW_FOOTER_IP == '1') {
?>
<div id="siteinfoIP"><?php echo TEXT_YOUR_IP_ADDRESS . '  ' . $_SERVER['REMOTE_ADDR']; ?></div>
<?php
}
?>
<!--eof-ip address display -->

<!--bof-banner #5 display -->
<?php
  if (SHOW_BANNERS_GROUP_SET5 != '' && $banner = zen_banner_exists('dynamic', SHOW_BANNERS_GROUP_SET5)) {
    if ($banner->RecordCount() > 0) {
?>
<div id="bannerFive" class="banners"><?php echo zen_display_banner('static', $banner); ?></div>
<?php
    }
  }
?>
<!--eof-banner #5 display -->

<?php
} // flag_disable_footer
?>
<footer class="footer">
  <div class="container">
      <div class="row">
        <div class="col-md-12 col-sm-12 col-sm-12">
          <ul class="as_footer">
            <?php require($template->get_template_dir('tpl_footer_menu.php',DIR_WS_TEMPLATE, $current_page_base,'common'). '/tpl_footer_menu.php');?>
          </ul>
        </div> 
      </div>
  </div>
</footer>
<div class="container-fluid copyright text-center"><?php echo FOOTER_TEXT_BODY; ?></div>

<script src="includes/templates/avonlee_contempo/js/bootstrap.min.js"></script> 
<script src="includes/templates/avonlee_contempo/js/modernizr.js"></script> 
<script src="includes/templates/avonlee_contempo/js/main.js"></script>
<script type="text/javascript" src="includes/templates/avonlee_contempo/js/modernizr.custom.js"></script> 
<script type="text/javascript" src="includes/templates/avonlee_contempo/js/jquery.cslider.js"></script> 
<script type="text/javascript" src="includes/templates/avonlee_contempo/galleria/galleria-1.2.9.min.js"></script>

<script type="text/javascript">
      $(document).ready(function() {
      
        $('#da-slider').cslider({
          autoplay  : true,
          bgincrement : 2900
        });
      
      });
    </script>


</body>
</html>
