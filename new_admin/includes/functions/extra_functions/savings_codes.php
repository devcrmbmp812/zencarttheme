<?php 
function savings_code_specials_process($id, $specials_price, $code, &$results)
{
   global $db;

   $id =  (int)$id; 
   $query_check = $db->Execute("SELECT products_id FROM " . TABLE_PRODUCTS. " WHERE products_id='$id'");
   if ($query_check->EOF) { 
      $results[] = "No such product: " . $id;
      return -1;
   }

   // if special exists, remove it.
   $query_check = $db->Execute("SELECT * FROM " . TABLE_SAVINGS_CODE_SPECIALS . " WHERE products_id='$id' AND savings_code = '" . $code . "'");
   if (!$query_check->EOF) { 
      $db->Execute("DELETE FROM " . TABLE_SAVINGS_CODE_SPECIALS . " where specials_id = " . (int)$query_check->fields['specials_id']); 
   }

   // add new special

   $db->Execute("insert into " . TABLE_SAVINGS_CODE_SPECIALS . "
                    (products_id, specials_new_products_price, savings_code, status)
                    values ('" . (int)$id . "',
                            '" . zen_db_input($specials_price) . "',
                            '" . zen_db_input($code) . "',
                            '1')");

   $results[] = "Success adding special for product " . $id;
}
