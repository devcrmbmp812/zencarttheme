<?php 
/*
===================================
© Copyright Webgility LLC 2015
----------------------------------------
This file and the source code contained herein are the property of Webgility LLC
and are protected by United States copyright law. All usage is restricted as per
the terms & conditions of Webgility License Agreement. You may not alter or remove
any trademark, copyright or other notice from copies of the content.

The code contained herein may not be reproduced, copied, modified or redistributed in any form
without the express written consent by an officer of Webgility LLC.

File last updated : 02/14/2012
Zen Cart versions: 1.3.0 to 1.3.9h
===================================
From : live
*/





//require_once('ecc-config.php'); 

if(file_exists('lib/D.zencart.php'))
{
	require_once('lib/D.zencart.php');
}
if(file_exists('D.zencart.php'))
{
	require_once('D.zencart.php');
}
ini_set("display_errors","Off");
error_reporting(E_ALL);
class WgConnect extends WgCommon
{
	



	function getOrders_button($id)
	{
		
	global $db;	
	global $add_option_in_sku;
	global $track_common_coupon_code;
	$Orders = new WG_Orders();
	
		$orderlist='';
		$orderlist = $id;
		$orderlist = $orderlist?($orderlist.",'".$id."'"):"'".$id."'";
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
	
	 
//	$orders_remained =  ($orders_remained > QB_ORDERS_PER_RESPONSE)? ($orders_remained-QB_ORDERS_PER_RESPONSE):"0";
	$orders_remained =  ($orders_remained >0)? ($orders_remained):"0";
	//$ordersNode = $xmlResponse->createTag("Orders", array(), '', $root);
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

//print_r($oInfo );die;
	# fetch orders
	while (!$oInfo->EOF) 
	{
		
		//$oInfo->fields =$this-> parseSpecCharsA($oInfo->fields);
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
		
		/*if($orderlist!='')
		             {
		$Order->setStatus();
		}else{
		$Order->setStatus();
		                   }	*/
		
		
		//$Orders->setOrders($Order->getOrder());

		$Bill = new WG_Bill();
		//print_r($oInfo);die;
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
		$billing_name = split(" ",strrev($oInfo->fields['billing_name']),2);
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
		//print_r($ship_method);die;
		
		
		$Ship = new WG_Ship();
		
		$Ship->setShipMethod( str_replace(")","",$ship_method[1]));
		$Ship->setCarrier( $ship_method[0]);
		
	//	$xmlResponse->createTag("TrackingNumber",array(), '',   $shipNode, __ENCODE_RESPONSE); // NOT FOUND
		$Ship->setTitle($title);
		$delivery_name = split(" ",strrev($oInfo->fields['delivery_name']),2);
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
				//$xmlResponse->createTag("SalesRep",  array(), $asc->fields['asc_contact_name'],  $orderNode, __ENCODE_RESPONSE);
				$asc->MoveNext();
			}
		} 
		else 
		{
			//_log("associations table not found", __line__);			
		}


		
		$items_query_raw = "SELECT o.* ,pd.products_description, pd.language_id as lng , p.product_is_always_free_shipping, p.product_is_call,p.products_weight,p.products_tax_class_id FROM ".TABLE_ORDERS_PRODUCTS." o left join ".TABLE_PRODUCTS." p on o.products_id = p.products_id left JOIN ". TABLE_PRODUCTS_DESCRIPTION . " pd ON p.products_id = pd.products_id   WHERE o.orders_id =".$oInfo->fields['orders_id']." order by o.orders_products_id ";

	
		$iInfo = $db->Execute($items_query_raw);
		# fetch item of given order
		while (!$iInfo->EOF) 
		{
			$Item = new WG_Item();
			//$iInfo->fields = $this->parseSpecCharsA($iInfo->fields);
			
			if ($iInfo->lng==1 || $iInfo->lng=="")
			{	
			
			$iInfo->fields['products_model'] = trim($iInfo->fields['products_model']);
			//$Item->setItemCode(empty($iInfo->fields['products_model'])?$iInfo->fields['products_name']:$iInfo->fields['products_model']);
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
				//$iaInfo->fields =$this->parseSpecCharsA($iaInfo->fields);

					$Itemoption = new WG_Itemoption();
					
					if($iaInfo->fields['price_prefix']=='')	
					{
					
					$iaInfo->fields['price_prefix']='+';
					}
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
					
					$Itemoption->setOptionValue($iaInfo->fields['products_options_values']);
					$Itemoption->setOptionName($iaInfo->fields['products_options']);
					$Itemoption->setOptionPrice($iaInfo->fields['options_values_price']);
					$Itemoption->setOptionWeight($iaInfo->fields['products_attributes_weight']);
								
				}
				else
				{
				
				$Itemoption->setOptionName($iaInfo->fields['products_options']);
				$Itemoption->setOptionValue($iaInfo->fields['products_options_values']);
				$Itemoption->setOptionPrice($iaInfo->fields['options_values_price']);
				}
				}
				else
				{
				
				$Itemoption->setOptionName($iaInfo->fields['products_options']);
				$Itemoption->setOptionValue($iaInfo->fields['products_options_values']);
				
				}
				$customsku =  $customsku? $customsku.'+'.$iaInfo->fields['products_options_values'] : $iaInfo->fields['products_options_values'];
				$Item->setItemOptions($Itemoption->getItemoption());
				$iaInfo->MoveNext();					

			}
			unset($itemOptionsNode);
			
			}
			if($add_option_in_sku)
			$Item->setItemCode(empty($iInfo->fields['products_model'])? $iInfo->fields['products_name'].'+'.$customsku :$iInfo->fields['products_model'].'+'.$customsku);
			else
			$Item->setItemCode(empty($iInfo->fields['products_model'])? $iInfo->fields['products_name'] : $iInfo->fields['products_model']);
			
			
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
				
			//$chInfo->fields =$this->parseSpecCharsA($chInfo->fields);
		
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
			}
			if ($chInfo->fields['value_class']=="ot_total") 
			{
				$charges->setTotal((float)$chInfo->fields['value_sum']);
				$is_total = true;
			}
	
			if ($chInfo->fields['value_class']=="ot_coupon") 
			{
				
				$discount_title = $chInfo->fields['title'];
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
			return $this->response($Orders->getOrders());

	}
	
	

	function getParams($profileID,$data,$orderno)
	{
		$parameters['profileID'] = 1;
		$parameters['orderNo'] = $orderno;
		//$parameters['Status'] = '';
		$parameters['orders'] = $data;
		return $parameters;
	}
	
	function callApi($url,$parameters)
	{

		$ch = curl_init();
		$data = http_build_query($parameters);
		$wcPac = $this->wcGetPac();
		//echo $url.'?'.$data;
		curl_setopt($ch, CURLOPT_URL,$url.'?'.$data);
		curl_setopt($ch, CURLOPT_POST, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER,  array(
													'content-length:'.strlen($data),													
													'Authorization:Basic '.$wcPac	
													));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$data );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec ($ch);
		
		curl_close ($ch);
		
		if(isset($server_output) && strlen($server_output)>0)
		{
			return $server_output;
			exit();			
		}else
		{
				
			return 'Error';
		}
	}
	
	function wgQbPost($postedId)
{
		global $db,$wcQbOrdersTable;
		$order_array = '';
		
		$data =  $this->getOrders_button($postedId);
		$params =  $this->getParams(1,$data,$postedId);
		$url =  $this->getUrl('PostOrderQB');
		$response =  $this->callApi($url,$params);
		
		$response = json_decode($response,true);
		$order_array = '';
		
		if($response === null || $response['Message']!='') {
				$order_array[0]['OrderID'] = $postedId;
				$order_array[0]['Status'] = 'ERROR_AUTH';
				//$order_array[0]['WgMsg'] = isset($response['Message'])?$response['Message']:'There is some error please try it later.';
				$order_array[0]['Message'] = isset($response['Message'])?$response['Message']:'There is some error please try it later.';
				$result_array['statuscode']= '1';
				$result_array['statuscode']= 'OK';
				$result_array['Result']= $order_array;
				return json_encode($result_array);
		}elseif(strpos(strtolower($response['StatusCode']),'error')!==false)
		{
			$order_array[0]['OrderID'] = $postedId;
			$order_array[0]['Status'] = $response['StatusCode'];
			//$order_array[0]['WgMsg'] = $response['StatusMessage'];
			$order_array[0]['Message'] = $response['StatusMessage'];
			unset($response['Result']);
			$result_array['statuscode']= '1';
			$result_array['statuscode']= 'OK';
			$result_array['Result']= $order_array;
			return json_encode($result_array);
		}
		
		$ordercnt = 0;
		$msg ="";
		foreach($response['Result'] as $k=>$v)
		{
			$query = "SELECT * from ".$wcQbOrdersTable." where orderid ='".$v['OrderNo']."'";
			$row = $db->Execute($query);
			if(!$row->fields['id'] )
			{
				
				$query = "INSERT INTO ".$wcQbOrdersTable." (`orderid`,`profile_id`,`qb_status`,`qb_posted`,`qb_posted_date`,`Transaction_type`,`qb_TransactionNumber`,`TransactionMsg`)
				 VALUES ('".$v['OrderNo']."','".$v['ProfileID']."','".$v['QBStatus']."','Yes','".time()."','".$v['QBTxnType']."','".$v['QBTxnNo']."','".$v['Message']."')";
				$row = $db->Execute($query);
				
				if($row==true)
				{
					$msg = "Successfully posted";				
				}else
				{
					$msg = "There is some error plese tryit later";
				
				}			
			}else {
			
			
			
					$query = "UPDATE ".$wcQbOrdersTable." SET  `Transaction_type` = '".$v['QBTxnType']."',`qb_TransactionNumber` = '".$v['QBTxnNo']."', `qb_status` = '".$v['QBStatus']."', `qb_posted_date` = '".time()."' where `orderid` = '".$v['OrderNo']."'";
					$row = $db->Execute($query);
					if($row==true)
					{
						$msg = "Successfully posted.";				
					}else
					{
						$msg = "There is some error plese tryit later.";
					
					}
							
			
			}	
		if($v['StatusCode']==1)
		{
			$msg = $v['Message'];
		}
		$order_array[$ordercnt]['OrderID'] = $v['OrderNo'];
		$order_array[$ordercnt]['Status'] = $v['QBStatus'];
		$order_array[$ordercnt]['QBTxnType'] = $v['QBTxnType'];
		$order_array[$ordercnt]['QBTxnNo'] = $v['QBTxnNo'];	
			
		//$order_array[$ordercnt]['WgMsg'] = $msg;
		$order_array[$ordercnt]['Message'] = $v['Message'];
		$ordercnt++;
		
		}
		
		$result_array['statuscode']='1';
		$result_array['statusmsg']= 'OK';
		$result_array['Result']= $order_array;
		return json_encode($result_array);
}
	
 	function wgGetTransactionStatus($postedId)
{
	
		global $db,$wcQbOrdersTable;
		$data =  '';
		$params =  $this->getParams(1,$data,$postedId);
		$url =  $this->getUrl('GetTransactionStatus');
		$order_array = '';
		$ordercnt = 0;
		
		$response =  $this->callApi($url,$params);
		$response = json_decode($response,true);
		if($response['Message']!='')
		{
			$order_array[0]['OrderID'] = $postedId;
			$order_array[0]['Status'] = 'Error1';
			//$order_array[0]['WgMsg'] = $response['Message'];
			$order_array[0]['Message'] = $response['Message'];
			$result_array['statuscode']='1';
			$result_array['statusmsg']= 'OK';
			$result_array['Result']= $order_array;
			return json_encode($result_array);
		}
		
		foreach($response['Result'] as $k=>$v)
		{
			$query = "SELECT * from ".$wcQbOrdersTable." where orderid ='".$v['OrderNo']."'";
			$row = $db->Execute($query);
			if($row->fields['id']!='' && (strtolower(trim($row->fields['qb_status']))=='pending' || strtolower(trim($row->fields['qb_status']))=='queued'))
			{
					
				//	$query = "UPDATE ".$wcQbOrdersTable." SET `qb_status` = '".$v['QBStatus']."', `qb_posted_date` = '".time()."' where `orderid` = '".$v['OrderNo']."'";
				//	{"StatusCode":0,"StatusMessage":"1 order found.","Result":[{"ProfileID":1,"OrderNo":"60","QBStatus":"Posted","QBTxnNo":"WEB000024","QBTxnType":"SalesReceipt","QBPostedDate":"2013-10-05"}]}Array
					
					$query = "UPDATE ".$wcQbOrdersTable." SET `qb_status` = '".$v['QBStatus']."', `qb_transactionNumber` ='".$v['QBTxnNo']."', `transaction_type` ='".$v['QBTxnType']."' , `qb_posted_date` = '".time()."' where `orderid` = '".$v['OrderNo']."'";
					
					$row = $db->Execute($query);
					
							
			}else {
			
					$msg = "There is some problem please try later.";
			}

			$order_array[$ordercnt]['OrderID'] = $v['OrderNo'];
			$order_array[$ordercnt]['Status'] = $v['QBStatus'];
			$order_array[$ordercnt]['QBTxnType'] = $v['QBTxnType'];
			$order_array[$ordercnt]['QBTxnNo'] = $v['QBTxnNo'];	
			$order_array[$ordercnt]['Message'] = $msg;
			$ordercnt++;
			
		}
		$result_array['statuscode']='1';
		$result_array['statusmsg']= 'OK';
		$result_array['Result']= $order_array;
				
		return json_encode($result_array);
}
	function wgGetQBStatus($postedId)
	{
			global $db,$wcQbOrdersTable;
				$order_array = '';
				$query = "SELECT * from ".$wcQbOrdersTable." where orderid in (".$postedId.")";
				$orders = $db->Execute($query);
				$result_array['statuscode']='1';
				$result_array['statusmsg']= 'OK';
				$result_array['Result']= '';
				$i=0;
					
				if ($orders->RecordCount() > 0) {
					while (!$orders->EOF) {
						$order_array[$i]['orderId'] = $orders->fields['orderid'];
						$order_array[$i]['status'] = $orders->fields['qb_status'];
						$order_array[$i]['transaction_type'] = $orders->fields['transaction_type'];
						$order_array[$i]['qb_transactionNumber'] = $orders->fields['qb_transactionNumber'];	
						$order_array[$i]['TransactionMsg'] = $orders->fields['TransactionMsg'];					
						$orders->MoveNext();
						$i++;	
					}
				}else
				{
						$order_array[$i]['orderId'] = $postedId;
						$order_array[$i]['status'] = '';
						$order_array[$i]['transaction_type'] = '';
						$order_array[$i]['qb_transactionNumber'] = '';	
						$order_array[$i]['TransactionMsg'] ='';					
						$i++;	
				}	
				$result_array['Result']	= $order_array;
				
				return json_encode($result_array);
				
	}
		
	function setConnectConfig($wctoken)
	{
		global $db,$wcConnectConfigTable;
		$statuscode = 1;
		
		$query= "SHOW TABLES WHERE Tables_in_".$db->database." = '".$wcConnectConfigTable."'";
		$wcconfig = $db->Execute($query);
			if($wcconfig->RecordCount()>0)
			{
				$query= "SHOW TABLES WHERE Tables_in_".$db->database." = '".$wcConnectConfigTable."'";
				$wcconfig = $db->Execute($query);
				if($wcconfig>0)
				{
					$query = "INSERT INTO ".$wcConnectConfigTable." (`Token`,`wcstoremodule`,`status`) VALUES (".$wctoken.",'',1)";
					$row = $db->Execute($query);
					if($row>0)
					{
						$msg = "Successfully configured.";
					}else 
					{
						$statuscode =0;	
						$msg = "There is some issue please configure it later.";
					}
				}
				
			}else 
			{
					$query = "CREATE TABLE IF NOT EXISTS `connect_config` (  `id` int(50) NOT NULL AUTO_INCREMENT,  `Token` varchar(255) NOT NULL,
			  `wcstoremodule` varchar(255) NOT NULL,  PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1" ;
					$wcconfig = $db->Execute($query);
						if($wcconfig)
						{	
						$query = "INSERT INTO ".$wcConnectConfigTable." (`Token`,`wcstoremodule`,`status`) VALUES (".$wctoken.",'',1)";
						$row = $db->Execute($query);
						if($row)
						{
							$msg = "Successfully inserted.";
						}else 
						{
							$statuscode = 0;	
							$msg = "There is some issue please configure it later.";
						}
					
					}	
			
			}
			
			$responce['statuscode']= $statuscode;
			$responce['statusmsg']= $msg;
			$responce['Result']='';
			return json_encode($responce);		
			 
		}
	function setDisableConfig()
	{
		global $db,$wcConnectConfigTable;
			 $query = "update ".$wcConnectConfigTable." SET status = '0' ";
			 
				        $row = $db->Execute($query);
				        if($row)
						{
							$msg = "Successfully Disabled.";
						}else 
						{
							$msg = "There is some issue please try later.";
						}
			
			$responce['statuscode']='1';
			$responce['statusmsg']= $msg;
			$responce['Result']='';
			$this->wcTrackStage3();			
			return json_encode($responce);			
					
			 
		}
	function setEnableConfig()
	{
		global $db,$wcConnectConfigTable;
			 $query = "update ".$wcConnectConfigTable." SET status = '1' ";
			 
				        $row = $db->Execute($query);
				        if($row)
						{
							$msg = "Successfully Enabled.";
						}else 
						{
							$msg = "There is some issue please try later.";
						}
			$this->wcTrackStage1();
			$responce['statuscode']='1';
			$responce['statusmsg']= $msg;
			$responce['Result']='';
						
			return json_encode($responce);			
					
			 
		}		
	function fncWcGetConfig()
	{
		global $db,$wcConnectConfigTable;
			 $query = "SELECT * FROM ".$wcConnectConfigTable." where status = 1 ";
			 
				        $row = $db->Execute($query);
				        if($row->RecordCount()>0)
						{
							$msg = "connected";
						}else 
						{
							$query = "SELECT * FROM ".$wcConnectConfigTable." ";
					        $row = $db->Execute($query);
							if($row->RecordCount()<=0)
							{
								$msg = "No congif avaialable.";
							}else
							{
								$msg = "disconnected";	
							}					
							
						}
			
			$responce['statuscode']='1';
			$responce['statusmsg']= $msg;
			$responce['Result']='';
						
			return json_encode($responce);			 
		}	
		
		function wcGetPac()
		{
			 global $db,$wcConnectConfigTable;
			 $query = "SELECT * FROM ".$wcConnectConfigTable." where status = 1";
			 $query = "SELECT * FROM ".$wcConnectConfigTable." ";
			 $row = $db->Execute($query);
			 
			 if($row->RecordCount()>0)
			 {
			 		return $row->fields['Token'];
			 }			
			
				
		}
	
