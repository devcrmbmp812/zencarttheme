<?php
  $savings_code = strtoupper(zen_db_prepare_input($_GET['keyword']));
  $check = $db->Execute("SELECT count(*) as count FROM " . TABLE_SAVINGS_CODE_SPECIALS . " WHERE  status='1' AND savings_code = '". $savings_code . "'");
  if ($check->EOF || $check->fields['count'] == 0) {
    $messageStack->add_session('shopping_cart', 'No such savings code', 'warning');
  } else { 
    $_SESSION['savings_code'] = $savings_code; 
    $messageStack->add_session('shopping_cart', 'Savings code is now ' . zen_output_string_protected($savings_code), 'success');
  }
  zen_redirect(zen_href_link(FILENAME_SHOPPING_CART));
