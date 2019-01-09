<?php
/**

 * @package admin

 * @copyright Copyright 2003-2016 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce

 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0

 * @version $Id: Author: DrByte  Fri Feb 26 20:52:53 2016 -0500 Modified in v1.5.5 $
 */

  require('includes/application_top.php');

  require(DIR_WS_MODULES . 'prod_cat_header_code.php');

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  $zco_notifier->notify('NOTIFY_BEGIN_ADMIN_PRODUCTS', $action);

  if (zen_not_null($action)) {

    switch ($action) {

      case 'setflag':

        if ( ($_GET['flag'] == '0') || ($_GET['flag'] == '1') ) {

          if (isset($_GET['pID'])) {

            zen_set_product_status($_GET['pID'], $_GET['flag']);

          }

        }

        zen_redirect(zen_href_link(FILENAME_CATEGORIES, 'cPath=' . $_GET['cPath'] . '&pID=' . $_GET['pID'] . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '') . ((isset($_GET['search']) && !empty($_GET['search'])) ? '&search=' . $_GET['search'] : '')));

        break;



      case 'delete_product_confirm':

      $delete_linked = 'true';

      if ($_POST['delete_linked'] == 'delete_linked_no') {

        $delete_linked = 'false';

      } else {

        $delete_linked = 'true';

      }

      $product_type = zen_get_products_type($_POST['products_id']);

        if (file_exists(DIR_WS_MODULES . $zc_products->get_handler($product_type) . '/delete_product_confirm.php')) {

          require(DIR_WS_MODULES . $zc_products->get_handler($product_type) . '/delete_product_confirm.php');

         } else {

          require(DIR_WS_MODULES . 'delete_product_confirm.php');

         }

        break;

      case 'move_product_confirm':

        if (file_exists(DIR_WS_MODULES . $zc_products->get_handler($product_type) . '/move_product_confirm.php')) {

          require(DIR_WS_MODULES . $zc_products->get_handler($product_type) . '/move_product_confirm.php');

         } else {

          require(DIR_WS_MODULES . 'move_product_confirm.php');

         }

        break;

      case 'insert_product_meta_tags':

      case 'update_product_meta_tags':

        if (file_exists(DIR_WS_MODULES . $zc_products->get_handler($product_type) . '/update_product_meta_tags.php')) {

          require(DIR_WS_MODULES . $zc_products->get_handler($product_type) . '/update_product_meta_tags.php');

         } else {

          require(DIR_WS_MODULES . 'update_product_meta_tags.php');

         }

        break;

      case 'insert_product':

      case 'update_product':

        if (file_exists(DIR_WS_MODULES . $zc_products->get_handler($product_type) . '/update_product.php')) {

          require(DIR_WS_MODULES . $zc_products->get_handler($product_type) . '/update_product.php');

         } else {

          require(DIR_WS_MODULES . 'update_product.php');

         }

        break;

      case 'copy_to_confirm':

        if (file_exists(DIR_WS_MODULES . $zc_products->get_handler($product_type) . '/copy_to_confirm.php')) {

          require(DIR_WS_MODULES . $zc_products->get_handler($product_type) . '/copy_to_confirm.php');

         } else {

          require(DIR_WS_MODULES . 'copy_to_confirm.php');

         }

        break;

      case 'new_product_preview_meta_tags':

        if (file_exists(DIR_WS_MODULES . $zc_products->get_handler($product_type) . '/new_product_preview_meta_tags.php')) {

          require(DIR_WS_MODULES . $zc_products->get_handler($product_type) . '/new_product_preview_meta_tags.php');

         } else {

          require(DIR_WS_MODULES . 'new_product_preview_meta_tags.php');

         }

        break;

      case 'new_product_preview':

        if (file_exists(DIR_WS_MODULES . $zc_products->get_handler($product_type) . '/new_product_preview.php')) {

          require(DIR_WS_MODULES . $zc_products->get_handler($product_type) . '/new_product_preview.php');

         } else {

          require(DIR_WS_MODULES . 'new_product_preview.php');

         }

        break;



    }

  }



