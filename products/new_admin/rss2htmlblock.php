<?php
/**
 * RSS Parser for Export Shipping Information Module "Tips" Feed
 *
 * @package Export Shipping Information
 * @copyright Copyright 2007, Eric Leuenberger http://www.zencartoptimization.com
 * @version $Id: shipping_export.php, v 1.2.3 09.26.2008 10:52 Eric Leuenberger econcepts@zencartoptimization.com$
 */
//define('MAGPIE_INPUT_ENCODING', 'UTF-8');
//define('MAGPIE_OUTPUT_ENCODING', 'UTF-8');
//Change the path to your MagpieRSS directory
require_once('magpierss/rss_fetch.inc');
//This is the function wrapper for the functionality.
//$url is an RSS feed URL string
function display_feed($url){
//Use MagpieRSS to grab the content of the feed
$rss = fetch_rss($url);
//If you're curious what it looks like, dump out the contents
//print_r($rss);
//We pull out the channel information.
//This could be used to add a title and link to the feed.
//If you were offering the feed to the public, you
//probably want to do that.
$channel = array_slice($rss->channel,0);
//The items are what we'll display
$items = array_slice($rss->items,0, 3);
//Loop through the items
foreach ($items as $item){
//Grab the useful bits of the item
$id = $item['guid'];
$link = $item['link'];
$subject = $item['title'];
$content = $item['content']['encoded'];
$description = $item['description'];
//spit out the actual HTML.
//The sample shows the description with a link to read the
//real item.
print("<style>
.post {
font-family: verdana;
font-size: 10px;
width: 450px;
margin-bottom: 20px;
}
.post-title {
font-family: verdana;
font-size: 11px;
font-weight: bold;
}
</style>");
print("<div class='post'>");
print("<div class='post-title'>$subject</div>");
print("<div class='content'>$description <a href='$link' target='_blank'><strong><u>Read Full Article</u></strong></a></div>");
print("</div>");
}
}
//This is the mini web service portion
//Check for a feedurl on the request
if($_GET['feedurl']){
//Show the feed directly
display_feed($_GET['feedurl']);
}
?>
