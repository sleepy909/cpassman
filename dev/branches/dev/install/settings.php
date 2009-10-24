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
$k['charset'] = "";  //the charset you want to use	: French => ISO-8859-15
@define('SALT', 'whateveryouwant'); //Define your encryption key => NeverChange it once it has been used !!!!!

### EMAIL PROPERTIES ###
$smtp_server = "";
$smtp_auth = false; //false or true
$smtp_auth_username = "";
$smtp_auth_password = "";
$email_from = "";
$email_from_name = "";


// DATABASE connexion parameters
if ( $_SERVER['HTTP_HOST'] == "localhost" ){
    //LOCAL => FOR TEST
    $host = "localhost";
    $login = "root";
    $password = ""; 
    $bdd = "cpassman"; 
}else{
    //HOSTED
    $host = "localhost";
    $login = "";    
    $password = "";  
    $bdd = "cpassman"; 
}

// connexion
@mysql_connect($host,$login,$password)
   or die("Impossible to get connected to server");
@mysql_select_db("$bdd")
   or die("Impossible to get connected to table");

$db = mysql_connect($host, $login, $password);  // 1
mysql_select_db($bdd,$db);                    // 2



?>
