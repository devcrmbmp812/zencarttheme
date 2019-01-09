<?php

$db->Execute("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES
       ('Override Shipping No Price Description', 'MODULE_SHIPPING_FREESHIPPER_TEXT_DESCRIPTION', 'Please Contact us for a freight Quote', 'This is the description of the method', " . $configuration_group_id . ", 9993, NOW(), NOW(), NULL, NULL),"
        . "('Override Shipping No Price Way', 'MODULE_SHIPPING_FREESHIPPER_TEXT_WAY', 'YOU MUST OBTAIN A QUOTE FOR SHIPPING', 'This is the part that is ussually in (_HERE_)', " . $configuration_group_id . ", 9993, NOW(), NOW(), NULL, NULL)");

$db->Execute("UPDATE ".TABLE_CONFIGURATION." SET configuration_value = '1.0.2' WHERE configuration_key='CONFIGURATION_OVERRIDESHIPPING_NO_PRICE_VERSION'");