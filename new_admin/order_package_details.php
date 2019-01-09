<?php
/**
 * @package admin
 * @copyright Copyright 2003-2014 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version GIT: $Id: Author: DrByte  Jun 30 2014 Modified in v1.5.4 $
 */
?>
<html>
<head>
<title><?php echo "AuctionInc Packaging Details"; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<link rel="stylesheet" type="text/css" media="print" href="includes/stylesheet_print.css">
<link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">
<script language="javascript" src="includes/menu.js"></script>
<script language="javascript" src="includes/general.js"></script>
<script type="text/javascript">
  <!--
  function init()
  {
    cssjsmenu('navbar');
    if (document.getElementById)
    {
      var kill = document.getElementById('hoverJS');
      kill.disabled = true;
    }
  }
  // -->
</script>
<script language="javascript" type="text/javascript"><!--
function couponpopupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=450,height=280,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
</head>
<body>
<?php require('includes/application_top.php'); ?>
<?php
  if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];
  } else {
    exit();
  }
?>
<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
<!-- body_text //-->
  <tr>
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
    
      <?php
        global $db;

        $result = $db->Execute("select * from orders where orders_id=" . $id);

        $data = $result->fields['actioninc_packages'];

        if(isset($data) && $data !== '') {
          echo $data;
        } else {
          echo "No Packaging Data is available for this order.";
        }
      ?>

    </td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

</body>
</html>

