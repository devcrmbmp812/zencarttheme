<?php

/*
 * 
 * @package zen_lightbox
 * @copyright Copyright 2003-2015 ZenCart.Codes a Pro-Webs Company
 * @copyright Copyright 2003-2015 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @filename jscript_checkout_reloaded.php
 * @file created 2015-05-25 11:30:56 AM
 * 
 */

if(!defined('CSS_JS_LOADER_VERSION') && ZEN_LIGHTBOX_STATUS == 'true') {
    echo '<link rel="stylesheet" type="text/css" href="'.$template->get_template_dir('zen_lightbox.css',DIR_WS_TEMPLATE, $current_page_base,'css'). '/zen_lightbox.css'.'">';
    echo '<script type="text/javascript">
        if (typeof jQuery == \'undefined\') {  
  document.write("<scr" + "ipt type=\"text/javascript\" src=\"//code.jquery.com/jquery-1.11.3.min.js\"></scr" + "ipt>");
  }
</script>'."\n";
    require($template->get_template_dir('jquery_zen_lightbox.php',DIR_WS_TEMPLATE, $current_page_base,'jscript/jquery'). '/jquery_zen_lightbox.php'); 
 } 
