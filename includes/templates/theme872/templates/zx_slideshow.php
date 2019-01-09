<script>
jQuery(window).load(function() {
        jQuery('#slider').nivoSlider({
			effect: '<?php echo ZX_SLIDESHOW_EFFECT; ?>',
			animSpeed: <?php echo ZX_SLIDESHOW_ANIM_SPEED; ?>,
 	        pauseTime: <?php echo ZX_SLIDESHOW_PAUSE; ?>,
			directionNav: <?php echo ZX_SLIDESHOW_NAV; ?>,
			directionNavHide: <?php echo ZX_SLIDESHOW_NAV_HIDE; ?>,
 	        controlNav: <?php echo ZX_SLIDESHOW_CONTROL_NAV; ?>,
			pauseOnHover: <?php echo ZX_SLIDESHOW_HOVER_PAUSE; ?>,
			captionOpacity: <?php echo ZX_SLIDESHOW_CAPTION_OPACITY; ?>
			});
    });

</script>

<div class="slider-wrapper theme-<?php echo ZX_SLIDESHOW_THEME; ?>">
    	<div id="slider" class="nivoSlider">
			<?php
            $new_banner_search = zen_build_banners_group(SHOW_BANNERS_GROUP_SET2);
    
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
                $banner = zen_banner_exists('dynamic', SHOW_BANNERS_GROUP_SET2);
                echo tm_zen_s_display_banner('static', $banners_all->fields['banners_id']);
            // add spacing between banners
                if ($banner_cnt < $banners_all->RecordCount()) {
                  
                }
                $banners_all->MoveNext();
              }
        ?>
			

		</div>
    </div>