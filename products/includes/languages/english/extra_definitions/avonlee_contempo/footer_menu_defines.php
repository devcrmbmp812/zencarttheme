<?php
/**
 * Footer Menu Definitions
 *
 * @package templateSystem
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V3.0
 * @version $Id: footer_menu_deines.php 1.0 5/9/2009 Clyde Jones $
 */

/*BOF Menu Column 1 link Definitions*/
Define('TITLE_ONE', '<li class="menuTitle">Quick Links</li>');
Define('HOME', '<li><a href="' . HTTP_SERVER . DIR_WS_CATALOG . '">' . HEADER_TITLE_CATALOG . '</a></li>');
Define('FEATURED','<li><a href="' . zen_href_link(FILENAME_FEATURED_PRODUCTS) . '">' .  TABLE_HEADING_FEATURED_PRODUCTS .  '</a></li>');
Define('ALLPRODUCTS', '<li><a href="' . zen_href_link(FILENAME_PRODUCTS_ALL) . '">' .CATEGORIES_BOX_HEADING_PRODUCTS_ALL . '</a></li>');
/*EOF Menu Column 1 link Definitions*/

/*OF Menu Column 2 link Definitions*/
Define('TITLE_TWO', '<li class="menuTitle">Information</li>');
//Define('ABOUT', '<li><a href="' . zen_href_link(FILENAME_ABOUT_US) . '">' . BOX_INFORMATION_ABOUT_US . '</a></li>');
Define('ABOUT', '<li><a href="http://www.boscoyostudio.com/products/index.php?main_page=page_3">' . BOX_INFORMATION_ABOUT_US . '</a></li>');
Define('NEWPRODUCTS', '<li><a href="' . zen_href_link(FILENAME_PRODUCTS_NEW) . '">' . BOX_HEADING_WHATS_NEW . '</a></li>');
Define('SITEMAP', '<li><a href="' . zen_href_link(FILENAME_SITE_MAP) . '">' . BOX_INFORMATION_SITE_MAP . '</a></li>');
/*EOF Menu Column 2 link Definitions*/

/*BOF Menu Column 3 link Definitions*/
Define('TITLE_THREE', '<li class="menuTitle">Customer Service</li>');
Define('CONTACT','<li><a href="' . zen_href_link(FILENAME_CONTACT_US) . '">' . BOX_INFORMATION_CONTACT . '</a></li>');
Define('SHIPPING', '<li><a href="' . zen_href_link(FILENAME_SHIPPING) . '">' . BOX_INFORMATION_SHIPPING . '</a></li>');
Define('ACCOUNT', '<li><a href="' . zen_href_link(FILENAME_ACCOUNT, '', 'SSL') .'">' . HEADER_TITLE_MY_ACCOUNT . '</a></li>');
/*EOF Menu Column 3 link Definitions*/

/*BOF Menu Column 4 link Definitions*/
Define('TITLE_FOUR', '<li class="menuTitle">Other</li>');
Define('FACEBOOK', '<li><a href="http://www.facebook.com/BOSCOYO-LINK" target="_blank">Facebook</a></li>');
Define('GALLERY', '<li><a href="http://www.boscoyostudio.com/gallery.html">Gallery</a></li>'); 
/*The actual links are determined by "footer links" set in EZ-Pages
*EOF Menu Column 4 link Definitions
*/

/*BOF Footer Menu Definitions*/
Define('QUICKLINKS', '<dd class="first">
<ul>' . TITLE_ONE . HOME . FEATURED . ALLPRODUCTS . '</ul></dd>');
Define('INFORMATION', '<dd class="second">
<ul>' . TITLE_TWO . ABOUT . NEWPRODUCTS . SITEMAP . '</ul></dd>');
Define('CUSTOMER_SERVICE', '<dd class="third">
<ul>' . TITLE_THREE . CONTACT . SHIPPING . ACCOUNT . '</ul></dd>');
Define('OTHER', '<dd><ul>' . TITLE_FOUR . FACEBOOK . GALLERY);
Define('IMPORTANT_END', '</ul></dd>');
/*EOF Footer Menu Definitions*/

define('TWITTER_ICON', 'twitter.png');
define('FACEBOOK_ICON','facebook.png');
Define('YOUTUBE_ICON', 'youtube.png');
Define('PINTEREST_ICON', 'pinterest.png');
Define('GOOGLE_ICON', 'google.png');
Define('BLOG_ICON', 'blog.png');
 
/*bof bottom footer urls*/
//Define('FACEBOOK','http://www.facebook.com/BOSCOYOFBLINK');
//Define('TWITTER', 'http://www.twitter.com/BOSCOYOFBLINK');
//Define('YOUTUBE', 'http://www.youtube.com/user/ZenCartEasyHelp');
//Define('PINTEREST', 'http://www.pinterest.com/picaflorazul');
//Define('GOOGLE', 'https://plus.google.com/113609090217058276980/posts');
//Define('BLOG', 'http://www.picaflor-azul.com/blog');

//EOF
