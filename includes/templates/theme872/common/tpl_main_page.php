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
  <?php if(!$this_is_home_page) { ?>
    <div class="container">
      <div class="row">
        <div class="col-xs-12">
          <?php if (DEFINE_BREADCRUMB_STATUS == '1' || (DEFINE_BREADCRUMB_STATUS == '2' && !$this_is_home_page) ) { ?>
            <div id="navBreadCrumb" class="breadcrumb"><?php echo $breadcrumb->trail(""); ?></div>
          <?php } ?>
        </div>
      </div>
    </div>
  <?php } ?>
  <?php if($this_is_home_page) { ?>
      <div class="container">
        <div class="slider"> 
          <!-- begin edit for ZX Slideshow -->
          <?php if(ZX_SLIDESHOW_STATUS == 'true') { ?>
          <?php require($template->get_template_dir('zx_slideshow.php',DIR_WS_TEMPLATE, $current_page_base,'templates'). '/zx_slideshow.php'); ?>
          <?php } ?>
          <!-- end edit for ZX Slideshow --> 
        </div>
      </div>
  <?php } ?>
    <div class="container">
    <div class="row">
      <div class="main-col 
	  <?php if ((COLUMN_LEFT_STATUS == 1 && $body_id !== 'productinfo') || (COLUMN_RIGHT_STATUS == 1 && $body_id !== 'productinfo')) { ?>col-sm-9 <?php }?>
	  <?php if (COLUMN_LEFT_STATUS == 1 ) {?> left_column<?php }?><?php if (COLUMN_RIGHT_STATUS == 1 ) {?> right_column<?php }?>
      <?php if ((COLUMN_LEFT_STATUS == 0 && $body_id !== 'productinfo') || (COLUMN_RIGHT_STATUS == 0 && $body_id !== 'productinfo')) { ?>col-sm-12 <?php }?>">
        <div class="banners1">
          <div class="row">
            <?php if($this_is_home_page) { ?>
              <?php $new_banner_search = zen_build_banners_group(SHOW_BANNERS_GROUP_SET3);
    
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
                echo '<div class="col-sm-6 col-xs-12"><div data-match-height="banner1" class="item item_'.$banner_cnt.'">'.tm_zen_display_banner('static', $banners_all->fields['banners_id']).'</div></div>';
            // add spacing between banners
                if ($banner_cnt < $banners_all->RecordCount()) {
                  
                }
                $banners_all->MoveNext();
              }
              }
            ?>
          </div>
        </div>
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
                <div class="clearfix"></div>
        
        <!--bof-custom block display-->
      <?php require($template->get_template_dir('tpl_customblock.php',DIR_WS_TEMPLATE, $current_page_base,'common'). '/tpl_customblock.php');?> 
        <!--eof-custom block display--> 
      </div>
   
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
      <div class="copyright">
        <div class="container">
          <div class="row">
           <div class="col-xs-12">
            <!-- ========== COPYRIGHT ========== -->
              <p><?php echo FOOTER_TEXT_BODY; ?> &nbsp;| &nbsp;<br><a href="<?php echo zen_href_link(FILENAME_PRIVACY)?>"><?php echo BOX_INFORMATION_PRIVACY?></a> &nbsp;| &nbsp;<a href="<?php echo zen_href_link(FILENAME_PAGE_2)?>">Template settings</a>
                <?php
                    if (SHOW_FOOTER_IP == '1') {
                ?>
                        <div id="siteinfoIP"><?php echo TEXT_YOUR_IP_ADDRESS . '  ' . $_SERVER['REMOTE_ADDR']; ?></div>
                <?php
                    }
                ?>
              </p>
            <!-- =============================== -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </footer>
<!-- ============================ --> 
</div>
<!-- ========================================= -->

<!-- begin olark code --> 
<script type="text/javascript" data-cfasync="false">// <![CDATA[
  /*<![CDATA[*/window.olark||(function(c){var f=window,d=document,l=f.location.protocol=="https:"?"https:":"http:",z=c.name,r="load";var nt=function(){
  f[z]=function(){
  (a.s=a.s||[]).push(arguments)};var a=f[z]._={
  },q=c.methods.length;while(q--){(function(n){f[z][n]=function(){
  f[z]("call",n,arguments)}})(c.methods[q])}a.l=c.loader;a.i=nt;a.p={
  0:+new Date};a.P=function(u){
  a.p[u]=new Date-a.p[0]};function s(){
  a.P(r);f[z](r)}f.addEventListener?f.addEventListener(r,s,false):f.attachEvent("on"+r,s);var ld=function(){function p(hd){
  hd="head";return["<",hd,"></",hd,"><",i,' onl' + 'oad="var d=',g,";d.getElementsByTagName('head')[0].",j,"(d.",h,"('script')).",k,"='",l,"//",a.l,"'",'"',"></",i,">"].join("")}var i="body",m=d[i];if(!m){
  return setTimeout(ld,100)}a.P(1);var j="appendChild",h="createElement",k="src",n=d[h]("div"),v=n[j](d[h](z)),b=d[h]("iframe"),g="document",e="domain",o;n.style.display="none";m.insertBefore(n,m.firstChild).id=z;b.frameBorder="0";b.id=z+"-loader";if(/MSIE[ ]+6/.test(navigator.userAgent)){
  b.src="javascript:false"}b.allowTransparency="true";v[j](b);try{
  b.contentWindow[g].open()}catch(w){
  c[e]=d[e];o="javascript:var d="+g+".open();d.domain='"+d.domain+"';";b[k]=o+"void(0);"}try{
  var t=b.contentWindow[g];t.write(p());t.close()}catch(x){
  b[k]=o+'d.write("'+p().replace(/"/g,String.fromCharCode(92)+'"')+'");d.close();'}a.P(2)};ld()};nt()})({
  loader: "static.olark.com/jsclient/loader0.js",name:"olark",methods:["configure","extend","declare","identify"]});
  /* custom configuration goes here (www.olark.com/documentation) */
  olark.identify('7830-582-10-3714');/*]]>*/
  // ]]></script>
<noscript>
<a href="https://www.olark.com/site/7830-582-10-3714/contact" title="Contact us" target="_blank">Questions? Feedback?</a> powered by <a href="http://www.olark.com?welcome" title="Olark live chat software">Olark live chat software</a>
</noscript>
<!-- end olark code -->

</body>
