<?php
global $txt;

if ( $_SERVER['HTTP_HOST'] == "localhost" ){
    //LOCAL => FOR TEST
    $host = "localhost";
    $login = "root";
    $password = ""; 
    $bdd = "cpm"; 
}else{
    //HOSTED
    $host = "mysql4.000webhost.com";
    $login = "a5336948_cpm";    
    $password = "aure78";  
    $bdd = "a5336948_cpm"; 
}

// connexion
@mysql_connect($host,$login,$password)
   or die("Impossible to get connected to server");
@mysql_select_db("$bdd")
   or die("Impossible to get connected to table");

$db = mysql_connect($host, $login, $password);  // 1
mysql_select_db($bdd,$db);

function prepare_title($text){
    return str_replace(array(' ','.'),array('-','-'),$text);
}
?>