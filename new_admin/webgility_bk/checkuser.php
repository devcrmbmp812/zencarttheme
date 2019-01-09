<?php
//require_once('../../../wp-config.php');
	DEFINE('__ENCODE_RESPONSE', true); 
	define('IS_ADMIN_FLAG',true);
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


class CheckUser {
	
	public  function CheckUser($username,$password)
        { 
		
		if(!isset($username) || !isset($password) || $username == "" || $password == "" ){
			return 'Invalid login. Authorization failed';	
			exit;
		} 
		ini_set('display_errors' , 'Off');
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
					return 0;
				else 
				
					return "Invalid password. Authorization failed";
					
			}
			else
			{
			
				return 'Invalid login. Authorization failed';
			}
		}	
	}
	else
	{
		return 0;
	}
}
}
?>