function InstallButton($data)
{
	 global $db,$wcConnectConfigTable;
	$wctoken = $data['Token'];
	$STRTOWRITE = $data['STRTOWRITE'];
	$wcDownloadurl = $data['Downloadurl'];
	$wc_order_file = 'orders.php';
	
	$wc_order_file_bak = $wc_order_file.'_wcbak_'.date("d_M_Y"); 
	$msg = ''; 
	$statuscode =1;
	if(file_exists($wc_order_file))
	{
		
		if(is_writable($wc_order_file))
		{
		
			if(!file_get_contents($wc_order_file))
			{
					$msg.= 'We are not able get content, Please provide the desired content.';
			}else if(!copy($wc_order_file, $wc_order_file_bak))
			{
					$msg.= 'Not able to create backup. Please do it mannually.';
			}
	
			$query = "CREATE TABLE IF NOT EXISTS `connect_config` (`id` int(50) NOT NULL AUTO_INCREMENT,`Token` varchar(255) NOT NULL,`wcstoremodule` varchar(255) NOT NULL,  `status` varchar(255) NOT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=35 ;" ;
			$wcconfig = $db->Execute($query);
			$query = "CREATE TABLE IF NOT EXISTS `connect_qb_orders` (`id` int(4) NOT NULL AUTO_INCREMENT,`orderid` varchar(20) NOT NULL,`profile_id` varchar(50) DEFAULT NULL,
  `qb_status` varchar(50) NOT NULL,`qb_posted` varchar(20) DEFAULT NULL,`qb_posted_date` varchar(20) DEFAULT NULL,`transaction_type` varchar(50) DEFAULT NULL,`qb_transactionNumber` varchar(255) DEFAULT NULL,`TransactionMsg` text ,PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=45 ;";
  			$wcconfig = $db->Execute($query);
		
					
			$query= "SELECT Token FROM ".$wcConnectConfigTable."";
			$wcconfig = $db->Execute($query);
			
			if($wcconfig->RecordCount()>0)
			{
					$query = "UPDATE ".$wcConnectConfigTable." SET `Token` = '".$wctoken."'";
					$row = $db->Execute($query);
					if($row>0)
					{
						$msg = "Successfully configured.";
					}else 
					{
						$statuscode =0;	
						$msg = "There is some issue please configure it later.";
					}
			}else
			{
					$query = "INSERT INTO ".$wcConnectConfigTable." (`Token`,`wcstoremodule`,`status`) VALUES ('".$wctoken."','',1)";
					$row = $db->Execute($query);
					if($row>0)
					{
						$this->wcTrackStage1();
						$msg = "Successfully configured.";
					}else 
					{
						$statuscode =0;	
						$msg = "There is some issue please configure it later.";
					}
			
			}
		
			
			copy($wc_order_file, 'orders.php');
			$wc_content = file_get_contents($wc_order_file);
			if(strpos($wc_content,'<!--webgility connect widget start-->')<=0)
			{
					$var = $STRTOWRITE;
					$wc_content = str_replace('<head>',$var,$wc_content);
		 			$fh = fopen($wc_order_file, 'w+') ;
					fwrite($fh, $wc_content);
					fclose($fh);
			}		
					$statuscode =0;

		}else
		{
			$msg= 'Please provide proper permission to file.';
		}
		
	}else
	{
		
		$msg.= $wc_order_file." File not found.";
	}

			$responce['statuscode']= $statuscode;
			$responce['statusmsg']= $msg;
			$responce['Result']='';
			return $this->response($responce);
			//return json_encode($responce);
	}
	
	function wcTrackStage1()
	{
		$params['stagingName']='Enableconnect';
		$params['stagingStatus']=1;
		$params['provider']='button';
		$params['stagingDetails']='The user has enabled the widget on his store admin page & is ready to post the orders from store';
		//$params = http_build_query($params);
		//$params =  $this->getParams(1,$data,$postedId);
		$url =  $this->getUrl('AddStage');
		$response =  $this->callApi($url,$params);
	} 
		function wcTrackStage3()
	{
		$params['stagingName']='DisableConnect';
		$params['stagingStatus']=3;
		$params['provider']='button';
		$params['stagingDetails']='The user was earlier an active user but has now disconnected widget.';
		//$params = http_build_query($params);
		//$params =  $this->getParams(1,$data,$postedId);
		$url =  $this->getUrl('AddStage');
		$response =  $this->callApi($url,$params);
	}
	function getUrl($methods)
	{
		$api_methods = array ('GetTransactionStatus'=>'OrderStatus','PostOrderQB'=>'Order','AddStage'=>'Staging');		
		//FOR DEV
		//return $api_url =  'https://ecctest2.webgility.com/API/api/'.$api_methods[$methods];
		//FOR STAGING
		//return $api_url =  'https://eccstaging.webgility.com/API/api/'.$api_methods[$methods];
		//FOR LIVE
		return $api_url =  'https://ecc.webgility.com/API/api/'.$api_methods[$methods];
	} 
}

