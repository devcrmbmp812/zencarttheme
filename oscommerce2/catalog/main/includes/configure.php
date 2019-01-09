<?php
// Define the webserver and path parameters
// * DIR_FS_* = Filesystem directories (local/physical)
// * DIR_WS_* = Webserver directories (virtual/URL)
  define('HTTP_SERVER', 'http://'.'boscoyostudio.com'); // eg, http://localhost - should not be empty for productive servers
  define('HTTP_CATALOG_SERVER', 'http://'.'boscoyostudio.com');
  define('HTTPS_CATALOG_SERVER', '');
  define('ENABLE_SSL_CATALOG', 'false'); // secure webserver for catalog module
  define('DIR_FS_DOCUMENT_ROOT', 'D:/hshome/james70818/boscoyostudio.com/oscommerce2'.'/catalog/'); // where the pages are located on the server
  define('DIR_WS_ADMIN', '/oscommerce2'.'/catalog/main/'); // absolute path required
  define('DIR_FS_ADMIN', 'D:/hshome/james70818/boscoyostudio.com/oscommerce2'.'/catalog/main/'); // absolute pate required
  define('DIR_WS_CATALOG', '/oscommerce2'.'/catalog/'); // absolute path required
  define('DIR_FS_CATALOG', 'D:/hshome/james70818/boscoyostudio.com/oscommerce2'.'/catalog/'); // absolute path required
  define('DIR_WS_IMAGES', 'images/');
  define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
  define('DIR_WS_CATALOG_IMAGES', DIR_WS_CATALOG . 'images/');
  define('DIR_WS_INCLUDES', 'includes/');
  define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
  define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
  define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
  define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
  define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');
  define('DIR_WS_CATALOG_LANGUAGES', DIR_WS_CATALOG . 'includes/languages/');
  define('DIR_FS_CATALOG_LANGUAGES', DIR_FS_CATALOG . 'includes/languages/');
  define('DIR_FS_CATALOG_IMAGES', DIR_FS_CATALOG . 'images/');
  define('DIR_FS_CATALOG_MODULES', DIR_FS_CATALOG . 'includes/modules/');
  define('DIR_FS_BACKUP', DIR_FS_ADMIN . 'backups/');

// define our database connection
  define('DB_SERVER', 'mysql1002.ixwebhosting.com'); // eg, localhost - should not be empty for productive servers
  define('DB_SERVER_USERNAME', 'james70_oscomme');
  define('DB_SERVER_PASSWORD', 'MwWr13ylPK');
  define('DB_DATABASE', 'james70_oscommerce2');
  define('USE_PCONNECT', 'false'); // use persisstent connections?
  define('STORE_SESSIONS', 'mysql'); // leave empty '' for default handler or set to 'mysql'
?>
