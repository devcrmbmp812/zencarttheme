<?php
/*
© Copyright 2007-2015 © webgility Inc. all rights reserved.
----------------------------------------
This file and the source code contained herein are the property of Webgility LLC
and are protected by United States copyright law. All usage is restricted as per
the terms & conditions of Webgility License Agreement. You may not alter or remove
any trademark, copyright or other notice from copies of the content.

The code contained herein may not be reproduced, copied, modified or redistributed in any form
without the express written consent by an officer of Webgility LLC.

File last updated: 02/14/2012
*/

//ini_set("display_errors","ON");
//error_reporting(E_ALL);
DEFINE('__ENCODE_RESPONSE', true); 
define('IS_ADMIN_FLAG',true);

#ZENCART COMPLIANCE - 1.5.5
/* function zen_parse_url($url, $element = 'array')
{
  // Read the various elements of the URL, to use in auto-detection of admin foldername (basically a simplified parse_url equivalent which automatically supports ports and uncommon TLDs)
  $t1 = array();
  // scheme
  $s1 = explode('://', $url);
  $t1['scheme'] = $s1[0];
  // host
  $s2 = explode('/', trim($s1[1], '/'));
  $t1['host'] = $s2[0];
  array_shift($s2);
  // path/uri
  $t1['path'] = implode('/', $s2);
  $p1 = ($t1['path'] != '') ? '/' . $t1['path'] : '';

  switch($element) {
    case 'path':
    case 'host':
    case 'scheme':
      return $t1[$element];
    case '/path':
      return $p1;
    case 'array':
    default:
      return $t1;
  }
} */
#END

if (file_exists('../includes/local/configure.php'))
 { 
 include('../includes/local/configure.php');
}else{ 
  require('../includes/configure.php');
}
#ZENCART COMPLIANCE - 1.5.5
if (file_exists('../includes/defined_paths.php'))
{ 
 require_once('../includes/defined_paths.php');
}
#END
require(DIR_FS_CATALOG . DIR_WS_INCLUDES .  'filenames.php');

if(file_exists("../".DIR_WS_FUNCTIONS . 'password_funcs.php')){
	
	require("../".DIR_WS_FUNCTIONS . 'password_funcs.php');
}else{
	require(DIR_FS_CATALOG . DIR_WS_FUNCTIONS . 'password_funcs.php');
}


if(file_exists("../".DIR_WS_FUNCTIONS . 'general.php')){
	
	require("../".DIR_WS_FUNCTIONS . 'general.php');
}

require("../".DIR_WS_FUNCTIONS . 'database.php');

require(DIR_FS_CATALOG . DIR_WS_INCLUDES . 'database_tables.php');
require("../".DIR_WS_FUNCTIONS . 'html_output.php');

require("../".DIR_WS_CLASSES . 'object_info.php');
require(DIR_FS_CATALOG . DIR_WS_CLASSES ."/class.base.php");

if(file_exists("../../".DIR_WS_CLASSES . 'class.zcPassword.php')){

require("../../".DIR_WS_CLASSES . 'class.zcPassword.php');
}

require(DIR_FS_CATALOG . DIR_WS_CLASSES ."/class.phpmailer.php");

if (!is_object($db)) 
{
  // Load queryFactory db classes
  require(DIR_FS_CATALOG . DIR_WS_CLASSES . 'db/' .DB_TYPE . '/query_factory.php');
  $db = new queryFactory();
  $db->connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE);
} 
require("../".DIR_WS_CLASSES . 'currencies.php');
$currencies = new currencies();
require(DIR_FS_CATALOG .DIR_WS_CLASSES . 'shopping_cart.php');

require(DIR_FS_CATALOG .DIR_WS_CLASSES . 'sniffer.php');
$sniffer =  new sniffer();
require(DIR_FS_CATALOG . DIR_WS_CLASSES . 'template_func.php');
$template = new template_func("../".DIR_WS_CATALOG_TEMPLATE);
$zco_notifier =  new base();

$_SESSION['language']="english";



 # TO STORE ALL THE CONGIURATION VARIABKES IN AN ARRAY 
$config_result = $db->Execute("SELECT configuration_key, configuration_value  FROM " . TABLE_CONFIGURATION);
$config = array();
while (!$config_result->EOF) 
{
	$config[$config_result->fields["configuration_key"]] = $config_result->fields["configuration_value"];
	$config_result->MoveNext();
}

DEFINE("STORE_NAME", substr(htmlspecialchars($config['STORE_NAME'], ENT_NOQUOTES), 0, 75));
DEFINE("STORE_NAME_ID", substr(htmlspecialchars($config['STORE_NAME'], ENT_NOQUOTES), 0, 75));

require_once('D.WgCommon.php'); 

chdir("../");
class zencart extends WgCommon
{
#*********************************************************************************
# 
# Zen-card stuff 
#
//$obj = @new order();


#########################################################################################
#
# registered function defination for latest Updated Date
#
function Last_updated_date($username,$password)
{ 
	$xmlResponse = new xml_doc();
    $xmlResponse->version='1.0';
	$xmlResponse->encoding='UTF-8';
	$root = $xmlResponse->createTag("RESPONSE", array('Version'=>'1.0'));
	#check for authorisation
	
	$status = auth_user($username,$password,$xmlResponse,$root);

	if($status!==0)
	{
	  return $status;
	}
	$pMethodNodes = $xmlResponse->createTag("UpdateDate", array(), '', $root);
	$xmlResponse->createTag("StatusCode", array(), "0", $pMethodNodes, __ENCODE_RESPONSE);
  	$xmlResponse->createTag("StatusMessage", array(), "All Ok", $pMethodNodes, __ENCODE_RESPONSE);
	
	//$date = date("m-d-Y");
	$date = date("11-18-2010");

	$xmlResponse->createTag('LatestUpdatedDate',  array(), $date, $pMethodNodes, __ENCODE_RESPONSE);
		
	return $xmlResponse->generate();
}
#
# Update Orders shipping status method
# Will update Order Notes and tracking number of  order
# Input parameter Username,Password, array (OrderID,ShippedOn,ShippedVia,ServiceUsed,TrackingNumber)
#

function UpdateOrdersShippingStatus($username,$password,$data,$statustype,$storeid,$others)
{	

	
	global $db,$config,$messageStack;
	DEFINE('SEND_EMAILS', $config['SEND_EMAILS']); 
	DEFINE('EMAIL_USE_HTML', $config['EMAIL_USE_HTML']); 
	DEFINE('STORE_OWNER_EMAIL_ADDRESS',$config["STORE_OWNER_EMAIL_ADDRESS"]);
	DEFINE('EMAIL_FROM',$config["EMAIL_FROM"]);	
	if (!defined('EMAIL_TRANSPORT'))
	{
		DEFINE('EMAIL_TRANSPORT', 'PHP'); 
	}
	if (!defined('EMAIL_SEND_MUST_BE_STORE'))
	{
		DEFINE('EMAIL_SEND_MUST_BE_STORE', 'Yes'); 
	}


	//echo  DIR_FS_CATALOG . DIR_WS_INCLUDES . 'languages/english.php' ;
	if(file_exists(DIR_WS_FUNCTIONS . 'sessions.php'))
	{ 
		require(DIR_WS_FUNCTIONS . 'sessions.php');
		
	}
	else
	{ 
		require("../".DIR_WS_FUNCTIONS . 'sessions.php');
		
	}
	//require("../../".DIR_WS_FUNCTIONS . 'sessions.php');
	
	if (!is_object($messageStack))
	{
		require(DIR_FS_CATALOG . DIR_WS_CLASSES ."/message_stack.php");
		$messageStack = new messageStack();
	}
	require(DIR_FS_CATALOG.DIR_WS_FUNCTIONS . 'functions_email.php');
		
	include_once DIR_FS_CATALOG . DIR_WS_INCLUDES . 'filenames.php' ;	
include_once DIR_WS_INCLUDES .'languages/english/orders.php' ;

	//chdir("../");	
	include_once DIR_WS_INCLUDES . 'languages/english.php' ;
	
		 $Orders = new WG_Orders();
		$WgBaseResponse = new WgBaseResponse();	
		#check for authorisation
		$status =$this->auth_user($username,$password);
		
		
		
	if($status!="0"){ //login name invalid
		if($status=="1"){
		
		$WgBaseResponse->setStatusCode('1');
		$WgBaseResponse->setStatusMessage('"Could not login to your online store. Authorization failed.');
		
		}
		if($status=="2"){ //password invalid
		
		$WgBaseResponse->setStatusCode('2');
		$WgBaseResponse->setStatusMessage('"Could not login to your online store. Authorization failed.');
		
		}
		if($status=="3"){ //Version Not Supported
		
		$WgBaseResponse->setStatusCode('2');
		$WgBaseResponse->setStatusMessage('""Version Not Supported.');		
		}
		
		return $this->response($WgBaseResponse->getBaseResponse());
			 
	}
	

	if (!is_array($data))
		{
		$Orders->setStatusCode("9997");
	    $Orders->setStatusMessage("Unknown request or request not in proper format");	
		return $this->response($Orders->getOrders());
		}
	
	
	if (count($data) == 0) {
		$Orders->setStatusCode("9996");
		$Orders->setStatusMessage("REQUEST array(s) doesnt have correct input format");		
		return $this->response($Orders->getOrders());	
		}
	
	if(strtolower($statustype)=='cancel')
		{
		
		$Orders->setStatusCode("9996");
		$Orders->setStatusMessage("Order can not be canceled");		
		return $this->response($Orders->getOrders());
				
		}
	

	if(count($data) == 0){$no_orders = true; }else {$no_orders = false;}
	
	$Orders->setStatusCode($no_orders?"1000":"0");
	$Orders->setStatusMessage($no_orders?"No new orders.":"All Ok");
	
	if ($no_orders)
	{
		return $this->response($Orders->getOrders());
	}
	
	//$ordersNode = $xmlResponse->createTag("Orders", array(), '', $root);
	
	foreach($data as $order_data)//Order
		{
		
		
		$order_data = array_change_key_case($order_data,CASE_UPPER); 
		$query = "SELECT orders_id cnt ,customers_name, customers_email_address,date_purchased FROM ".TABLE_ORDERS." WHERE orders_id = '".$order_data['ORDERID']."'";
		$row = $db->Execute($query);
		
		$query = "SELECT orders_status_id FROM ".TABLE_ORDERS_STATUS." WHERE orders_status_name = '".$order_data['ORDERSTATUS']."'";
		$row_status = $db->Execute($query);
				
		$result = 'Success';
		$customers_name = $row->fields['customers_name'];
		$date_purchased = $row->fields['date_purchased'];
		$customers_email_address = $row->fields['customers_email_address'];
		
		if ($row->fields['cnt']=='') 
		{
			$result = 'Order not found';
		}	
		elseif($row_status->fields['orders_status_id']<1)
		{
			$result = 'Status not found';
		}
		else 
		{
			
			$date = date("Y-m-d H:i:s");
			
			$info = "\nOrder Shipped ";
			
		    if ($order_data['SHIPPEDON']!="")
			$info .= " on ". substr($order_data['SHIPPEDON'],0,10);
			
			
			
			if ( $order_data['SERVICEUSED']!="" )
			$info .= " via ".$order_data['SERVICEUSED'];
			
			if ($order_data['TRACKINGNUMBER']!="")
			$info .= "  Tracking Number is: ".$order_data['TRACKINGNUMBER'].".";
			
			if ($order_data['ORDERNOTES']!="")
			$info .=" \n".$order_data['ORDERNOTES'];
			
			$updateQuery = " UPDATE " . TABLE_ORDERS . " SET  orders_status = '".$row_status->fields['orders_status_id']."', 			last_modified = '".$date."' WHERE orders_id = '".$order_data['ORDERID']."'";
			$db->Execute($updateQuery);
						
			$insert_history = " INSERT into ".TABLE_ORDERS_STATUS_HISTORY."  (customer_notified ,orders_id,orders_status_id,date_added,comments) VALUES ('". ($order_data['ISNOTIFYCUSTOMER']?1:0) ."','".$order_data['ORDERID']."','".$row_status->fields['orders_status_id']."',now(),'". addslashes(html_entity_decode($info))."') ";
			
			$db->Execute($insert_history);
			
			## Email send to customer for order status notification
		  if($order_data['ISNOTIFYCUSTOMER']=='Y') // only if eCC status in On
		  {		//require_once ("../../".DIR_WS_INCLUDES . 'languages/english/checkout_process.php') ;
				
		 	  $notify_comments = EMAIL_TEXT_COMMENTS_UPDATE . $order_data['ORDERNOTES'] . "\n\n";		
			  			 
			  	
			  $message = STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" .EMAIL_TEXT_ORDER_NUMBER . ' ' . $order_data['ORDERID'] . "\n\n" .
			  EMAIL_TEXT_INVOICE_URL . ' ' . zen_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $order_data['ORDERID'], 'SSL') . "\n\n" .
			  EMAIL_TEXT_DATE_ORDERED . ' ' . zen_date_long($date_purchased) . "\n\n" .  strip_tags($notify_comments) .  EMAIL_TEXT_STATUS_UPDATED . sprintf(EMAIL_TEXT_STATUS_LABEL, $order_data['ORDERSTATUS'] ) . EMAIL_TEXT_STATUS_PLEASE_REPLY;		  
			  $html_msg['EMAIL_CUSTOMERS_NAME']    = $customers_name;
			  $html_msg['EMAIL_TEXT_ORDER_NUMBER'] = EMAIL_TEXT_ORDER_NUMBER . ' ' . $order_data['ORDERID'];
			  $html_msg['EMAIL_TEXT_INVOICE_URL']  = '<a href="' . zen_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $order_data['ORDERID'], 'SSL') .'">'.str_replace(':','',EMAIL_TEXT_INVOICE_URL).'</a>';
			  $html_msg['EMAIL_TEXT_DATE_ORDERED'] = EMAIL_TEXT_DATE_ORDERED . ' ' . zen_date_long($date_purchased);
			  $html_msg['EMAIL_TEXT_STATUS_COMMENTS'] = nl2br($info);
			  $html_msg['EMAIL_TEXT_STATUS_UPDATED'] = str_replace('\n','', EMAIL_TEXT_STATUS_UPDATED);
			  $html_msg['EMAIL_TEXT_STATUS_LABEL'] = str_replace('\n','', sprintf(EMAIL_TEXT_STATUS_LABEL, $order_data['ORDERSTATUS'] ));
			  $html_msg['EMAIL_TEXT_NEW_STATUS'] = $order_data['ORDERSTATUS'];
			  $html_msg['EMAIL_TEXT_STATUS_PLEASE_REPLY'] = str_replace('\n','', EMAIL_TEXT_STATUS_PLEASE_REPLY);
			  
			zen_mail($customers_name, $customers_email_address, EMAIL_TEXT_SUBJECT . ' #' . $order_data['ORDERID'], $message, STORE_NAME, EMAIL_FROM, $html_msg, 'order_status');
         	
		  }	 
 	}
 		$query = "SELECT last_modified  FROM ".TABLE_ORDERS." WHERE orders_id = '".$order_data['ORDERID']."'";
		$row = $db->Execute($query);
		$last_dateTime = $row->fields['last_modified'];
		//$last_dateTime_arr = explode(' ' ,$last_dateTime );
		$last_modified_date = date("m-d-Y H:i:s",strtotime($last_dateTime));
		
		$query = "SELECT comments  FROM ".TABLE_ORDERS_STATUS_HISTORY." WHERE orders_id = '".$order_data['ORDERID']."' ORDER BY date_added DESC";
		$row = $db->Execute($query);
		$orderNote  = $row->fields['comments'];
		
