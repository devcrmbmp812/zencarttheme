<?php
$zip = new ZipArchive;
$res = $zip->open('boscoyostudio.zip');
if ($res === TRUE) {
  $zip->extractTo('D:/hshome/james70818/boscoyostudio.com/products/zen155/');
  $zip->close();
  echo "file boscoyostudio.zip unzipped \n"."<br/>";
} else {
  echo 'doh!';
}
?>