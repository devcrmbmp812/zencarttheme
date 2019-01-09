<?php
/**
 * Common Template - tpl_header.php
 *
 * this file can be copied to /templates/your_template_dir/pagename<br />
 * example: to override the privacy page<br />
 * make a directory /templates/my_template/privacy<br />
 * copy /templates/templates_defaults/common/tpl_footer.php to /templates/my_template/privacy/tpl_header.php<br />
 * to override the global settings and turn off the footer un-comment the following line:<br />
 * <br />
 * $flag_disable_header = true;<br />
 *
 * @package templateSystem
 * @copyright Copyright 2003-2012 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version GIT: $Id: Author: Ian Wilson  Tue Aug 14 14:56:11 2012 +0100 Modified in v1.5.1 $
 */
?>

<?php
  // Display all header alerts via messageStack:
  if ($messageStack->size('header') > 0) {
    echo $messageStack->output('header');
  }
  if (isset($_GET['error_message']) && zen_not_null($_GET['error_message'])) {
  echo htmlspecialchars(urldecode($_GET['error_message']), ENT_COMPAT, CHARSET, TRUE);
  }
  if (isset($_GET['info_message']) && zen_not_null($_GET['info_message'])) {
   echo htmlspecialchars($_GET['info_message'], ENT_COMPAT, CHARSET, TRUE);
} else {

}
?>


