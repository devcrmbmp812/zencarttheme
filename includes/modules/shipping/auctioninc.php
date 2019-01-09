<?php
/**
 * @package shippingMethod
 * @copyright Copyright 2003-2009 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: actioninc.php
 */
/**
 * auctioninc / real time calculated shipping methods
 *
 */
 
include_once(DIR_WS_INCLUDES.'ShipRateParserXML.php');
include_once(DIR_WS_INCLUDES.'ShipRateParserXMLItem.php');
 
class auctioninc extends base {
  /**
   * $code determines the internal 'code' name used to designate "this" payment module
   *
   * @var string
   */
  var $code;
  /**
   * $title is the displayed name for this payment method
   *
   * @var string
   */
  var $title;
  /**
   * $description is a soft name for this payment method
   *
   * @var string
   */
  var $description;
  /**
   * module's icon
   *
   * @var string
   */
  var $icon;
  /**
   * $enabled determines whether this module shows or not... during checkout.
   *
   * @var boolean
   */
  var $enabled;
  /**
   * constructor
   *
   * @return storepickup
   */
   
   var $rateReq;
   var $defaultcalcmethod;
   var $defaultpackage;
  
   var $useragent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";
   var $timeout   = 30;       // timeout (seconds) for communications

   var $url = 'api.auctioninc.com';
   var $uri = '/websvc/shire';
   var $port = 80;		
   
