<?php
// Report all errors except E_NOTICE
session_start();
include('../includes/language/'.$_SESSION['user_language'].'.php'); 
include('../includes/settings.php'); 
header("Content-type: text/html; charset=".$k['charset']);
include('main.functions.php'); 
require_once ("NestedTree.class.php");
$erreurSQL = "";

// Construction de la requête en fonction du type de valeur
if ( isset($_POST['newtitle']) ){
    $id = explode('_',$_POST['id']);
    mysql_query("UPDATE ".$k['prefix']."nested_tree SET title = '".mysql_real_escape_string(stripslashes(utf8_decode($_POST['newtitle'])))."' WHERE id = ".$id[1]) or die(mysql_error());
    echo ($_POST['newtitle']);
}
else if ( isset($_POST['newparent_id']) ){
    $id = explode('_',$_POST['id']);
    mysql_query("UPDATE ".$k['prefix']."nested_tree SET parent_id = '".$_POST['newparent_id']."' WHERE id = ".$id[1]) or die(mysql_error());
    //récupérer le titre pour l'afficher
    $res = mysql_query("SELECT title FROM ".$k['prefix']."nested_tree WHERE id = ".$_POST['newparent_id']);
    $data = mysql_fetch_row($res);
    echo ($data[0]);
    //reconstruire l'arbo
    $tree = new NestedTree($k['prefix'].'nested_tree', 'id', 'parent_id', 'title');
    $tree->rebuild();
}

# CHANGER LA COMPLEXITE
else if ( isset($_POST['changer_complexite']) ){
    $id = explode('_',$_POST['id']);
    
    //vérifier si le groupe existe
    $tmp = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM ".$k['prefix']."misc WHERE type = 'complex' AND intitule = '".$id[1]."'"));
    if ( $tmp[0] == 0 )
        mysql_query("INSERT INTO ".$k['prefix']."misc VALUES ('complex', '".$id[1]."', '".$_POST['changer_complexite']."')") or die(mysql_error());
    else
        mysql_query("UPDATE ".$k['prefix']."misc SET valeur = '".$_POST['changer_complexite']."' WHERE type='complex' AND  intitule = ".$id[1]) or die(mysql_error());
    
    //récupérer le titre pour l'afficher
    echo $mdp_complexite[$_POST['changer_complexite']][1];
    //reconstruire l'arbo
    $tree = new NestedTree($k['prefix'].'nested_tree', 'id', 'parent_id', 'title');
    $tree->rebuild();
}


else if ( isset($_POST['type']) ){
    switch($_POST['type'])
    {
        case "supprimer_groupe":
            mysql_query("DELETE FROM ".$k['prefix']."nested_tree WHERE id = ".$_POST['id']);
            //reconstruire l'arbo
            $tree = new NestedTree($k['prefix'].'nested_tree', 'id', 'parent_id', 'title');
            $tree->rebuild();
        break;
        
        case "ajouter_groupe":
            $sql = "INSERT INTO ".$k['prefix']."nested_tree VALUES (NULL,'".$_POST['parent_id']."','".mysql_real_escape_string(stripslashes(($_POST['titre'])))."','','','','0','0')";
            mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
            $new_id=mysql_insert_id();
            
            //ajouter complexité
            mysql_query("INSERT INTO ".$k['prefix']."misc VALUES ('complex','".$new_id."','".$_POST['complex']."')");
            
            //Rafraichir les droits de l'utilsiateur            
            IdentificationDesDroits(implode(';',$_SESSION['groupes_visibles']).';'.$new_id,$_SESSION['groupes_interdits'],$_SESSION['is_admin'],$_SESSION['fonction_id'],true);
            
            //reconstruire l'arbo
            $tree = new NestedTree($k['prefix'].'nested_tree', 'id', 'parent_id', 'title');
            $tree->rebuild();
        break;
        
        case "fonction":
            $val = explode(';',$_POST['valeur']);
            $valeur = $_POST['valeur'];
            //vérifier si l'id est déjà présent
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
        
        case "modif_droit_autorisation_sans_complexite":
            mysql_query("UPDATE ".$k['prefix']."nested_tree SET bloquer_creation = '".$_POST['droit']."' WHERE id = '".$_POST['id']."'");
        break;
        
        case "modif_droit_modification_sans_complexite":
            mysql_query("UPDATE ".$k['prefix']."nested_tree SET bloquer_modification = '".$_POST['droit']."' WHERE id = '".$_POST['id']."'") or die(mysql_error());
        break;
    }
}
?>