// check if the catalog image directory exists

  if (is_dir(DIR_FS_CATALOG_IMAGES)) {

    if (!is_writeable(DIR_FS_CATALOG_IMAGES)) $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE, 'error');

  } else {

    $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST, 'error');

  }

?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">

<html <?php echo HTML_PARAMS; ?>>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">

<title><?php echo TITLE; ?></title>

<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">

<link rel="stylesheet" type="text/css" href="includes/cssjsmenuhover.css" media="all" id="hoverJS">

<script language="javascript" src="includes/menu.js"></script>
<style type="text/css" title="currentStyle">
	@import "media/css/page.css";
	@import "media/css/table.css";
</style>
<script type="text/javascript" charset="utf-8" src="media/js/jquery.js"></script>
<script type="text/javascript" charset="utf-8" src="media/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf-8">
var jq = jQuery.noConflict();
	jq(document).ready( function () {
		jq('#example').dataTable();
		//new AutoFill( oTable );				
	} );
	
</script>


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

<?php if ($action != 'new_product_meta_tags' && $editor_handler != '') include ($editor_handler); ?>
</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="init()">

<div id="spiffycalendar" class="text"></div>

<!-- header //-->

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<!-- header_eof //-->
<!-- body //-->
<?php 
if(isset($_POST['product'])){
	$products = implode(",",$_POST['products']);
	$location = $_POST['slider_option'];
	$db->Execute("update zen_slider set selected_products = '$products' where slider_value = '$location'");
	header("Location: slider.php?location=".$location);
}

$Slider_val = @$_GET['location']; 
$sql_selected = $db->Execute("select * from zen_slider where slider_value = '$Slider_val'");
$selected_pro = explode(',',$sql_selected->fields['selected_products']);
	

$q = $db->Execute("SELECT * FROM zen_products p LEFT JOIN zen_products_description pd ON p.products_id = pd.products_id left join zen_categories_description on p.master_categories_id =  zen_categories_description.categories_id ");
$items = array();
while(!$q->EOF) {
    $items[] = $q->fields;
    $q->MoveNext();
}

?>

<form action='#' method='post' >
<table style='float:right;'>
	<tr>			
		<td colspan="6" >
			<select name='slider_option' class='change_slider'>
				<option value=''>Select Slider Option</option>
				<?php $sql_theme = $db->Execute('select * from zen_slider');
					while(!$sql_theme->EOF) { $slider = $sql_theme->fields;	$sql_theme->MoveNext(); ?>
					<option value='<?php echo $slider['slider_value']; ?>' <?php if($slider['slider_value'] == $_GET['location']){ echo "selected"; } ?> ><?php echo $slider['slider_name']; ?></option>
				<?php } ?>
			</select>
		</td>	
		<td colspan="6" ><input type='submit' name='product' value='Go' /></td>				
	</tr>
</table>
<table id="example" class="table table-striped table-bordered">
<thead>
	<tr>
		<td>S. No</td>
		<td>Product Name </td>
		<td>Master Category</td>
		<td>Product Price</td>
		<td>Products Quantity</td>
		<td>Action</td>
	</tr>
 </thead>
   <tbody> 
	<?php $i= 1; foreach($items as $item) { ?>
		<tr>			
			<td><?php echo $i; ?></td>
			<td><?php echo $item['products_name']; ?></td>
			<td><?php echo $item['categories_name']; ?></td>
			<td><?php echo $item['products_price']; ?></td>
			<td><?php echo $item['products_quantity']; ?></td>
			<td>
				<?php $found = 0;
				foreach($selected_pro as $product){
					if($product == $item['products_id']){
						$found = 1;
						break;
					}
				}
				?>
				<input type='checkbox' name='products[]' <?php if($found == 1){ echo "checked"; } ?> value='<?php echo $item['products_id']; ?>' />
			</td>				
		</tr>
	<?php $i++; } ?>		
  </tbody> 
</table>

</form>
<!-- body_eof //-->



<!-- footer //-->
<script>
$(document).ready(function(){	
	$('.change_slider').on('change',function(){
		var slected_slider = $(this).val();
		window.location.replace("slider.php?location="+slected_slider);
	});
});
</script>
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>

<!-- footer_eof //-->

<br />

</body>

<script language="javascript" src="includes/general.js"></script>

</html>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

