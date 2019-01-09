<?php
require("includes/application_top.php");
?>
<?php
if (isset($_GET['action'])) {
   switch ($_GET['action']) {
     case 'deletecode' : 
       $code = urldecode(zen_db_input($_GET['code'])); 
       $db->Execute("DELETE FROM " . TABLE_SAVINGS_CODE_SPECIALS . " WHERE savings_code = '" . $code . "'");
       zen_redirect(zen_href_link(FILENAME_SAVINGS_CODE_SPECIALS));
       return; 

     case 'downloadcode' : 
     case 'basecsv' : 
       $code = urldecode(zen_db_input($_GET['code'])); 
       header("Content-type: text/csv");
       header("Content-Disposition: attachment; filename=codes.csv");
       header("Pragma: no-cache");
       header("Expires: 0");
       $fp = fopen('php://output', 'w');
       if ($_GET['action'] == "basecsv")  {
          $codes_query = $db->Execute("SELECT p.products_id, products_name, p.products_price FROM " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd WHERE p.products_id = pd.products_id");
       } else { 
          $codes_query = $db->Execute("SELECT products_id,specials_new_products_price, savings_code FROM " . TABLE_SAVINGS_CODE_SPECIALS . " WHERE savings_code = '" . $code . "'"); 
       }
       $line = array();
       $line[] = "products_id"; 
       $line[] = "specials_new_products_price"; 
       $line[] = "savings_code"; 
       if ($_GET['action'] == "basecsv")  {
         $line[] = "products_name"; 
       }
       fputcsv($fp, $line);
       while (!$codes_query->EOF) { 
          $line = array();
          $line[] = $codes_query->fields['products_id']; 
          if ($_GET['action'] == "downloadcode")  {
            $line[] = $codes_query->fields['specials_new_products_price']; 
            $line[] = $codes_query->fields['savings_code']; 
          } else {
            $line[] = number_format($codes_query->fields['products_price'],2); // base price
            $line[] = "YOUR_CODE"; // savings_code"; 
            $line[] = $codes_query->fields['products_name']; 
          }
          fputcsv($fp, $line);
          $codes_query->MoveNext(); 
       }
       fclose($fp);
       return; 
       break; 

   }
}
?>
    <!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
    <html <?php echo HTML_PARAMS; ?>>
    <head>
<script>
function deleteCode(code_name) {
    if (confirm(decodeURIComponent(code_name) + ": Are you sure you want to delete all codes?")) {
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
    ?>
    <p class="main">
    <center><h1><?php echo PAGE_TITLE; ?></h1></center>
    </p>
    <h3> <?php echo CURRENT_CODES; ?> </h3>
<?php
 $codes_query = $db->Execute("SELECT DISTINCT(savings_code) AS savings_code FROM " . TABLE_SAVINGS_CODE_SPECIALS); 
?>
    <table border="1" cellpadding="5">
    <tr>
    <th><?php echo CODE_NAME; ?></th>
    <th><?php echo CODE_COUNT; ?></th>
    <th><?php echo CODE_EDIT; ?></th>
    <th><?php echo CODE_DOWNLOAD; ?></th>
    <th><?php echo CODE_DELETE; ?></th>
    </tr>
<?php 
   while (!$codes_query->EOF) {
       $code_name = $codes_query->fields['savings_code']; 
       $code_count_query = $db->Execute("SELECT count(*) AS count FROM " . TABLE_SAVINGS_CODE_SPECIALS . " WHERE savings_code = '" . $code_name . "'"); 
       $code_count = $code_count_query->fields['count']; 
       echo "<tr><td>" . $code_name . "</td><td>" . $code_count . "</td>"; 
       echo "<td>" . '<a href="' . zen_href_link(FILENAME_EDIT_SAVINGS_CODE_SPECIALS, "action=display&code=". urlencode($code_name))  . '">' . CODE_EDIT. "</td>"; // Edit
       echo "<td>" . '<a href="' . zen_href_link(FILENAME_SAVINGS_CODE_SPECIALS, "action=downloadcode&code=". urlencode($code_name))  . '">' . CODE_DOWNLOAD . "</td>"; // Download
       echo "<td>" . '<a href="' . zen_href_link(FILENAME_SAVINGS_CODE_SPECIALS, "action=deletecode&code=". urlencode($code_name))  . '" onclick=\'return deleteCode("' . rawurlencode($code_name) . '")\'>' . CODE_DELETE. "</td>"; 
       echo "</tr>"; 
       $codes_query->MoveNext(); 
   }
?>
    </table>

    <br/><br/>
    <h3> <?php echo LOAD_NEW_CODES; ?> </h3>
<a href="<?php echo zen_href_link(FILENAME_UPLOAD_SAVINGS_CODE_SPECIALS); ?>"><?php echo BOX_TOOLS_UPLOAD_SAVINGS_CODE_SPECIALS; ?></a>
    <br/><br/>
    
    <br/><br/>
    <h3> <?php echo CREATE_BASE_CSV; ?> </h3>
    <a href="<?php echo zen_href_link(FILENAME_SAVINGS_CODE_SPECIALS, "action=basecsv")  . '">' . CREATE_BASE_CSV . "</a>"; ?>


    <br/><br/>
    <?php
    require(DIR_WS_INCLUDES . 'footer.php');
    ?>
    </body>
    </html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
