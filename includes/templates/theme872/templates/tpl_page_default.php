<?php
/**
 * Page Template
 *
 * This is the template used for EZ-Pages content display.  It is named "tpl_page_default" instead of ezpage for friendlier appearance
 *
 * @package templateSystem
 * @copyright Copyright 2003-2012 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version GIT: $Id: Author: DrByte  Sat Oct 6 16:09:44 2012 -0400 Modified in v1.5.2 $
 */
?>
<div class="centerColumn" id="ezPageDefault">
<h1 id="ezPagesHeading"><?php echo $var_pageDetails->fields['pages_title']; ?></h1>

<?php if (EZPAGES_SHOW_PREV_NEXT_BUTTONS=='2' and $counter > 1) { ?>
<div id="navEZPageNextPrev">
      <a href="<?php echo $prev_link; ?>"><?php echo $previous_button; ?></a>
      <?php echo zen_back_link() . $home_button; ?></a>
      <a href="<?php echo $next_link; ?>"><?php echo $next_item_button; ?></a>
      <br><br>
    </div>
<?php } elseif (EZPAGES_SHOW_PREV_NEXT_BUTTONS=='1') { ?>
    <div id="navEZPageNextPrev"><?php echo zen_back_link() . $home_button . '</a>'; ?></div>
<?php  } ?>


    <div><?php echo $var_pageDetails->fields['pages_html_text']; ?></div>
</div>
