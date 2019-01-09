<?php
/**
 * Module Template
 *
 * @package templateSystem
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_modules_listing_display_order.php 3369 2006-04-03 23:09:13Z drbyte $
 */
?>
<?php
// NOTE: to remove a sort order option add an HTML comment around the option to be removed
?>
<div class="top_sort">
<div class="content_sortPagiBar list">
    <div class="dropdown">
      <form name="sorter_form" id="sort_form" action="<?=zen_href_link($_GET['main_page']); ?>" method="get">
          <?php 
                echo zen_draw_hidden_field('main_page', $_GET['main_page']);
                echo zen_hide_session_id();
          ?>
          <input type="hidden" name="disp_order" value="1" id="disp_order"/>
          <button class="btn-default-small dropdown-toggle" type="button" id="dropdownMenuSort" data-toggle="dropdown">
            <?php 
                $values_arr = array(TEXT_INFO_SORT_BY_PRODUCTS_NAME,
                                    TEXT_INFO_SORT_BY_PRODUCTS_NAME_DESC,
                                    TEXT_INFO_SORT_BY_PRODUCTS_PRICE,
                                    TEXT_INFO_SORT_BY_PRODUCTS_PRICE_DESC,
                                    TEXT_INFO_SORT_BY_PRODUCTS_MODEL,
                                    TEXT_INFO_SORT_BY_PRODUCTS_DATE_DESC,
                                    TEXT_INFO_SORT_BY_PRODUCTS_DATE);
                $add_str = '';
                if(isset($_GET['disp_order']))
                    $add_str.= ' '.$values_arr[($_GET['disp_order'] - 1)];
                echo TEXT_INFO_SORT_BY.$add_str; 
            ?>
            <span class="caret"></span>
          </button>
          <ul class="dropdown-menu" aria-labelledby="dropdownMenuSort">
            <?php if ($disp_order != $disp_order_default) { ?>
                <li <?php echo ($disp_order == $disp_order_default ? 'selected="selected"' : ''); ?>><a role="menuitem" href="javascript:void(0);" onclick="document.getElementById('disp_order').value = <?php echo $disp_order_default; ?>;document.getElementById('sort_form').submit();"><?php echo PULL_DOWN_ALL_RESET; ?></a></li>
            <?php } // reset to store default ?>
            <li <?php echo ($disp_order == '1' ? 'selected="selected"' : ''); ?>><a role="menuitem" href="javascript:void(0);" onclick="document.getElementById('disp_order').value = 1;document.getElementById('sort_form').submit();"><?php echo TEXT_INFO_SORT_BY_PRODUCTS_NAME; ?></a></li>
            <li <?php echo ($disp_order == '2' ? 'selected="selected"' : ''); ?>><a role="menuitem" href="javascript:void(0);" onclick="document.getElementById('disp_order').value = 2;document.getElementById('sort_form').submit();"><?php echo TEXT_INFO_SORT_BY_PRODUCTS_NAME_DESC; ?></a></li>
            <li <?php echo ($disp_order == '3' ? 'selected="selected"' : ''); ?>><a role="menuitem" href="javascript:void(0);" onclick="document.getElementById('disp_order').value = 3;document.getElementById('sort_form').submit();"><?php echo TEXT_INFO_SORT_BY_PRODUCTS_PRICE; ?></a></li>
            <li <?php echo ($disp_order == '4' ? 'selected="selected"' : ''); ?>><a role="menuitem" href="javascript:void(0);" onclick="document.getElementById('disp_order').value = 4;document.getElementById('sort_form').submit();"><?php echo TEXT_INFO_SORT_BY_PRODUCTS_PRICE_DESC; ?></a></li>
            <li <?php echo ($disp_order == '5' ? 'selected="selected"' : ''); ?>><a role="menuitem" href="javascript:void(0);" onclick="document.getElementById('disp_order').value = 5;document.getElementById('sort_form').submit();"><?php echo TEXT_INFO_SORT_BY_PRODUCTS_MODEL; ?></a></li>
            <li <?php echo ($disp_order == '6' ? 'selected="selected"' : ''); ?>><a role="menuitem" href="javascript:void(0);" onclick="document.getElementById('disp_order').value = 6;document.getElementById('sort_form').submit();"><?php echo TEXT_INFO_SORT_BY_PRODUCTS_DATE_DESC; ?></a></li>
            <li <?php echo ($disp_order == '7' ? 'selected="selected"' : ''); ?>><a role="menuitem" href="javascript:void(0);" onclick="document.getElementById('disp_order').value = 7;document.getElementById('sort_form').submit();"><?php echo TEXT_INFO_SORT_BY_PRODUCTS_DATE; ?></a></li>
          </ul>
      </form>
    </div>	
</div>