   var $secureComm = false;       // ssl not supported at this time
   var $apiURL;	                // if set allows client to override the URL to the web service
   var $maxWeights = array();     // maximum package weights for particular carrier service
   var $flatRateFlag = array();   // Flag to set qualification for flat rate packaging at the carrier level
  
function auctionInc() {
	global $order, $db;
	
	$this->reset();
	
	//START => DEFAULT SETTING
	if(strstr(MODULE_SHIPPING_AUCTIONINC_DEFAULTCALCMETHOD, 'Calculated'))
		$this->defaultcalcmethod='C';
   else if(strstr(MODULE_SHIPPING_AUCTIONINC_DEFAULTCALCMETHOD, 'Free Domestic Shipping'))
	{
		$this->defaultcalcmethod='CI';
	}
	else if(strstr(MODULE_SHIPPING_AUCTIONINC_DEFAULTCALCMETHOD, 'Fixed') && MODULE_SHIPPING_AUCTIONINC_DEFAULTFIXEDFEE)
	{
		$this->defaultcalcmethod='F';
		$this->defaultfixedfee1=MODULE_SHIPPING_AUCTIONINC_DEFAULTFIXEDFEE;
	}

	if(MODULE_SHIPPING_AUCTIONINC_DEFAULTPACKAGE=='Together with other items')
		$this->defaultpackage='T';
	else if(MODULE_SHIPPING_AUCTIONINC_DEFAULTPACKAGE=='Separately')
		$this->defaultpackage='S';
	//END => DEFAULT SETTING
	
	if (is_array($order->products)){
	foreach ($order->products AS $val){
	   
		if ($val["actioninc_calcmethod"] == "C" || $val["actioninc_calcmethod"] == "CI"){
		
		if($val["actioninc_insurable"]=='Yes' && MODULE_SHIPPING_AUCTIONINC_INSURANCE == "Yes")
			$price=$val["price"];
		else
			$price='';
		
			$this->addItemCalc($val["name"], $val["quantity"], $val["weight"], TEXT_PRODUCT_WEIGHT_UNIT, $val["actioninc_length"], $val["actioninc_width"], $val["actioninc_height"], MODULE_SHIPPING_AUCTIONINC_DIM_UNIT,  $price, $val["actioninc_package"],$val["actioninc_origincode"],$val["actioninc_fixeddollaramount"],$val["actioninc_handlingcode"],$val["actioninc_servicecodes"],$val["actioninc_specialaccessorialfees"],$val["actioninc_calcmethod"]);
		
		} 
		
		else if($val["actioninc_calcmethod"] == "F"){
		   $feeType = empty($val["actioninc_fixedfeecode"]) ?  "F" : "C";
			$this->addItemFixed($val["name"], $val["quantity"], $feeType, $val["actioninc_fixedfee1"], $val["actioninc_fixedfee2"], $val["actioninc_fixedfeecode"]);    
		}
		
		else if($val["actioninc_calcmethod"] == "N") {
			$this->addItemFree($val["name"],$val["quantity"]);
		}	
		
		else if ($this->defaultcalcmethod == "C" || $this->defaultcalcmethod == "CI"){
		if($val["actioninc_insurable"]=='Yes' && MODULE_SHIPPING_AUCTIONINC_INSURANCE == "Yes")
			$price=$val["price"];
		else
			$price='';
			
			$this->addItemCalc($val["name"], $val["quantity"], $val["weight"], TEXT_PRODUCT_WEIGHT_UNIT, $val["actioninc_length"], $val["actioninc_width"], $val["actioninc_height"], MODULE_SHIPPING_AUCTIONINC_DIM_UNIT,  $price, $this->defaultpackage,$val["actioninc_origincode"],$val["actioninc_fixeddollaramount"],$val["actioninc_handlingcode"],$val["actioninc_servicecodes"],$val["actioninc_specialaccessorialfees"],$this->defaultcalcmethod);
		
		}
		
		else if($this->defaultcalcmethod == "F"){
			$this->addItemFixed($val["name"], $val["quantity"], 'F', $this->defaultfixedfee1, $this->defaultfixedfee2, $this->defaultfixedfeecode);    
		}
		else if($this->defaultcalcmethod == "N") {
			$this->addItemFree($val["name"],$val["quantity"]);
		}		
	}
	}
	$destCountryCode = $order->delivery['country']['iso_code_2'];
	$selectcountryid=$order->delivery['country_id'];
	$selectstateid=$order->delivery['zone_id'];
	$deststate  = zen_get_zone_code($selectcountryid, $selectstateid, '');
	$destPostalCode  = $order->delivery['postcode'];	
	
	$residential = ((MODULE_SHIPPING_AUCTIONINC_DESTINATION == 'Residential') ? true : false);
	
	$this->setDestinationAddress($destCountryCode, $destPostalCode, $deststate, $residential);
   	
	$this->code = 'auctioninc';
	$this->title = MODULE_SHIPPING_AUCTIONINC_TEXT_TITLE;
	$this->description = MODULE_SHIPPING_AUCTIONINC_TEXT_DESCRIPTION;
	$this->sort_order = MODULE_SHIPPING_AUCTIONINC_SORT_ORDER;
	$this->icon = '';
	$this->tax_class = MODULE_SHIPPING_AUCTIONINC_TAX_CLASS;
	$this->tax_basis = MODULE_SHIPPING_AUCTIONINC_TAX_BASIS;
	$this->enabled = ((MODULE_SHIPPING_AUCTIONINC_STATUS == 'On') ? true : false);
	$this->accountId =MODULE_SHIPPING_AUCTIONINC_APIKEY;
	
	if ( ($this->enabled == true) && ((int)MODULE_SHIPPING_AUCTIONINC_ZONE > 0) ) {
		$check_flag = false;
		$check = $db->Execute("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . "
		where geo_zone_id = '" . MODULE_SHIPPING_AUCTIONINC_ZONE . "'
		and zone_country_id = '" . $order->delivery['country']['id'] . "'
		order by zone_id");
		while (!$check->EOF) {
			if ($check->fields['zone_id'] < 1) {
				$check_flag = true;
				break;
			} elseif ($check->fields['zone_id'] == $order->delivery['zone_id']) {
				$check_flag = true;
				break;
			}
			$check->MoveNext();
		}
		
		if ($check_flag == false) {
			$this->enabled = false;
		}
	}
}


//START ACTIONINC SHIPPING API OBJECT
function reset()
{
  $this->rateReq = array();
  $this->rateReq['DestinationAddress'] = array();
  $this->rateReq['OriginAddressList'] = array();
  $this->rateReq['ItemList'] = array();
  $this->rateReq['CarrierList'] = array();
  $this->rateReq['Currency'] = DEFAULT_CURRENCY;//'USD';
  $this->rateReq['DetailLevel'] = 3;  // set to 3 for package-level details
}

function _makeGetItemShipRateSS_XML()
{
  $head = $this->_makeXML_header();
  $body = '<Body><GetItemShipRateSS version="2.1">'.
		  '<Currency>'.$this->rateReq['Currency'].'</Currency>'.
		  '<DetailLevel>'.$this->rateReq['DetailLevel'].'</DetailLevel>'.             
		  $this->_makeXML_destination().
		  $this->_makeXML_itemList().
		  '</GetItemShipRateSS></Body>';
  return ('<?xml version="1.0" encoding="utf-8" ?><Envelope>'.$head.$body.'</Envelope>');
  
}

   
function _makeXML_header() {
	$head = '<Header>' . 
	"<AccountId>{$this->accountId}</AccountId>" . 
	(! empty($this->refCode) ? "<RefCode>{$this->refCode}</RefCode>" : '') .
	'</Header>';
	return $head;
}

function _makeXML_destination()
{
  $xml = '<DestinationAddress>'.
		 '<ResidentialDelivery>'.($this->rateReq['DestinationAddress']['Residential'] ? 'true' : 'false').'</ResidentialDelivery>'.
		 '<CountryCode>'.$this->rateReq['DestinationAddress']['CountryCode'].'</CountryCode>'.
		 '<StateOrProvinceCode>'.$this->rateReq['DestinationAddress']['StateOrProvinceCode'].'</StateOrProvinceCode>'.
		 '<PostalCode>'.$this->rateReq['DestinationAddress']['PostalCode'].'</PostalCode>'.
		 '</DestinationAddress>';
  return ($xml);
}

function setDestinationAddress($countryCode, $postalCode, $stateOrProvinceCode='', $residentialFlag=false)
{
  $this->rateReq['DestinationAddress']['PostalCode'] = strtoupper($postalCode);
  $this->rateReq['DestinationAddress']['StateOrProvinceCode'] = strtoupper($stateOrProvinceCode);
  $this->rateReq['DestinationAddress']['CountryCode'] = strtoupper($countryCode);
  $this->rateReq['DestinationAddress']['Residential'] = ($residentialFlag ? 1 : 0);
}

function addItemCalc($refCode, $qty, $weight, $wtUOM, $length, $width ,$height ,$dimUOM, $decVal, $packMethod,$origincode,$supphandlingfee, $supphandlingcode, $ondemandservices, $specialservices, $calcmethod, $lotSize=1)
{
 $cnt = (int) count($this->rateReq['ItemList']);
 
  $this->rateReq['ItemList'][$cnt] = array();
  $this->rateReq['ItemList'][$cnt]['CalcMethod']    = $calcmethod;//'C';
  $this->rateReq['ItemList'][$cnt]['RefCode']       = $refCode;
  $this->rateReq['ItemList'][$cnt]['Quantity']      = (float) abs($qty);
  $this->rateReq['ItemList'][$cnt]['LotSize']       = (float) abs($lotSize);
  $this->rateReq['ItemList'][$cnt]['Length']        = (float) abs($length);
  $this->rateReq['ItemList'][$cnt]['Width']         = (float) abs($width);
  $this->rateReq['ItemList'][$cnt]['Height']        = (float) abs($height);
  $this->rateReq['ItemList'][$cnt]['DimUOM']        = stripos($dimUOM,'cm') !== false ? 'CM' : 'IN'; //(!strcasecmp('CM', $dimUOM) ? 'CM' : 'IN');
  if (!empty($origincode)) $this->rateReq['ItemList'][$cnt]['OriginCode']		= $origincode;
  if (!empty($supphandlingcode)) $this->rateReq['ItemList'][$cnt]['SuppHandlingCode'] = "$supphandlingcode";
  if (!empty($supphandlingfee) && $supphandlingfee > 0) $this->rateReq['ItemList'][$cnt]['SuppHandlingFee'] = "$supphandlingfee";
  if (!empty($ondemandservices)) $this->rateReq['ItemList'][$cnt]['OnDemandServices'] = "$ondemandservices";
  if (!empty($specialservices)) $this->rateReq['ItemList'][$cnt]['SpecialServices'] = "$specialservices";
  $this->rateReq['ItemList'][$cnt]['Weight']        = (float) abs($weight);
  $this->rateReq['ItemList'][$cnt]['WeightUOM']     = stripos($wtUOM,'kg') !== false ? 'KGS' : (stripos($wtUOM,'oz') !== false ? 'OZ' : 'LBS');//(!strcasecmp('KGS',$wtUOM) ? 'KGS' :  (!strcasecmp('OZ',$wtUOM) ? 'OZ' : 'LBS'));
  $this->rateReq['ItemList'][$cnt]['DeclaredValue'] = (float) abs($decVal);
  $this->rateReq['ItemList'][$cnt]['PackMethod']    = (!strcasecmp('S', $packMethod) ? 'S' : 'T');
}


function addItemFixed($refCode,$q,$t,$f1,$f2,$c)
{
	$cnt = (int) count($this->rateReq['ItemList']);
	$this->rateReq['ItemList'][$cnt] = array();
	$this->rateReq['ItemList'][$cnt]['CalcMethod'] = 'F';
	$this->rateReq['ItemList'][$cnt]['RefCode']    = $refCode;
	$this->rateReq['ItemList'][$cnt]['Quantity']   = (float) abs($q);
	$this->rateReq['ItemList'][$cnt]['FeeType']    = (!strcasecmp('F',$t) ? 'F' : 'C');
	$this->rateReq['ItemList'][$cnt]['Fee1']       = (float) abs($f1);
	if (isset($f2)) $this->rateReq['ItemList'][$cnt]['Fee2']  = (float) abs($f2);
	$this->rateReq['ItemList'][$cnt]['FeeCode']    = $c;
}

function addItemFree($refCode,$q)
{
	$cnt = (int) count($this->rateReq['ItemList']);
	$this->rateReq['ItemList'][$cnt] = array();
	$this->rateReq['ItemList'][$cnt]['CalcMethod'] = 'N';
	$this->rateReq['ItemList'][$cnt]['RefCode']    = $refCode;
	$this->rateReq['ItemList'][$cnt]['Quantity']   = (float) abs($q);
}


function _makeXML_itemList()
{
 
  $cnt  = count($this->rateReq['ItemList']);
  
  $xml  = '<ItemList>';
  for ($i = 0; $i < $cnt; $i++) {
	  $xml .= '<Item>'.
			  '<RefCode>'.$this->rateReq['ItemList'][$i]['RefCode'].'</RefCode>'.
			  '<Quantity>'.$this->rateReq['ItemList'][$i]['Quantity'].'</Quantity>'.
			  '<CalcMethod code="'.$this->rateReq['ItemList'][$i]['CalcMethod'].'">';
		 if ($this->rateReq['ItemList'][$i]['CalcMethod'] === 'C' || $this->rateReq['ItemList'][$i]['CalcMethod'] === 'CI') {
		 
		    $xml .= '<CarrierCalcProps>'.
				'<Weight>'.$this->rateReq['ItemList'][$i]['Weight'].'</Weight>'.
				'<WeightUOM>'.$this->rateReq['ItemList'][$i]['WeightUOM'].'</WeightUOM>'.
				'<Length>'.$this->rateReq['ItemList'][$i]['Length'].'</Length>'.
				'<Width>'.$this->rateReq['ItemList'][$i]['Width'].'</Width>'.
				'<Height>'.$this->rateReq['ItemList'][$i]['Height'].'</Height>'.
				'<DimUOM>'.$this->rateReq['ItemList'][$i]['DimUOM'].'</DimUOM>'.
				'<DeclaredValue>'.$this->rateReq['ItemList'][$i]['DeclaredValue'].'</DeclaredValue>'.
				'<PackMethod>'.$this->rateReq['ItemList'][$i]['PackMethod'].'</PackMethod>'.
				(isset($this->rateReq['ItemList'][$i]['LotSize']) ? '<LotSize>'.$this->rateReq['ItemList'][$i]['LotSize'].'</LotSize>' : '') .
				(($this->rateReq['ItemList'][$i]['OriginCode'] != "") ? '<OriginCode>'.$this->rateReq['ItemList'][$i]['OriginCode'].'</OriginCode>' : '') .
				(!empty($this->rateReq['ItemList'][$i]['SuppHandlingCode']) ? '<SuppHandlingCode>'.$this->rateReq['ItemList'][$i]['SuppHandlingCode'].'</SuppHandlingCode>' : '') .
				(isset($this->rateReq['ItemList'][$i]['SuppHandlingFee']) ? '<SuppHandlingFee>'.$this->rateReq['ItemList'][$i]['SuppHandlingFee'].'</SuppHandlingFee>' : '');
				
				
			 if (isset($this->rateReq['ItemList'][$i]['OnDemandServices'])){
		       $ondemandservicesvalues=explode(',',$this->rateReq['ItemList'][$i]['OnDemandServices']);
				 $cnt_od  = count($ondemandservicesvalues); 
				 if ($cnt_od > 0){
				    $xml .= '<OnDemandServices>';
					 for ($j = 0; $j < $cnt_od; $j++) {
					   $xml .= '<ODService>'.strtoupper($ondemandservicesvalues[$j]).'</ODService>';       
				    } 
				    $xml .= '</OnDemandServices>';    
				 }  
		    }
			 
			 if (isset($this->rateReq['ItemList'][$i]['SpecialServices'])) {
			    $specialservicesvalues=explode(',',$this->rateReq['ItemList'][$i]['SpecialServices']);
			    $cnt_ss  = count($specialservicesvalues); 
			    if ($cnt_ss > 0){
				    $xml .= '<SpecialServices>';
				    for ($j = 0; $j < $cnt_ss; $j++) {
				       $xml .= '<'.$specialservicesvalues[$j].'>TRUE</'.$specialservicesvalues[$j].'>';       
				    } 
				  $xml .= '</SpecialServices>';  
			     }  
			  }  
			  $xml .=   '</CarrierCalcProps>';
			    
		 } else if ($this->rateReq['ItemList'][$i]['CalcMethod'] === 'F'){
			$xml .= '<FixedCalcProps>'.
					'<FeeType>'.$this->rateReq['ItemList'][$i]['FeeType'].'</FeeType>'.
					'<Fee1>'.$this->rateReq['ItemList'][$i]['Fee1'].'</Fee1>'.
					'<Fee2>'.$this->rateReq['ItemList'][$i]['Fee2'].'</Fee2>'.
					'<FeeCode>'.$this->rateReq['ItemList'][$i]['FeeCode'].'</FeeCode>'.
					(($this->rateReq['ItemList'][$i]['OriginCode'] != "") ? '<OriginCode>'.$this->rateReq['ItemList'][$i]['OriginCode'].'</OriginCode>' : '') .
					'</FixedCalcProps>';
			  }
	  $xml .= '</CalcMethod>';
	  $xml .= '</Item>';
			  
  }   
  $xml .= '</ItemList>';
  
  return ($xml);

}


function GetItemShipRateSS()
{
  $reqXML = $this->_makeGetItemShipRateSS_XML(); 
  return $this->_GetItemShipRate($reqXML);
}

function setTimeout($v) { $this->timeout = (int)$v; }
function setURL($url)   { $this->url     = $url; }
function setURI($uri)   { $this->uri     = $uri; }
function setPort($port) { $this->port    = $port; }

function _GetItemShipRate($reqXML)
{

   if (! $this->secureComm) $this->setPort(80);
	if (isset($this->apiURL)) $this->setURL($this->apiURL);
	
	if ($this->post($reqXML, false, $respXML, $headers, $errorMsg='')) { 
	 $p = new ShipRateParserXMLItem();
	 $respXML = substr($respXML, strpos($respXML, "<?"), strpos($respXML, "</Envelope>"));
	 
	 if (MODULE_SHIPPING_AUCTIONINC_DEBUG == 'Email'){
	    $content =  "AUCTIONINC API DEBUG\r\n\r\nSENT XML\r\n\r\n$reqXML\r\n\r\nREUTRNED XML\r\n\r\n$respXML";    
	    zen_mail("", STORE_OWNER_EMAIL_ADDRESS, 'Debug: AuctionInc rate quote', $content, EMAIL_FROM, STORE_OWNER_EMAIL_ADDRESS, "", 'xml_record');
	 }
  
	 return $shipRateArray = $p->parse($respXML);
	} else {
	 // Set an error
	 return $shipRateArray = $this->_createError(505, $errorMsg);
	}
}

function _createError($errorCode, $errorMsg, $severity='CRITICAL') {
  $error = array('ErrorList' => array());
  $error['ErrorList'][] = array(
		'Code' => $errorCode,
		'Message' => $errorMsg,
		'Severity' => $severity
		);
  return $error;
}
   
function post($queryData, $reqHeaders=false, &$respContent, &$respHeaders, &$errorMsg)
   {
	  
	  if (!is_array($reqHeaders)) $reqHeaders = array();
      
      switch ($this->port) {
         case 443 : 
            $host = "ssl://" . $this->url;
            break;
         default : 
            $host = "" . $this->url;
      }
      
      
         $fp = @fsockopen($host, $this->port, $errnum, $errmsg, $this->timeout);
         if (!$fp) {
             $errorMsg = 'Unable to connect to Shipping Rate Web Service';
            return false;
         }

         // keep track of how long the communications has taken
         $start = time();
		 
		   //echo $queryData; exit;
		 
         $req  = "POST {$this->uri} HTTP/1.1\r\n";
         $req .= "Host: {$this->url}:{$this->port}\r\n";
         $req .= "Content-type: text/xml\r\n";
         $req .= "User-Agent: {$this->useragent}\r\n";
         $req .= "Content-length: ".strlen($queryData)."\r\n";
         $req .= "Connection: Close\r\n\r\n";

         // Set the timeout for the communications to retreive the results
         stream_set_timeout($fp, $this->timeout, 0);

         fwrite($fp, $req.$queryData);
         fflush($fp);

         $result = '';
         $timedOut = false;
         while (!feof($fp)) {
            // See if we've timed out for the communications
            if (time() - $start > $this->timeout) {
               $timedOut = true;
               break;
            }
            $result .= fgets($fp, 1024);
         }
         fclose($fp);

         if ($timedOut) {
            $errorMsg = "Communications to rate engine timed out ({$this->timeout} seconds)";
            return false;
         }
      
      // ------------------------------------------------------------------
      // Handle Non 200 (OK) 302 (REDIRECT) Response
      // ------------------------------------------------------------------
      if ($errnum != 0 || !preg_match("#HTTP/1.1 200 OK#i",$result)) {
         $errorMsg = 'An unexpected error occured while communicating with Shipping Rate Web Service';
         return(false);
      }
      
      if (strstr($result, "\r\n\r\n")) {
         // loop to handle "HTTP/1.1 100 Continue" headers
         $headers = '';
         while(true) {
            list($respHeaders, $respContent) = preg_split("/\r\n\r\n/",$result,2);
            
            // See if we got a 100 Continue header
            if (ereg('^HTTP\/1\.[0-9][ ]{1,}100[ ]{1,}', $respHeaders)) {
               // Hold onto the continue header
               $headers .= $respHeaders;
               $result = $respContent;
               continue;
            }
            break;
         }
         // Tack the headers back together if we had multiple.
         if (isset($headers{0})) {
            $respHeaders = $headers . $respHeaders;
         }
      } elseif (stristr($result, 'content-length: 0')) {
         $respHeaders = $result;
         $respContent = '';
      } else {
         $respContent = $result;
      }
	  
      return(true);
   }

//END ACTIONINC SHIPPING API OBJECT

  /**
   * Obtain quote from shipping system/calculations
   *
   * @param string $method
   * @return array
   */
  function quote($method = '') {
  
   global $order;
    
   if (empty($this->rateReq['DestinationAddress']['PostalCode'])) return false;
	
	$shipRates=$this->GetItemShipRateSS();
	$respcnt=sizeof($shipRates['ShipRate']);
	
	$this->quotes = array('id' => $this->code,
                          'module' => 'Ship Service',
						  );
   $methods = array();

	$package_details = array();	  
	for($i=0, $c=$respcnt; $i < $c; $i++) {
		$serviceName = $shipRates['ShipRate'][$i]['ServiceName'];
		$type=$shipRates['ShipRate'][$i]['ServiceCode'];
		$flatRateCode=$shipRates['ShipRate'][$i]['FlatRateCode'];
		$carrierRate=$shipRates['ShipRate'][$i]['CarrierRate'];
		$fuelSurcharges=$shipRates['ShipRate'][$i]['FuelSurcharges'];
		$surcharges=$shipRates['ShipRate'][$i]['Surcharges'];
		$handlingFees=$shipRates['ShipRate'][$i]['HandlingFees'];
		$insuranceCharges=$shipRates['ShipRate'][$i]['InsuranceCharges'];
		$packageCount=$shipRates['ShipRate'][$i]['PackageCount'];
		$cost=$shipRates['ShipRate'][$i]['Rate'];
		$cost = preg_replace('/[^0-9.]/', '',  $cost);
		
		if ($flatRateCode && $flatRateCode != "NONE"){
		   $frb = ($flatRateCode== "FRE") ? "Flat Rate Envelope" : "Flat Rate Box";
		   if ($flatRateCode== "LFRB") $frPre = "Large";
		   if ($flatRateCode== "SFRB") $frPre = "Small";
		   if ($flatRateCode== "FRB1" || $flatRateCode== "FRB2") $frPre = "Medium";
		   $serviceName .= isset($frPre) ? " $frPre $frb" : " $frb";
		}

		$packages = array();
		if(is_array($shipRates['ShipRate'][$i]['PackageDetail'])) {
			foreach($shipRates['ShipRate'][$i]['PackageDetail'] as $package) {
				$package_info = array();
				$package_info['FlatRateCode'] = $package['FlatRateCode'] == '' ? 'NONE' : $package['FlatRateCode'];
				$package_info['Quantity'] = $package['Quantity'];
				$package_info['PackMethod'] = $package['PackMethod'];
				$package_info['Origin'] = $package['Origin'];
				$package_info['DeclaredValue'] = $package['DeclaredValue'];
				$package_info['Weight'] = $package['Weight'];
				$package_info['Length'] = $package['Length'];
				$package_info['Width'] = $package['Width'];						
				$package_info['Height'] = $package['Height'];
				$package_info['OversizeCode'] = $package['OversizeCode'];
				$package_info['CarrierRate'] = $package['CarrierRate'];
				$package_info['Surcharge'] = $package['Surcharge'];
				$package_info['FuelSurcharge'] = $package['FuelSurcharge'];
				$package_info['Insurance'] = $package['Insurance'];
				$package_info['Handling'] = $package['Handling'];
				$package_info['ShipRate'] = $package['ShipRate'];
				
				$package_items = array();
				foreach ($package['PkgItem'] as $pkg_item) {
					$package_data = array();
					$package_data['RefCode'] = $pkg_item['RefCode'];
					$package_data['Qty'] = $pkg_item['Qty'];
					$package_data['Weight'] = $pkg_item['Weight'];
					$package_items[] = $package_data;						
				}

				$package_info['Items'] = $package_items;
				$packages[] = $package_info;
			}
		}
		$package_details[$serviceName] = $packages;

		if(!empty($method) && $method==$type)
		{
		$methods[] = array('id' => $type,
					   'title' => $serviceName,
					   'cost' => $cost);
		}
		else if(empty($method))
		{
		$methods[] = array('id' => $type,
					   'title' => $serviceName,
					   'cost' => $cost);
		}
	}
	
	$_SESSION['package_details'] = $package_details;

	/*echo '<pre>';
	print_r($methods);
	exit;*/
	
	$this->quotes['methods'] = $methods;

    if ($this->tax_class > 0) {
      $this->quotes['tax'] = zen_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
    }
	if($respcnt==0) {
	   
	   // test specifically for bad destination address
	   $errcnt=sizeof($shipRates['ErrorList']);
	   $addrError= false;
	   for($i=0, $c=$errcnt; $i < $c; $i++) {
		   $errCode = $shipRates['ErrorList'][$i]['Code'];
		   // current address error code is too generic, for now use content
		   if (strstr($shipRates['ErrorList'][$i]['Message'], "Inadequate destination address")){
		     $addrError = true; 
		     break;  
		   }
	   }
	   
	   if ($addrError){
	      
	       
         $this->quotes = array('module' => "Shipping Rate Error",
                               'error' => 'There seems to be a problem with your destination postal code. Please check it carefully and try again.'); 
         $this->quotes["methods"][0]["id"] = 'AuctionInc Shipping Rates API';                         
                                                
	   } else {
	      
         $this->quotes = array('module' => "Shipping Rate Error",
                               'error' => 'We are unable to obtain any shipping rate quotes.<br />Please contact the store if no other alternative is shown.');
         $this->quotes["methods"][0]["id"] = 'AuctionInc Shipping Rates API';                         
	   }
    }
    if (zen_not_null($this->icon)) $this->quotes['icon'] = zen_image($this->icon, $this->title);

    return $this->quotes;
	
	
	
  }
  /**
   * Check to see whether module is installed
   *
   * @return boolean
   */
  function check() {
    global $db;
    if (!isset($this->_check)) {
      $check_query = $db->Execute("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_AUCTIONINC_STATUS'");	  
      $this->_check = $check_query->RecordCount();
    }
    return $this->_check;
  }
  /**
   * Install the shipping module and its configuration settings
   *
   */
   
  function install() {
    global $db;
    
   $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable AuctionInc Shipping API', 'MODULE_SHIPPING_AUCTIONINC_STATUS', 'On', 'A subscription based service which provides accurate comparative shipping rates for FEDEX, UPS, DHL and USPS.', '6', '0', 'zen_cfg_select_option(array(\'On\', \'Off\'), ', now())");	
	$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('AuctionInc Account ID', 'MODULE_SHIPPING_AUCTIONINC_APIKEY', '', 'Enter the AuctionInc Account ID.', '6', '0', now())");	
	//$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('<fieldset>Default Settings', 'MODULE_SHIPPING_AUCTIONINC_DEFAULTCALCMETHOD', '', 'These settings will be used if AuctionInc values are not set for your Product. Note: if you use Calculated Shipping, you must have product weights configured for your products.<br><br><b>Calculation Method</b>', '6', '0', 'zen_cfg_select_option(array(\'Calculated Shipping (based on item weight)\',\'Fixed Fee Shipping\'), ', now())");	
    // -- FREE DOMESTIC -- REPLACE THE ABOVE LINE WITH THE BELOW LINE	
	$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('<fieldset>Default Settings', 'MODULE_SHIPPING_AUCTIONINC_DEFAULTCALCMETHOD', '', 'These settings will be used if AuctionInc values are not set for your Product. Note: if you use Calculated Shipping, you must have product weights configured for your products.<br><br><b>Calculation Method</b>', '6', '0', 'zen_cfg_select_option(array(\'Calculated Shipping (based on item weight)\',\'Fixed Fee Shipping\',\'Free Domestic Shipping\'), ', now())");	
	$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) values ('Package', 'MODULE_SHIPPING_AUCTIONINC_DEFAULTPACKAGE', '', '6', '0', 'zen_cfg_select_option(array(\'Together with other items\', \'Separately\'), ', now())");	
	$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Fixed Fee $', 'MODULE_SHIPPING_AUCTIONINC_DEFAULTFIXEDFEE', '', '', '6', '0', now())");	
	$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('</fieldset>Destination Type', 'MODULE_SHIPPING_AUCTIONINC_DESTINATION', 'Residential', 'Please select whether your customer destinations are: (Residential destinations add a residential surcharge for FedEx & UPS.)', '6', '0', 'zen_cfg_select_option(array(\'Residential\', \'Commercial\'), ', now())");	
	$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Carrier Insurance', 'MODULE_SHIPPING_AUCTIONINC_INSURANCE', 'Yes', 'If selected, Carrier Insurance will be added to your items, subject to both your product insurable flag and your AuctionInc Insurance Thresholds.', '6', '0', 'zen_cfg_select_option(array(\'Yes\', \'No\'), ', now())");	
	$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Tax Class', 'MODULE_SHIPPING_AUCTIONINC_TAX_CLASS', '0', 'Use the following tax class on the shipping fee.', '6', '0', 'zen_get_tax_class_title', 'zen_cfg_pull_down_tax_classes(', now())");
   $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Tax Basis', 'MODULE_SHIPPING_AUCTIONINC_TAX_BASIS', 'Shipping', 'On what basis is Shipping Tax calculated. Options are<br />Shipping - Based on customers Shipping Address<br />Billing Based on customers Billing address<br />Store - Based on Store address if Billing/Shipping Zone equals Store zone', '6', '0', 'zen_cfg_select_option(array(\'Shipping\', \'Billing\', \'Store\'), ', now())");
   $db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Shipping Zone', 'MODULE_SHIPPING_AUCTIONINC_ZONE', '0', 'If a zone is selected, only enable this shipping method for that zone.', '6', '0', 'zen_get_zone_class_title', 'zen_cfg_pull_down_zone_classes(', now())");	
	$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Debug', 'MODULE_SHIPPING_AUCTIONINC_DEBUG', 'Off', 'Would you like to enable debug mode? A record of the incoming and outgoing XML will be emailed to the store owner for each rating. (Use this sparingly, for debugging purposes only.)', '6', '0', 'zen_cfg_select_option(array(\'Off\', \'Email\'), ', now())");	
	$db->Execute("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SHIPPING_AUCTIONINC_SORT_ORDER', '0', 'Sort order of display.', '6', '0', now())");
	
	$db->Execute("ALTER TABLE " . TABLE_PRODUCTS . " 
	ADD actioninc_calcmethod varchar(2) after metatags_title_tagline_status, 
	ADD actioninc_length decimal(15,2) after actioninc_calcmethod, 
	ADD actioninc_width decimal(15,2) after actioninc_length,
	ADD actioninc_height decimal(15,2) after actioninc_width,
	ADD actioninc_package varchar(1) after actioninc_height,
	ADD actioninc_origincode varchar(64) after actioninc_package,
	ADD actioninc_insurable varchar(3) after actioninc_origincode,
	ADD actioninc_handlingcode varchar(64) after actioninc_insurable,
	ADD actioninc_fixeddollaramount decimal(15,2) after actioninc_handlingcode,
	ADD actioninc_servicecodes varchar(64) after actioninc_fixeddollaramount,
	ADD actioninc_specialaccessorialfees  varchar(64) after actioninc_servicecodes,
	ADD actioninc_fixedfeecode varchar(64) after actioninc_specialaccessorialfees,
	ADD actioninc_fixedfee1 varchar(64) after actioninc_fixedfeecode,
	ADD actioninc_fixedfee2 varchar(64) after actioninc_fixedfee1");

	$db->Execute("ALTER TABLE " . TABLE_ORDERS . " 
	ADD actioninc_packages text after ip_address");
  }
	
	
  /**
   * Remove the module and all its settings
   *
   */
  function remove() {
    global $db;
    $db->Execute("delete from " . TABLE_CONFIGURATION . " where configuration_key like 'MODULE\_SHIPPING\_AUCTIONINC\_%'");
	$db->Execute("ALTER TABLE ". TABLE_PRODUCTS ." 
	DROP COLUMN actioninc_calcmethod,
	DROP COLUMN actioninc_length,
	DROP COLUMN actioninc_width,
	DROP COLUMN actioninc_height,
	DROP COLUMN actioninc_package,
	DROP COLUMN actioninc_origincode,
	DROP COLUMN actioninc_insurable,
	DROP COLUMN actioninc_handlingcode,
	DROP COLUMN actioninc_fixeddollaramount,
	DROP COLUMN actioninc_servicecodes,
	DROP COLUMN actioninc_specialaccessorialfees,
	DROP COLUMN actioninc_fixedfeecode,
	DROP COLUMN actioninc_fixedfee1,
	DROP COLUMN actioninc_fixedfee2
	");
	$db->Execute("ALTER TABLE ". TABLE_ORDERS ." 
	DROP COLUMN actioninc_packages
	");
  }
  
  /**
   * Internal list of configuration keys used for configuration of the module
   *
   * @return array
   */
function keys() {
    return array('MODULE_SHIPPING_AUCTIONINC_STATUS','MODULE_SHIPPING_AUCTIONINC_APIKEY','MODULE_SHIPPING_AUCTIONINC_DEFAULTCALCMETHOD', 'MODULE_SHIPPING_AUCTIONINC_DEFAULTPACKAGE','MODULE_SHIPPING_AUCTIONINC_DEFAULTFIXEDFEE', 'MODULE_SHIPPING_AUCTIONINC_DESTINATION', 'MODULE_SHIPPING_AUCTIONINC_INSURANCE', 'MODULE_SHIPPING_AUCTIONINC_TAX_CLASS', 'MODULE_SHIPPING_AUCTIONINC_TAX_BASIS', 'MODULE_SHIPPING_AUCTIONINC_ZONE', 'MODULE_SHIPPING_AUCTIONINC_DEBUG','MODULE_SHIPPING_AUCTIONINC_SORT_ORDER');
  }
}
?>