<?php
/**
 * Page Template
 *
 * Displays EZ-Pages footer-bar content.<br />
 *
 * @package templateSystem
 * @copyright Copyright 2003-2010 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_ezpages_bar_footer.php 15645 2010-03-07 18:17:19Z drbyte $
 */
   /**
   * require code to show EZ-Pages list
   */
  include(DIR_WS_MODULES . zen_get_module_directory('ezpages_bar_footer.php'));
//sort links into ul lists according to first digit of sort order
//allows ordering links within columns - use 110, 120, 210, 220...
if (sizeof($var_linksList) >= 1) {
  $col_output = '';
  $cols = 0;
  for ($list_col=1; $list_col<=9; $list_col++) { 
    $col = '';
    $col_links = 0;
    $col .= "\n" . '<div class="ezpagesFooterCol col' . $list_col . '" style="widthplaceholder%">' . (defined('EZPAGES_FOOTER_COL_HEADING_' . $list_col)? '<h4>' . constant('EZPAGES_FOOTER_COL_HEADING_' . $list_col) . '</h4>' . "\n": "\n");
    $col .= '<ul>' . "\n";
    for ($i=1, $n=sizeof($var_linksList); $i<=$n; $i++) { 
      if(substr($var_linksList[$i]['sort'], 0, 1) == $list_col){ 
        $col .= defined('EZPAGES_FOOTER_COL_SUBHEAD_' . $var_linksList[$i]['id'])? '  <li><h4>' . constant('EZPAGES_FOOTER_COL_SUBHEAD_' . $var_linksList[$i]['id']) . '</h4></li>': '';
        $col .= '  <li><a href="' . $var_linksList[$i]['link'] . '"' . (active_page_class($var_linksList[$i]['id'],$var_linksList[$i]['altURL'])?  active_page_class($var_linksList[$i]['id'],$var_linksList[$i]['altURL']): '') . '>' . $var_linksList[$i]['name'] . '</a>';
        $col .= defined('EZPAGES_FOOTER_COL_COMMENT_' . $var_linksList[$i]['id'])? "\n" . '    <span class="ezpagesFooterColComment">' . constant('EZPAGES_FOOTER_COL_COMMENT_' . $var_linksList[$i]['id']) . '</span>': '';
		$col .= '</li>' . "\n";
        $col_links++;
      }
    } 
    $col .= '</ul>' . "\n" . '</div>';
    //if ($col_links >= 1) echo $col;
    if ($col_links >= 1) {
	  $col_output .= $col;
	  $cols ++;
	}
  } // end FOR loop
  $col_width = (int)(100/$cols);//update divs with % width
  $col_output = str_replace('widthplaceholder', 'width: ' . $col_width, $col_output);
  echo $col_output . '';
} // end if ?>