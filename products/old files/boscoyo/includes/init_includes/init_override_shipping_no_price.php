<?php
  if (!defined('IS_ADMIN_FLAG')) {
    die('Illegal Access');
  }   
 
 $module_installer_directory =  DIR_FS_ADMIN.'includes/installers/override_shipping_no_price';
 $module_name = "Override Shipping No Price"; 

 if(defined('CONFIGURATION_OVERRIDESHIPPING_NO_PRICE_VERSION')) { $current_version =  CONFIGURATION_OVERRIDESHIPPING_NO_PRICE_VERSION; } else { $current_version = "0.0.0"; }
  
$configuration = $db->Execute("SELECT configuration_group_id FROM " . TABLE_CONFIGURATION_GROUP . " WHERE configuration_group_title = 'Shipping/Packaging' ORDER BY configuration_group_id ASC;");
$configuration_group_id = $configuration->fields['configuration_group_id'];
 
 $installers = scandir($module_installer_directory, 1);
 
 $newest_version = $installers[0];
 $newest_version = substr($newest_version,0,-4);
 
 
 sort($installers);
 if(version_compare($newest_version, $current_version) > 0){
     foreach ($installers as $installer) {
         if(version_compare($newest_version, substr($installer,0,-4) ) >= 0 && version_compare($current_version, substr($installer,0,-4) ) < 0 ){
         include($module_installer_directory.'/'.$installer);
         $current_version = str_replace("_", ".", substr($installer,0,-4));
         $messageStack->add("Installed ".$module_name." V ".$current_version, 'success');
         }
     }     
 }
 

 