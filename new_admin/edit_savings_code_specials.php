<?php
require("includes/application_top.php");
?>
<?php
if (!isset($_GET['action'])) $_GET['action'] = 'display';
if (isset($_GET['action'])) {
   switch ($_GET['action']) {
     case 'deleteprice' : 
       $prid = zen_db_input($_GET['prid']); 
       $code = zen_db_input($_GET['code']); 
       $db->Execute("DELETE FROM " . TABLE_SAVINGS_CODE_SPECIALS . " WHERE savings_code = '" . urldecode($code) . "' AND products_id = " . (int)$prid);
       zen_redirect(zen_href_link(FILENAME_EDIT_SAVINGS_CODE_SPECIALS, "code=" . $code));
       return; 

      display: 
      default: 
       $_GET['action'] = 'display';
       $code = urldecode(zen_db_input($_GET['code'])); 
       $codes_query = $db->Execute("SELECT s.products_id,specials_new_products_price, savings_code, pd.products_name FROM " . TABLE_SAVINGS_CODE_SPECIALS . " s, " . TABLE_PRODUCTS_DESCRIPTION . " pd WHERE s.products_id = pd.products_id AND savings_code = '" . $code . "'"); 
       if ($codes_query->RecordCount() == 0) {
          zen_redirect(zen_href_link(FILENAME_SAVINGS_CODE_SPECIALS));
       }
   }
}
?>
    <!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
    <html <?php echo HTML_PARAMS; ?>>
    <head>
<script>
function deletePrice(products_id) {
    if (confirm("Product " + products_id + ": Are you sure you want to delete this price?")) {
       return true; 
    }
    return false;
}
</script>
        <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
        <meta name="robot" content="noindex, nofollow"/>
        <script language="JavaScript" src="includes/menu.js" type="text/JavaScript"></script>
        <link href="includes/stylesheet.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS"/>
        <script type="text/javascript">
            <!--
            function init() {
                cssjsmenu('navbar');
                if (document.getElementById) {
                    var kill = document.getElementById('hoverJS');
                    kill.disabled = true;
                }
            }
            // -->
        </script>
    </head>
    <body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0"
          bgcolor="#FFFFFF" onload="init()">
    <?php
    require(DIR_WS_INCLUDES . 'header.php');
  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
    ?>
    <p class="main">
    <center><h1><?php echo PAGE_TITLE; ?></h1></center>
    </p>
    <h3> <?php echo CURRENT_CODES; ?> </h3>
<br />
<?php echo CODE_NAME . ": " . $code; ?>
<br />
<?php echo '<a href="'. zen_href_link(FILENAME_SAVINGS_CODE_SPECIALS) . '">' . BACK_TO_ALL . "</a>"; ?>
<br /><br />
<?php
?>
    <table border="1" cellpadding="5">
    <tr>
    <th><?php echo PRODUCT_ID; ?></th>
    <th><?php echo PRICE; ?></th>
    <th><?php echo CODE_EDIT; ?></th>
    <th><?php echo CODE_DELETE; ?></th>
    <th><?php echo PRODUCT_NAME; ?></th>
    </tr>
<?php 
   while (!$codes_query->EOF) {
       $products_id= $codes_query->fields['products_id']; 
       $price = $currencies->format($codes_query->fields['specials_new_products_price']); 
       $products_name = $codes_query->fields['products_name']; 
       echo "<tr><td>" . $products_id. "</td><td>" . $price. "</td>"; 
       echo "<td>" . '<a href="' . zen_href_link(FILENAME_EDIT_SAVINGS_CODE_SPECIALS, "action=display&code=". urlencode($code))  . '">' . CODE_EDIT. "</td>"; // Edit
       echo "<td>" . '<a href="' . zen_href_link(FILENAME_EDIT_SAVINGS_CODE_SPECIALS, "action=deleteprice&prid=". $products_id . "&code=" . urlencode($code))  . '" onclick=\'return deletePrice(' . $products_id . ')\'>' . CODE_DELETE. "</td>"; 
       echo "<td>" . $products_name. "</td>"; 
       echo "</tr>"; 
       $codes_query->MoveNext(); 
   }
?>
    </table>

    <br/><br/>
    <?php
    require(DIR_WS_INCLUDES . 'footer.php');
    ?>
    </body>
    </html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
