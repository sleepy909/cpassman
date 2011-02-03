<?php
####################################################################################################
## File : downloadFile.php
## Author : Nils Laumaillé
## Description : Permits to download any attached file. It also forces the download.
##
## DON'T CHANGE !!!
##
####################################################################################################

header("Content-disposition: attachment; filename=".rawurldecode($_GET['name']));
header("Content-Type: application/force-download");
header("Content-Transfer-Encoding: ".$_GET['type']."\n"); // Surtout ne pas enlever le \n
header("Pragma: no-cache");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0, public");
header("Expires: 0");
readfile($_GET['path']);
?>
