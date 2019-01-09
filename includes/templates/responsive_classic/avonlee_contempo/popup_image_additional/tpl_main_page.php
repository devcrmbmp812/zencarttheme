<?php
/**mod Image Handler 3.1
 * Override Template for popup_image_additional/tpl_main_page.php
 *
 * @package templateSystem
 * @copyright Copyright 2005-2006 Tim Kroeger
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_main_page.php 2993 2006-02-08 07:14:52Z birdbrain $
 */
?>
<body id="popupAdditionalImage" class="centeredContent" onload="resize();">
<div>
<?php
// $products_values->fields['products_image']
  if (file_exists($_GET['products_image_large_additional'])) {
  echo '<a href="javascript:window.close()">' . zen_image($_GET['products_image_large_additional'], $products_values->fields['products_name'] . ' ' . TEXT_CLOSE_WINDOW) . '</a>';
  } else {
//Begin Image Handler changes 1 of 1
    echo '<a href="javascript:window.close()">' . zen_image(DIR_WS_IMAGES . PRODUCTS_IMAGE_NO_IMAGE . ' ' . TEXT_CLOSE_WINDOW) . '</a>';  /*v4.3.1c-lat9*/
//End Image Handler changes 1 of 1
  }  
?>
</div>
</body>