		$Order = new WG_Order();
		$Order->setOrderId($order_data['ORDERID']);
		$Order->setStatus($result);
		$Order->setOrderNotes($orderNote);
		$Order->setLastModifiedDate($last_modified_date);
		$Order->setOrderStatus($order_data['ORDERSTATUS']);
		$Orders->setOrders($Order->getOrder());	
	}
	
	 return $this->response($Orders->getOrders());
}


function UpdateOrdersStatusAcknowledge($username,$password,$data) 
{ 
	global $db,$config,$messageStack;
	DEFINE('SEND_EMAILS', $config['SEND_EMAILS']); 
	DEFINE('EMAIL_USE_HTML', $config['EMAIL_USE_HTML']); 
	DEFINE('STORE_OWNER_EMAIL_ADDRESS',$config["STORE_OWNER_EMAIL_ADDRESS"]);
	DEFINE('EMAIL_FROM',$config["EMAIL_FROM"]);	
	if (!defined('EMAIL_TRANSPORT'))
	{
		DEFINE('EMAIL_TRANSPORT', 'PHP'); 
	}
	if (!defined('EMAIL_SEND_MUST_BE_STORE'))
	{
		DEFINE('EMAIL_SEND_MUST_BE_STORE', 'Yes'); 
	}

	//echo  DIR_FS_CATALOG . DIR_WS_INCLUDES . 'languages/english.php' ;
	if(file_exists(DIR_WS_FUNCTIONS . 'sessions.php'))
	{ 
	require(DIR_WS_FUNCTIONS . 'sessions.php');		
	}
	else
	{ 
		require("../".DIR_WS_FUNCTIONS . 'sessions.php');
		
	}
	
	
	if (!is_object($messageStack))
	{
		require(DIR_FS_CATALOG . DIR_WS_CLASSES ."/message_stack.php");
		$messageStack = new messageStack();
	}
	require(DIR_FS_CATALOG.DIR_WS_FUNCTIONS . 'functions_email.php');
		
	include_once DIR_FS_CATALOG . DIR_WS_INCLUDES . 'filenames.php' ;	
	//chdir("../");	
	include_once DIR_WS_INCLUDES . 'languages/english.php' ;
	
	include_once DIR_WS_INCLUDES . 'languages/english/orders.php' ;
	
		$WgBaseResponse = new WgBaseResponse();	
		$Orders = new WG_Orders();
		$status = $this->auth_user($username,$password);
		
		if($status!="0"){ //login name invalid
		if($status=="1"){
		$WgBaseResponse->setStatusCode('1');
		$WgBaseResponse->setStatusMessage('Could not login to your online store. Authorization failed.');		
		}
		if($status=="2"){ //password invalid
		
		$WgBaseResponse->setStatusCode('2');
		$WgBaseResponse->setStatusMessage('Could not login to your online store. Authorization failed.');				
		}
		if($status=="3"){ //Version Not Supported
		
		$WgBaseResponse->setStatusCode('2');
		$WgBaseResponse->setStatusMessage('Version Not Supported.');					
		}
		return $this->response($WgBaseResponse->getBaseResponse());		
	}


	
	 if (!is_array($data))
	  {
	
		$Orders->setStatusCode("9997");
		$Orders->setStatusMessage("Unknown request or request not in proper format");	
		return $this->response($Orders->getOrders());
	
	}
		
	if (count($data) == 0) {
	
			$Orders->setStatusCode("9996");
			$Orders->setStatusMessage("REQUEST array(s) doesnt have correct input format");				
			return $this->response($Orders->getOrders());
	
			}
	
	if(strtolower($statustype)=='cancel')
		{
			$Orders->setStatusCode("9996");
			$Orders->setStatusMessage("Order can not be canceled");				
			return $this->response($Orders->getOrders());
		
		}
		
	
	
	if(count($data) == 0) $no_orders = true; else $no_orders = false;
		
		$Orders->setStatusCode($no_orders?"1000":"0");
		$Orders->setStatusMessage($no_orders?"No new orders.":"All Ok");
	
	
	if ($no_orders)
	{
		return $this->response($Orders->getOrders());
	}
	
	//$ordersNode = $xmlResponse->createTag("Orders", array(), '', $root);
	
	foreach($data as $order_data)//Order
		{
		$order_data = array_change_key_case($order_data,CASE_UPPER); 
		$query = "SELECT orders_id cnt ,customers_name, customers_email_address,date_purchased FROM ".TABLE_ORDERS." WHERE orders_id = '".$order_data['ORDERID']."'";
		$row = $db->Execute($query);
		$query = "SELECT orders_status_id FROM ".TABLE_ORDERS_STATUS." WHERE orders_status_name = '".$order_data['ORDERSTATUS']."'";
		$row_status = $db->Execute($query);
		$result = 'Success';
		$customers_name = $row->fields['customers_name'];
		$date_purchased = $row->fields['date_purchased'];
		$customers_email_address = $row->fields['customers_email_address'];
		
		if ($row->fields['cnt']=='') 
		{
			$result = 'Order not found';
		}	
		elseif($row_status->fields['orders_status_id']<1)
		{
			$result = 'Status not found';
		}
		else 
		{
			$date = date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y")));			
			$updateQuery = " UPDATE " . TABLE_ORDERS . " SET  orders_status = '".$row_status->fields['orders_status_id']."', 			last_modified = '".$date."' WHERE orders_id = '".$order_data['ORDERID']."'";
			$db->Execute($updateQuery);
		
		}
		
		$query = "SELECT last_modified  FROM ".TABLE_ORDERS." WHERE orders_id = '".$order_data['ORDERID']."'";
		$row = $db->Execute($query);
		$last_dateTime = $row->fields['last_modified'];
		$last_modified_date = date("m-d-Y H:i:s",strtotime($last_dateTime));
		
		$query = "SELECT comments  FROM ".TABLE_ORDERS_STATUS_HISTORY." WHERE orders_id = '".$order_data['ORDERID']."' ORDER BY date_added DESC";
		$row = $db->Execute($query);
		$orderNote  = $row->fields['comments'];
		
		$Order = new WG_Order();
		$Order->setOrderId($order_data['ORDERID']);
		$Order->setStatus($result);
		$Order->setOrderNotes($orderNote);
		$Order->setLastModifiedDate($last_modified_date);
		$Order->setOrderStatus($order_data['ORDERSTATUS']);
		$Orders->setOrders($Order->getOrder());	
		
		
	}
	
	return $this->response($Orders->getOrders());

}

#
# function to return the store Manufacturer list so synch with QB inventory
#
function getManufacturers($username,$password)
{
		global $db;
		$WgBaseResponse = new WgBaseResponse();	
		$Manufacturers = new WG_Manufacturers();
	
		#check for authorisation
		$status =$this->auth_user($username,$password);
	
	if($status!="0")
	{ //login name invalid
		if($status=="1")
		{
			$WgBaseResponse->setStatusCode('1');
			$WgBaseResponse->setStatusMessage('Invalid login. Authorization failed');
			$response=$this->response($WgBaseResponse->getBaseResponse());
			return $response;
		
		}
		if($status=="2")
		{ //password invalid
		
			$WgBaseResponse->setStatusCode('2');
			$WgBaseResponse->setStatusMessage('Invalid password. Authorization failed');
			$response=$this->response($WgBaseResponse->getBaseResponse());
			return $response;
	
		}
		
	}
	$query = "SELECT * FROM ".TABLE_MANUFACTURERS." Order by manufacturers_name ";	
	$row = $db->Execute($query);
	
	
	  	$Manufacturers->setStatusCode('0');
		$Manufacturers->setStatusMessage('All Ok');
		
//	# fetch manufacturers;
	while (!$row->EOF) 
	{
	  
		$row->fields =$this->parseSpecCharsA($row->fields);
		$Manufacturer =new WG_Manufacturer();					
		$Manufacturer->setManufacturerID($row->fields['manufacturers_id']);
		$Manufacturer->setManufacturerName(htmlentities($row->fields['manufacturers_name']));
		$Manufacturers->setManufacturers($Manufacturer->getManufacturer());
		$row->MoveNext();
		
	}
	return $this->response($Manufacturers->getManufacturers());

}


#
# function to return the store tax list so synch with QB inventory
#
function getTaxes($username,$password)
{
		
		global $db;
		$WgBaseResponse = new WgBaseResponse();	
		$Taxes = new WG_Taxes();
		#check for authorisation
		$status = $this->auth_user($username,$password);

		if($status!="0")
		{ //login name invalid
			if($status=="1")
		{
			$WgBaseResponse->setStatusCode('1');
			$WgBaseResponse->setStatusMessage('Invalid login. Authorization failed');
			$response=$this->response($WgBaseResponse->getBaseResponse());
			return $response;
				
		}
		if($status=="2")
		{ //password invalid
		
			$WgBaseResponse->setStatusCode('2');
			$WgBaseResponse->setStatusMessage('Invalid password. Authorization failed');
			$response=$this->response($WgBaseResponse->getBaseResponse());
			return $response;
		}		
		
	}	
	$query = "SELECT * FROM ".TABLE_TAX_CLASS." Order by tax_class_title ";	
	$row = $db->Execute($query);
	
		$Taxes->setStatusCode('0');
		$Taxes->setStatusMessage('All Ok');
	 
	while (!$row->EOF) 
	{
	 		
		$row->fields =$this->parseSpecCharsA($row->fields);
		$Tax =new WG_Tax();
		$Tax->setTaxID($row->fields['tax_class_id']);
		$Tax->setTaxName(htmlentities($row->fields['tax_class_title']));
		$Taxes->setTaxes($Tax->getTax());
		$row->MoveNext();	
				
	}
	return $this->response($Taxes->getTaxes());
}


#
# function to return the store Category list so synch with QB inventory
#
function getCategory($username,$password)
{
	global $db;
	$WgBaseResponse = new WgBaseResponse();	
	#check for authorisation
	$status = $this->auth_user($username,$password);
	if($status!="0")
	{ //login name invalid
		if($status=="1")
		{
			$WgBaseResponse->setStatusCode('1');
			$WgBaseResponse->setStatusMessage('Invalid login. Authorization failed');
			
		}
		if($status=="2")
		{ //password invalid
			$WgBaseResponse->setStatusCode('2');
			$WgBaseResponse->setStatusMessage('Invalid password. Authorization failed');
			
		}
		if($status=="3"){ //Version Not Supported
			$WgBaseResponse->setStatusCode('2');
		$WgBaseResponse->setStatusMessage('Version Not Supported');
		
		}
		
		$response=$this->response($WgBaseResponse->getBaseResponse());
		return $response;
	}
	
	 $query = "SELECT distinct categories_name , a.categories_id ,a.parent_id FROM ".TABLE_CATEGORIES." a join ". TABLE_CATEGORIES_DESCRIPTION ." b on a.categories_id= b.categories_id where categories_status=1 and language_id=1 Order by categories_name";
	$row = $db->Execute($query);

	//$pMethodNodes = $xmlResponse->createTag("Categories", array(), '', $root);
		$Categories = new WG_Categories();
		$Categories->setStatusCode('0');
		$Categories->setStatusMessage('All Ok');
			
//	# fetch manufacturers;
	while (!$row->EOF) 
	{		
			$row->fields =$this->parseSpecCharsA($row->fields);
			$Category =new WG_Category();
			$Category->setCategoryID($row->fields['categories_id']);
			$Category->setCategoryName(htmlentities($row->fields['categories_name']), ENT_QUOTES);
			$Category->setParentID($row->fields['parent_id']);
			$Categories->setCategories($Category->getCategory());
			$row->MoveNext();
	}
	
	return $this->response($Categories->getCategories());
} 

# Function to add the product in the store which found in QB

function addProduct($username,$password,$data)
{
	global $db; 	
	//$config['General']['unlimited_products'];  //variable to track inventory 
	$WgBaseResponse = new WgBaseResponse();	
	$Items = new WG_Items();
	$status = $this->auth_user($username,$password);
	if($status!="0"){ //login name invalid
	if($status=="1"){
	
	$WgBaseResponse->setStatusCode('1');
	$WgBaseResponse->setStatusMessage('Invalid user name. Authorization failed');
	}
	
	if($status=="2"){ //password invalid
	$WgBaseResponse->setStatusCode('2');
	$WgBaseResponse->setStatusMessage('Invalid password. Authorization failed');
	}	
	$response=$this->response($WgBaseResponse->getBaseResponse());
	return $response;
	
	}
	else
	{
	$Items->setStatusCode('0');
	$Items->setStatusMessage('All Ok');
	
	}
	
	$requestArray = $data;
	
	if (!is_array($requestArray)) 
	{
	
	$Items->setStatusCode('9997');
	$Items->setStatusMessage('Unknown request or request not in proper format');				
	return $this->response($Items->getItems());
	
	}
		if (count($requestArray) == 0) {
		
					$Items->setStatusCode('9996');
					$Items->setStatusMessage('REQUEST tag(s) doesnt have correct input format');
					return $this->response($Items->getItems());
		
		}
	 $itemsCount = 0;
	 $itemsProcessed = 0;
	 // Go throught items
	 $itemsCount = 0;
	 $_err_message_arr = Array();
	 foreach($requestArray as $k2=>$vItem)//request
	 {
	 	
 		$itemsCount++;
		$productcode=$vItem['ItemCode'];
		$product=$vItem['ItemName'];
		$descr=$vItem['ItemDesc'];
		$free_shipping=$vItem['FreeShipping'];
		$free_tax=$vItem['TaxExempt'];
		$tax_id=$vItem['TaxID'];
		$item_match=$vItem['ItemMatchBy'];
		$manufacturerid=$vItem['ManufacturerID'];
		$avail_qty=$vItem['Quantity'];
		$price=$vItem['UnitPrice'];
		$weight=$vItem['Weight'];
			

			if (strtolower($item_match)=="sku#")
			{				
			$query = "select count(*) as cnt from ".TABLE_PRODUCTS." where products_model='".addslashes(html_entity_decode($productcode))."'";     }
			else
			{
			$query = "select count(*) as cnt from ".TABLE_PRODUCTS_DESCRIPTION." where language_id=1 and products_name='".addslashes(html_entity_decode($product))."'";
			}
			
			$row = $db->Execute($query);
			
			if ($free_shipping=='Y')
			$free_shipping=1;
			else
			$free_shipping=0;
			
			if($avail_qty>0)
				$stockstatus = 1;
			else
				$stockstatus = 0;
			
			if ($row->fields['cnt']==0)
			{			
			 $sql_data_array = array('products_quantity' => $avail_qty,
                                  'products_model' => zen_db_prepare_input($productcode),
                                  'products_price' => $price,
                                  'products_date_available' => $products_date_available,
								  'product_is_always_free_shipping'=>$free_shipping,
                                  'products_weight' => $weight,
                                  'products_status' => $stockstatus,
                                  'products_tax_class_id' => zen_db_prepare_input($tax_id),
                                  'manufacturers_id' => $manufacturerid,
								  'products_image' => '',
								  'products_date_added' => 'now()',
								   'master_categories_id'=>$cid,
								  'products_date_available' => 'now()');
 
 			zen_db_perform(TABLE_PRODUCTS, $sql_data_array);
            $products_id = zen_db_insert_id();
			//$arrayCategories=$vItem['Categories'];
				if(is_array($vItem['Categories']))
				 {
									
				 $arrayCategories=$vItem['Categories'];
				 $c=0;			
						
				foreach($arrayCategories as $k3=>$vCategories)  
				
				{	
								
					$cid=$vCategories['CategoryId'];
					if(isset($cid)&& $cid!='')
					{ 	
					$categoryid =  $cid;	
						//$categoryid[] =  $cid;
						//$catid[] =  $cid;	
						
					 $db->Execute("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$products_id . "', '" . (int)$cid . "')");	
					}
	
				}
			
			} 
			
			 $sql_data_array = array(
			 'products_name' => zen_db_prepare_input($product),
			 'products_description' => zen_db_prepare_input($descr),
			 'products_url' => '',
			 'products_id' => zen_db_prepare_input($products_id),
			 'language_id' => 1);
			  zen_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array);
			
			##  end insert query
			
			
			#Calling function for add image
			if($vItem['Image']) {
				$this->addItemImage($products_id,$vItem['Image'],$storeid=1);
			}
			
			$Item = new WG_Item();
			$Item->setStatus('Success');
			$Item->setProductID($products_id);
			$Item->setSku(htmlentities($productcode));
			$Item->setProductName(htmlentities($product));
			
			}
			else
			{
			
			$Item = new WG_Item();
			$Item->setStatus('Duplicate product code exists');
			$Item->setProductID($products_id);
			$Item->setSku(htmlentities($productcode));
			$Item->setProductName(htmlentities($product));
	
			}	
			$Items->setItems($Item->getItem());	  		
		} //End of Items foreach loop
		return $this->response($Items->getItems());
	
}


