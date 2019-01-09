<?php
// use $configuration_group_id where needed
// DELETE FROM `configuration_group` WHERE `configuration_group_title` = 'Zen Lightbox';
// delete old config group id
$db->Execute("DELETE FROM ".TABLE_CONFIGURATION_GROUP." WHERE `configuration_group_title` = 'Zen Lightbox'");

//update current gonfiguration values to new $configuration_group_id
$db->Execute("UPDATE ".TABLE_CONFIGURATION." SET configuration_group_id='".$configuration_group_id."' WHERE `configuration_key` LIKE 'ZEN_LIGHTBOX_%'");

//remove admin page and re-add
$zc150 = (PROJECT_VERSION_MAJOR > 1 || (PROJECT_VERSION_MAJOR == 1 && substr(PROJECT_VERSION_MINOR, 0, 3) >= 5));
if ($zc150) { // continue Zen Cart 1.5.0
    $admin_page = 'configZenLightbox';
  // delete configuration menu
  $db->Execute("DELETE FROM ".TABLE_ADMIN_PAGES." WHERE page_key = '".$admin_page."' LIMIT 1;");
  // add configuration menu
  if (!zen_page_key_exists($admin_page)) {
    if ((int)$configuration_group_id > 0) {
      zen_register_admin_page($admin_page,
                              'BOX_CONFIGURATION_ZEN_LIGHTBOX', 
                              'FILENAME_CONFIGURATION',
                              'gID=' . $configuration_group_id, 
                              'configuration', 
                              'Y',
                              $configuration_group_id);
        
      $messageStack->add('Enabled Zen Lightbox Configuration Menu.', 'success');
    }
  }
}

