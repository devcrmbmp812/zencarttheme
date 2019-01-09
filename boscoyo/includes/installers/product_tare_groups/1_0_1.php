<?php
//add field to product field
	if (!chk_field_column_exists(TABLE_PRODUCTS, 'products_tare_group')) $db->Execute("ALTER TABLE " . TABLE_PRODUCTS . " ADD COLUMN products_tare_group varchar(32);");
