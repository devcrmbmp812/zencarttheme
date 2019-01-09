<?php

$db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES
       ('Override Shipping No Price Title', 'CONFIGURATION_OVERRIDESHIPPING_NO_PRICE_TITLE', '', 'Title of the method that should show instead of \"Free Shipping\"', " . $configuration_group_id . ", 9992, NOW(), NOW(), NULL, NULL)");

$db->Execute("UPDATE ".TABLE_CONFIGURATION." SET configuration_value = '1.0.1' WHERE configuration_key='CONFIGURATION_OVERRIDESHIPPING_NO_PRICE_VERSION'");