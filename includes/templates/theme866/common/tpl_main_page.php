<?php
/**
 * Common Template - tpl_main_page.php
 *
 * Governs the overall layout of an entire page<br>
 * Normally consisting of a header, left side column. center column. right side column and footer<br>
 * For customizing, this file can be copied to /templates/your_template_dir/pagename<br>
 * example: to override the privacy page<br>
 * - make a directory /templates/my_template/privacy<br>
 * - copy /templates/templates_defaults/common/tpl_main_page.php to /templates/my_template/privacy/tpl_main_page.php<br>
 * <br>
 * to override the global settings and turn off columns un-comment the lines below for the correct column to turn off<br>
 * to turn off the header and/or footer uncomment the lines below<br>
 * Note: header can be disabled in the tpl_header.php<br>
 * Note: footer can be disabled in the tpl_footer.php<br>
 * <br>
 * $flag_disable_header = true;<br>
 * $flag_disable_left = true;<br>
 * $flag_disable_right = true;<br>
 * $flag_disable_footer = true;<br>
 * <br>
 * // example to not display right column on main page when Always Show Categories is OFF<br>
 * <br>
 * if ($current_page_base == 'index' and $cPath == '') {<br>
 *  $flag_disable_right = true;<br>
 * }<br>
 * <br>
 * example to not display right column on main page when Always Show Categories is ON and set to categories_id 3<br>
 * <br>
 * if ($current_page_base == 'index' and $cPath == '' or $cPath == '3') {<br>
 *  $flag_disable_right = true;<br>
 * }<br>
 *
 * @package templateSystem
 * @copyright Copyright 2003-2007 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_main_page.php 7085 2007-09-22 04:56:31Z ajeh $
 */

// the following IF statement can be duplicated/modified as needed to set additional flags
  if (in_array($current_page_base,explode(",",'list_pages_to_skip_all_right_sideboxes_on_here,separated_by_commas,and_no_spaces')) ) {
    $flag_disable_right = true;
  }
  $header_template = 'tpl_header.php';
  $footer_template = 'tpl_footer.php';
  $left_column_file = 'column_left.php';
  $right_column_file = 'column_right.php';
  $body_id = ($this_is_home_page) ? 'indexHome' : str_replace('_', '', $_GET['main_page']);
  
?>
</head>
<body id="<?php echo $body_id . 'Body'; ?>"<?php if($zv_onload !='') echo ' onload="'.$zv_onload.'"'; ?>>
  
  <?php if ($messageStack->size('contact') > 0) echo $messageStack->output('contact'); ?>

 <div id="page">
<!-- ========== IMAGE BORDER TOP ========== --> 

<!-- BOF- BANNER TOP display -->

    <?php
      if (SHOW_BANNERS_GROUP_SET1 != '' && $banner = zen_banner_exists('dynamic', SHOW_BANNERS_GROUP_SET1)) {
        if ($banner->RecordCount() > 0) {
          ?>
        <div id="bannerTop" class="banners"><?php echo zen_display_banner('static', $banner); ?></div>
        <?php
      }
    }
    ?>
