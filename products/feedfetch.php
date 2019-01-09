<?php

// maximum execution time in seconds
set_time_limit (24 * 60 * 60);

// folder to save downloaded files to. must end with slash
$destination_folder = 'D:/hshome/james70818/boscoyostudio.com/products/';

$url = "http://mysourcebest.com/product/boscoyostudio.zip";
$newfname = $destination_folder . basename($url);

$file = fopen ($url, "rb");
if ($file) {
  $newf = fopen ($newfname, "wb");

  if ($newf)
  while(!feof($file)) {
    fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 );
  }
}

if ($file) {
  fclose($file);
}

if ($newf) {
  fclose($newf);
}

?>
Feed File imported 