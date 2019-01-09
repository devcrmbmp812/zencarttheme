<?php
/**
 * @copyright Copyright 2010 Glenn Herbert
 * @license http://www.gnu.org/licenses/ GNU Public License V3.0
 * //includes/functions/extra_functions/image_titles_functions.php
 * Ezpages Footer Columns  by Glenn Herbert (gjh42)    2010-09-24 - copied from master version in  Images for Titles  mod
 */

function title_image_exists($title, $file, $ext=''){
  $ext = $ext? $ext: IMAGE_TITLES_EXT;
  $title = file_exists(DIR_WS_TEMPLATE . 'buttons/' . $_SESSION['language'] . '/' . $file . $ext)? zen_image(DIR_WS_TEMPLATE . 'buttons/' . $_SESSION['language'] . '/' . $file . $ext, $title): $title;
  return $title;
}
?>