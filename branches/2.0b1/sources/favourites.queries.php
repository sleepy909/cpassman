<?php
####################################################################################################
## File : favourites.queries.php
## Author : Nils Laumaillé
## Description : File contains queries for ajax
## 
## DON'T CHANGE !!!
## 
####################################################################################################

session_start();

include('../includes/language/'.$_SESSION['user_language'].'.php'); 
include('../includes/settings.php');
header("Content-type: text/html; charset=".$k['charset']); 

// connect to the server 
require_once("class.database.php"); 
$db = new Database($server, $user, $pass, $database, $pre);
$db->connect(); 

// Construction de la requ?te en fonction du type de valeur
if ( !empty($_POST['type']) ){
    switch($_POST['type'])
    {
        #CASE adding a new function
        case "del_fav":
            //Get actual favourites
            $data = $db->fetch_row("SELECT favourites FROM ".$pre."users WHERE id = '".$_SESSION['user_id']."'");
            $tmp = explode(";",$data[0]);
            $favs = "";
            $tab_favs = array();
            //redefine new list of favourites
            foreach($tmp as $f){
                if (!empty($f) && $f != $_POST['id']){
                    if ( empty($favs) ) $favs = $f;
                    else $favs = ';'.$f;
                    array_push($tab_favs,$f);
                }
            }
            //update user's account
            $db->query_update(
                "users",
                array(
                    'favourites' => $favs
                ),
                "id = '".$_SESSION['user_id']."'"
            );
            //update session
            $_SESSION['favourites'] = $tab_favs;print_r($tab_favs);
            //refresh page
            echo 'document.form_favourites.submit();';
        break;
    }
}
?>
