<?php
/**
 * Common Template main_template_vars handler
 *
 * Normally a page will automatically load its own template based on the page name.<br />
 * so that a page called some_page will load tpl_some_page_default.php from the template directory.<br />
 * <br />
 * However sometimes a page may need to choose the template it displays based on a set of criteria.<br />
 * Placing a file in the includes/modules/pages/some_page/ directory called main_template_vars.php<br />
 * allows you to override this page and choose the template that loads.<br />
 *
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: main_template_vars.php 2620 2005-12-20 00:52:57Z drbyte $
 */

  if (file_exists(DIR_WS_MODULES . 'pages/' . $current_page_base . '/main_template_vars.php')) {
    $body_code = DIR_WS_MODULES . 'pages/' . $current_page_base . '/main_template_vars.php';
  } else {
    $body_code = $template->get_template_dir('tpl_' . preg_replace('/.php/', '',$_GET['main_page']) . '_default.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_' . $_GET['main_page'] . '_default.php';
  }
  
  // Display a banner from the specified group or banner id ($identifier)
  function tm_zen_display_banner($action, $identifier) {
    global $db, $request_type;

    switch ($request_type) {
      case ('SSL'):
        $my_banner_filter=" and banners_on_ssl= " . "1 ";
        break;
      case ('NONSSL'):
        $my_banner_filter='';
        break;
    }

    if ($action == 'dynamic') {
      $new_banner_search = zen_build_banners_group($identifier);

      $banners_query = "select count(*) as count
                        from " . TABLE_BANNERS . "
                           where status = '1' " .
                           $new_banner_search . $my_banner_filter;

      $banners = $db->Execute($banners_query);

      if ($banners->fields['count'] > 0) {
        $banner = $db->Execute("select banners_id, banners_title, banners_image, banners_html_text, banners_open_new_windows, banners_url
                               from " . TABLE_BANNERS . "
                               where status = 1 " .
                               $new_banner_search . $my_banner_filter . " order by rand()");

      } else {
        return '<p class="alert">ZEN ERROR! (zen_display_banner(' . $action . ', ' . $identifier . ') -> No banners with group \'' . $identifier . '\' found!</p>';
      }
    } elseif ($action == 'static') {
      if (is_object($identifier)) {
        $banner = $identifier;
      } else {
        $banner_query = "select banners_id, banners_title, banners_image, banners_html_text, banners_open_new_windows, banners_url
                         from " . TABLE_BANNERS . "
                         where status = 1
                         and banners_id = '" . (int)$identifier . "'" . $my_banner_filter;

        $banner = $db->Execute($banner_query);

        if ($banner->RecordCount() < 1) {
          //return '<strong>ZEN ERROR! (zen_display_banner(' . $action . ', ' . $identifier . ') -> Banner with ID \'' . $identifier . '\' not found, or status inactive</strong>';
        }
      }
    } else {
      return '<p class="alert">ZEN ERROR! (zen_display_banner(' . $action . ', ' . $identifier . ') -> Unknown $action parameter value - it must be either \'dynamic\' or \'static\'</p>';
    }

    if (zen_not_null($banner->fields['banners_html_text'])) {
      $banner_string = $banner->fields['banners_html_text'];
    } else {
      if ($banner->fields['banners_url'] == '') {
        $banner_string = zen_image(DIR_WS_IMAGES . $banner->fields['banners_image'], $banner->fields['banners_title']);
      } else {
        if ($banner->fields['banners_open_new_windows'] == '1') {
          $banner_string = '<a href="' . zen_href_link(FILENAME_REDIRECT, 'action=banner&goto=' . $banner->fields['banners_id']) . '" target="_blank">' . zen_image(DIR_WS_IMAGES . $banner->fields['banners_image'], $banner->fields['banners_title']) . '</a>
		  <a class="title" href="' . zen_href_link(FILENAME_REDIRECT, 'action=banner&goto=' . $banner->fields['banners_id']) . '" target="_blank"><span>' . $banner->fields['banners_title'] . '</span></a>';
        } else {
          $banner_string = '<a href="' . zen_href_link(FILENAME_REDIRECT, 'action=banner&goto=' . $banner->fields['banners_id']) . '">' . zen_image(DIR_WS_IMAGES . $banner->fields['banners_image'], $banner->fields['banners_title']) . '<div class="title">' . $banner->fields['banners_title'] . '</div></a>
		  ';
        }
      }
	}

    zen_update_banner_display_count($banner->fields['banners_id']);

    return $banner_string;
  }
  
  function tm_zen_s_display_banner($action, $identifier) {
    global $db, $request_type;

    switch ($request_type) {
      case ('SSL'):
        $my_banner_filter=" and banners_on_ssl= " . "1 ";
        break;
      case ('NONSSL'):
        $my_banner_filter='';
        break;
    }

    if ($action == 'dynamic') {
      $new_banner_search = zen_build_banners_group($identifier);

      $banners_query = "select count(*) as count
                        from " . TABLE_BANNERS . "
                           where status = '1' " .
                           $new_banner_search . $my_banner_filter;

      $banners = $db->Execute($banners_query);

      if ($banners->fields['count'] > 0) {
        $banner = $db->Execute("select banners_id, banners_title, banners_image, banners_html_text, banners_open_new_windows, banners_url
                               from " . TABLE_BANNERS . "
                               where status = 1 " .
                               $new_banner_search . $my_banner_filter . " order by rand()");

      } else {
        return '<p class="alert">ZEN ERROR! (zen_display_banner(' . $action . ', ' . $identifier . ') -> No banners with group \'' . $identifier . '\' found!</p>';
      }
    } elseif ($action == 'static') {
      if (is_object($identifier)) {
        $banner = $identifier;
      } else {
        $banner_query = "select banners_id, banners_title, banners_image, banners_html_text, banners_open_new_windows, banners_url
                         from " . TABLE_BANNERS . "
                         where status = 1
                         and banners_id = '" . (int)$identifier . "'" . $my_banner_filter;

        $banner = $db->Execute($banner_query);

        if ($banner->RecordCount() < 1) {
          //return '<strong>ZEN ERROR! (zen_display_banner(' . $action . ', ' . $identifier . ') -> Banner with ID \'' . $identifier . '\' not found, or status inactive</strong>';
        }
      }
    } else {
      return '<p class="alert">ZEN ERROR! (zen_display_banner(' . $action . ', ' . $identifier . ') -> Unknown $action parameter value - it must be either \'dynamic\' or \'static\'</p>';
    }

    if (zen_not_null($banner->fields['banners_html_text'])) {
      $banner_string = $banner->fields['banners_html_text'];
    } else {
      if ($banner->fields['banners_url'] == '') {
        $banner_string = zen_image(DIR_WS_IMAGES . $banner->fields['banners_image'], $banner->fields['banners_title']);
      } else {
        if ($banner->fields['banners_open_new_windows'] == '1') {
          $banner_string = '<a href="' . zen_href_link(FILENAME_REDIRECT, 'action=banner&goto=' . $banner->fields['banners_id']) . '" target="_blank">' . zen_image(DIR_WS_IMAGES . $banner->fields['banners_image'], $banner->fields['banners_title']) . '</a>';
        } else {
          $banner_string = '<a href="' . zen_href_link(FILENAME_REDIRECT, 'action=banner&goto=' . $banner->fields['banners_id']) . '">' . zen_image(DIR_WS_IMAGES . $banner->fields['banners_image'], $banner->fields['banners_title']) . '</a>';
        }
      }
	}

    zen_update_banner_display_count($banner->fields['banners_id']);

    return $banner_string;
  }

////
?>