$WgConnect = new WgConnect();
$wcQbOrdersTable = 'connect_qb_orders';
$wcConnectConfigTable = 'connect_config';

if(isset($_REQUEST['method'])) {
		$postedId = $_REQUEST['id']?$_REQUEST['id']:"";
		$method = $_REQUEST['method'];
		//$method = 'GetTransactionStatus';
		switch ($method) {
		    case 'PostOrderQB':
			    echo $WgConnect->wgQbPost($postedId);
		        break;
		    case 'GetTransactionStatus':
				echo $WgConnect->wgGetTransactionStatus($postedId);		
		    	break;
		    case 'GetQBStatus':
		    echo $WgConnect->wgGetQBStatus($postedId);		
		        break;
			case 'setConnectConfig':
		    echo $WgConnect->setConnectConfig($postedId);		
		        break;
			case 'setEnableConfig':
		    echo $WgConnect->setEnableConfig();		
		        break;			
			case 'setDisableConfig':
		    echo $WgConnect->setDisableConfig();		
		        break;			
			case 'fncWcGetConfig':
		    echo $WgConnect->fncWcGetConfig();		
		        break;
			case 'InstallButton':
			
		    echo $WgConnect->InstallButton($_REQUEST);		
		        break;					
						
		}
		exit();
	}
?>