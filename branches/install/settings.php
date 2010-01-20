<?php
global $lang, $txt, $k, $chemin_passman, $url_passman, $mdp_complexite, $mng_pages;
global $smtp_server, $smtp_auth, $smtp_auth_username, $smtp_auth_password, $email_from,$email_from_name;

include('include.php');

$k['user_password_limit'] = ; //Number of days the user's password is available. After this limit, user has to enter a new password.
$k['charset'] = "";  //the charset you want to use    : French => ISO-8859-15
$k['prefix'] = "";  //tables prefix
@define('SALT', ''); //Define your encryption key => NeverChange it once it has been used !!!!!

### EMAIL PROPERTIES ###
$smtp_server = "";
$smtp_auth = ; //false or true
$smtp_auth_username = "";
$smtp_auth_password = "";
$email_from = "";
$email_from_name = "";

// DATABASE connexion parameters
if ( $_SERVER['HTTP_HOST'] == "localhost" ){
    //LOCAL => FOR TESTING PURPOSE - UPDATE AS YOU WANT
    $db_host = "localhost";
    $db_login = "";
    $db_password = ""; 
    $db_bdd = "";
}else{
    //HOSTED
    $db_host = "localhost";
    $db_login = "";
    $db_password = "";
    $db_bdd = "";
}

// connexion
@mysql_connect($db_host,$db_login,$db_password)
   or die("Impossible to get connected to server");
@mysql_select_db("$db_bdd")
   or die("Impossible to get connected to table");

$db = mysql_connect($db_host, $db_login, $db_password);
mysql_select_db($db_bdd,$db);

?>