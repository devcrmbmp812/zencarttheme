<?php
/**
 * Common Template - tpl_tabular_display.php
 *
 * This file is used for generating tabular output where needed, based on the supplied array of table-cell contents.
 *
 * @package templateSystem
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: tpl_tabular_display.php 3957 2006-07-13 07:27:06Z drbyte $
 */

//print_r($list_box_contents);

?>
<ul class="product_list row list">
    <?php
        for($row=1; $row<sizeof($list_box_contents); $row++) 
        {
    ?>
    <li class="col-xs-12">
        <div class="product-container">
            <div class="row">
                <?php
                    for($col=0;$col<sizeof($list_box_contents[$row]);$col++)  
                    {
                        if (isset($list_box_contents[$row][$col]['text'])) 
                        {
                            if($col == 0) // product image
                            {
                                ?>
                                <div class="img">
                                        <?php echo $list_box_contents[$row][$col]['text'] ?>
                                </div>
                                <?php
                            }
                            if($col == 1) // product description
                            {
                                ?>
                                <div class="center-block col-xs-4 col-xs-7 col-md-4">
                                    <?php echo $list_box_contents[$row][$col]['text'] ?>
                                </div>
                                <?php
                            }
                            if($col == 2)
                            {
                                ?>
                                <div class="product-buttons">
                                    <div class="content_price col-xs-5 col-md-12">
                                        <?php echo $list_box_contents[$row][$col]['text'] ?>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                    }
                ?>
            </div>
        </div>
    </li>
    <?php
        } 
    ?>
</ul>