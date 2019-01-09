<br />
<?php
  echo '<div id="savings_code">'; 
  echo  zen_draw_form('savings_code', zen_href_link(FILENAME_SAVINGS_CODE, '', $request_type, false), 'get');
  echo  zen_draw_hidden_field('main_page',FILENAME_SAVINGS_CODE);
  echo zen_hide_session_id();

  if (strtolower(IMAGE_USE_CSS_BUTTONS) == 'yes') {
    echo  zen_draw_input_field('keyword', '', 'size="30" maxlength="100" style="width: ' . ($column_width-30) . 'px" placeholder="' . SAVINGS_CODE_DEFAULT_TEXT . '"') . '<br />' . zen_image_submit (BUTTON_IMAGE_UPDATE,SAVINGS_CODE_BUTTON);
  } else {
    echo  zen_draw_input_field('keyword', '', 'size="30" maxlength="100" style="width: ' . ($column_width-30) . 'px" placeholder="' . SAVINGS_CODE_DEFAULT_TEXT . '" onfocus="if (this.value == \'' . SAVINGS_CODE_DEFAULT_TEXT . '\') this.value = \'\';" onblur="if (this.value == \'\') this.value = \'' . SAVINGS_CODE_DEFAULT_TEXT . '\';"') . '<br /><input type="submit" value="' . SAVINGS_CODE_BUTTON . '" style="width: 50px" />';
  }

  echo  "</form>";
  echo  '</div>';
