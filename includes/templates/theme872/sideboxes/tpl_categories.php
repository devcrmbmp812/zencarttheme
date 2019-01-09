<?php 
/** 
 * Side Box Template 
 * 
 * @package templateSystem 
 * @copyright Copyright 2003-2006 Zen Cart Development Team 
 * @copyright Portions Copyright 2003 osCommerce 
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0 
 * @version $Id: tpl_categories.php 4162 2006-08-17 03:55:02Z ajeh $ 
 */ 
  $content = ""; 
   
  $content .= '<div id="' . str_replace('_', '-', $box_id . 'Content') . '" class="sideBoxContent">' . "\n"; 
  $content .= '<ul class="list-group submenu" style="list-style-type:none">' . "\n"; 
   
  $li_class=""; 
  $levels = array(); 
   
  $j = 0; 
  foreach($main_category_tree->tree as $key => $value){ 
      for ($i=0;$i<sizeof($box_categories_array);$i++){ 
        if($box_categories_array[$i]['path'] == 'cPath=' . $value['path']){ 
            $levels[$i] = $value['level']; 
        } 
    } 
    $j++; 
  } 
 // var_dump($box_categories_array);
  for ($i=0;$i<sizeof($box_categories_array);$i++) { 

       
    switch(true) { 
// to make a specific category stand out define a new class in the stylesheet example: A.category-holiday 
// uncomment the select below and set the cPath=3 to the cPath= your_categories_id 
// many variations of this can be done 
//      case ($box_categories_array[$i]['path'] == 'cPath=3'): 
//        $new_style = 'category-holiday'; 
//        break; 

         
         
         
      case ($box_categories_array[$i]['top'] == 'true'): 
              if($i == 0) 
            { 
             
             
        $new_style = 'category-top_un';

        $li_class='<li>'; 
        break; 
                 
            } 
             
            else{ 

       
        $new_style = 'category-top'; 
        $li_class='<li>'; 
        break; 
        } 
      case ($box_categories_array[$i]['has_sub_cat']): 
        $new_style = 'category-subs'; 
        $li_class='<li>'; 
        break; 
      default: 
        $new_style = 'category-products'; 
        $li_class='<li>'; 
        break; 
         
      } 
       
     if (zen_get_product_types_to_category($box_categories_array[$i]['path']) == 3 or ($box_categories_array[$i]['top'] != 'true' and SHOW_CATEGORIES_SUBCATEGORIES_ALWAYS != 1)) { 
        // skip if this is for the document box (==3) 
      } else { 
	  if($box_categories_array[$i]['top'] == 'true'){
      $content .= '<li>'; 
      
	  if ($box_categories_array[$i]['has_sub_cat']) { 
	  
      $content .= '<span class="grower CLOSE"> </span><a class="' . $new_style . '" href="' . zen_href_link(FILENAME_DEFAULT, $box_categories_array[$i]['path']) . '">
	  <span class="category-subs-parent-selected">' . $box_categories_array[$i]['name'] . '</span></a><ul class="menu_sub">
	  ';
	  foreach($sub_categories as $sub){
		if($sub['parent'] == $i+1){
		$content .= '<li><a href="'. zen_href_link(FILENAME_DEFAULT, 'cPath='.$sub['parent'].'_'. $sub['path']) . '">'.$sub['name'].'</a></li>';
		}
		elseif($sub['parent'] > $i+1) {
		$content .= '<li><a href="'. zen_href_link(FILENAME_DEFAULT, 'cPath='.$sub['parent'].'_'. $sub['path']) . '">'.$sub['name'].'</a></li>';
		}
	  }
	  $content .= '</ul>'; 
	  }
	  else{
	  $content .= '<a class="' . $new_style . '" href="' . zen_href_link(FILENAME_DEFAULT, $box_categories_array[$i]['path']) . '">'; 
	  }
	  
      if ($box_categories_array[$i]['current']) { 
        if ($box_categories_array[$i]['has_sub_cat']) { 
          //$content .= '<span class="category-subs-parent-selected">' . $box_categories_array[$i]['name'] . '</span>'; 
        } else { 
          $content .= '<span class="category-subs-selected">' . $box_categories_array[$i]['name'] . '</span>'; 
        } 
      } else { 
	  	if ($box_categories_array[$i]['has_sub_cat']) { 
         // $content .= '<span class="category-subs-parent">' . $box_categories_array[$i]['name'] . '</span>'; 
        } else
        $content .= $box_categories_array[$i]['name']; 
      } 

      
      $content .= '</a>'; 

      if (SHOW_COUNTS == 'true') { 
        if ((CATEGORIES_COUNT_ZERO == '1' and $box_categories_array[$i]['count'] == 0) or $box_categories_array[$i]['count'] >= 1) { 
          $content .= '<span class="count" title="products in category">' .CATEGORIES_COUNT_PREFIX . $box_categories_array[$i]['count'] . CATEGORIES_COUNT_SUFFIX. '</span>'; 
        } 
      } 
       
      $content .= '</li>'; 
	  }
    } 
  } 
      $content .= '</ul>' . "\n"; 

  if (SHOW_CATEGORIES_BOX_SPECIALS == 'true' or SHOW_CATEGORIES_BOX_PRODUCTS_NEW == 'true' or SHOW_CATEGORIES_BOX_FEATURED_PRODUCTS == 'true' or SHOW_CATEGORIES_BOX_PRODUCTS_ALL == 'true') { 

// display a separator between categories and links 
    if (SHOW_CATEGORIES_SEPARATOR_LINK == '1') { 
      $content .= '<div id="catBoxDivider"></div>' . "\n"; 
    } 
     
     $content .= '<div class="box_body_2"><ul>';  
     
     
    if (SHOW_CATEGORIES_BOX_SPECIALS == 'true') { 
      $show_this = $db->Execute("select s.products_id from " . TABLE_SPECIALS . " s where s.status= 1 limit 1"); 
      if ($show_this->RecordCount() > 0) { 
        $content .= '<li><a class="category-links" href="' . zen_href_link(FILENAME_SPECIALS) . '">' . CATEGORIES_BOX_HEADING_SPECIALS . '</a></li>' . "\n"; 
      } 
    } 
     
    if (SHOW_CATEGORIES_BOX_PRODUCTS_NEW == 'true') { 
      // display limits 
//      $display_limit = zen_get_products_new_timelimit(); 
      $display_limit = zen_get_new_date_range(); 

      $show_this = $db->Execute("select p.products_id 
                                 from " . TABLE_PRODUCTS . " p 
                                 where p.products_status = 1 " . $display_limit . " limit 1"); 
      if ($show_this->RecordCount() > 0) { 
        $content .= '<li><a class="category-links" href="' . zen_href_link(FILENAME_PRODUCTS_NEW) . '">' . CATEGORIES_BOX_HEADING_WHATS_NEW . '</a></li>' . "\n"; 
      } 
    } 
     
    if (SHOW_CATEGORIES_BOX_FEATURED_PRODUCTS == 'true') { 
      $show_this = $db->Execute("select products_id from " . TABLE_FEATURED . " where status= 1 limit 1"); 
      if ($show_this->RecordCount() > 0) { 
        $content .= '<li><a class="category-links" href="' . zen_href_link(FILENAME_FEATURED_PRODUCTS) . '">' . CATEGORIES_BOX_HEADING_FEATURED_PRODUCTS . '</a></li>' . "\n"; 
      } 
    } 
     
    if (SHOW_CATEGORIES_BOX_PRODUCTS_ALL == 'true') { 
      $content .= '<li><a class="category-links" href="' . zen_href_link(FILENAME_PRODUCTS_ALL) . '">' . CATEGORIES_BOX_HEADING_PRODUCTS_ALL . '</a></li>' . "\n"; 
    } 
     
    $content .= '</ul></div>';  
     
  } 
  $content .= '</div>'; 
?> 