//cycle through configuration options to verify they are present.
$zen_lightbox_config_values[] = array(
    'configuration_key' => 'ZEN_LIGHTBOX_STATUS',
    'configuration_title' => '<b>Zen Lightbox</b>',
    'configuration_value' => 'true',
    'configuration_description' => '<br />If true, all product images on the following pages will be displayed within a lightbox:<br /><br />- document_general_info<br />- document_product_info<br />- page (EZ-Pages)<br />- product_free_shipping_info<br />- product_info<br />- product_music_info<br />- product_reviews<br />- product_reviews_info<br />- product_reviews_write<br /><br /><b>Default: true</b>',
    'sort_order' => '100',
    'use_function' => 'NULL',
    'set_function' => "zen_cfg_select_option(array('true', 'false'), ",
    'last_modified' => 'NOW()',
    'date_added' => 'NOW()',
    'configuration_group_id' => $configuration_group_id,
);
$zen_lightbox_config_values[] = array(
    'configuration_key' => 'ZEN_LIGHTBOX_OVERLAY_OPACITY',
    'configuration_title' => 'Overlay Opacity',
    'configuration_value' => '0.8',
    'configuration_description' => '<br />Controls the transparency of the overlay.<br /><br /><b>Default: 0.8</b>',
    'sort_order' => '101',
    'use_function' => 'NULL',
    'set_function' => "zen_cfg_select_option(array('0', '0.1', '0.2', '0.3', '0.4', '0.5', '0.6', '0.7', '0.8', '0.9', '1'), ",
    'last_modified' => 'NOW()',
    'date_added' => 'NOW()',
    'configuration_group_id' => $configuration_group_id,
);
$zen_lightbox_config_values[] = array(
    'configuration_key' => 'ZEN_LIGHTBOX_OVERLAY_FADE_DURATION',
    'configuration_title' => 'Overlay Fade Duration',
    'configuration_value' => '400',
    'configuration_description' => '<br />Controls the fade duration of the overlay.<br /><br />Note: This value is measured in milliseconds.<br /><br /><b>Default: 400</b><br />',
    'sort_order' => '102',
    'use_function' => 'NULL',
    'set_function' => 'NULL',
    'last_modified' => 'NOW()',
    'date_added' => 'NOW()',
    'configuration_group_id' => $configuration_group_id,
);
$zen_lightbox_config_values[] = array(
    'configuration_key' => 'ZEN_LIGHTBOX_RESIZE_DURATION',
    'configuration_title' => 'Resize Duration',
    'configuration_value' => '400',
    'configuration_description' => '<br />Controls the speed of the image resizing.<br /><br />Note: This value is measured in milliseconds.<br /><br /><b>Default: 400</b><br />',
    'sort_order' => '103',
    'use_function' => 'NULL',
    'set_function' => 'NULL',
    'last_modified' => 'NOW()',
    'date_added' => 'NOW()',
    'configuration_group_id' => $configuration_group_id,
);
$zen_lightbox_config_values[] = array(
    'configuration_key' => 'ZEN_LIGHTBOX_RESIZE_TRANSITION',
    'configuration_title' => 'Resize Transition',
    'configuration_value' => 'false',
    'configuration_description' => '<br />Allows for custom control over the transition effect used to animate the lightbox.<br /><br /><b>Default: false</b><br />',
    'sort_order' => '104',
    'use_function' => 'NULL',
    'set_function' => "zen_cfg_select_option(array('true', 'false'), ",
    'last_modified' => 'NOW()',
    'date_added' => 'NOW()',
    'configuration_group_id' => $configuration_group_id,
);
$zen_lightbox_config_values[] = array(
    'configuration_key' => 'ZEN_LIGHTBOX_INITIAL_WIDTH',
    'configuration_title' => 'Initial Width',
    'configuration_value' => '250',
    'configuration_description' => '<br />If Enable Resize Animations is set to true, the lightbox will resize its width from this value to the current image width, when first displayed.<br /><br />Note: This value is measured in pixels.<br /><br /><b>Default: 250</b><br />',
    'sort_order' => '105',
    'use_function' => 'NULL',
    'set_function' => 'NULL',
    'last_modified' => 'NOW()',
    'date_added' => 'NOW()',
    'configuration_group_id' => $configuration_group_id,
);
$zen_lightbox_config_values[] = array(
    'configuration_key' => 'ZEN_LIGHTBOX_INITIAL_HEIGHT',
    'configuration_title' => 'Initial Height',
    'configuration_value' => '250',
    'configuration_description' => '<br />If Enable Resize Animations is set to true, the lightbox will resize its height from this value to the current image height, when first displayed.<br /><br />Note: This value is measured in pixels.<br /><br /><b>Default: 250</b><br />',
    'sort_order' => '106',
    'use_function' => 'NULL',
    'set_function' => 'NULL',
    'last_modified' => 'NOW()',
    'date_added' => 'NOW()',
    'configuration_group_id' => $configuration_group_id,
);
$zen_lightbox_config_values[] = array(
    'configuration_key' => 'ZEN_LIGHTBOX_IMAGE_FADE_DURATION',
    'configuration_title' => 'Image Fade Duration',
    'configuration_value' => '400',
    'configuration_description' => '<br />Controls the fade duration of images.<br /><br />Note: This value is measured in milliseconds.<br /><br /><b>Default: 400</b><br />',
    'sort_order' => '107',
    'use_function' => 'NULL',
    'set_function' => 'NULL',
    'last_modified' => 'NOW()',
    'date_added' => 'NOW()',
    'configuration_group_id' => $configuration_group_id,
);
$zen_lightbox_config_values[] = array(
    'configuration_key' => 'ZEN_LIGHTBOX_CAPTION_ANIMATION_DURATION',
    'configuration_title' => 'Caption Animation Duration',
    'configuration_value' => '400',
    'configuration_description' => '<br />Controls the animation duration of the caption.<br /><br />Note: This value is measured in milliseconds.<br /><br /><b>Default: 400</b><br />',
    'sort_order' => '108',
    'use_function' => 'NULL',
    'set_function' => 'NULL',
    'last_modified' => 'NOW()',
    'date_added' => 'NOW()',
    'configuration_group_id' => $configuration_group_id,
);
$zen_lightbox_config_values[] = array(
    'configuration_key' => 'ZEN_LIGHTBOX_COUNTER',
    'configuration_title' => 'Display Image Counter',
    'configuration_value' => 'true',
    'configuration_description' => '<br />If true, the image counter will be displayed (below the caption of each image) within the lightbox.<br /><br /><b>Default: true</b>',
    'sort_order' => '109',
    'use_function' => 'NULL',
    'set_function' => "zen_cfg_select_option(array('true', 'false'), ",
    'last_modified' => 'NOW()',
    'date_added' => 'NOW()',
    'configuration_group_id' => $configuration_group_id,
);
$zen_lightbox_config_values[] = array(
    'configuration_key' => 'ZEN_LIGHTBOX_CLOSE_IMAGE',
    'configuration_title' => 'Close on Image Click',
    'configuration_value' => 'false',
    'configuration_description' => '<br />If true, the lightbox will close when the image being displaying is clicked.<br /><br /><b>Default: false</b>',
    'sort_order' => '110',
    'use_function' => 'NULL',
    'set_function' => "zen_cfg_select_option(array('true', 'false'), ",
    'last_modified' => 'NOW()',
    'date_added' => 'NOW()',
    'configuration_group_id' => $configuration_group_id,
);
$zen_lightbox_config_values[] = array(
    'configuration_key' => 'ZEN_LIGHTBOX_CLOSE_OVERLAY',
    'configuration_title' => 'Close on Overlay Click',
    'configuration_value' => 'false',
    'configuration_description' => '<br />If true, the lightbox will close when the overlay is clicked.<br /><br /><b>Default: false</b>',
    'sort_order' => '111',
    'use_function' => 'NULL',
    'set_function' => "zen_cfg_select_option(array('true', 'false'), ",
    'last_modified' => 'NOW()',
    'date_added' => 'NOW()',
    'configuration_group_id' => $configuration_group_id,
);
$zen_lightbox_config_values[] = array(
    'configuration_key' => 'ZEN_LIGHTBOX_PREV_NEXT',
    'configuration_title' => 'Always show Prev / Next',
    'configuration_value' => 'false',
    'configuration_description' => '<br />If true, the lightbox will always show Previous & Next buttons when using additional images. NOTE: This setting will be overwritten automatically when Close on Image Click is set to TRUE.<br /><br /><b>Default: false</b>',
    'sort_order' => '112',
    'use_function' => 'NULL',
    'set_function' => "zen_cfg_select_option(array('true', 'false'), ",
    'last_modified' => 'NOW()',
    'date_added' => 'NOW()',
    'configuration_group_id' => $configuration_group_id,
);
$zen_lightbox_config_values[] = array(
    'configuration_key' => 'ZEN_LIGHTBOX_KEYBOARD_NAVIGATION',
    'configuration_title' => '<b>Keyboard Navigation</b>',
    'configuration_value' => 'true',
    'configuration_description' => '<br />If true, keyboard inputs will also be used to control the lightbox.<br /><br /><b>Default: true</b>',
    'sort_order' => '200',
    'use_function' => 'NULL',
    'set_function' => "zen_cfg_select_option(array('true', 'false'), ",
    'last_modified' => 'NOW()',
    'date_added' => 'NOW()',
    'configuration_group_id' => $configuration_group_id,
);
$zen_lightbox_config_values[] = array(
    'configuration_key' => 'ZEN_LIGHTBOX_ESCAPE_KEYS',
    'configuration_title' => 'Close Lightbox',
    'configuration_value' => '27,88,67',
    'configuration_description' => '<br />The lightbox will close when any of these keys are pressed.<br /><br />Note: Only <a href="http://en.wikipedia.org/wiki/ASCII" target="_blank">ASCII</a> decimal values should be entered and separated with a comma (if listing multiple values).<br /><br /><b>Default: 27,88,67</b><br />',
    'sort_order' => '201',
    'use_function' => 'NULL',
    'set_function' => 'NULL',
    'last_modified' => 'NOW()',
    'date_added' => 'NOW()',
    'configuration_group_id' => $configuration_group_id,
);
$zen_lightbox_config_values[] = array(
    'configuration_key' => 'ZEN_LIGHTBOX_PREVIOUS_KEYS',
    'configuration_title' => 'Previous Image',
    'configuration_value' => '37,80',
    'configuration_description' => '<br />The lightbox will display the previous image (if available) when any of these keys are pressed.<br /><br />Note: Only <a href="http://en.wikipedia.org/wiki/ASCII" target="_blank">ASCII</a> decimal values should be entered and separated with a comma (if listing multiple values).<br /><br /><b>Default: 37,80</b><br />',
    'sort_order' => '202',
    'use_function' => 'NULL',
    'set_function' => 'NULL',
    'last_modified' => 'NOW()',
    'date_added' => 'NOW()',
    'configuration_group_id' => $configuration_group_id,
);
$zen_lightbox_config_values[] = array(
    'configuration_key' => 'ZEN_LIGHTBOX_NEXT_KEYS',
    'configuration_title' => 'Next Image',
    'configuration_value' => '39,78',
    'configuration_description' => '<br />The lightbox will display the next image (if available) when any of these keys are pressed.<br /><br />Note: Only <a href="http://en.wikipedia.org/wiki/ASCII" target="_blank">ASCII</a> decimal values should be entered and separated with a comma (if listing multiple values).<br /><br /><b>Default: 39,78</b><br />',
    'sort_order' => '203',
    'use_function' => 'NULL',
    'set_function' => 'NULL',
    'last_modified' => 'NOW()',
    'date_added' => 'NOW()',
    'configuration_group_id' => $configuration_group_id,
);
$zen_lightbox_config_values[] = array(
    'configuration_key' => 'ZEN_LIGHTBOX_GALLERY_MODE',
    'configuration_title' => '<b>Gallery Mode</b>',
    'configuration_value' => 'true',
    'configuration_description' => '<br />If true, the lightbox will allow additional images to quickly be displayed using previous and next buttons.<br /><br /><b>Default: true</b>',
    'sort_order' => '300',
    'use_function' => 'NULL',
    'set_function' => "zen_cfg_select_option(array('true', 'false'), ",
    'last_modified' => 'NOW()',
    'date_added' => 'NOW()',
    'configuration_group_id' => $configuration_group_id,
);
$zen_lightbox_config_values[] = array(
    'configuration_key' => 'ZEN_LIGHTBOX_GALLERY_MAIN_IMAGE',
    'configuration_title' => 'Include Main Image in Gallery',
    'configuration_value' => 'true',
    'configuration_description' => '<br />If true, the main product image will be included in the lightbox gallery.<br /><br /><b>Default: true</b>',
    'sort_order' => '301',
    'use_function' => 'NULL',
    'set_function' => "zen_cfg_select_option(array('true', 'false'), ",
    'last_modified' => 'NOW()',
    'date_added' => 'NOW()',
    'configuration_group_id' => $configuration_group_id,
);
$zen_lightbox_config_values[] = array(
    'configuration_key' => 'ZEN_LIGHTBOX_EZPAGES',
    'configuration_title' => '<b>EZ-Pages Support</b>',
    'configuration_value' => 'true',
    'configuration_description' => '<br />If true, the lightbox effect will be used for linked images on all EZ-Pages.<br /><br /><b>Default: true</b>',
    'sort_order' => '400',
    'use_function' => 'NULL',
    'set_function' => "zen_cfg_select_option(array('true', 'false'), ",
    'last_modified' => 'NOW()',
    'date_added' => 'NOW()',
    'configuration_group_id' => $configuration_group_id,
);
$zen_lightbox_config_values[] = array(
    'configuration_key' => 'ZEN_LIGHTBOX_FILE_TYPES',
    'configuration_title' => 'File Types',
    'configuration_value' => 'jpg,png,gif',
    'configuration_description' => '<br />On EZ-Pages, the lightbox effect will be applied to all images with one of the following file types.<br /><br /><b>Default: jpg,png,gif</b><br />',
    'sort_order' => '401',
    'use_function' => 'NULL',
    'set_function' => 'NULL',
    'last_modified' => 'NOW()',
    'date_added' => 'NOW()',
    'configuration_group_id' => $configuration_group_id,
);



foreach($zen_lightbox_config_values as $config_value){
    if(!defined($config_value['configuration_key'])){
        $sql = "INSERT INTO ".TABLE_CONFIGURATION." SET ";
        foreach($config_value as $field => $value){
            $sql .= " ".$field.'="'.addslashes($value).'",';
        }
        $sql = rtrim($sql,',');
        $db->Execute($sql);
    }
}