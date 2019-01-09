<?php 
/* Catalog-on-Demand.com Data File Exporter for Zen Cart 
 * 
 * Copyright © 2013. Object Publishing Software, Inc. All Rights Reserved.
 * Website: http://www.catalog-on-demand.com
 * */
/*
 * Change this with a password of your choice. 
 */
define( "PASSWORD", "Hohe6244" );
/*
 * Script version - use it with support requests etc.
 */
define( "VERSION", "2014-11-17" );
/*
 * Do not change the rest of the script text
 */
define( "ZENCARTROOT", realpath(dirname(__FILE__) ) );
require( 'includes/application_top.php' );
$_DEBUG = isset($_REQUEST["_DEBUG"]) ? $_REQUEST["_DEBUG"] : "0";
$_INFO = isset($_REQUEST["_INFO"]) ? $_REQUEST["_INFO"] : "0";
$LanguageCode = isset($_REQUEST["Language"]) ? trim( $_REQUEST["Language"] ) : FALSE;
$Class = isset($_REQUEST["Class"]) ? trim( $_REQUEST["Class"] ) : "Info";
$Password = isset($_REQUEST["Password"]) ? trim( $_REQUEST["Password"] ) : "";
$RequiredPassword = trim( PASSWORD );
// Headers
$gmdate_mod = gmdate( 'D, d M Y H:i:s', time() ).' GMT';
header('Last-Modified: '.$gmdate_mod);
header('Content-Type: text/plain; charset=UTF-8');
header('Content-Disposition: inline; filename="'.$Class.'.txt"');
header('X-CoDSoftware-Version: '.VERSION);
// Enable errors display / list versions
ini_set('display_errors', '1');
ini_set('error_reporting', E_ALL);
ini_set('html_errors', '0');
ini_set('xmlrpc_errors', '0');
ini_set('error_prepend_string', "");
ini_set('error_append_string', "");
// Set an error handler for warnings and notices.
function myErrorHandler( $errno, $errstr, $errfile, $errline ) {
  echo "WARNING: ".$errstr." (line ".$errline.")
";
  return true;
}
set_error_handler('myErrorHandler');
$ceon_uri_mapping_version = FALSE;
try{
  $rs = mysql_query( "SELECT version FROM ".TABLE_CEON_URI_MAPPING_CONFIGS." ORDER BY id DESC", $db->link );
  if( $rs ){
    $r = mysql_fetch_row( $rs );
    if( $r ) $ceon_uri_mapping_version = $r[ 0 ];
    else echo "WARNING: ".TABLE_CEON_URI_MAPPING_CONFIGS." table is empty
";
    mysql_free_result( $rs );
  }
}catch (Exception $e) {
  echo "WARNING: no ".TABLE_CEON_URI_MAPPING_CONFIGS." table 
";
  $ceon_uri_mapping_version = FALSE;
}
// Info output
if( $_DEBUG || $_INFO ) {
  $started_time = time();
  echo "PHP version: ".phpversion()."  
Zen Cart version: ".PROJECT_VERSION_MAJOR.".".PROJECT_VERSION_MINOR."
Ceon URI Mapping version: ".( $ceon_uri_mapping_version ? $ceon_uri_mapping_version : "none" )."
Script version: ".VERSION."
Default language: ".DEFAULT_LANGUAGE."
=========================================================================================================
";
}
// Debug output
if( $_DEBUG ) echo "Class=".$Class."
Password=".$Password."
Language=".$LanguageCode."
";
//================== common code end ==================
// Check if a password is defined
if( empty( $RequiredPassword ) )
  die('ERROR: A blank password is not allowed.  Edit this script and set a password.');
// Check the password
if( $Password != $RequiredPassword )
  die('ERROR: The specified password is invalid.');