function GetImage($username,$password,$data,$storeid=1,$others) {
	
	
	global $db; 	
	
	$WgBaseResponse = new WgBaseResponse();	
	$Items = new WG_Items();
	$status = $this->auth_user($username,$password);
	if($status!="0"){ 
	if($status=="1"){
	
	$WgBaseResponse->setStatusCode('1');
	$WgBaseResponse->setStatusMessage('Invalid user name. Authorization failed');
	}
	
	if($status=="2"){ //password invalid
	$WgBaseResponse->setStatusCode('2');
	$WgBaseResponse->setStatusMessage('Invalid password. Authorization failed');
	}	
	$response=$this->response($WgBaseResponse->getBaseResponse());
	return $response;
	
	}
	else
	{
	$Items->setStatusCode('0');
	$Items->setStatusMessage('All Ok');
	
	}
	
	$requestArray = $data;
	
	if (!is_array($requestArray)) 
	{
	
	$Items->setStatusCode('9997');
	$Items->setStatusMessage('Unknown request or request not in proper format');				
	return $this->response($Items->getItems());
	
	}
if (count($requestArray) == 0) {
		
					$Items->setStatusCode('9996');
					$Items->setStatusMessage('REQUEST tag(s) doesnt have correct input format');
					return $this->response($Items->getItems());
		
		}
	 $itemsCount = 0;
	 $itemsProcessed = 0;
	 // Go throught items
	 $itemsCount = 0;
	 $_err_message_arr = Array();
	 foreach($requestArray as $k2=>$vItem)//request
	 {
		 
		 $status ="Success";
		$productID = $vItem['ItemID'];
		 
		 
		 $iInfo = $db->Execute("SELECT DISTINCT ". TABLE_PRODUCTS .". * , ". TABLE_PRODUCTS_DESCRIPTION . ". *, if(tax_rate > 0 ,1,NULL) as tax_rate  FROM ". TABLE_PRODUCTS ."
	INNER JOIN ". TABLE_PRODUCTS_DESCRIPTION . " ON ". TABLE_PRODUCTS .".products_id = ". TABLE_PRODUCTS_DESCRIPTION . ".products_id LEFT JOIN " . TABLE_SPECIALS . " ON  " . TABLE_SPECIALS . ".products_id =  ". TABLE_PRODUCTS .".products_id
	LEFT JOIN " . TABLE_TAX_RATES . " ON  " . TABLE_TAX_RATES . ".tax_class_id =  ". TABLE_PRODUCTS .".products_tax_class_id  WHERE ". TABLE_PRODUCTS_DESCRIPTION . ".language_id =1 and ". TABLE_PRODUCTS .".products_status=1 and ". TABLE_PRODUCTS .".products_id = '".$productID."'");
		 
		 
		if (!$iInfo->EOF) {
		
			
			$itemI = 0;
			while (!$iInfo->EOF) {
			
				//$itemNode = $xmlResponse->createTag("Item",    array(), '',    $itemsNode);
			
				
				$iInfo->fields = $this->parseSpecCharsA($iInfo->fields);
				//Code to set image
				if($iInfo->fields['products_image'] != '' && strlen($iInfo->fields['products_image']) > 0) {
					
					$responseArray = array();
					$responseArray['ItemID']		=	$productID;
					$responseArray['Image']		=	base64_encode(file_get_contents(DIR_FS_CATALOG_IMAGES.$iInfo->fields['products_image']));
					$Items->setItems($responseArray);
					
				}break;
				//End code to set image		
				
				
			} //end of item while
			  
			
		} //end of if records
		 
		 
			 
		 
	} //End of Items foreach loop
		return $this->response($Items->getItems());exit();
	//	return $xmlResponse->generate();

	
}

function addItemImage($itemid,$image,$storeid=1) {

	global $db;
	
	$product_q = $db->Execute("SELECT products_id , products_image  from ".TABLE_PRODUCTS." where products_id ='".$itemid."'");
	//print_r($product_q);
	if (!$product_q->EOF) {
		while (!$product_q->EOF) {
			$product_q->fields = $this->parseSpecCharsA($product_q->fields);
			$products_image	=	$product_q->fields['products_image'];
			$products_id	=	$product_q->fields['products_id'];
			$product_q->MoveNext();
		}
	}
	//if(substr(decoct(fileperms(DIR_FS_CATALOG_IMAGES)),2) == '777') {
		
		$image_name = time().'.jpg';
		
		
		$str	=	base64_decode($image);
		$fp = fopen(DIR_FS_CATALOG_IMAGES.$image_name, 'w+');
		fwrite($fp, $str);
		fclose($fp);
		$update_sql = "UPDATE " . TABLE_PRODUCTS . " SET products_image = '" . $image_name . "' WHERE products_id ='".$itemid."'";
		//echo $update_sql;
		$db->Execute($update_sql);
		
		//echo $products_image;
		$old_filename	=	DIR_FS_CATALOG_IMAGES.$products_image;
		if (file_exists($old_filename)) {
			@unlink(old_filename);
		}	
		
		
		return true;
	/* } else {
		
		return false;
	} */	
	
	#return $this->response($Items->getItems());
}


# Function to Sync the Items and the Varients with the QB



	function synchronizeItems($username,$password,$data,$storeid=1,$others)
	{
	
	
		global $db; 	
		
		$WgBaseResponse = new WgBaseResponse();	
		$Items = new WG_Items();
		$status = $this->auth_user($username,$password);	
		
		if($status!="0"){ //login name invalid
		
		if($status=="1"){
		$WgBaseResponse->setStatusCode('1');
		$WgBaseResponse->setStatusMessage('Invalid user name. Authorization failed');
		}
		if($status=="2"){ //password invalid
		$WgBaseResponse->setStatusCode('2');
		$WgBaseResponse->setStatusMessage('Invalid password. Authorization failed');
		}
		if($status=="3"){ //Version Not Supported
		$WgBaseResponse->setStatusCode('2');
		$WgBaseResponse->setStatusMessage('Version Not Supported');
			}
		
		return $this->response($WgBaseResponse->getBaseResponse());
		
		}
		else
		{
		$Items->setStatusCode('0');
		$Items->setStatusMessage('All Ok');	
		}		
		$requestArray = $data;
		
		if (!is_array($requestArray)) 
	    {
		 
		$Items->setStatusCode('9997');
		$Items->setStatusMessage('Unknown request or request not in proper format');				
		return $this->response($Items->getItems());
		}
		
		if (count($requestArray) == 0) 
		{
		$Items->setStatusCode('9996');
		$Items->setStatusMessage('REQUEST tag(s) doesnt have correct input format');
		return $this->response($Items->getItems());
		}
		
		 $itemsCount = 0;
		 $itemsProcessed = 0;
		
		 // Go throught items
		 $itemsCount = 0;
		 $_err_message_arr = Array();
		
		$pos = strpos($others,'/');
		if($pos)
		{
		    $array_others = explode("/",$others);
					   
		}else{
			$array_others=array();
			$array_others[]=$others;       
		}
		foreach($requestArray as $k=>$v4)
		{
	 			$itemsCount++;
				
				$Item = new WG_Item();
				$productID = $v4['ProductID'];
				$sku = $v4['Sku'];
				$productName = $v4['ProductName'];
				$qty = $v4['Qty'];
				$price = $v4['Price'];
				
				
									
					$Item->setProductID($v4['ProductID']);
					$Item->setSku(htmlentities($v4['Sku']));	
					$Item->setProductName(htmlentities($v4['ProductName']));						
					
				
				if ( count($_varientsTags)>0)
				{		
				$Item->setVarients('');	
				}
				$updated_attrib=0;
						
	          
				
					
						foreach($v4 as $key=>$value)
						{
							if ($key=="ID")
							{
								$varient_id = $value;
							}
							if ($key=="QTY")
							{
								$varient_qty =$value;
							}
							if ($key=="PRICE")
							{
								$varient_price = $value;
							}	
							if ($key=="SKU")
							{
								$vsku = $value;
							}	
	
						}
				foreach($array_others as $ot)
				{					
					if ($updated_attrib ==0)
					{
						$status ="";
						$sql_str="select count(*) as cnt from ".TABLE_PRODUCTS." where products_id='".$productID."'";
		                $row = $db->Execute($sql_str);
						$no_orders = false;
						
						if ($row->fields['cnt']==0) 
						{
							$status ="Product not found";
						}
					
					
					if($qty>0)
						$stockstatus = 1;
					else
						$stockstatus = 0;
					
						//if($others=="QTY" || $others=="BOTH")
						if($ot=="QTY" || $ot=="BOTH")
						{			
						$qry = "UPDATE ".TABLE_PRODUCTS." SET products_quantity='$qty',products_status='$stockstatus' WHERE products_id ='".$productID."'";
						if(!$db->Execute($qry)) $status = 'Failed';
						}
						//if($others=="PRICE" || $others=="BOTH")
						elseif($ot=="PRICE" || $ot=="BOTH") 
						{			
						$qry = "UPDATE ".TABLE_PRODUCTS." SET products_price='$price' WHERE products_id ='".$productID."'";
						if(!$db->Execute($qry)) $status = 'Failed';
						}	
						$itemsProcessed++; 	
						
						if ($status =="") $status ="Success";	
						
						$Item->setStatus($status);
						$Item->setStatus('Success');
						$Item->setProductID($productID);
						$Item->setSku($v4['Sku']);							
						$Items->setItems($Item->getItem());	
					
					}
					else if($updated_attrib == $k1+1)
					{
						$itemsProcessed++; 
					}
				}
				
		 }
			
		return $this->response($Items->getItems());	
	}

# Return the Count of the orders remained with specific dates and status

function getOrdersRemained($start_date,$start_order_no=0,$str_excl_status,$str_date_filter)
{	
	global $db;	
	$WgBaseResponse = new WgBaseResponse();	
	$Orders = new WG_Orders();
	
 	$sql_str = "SELECT 	COUNT(*) cnt FROM " . TABLE_ORDERS . " o
	LEFT JOIN ".TABLE_ORDERS_STATUS." os on os.orders_status_id = o.orders_status 
	WHERE  orders_id >".$start_order_no." ".$str_date_filter." ".$str_excl_status;
	$row = $db->Execute($sql_str);
	return $row->fields['cnt'];
}

# Return the Orders to sync with the QB according to the date and the staus and order id.

function getOrders($username,$password,$datefrom,$start_order_no,$ecc_excl_list,$order_per_response=25,$LastModifiedDate,$storeid,$others,$ccdetails)
{
	global $db;	
	global $add_option_in_sku;
	global $track_common_coupon_code;
	$Orders = new WG_Orders();
	
		$orderlist='';
		foreach($others as $k=>$v)
		{
		$orderlist = $orderlist?($orderlist.",'".$v['OrderId']."'"):"'".$v['OrderId']."'";
		}
	//if(!isset($datefrom) or empty($datefrom)) $datefrom=date('Y-m-d');
	if(!isset($datefrom) or empty($datefrom)) 
	{
	$datefrom=date('Y-m-d');
	}
	else
	{
	$date = explode("-",$datefrom);
	$datefrom = $date[2] ."-" .$date[0] ."-" . $date[1];
	}
	
	$status =$this->auth_user($username,$password);
	if($status!="0")
	{ //login name invalid
		if($status=="1")
		{
		  $Orders->setStatusCode('1');
		  $Orders->setStatusMessage('Invalid login. Authorization failed');
		}
		if($status=="2")
		{ //password invalid
		  $Orders->setStatusCode('2');
		  $Orders->setStatusMessage('Invalid password. Authorization failed');
		}
		if($status=="3"){ //Version Not Supported
		 $Orders->setStatusCode('2');
		  $Orders->setStatusMessage('Version Not Supported');
		}
		
		return $this->response($Orders->getOrders());
	}
   
	
   DEFINE("QB_ORDERS_PER_RESPONSE",$order_per_response);

	$str_date_filter = " and o.date_purchased >='$datefrom' ";
	
	$str_excl_status = "  and os.language_id=1 ";
	if ($ecc_excl_list !="")
	{
		$str_excl_status .= " and os.orders_status_name  in ($ecc_excl_list)";
	}	
	
	if($orderlist!='')
	{	
		$sql_str = "SELECT 	COUNT(*) cnt FROM " . TABLE_ORDERS . " o
	LEFT JOIN ".TABLE_ORDERS_STATUS." os on os.orders_status_id = o.orders_status 
	WHERE  orders_id in($orderlist)";
	
	}else{
	$sql_str = "SELECT 	COUNT(*) cnt FROM " . TABLE_ORDERS . " o
	LEFT JOIN ".TABLE_ORDERS_STATUS." os on os.orders_status_id = o.orders_status 
	WHERE  orders_id >".$start_order_no." ".$str_date_filter." ".$str_excl_status." ".(QB_ORDERS_PER_RESPONSE>0?"LIMIT 0, ".QB_ORDERS_PER_RESPONSE:'');
	
			}	
	
	$row = $db->Execute($sql_str);	
	$no_orders = false;	
	if ($row->fields['cnt']==0) 
	{
		$no_orders = true;
	}
	#
	# Get total no of orders available for the said filter criteria excluding start order no. 
	#

	
	$Orders = new WG_Orders();
if($orderlist=='')
{	
	$orders_remained =$this->getOrdersRemained($start_date,$start_order_no,$str_excl_status,$str_date_filter);
	
	 

	$orders_remained =  ($orders_remained >0)? ($orders_remained):"0";
	
}	 
	
 	 $Orders->setStatusCode($no_orders?"9999":"0");
	 $Orders->setStatusMessage($no_orders?"No Orders returned":"Total Orders:".$orders_remained);
	 

	
	if ($no_orders){	
	
	return $this->response($Orders->getOrders());
		flush; exit;
	}
	
	

//ini_set('display_errors' , 'On');

if($orderlist!='')
{

 $orders_query_raw = "SELECT o.*,tp.txn_id,tp.order_id,  ot.value AS orders_total , cust.customers_firstname , cust.customers_lastname,cust.customers_gender,cust.customers_fax, os.orders_status_name 	FROM ".TABLE_ORDERS." o 
	
	LEFT JOIN ".TABLE_ORDERS_TOTAL." ot ON o.orders_id = ot.orders_id 
	LEFT JOIN ".TABLE_CUSTOMERS." cust on cust.customers_id = o.customers_id	
	LEFT JOIN ".TABLE_ORDERS_STATUS." os on os.orders_status_id = o.orders_status
	LEFT JOIN ".TABLE_PAYPAL."	tp on o.orders_id = tp.order_id		
	WHERE o.orders_id in($orderlist) GROUP BY o.orders_id ORDER BY o.orders_id "; 
	


}else{
	 $orders_query_raw = "SELECT o.*, tp.txn_id, tp.order_id, ot.value AS orders_total , cust.customers_firstname , cust.customers_lastname,cust.customers_gender,cust.customers_fax, os.orders_status_name 	FROM ".TABLE_ORDERS." o 
	
	LEFT JOIN ".TABLE_ORDERS_TOTAL." ot ON o.orders_id = ot.orders_id 
	LEFT JOIN ".TABLE_CUSTOMERS." cust on cust.customers_id = o.customers_id	
	LEFT JOIN ".TABLE_ORDERS_STATUS." os on os.orders_status_id = o.orders_status	
	LEFT JOIN ".TABLE_PAYPAL."	tp on o.orders_id = tp.order_id
	WHERE o.orders_id >".$start_order_no." and ot.class ='ot_total' 
	 ".$str_excl_status." ".$str_date_filter." GROUP BY o.orders_id ORDER BY o.orders_id,o.last_modified 
	".(QB_ORDERS_PER_RESPONSE>0?"LIMIT 0, ".QB_ORDERS_PER_RESPONSE:'');
}
		
	//$module = new $order->info['payment_module_code'];
	$oInfo = $db->Execute($orders_query_raw);


	# fetch orders
	while (!$oInfo->EOF) 
	{

		$oInfo->fields =$this-> parseSpecCharsA($oInfo->fields);
		
		// Orders/Order info
		$Order= new WG_Order();
		$Order->setOrderId( $oInfo->fields['orders_id']);
		if ($oInfo->fields['customers_gender']=="m")
		$Order->setTitle("Mr.");
		else if($oInfo->fields['customers_gender']=="f")
		$Order->setTitle("Ms.");		
		else
		$Order->setTitle("");	
		$Order->setFirstName(strtok($oInfo->fields['customers_firstname'], " "));
		$Order->setLastName(strtok($oInfo->fields['customers_lastname'], " "));
		$Order->setDate(date("m-d-Y",strtotime($oInfo->fields['date_purchased']))); 					
		$Order->setTime(date("H:i:s",strtotime($oInfo->fields['date_purchased']))); 

		if($oInfo->fields['last_modified']=="" || $oInfo->fields['last_modified']== NULL){
			$lastModifiedDate =  date("m-d-Y H:i:s",strtotime($oInfo->fields['date_purchased']));
		}
		else{
			
			$lastModifiedDate = date("m-d-Y H:i:s",strtotime($oInfo->fields['last_modified']));
		}
		$Order->setLastModifiedDate($lastModifiedDate);	
		$Order->setStoreID(STORE_NAME_ID);
		$Order->setStoreName(STORE_NAME);
		$Order->setCurrency($oInfo->fields['currency']);
		$Order->setWeight_Symbol('lbs');
		$Order->setWeight_Symbol_Grams('453.6');
		$Order->setStatus( $oInfo->fields['orders_status_name']);
		
		$query = "SELECT comments  FROM ".TABLE_ORDERS_STATUS_HISTORY." WHERE orders_id = '".$oInfo->fields['orders_id']."' ORDER BY date_added DESC";
		$row = $db->Execute($query);
		$comment  = $row->fields['comments'];
		
		$Order->setNotes($comment);
		$Order->setComment( $comment);
		$Order->setFax($oInfo->fields['customers_fax']);
		
		
		
		
		//$Orders->setOrders($Order->getOrder());
		$Bill = new WG_Bill();
		
		if (!empty($oInfo->cc_type) || (!empty($oInfo->fields['cc_number'])))
		{
			$CreditCard = new WG_CreditCard();
			if($ccdetails!=='DONOTSEND')
			{
			$CreditCard->setCreditCardType( $oInfo->fields['cc_type']);
			$CreditCard->setCreditCardCharge( $oInfo->fields['orders_total']);
			$CreditCard->setExpirationDate( $oInfo->fields['cc_expires']);
			$CreditCard->setCreditCardName( $oInfo->fields['cc_owner']);
			$CreditCard->setCreditCardNumber($oInfo->fields['cc_number']);
			$CreditCard->setCVV2('');
			$CreditCard->setAdvanceInfo( '');
			}
			$CreditCard->setTransactionId($oInfo->fields['txn_id']);
			
		
		}
		else{
		
			$CreditCard = new WG_CreditCard();
			switch($oInfo->fields['payment_module_code'])
			{
				case "paypalwpp":
					$sql = "SELECT txn_id as transaction_id from  " . TABLE_PAYPAL . " WHERE order_id = :orderID  AND parent_txn_id = '' AND order_id > 0   ORDER BY paypal_ipn_id DESC LIMIT 1";
					
					$sql = $db->bindVars($sql, ':orderID', $oInfo->fields['orders_id'], 'integer');
					$ipn = $db->Execute($sql);
				
					$CreditCard->setTransactionId($ipn->fields['transaction_id']);
			
				break;
				
				case "authorizenet":
				
					$sql = "SELECT transaction_id from " . TABLE_AUTHORIZENET . " WHERE order_id = :orderID  ";
					$sql = $db->bindVars($sql, ':orderID', $oInfo->fields['orders_id'], 'integer');
					$ipn = $db->Execute($sql);
					$CreditCard->setTransactionId($ipn->fields['transaction_id']);
				
				default:
					$CreditCard->setTransactionId("");
				break;
			}
	 	
		}
		// Orders/Bill info
		$Bill->setCreditCardInfo($CreditCard->getCreditCard());
		//$Bill = new WG_Bill();
		$Bill->setPayMethod($oInfo->fields['payment_method']);
		//$Bill->setPayStatus($oInfo->fields['orders_status_name']);
		$Bill->setTitle("");
		$billing_name = explode(" ",strrev($oInfo->fields['billing_name']),2);
		$Bill->setFirstName(strrev($billing_name[1]));
		$Bill->setLastName(strrev($billing_name[0]));
		$Bill->setCompanyName( $oInfo->fields['billing_company']);
		$Bill->setAddress1($oInfo->fields['billing_street_address']);
		$Bill->setAddress2( $oInfo->fields['billing_suburb']);
		$Bill->setCity( $oInfo->fields['billing_city']);
		$Bill->setState( $oInfo->fields['billing_state']);
		$Bill->setZip($oInfo->fields['billing_postcode']);
		$Bill->setCountry( $oInfo->fields['billing_country']);
		$Bill->setEmail($oInfo->fields['customers_email_address']);
		$Bill->setPhone($oInfo->fields['customers_telephone']);	
		$Bill->setPonumber($oInfo->fields['purchase_order_number']);
		$Order->setOrderBillInfo($Bill->getBill());
	
		//Gift wrapping
		$giftwrapping = $db->Execute("SELECT title FROM " . TABLE_ORDERS_TOTAL . " WHERE orders_id = '".$oInfo->orders_id."' AND class = 'ot_giftwrapping'");		
		
		
		$giftwrap = ((substr($giftwrapping->fields['title'], -1) == ':') ? substr(strip_tags($giftwrapping->fields['title']), 0, -1) : strip_tags($giftwrapping->fields['title']));
		$giftwrap = htmlentities($giftwrap, ENT_QUOTES); 

		// Orders/Ship info
		$shipping_method = $db->Execute("SELECT title FROM " . TABLE_ORDERS_TOTAL . " WHERE orders_id = '".$oInfo->fields['orders_id']."' AND class = 'ot_shipping'");
		
		
		$ship_method = ((substr($shipping_method->fields['title'], -1) == ':') ? substr(strip_tags($shipping_method->fields['title']), 0, -1) : strip_tags($shipping_method->fields['title']));
		$ship_method = preg_replace("/&\w+;/is", "", $ship_method);
		$ship_method = htmlentities($ship_method, ENT_QUOTES); 
	
		$ship_method = preg_replace("/([^(]+)(\([^)]*?\))?\s*(\([^)]+\))/is", "$1$3", $ship_method);
		
		$ship_method = (explode('(',$ship_method,2));
	
		
		
		$Ship = new WG_Ship();
		
		$Ship->setShipMethod( str_replace(")","",$ship_method[1]));
		$Ship->setCarrier( $ship_method[0]);
		
	//	$xmlResponse->createTag("TrackingNumber",array(), '',   $shipNode, __ENCODE_RESPONSE); // NOT FOUND
		$Ship->setTitle($title);
		$delivery_name = explode(" ",strrev($oInfo->fields['delivery_name']),2);
		$Ship->setFirstName( strrev($delivery_name[1]));
		$Ship->setLastName(strrev($delivery_name[0]));	
		$Ship->setCompanyName($oInfo->fields['delivery_company']);	
		$Ship->setAddress1( $oInfo->fields['delivery_street_address']);
		$Ship->setAddress2($oInfo->fields['delivery_suburb']);
		$Ship->setCity( $oInfo->fields['delivery_city']);
		$Ship->setState( $oInfo->fields['delivery_state']);
		$Ship->setZip($oInfo->fields['delivery_postcode']);
		$Ship->setCountry($oInfo->fields['delivery_country']);
		$Ship->setEmail($oInfo->fields['customers_email_address']);
		$Ship->setPhone($oInfo->fields['customers_telephone']);
		$Order->setOrderShipInfo($Ship->getShip());
		unset($delivery_name);
		unset($billing_name);
		$Order->setOrderShipInfo($Ship->getShip());
		
		//Associations
		$sql_str_asc = "SHOW TABLES LIKE 'associations'";    
		$asc_exist_rows = $db->Execute($sql_str_asc);
		
		if (!$asc_exist_rows->EOF) 
		{
						
			$asc_query_raw = "select a.asc_contact_name from associations a, associations_account_detail d  where a.asc_id = d.asc_id and d.asc_ord_id = ".$oInfo->fields['orders_id'];
			$asc = $db->Execute($asc_query_raw);
		
			while (!$asc->EOF) 
			{
				
				$asc->MoveNext();
			}
		} 
		else 
		{
			
		}


		$items_query_raw = "SELECT o.* ,pd.products_description, pd.language_id as lng , p.product_is_always_free_shipping, p.product_is_call,p.products_weight,p.products_tax_class_id FROM ".TABLE_ORDERS_PRODUCTS." o left join ".TABLE_PRODUCTS." p on o.products_id = p.products_id left JOIN ". TABLE_PRODUCTS_DESCRIPTION . " pd ON p.products_id = pd.products_id   WHERE o.orders_id =".$oInfo->fields['orders_id']." order by o.orders_products_id ";

	
		$iInfo = $db->Execute($items_query_raw);
		# fetch item of given order
		while (!$iInfo->EOF) 
		{
			$Item = new WG_Item();
			$iInfo->fields = $this->parseSpecCharsA($iInfo->fields);
			
			if ($iInfo->lng==1 || $iInfo->lng=="")
			{	
			
			$iInfo->fields['products_model'] = trim($iInfo->fields['products_model']);
			
			$Item->setItemDescription(html_entity_decode($iInfo->fields['products_name']));
			
			if ($iInfo->fields['lng']==1)
			$desc=htmlentities(substr($iInfo->fields['products_description'],0,4000),ENT_QUOTES);
			else
			$desc='Deleted Item';
			$Item->setItemShortDescr(html_entity_decode($desc));
			$Item->setQuantity($iInfo->fields['products_quantity']);
			if ($iInfo->fields['product_is_free']==1 || $iInfo->fields['product_is_call']==1)
			$iInfo->fields['products_price']="0.00"; 
			$Item->setUnitPrice((float)$iInfo->fields['final_price']);
			$Item->setWeight((float)$iInfo->fields['products_weight']); 				
			$Item->setFreeShipping($iInfo->fields['product_is_always_free_shipping']==1?"Y":"N");
			$Item->setDiscounted( "N"); 
			$Item->setshippingFreight( "0");
			$Item->setWeight_Symbol("lbs");
			$Item->setWeight_Symbol_Grams("453.6");
			if($iInfo->fields['products_tax_class_id'] >0)
			{
			
				$Item->setTaxExempt('N');
			}else{
				$Item->setTaxExempt('Y');
			}
			//$Item->setOneTimeCharge( number_format($iInfo->fields['onetime_charges'],2,'.',''));
			
			//MJ - Fix for UK edition to handle VAT
			$Item->setItemTaxAmount((($iInfo->fields['products_tax'] * $iInfo->fields['products_price'] * $iInfo->fields['products_quantity'])/100));
			
			$itemsa_query_raw = "SELECT * FROM ".TABLE_ORDERS_PRODUCTS_ATTRIBUTES." WHERE orders_id='".$oInfo->fields['orders_id']."' AND orders_products_id = '".$iInfo->fields['orders_products_id']."' order by  products_options";
			$iaInfo =  $db->Execute($itemsa_query_raw);
			$customsku = '';
			while (!$iaInfo->EOF)
			{ 
				$iaInfo->fields =$this->parseSpecCharsA($iaInfo->fields);
				
					$Itemoption = new WG_Itemoption();
					
					
					$weight=0;	
					if($iaInfo->fields['products_attributes_weight']>0)
					{
					$weight=1;
					if($iaInfo->fields['products_attributes_weight_prefix']=='')
					{
					$iaInfo->fields['products_attributes_weight'] = "+" . $iaInfo->fields['products_attributes_weight'];
					}
					else
					{
					$iaInfo->fields['products_attributes_weight'] = $iaInfo->fields['products_attributes_weight_prefix']. $iaInfo->fields['products_attributes_weight'];
					
					}
					}
										
					if($iaInfo->fields['options_values_price']>0 || $iaInfo->fields['attributes_price_onetime']>0)
					{
						$iaInfo->fields['options_values_price'] = $iaInfo->fields['options_values_price']+$iaInfo->fields['attributes_price_onetime'];
						if($weight==1)
						{
						
						$Itemoption->setOptionValue(html_entity_decode($iaInfo->fields['products_options_values']));
						$Itemoption->setOptionName(html_entity_decode($iaInfo->fields['products_options']));
						$total_option_price = $iInfo->fields['products_quantity']*$iaInfo->fields['options_values_price'];
						$Itemoption->setOptionPrice("(".$iaInfo->fields['price_prefix'].$total_option_price.")");
						$Itemoption->setOptionWeight($iaInfo->fields['products_attributes_weight']);
								
						}
						else
						{
						
						$Itemoption->setOptionName(html_entity_decode($iaInfo->fields['products_options']));
						$Itemoption->setOptionValue(html_entity_decode($iaInfo->fields['products_options_values']));
						$total_option_price = $iInfo->fields['products_quantity']*$iaInfo->fields['options_values_price'];
						$Itemoption->setOptionPrice("(".$iaInfo->fields['price_prefix'].$total_option_price.")");
						$Itemoption->setOptionWeight($iaInfo->fields['products_attributes_weight']);
						}
				}
				else
				{
				
				$Itemoption->setOptionName(html_entity_decode($iaInfo->fields['products_options']));
				$Itemoption->setOptionValue(html_entity_decode($iaInfo->fields['products_options_values']));
				$total_option_price = $iInfo->fields['products_quantity']*$iaInfo->fields['options_values_price'];
				$Itemoption->setOptionPrice("(".$iaInfo->fields['price_prefix'].$total_option_price.")");
				$Itemoption->setOptionWeight($iaInfo->fields['products_attributes_weight']);
				
				}
				$customsku =  html_entity_decode($customsku)? html_entity_decode($customsku).'+'.html_entity_decode($iaInfo->fields['products_options_values']) : html_entity_decode($iaInfo->fields['products_options_values']);
				$Item->setItemOptions($Itemoption->getItemoption());
				$iaInfo->MoveNext();					

			}
			unset($itemOptionsNode);
			
			}
			if($add_option_in_sku)
			$Item->setItemCode(empty($iInfo->fields['products_model'])? html_entity_decode($iInfo->fields['products_name']).'+'.$customsku :html_entity_decode($iInfo->fields['products_model']).'+'.$customsku);
			else
			$Item->setItemCode(empty($iInfo->fields['products_model'])? html_entity_decode($iInfo->fields['products_name']) : html_entity_decode($iInfo->fields['products_model']));
			
			
			$iInfo->MoveNext();
			$Order->setOrderItems($Item->getItem());	
			
		} // end items
		
		
		
		$rewardspoint_sql  =  "SELECT * from ".TABLE_ORDERS_TOTAL." WHERE orders_id =". $oInfo->fields['orders_id']." and class = 'ot_reward_points' ";
		$rewards = $db->Execute($rewardspoint_sql);
		if(isset($rewards->fields))
		{
			$reward_amt = $rewards->fields['value'];
		}
		else
		{
			$rewardspoint_sql  =  "SELECT * from ".TABLE_ORDERS_TOTAL." WHERE orders_id =". $oInfo->fields['orders_id']." and class = 'ot_sc' ";
			$rewards = $db->Execute($rewardspoint_sql);
			$reward_amt = $rewards->fields['value'];
		}
		
		if(isset($reward_amt) & $reward_amt!='')
		{
			$Item = new WG_Item();
			//$itemNode = $xmlResponse->createTag("Item",    array(), '',    $itemsNode);
			$Item->setItemCode('Redeemed Reward Points');
			$Item->setItemDescription('Redeemed Reward Points');
			$Item->setQuantity('1');
			$Item->setUnitPrice("-".$reward_amt);
			$Item->setWeight(0); 		
			$Item->setTaxExempt('N');
			$Order->setOrderItems($Item->getItem());	
			
		}

//problum

		$charges_query_raw = "SELECT SUM(value) as value_sum, class as value_class,title	FROM ".TABLE_ORDERS_TOTAL." 
		WHERE orders_id = ".$oInfo->fields['orders_id']."	GROUP BY class"; 
		
		$chInfo = $db->Execute($charges_query_raw);
		$is_tax = false;
		$is_shipping = false;
		$is_total = false;
		$is_discount = false;
		$charges =new WG_Charges();
		while (!$chInfo->EOF) 
		{
				
			$chInfo->fields =$this->parseSpecCharsA($chInfo->fields);
		
			if ($chInfo->fields['value_class'] == "ot_tax") 
			{
				$charges->setTax($chInfo->fields['value_sum']); 
				$is_tax = true;
			}
		
			if ($chInfo->fields['value_class'] == "ot_shipping") 
			{
				$charges->setShipping($chInfo->fields['value_sum']);
				$is_shipping = true;
			}
			if ($chInfo->fields['value_class'] == "ot_gv") 
			{
				//$xmlResponse->createTag("OtherCharge", array('Name'=>" $giftwrap "), $chInfo->fields['value_sum'], $chargesNode, __ENCODE_RESPONSE);
				$Item = new WG_Item();
				//$itemNode = $xmlResponse->createTag("Item",    array(), '',    $itemsNode);		
				$Item->setItemCode(substr($chInfo->fields['title'], 0, -1));
				$Item->setItemDescription(substr($chInfo->fields['title'], 0, -1));
				$Item->setQuantity('1');
				$Item->setUnitPrice( '-'.$chInfo->fields['value_sum']);
				$Item->setWeight(' 0'); 		
				$Item->setTaxExempt('N');
				$is_discount = true;
				$Order->setOrderItems($Item->getItem());	
			}
			if ($chInfo->fields['value_class']=="ot_total") 
			{
				$charges->setTotal((float)$chInfo->fields['value_sum']);
				$is_total = true;
			}
	
			if ($chInfo->fields['value_class']=="ot_coupon") 
			{
				
				//$discount_title = $chInfo->fields['title'];
				$discount_title = trim(str_replace('Discount Coupon',"",str_replace(':',"",$chInfo->fields['title'])));
				$discount_title = $discount_title?$discount_title: "Discount Coupon";
				
				$discount_value = $chInfo->fields['value_sum'];
				if($track_common_coupon_code == false)
				{
					
					$discount_sku = $discount_title;
				}	
				else
				{
					
					$discount_sku = 'Discount Coupon';
				} 
				
				if($discount_value != '' && $discount_value != '0.00')
				{
					$Item = new WG_Item();
				//$itemNode = $xmlResponse->createTag("Item",    array(), '',    $itemsNode);		
					$Item->setItemCode($discount_sku);
				    $Item->setItemDescription($discount_title);
					$Item->setQuantity('1');
					$Item->setUnitPrice( '-'.$discount_value);
					$Item->setWeight(' 0'); 		
					$Item->setTaxExempt('N');
					$is_discount = true;
					$Order->setOrderItems($Item->getItem());	
				
				}
			
			}
			
			if ($chInfo->fields['value_class']=="ot_combination_discounts")
			{
			
				$ot_combination_discounts_title = $chInfo->fields['title'];
				$ot_combination_discounts_value = $chInfo->fields['value_sum'];
			
			
				if($ot_combination_discounts_value != '' && $ot_combination_discounts_value != '0.00')
				{
					$Item = new WG_Item();
					//$itemNode = $xmlResponse->createTag("Item",    array(), '',    $itemsNode);
					$Item->setItemCode($ot_combination_discounts_title);
					$Item->setItemDescription($ot_combination_discounts_title);
					$Item->setQuantity('1');
					$Item->setUnitPrice( '-'.$ot_combination_discounts_value);
					$Item->setWeight('0');
					$Item->setTaxExempt('Y');
					$is_discount = true;
					$Order->setOrderItems($Item->getItem());
			
				}
			
			}
			unset($title);
			unset($value);
			if(($chInfo->fields['value_class']=="ot_fuelsurcharge") || ($chInfo->fields['value_class']=="ot_conveniencefee") || ($chInfo->fields['value_class']=="ot_loworderfee") || ($chInfo->fields['value_class']=="ot_custom") || ($chInfo->fields['value_class']=="ot_discount_coupon"))
			{
			
				$title = $chInfo->fields['title'];
				$value = $chInfo->fields['value_sum'];
			
				if($value != '' && $value != '0.00')
				{
					$Item = new WG_Item();
					//$itemNode = $xmlResponse->createTag("Item",    array(), '',    $itemsNode);
					$Item->setItemCode($title);
					$Item->setItemDescription($title);
					$Item->setQuantity('1');
					$Item->setUnitPrice($value);
					$Item->setWeight('0');
					$Item->setTaxExempt('Y');
					$is_discount = true;
					$Order->setOrderItems($Item->getItem());
			
				}
			
			}
			if ($chInfo->fields['value_class']=="ot_insurance")
			{
				$discount_title = $chInfo->fields['title'];
				$discount_value = $chInfo->fields['value_sum'];
				//$xmlResponse->createTag("Discount", array('Name'=>$chInfo->fields['title']), $chInfo->fields['value_sum'], $chargesNode, __ENCODE_RESPONSE);
				if($discount_value != '' && $discount_value != '0.00')
				{
					$Item = new WG_Item();
					$Item->setItemCode($chInfo->fields['title']);
					$Item->setItemDescription($chInfo->fields['title']);
					$Item->setQuantity('1');
					$Item->setUnitPrice($chInfo->fields['value_sum']);
					$Item->setWeight(0);
					$Item->setTaxExempt( 'N');
					$is_discount = true;
					$Order->setOrderItems($Item->getItem());	
				}
			
			}

			if ($chInfo->fields['value_class']=="ot_group_pricing") 
			{
				if ($is_discount ==true)
				{
					$discount_title .= ", ".$chInfo->fields['title'];
					$discount_value = $discount_value +  $chInfo->fields['value_sum'];
				}
				else
				{
					$discount_title = $chInfo->fields['title'];
					$discount_value = $chInfo->fields['value_sum'];
							
				}	
				if($discount_value != '' && $discount_value != '0.00')
				{
					$Item = new WG_Item();
					//$itemNode = $xmlResponse->createTag("Item",    array(), '',    $itemsNode);
					$Item->setItemCode($discount_title);
					$Item->setItemDescription($discount_title);
					$Item->setQuantity('1');
					$Item->setUnitPrice('-'.$discount_value);
					$Item->setWeight(0); 		
					$Item->setTaxExempt( 'N');
					$is_discount = true;
					$Order->setOrderItems($Item->getItem());	
					
				}
			}			
			$chInfo->MoveNext();
				
		
		} 
			//$Order->setOrderItems($Item->getItem());
			
			if (!$is_tax)
			   $charges->setTax("0"); 
			if (!$is_shipping)
			 $charges->setShipping("0"); 
			if (!$is_total)
			  $charges->setTotal("0"); 
			if (!$is_discount) 
			 $charges->setDiscount("0");
			
			$Order->setOrderChargeInfo($charges->getCharges());
			$oInfo->MoveNext(); 
			
			$Orders->setOrders($Order->getOrder());	
	} //orders
	
	 // print_r($Orders->getOrders());
return $this->response($Orders->getOrders());

	}
 
# retrive all order status
function getOrderStatus($username,$password)
{
	global $db;
	$WgBaseResponse = new WgBaseResponse();		
	$status =$this->auth_user($username,$password);
	#check for authorisation
	$status = $this->auth_user($username,$password);
	if($status!="0")
	{ //login name invalid
		if($status=="1")
		{		
			$WgBaseResponse->setStatusCode('1');
			$WgBaseResponse->setStatusMessage('Invalid login. Authorization failed');
			$response=$this->response($WgBaseResponse->getBaseResponse());
			return $response;		
		}
		if($status=="2")
		{ //password invalid
		
			$WgBaseResponse->setStatusCode('2');
			$WgBaseResponse->setStatusMessage('Invalid password. Authorization failed');
			$response=$this->response($WgBaseResponse->getBaseResponse());
			return $response;
		
		}
		if($status=="3")
		{ //Version Not Supported
			$WgBaseResponse->setStatusCode('2');
			$WgBaseResponse->setStatusMessage('Version Not Supported');
			$response=$this->response($WgBaseResponse->getBaseResponse());
			return $response;

		}
	}
	$query = "SELECT * FROM ".TABLE_ORDERS_STATUS." where language_id='1' and  orders_status_name!='' ";
	$iInfo = $db->Execute($query);	
//$pMethodNodes = $xmlResponse->createTag("OrderStatus", array(), '', $root);

	if(!$iInfo->EOF)
	{
		$OrderStatuses = new WG_OrderStatuses();
		$OrderStatuses->setStatusCode('0');
		$OrderStatuses->setStatusMessage('All Ok');	
		while (!$iInfo->EOF) 
		{
			$iInfo->fields = $this->parseSpecCharsA($iInfo->fields);
			$OrderStatus =new WG_OrderStatus();
			$OrderStatus->setOrderStatusID($iInfo->fields['orders_status_id']);
			$OrderStatus->setOrderStatusName($iInfo->fields['orders_status_name']);
			$iInfo->MoveNext();
			
		$OrderStatuses->setOrderStatuses($OrderStatus->getOrderStatus());
		}	
	}
		
	return $this->response($OrderStatuses->getOrderStatuses());
} 

# Returns all the shipping methods used by the store
function getShippingMethods($username,$password)
{
	global $db, $currencies;
	
	
	
	// create the shopping cart & fix the cart if necesary
	if (!$_SESSION['cart']) {
		$_SESSION['cart'] = new shoppingCart;
	}
	 
	## start response tag
	$WgBaseResponse = new WgBaseResponse();	
	$status = $this-> auth_user($username,$password);
	$ShippingMethods = new WG_ShippingMethods();
	
	#check for authorisation
	
	if($status!="0"){ //login name invalid
		if($status=="1"){		
			$WgBaseResponse->setStatusCode('1');
				$WgBaseResponse->setStatusMessage('Invalid user name. Authorization failed');
		}
		if($status=="2"){ //password invalid
				$WgBaseResponse->setStatusCode('2');
				$WgBaseResponse->setStatusMessage('Invalid password. Authorization failed');
		}
		if($status=="3"){ //Version Not Supported
				$WgBaseResponse->setStatusCode('2');
				$WgBaseResponse->setStatusMessage('Version Not Supported');
		}

		return $this->response($WgBaseResponse->getBaseResponse());
			
	}

	$language="english";
	$module_type = 'shipping';
    $module_directory = DIR_FS_CATALOG_MODULES . 'shipping/';
    $module_key = 'MODULE_SHIPPING_INSTALLED';
	
	# read directory file 
	$file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
	$directory_array = array();
	
	if ($dir = @dir($module_directory))
	{
		while ($file = $dir->read())
		{
			if (!is_dir($module_directory."".$file))
			{
			if($file!='upsxml_error.log')
			{
				$ext = explode(".",strrev($file));
				$ext = $ext[0]; 
				if(strtolower($ext)=='php')
				{
					$directory_array[] = $file;
				}
			}
			}
		}
		sort($directory_array);
		$dir->close();
	}

	$carriers_code["OTH"] = "Defined shipping methods";
	
			$ShippingMethod = new WG_ShippingMethod();
			$ShippingMethods->setStatusCode('0');
			$ShippingMethods->setStatusMessage('All Ok');	

	for ($i=0, $n=sizeof($directory_array); $i<$n; $i++)
	{
	
 		$file = $directory_array[$i];
	
		
	
		include(DIR_FS_CATALOG_LANGUAGES . $language . '/modules/' . $module_type . '/' . $file);
		include($module_directory . $file);
			
		$class = substr($file, 0, strrpos($file, '.'));
		
		/* if (zen_class_exists($class)) #COMPLIANCE 1.5.5
		{ */
		
			
 			$module = new $class;
						
 				
			$ShippingMethod->setCarrier(htmlentities( strip_tags($module->title)));
						
			switch ($class) 
			{
			
 				case "flat":
				case "table":
				case "item":
				{
					$m_array =$module->quote();
					
					foreach($m_array as $methods)
					{
						foreach($methods as $method)
						{
						   
							$method_name = $method['title'];
							
							if(empty($method_name))
							$method_name = $module->title;
							
							$ShippingMethod->setMethods($method_name);
						}
						
					};
					
					unset($m_array,$methods,$method_name);
				}
				break;
				
			
				case "freeoptions":			
					$m_array_freeoptions = array('id' => $module->code,
								'module' => MODULE_SHIPPING_FREEOPTIONS_TEXT_TITLE,
								'methods' => array(array('id' => $module->code,
														 'title' =>  MODULE_SHIPPING_FREEOPTIONS_TEXT_WAY ,
														 'cost' => SHIPPING_HANDLING + MODULE_SHIPPING_FREEOPTIONS_COST)));
						foreach($m_array_freeoptions as $array_foshipper)
						{
					
						
							
							foreach($array_foshipper as $method_shipper)
							{ 
								
								$method_name = $method_shipper['title'];
								if(empty($method_name))
								$method_name = $module->title;
								
								$ShippingMethod->setMethods($method_name);
								unset($method_name );
							}
							
						}
					unset($m_array_freeoptions,$array_foshipper,$method_shipper,$method_name);							 
														 
				break;
				case "freeshipper":
				{
								
					$m_array_fshipper = array('id' => $module->code,
								'module' => MODULE_SHIPPING_FREESHIPPER_TEXT_TITLE,
								'methods' => array(array('id' => $module->code,
														 'title' =>  MODULE_SHIPPING_FREESHIPPER_TEXT_WAY ,
														 'cost' => SHIPPING_HANDLING + MODULE_SHIPPING_FREESHIPPER_COST)));
	
					foreach($m_array_fshipper as $array_fshipper)
					{
					
						foreach($array_fshipper as $method_shipper)
						{ 
						
						
							$method_name = $method_shipper['title'];
							if(empty($method_name))
								$method_name = $module->title;

								$ShippingMethod->setMethods($method_name);
								unset($method_name );
						}
						
					}
					unset($m_array_fshipper,$array_fshipper,$method_shipper,$method_name);
				}
				break;
									
				case "perweightunit":					
				{
				
				
					$m_array_indvship = array('id' => $module->code,
								'module' => MODULE_SHIPPING_PERWEIGHTUNIT_TEXT_TITLE,
								'methods' => array(array('id' => $module->code,
														 'title' => MODULE_SHIPPING_PERWEIGHTUNIT_TEXT_WAY,
														 'cost' => $shiptotal)));;
					
					foreach($m_array_indvship as $array_indvship)
					{
						foreach($array_indvship as $method_indvship)
						{
					
						$method_name = $method_indvship['title'];
						if(empty($method_name))
							$method_name = $module->title;
						$ShippingMethod->setMethods($method_name);
						unset($method_name );
						}
						
					} //echo $method_name;die;
					unset($m_array_indvship,$array_indvship,$method_indvship,$method_name); 
				}
				break;
				case "storepickup":					
				{
				
				
					$m_array_storepickup = array('id' => $module->code,
								'module' => MODULE_SHIPPING_STOREPICKUP_TEXT_TITLE,
								'methods' => array(array('id' => $module->code,
														 'title' => MODULE_SHIPPING_STOREPICKUP_TEXT_WAY,
														 'cost' => $shiptotal)));;
					
					foreach($m_array_storepickup as $array_storepickship)
					{
						foreach($array_storepickship as $method_indvship)
						{
						$method_name = $method_indvship['title'];
						if(empty($method_name))
							$method_name = $module->title;
						$ShippingMethod->setMethods($method_name);
						}
						
					} //echo $method_name;die;
					unset($m_array_indvship,$array_indvship,$method_indvship,$method_name); 
				}
				break;
				
				case "usps":
				{
				
					$m_array_usps = $module->intl_types;
					
					if(is_array($m_array_usps))
						foreach($m_array_usps as $method_name)
						{ 
							if(empty($method_name))
								$method_name = $module->title;	
								
							$ShippingMethod->setMethods($method_name);
						} 
					else
						$ShippingMethod->setMethods( $module->title);
					unset($m_array_usps,$method_name); 
					
					$m_array_usps = $module->types;
					if(is_array($m_array_usps))
						foreach($m_array_usps as $method_name)
						{ 
							if(empty($method_name))
								$method_name = $module->title;							
							$ShippingMethod->setMethods($method_name);
						} 
					else
						$ShippingMethod->setMethods($module->title);
					unset($m_array_usps,$method_name); 
				}
				
				break;
				case "ups":
				{
				
					$m_array_ups = $module->types;			
					
					if(is_array($m_array_ups))
						foreach($m_array_ups as $method_name)
						{
								
							$ShippingMethod->setMethods($method_name);
							
						} 
					else
							$ShippingMethod->setMethods($module->title); 
				}
				break;
				
				case "zones":
				{
				
					$array_zones = $module->quote();		
					if(is_array($array_zones))
					foreach($array_zones as $zones_array)
					{
						foreach($zones_array as $zone)
						{
							$method_name = $zone['title'];
							if(empty($method_name))
								$method_name = $module->title;
								
							$ShippingMethod->setMethods( $method_name);
							unset($method_name);
						}
						
					}	
				unset($array_zones,$zones_array,$zone,$method_name); 
				}
				
				break;
					default:
					
						$ShippingMethod->setMethods("");
							break;
			
			
		}	 //switch	
			
		$ShippingMethods->setShippingMethods($ShippingMethod->getShippingMethod());	
		
		//} // if #COMPLIANCE 1.5.5
		
	}
						
	   return $this->response($ShippingMethods->getShippingMethods());
			
}


# Function returns All the Payment Methods used by the store

function getPaymentMethods($username,$password)
{

	## start response tag
		$WgBaseResponse = new WgBaseResponse();	
	    $PaymentMethods = new WG_PaymentMethods();
		$status = $this->auth_user($username,$password);
	
	if($status!="0"){ //login name invalid
		if($status=="1"){
			$WgBaseResponse->setStatusCode('1');
			$WgBaseResponse->setStatusMessage('Invalid login. Authorization failed');
			$response=$this->response($WgBaseResponse->getBaseResponse());
			return $response;
		}
		if($status=="2"){ //password invalid
			$WgBaseResponse->setStatusCode('2');
			$WgBaseResponse->setStatusMessage('Invalid password. Authorization failed');
			$response=$this->response($WgBaseResponse->getBaseResponse());
			return $response;
		}
		if($status=="3"){ //Version Not Supported
			$WgBaseResponse->setStatusCode('2');
			$WgBaseResponse->setStatusMessage('Version Not Supported');
			$response=$this->response($WgBaseResponse->getBaseResponse());
			return $response;
		}
		
	}	
	$language="english";
	$module_type = 'payment';
	$module_directory = DIR_FS_CATALOG_MODULES . 'payment/';
	
	$module_key = 'MODULE_PAYMENT_INSTALLED';
	$file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
	$directory_array = array();
	if ($dir = @dir($module_directory))
	{
		while ($file = $dir->read())
		{
			if (!is_dir($module_directory."".$file))
			{
				$ext = explode(".",strrev($file));
				$ext = $ext[0]; 
				if(strtolower($ext)=='php')
				{
					$directory_array[] = $file;
				}
			}
		}
		
		
		sort($directory_array);
		$dir->close();
	}
	
	## loop to featch payment records	
		$PaymentMethods->setStatusCode('0');		 
		$PaymentMethods->setStatusMessage('All Ok');	
	
	for ($i=0, $n=sizeof($directory_array); $i<$n; $i++)
	{		

		$file = $directory_array[$i];
		
		$mypaymethod = DIR_FS_CATALOG_LANGUAGES . $language . '/modules/' . $module_type . '/' . $file;
		
		if (file_exists($mypaymethod))
		{
			include($mypaymethod);
			
			if(file_exists($module_directory.$file)) include($module_directory.$file);	
			
			$class = substr($file, 0, strrpos($file, '.'));	
			
			if ($class!="" && class_exists($class)) 
			{
				 $module = new $class;								
				//if ($module->check())
				//{	
				
				$PaymentMethod = new WG_PaymentMethod();				
				$PaymentMethod->setMethodId($i+1);
				$PaymentMethod->setMethod(htmlspecialchars(htmlentities( strip_tags($module->title))));
				$PaymentMethod->setDetail (htmlspecialchars( htmlentities( strip_tags($module->description))));	
				$PaymentMethods->setPaymentMethods($PaymentMethod->getPaymentMethod());
						
				//}
						
			}
		}
	}// end of for loop	
	
		
	
	return $this->response($PaymentMethods->getPaymentMethods());	
}

//function getItems($username,$password,$start,$limit)
function getItems($username,$password,$start_item_no=0,$limit=500,$UpdatedDate,$datefrom,$storeId=1,$others) 
{
    global $db;
	$MySqlSafe_obj=new Wg_MySqlSafe(); 
	$WgBaseResponse = new WgBaseResponse();		
	$status =$this->auth_user($username,$password);
	$Items = new WG_Items();

	if($status!="0"){ //login name invalid
	
		if($status=="1"){
		$WgBaseResponse->setStatusCode('1');
		$WgBaseResponse->setStatusMessage('Invalid login. Authorization failed');
		}	
		if($status=="2"){ //password invalid
		$WgBaseResponse->setStatusCode('2');
		$WgBaseResponse->setStatusMessage('Invalid password. Authorization failed');
		}
		if($status=="3"){ //Version Not Supported
		$WgBaseResponse->setStatusCode('2');
			$WgBaseResponse->setStatusMessage('Version Not Supported');	
		}
		return $this->response($WgBaseResponse->getBaseResponse());		 
	}
	
	$query_for_update="";
	if($UpdatedDate!="")
	{
	
		list($mm,$dd,$yy)=explode("/",$UpdatedDate);
		list($yy_val,$time)=explode(" ",$yy);
		$time_from = $yy_val."-".$mm."-".$dd;
	
		 $query_for_update=" AND (". TABLE_PRODUCTS .".products_last_modified >= '".$time_from."' OR ". TABLE_PRODUCTS .".products_last_modified IS NULL )";
	
	}
	

$iInfo = $db->Execute("SELECT DISTINCT ". TABLE_PRODUCTS .". * , ". TABLE_PRODUCTS_DESCRIPTION . ". *, if(tax_rate > 0 ,1,NULL) as tax_rate  FROM ". TABLE_PRODUCTS ."
	
	INNER JOIN ". TABLE_PRODUCTS_DESCRIPTION . " ON ". TABLE_PRODUCTS .".products_id = ". TABLE_PRODUCTS_DESCRIPTION . ".products_id LEFT JOIN " . TABLE_SPECIALS . " ON  " . TABLE_SPECIALS . ".products_id =  ". TABLE_PRODUCTS .".products_id	
	LEFT JOIN " . TABLE_TAX_RATES . " ON  " . TABLE_TAX_RATES . ".tax_class_id =  ". TABLE_PRODUCTS .".products_tax_class_id  WHERE ". TABLE_PRODUCTS_DESCRIPTION . ".language_id =1 and ". TABLE_PRODUCTS .".products_status=1 and (". TABLE_PRODUCTS .".products_date_available is null or ". TABLE_PRODUCTS .".products_date_available < now()) ".$query_for_update." ");
 
 $total_record = $iInfo->RecordCount();
	
	
	
				
				$items_query_raw ='';
                                //$others[0]['ItemCode'] = "'titanic 22','titanic 11'";
				if(isset($others[0]['ItemCode']) && trim($others[0]['ItemCode'])!='')
				{
					$items_query_raw = " AND ".TABLE_PRODUCTS.".products_model in (".trim($others[0]['ItemCode']).")";
				}
				
	
	$iInfo = $db->Execute("SELECT DISTINCT ". TABLE_PRODUCTS .". * , ". TABLE_PRODUCTS_DESCRIPTION . ". *, if(tax_rate > 0 ,1,NULL) as tax_rate  FROM ". TABLE_PRODUCTS ."
	
	INNER JOIN ". TABLE_PRODUCTS_DESCRIPTION . " ON ". TABLE_PRODUCTS .".products_id = ". TABLE_PRODUCTS_DESCRIPTION . ".products_id LEFT JOIN " . TABLE_SPECIALS . " ON  " . TABLE_SPECIALS . ".products_id =  ". TABLE_PRODUCTS .".products_id	
	LEFT JOIN " . TABLE_TAX_RATES . " ON  " . TABLE_TAX_RATES . ".tax_class_id =  ". TABLE_PRODUCTS .".products_tax_class_id  WHERE ". TABLE_PRODUCTS_DESCRIPTION . ".language_id =1 ".$items_query_raw." and ". TABLE_PRODUCTS .".products_status=1 and (". TABLE_PRODUCTS .".products_date_available is null or ". TABLE_PRODUCTS .".products_date_available < now()) ".$query_for_update." limit $start_item_no,$limit");
	
	
	
	
	
	if (!$iInfo->EOF) {	
		$Items->setStatusCode('0');
		$Items->setStatusMessage('All Ok');
		$Items->setTotalRecordFound($total_record);		
		
		while (!$iInfo->EOF) {
		
			//$itemNode = $xmlResponse->createTag("Item",    array(), '',    $itemsNode);			
			$iInfo->fields = $this->parseSpecCharsA($iInfo->fields);			
			$Item = new WG_Item();			
			$Item->setItemID($iInfo->fields['products_id']);
			$Item->setItemCode (empty($iInfo->fields['products_model'])?html_entity_decode($iInfo->fields['products_name']):html_entity_decode($iInfo->fields['products_model']));
			$Item->setItemDescription(html_entity_decode(strip_tags($iInfo->fields['products_name'])));			
			$desc=(substr($iInfo->fields['products_description'],0,4000));
			$Item->setItemShortDescr(strip_tags(html_entity_decode($desc)));	
			$category_q = $db->Execute("SELECT ptc.categories_id , c.parent_id , cd.categories_name  from ".TABLE_PRODUCTS_TO_CATEGORIES." as ptc LEFT JOIN ".TABLE_CATEGORIES." as c on ptc.categories_id = c.categories_id LEFT JOIN ".TABLE_CATEGORIES_DESCRIPTION." as cd on cd.categories_id = ptc.categories_id where ptc.products_id ='".$iInfo->fields['products_id']."'");
			
			while (!$category_q->EOF) 
				{
					$Category= new WG_Category();				
					//$categoryNode = $xmlResponse->createTag("Category",    array(), '',    $categoriesNode,__ENCODE_RESPONSE);			
					$Category->setCategoryName($category_q->fields['categories_name']);
					$Category->setCategoryID($category_q->fields['categories_id']);
					$Category->setParentID($category_q->fields['parent_id']);
					$Item->setCategories($Category->getCategory());	
					$category_q->MoveNext();	
				}
			
			$manfacture_q = $db->Execute("select manufacturers_name as manufacturer from ".TABLE_MANUFACTURERS." where manufacturers_id = '".$iInfo->fields['manufacturers_id']."'");
			$manufacturer_count = true;
			if (!$manfacture_q->EOF)
				{
					$manufacturer_count = false;
					$Item->setManufacturer(htmlspecialchars($manfacture_q->fields['manufacturer']));	
				}
			if($manufacturer_count )
			$Item->setManufacturer("");	
			$Item->setQuantity($iInfo->fields['products_quantity']);			
	if($iInfo->fields['product_is_free']==1 || $iInfo->fields['product_is_call']==1)
			$iInfo->fields['products_price']="0.00";
			
			$Item->setUnitPrice((float)$iInfo->fields['products_price']);	
			$Item->setListPrice((float)$iInfo->fields['products_price']);	
			$Item->setWeight((float)$iInfo->fields['products_weight']);
			$Item->setLowQtyLimit((float)$iInfo->fields['products_quantity_order_min']);
			$FreeShipping=($iInfo->fields['product_is_always_free_shipping']==1)?"Y":"N";
			$Item->setFreeShipping($FreeShipping);			
			$Item->setDiscounted((float)0.0);
			$Item->setShippingFreight($iInfo->fields['shipping_freight']);
			$Item->setWeight_Symbol("lbs");
			$Item->setWeight_Symbol_Grams('453.6');
			
			$time = strtotime($iInfo->fields['products_date_added']);
			$Item->setCreatedAt(date('d-m-Y',$time));
			
			if($iInfo->fields['tax_rate']>0)
			{
				//$Item->setTaxExempt('N');
				$Item->setTaxExempt('Y');
			}else{
				//$Item->setTaxExempt('Y');
				$Item->setTaxExempt('N');
			}
	
			$ioInfo  = $db->Execute("SELECT DISTINCT  a. * , po.products_options_name, pov.products_options_values_name
	FROM " . TABLE_PRODUCTS_ATTRIBUTES  ." a INNER JOIN " . TABLE_PRODUCTS_OPTIONS  . " po ON po.products_options_id = a.options_id INNER JOIN " . TABLE_PRODUCTS_OPTIONS_VALUES  . "  pov ON pov.products_options_values_id = a.options_values_id AND pov.language_id = '1' AND po.language_id = '1' AND a.products_id =".$iInfo->fields['products_id']);
	
			$c=1; //counter
			$Itemoption = new WG_Itemoption();
			while (!$ioInfo->EOF) 
			{
			
			$ivInfo->fields =$this->parseSpecCharsA($ivInfo->fields);	
			$Itemoption->setOptionID($ioInfo->fields['products_attributes_id']);
			$Itemoption->setOptionValue(htmlspecialchars(strip_tags($ioInfo->fields['products_options_values_name'])));
			$Itemoption->setOptionName(htmlspecialchars(strip_tags($ioInfo->fields['products_options_name'])));
			$ioInfo->MoveNext();
			$Item->setItemOptions($Itemoption->getItemoption());
			$ioInfo->MoveNext();
			} // end of while for variants
		
		 $Items->setItems($Item->getItem());
		
		$iInfo->MoveNext();
		} //end of item while
		 
		
	} //end of if records
	
	return $this->response($Items->getItems());
}




# Returns the Company Info of the Store

function getCompanyInfo($username,$password)

{
	global $config,$db;
	$CompanyInfo = new WG_CompanyInfo();
	$WgBaseResponse = new WgBaseResponse();	
	$status = $this->auth_user($username,$password);
	if($status!="0"){ //login name invalid
		if($status=="1"){		
			$WgBaseResponse->setStatusCode('1');
			$WgBaseResponse->setStatusMessage('Invalid login. Authorization failed');
			
		}
		if($status=="2"){ //password invalid
			$WgBaseResponse->setStatusCode('2');
			$WgBaseResponse->setStatusMessage('Invalid password. Authorization failed');
			
		}
			if($status=="3"){ //Version Not Supported
			$WgBaseResponse->setStatusCode('2');
			$WgBaseResponse->setStatusMessage('Version Not Supported');
						
	}
	$response=$this->response($WgBaseResponse->getBaseResponse());
	return $response;	
	
}

	if (count(explode("\n",$config['STORE_NAME_ADDRESS']))==4)
	list($storename,$storeaddr,$country,$phone)= explode("\n",$config['STORE_NAME_ADDRESS']);	
	else
	$storename = $config['STORE_NAME_ADDRESS'];
	$CompanyInfo->setStatusCode('0');
	$CompanyInfo->setStatusMessage("All Ok");
	$CompanyInfo->setStoreID(STORE_NAME_ID);
	$CompanyInfo->setStoreName(htmlspecialchars($storename, ENT_QUOTES));
	$CompanyInfo->setAddress(htmlspecialchars($storeaddr, ENT_QUOTES));
	$CompanyInfo->setAddress2('');
	$CompanyInfo->setcity('');
	$CompanyInfo->setState(htmlspecialchars($this->getConfigvalue("STORE_ZONE",TABLE_ZONES,"zone_id","zone_name"), ENT_NOQUOTES)); //date
	$CompanyInfo->setCountry(htmlspecialchars($this->getConfigvalue("STORE_COUNTRY",TABLE_COUNTRIES,"countries_id","countries_name"), ENT_NOQUOTES)); //time
		
	
			
	$CompanyInfo->setZipcode(htmlspecialchars($config['SHIPPING_ORIGIN_ZIP']?$config['SHIPPING_ORIGIN_ZIP']:"", ENT_NOQUOTES));
	//}
	$CompanyInfo->setPhone(htmlspecialchars($phone, ENT_QUOTES));
	$CompanyInfo->setFax("");	
    $CompanyInfo->setEmail(htmlspecialchars($config['STORE_OWNER_EMAIL_ADDRESS'],ENT_NOQUOTES));	
	$CompanyInfo->setWebsite(HTTP_CATALOG_SERVER);
	
	return $this->response($CompanyInfo->getCompanyInfo());	
}


#***********************************************************
# Function to check the admin username and password and also the eCC Version and Store Version
function checkAccessInfo($username,$password)
{ 
	global $config;
	global $currentVersion;
	$WgBaseResponse = new WgBaseResponse();	
	$status = $this->auth_user($username,$password);
	#check for authorisation
	
	if($status!="0"){ //login name invalid
		if($status=="1"){
						
			$WgBaseResponse->setStatusCode('1');
			$WgBaseResponse->setStatusMessage('Invalid login. Authorization failed');
			return $response=$this->response($WgBaseResponse->getBaseResponse());
		}
		if($status=="2"){ //password invalid
		
			$WgBaseResponse->setStatusCode('2');
			$WgBaseResponse->setStatusMessage('Invalid password. Authorization failed');
			return $response=$this->response($WgBaseResponse->getBaseResponse());
		}		

	}else
	 {
		$code = "0";
		$message = "Successfully connected to your online store.";
		$WgBaseResponse->setStatusCode($code);
		$version = $this->getVersion();
		if($version!="0")
		{
			//if ($version >='1.3.0' && $version <='1.3.9h' )
			if ($version >='1.0' && $version <='1.5.5f' )
			{
				$WgBaseResponse->setStatusMessage($message);
				return $response=$this->response($WgBaseResponse->getBaseResponse());
			}
			else
			{
			$WgBaseResponse->setStatusMessage($message ." However, your store version is " . $version ." which hasn't been fully tested with webgility. If you'd still like to continue, click OK to continue or contact Webgility to confirm compatibility.");
			return $response=$this->response($WgBaseResponse->getBaseResponse());
				
			}
		}
		else
		{
			$WgBaseResponse->setStatusMessage($message ." However, Webgility is unable to detect your store version. If you'd still like to continue, click OK to continue or contact Webgility to confirm compatibility.");
			return $response=$this->response($WgBaseResponse->getBaseResponse());

		}
	}
}

# private function to authenticate user with every method.
function auth_user($username,$password)
{
	ini_set('display_errors' , 'On');
	global $db;
	// check for installed admin auth contribution and do authentification.
	
	$sql_str = "SHOW TABLES LIKE '%admin'";    
	$rows = $db->Execute($sql_str);
	
	if (!$rows->EOF) 
	{
		$sql_str = "SHOW COLUMNS FROM ".TABLE_ADMIN." LIKE 'admin_email'";
		$rows = $db->Execute($sql_str);		
	
		if (!$rows->EOF) 
		{
			
		
			$MySqlSafe_obj=new Wg_MySqlSafe(); 			
			$username=$MySqlSafe_obj->mySQLSafe($username);	
		
			$sql_str = "SELECT admin_pass FROM ".TABLE_ADMIN." WHERE admin_name=".$username.""; 
			$rows = $db->Execute($sql_str);				
			if(!$rows->EOF)
			{
				
				if (zen_validate_password($password, $rows->fields['admin_pass'])) 
					return "0";
				else 
				
					return "2";
					
			}
			else
			{
			
				return "1";
			}
		}	
	}
	else
	{
		return 0;
	}
	
}

# To Check the Version Compatibility

function isValidVersion()
{
global $db;
$currentVersion = getVersion();

if ($currentVersion >='1.3.0' && $currentVersion <='1.3.8a')
{
return true;
}
else
{
return false;
}
}

# Function returns the shopping cart version.
function getVersion()
{
if(file_exists(DIR_FS_CATALOG . DIR_WS_INCLUDES . 'version.php'))
{
include(DIR_FS_CATALOG . DIR_WS_INCLUDES . 'version.php');

if(defined('PROJECT_VERSION_MAJOR') && defined('PROJECT_VERSION_MINOR'))
{
$version = PROJECT_VERSION_MAJOR . '.' . PROJECT_VERSION_MINOR;
return $version;
}
else
{
return "0";
}
}
else
{
return "0";
}

}


function getCustomersNew($username,$password,$datefrom,$customerid,$limit,$storeid=1,$others)
 {
 	ini_set('display_errors' , 'Off');
 	 global $db;
 
	$MySqlSafe_obj=new Wg_MySqlSafe(); 
	
	$datefrom =$datefrom ?$datefrom:0;
	$status =$this->auth_user($username,$password);
 	
	if($status !='0')
	{
		return $status;
	}
	$Customers = new WG_Customers();
	
	$cust_count_arr = $db->Execute("SELECT COUNT(*) as cnt
			FROM ".TABLE_CUSTOMERS." c LEFT JOIN ".TABLE_ADDRESS_BOOK."  ab ON c.customers_default_address_id=ab.address_book_id AND c.customers_id=ab.customers_id 	LEFT JOIN ".TABLE_CUSTOMERS_INFO." ci ON c.customers_id =ci.customers_info_id ");
	$total_count = $cust_count_arr->fields['cnt'];
	
	
	//$customersArray = $this->getCustomer($customerid,$datefrom,$storeId,$limit);
	$start_no = 0;
	
	$row = $db->Execute("SELECT c.customers_id as custid,c.customers_firstname,c.customers_newsletter ,c.customers_lastname,c.customers_email_address,c.customers_default_address_id,c.customers_telephone,ab.*,ci.customers_info_date_account_created,ci.customers_info_date_account_last_modified
			FROM ".TABLE_CUSTOMERS." c LEFT JOIN ".TABLE_ADDRESS_BOOK."  ab ON c.customers_default_address_id=ab.address_book_id AND c.customers_id=ab.customers_id 	LEFT JOIN ".TABLE_CUSTOMERS_INFO." ci ON c.customers_id =ci.customers_info_id
			WHERE c.customers_id >".intval($customerid)." order by c.customers_id ASC LIMIT ".$start_no.",".$limit."");
	
	while (!$row->EOF)
	{
		$customersArray[]=$row->fields;
		$row->MoveNext();
	} 
	
	
	$no_customer =false;
	if($total_count<0)
	{
		$no_customer = true;
	}

	$Customers->setStatusCode($no_customer?"0":"0");
	$Customers->setStatusMessage($no_customer?"No Customer returned":"Total Customer:".count($customersArray));
	$Customers->setTotalRecordFound($total_count?$total_count:'0');
	$Customers->setTotalRecordSent(count($customersArray)?count($customersArray):'0');


	foreach($customersArray as $customer)
	{
		$Customer = new WG_Customer();
		$Customer->setCustomerId($customer["custid"]);
		$Customer->setFirstName($customer["customers_firstname"]);
		$Customer->setMiddleName('');
		$Customer->setLastName($customer["customers_lastname"]);
		$Customer->setCustomerGroup('');
		$Customer->setcompany($customer["entry_company"]);
		$Customer->setemail($customer["customers_email_address"]);
		if($customer['customers_newsletter']==1) {
			$Customer->setsubscribedToEmail("true");
		}else{
			$Customer->setsubscribedToEmail("false");
		}
		$Customer->setAddress1($customer["entry_street_address"]);
		$Customer->setAddress2($customer['entry_suburb']);
		$Customer->setCity($customer["entry_city"]);
		$country_code = $customer['entry_country_id'];
		if(isset($country_code))
		{
			$rows = $db->Execute("SELECT countries_name FROM ".TABLE_COUNTRIES." WHERE countries_id = $country_code");
			//
			if(!$rows->EOF)
			{
				$countryName=$rows->fields['countries_name'];
			
			}
			$zone_id = $customer['entry_zone_id'];
			$rows2 = $db->Execute("SELECT zone_name FROM ".TABLE_ZONES." WHERE zone_country_id = ".$customer['entry_country_id']." AND zone_id = ".$zone_id ." ");
			//
			if(!$rows2->EOF)
			{
				$StateName=$rows2->fields['zone_name'];
			
			}
		}
		//$Customer->setState($customer["entry_state"]);
		$Customer->setState($StateName);
		$Customer->setZip($customer["entry_postcode"]);
		
		
		
		$Customer->setCountry($countryName);
		$Customer->setPhone($customer["customers_telephone"]);
		if(!isset($customer["customers_info_date_account_created"]) || $customer["customers_info_date_account_created"]=='') {
		$customer["customers_info_date_account_created"]='2007-01-01 00:00:00' ;
		}
		if(!isset($customer["customers_info_date_account_last_modified"]) || $customer["customers_info_date_account_last_modified"]=='') {
		$customer["customers_info_date_account_last_modified"]='2007-01-01 00:00:00' ;
		}
		$Customer->setCreatedAt($customer["customers_info_date_account_created"]);
		$Customer->setUpdatedAt($customer["customers_info_date_account_last_modified"]);

		$Customers->setCustomer($Customer->getCustomer());

	}


	return $this->response($Customers->getCustomers());
}

function getCustomer($customerid,$datefrom,$storeId,$limit)
{
	global $db;
	
	
	$start_no = 0;

	$row = $db->Execute("SELECT c.customers_id,c.customers_firstname ,c.customers_lastname,c.customers_email_address,c.customers_default_address_id,c.customers_telephone,ab.*,ci.customers_info_date_account_created,ci.customers_info_date_account_last_modified
			FROM ".TABLE_CUSTOMERS." c LEFT JOIN ".TABLE_ADDRESS_BOOK."  ab ON c.customers_default_address_id=ab.address_book_id AND c.customers_id=ab.customers_id 	LEFT JOIN ".TABLE_CUSTOMERS_INFO." ci ON c.customers_id =ci.customers_info_id
			WHERE c.customers_id >".$customerid." order by c.customers_id ASC LIMIT ".$start_no.",".$limit."");
	
	while (!$row->EOF)
	{
		$customersDataArray[]=$row->fields;
		$row->MoveNext();
	}
	return $customersDataArray;
}
public function addCustomers($username,$password,$data,$storeid=1,$others='') 
{


	global $db,$config;
 	
	//DEFINE('SEND_EMAILS', $config['SEND_EMAILS']);
	
	DEFINE('SEND_EMAILS',true);
	DEFINE('EMAIL_USE_HTML', $config['EMAIL_USE_HTML']);
	define('STORE_OWNER_EMAIL_ADDRESS',  $config["STORE_OWNER_EMAIL_ADDRESS"]);
	DEFINE('STORE_OWNER',  $config["STORE_OWNER"]);
	define('EMAIL_FROM' , $config["EMAIL_FROM"]);
	if (!defined('EMAIL_TRANSPORT'))
	{
		DEFINE('EMAIL_TRANSPORT', 'PHP');
	}
	if (!defined('EMAIL_SEND_MUST_BE_STORE'))
	{
		DEFINE('EMAIL_SEND_MUST_BE_STORE', 'Yes');
	}
	include_once ('../includes/functions/functions_email.php') ;
	
	include_once ('../includes/languages/english/create_account.php') ;
	

	$MySqlSafe_obj=new Wg_MySqlSafe(); 
//
	$datefrom =$datefrom ?$datefrom:0;

	$status = $this->auth_user($username,$password);
	if($status !='0')
	{
		return $status;
	}
	$Customers = new WG_Customers();
	$Customers->setStatusCode('0');
	$Customers->setStatusMessage('All Ok');

	$requestArray = $data;
	//$requestArray = json_decode($item_json_array, true);
	if (!is_array($requestArray)) {
		$Items->setStatusCode('9997');
		$Items->setStatusMessage('Unknown request or request not in proper format');
		return $this->response($Items->getItems());
	}

	if (count($requestArray) == 0) {
		$Items->setStatusCode('9996');
		$Items->setStatusMessage('REQUEST tag(s) doesnt have correct input format');
		return $this->response($Items->getItems());
	}



		foreach($requestArray as $k=>$vCustomer) 
		{
		
		//print_r($vCustomer);
		$Email			=	$vCustomer['Email'];
		$CustomerId		=	$vCustomer['CustomerId'];
		$firstname		=	$vCustomer['FirstName'];
		$middlename		=	$vCustomer[''];
		$lastname		=	$vCustomer['LastName'];
		$company		=	$vCustomer['Company'];
		$street1		=	$vCustomer['Address1'];
		$street2		=	$vCustomer['Address2'];
		$city			=	$vCustomer['City'];
		$state_name		=	$vCustomer['State'];
		$postcode		=	$vCustomer['Zip'];
		$country_name	=	$vCustomer['Country'];
		$phone			=	$vCustomer['Phone'];
		$membershipid	=	$vCustomer['CustomerGroup'];
		$password	=	md5(rand(6,10));
		
		
		
		$user_login_arr = explode('@',$Email);
		$user_login = $user_login_arr[0];
		
		unset($existing_user);
		 $check_email = $db->Execute("select customers_email_address
		                                   from " . TABLE_CUSTOMERS . "
		                                   where customers_email_address = '" . zen_db_input($Email) . "' ");
		if ($check_email->RecordCount() > 0) {
		
		$existing_user = '1';
		}
		
	
		if($existing_user!= '1')
		{
		
		$password1 = zen_encrypt_password($password);
		
		 $sql_data_array = array('customers_firstname' => $firstname,
                            'customers_lastname' => $lastname,
                            'customers_email_address' => $Email,
                            'customers_nick' => '',
                            'customers_telephone' => $phone,
                            'customers_fax' => '',
                            'customers_newsletter' => '0',
                            'customers_email_format' => '',
                            'customers_default_address_id' => 0,
                            'customers_password' => $password1
                           // 'customers_authorization' => (int)CUSTOMERS_APPROVAL_AUTHORIZATION
    );
		
	zen_db_perform(TABLE_CUSTOMERS, $sql_data_array);

    $customer_id = $db->Insert_ID();
		
		
		
		
		
  		$country_q = $db->Execute("SELECT countries_id FROM " . TABLE_COUNTRIES . " WHERE (countries_name = '" .$country_name. "' OR countries_iso_code_2 = '" .$country_name. "' OR countries_iso_code_3 = '" .$country_name. "') ");
		
		if(!$country_q->EOF)
		{	$country_q->MoveNext();
			$countries_vid = $country_q->fields['countries_id'];
			 //
		}

		
		$rows2 = $db->Execute("SELECT zone_id FROM ".TABLE_ZONES." WHERE zone_country_id = '".$countries_vid."' AND zone_name  = '".$state_name ."' ");
		if(!$rows2->EOF)
		{
			$rows2->MoveNext();
 			$State_id=$rows2->fields['zone_id'];
			
		}

		

		$sql_data_array2 = array('customers_id' => $customer_id,
                            'entry_firstname' => $firstname,
                            'entry_lastname' => $lastname,
                            'entry_street_address' => $street1,
							'entry_company' => $company,
                            'entry_postcode' => $postcode,
                            'entry_city' => $city,
							'entry_zone_id'=>$State_id,
                            'entry_country_id' => $countries_vid);

		$sql_data_array2['entry_zone_id'] = $state_name;
		$sql_data_array2['entry_state'] = '';
		//zen_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array2);
		
		$sql = "insert into " . TABLE_ADDRESS_BOOK . "
		(customers_id, entry_firstname,	entry_lastname, entry_street_address,entry_suburb,entry_company,entry_postcode,entry_city,entry_zone_id,entry_country_id)
		values ('" . (int)$customer_id . "', '".$firstname."', '".$lastname."', '".$street1."','".$street2."','".$company."','".$postcode."','".$city."','".$State_id."','".$countries_vid."')";
		
		$db->Execute($sql);
		
		$db->Execute($sql);
		$address_id = $db->Insert_ID();
		$sql = "update " . TABLE_CUSTOMERS . "
		set customers_default_address_id = '" . (int)$address_id . "'
		where customers_id = '" . $customer_id . "'";
		
		$db->Execute($sql);
		
		$sql = "insert into " . TABLE_CUSTOMERS_INFO . "
		(customers_info_id, customers_info_number_of_logons,
		customers_info_date_account_created, customers_info_date_of_last_logon)
		values ('" . (int)$customer_id . "', '1', now(), now())";
		
		$db->Execute($sql);
		//$vCustomer['IsNotifyCustomer'] = 'Y';
		if(isset($vCustomer['IsNotifyCustomer']) && $vCustomer['IsNotifyCustomer']=='Y')
		{
// 		// build the message content
		$name = $firstname . ' ' . $lastname;
		$email_address =$Email;
		$email_text = sprintf(EMAIL_GREET_NONE, $firstname);
		
		$html_msg['EMAIL_GREETING'] = str_replace('\n','',$email_text);
		$html_msg['EMAIL_FIRST_NAME'] = $firstname;
		$html_msg['EMAIL_LAST_NAME']  = $lastname;
		
		// initial welcome
		$email_text .=  EMAIL_WELCOME;
		$html_msg['EMAIL_WELCOME'] = str_replace('\n','',EMAIL_WELCOME);
	
		define('SEND_EMAIL_ADDRESS', $email_address);
		
		define('SEND_PASSWORD', $password);
		
	
		define('SEND_ACCOUNT_INFO',  "\n". 'Here is you account information' . "\n" . 'E-mail address : '.SEND_EMAIL_ADDRESS.' and Password : '.SEND_PASSWORD );
	
		$email_text .= "\n\n"  .SEND_ACCOUNT_INFO  ;
		// add in regular email welcome text
		$email_text .= "\n\n"  . EMAIL_TEXT . EMAIL_CONTACT . EMAIL_GV_CLOSURE;
		
		$html_msg['EMAIL_MESSAGE_HTML']  = str_replace('\n','',EMAIL_TEXT);
		$html_msg['EMAIL_CONTACT_OWNER'] = str_replace('\n','',EMAIL_CONTACT);
		$html_msg['EMAIL_CLOSURE']       = nl2br(EMAIL_GV_CLOSURE);
		
		// include create-account-specific disclaimer
		$email_text .= "\n\n" . sprintf(EMAIL_DISCLAIMER_NEW_CUSTOMER, STORE_OWNER_EMAIL_ADDRESS). "\n\n";
		$html_msg['EMAIL_DISCLAIMER'] = sprintf(EMAIL_DISCLAIMER_NEW_CUSTOMER, '<a href="mailto:' . STORE_OWNER_EMAIL_ADDRESS . '">'. STORE_OWNER_EMAIL_ADDRESS .' </a>');
		
		
		// send welcome email
		
		 zen_mail($name, $email_address, EMAIL_SUBJECT, $email_text, STORE_NAME, EMAIL_FROM, $html_msg, 'welcome');
		

		
		}// mail
		
		$status = 'Success';
		$Customer = new WG_Customer();
		$Customer->setCustomerId($newuserid);
		
		
		$Customer->setStatus($status);
		$Customer->setFirstName($firstname);
		$Customer->setMiddleName($middlename);
		$Customer->setLastName($lastname);
		$Customer->setCustomerGroup($group);
		$Customer->setemail($Email);
		$Customer->setCompany($company);
		$Customer->setAddress1($street1);
		$Customer->setAddress2($street2);
		$Customer->setCity($city);
		$Customer->setState($region);
		$Customer->setZip($postcode);
		$Customer->setCountry($country_code);
		$Customer->setPhone($phone);
		
		
		$Customers->setCustomer($Customer->getCustomer());
		
		
		}
			else {
		
			$Customer = new WG_Customer();
			$Customer->setStatus('Customer email already exist');
			$Customer->setCustomerId('');
			$Customer->setFirstName($firstname);
			$Customer->setLastName($lastname);
			$Customer->setemail($Email);
			$Customer->setCompany($company);
			$Customers->setCustomer($Customer->getCustomer());
		
		}
	}
	return $this->response($Customers->getCustomers());
}


#
	#
	# Update Orders via status type method
	# Will update Order Notes and tracking number of  order
	# Input parameter Username,Password, array (OrderID,ShippedOn,ShippedVia,ServiceUsed,TrackingNumber)
	#
	function AutoSyncOrder($username,$password,$data,$statustype,$storeid,$others)
	{ 
		
		global $db,$config,$messageStack;
		$status =$this->auth_user($username,$password);
		if($status !='0')
		{
			return $status;
		}
		DEFINE('SEND_EMAILS', $config['SEND_EMAILS']); 
		DEFINE('EMAIL_USE_HTML', $config['EMAIL_USE_HTML']); 
		DEFINE('STORE_OWNER_EMAIL_ADDRESS',$config["STORE_OWNER_EMAIL_ADDRESS"]);
		DEFINE('EMAIL_FROM',$config["EMAIL_FROM"]);	
		if (!defined('EMAIL_TRANSPORT'))
		{
			DEFINE('EMAIL_TRANSPORT', 'PHP'); 
		}
		if (!defined('EMAIL_SEND_MUST_BE_STORE'))
		{
			DEFINE('EMAIL_SEND_MUST_BE_STORE', 'Yes'); 
		}
		if(file_exists(DIR_WS_FUNCTIONS . 'sessions.php'))
		{ 
			require(DIR_WS_FUNCTIONS . 'sessions.php');
			
		}
		else
		{ 
			require("../".DIR_WS_FUNCTIONS . 'sessions.php');
			
		}
		//require("../../".DIR_WS_FUNCTIONS . 'sessions.php');
		
		if (!is_object($messageStack))
		{
			require(DIR_FS_CATALOG . DIR_WS_CLASSES ."/message_stack.php");
			$messageStack = new messageStack();
		}
		require(DIR_FS_CATALOG.DIR_WS_FUNCTIONS . 'functions_email.php');
			
		include_once DIR_FS_CATALOG . DIR_WS_INCLUDES . 'filenames.php' ;	
		include_once DIR_WS_INCLUDES .'languages/english/orders.php' ;
	
		//chdir("../");	
		include_once DIR_WS_INCLUDES . 'languages/english.php' ;
		$Orders = new WG_Orders();		
		
		$response_array = $data; 
		if (!is_array($response_array))
		{
			$Orders->setStatusCode("9997");
			$Orders->setStatusMessage("Unknown request or request not in proper format");	
			return $this->response($Orders->getOrders());exit();				
		}
		if (count($response_array) == 0)
		{
			$Orders->setStatusCode("9996");
			$Orders->setStatusMessage("REQUEST array(s) doesnt have correct input format");				
			return $this->response($Orders->getOrders());exit();
		}
		if(count($response_array) == 0) {
			$no_orders = true;
		}else {
			$no_orders = false;
		}
		$Orders->setStatusCode($no_orders?"1000":"0");
		$Orders->setStatusMessage($no_orders?"No new orders.":"All Ok");
		if ($no_orders){
			return json_encode($response_array);
		}

		$i=0;	
		
	
		
	foreach($response_array as $k=>$v)//request
		{
					
				if(isset($order_wg))
				{
					unset($order_wg);
				}
				foreach($v as $k1=>$v1)
				{
					$order_wg[$k1] = $v1;
				}
				
			
			//$order_id = $order_wg['Orderno'];	
			$order_id = $order_wg['OrderID'];
			//$order_wg['IsNotifyCustomer'] = 'Y';
			if($order_wg['IsNotifyCustomer']=='N')
				$customer_notified = 0;
			else
				$customer_notified = 1;
			
			//$date = date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y")));
			$date = date("Y-m-d H:i:s");			
			$sql_str = "SELECT os.orders_status_name, o.orders_status FROM " . TABLE_ORDERS . " o LEFT JOIN ".TABLE_ORDERS_STATUS." os on os.orders_status_id = o.orders_status WHERE  orders_id =".$order_id." ";
			$row = $db->Execute($sql_str);	
			
			$status = $row->fields['orders_status_name'];
			$status_id = $row->fields['orders_status'];
			
			$query = "SELECT orders_id cnt ,customers_name, customers_email_address,date_purchased FROM ".TABLE_ORDERS." WHERE orders_id = '".$order_id."'";
			$row = $db->Execute($query);
			$customers_name = $row->fields['customers_name'];
			$date_purchased = $row->fields['date_purchased'];
			$customers_email_address = $row->fields['customers_email_address'];
			
			
			
			switch ($statustype)
			{
				
			case 'paymentUpdate':
			## Zencart does not support payment update
			$isupdated = "error";
			
			break;

			case 'statusUpdate':
				break;
				
			case 'notesUpdate':
			$isupdated = "error";
			
			$updateQuery = " UPDATE " . TABLE_ORDERS . " SET  last_modified = '".$date."' WHERE orders_id = '".$order_id."'";
			$update_date = $db->Execute($updateQuery);
			$insert_history = " INSERT into ".TABLE_ORDERS_STATUS_HISTORY."  (customer_notified ,orders_id,orders_status_id,date_added,comments) VALUES ($customer_notified,'".$order_id."','".$status_id."','".$date."','". addslashes(html_entity_decode($order_wg['OrderNotes']))."') ";
			$update_notes = $db->Execute($insert_history);
			if($update_date == '1' && $update_notes == '1')$isupdated = "success";
			
			## Email send to customer for order status notification
		  	if($order_wg['IsNotifyCustomer']=='Y') // only if eCC status in On
		  	{		
				$notify_comments = EMAIL_TEXT_COMMENTS_UPDATE . $order_wg['OrderNotes'] . "\n\n";		
			  
				$message =
	            EMAIL_TEXT_ORDER_NUMBER . ' ' . $order_id . "\n\n" .
	            EMAIL_TEXT_INVOICE_URL . ' ' . zen_catalog_href_link(FILENAME_CATALOG_ACCOUNT_HISTORY_INFO, 'order_id=' . $order_id, 'SSL') . "\n\n" .
	            EMAIL_TEXT_DATE_ORDERED . ' ' . zen_date_long($date_purchased) . "\n\n" .
	            strip_tags($notify_comments) .
	            EMAIL_TEXT_STATUS_UPDATED . sprintf(EMAIL_TEXT_STATUS_LABEL, $status ) .
	            EMAIL_TEXT_STATUS_PLEASE_REPLY;
	
				zen_mail($customers_name, $customers_email_address, EMAIL_TEXT_SUBJECT . ' #' . $order_id, $message, STORE_NAME, EMAIL_FROM, $html_msg, 'order_status');
           
		  	}	
			
			$isupdated = 'success';
			break;
			
			case 'shipmentUpdate':
			## Zencart does not support shippment update
			$isupdated = "error";
			break;	
			} 
			
			$last_modified_date = date("m-d-Y H:i:s",strtotime($date));
			
			$query = "SELECT comments  FROM ".TABLE_ORDERS_STATUS_HISTORY." WHERE orders_id = '".$order_id."' ORDER BY date_added DESC";
			$row = $db->Execute($query);
			$orderNote  = $row->fields['comments'];
		
		
			$Order = new WG_Order();
			
			$Order->setOrderID($order_id);
			$Order->setStatus($isupdated);
			$Order->setLastModifiedDate($last_modified_date);
			$Order->setOrderNotes($orderNote);
			$Order->setOrderStatus($status);	
			$Orders->setOrders($Order->getOrder());
	   }
	
	return $this->response($Orders->getOrders()); 
	} 




# function to escape html entity characters
function parseSpecCharsA($arr)
{
   if (is_array($arr))
   {	
	   foreach($arr as $k=>$v)
	   {
		 //$arr[$k] = htmlspecialchars($v, ENT_NOQUOTES);
			 $arr[$k] = addslashes(htmlentities($v, ENT_QUOTES));
	   }
   }
   else
   {
   	   $arr = addslashes(htmlentities($v, ENT_QUOTES));	
   }
   
   return $arr;
}

function parseSpecChars($obj) 
{
  foreach($obj as $k=>$v)
  {
    $obj->$k = addslashes(htmlentities($v, ENT_QUOTES)); 
  }
  return $obj;
}

# Return the Configuratin values of the store.
function getConfigvalue($val,$table,$field_name,$retval)
{
	global $db;
	$configval = $db->Execute("SELECT * from " . TABLE_CONFIGURATION . " WHERE configuration_key = '" . $val .  "'" );
	$newval = $db->Execute("SELECT * from " . $table  . " where " . $field_name . " ='" . $configval->fields['configuration_value'] . "'" );
	
	return $newval->fields[$retval];
}

}//End Class	

if(isset($_REQUEST['request'])) {
$zencart = new zencart();
$zencart->parseRequest();
}

?>