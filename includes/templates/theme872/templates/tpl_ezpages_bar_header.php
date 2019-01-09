<?php
/**
 * Page Template
 *
 * Displays EZ-Pages Header-Bar content.<br />
 *
 * @package templateSystem
 * @copyright Copyright 2003-2011 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_ezpages_bar_header.php 3377 2011-04-05 04:43:11Z ajeh $
 */

  /**
   * require code to show EZ-Pages list
   */
  include(DIR_WS_MODULES . zen_get_module_directory('ezpages_bar_header.php'));
?>
<?php if (sizeof($var_linksList) >= 1) { ?>
<nav> 
  <ul class="ez-menu">
    <?php  
    $count = 1; 
        foreach($var_linksList as $list) {  
            $link_list = substr($list['link'], strrpos($list['link'], '/'));  
            $link_referer = str_replace('&', '&amp;', $_SERVER['REQUEST_URI']);  
            $link_referer = substr($link_referer, strrpos($link_referer, '/')); 
             
            if($link_list == $_SERVER['REQUEST_URI'] || $_REQUEST['id']==$list['id'] || $link_list == $link_referer){  
                $class = 'selected';  
            }else{  
                $class = '';  
            }  
            if($this_is_home_page && strpos($link_list, 'main_page=index') && $count== 1){  
                $class = 'selected';  
            }  
           if(!next($var_linksList)){ 
                $last = 'last'; 
            } 
            else $last = '';  
            if($count == 1){ 
                $first = 'first'; 
            } 
            else $first = '';  
            echo '  
                <li class="' . $class . ' '.$last.' '.$first.'">  
                    <a href="' . $list['link'] . '">
                        <span>' . $list['name'] . '</span>   
                    </a>  
                </li>  
            ';  
            $count ++;  

        }  
       ?>  
  </ul>
</nav>
<?php } ?>

