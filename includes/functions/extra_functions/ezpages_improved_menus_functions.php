<?php
/**
 *
 * @copyright Copyright 2010 Glenn Herbert
 * @copyright Portions Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.gnu.org/licenses/ GNU Public License V3.0
 * @version $Id: /includes/functions/extra_functions/ezpages_improved_menus_functions.php 
 *Ezpages Footer Columns copy of master file from Ezpages Improved Menus by Glenn Herbert (gjh42) 2010-11-20
 */

//call as   active_page_class($var_linksList[$i]['id'],$var_linksList[$i]['altURL']);
function active_page_class($ezpid,$alturl) {
  global $this_is_home_page;
  $active = '';
  if($_GET['main_page'] == 'page') {
    $active = ($_GET['id'] == $ezpid)? ' class="activeEZPage"': '';
  }elseif($alturl) {
    $alturl = htmlspecialchars_decode(str_replace(HTTP_SERVER . DIR_WS_CATALOG,'/',$alturl));
    $active = ((strstr($_SERVER['REQUEST_URI'],$alturl) and !strstr('/index.php?main_page=index',$alturl)) or ($this_is_home_page and strstr('/index.php?main_page=index',$alturl)))?' class="activeILPage"': '';
  }
  return $active;
}
?>