<?php
####################################################################################################
## File : settings.php
## Author : Nils Laumaillé
## Description : tool settings
## 
## CAN BE CHANGED
## 
####################################################################################################

global $lang, $txt, $k, $chemin_passman, $url_passman, $mdp_complexite, $mng_pages;
global $smtp_server, $smtp_auth, $smtp_auth_username, $smtp_auth_password, $email_from,$email_from_name;

include('include.php');

$k['DEBUG'] = 0;    //actually not used
$k['user_password_limit'] = 30; //Number of days the user's password is available. After this limit, user has to enter a new password.
$k['charset'] = "ISO-8859-15";  //the charset you want to use
$k['prefix'] = "cpm_";  //tables prefix
@define('SALT', 'whateveryouwant'); //Define your encryption key => NeverChange it once it has been used !!!!!

### EMAIL PROPERTIES ###
$smtp_server = "mail.clinsight.eu";
$smtp_auth = false; //false or true
$smtp_auth_username = "";
$smtp_auth_password = "";
$email_from = "nils.laumaille@clinsight.fr";
$email_from_name = "Passwords Manager";


// DATABASE connexion parameters
if ( $_SERVER['HTTP_HOST'] == "localhost" ){
    //LOCAL => FOR TEST
    $db_host = "localhost";
    $db_login = "root";
    $db_password = ""; 
    $db_bdd = "passman"; 
}else{
    //HOSTED
    $db_host = "localhost";
    $db_login = "passmanAdmin";    
    $db_password = "rGVWNvt3eWUXj3Z9";  
    $db_bdd = "passman"; 
}

// connexion
@mysql_connect($db_host,$db_login,$db_password)
   or die("Impossible to get connected to server");
@mysql_select_db("$db_bdd")
   or die("Impossible to get connected to table");

$db = mysql_connect($db_host, $db_login, $db_password);  // 1
mysql_select_db($db_bdd,$db);                    // 2



?>
