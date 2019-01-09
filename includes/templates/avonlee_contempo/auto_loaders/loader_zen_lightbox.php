<?php


if (ZEN_LIGHTBOX_STATUS == 'true') {
$loaders[] = array(
    'conditions' => array(
        'pages' => array('document_general_info','document_product_info','page','product_free_shipping_info','product_info','product_music_info','product_reviews','product_reviews_write') 
    ),
    'jscript_files' => array(
        '//code.jquery.com/jquery-1.11.3.min.js' => 1,
        'jquery/jquery_zen_lightbox.php' => 3, // this is set to 3 incase a jquery migrate file is needed
    ),
    'css_files' => array(
        'zen_lightbox.css' => 1,)
);
}