//================== common code start ==================
if( $Class == "DataFile" ) {
  $getactualprice = ( isset($_REQUEST["getactualprice"]) && $_REQUEST["getactualprice"] == "1" );
  $includetaxes = ( isset($_REQUEST["includetaxes"]) && $_REQUEST["includetaxes"] == "1" );
  if( $_DEBUG ) echo "getactualprice=".$getactualprice."
includetaxes=".$includetaxes."
";
}else if( $Class == "CatalogSection" ) {
}else if( $Class == "CatSectionAttrs" ) {
}else if( $Class == "CatalogProject" ) {
}
// Increase memory limit to 1024M
ini_set('memory_limit','1024M');
// Increase maximum execution time to 6 hours
ini_set('max_execution_time',28800);
// Make sure GC is enabled
if( function_exists( "gc_enable" ) )
  gc_enable();
else if( $_DEBUG || $_INFO )
  echo "gc_enable does not exist.
";
if( $_INFO ) {
  $lng = $db->Execute( "SELECT languages_id,code FROM ".TABLE_LANGUAGES.";" );
  while( !$lng->EOF ) {
    echo "language #".$lng->fields["languages_id"]." - ".$lng->fields["code"]."
";
    $lng->MoveNext();
  }
  if( function_exists( "gc_enabled" ) )
    echo "gc_enabled=".gc_enabled()."
";
  else
    echo "gc_enabled does not exist.
";
  echo "=========================================================================================================
";
  die();
}
// Determine / check language
$DefLanguageID = 0;
$DefLanguageCode = constant( "DEFAULT_LANGUAGE" );
$LanguageID = 0;
if( $LanguageCode ) $LanguageCode = trim( $LanguageCode );
if( !$LanguageCode ) $LanguageCode = $DefLanguageCode;
$lng = $db->Execute( "SELECT languages_id,code FROM ".TABLE_LANGUAGES.";" );
while( !$lng->EOF ) {
  if($_DEBUG) echo "language #".$lng->fields["languages_id"]." - ".$lng->fields["code"]."
";
  if( $LanguageCode == $lng->fields["code"] )
    $LanguageID = $lng->fields["languages_id"];
  if( $DefLanguageCode == $lng->fields["code"] )
    $DefLanguageID = $lng->fields["languages_id"];
  $lng->MoveNext();
}
if( !$LanguageID )
  die( "ERROR: Language \"".$LanguageCode."\" not defined.");
