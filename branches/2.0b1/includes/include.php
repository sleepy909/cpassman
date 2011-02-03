<?php
####################################################################################################
## File : include.php
## Author : Nils Laumaillé
## Description : tool needed inclusions
##
## DON'T CHANGE !!!
##
####################################################################################################

//DONT'T CHANGE BELOW THIS LINE
global $settings;

$k['version'] = "2.00b1";
$k['tool_name'] = "cPassMan";
$k['jquery-version'] = "1.4.4";
$k['jquery-ui-version'] = "1.8.7";
$k['jquery-ui-theme'] = "overcast";
$k['one_month_seconds'] = 2592000;
$k['image_file_ext'] = array('jpg','gif','png','jpeg','tiff','bmp');
$k['office_file_ext'] = array('xls','xlsx','docx','doc','csv','ppt','pptx');

//Management Pages
$mngPages = array(
    'manage_users' => 'users.php',
    'manage_folders' => 'folders.php',
    'manage_roles' => 'roles.php',
    'manage_views' => 'views.php',
    'manage_main' => 'admin.php',
    'manage_settings' => 'admin.settings.php'
);

//languages
$k['langs'] = array(
	'english'=>'en',
	'french'=>'fr',
	'spanish'=>'es',
	'czech'=>'cs',
	'german'=>'de',
    'russian'=>'ru'
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
?>