<?php
if (!isset($flag_disable_header) || !$flag_disable_header) {
?>

        <header>
            <div class="nav">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-md-5 col-sm-5">
                            <div id="header_logo">
                                <!-- ========== LOGO ========== -->
                                    <a href="<?php echo zen_href_link(FILENAME_DEFAULT);?>"><?php echo zen_image(DIR_WS_TEMPLATE.'images/logo.png'); ?></a>
                                <!-- ========================== -->
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6 col-sm-6">
                            <div id="currencies-block-top" class="top_dropdown_menu">
                                <!-- ========== CURRENCIES ========= -->
                                <form name="currencies" id="currencies_form" action="<?php echo zen_href_link(basename(preg_replace('~.php~','', $PHP_SELF)), '', $request_type, false) ?>" method="get" >
                                    <input type="hidden" name="currency" id="currency_value" value="" />
                                    <?php
                                        if (isset($currencies) && is_object($currencies)) 
                                        {
                                            $hidden_get_variables = '';
                                            reset($_GET);
                                            while (list($key, $value) = each($_GET)) 
                                            {
                                                if ( ($key != 'currency') && ($key != zen_session_name()) && ($key != 'x') && ($key != 'y') ) {
                                                    echo zen_draw_hidden_field($key, $value);
                                                }
                                            } 
                                        }
                                    ?>                   
                                    <div class="btn-group">
                                      <span class="trigger_down dropdown-toggle" data-toggle="dropdown">
                                        <?php 
                                            $tmp_str = '';
                                            if(isset($_SESSION['currency']))
                                                $tmp_str.= '<span class="lbl">'.$_SESSION['currency'].' </span>';
                                            echo $tmp_str;
                                        ?> 
                                      </span>
                                      <ul class="dropdown-menu" role="menu">
                                        <?php
                                            if (isset($currencies) && is_object($currencies)) 
                                            {
                                                reset($currencies->currencies);
                                                $output = '';
                                                while (list($key, $value) = each($currencies->currencies)) 
                                                {
                                                    if($_SESSION['currency'] == $key)
                                                        $output.= "<li class='current_cur'><a href=\"javascript:void(0);\" onclick='document.getElementById(\"currency_value\").value=\"".$key."\";'>".$value['title']."</a></li>";
                                                    else
                                                        $output.= "<li><a href='javascript:void(0);' onclick='document.getElementById(\"currency_value\").value=\"".$key."\";document.getElementById(\"currencies_form\").submit();'>".$value['title']."</a></li>";
                                                } 
                                                echo $output;
                                            }
                                        ?>
                                      </ul>
                                    </div>
                                </form>
                                <!-- ====================================== -->
                            </div>
                            <?php ?><div id="langs_block" class="top_dropdown_menu">
                                <!-- ========== LANGUAGES ========== -->        
                                <?php 
                                    if (!isset($lng) || (isset($lng) && !is_object($lng))) {
                                        $lng = new language;
                                    }
                                    reset($lng->catalog_languages);
                                ?>
                                <div class="btn-group">
                                      <span class="trigger_down dropdown-toggle" data-toggle="dropdown">
                                        <?php 
                                            echo '<span class="lbl">'.$_SESSION['language'].' </span>';
                                        ?> 
                                      </span>
                                    <ul class="dropdown-menu" role="menu">
                                        <?php
                                            $output = '';
                                            while (list($key, $value) = each($lng->catalog_languages)) 
                                            {
                                                if($_SESSION['languages_id'] == $value['id'])
                                                    $output.= '<li class="current_cur"><a class="lang_link" href="' . zen_href_link($_GET['main_page'], zen_get_all_get_params(array('language', 'currency')) . 'language=' . $key, $request_type) . '">' . $value['name'] . '</a></li>';
                                                else
                                                    $output.= '<li><a class="lang_link" href="' . zen_href_link($_GET['main_page'], zen_get_all_get_params(array('language', 'currency')) . 'language=' . $key, $request_type) . '">' . $value['name'] . '</a></li>';
                                            } 
                                            echo $output;
                                        ?>
                                      </ul>
                                </div>
                                <!-- =============================== -->
                                </div><?php ?>
                            <ul class="header_user_info customer_links">
                                <!-- ========== NAVIGATION LINKS ========== --> 
                                    <?php if ($_SESSION['customer_id']) { ?>
                                        <li><a href="<?php echo zen_href_link(FILENAME_LOGOFF, '', 'SSL'); ?>"><?php echo HEADER_TITLE_LOGOFF; ?></a></li>
                                    <?php
                                        } else {
                                        if (STORE_STATUS == '0') {
                                    ?>
                                    <li><a class="<?php if($body_id == 'account'){?>home<?php } ?>" href="<?php echo zen_href_link(FILENAME_ACCOUNT, '', 'SSL'); ?>"><?php echo HEADER_TITLE_MY_ACCOUNT; ?></a> </li>
                                    <li><a class="<?php if($body_id == 'login'){?>home<?php } ?>" href="<?php echo zen_href_link(FILENAME_LOGIN, '', 'SSL'); ?>"><?php echo HEADER_TITLE_LOGIN; ?></a></li>
                                    <?php } } ?>  
                                    <?php if ($_SESSION['cart']->count_contents() != 0) { ?>
                                        <li><a class="<?php if($body_id == 'shoppingcart'){?>home<?php } ?>" href="<?php echo zen_href_link(FILENAME_SHOPPING_CART, '', 'NONSSL'); ?>"><?php echo HEADER_TITLE_CART_CONTENTS; ?></a></li>
                                        <li><a class="<?php if($body_id == 'checkoutshipping' || $body_id == 'checkoutpayment' || $body_id == 'checkoutconfirmation'){?>home<?php } ?>" href="<?php echo zen_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL'); ?>"><?php echo HEADER_TITLE_CHECKOUT; ?></a></li>
                                    <?php } ?>
                                <!-- ====================================== -->
                            </ul>
                        </div>
                        <div class="col-xs-12 col-md-1 col-sm-1">
                            <div class="shopping_cart" id="shopping_cart">
                                <!-- ========== SHOPPING CART ========== -->
                                <?php 
                                   if ($_SESSION['cart']->count_contents() >= 1){
                                       $cart_text = '<span class="st3"> ' . $_SESSION['cart']->count_contents();
                                       if ( $_SESSION['cart']->count_contents() < 2 )
                                           $cart_text .= ' </span>';
                                       else    
                                           $cart_text .= ' </span>';
                                   } else {
                                       $cart_text = '<span class="st3"> 0 </span>';
                                   }
                                   
                                ?>
                                <div class="shop-box-wrap">
                                    <span class="cart_title"><?php echo BOX_HEADING_SHOPPING_CART;?></span><?php echo $cart_text ?>
                                </div>
                                <div class="shopping_cart_content" id="shopping_cart_content" >
                                    <?php require($template->get_template_dir('tpl_shopping_cart_header.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_shopping_cart_header.php'); 
                                    echo $content;?>
                                </div>
                                <!-- =================================== --> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg1">
            <?php if(TM_MEGAMENU_STICK == 'true') { ?> 
                <div class="stickUpTop fix">
                <div class="stickUpHolder">
                <?php } ?> 
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-md-3 col-sm-3 fright">
                            <div id="search_block" class="clearfix">
                                <!-- ========== SEARCH ========== -->
                                <form name="quick_find_header" action="<?php echo zen_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false) ?>" method="get" class="form-inline form-search pull-right">
                                    <?php 
                                        echo zen_draw_hidden_field('main_page',FILENAME_ADVANCED_SEARCH_RESULT);
                                        echo zen_draw_hidden_field('search_in_description', '1') . zen_hide_session_id();
                                    ?>
                                    <label class="sr-only" for="searchInput">Search</label>
                                    <input class="form-control" id="searchInput" type="text" name="keyword" />
                                    <button type="submit" class="button-search"><i class="fa fa-search"></i><b><?php echo BOX_HEADING_SEARCH ?></b></button>
                                </form>
                                <!-- ============================ -->
                            </div>
                        </div> 
                        <div class="col-xs-12 col-sm-9 col-md-9 fleft">
                            <?php if(TM_MEGAMENU_STATUS == 'true') { ?>
                                <div class="cat-title"><?php echo BOX_HEADING_MENU; ?></div>
                                <!--bof-mega menu display-->
                                <?php require($template->get_template_dir('tpl_mega_menu.php',DIR_WS_TEMPLATE, $current_page_base,'common'). '/tpl_mega_menu.php');?> 
                                <!--eof-mega menu display-->
                            <?php } ?> 
                        </div> 
                    </div>
                </div>
            </div>
            <?php if(TM_MEGAMENU_STICK == 'true') { ?> 
</div>
</div>
<?php } ?>
        </header>
    

    <?php 
        if (HEADER_SALES_TEXT != '' || (SHOW_BANNERS_GROUP_SET2 != '' && $banner = zen_banner_exists('dynamic', SHOW_BANNERS_GROUP_SET2))) {
            if (HEADER_SALES_TEXT != '') {
    ?>
                <div id="tagline"><?php echo HEADER_SALES_TEXT;?></div>
    <?php
            }
        }
    ?>
   




    <!-- ========== CATEGORIES TABS ========= -->
        <?php require($template->get_template_dir('tpl_modules_categories_tabs.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_categories_tabs.php'); ?>
    <!-- ==================================== -->
                
    
<?php } ?>