// globals
class Section{
  var $ID;
  var $parentID;
  var $sort_order = 0;
  var $name;
  var $desc;
  var $image;
  var $childs = FALSE;
}
class Mfr{
  var $ID;
  var $name;
  var $logo;
}
function cleanStr( &$str ){ return str_replace("\n"," ", str_replace("\r"," ", str_replace("\r\n"," ", str_replace("\t","    ", $str ) ) ) ); }
function improvePrice( $rv ){
  if( $rv == 0 ) return "";  
  else $rv = "".$rv;
  $tail = strrchr( $rv, "." );
  if( !empty( $tail ) && strlen( $tail ) > 3 ) $rv = substr( $rv, 0, strlen( $rv ) - ( strlen( $tail ) - 3 ) );
  else if( !$tail )                            $rv = $rv.".00";
  return $rv;
}
function my_urlencode( $image ){
  if( strpos( $image, "/" ) < 0 ) return urlencode( $image );
  $arr = explode( "/", $image );
  $arr2 = array();
  foreach( $arr as $part ) $arr2[] = urlencode( $part );
  return implode( "/", $arr2 );
}
// class files
if( $Class == "DataFile" ) {
  $mfrMap = array();
  echo "itemNumber\titemQty\titemUom\titemPrice\titemDescription\titemLink\titemAttributes\titemGraphic\tproductName\tproductDescription\tproductGraphic\tproductLink\tproductAttributes\tManufacturer\tManufacturerLogo\titemSequence
";  
  if( $DefLanguageID != $LanguageID )
    $prods = "SELECT p.*,pd.products_name as lname,dpd.products_name as dlname,pd.products_description as ldesc,dpd.products_description as dldesc,pd.products_url as lurl,dpd.products_url as dlurl FROM ".TABLE_PRODUCTS." p
                 LEFT OUTER JOIN ".TABLE_PRODUCTS_DESCRIPTION." pd ON (pd.products_id=p.products_id and pd.language_id=".$LanguageID.")
                 LEFT OUTER JOIN ".TABLE_PRODUCTS_DESCRIPTION." dpd ON (dpd.products_id=p.products_id and dpd.language_id=".$DefLanguageID.")
                 WHERE p.products_status=1 ORDER BY lname,dlname";
  else               
    $prods = "SELECT p.*,pd.products_name as lname,pd.products_description as ldesc,pd.products_url as lurl FROM ".TABLE_PRODUCTS." p
                 LEFT OUTER JOIN ".TABLE_PRODUCTS_DESCRIPTION." pd ON (pd.products_id=p.products_id and pd.language_id=".$LanguageID.")
                 WHERE p.products_status=1 ORDER BY lname";
  $prods = $db->Execute( $prods );
  while( !$prods->EOF ){
    $prodID = "".$prods->fields["products_id"];
    $prodURL = FALSE;
    if( $ceon_uri_mapping_version ){
      $rs = mysql_query( "SELECT uri FROM ".TABLE_CEON_URI_MAPPINGS." WHERE language_id=".$LanguageID."  AND current_uri=1 AND main_page='product_info' AND associated_db_id=".$prodID."", $db->link );
      if( $rs ){
        $r = mysql_fetch_row( $rs );
        if( $r ) $prodURL = $r[ 0 ];
        mysql_free_result( $rs );
      }
      if( !$prodURL && $DefLanguageID != $LanguageID ){
        $rs = mysql_query( "SELECT uri FROM ".TABLE_CEON_URI_MAPPINGS." WHERE language_id=".$DefLanguageID."  AND current_uri=1 AND main_page='product_info' AND associated_db_id=".$prodID."", $db->link );
        if( $rs ){
          $r = mysql_fetch_row( $rs );
          if( $r ) $prodURL = $r[ 0 ];
          mysql_free_result( $rs );
        }
      }
      if( $prodURL ){
        $prodURL = "http://".$_SERVER["SERVER_NAME"].$prodURL;
      }
    }    
    $prodName = isset( $prods->fields["lname"] ) ? trim( cleanStr( $prods->fields["lname"] ) ) : FALSE;
    if( !$prodName && isset( $prods->fields["dlname"] ) )
      $prodName = trim( cleanStr( $prods->fields["dlname"] ) );
    $prodGroup = $prodID."#$#".( $prodName ? $prodName : "[unnamed product]" );
    $prodDesc = isset( $prods->fields["ldesc"] ) ? trim( cleanStr( $prods->fields["ldesc"] ) ) : FALSE;
    if( !$prodDesc && isset( $prods->fields["dldesc"] ) )
      $prodDesc = trim( cleanStr( $prods->fields["dldesc"] ) );
    if( !$prodURL ){
      $prodURL = isset( $prods->fields["lurl"] ) ? trim( cleanStr( $prods->fields["lurl"] ) ) : FALSE;
      if( !$prodURL && isset( $prods->fields["dlurl"] ) )
        $prodURL = trim( cleanStr( $prods->fields["dlurl"] ) );
      if( $prodURL ){
        if( substr( strtolower( $prodURL ), 0, 4 ) != "http" )
          $prodURL = "http://".$prodURL;
      }
    }
    $prodImage = isset( $prods->fields["products_image"] ) ? trim( cleanStr( $prods->fields["products_image"] ) ) : FALSE;
    if( $prodImage ){
      $name = $prodImage;
      $pos = strrpos( $name, "/" );
      if( $pos >= 0 ) $name = substr( $name, $pos + 1 );
      if( $name == "no_picture.gif" ) $prodImage = "";
      else              $prodImage = "http://".$_SERVER["SERVER_NAME"]."/images/".my_urlencode( $prodImage );
    }
    $mfrID = isset( $prods->fields["manufacturers_id"] ) ? $prods->fields["manufacturers_id"] : FALSE;
    $mfr = FALSE;
    if( $mfrID && $mfrID > 0 ){
      if( isset( $mfrMap[ $mfrID ] ) )
        $mfr = $mfrMap[ $mfrID ];
      else {
        $manufacturers = $db->Execute( "SELECT * FROM ".TABLE_MANUFACTURERS." WHERE manufacturers_id=".$mfrID );      
        if( !$manufacturers->EOF ){
          $mfr = new Mfr();
          $mfr->ID = $mfrID; 
          $mfr->name = isset( $manufacturers->fields["manufacturers_name"] ) ? trim( cleanStr( $manufacturers->fields["manufacturers_name"] ) ) : "Manufacturer #".$mfrID;
          $mfr->logo = isset( $manufacturers->fields["manufacturers_image"] ) ? trim( cleanStr( $manufacturers->fields["manufacturers_image"] ) ) : FALSE;
          if( $mfr->logo ){
            $name = $mfr->logo;
            $pos = strrpos( $name, "/" );
            if( $pos >= 0 ) $name = substr( $name, $pos + 1 );
            if( $name == "no_picture.gif" ) $mfr->logo = "";
            else                            $mfr->logo = "http://".$_SERVER["SERVER_NAME"]."/images/".my_urlencode( $mfr->logo );
          }
          $mfrMap[ $mfrID ] = $mfr;
        }
      }
    }
    $itemNumber = isset( $prods->fields["products_model"] ) ? trim( cleanStr( $prods->fields["products_model"] ) ) : FALSE;
    if( !$itemNumber )
      $itemNumber = $prodName;
    if( !$itemNumber )
      $itemNumber = "#".$prodID;
    if( $getactualprice )
      $price = zen_get_products_actual_price( $prods->fields["products_id"] );  
    else  
      $price = zen_get_products_base_price( $prods->fields["products_id"] );  
    $taxRate = 0;
    if( $includetaxes ){
      $taxRate = zen_get_tax_rate( $prods->fields["products_tax_class_id"] );
      if( $taxRate != 0 )
        $price = $price * ( 100.0 + $taxRate ) / 100.0;
    }
    $price = improvePrice( $price );     
    if($_DEBUG){
      echo "product ".$prodGroup."
";
      echo "\"".$prodDesc."\"
";
      echo "URL=".$prodURL."
";
      echo "image=".$prodImage."
";
      echo "mfr=".( $mfr ? $mfr->name : "" )."
";
      echo "mfrLogo=".( $mfr ? $mfr->logo : "" )."
";
      echo "itemNumber=".$itemNumber."
";
      $base_price = zen_get_products_base_price( $prods->fields["products_id"], false);
      $special_price = zen_get_products_special_price( $prods->fields["products_id"], false);
      $special_price_only = zen_get_products_special_price( $prods->fields["products_id"], true );
      $actual_price = zen_get_products_actual_price( $prods->fields["products_id"] );
      echo "base_price=".$base_price." special_price=".$special_price." special_price_only=".$special_price_only." actual_price=".$actual_price."
";
      echo "taxRate=".$taxRate."
";
    }
    echo $itemNumber
      . "\t" //ItemQty
      . "\t" //ItemUom
      . "\t". $price //ItemPrice
      . "\t" //ItemDescription
      . "\t" //ItemLink
      . "\t" //ItemAttributes
      . "\t" //ItemGraphic
      . "\t". $prodGroup //ProductName
      . "\t". $prodDesc
      . "\t". $prodImage //ProductGraphic
      . "\t". $prodURL //ProductLink
      . "\t" //ProductAttributes
      . "\t". ( $mfr ? $mfr->name : "" ) //Manufacturer
      . "\t". ( $mfr ? $mfr->logo : "" ) //ManufacturerLogo
      . "\t1"
      . "\n";
    $prods->MoveNext();
  }
  echo "==EOF==";
} else if( $Class == "CatalogSection" ) {
  $sections = array();
  $sectionsByID = array();
  $cat = $db->Execute( "SELECT categories_id,parent_id,sort_order FROM ".TABLE_CATEGORIES." WHERE categories_status>0 ORDER BY parent_id,sort_order;" );
  while( !$cat->EOF ) {
    $sec = new Section();
    $sec->ID = intval( $cat->fields["categories_id"] );
    $sec->parentID = isset( $cat->fields["parent_id"] ) ? intval( $cat->fields["parent_id"] ) : 0;
    $sec->sort_order = $cat->fields["sort_order"];
    $catdesc = $db->Execute( "SELECT categories_name FROM ".TABLE_CATEGORIES_DESCRIPTION." WHERE categories_id=".$sec->ID." AND language_id=".$LanguageID.";" );
    if( !$catdesc->EOF && isset( $catdesc->fields["categories_name"] ) )
      $sec->name = trim( $catdesc->fields["categories_name"] );
    if( !$sec->name && $DefLanguageID != $LanguageID ){
      $catdesc = $db->Execute( "SELECT categories_name FROM ".TABLE_CATEGORIES_DESCRIPTION." WHERE categories_id=".$sec->ID." AND language_id=".$DefLanguageID.";" );
      if( !$catdesc->EOF && isset( $catdesc->fields["categories_name"] ) )
        $sec->name = trim( $catdesc->fields["categories_name"] );
    }
    if( !$sec->name )
      $sec->name = "Section #".$sec->ID;
    if($_DEBUG) echo "section #".$sec->ID." - ".$sec->name." (parent ".$sec->parentID." sort ".$sec->sort_order.")
";
    $sections[] = $sec;
    $sectionsByID[ $sec->ID ] = $sec;
    $cat->MoveNext();
  }
  $changed = TRUE;
  $all = 0;
  while( $changed ){
    $changed = FALSE;
    $index = 1;
    while( TRUE ){
      if( !isset( $sections[ $index ] ) ) break;
      $prevsec = $sections[ $index - 1 ];
      $sec = $sections[ $index ];
      if( $prevsec->parentID == $sec->parentID && $prevsec->sort_order == $sec->sort_order ){
        $prevName = strtolower( $prevsec->name );
        $name = strtolower( $sec->name );
        if( $name < $prevName ){
          $sections[ $index - 1 ] = $sec;
          $sections[ $index ] = $prevsec;
          $changed = TRUE;
          if($_DEBUG) echo "changed sections #".$prevsec->ID." - #".$sec->ID."
";
        }
      }
      $index++;
      $all++;
    }
    if( $all > 1000000000 ) break;
  }
  $index = 0;
  $rootchilds = array();
  while( TRUE ){
    if( !isset( $sections[ $index ] ) ) break;
    $sec = $sections[ $index ];
    if( $sec->parentID > 0 ){
      if( isset( $sectionsByID[ $sec->parentID ] ) ){
        $parentsec = $sectionsByID[ $sec->parentID ];
        if( !$parentsec->childs ){
          $parentsec->childs = array();
        }
        $parentsec->childs[] = $sec->ID;
      }
    } else {
      $rootchilds[] = $sec->ID;
    }
    $index++;
  }
  $sort_order = 1;
  foreach( $rootchilds as $childID ){
    $sec = $sectionsByID[ $childID ];
    $sec->sort_order = $sort_order;
    $sort_order++;
  }
  $lastTopSeq = $sort_order;
  $index = 0;
  while( TRUE ){
    if( !isset( $sections[ $index ] ) ) break;
    $sec = $sections[ $index ];
    if( $sec->childs ){
      $sort_order = 1;
      foreach( $sec->childs as $childID ){
        $childsec = $sectionsByID[ $childID ];
        $childsec->sort_order = $sort_order;
        $sort_order++;
      }
    }
    $index++;
  }
  if( $_DEBUG ) {
    $index = 0;
    while( TRUE ){
      if( !isset( $sections[ $index ] ) ) break;
      $sec = $sections[ $index ];
      echo "".$index." - section #".$sec->ID." - ".$sec->name." (parent ".$sec->parentID." sort ".$sec->sort_order.")
";
      $index++;
    }
  }
  echo "sec_Project\tsec_Sequence\tsec_HierarchyPath\tsec_Flag
";
  foreach( $sections as $sec ){
    $seq = "".$sec->sort_order;
    $hpath = "".cleanStr( $sec->name );
    while( $sec->parentID > 0 ){
      if( !isset( $sectionsByID[ $sec->parentID ] ) ) break;
      $sec = $sectionsByID[ $sec->parentID ];
      $seq = "".$sec->sort_order.",".$seq;
      $hpath = "".cleanStr( $sec->name )."#$#".$hpath;
    }
    echo "General\t".$seq."\t".$hpath."\t
";
  }
  echo "General\t".$lastTopSeq."\tUncategorized\t
==EOF==";
} else if( $Class == "CatSectionAttrs" ) {
  $sections = array();
  $sectionsByID = array();
  $cat = $db->Execute( "SELECT categories_id,parent_id,categories_image FROM ".TABLE_CATEGORIES." WHERE categories_status>0 ORDER BY sort_order;" );
  while( !$cat->EOF ) {
    $sec = new Section();
    $sec->ID = intval( $cat->fields["categories_id"] );
    $sec->parentID = isset( $cat->fields["parent_id"] ) ? intval( $cat->fields["parent_id"] ) : 0;
    $sec->image = isset( $cat->fields["categories_image"] ) ? trim( $cat->fields["categories_image"] ) : FALSE;
    $catdesc = $db->Execute( "SELECT categories_name,categories_description FROM ".TABLE_CATEGORIES_DESCRIPTION." WHERE categories_id=".$sec->ID." AND language_id=".$LanguageID.";" );
    if( !$catdesc->EOF && isset( $catdesc->fields["categories_name"] ) ) {
      $sec->name = trim( $catdesc->fields["categories_name"] );
      if( isset( $catdesc->fields["categories_description"] ) )
        $sec->desc = trim( $catdesc->fields["categories_description"] );
    }
    if( !$sec->name && $DefLanguageID != $LanguageID ){
      $catdesc = $db->Execute( "SELECT categories_name,categories_description FROM ".TABLE_CATEGORIES_DESCRIPTION." WHERE categories_id=".$sec->ID." AND language_id=".$DefLanguageID.";" );
      if( !$catdesc->EOF && isset( $catdesc->fields["categories_name"] ) )
        $sec->name = trim( $catdesc->fields["categories_name"] );
      if( !$sec->desc && isset( $catdesc->fields["categories_description"] ) )
        $sec->desc = trim( $catdesc->fields["categories_description"] );
    }
    if( !$sec->name )
      $sec->name = "Section #".$sec->ID;
    if($_DEBUG) echo "section #".$sec->ID." - ".$sec->name." (parent ".$sec->parentID."), desc=\"".$sec->desc."\", image=".$sec->image."
";
    if( $sec->image ){
      $name = $sec->image;
      $pos = strrpos( $name, "/" );
      if( $pos >= 0 ) $name = substr( $name, $pos + 1 );
      if( $name == "no_picture.gif" ) $sec->image = "";
      else                            $sec->image = "http://".$_SERVER["SERVER_NAME"]."/images/".my_urlencode( $sec->image );
    }  
    $sections[] = $sec;
    $sectionsByID[ $sec->ID ] = $sec;
    $cat->MoveNext();
  }
  echo "secAttrs_ProjName\tsecAttrs_HierarchyPath\tsecAttrs_Flag\tsecAttrs_Description\tsecAttrs_Notes\tsecAttrs_Image
";
  foreach( $sections as $sec ){
    if( !$sec->desc && !$sec->image ) continue;
    $hpath = "".cleanStr( $sec->name );
    while( $sec->parentID > 0 ){
      if( !isset( $sectionsByID[ $sec->parentID ] ) ) break;
      $sec = $sectionsByID[ $sec->parentID ];
      $hpath = "".cleanStr( $sec->name )."#$#".$hpath;
    }
    echo "General\t".$hpath."\t\t".( $sec->desc ? cleanStr( $sec->desc ) : "" )."\t\t".$sec->image."
";
  }
  echo "==EOF==";
} else if( $Class == "CatalogProject" ) {
  $linkedProds = array();
  $prods = $db->Execute( "SELECT products_id FROM ".TABLE_PRODUCTS." WHERE products_status=1" );
  while( !$prods->EOF ){
    $prodID = "".$prods->fields["products_id"];
    $linkedProds[ $prodID ] = FALSE;
    $prods->MoveNext();
  }
  $sections = array();
  $sectionsByID = array();
  $cat = $db->Execute( "SELECT categories_id,parent_id FROM ".TABLE_CATEGORIES." WHERE categories_status>0 ORDER BY parent_id,sort_order;" );
  while( !$cat->EOF ) {
    $sec = new Section();
    $sec->ID = intval( $cat->fields["categories_id"] );
    $sec->parentID = isset( $cat->fields["parent_id"] ) ? intval( $cat->fields["parent_id"] ) : 0;
    $catdesc = $db->Execute( "SELECT categories_name FROM ".TABLE_CATEGORIES_DESCRIPTION." WHERE categories_id=".$sec->ID." AND language_id=".$LanguageID.";" );
    if( !$catdesc->EOF && isset( $catdesc->fields["categories_name"] ) )
      $sec->name = trim( $catdesc->fields["categories_name"] );
    if( !$sec->name && $DefLanguageID != $LanguageID ){
      $catdesc = $db->Execute( "SELECT categories_name FROM ".TABLE_CATEGORIES_DESCRIPTION." WHERE categories_id=".$sec->ID." AND language_id=".$DefLanguageID.";" );
      if( !$catdesc->EOF && isset( $catdesc->fields["categories_name"] ) )
        $sec->name = trim( $catdesc->fields["categories_name"] );
    }
    if( !$sec->name )
      $sec->name = "Section #".$sec->ID;
    if($_DEBUG) echo "section #".$sec->ID." - ".$sec->name." (parent ".$sec->parentID.")
";
    $sections[] = $sec;
    $sectionsByID[ $sec->ID ] = $sec;
    $cat->MoveNext();
  }
  echo "proj_Key\tproj_ProdName\tproj_Sequence\tproj_Name\tproj_HierarchyPath\tproj_Flag\tproj_ProdLayout
";
  foreach( $sections as $sec ){
    $secID = $sec->ID;
    $hpath = "".cleanStr( $sec->name );
    while( $sec->parentID > 0 ){
      if( !isset( $sectionsByID[ $sec->parentID ] ) ) break;
      $sec = $sectionsByID[ $sec->parentID ];
      $hpath = "".cleanStr( $sec->name )."#$#".$hpath;
    }
    if($_DEBUG) echo "section ".$hpath."
";
    if( $DefLanguageID != $LanguageID )
      $prods = "SELECT ptc.*,pd.products_name as lname,dpd.products_name as dlname from (".TABLE_PRODUCTS_TO_CATEGORIES." ptc,".TABLE_PRODUCTS." p)
                   LEFT OUTER JOIN ".TABLE_PRODUCTS_DESCRIPTION." pd ON (ptc.products_id=pd.products_id and pd.language_id=".$LanguageID.")
                   LEFT OUTER JOIN ".TABLE_PRODUCTS_DESCRIPTION." dpd ON (ptc.products_id=dpd.products_id and dpd.language_id=".$DefLanguageID.")
                   WHERE ptc.categories_id=" .$secID." AND p.products_id = ptc.products_id AND p.products_status=1 ORDER BY lname,dlname";
    else               
      $prods = "SELECT ptc.*,pd.products_name as lname from (".TABLE_PRODUCTS_TO_CATEGORIES." ptc,".TABLE_PRODUCTS." p)
                   LEFT OUTER JOIN ".TABLE_PRODUCTS_DESCRIPTION." pd ON (ptc.products_id=pd.products_id and pd.language_id=".$LanguageID.")
                   WHERE ptc.categories_id=" .$secID." AND p.products_id = ptc.products_id AND p.products_status=1 ORDER BY lname";
    $prods = $db->Execute( $prods );
    $position = 1;
    while( !$prods->EOF ){
      $prodID = "".$prods->fields["products_id"];
      $linkedProds[ $prodID ] = TRUE;
      $prodName = isset( $prods->fields["lname"] ) ? trim( $prods->fields["lname"] ) : FALSE;
      if( !$prodName && isset( $prods->fields["dlname"] ) )
        $prodName = trim( $prods->fields["dlname"] );
      $prodName = $prodID."#$#".( $prodName ? $prodName : "[unnamed product]" );
      $prodName = cleanStr( $prodName );
      echo $prodName."\t".$prodName."\t".$position."\tGeneral\t".$hpath."\t\t
";
      $prods->MoveNext();
      $position++;
    }
  }
  $position = 1;
  foreach( $linkedProds as $prodID => $linked ){
    if( $linked ) continue;
    if($_DEBUG) echo "not linked: #".$prodID."
";
    if( $DefLanguageID != $LanguageID )
      $prods = "SELECT pd.products_name as lname,dpd.products_name as dlname FROM ".TABLE_PRODUCTS." p
                   LEFT OUTER JOIN ".TABLE_PRODUCTS_DESCRIPTION." pd ON (p.products_id=pd.products_id and pd.language_id=".$LanguageID.")
                   LEFT OUTER JOIN ".TABLE_PRODUCTS_DESCRIPTION." dpd ON (p.products_id=dpd.products_id and dpd.language_id=".$DefLanguageID.")
                   WHERE p.products_id=".$prodID."";
    else               
      $prods = "SELECT pd.products_name as lname FROM ".TABLE_PRODUCTS." p
                   LEFT OUTER JOIN ".TABLE_PRODUCTS_DESCRIPTION." pd ON (p.products_id=pd.products_id and pd.language_id=".$LanguageID.")
                   WHERE p.products_id=".$prodID."";
    $prods = $db->Execute( $prods );
    if( !$prods->EOF ){
      $prodName = isset( $prods->fields["lname"] ) ? trim( $prods->fields["lname"] ) : FALSE;
      if( !$prodName && isset( $prods->fields["dlname"] ) )
        $prodName = trim( $prods->fields["dlname"] );
      $prodName = $prodID."#$#".( $prodName ? $prodName : "[unnamed product]" );
      $prodName = cleanStr( $prodName );
      echo $prodName."\t".$prodName."\t".$position."\tGeneral\tUncategorized\t\t
";
      $position++;
    }
  }
  echo "==EOF==";
} else if($_DEBUG){
  echo "max_execution_time=".ini_get("max_execution_time")."
memory_limit=".ini_get("memory_limit")."
";
  if( function_exists( "gc_enabled" ) ) echo "gc_enabled=".gc_enabled()."
";
  else echo "gc_enabled does not exist.
";
  echo "=========================================================================================================
";
} else {
  echo "==EOF==";
}
if($_DEBUG) echo "
executed in ".( time() - $started_time )." sec. 
";
die();
//
//
//
//
//
//
/* RELEASE NOTES
 *  
 * 2014-11-17
 *  1. Ceon URI Mapping module incorporated
 *  
 * 2014-01-07
 *  1. "get actual price" option added
 *  
 * 2014-01-06
 *  1. Subsection sequencing bug fixed
 *  2. Improved exporting of image URLs
 *  3. Added ignoring of no_picture.gif image assignments.
 *  
 * 2013-12-16
 *  First release
 */
?>

