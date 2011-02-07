<?php
global $lang, $txt, $k, $chemin_passman, $url_passman, $mdp_complexite, $mngPages;
global $smtp_server, $smtp_auth, $smtp_auth_username, $smtp_auth_password, $email_from,$email_from_name;
global $server, $user, $pass, $database, $pre, $db;

@define('SALT', 'whateveryouwant'); //Define your encryption key => NeverChange it once it has been used !!!!!

### EMAIL PROPERTIES ###
$smtp_server = "mail.vag-technique.fr";
$smtp_auth = true; //false or true
$smtp_auth_username = "	webmaster@vag-technique.fr";
$smtp_auth_password = "romar06";
$email_from = "webmaster@vag-technique.fr";
$email_from_name = "cPassMan";

### DATABASE connexion parameters ###
$server = "localhost";
$user = "root";
$pass = "";
$database = "cpm2";
$pre = "cpassman_";

?>