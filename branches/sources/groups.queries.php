<?php
####################################################################################################
## File : groups.queries.php
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
include('main.functions.php'); 
require_once ("NestedTree.class.php");


// CASE where title is changed
if ( isset($_POST['newtitle']) ){
    $id = explode('_',$_POST['id']);
    mysql_query("UPDATE ".$k['prefix']."nested_tree SET title = '".mysql_real_escape_string(stripslashes(utf8_decode($_POST['newtitle'])))."' WHERE id = ".$id[1]) or die(mysql_error());
    echo ($_POST['newtitle']);
}

// CASE where the parent is changed
else if ( isset($_POST['newparent_id']) ){
    $id = explode('_',$_POST['id']);
    mysql_query("UPDATE ".$k['prefix']."nested_tree SET parent_id = '".$_POST['newparent_id']."' WHERE id = ".$id[1]) or die(mysql_error());
    //Get the title to display it
    $res = mysql_query("SELECT title FROM ".$k['prefix']."nested_tree WHERE id = ".$_POST['newparent_id']);
    $data = mysql_fetch_row($res);
    echo ($data[0]);
    //rebuild the tree grid
    $tree = new NestedTree($k['prefix'].'nested_tree', 'id', 'parent_id', 'title');
    $tree->rebuild();
}

// CASE where complexity is changed
else if ( isset($_POST['changer_complexite']) ){
    $id = explode('_',$_POST['id']);
    
    //Check if group exists
    $tmp = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM ".$k['prefix']."misc WHERE type = 'complex' AND intitule = '".$id[1]."'"));
    if ( $tmp[0] == 0 )
        mysql_query("INSERT INTO ".$k['prefix']."misc VALUES ('complex', '".$id[1]."', '".$_POST['changer_complexite']."')") or die(mysql_error());
    else
        mysql_query("UPDATE ".$k['prefix']."misc SET valeur = '".$_POST['changer_complexite']."' WHERE type='complex' AND  intitule = ".$id[1]) or die(mysql_error());
    
    //Get title to display it
    echo $mdp_complexite[$_POST['changer_complexite']][1];
    
    //rebuild the tree grid
    $tree = new NestedTree($k['prefix'].'nested_tree', 'id', 'parent_id', 'title');
    $tree->rebuild();
}

// Several other cases
else if ( isset($_POST['type']) ){
    switch($_POST['type'])
    {
        // CASE where DELETING a group
        case "supprimer_groupe":
            mysql_query("DELETE FROM ".$k['prefix']."nested_tree WHERE id = ".$_POST['id']);
            
            //rebuild tree
            $tree = new NestedTree($k['prefix'].'nested_tree', 'id', 'parent_id', 'title');
            $tree->rebuild();
            
            //Refresh the page
            echo 'RefreshPage("form_groupes");';
        break;
        
        //CASE where ADDING a new group
        case "ajouter_groupe":
            $sql = "INSERT INTO ".$k['prefix']."nested_tree SET
                parent_id = '".$_POST['parent_id']."',
                title = '".mysql_real_escape_string(stripslashes(($_POST['titre'])))."',
                bloquer_creation = '0',
                bloquer_modification = '0'
            ";
            mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
            $new_id=mysql_insert_id();
            
            //Add complexity level
            mysql_query("INSERT INTO ".$k['prefix']."misc VALUES ('complex','".$new_id."','".$_POST['complex']."')");
            
            //Refresh the rights of actual user
            IdentificationDesDroits(implode(';',$_SESSION['groupes_visibles']).';'.$new_id,$_SESSION['groupes_interdits'],$_SESSION['is_admin'],$_SESSION['fonction_id'],true);
            
            //rebuild the tree
            $tree = new NestedTree($k['prefix'].'nested_tree', 'id', 'parent_id', 'title');
            $tree->rebuild();
            
            //Refresh the page
            echo 'RefreshPage("form_groupes");';
        break;
        
        //CASE where to update the associated Function
        case "fonction":
            $val = explode(';',$_POST['valeur']);
            $valeur = $_POST['valeur'];
            //Check if ID already exists
            $res = mysql_query("SELECT authorized FROM ".$k['prefix']."rights WHERE tree_id = '".$val[0]."' AND fonction_id= '".$val[1]."'") or die(mysql_error());
            $data = mysql_fetch_row($res);
            if ( empty($data[0]) )
                mysql_query("INSERT INTO ".$k['prefix']."rights VALUES (NULL, '".$val[0]."', '".$val[1]."','1')") or die(mysql_error());
            else{
                if ($data[0]==1)
                    mysql_query("UPDATE ".$k['prefix']."rights SET authorized = '0' WHERE id = '".$val[0]."' AND fonction_id= '".$val[1]."'") or die(mysql_error());
                else
                    mysql_query("UPDATE ".$k['prefix']."rights SET authorized = '1' WHERE id = '".$val[0]."' AND fonction_id= '".$val[1]."'") or die(mysql_error());
            }    
        break;
        
        // CASE where to authorize an ITEM creation without respecting the complexity
        case "modif_droit_autorisation_sans_complexite":
            mysql_query("UPDATE ".$k['prefix']."nested_tree SET bloquer_creation = '".$_POST['droit']."' WHERE id = '".$_POST['id']."'");
        break;
        
        // CASE where to authorize an ITEM modification without respecting the complexity
        case "modif_droit_modification_sans_complexite":
            mysql_query("UPDATE ".$k['prefix']."nested_tree SET bloquer_modification = '".$_POST['droit']."' WHERE id = '".$_POST['id']."'") or die(mysql_error());
        break;
    }
}
?>