<!-- EOF- BANNER TOP display -->
    
    <!-- ====================================== --> 

    <!-- ========== HEADER ========== -->
      <?php
	    /* prepares and displays header output */
	     if (CUSTOMERS_APPROVAL_AUTHORIZATION == 1 && CUSTOMERS_AUTHORIZATION_HEADER_OFF == 'true' and ($_SESSION['customers_authorization'] != 0 or $_SESSION['customer_id'] == '')) {
		    $flag_disable_header = true;
	     }
	     require($template->get_template_dir('tpl_header.php',DIR_WS_TEMPLATE, $current_page_base,'common'). '/tpl_header.php');
	    ?>
    <!-- ============================ -->
  <section>
      <?php
    		if($this_is_home_page){
    	?>
        <div class="slider"> 
          <!-- begin edit for ZX Slideshow -->
          <?php if(ZX_SLIDESHOW_STATUS == 'true') { ?>
          <?php require($template->get_template_dir('zx_slideshow.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/zx_slideshow.php'); ?>
          <?php } ?>
          <!-- end edit for ZX Slideshow --> 
        </div>
      <div class="container">
        <div class="banners1 clearfix">
          <div class="row">
          <?php
            $new_banner_search = zen_build_banners_group(SHOW_BANNERS_GROUP_SET3);
    
              // secure pages
              switch ($request_type) {
                case ('SSL'):
                  $my_banner_filter=" and banners_on_ssl= " . "1";
                  break;
                case ('NONSSL'):
                  $my_banner_filter='';
                  break;
              }
            
              $sql = "select banners_id from " . TABLE_BANNERS . " where status = 1 " . $new_banner_search . $my_banner_filter . " order by banners_sort_order";
              $banners_all = $db->Execute($sql);
    
            // if no active banner in the specified banner group then the box will not show
              $banner_cnt = 0;
              while (!$banners_all->EOF) {
                $banner_cnt++;
                $banner = zen_banner_exists('dynamic', SHOW_BANNERS_GROUP_SET3);
                echo '<div data-match-height="items-b" class="col-sm-6 col-xs-12 item item_'.$banner_cnt.'">'.tm_zen_display_banner('static', $banners_all->fields['banners_id']).'</div>';
            // add spacing between banners
                if ($banner_cnt < $banners_all->RecordCount()) {
                  
                }
                $banners_all->MoveNext();
              }
            ?>
          </div>
        </div>
      </div>
      <?php
    		}
    	?>
    <div class="container">
          <?php if (DEFINE_BREADCRUMB_STATUS == '1' || (DEFINE_BREADCRUMB_STATUS == '2' && !$this_is_home_page) ) 
                { ?>
      <div id="navBreadCrumb" class="breadcrumb"><?php echo $breadcrumb->trail(""); ?></div>
      <?php } ?>
    <div class="row">
      <div class="main-col 
	  
	   <?php if (COLUMN_LEFT_STATUS == 1 ) {?> left_column<?php }?><?php if (COLUMN_RIGHT_STATUS == 1 ) {?> right_column<?php }?>
      <?php if ((COLUMN_LEFT_STATUS == 0 && $body_id !== 'productinfo') || (COLUMN_RIGHT_STATUS == 0 && $body_id !== 'productinfo')) { ?>col-sm-12 <?php }?>">

		 <div class="row">
        <div class="center_column col-xs-12
				<?php 
					if (COLUMN_LEFT_STATUS == 1 && COLUMN_RIGHT_STATUS == 1 && $body_id !== 'productinfo') { 
						echo 'col-sm-8 two_columns';
					} elseif ((COLUMN_LEFT_STATUS == 1 && $body_id !== 'productinfo') || (COLUMN_RIGHT_STATUS == 1 && $body_id !== 'productinfo')) { 
						echo 'col-sm-12 with_col';
					} else {
						echo 'col-sm-12';
					} 
					
    			?> ">
          <?php 
            if ($messageStack->size('upload') > 0) echo $messageStack->output('upload');
    				require($body_code);
    			?>
        </div>
      
      <?php
	  if($body_id !== 'productinfo'){
    				if (COLUMN_RIGHT_STATUS == 0 or (CUSTOMERS_APPROVAL == '1' and $_SESSION['customer_id'] == '')) {
    				  // global disable of column_right
    				  $flag_disable_right = true;
    				}
    				if (!isset($flag_disable_right) || !$flag_disable_right) {
    			?>
        <div class="column right_column col-xs-12 col-sm-4">
          <?php
    						 /* ----- prepares and displays left column sideboxes ----- */
    						?>
          <?php require(DIR_WS_MODULES . zen_get_module_directory('column_right.php')); ?>
        </div> 
        <?php
    				}
	  		}
    			?>
            </div>
          </div>
            <?php
		if($body_id !== 'productinfo'){
			if (COLUMN_LEFT_STATUS == 0 or (CUSTOMERS_APPROVAL == '1' and $_SESSION['customer_id'] == '')) {
			  // global disable of column_left
			  $flag_disable_left = true;
			}
			if (!isset($flag_disable_left) || !$flag_disable_left) {
		?>
        <aside class="column left_column col-xs-12 col-sm-3">
	       <?php
           /* ----- prepares and displays left column sideboxes ----- */
        ?>
        <?php require(DIR_WS_MODULES . zen_get_module_directory('column_left.php')); ?>
        </aside>
        <?php
			 }
			}
		?>
        </div>
      </div>
        <?php
          if($this_is_home_page){
          ?>
        <div class="banners2">
          <?php
              $new_banner_search = zen_build_banners_group(SHOW_BANNERS_GROUP_SET4);
                // secure pages
                switch ($request_type) {
                  case ('SSL'):
                    $my_banner_filter=" and banners_on_ssl= " . "1";
                    break;
                  case ('NONSSL'):
                    $my_banner_filter='';
                    break;
                }         
                $sql = "select banners_id from " . TABLE_BANNERS . " where status = 1 " . $new_banner_search . $my_banner_filter . " order by banners_sort_order";
                $banners_all = $db->Execute($sql);
              // if no active banner in the specified banner group then the box will not show
                $banner_cnt = 0;
                while (!$banners_all->EOF) {
                  $banner_cnt++;
                  $banner = zen_banner_exists('dynamic', SHOW_BANNERS_GROUP_SET4);
                  echo '<div class="item_'.$banner_cnt.'">'.tm_zen_display_banner('static', $banners_all->fields['banners_id']).'</div>';
              // add spacing between banners
                  if ($banner_cnt < $banners_all->RecordCount()) {              
                  }
                  $banners_all->MoveNext();
                }
          ?>
        </div>
        <?php
        }
      ?>

    <?php if($this_is_home_page) { ?>
      <div class="container">
        <div class="row">
          <div class="col-xs-12">
          <!--bof-featured display-->
          <?php require($template->get_template_dir('tpl_modules_featured_products.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/tpl_modules_featured_products.php'); ?>
          <!--eof-featured display-->
          </div>
        </div>
      </div>
        <div class="banners3">
          <?php
              $new_banner_search = zen_build_banners_group(SHOW_BANNERS_GROUP_SET5);
                // secure pages
                switch ($request_type) {
                  case ('SSL'):
                    $my_banner_filter=" and banners_on_ssl= " . "1";
                    break;
                  case ('NONSSL'):
                    $my_banner_filter='';
                    break;
                }         
                $sql = "select banners_id from " . TABLE_BANNERS . " where status = 1 " . $new_banner_search . $my_banner_filter . " order by banners_sort_order";
                $banners_all = $db->Execute($sql);
              // if no active banner in the specified banner group then the box will not show
                $banner_cnt = 0;
                while (!$banners_all->EOF) {
                  $banner_cnt++;
                  $banner = zen_banner_exists('dynamic', SHOW_BANNERS_GROUP_SET5);
                  echo '<div class="item_'.$banner_cnt.'">'.tm_zen_display_banner('static', $banners_all->fields['banners_id']).'</div>';
              // add spacing between banners
                  if ($banner_cnt < $banners_all->RecordCount()) {              
                  }
                  $banners_all->MoveNext();
                }
          ?>
        </div>
        <?php
        }
      ?>
      <div class="container">
        <div class="row">
          <!--bof-custom block display-->
          <?php require($template->get_template_dir('tpl_customblock.php',DIR_WS_TEMPLATE, $current_page_base,'common'). '/tpl_customblock.php');?> 
          <!--eof-custom block display--> 
        </div>
      </div>
      <div class="clearfix"></div>
  </section>
<!-- ========== FOOTER ========== -->
  <footer>
    <div class="footer-container">
        <?php
        	 /* prepares and displays footer output */
        	  if (CUSTOMERS_APPROVAL_AUTHORIZATION == 1 && CUSTOMERS_AUTHORIZATION_FOOTER_OFF == 'true' and ($_SESSION['customers_authorization'] != 0 or $_SESSION['customer_id'] == '')) {
        		$flag_disable_footer = true;
        	  }
        	  require($template->get_template_dir('tpl_footer.php',DIR_WS_TEMPLATE, $current_page_base,'common'). '/tpl_footer.php');
        	?>
    </div>
  </footer>
<!-- ============================ --> 
</div>

<!-- ========================================= -->
</body>
