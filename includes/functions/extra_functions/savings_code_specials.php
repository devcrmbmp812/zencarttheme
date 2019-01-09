<?php
function savings_code_special($special_price, $product_id) {
   global $db; 
   $orig_special_price = $special_price;

   if (!isset($_SESSION['savings_code']) || empty($_SESSION['savings_code'])) {
      return $orig_special_price; 
   }
   
   // See if there's a savings code special price 
    $is_user_special = false; 
    $user_specials = $db->Execute("select specials_new_products_price from " . TABLE_SAVINGS_CODE_SPECIALS . " where products_id = '" . (int)$product_id . "' and status='1' AND savings_code = '". $_SESSION['savings_code'] . "'");
    if ($user_specials->RecordCount() > 0) {
       $user_special_price = $user_specials->fields['specials_new_products_price'];
       $is_user_special = true; 
    }
    
    if ($is_group_special && !$is_user_special) {
       return $group_special_price;  
    } else if ($is_user_special) {
       return $user_special_price;  
    } else {
      return $orig_special_price; 
    }
}
