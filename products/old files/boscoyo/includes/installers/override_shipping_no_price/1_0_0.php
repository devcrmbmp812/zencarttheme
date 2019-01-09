<?php


$db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES
            ('Override Shipping No Price Verison', 'CONFIGURATION_OVERRIDESHIPPING_NO_PRICE_VERSION', '1.0.0', 'Version installed:', " . $configuration_group_id . ", 9991, NOW(), NOW(), NULL, NULL), 
            ('Override Shipping No Price Products', 'CONFIGURATION_OVERRIDESHIPPING_NO_PRICE_PRODUCTS', '', 'Products seperated by commas that change the nme of the free shipping method', " . $configuration_group_id . ", 9992, NOW(), NOW(), NULL, NULL),   
            ('Override Shipping No Price Enabled', 'CONFIGURATION_OVERRIDESHIPPING_NO_PRICE_ENABLED', 'false', 'If the Override Shipping No Price Module is enabled', " . $configuration_group_id . ", 9993, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\"true\", \"false\"),');");
