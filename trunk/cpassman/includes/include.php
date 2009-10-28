<?php
####################################################################################################
## File : include.php
## Author : Nils Laumaillé
## Description : tool needed inclusions
## 
## DON'T CHANGE !!!
## 
####################################################################################################

//DONT'T CHANGE
$k['version'] = "1.02";
$k['tool_name'] = "Collaborative Passwords Manager";
$k['jquery-version'] = "1.3.2";
$k['jquery-ui-version'] = "1.7.2";
$k['jquery-ui-theme'] = "ui-lightness";

//Management Pages
$mng_pages = array(
    'manage_users' => 'users.php',
    'manage_groups' => 'groups.php',
    'manage_functions' => 'functions.php',
    'manage_views' => 'views.php',
    'administration' => 'admin.php'
);

//pw complexity levels
$mdp_complexite = array(
    0=>array(0,$txt['complex_level0']),
    25=>array(25,$txt['complex_level1']),
    50=>array(50,$txt['complex_level2']),
    60=>array(60,$txt['complex_level3']),
    70=>array(70,$txt['complex_level4']),
    80=>array(80,$txt['complex_level5']),
    90=>array(90,$txt['complex_level6'])
);

## VARIABLES ##
$chemin_passman = substr($_SERVER['DOCUMENT_ROOT'],0,strlen($_SERVER['DOCUMENT_ROOT'])-1).substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'],'/'));
$url_passman = 'http://' . $_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'],'/'));
?>
