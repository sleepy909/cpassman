<?php
global $lang, $txt, $k, $chemin_passman, $url_passman, $mdp_complexite, $mngPages;
global $smtp_server, $smtp_auth, $smtp_auth_username, $smtp_auth_password, $email_from,$email_from_name;
global $server, $user, $pass, $database, $pre, $db;

@define('SALT', 'whateveryouwant'); //Define your encryption key => NeverChange it once it has been used !!!!!

### EMAIL PROPERTIES ###
$smtp_server = "smtp.my_domain.com";
$smtp_auth = false; //false or true
$smtp_auth_username = "";
$smtp_auth_password = "";
$email_from = "";
$email_from_name = "";

### DATABASE connexion parameters ###
$server = "localhost";
$user = "root";
$pass = "";
$database = "cpm2";
$pre = "cpassman_";

?>