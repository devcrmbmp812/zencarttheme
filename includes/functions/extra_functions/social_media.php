<?php

function social_media($products_id, $products_name, $products_img) {

  $media_icons = '';
if (TWITTER_STATUS == 1) {
  echo '<a href="https://twitter.com/share" class="twitter-share-button fleft">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>';
}
if (GOOGLE_STATUS == 1) {
  echo '<div class="fleft"><div class="g-plusone" data-size="medium"></div></div>'; 
}
if (FACEBOOK_STATUS == 1) {
	echo '<div class="fleft"><fb:like send="false" layout="button_count" width="150" show_faces="false"></fb:like></div>';
}
if (PINTEREST_STATUS == 1) {
	echo '<div class="pint">
		<a href="http://pinterest.com/pin/create/button/?url='.  urlencode(zen_href_link(zen_get_info_page($_GET['products_id']), 'cPath=' . $_GET['cPath'] . '&products_id=' . $_GET['products_id']) ).'&media=' .  urlencode(HTTP_SERVER . DIR_WS_CATALOG . $products_img) . '&description=' .  rawurlencode($products_name) . '" class="pin-it-button" count-layout="horizontal"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>
	</div>';
}

  return $media_icons;
}
?>