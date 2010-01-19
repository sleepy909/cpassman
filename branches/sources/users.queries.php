<?php
####################################################################################################
## File : users.queries.php
## Author : Nils Laumaillé
## Description : File contains queries for ajax
## 
## DON'T CHANGE !!!
## 
####################################################################################################

session_start();
include('../includes/settings.php'); 
header("Content-type: text/html; charset=".$k['charset']); 

// Construction de la requête en fonction du type de valeur
if ( !empty($_POST['type']) ){
    switch($_POST['type'])
    {
        case "groupes_visibles":
        case "groupes_interdits":
            $val = explode(';',$_POST['valeur']);
            $valeur = $_POST['valeur'];
            //vérifier si l'id est déjà présent
            $res = mysql_query("SELECT ".$_POST['type']." FROM ".$k['prefix']."users WHERE id = $val[0]");
            $data = mysql_fetch_row($res);
            $new_groupes = $data[0];
            if ( !empty($data[0]) ){
                $groupes = explode(';',$data[0]);
                if ( in_array($val[1],$groupes ) ) $new_groupes = str_replace($val[1],"",$new_groupes);
                else $new_groupes .= ";".$val[1];
            }else{
                $new_groupes = $val[1];
            }
            while ( substr_count($new_groupes,";;") > 0 ) $new_groupes = str_replace(";;",";",$new_groupes);
            mysql_query("UPDATE ".$k['prefix']."users SET ".$_POST['type']." = '".$new_groupes."' WHERE id = ".$val[0]);
        
        break;
        
        case "fonction":
            $val = explode(';',$_POST['valeur']);
            $valeur = $_POST['valeur'];
            //vérifier si l'id est déjà présent
            $res = mysql_query("SELECT fonction_id FROM ".$k['prefix']."users WHERE id = $val[0]");
            $data = mysql_fetch_row($res);
            $new_fonctions = $data[0];
            if ( !empty($data[0]) ){
                $fonctions = explode(';',$data[0]);
                if ( in_array($val[1],$fonctions ) ) $new_fonctions = str_replace($val[1],"",$new_fonctions);
                else if ( !empty($new_fonctions) )
                    $new_fonctions .= ";".$val[1];
                else
                    $new_fonctions = ";".$val[1];
            }else{
                $new_fonctions = $val[1];
            }
            while ( substr_count($new_fonctions,";;") > 0 ) $new_fonctions = str_replace(";;",";",$new_fonctions);
            mysql_query("UPDATE ".$k['prefix']."users SET fonction_id = '".$new_fonctions."' WHERE id = ".$val[0]);
        
        break;   
        
        case "add_new_user":
            $sql = "INSERT INTO ".$k['prefix']."users SET 
                    login = '".$_POST['login']."',
                    pw = '".md5($_POST['pw'])."',
                    email = '".($_POST['email'])."',
                    admin = '".($_POST['admin']=="true" ? '1' : '0')."',
                    gestionnaire = '".($_POST['manager']=="true" ? '1' : '0')."'
            ";
            mysql_query($sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysql_error());
            echo 'document.form_utilisateurs.submit();';
        break;
        
        case "supprimer_user":
            mysql_query("DELETE FROM ".$k['prefix']."users WHERE id = ".$_POST['id']);
        break;
        
        case "modif_mdp_user":
            mysql_query("UPDATE ".$k['prefix']."users SET pw = '".md5($_POST['newmdp'])."' WHERE id = ".$_POST['id']);
        break;
        
        case "modif_mail_user":
            mysql_query("UPDATE ".$k['prefix']."users SET email = '".$_POST['newmail']."' WHERE id = ".$_POST['id']);
        break;
        
        case "modif_droit_gest_groupes_user":
            mysql_query("UPDATE ".$k['prefix']."users SET gestionnaire = '".$_POST['gest_groupes']."' WHERE id = ".$_POST['id']);
        break;
        
        case "modif_droit_admin_user":
            mysql_query("UPDATE ".$k['prefix']."users SET admin = '".$_POST['admin']."' WHERE id = ".$_POST['id']) or die('Erreur SQL ! '.mysql_error());
        break;
        
        //CHANGE USER FUNCTIONS
        case "open_div_functions";
            $text = "";
            //Refresh list of existing functions
            $data_user = mysql_fetch_row(mysql_query("SELECT fonction_id FROM ".$k['prefix']."users WHERE id = ".$_POST['id']));
            $users_functions = explode(';',$data_user[0]);
            
            $data = mysql_query("SELECT id,title FROM ".$k['prefix']."functions");
            while ( $res = mysql_fetch_row($data) ){
                $text .= '<input type=\"checkbox\" id=\"cb_change_function-'.$res[0].'\"';
                if ( in_array($res[0],$users_functions) )  $text .= ' checked';
                $text .= '>&nbsp;'.$res[1].'<br />';
            }
            
            echo 'document.getElementById("change_user_functions_list").innerHTML = "'.$text.'";';
            echo 'document.getElementById("selected_user").value = "'.$_POST['id'].'";';
                        
            //display dialogbox
            echo '$("#change_user_functions").dialog("open");';
        break;
        
        case "change_user_functions";
            //save data
            mysql_query("UPDATE ".$k['prefix']."users SET fonction_id = '".$_POST['list']."' WHERE id = ".$_POST['id']);
            
            //display information
            $text = "";
            $val = str_replace(';',',',$_POST['list']);
            //Check if POST is empty
            if ( !empty($val) ){
                $data = mysql_query("SELECT title FROM ".$k['prefix']."functions WHERE id IN (".$val.")");
                while ( $res = mysql_fetch_row($data) ){
                    $text .= $res[0]."<br />";
                }                
            }else
                $text = '<span style=\"text-align:center\"><img src=\"includes/images/error.png\" /></span>';
            echo 'document.getElementById("list_function_user_'.$_POST['id'].'").innerHTML = "'.$text.'";';
        break;
        
        //CHANGE AUTHORIZED GROUPS
        case "open_div_autgroups";
            $text = "";
            //Refresh list of existing functions
            $data_user = mysql_fetch_row(mysql_query("SELECT groupes_visibles FROM ".$k['prefix']."users WHERE id = ".$_POST['id']));
            $user = explode(';',$data_user[0]);
            
            require_once ("NestedTree.class.php");
            $tree = new NestedTree($k['prefix'].'nested_tree', 'id', 'parent_id', 'title');
            $tree_desc = $tree->getDescendants();
            foreach($tree_desc as $t){
                $text .= '<input type=\"checkbox\" id=\"cb_change_autgroup-'.$t->id.'\"';
                $ident="";
                for($y=1;$y<$t->nlevel;$y++) $ident .= "&nbsp;&nbsp;";
                if ( in_array($t->id,$user) ) $text .= ' checked';
                $text .= '>&nbsp;'.$ident.$t->title.'<br />';
                $prev_level = $t->nlevel;
            }
            
            echo 'document.getElementById("change_user_autgroups_list").innerHTML = "'.$text.'";';
            echo 'document.getElementById("selected_user").value = "'.$_POST['id'].'";';
                        
            //display dialogbox
            echo '$("#change_user_autgroups").dialog("open");';
        break;
        
        case "change_user_autgroups";
            //save data
            mysql_query("UPDATE ".$k['prefix']."users SET groupes_visibles = '".$_POST['list']."' WHERE id = ".$_POST['id']);
            
            //display information
            $text = "";
            $val = str_replace(';',',',$_POST['list']);
            $data = mysql_query("SELECT title,nlevel FROM ".$k['prefix']."nested_tree WHERE id IN (".$val.")");
            while ( $res = mysql_fetch_row($data) ){
                $ident="";
                for($y=1;$y<$res[1];$y++) $ident .= "&nbsp;&nbsp;";
                $text .= $ident.$res[0]."<br />";
            }
             echo 'document.getElementById("list_autgroups_user_'.$_POST['id'].'").innerHTML = "'.$text.'";';
        break;
        
        //CHANGE FORBIDDEN GROUPS
        case "open_div_forgroups";
            $text = "";
            //Refresh list of existing functions
            $data_user = mysql_fetch_row(mysql_query("SELECT groupes_interdits FROM ".$k['prefix']."users WHERE id = ".$_POST['id']));
            $user = explode(';',$data_user[0]);
            
            require_once ("NestedTree.class.php");
            $tree = new NestedTree($k['prefix'].'nested_tree', 'id', 'parent_id', 'title');
            $tree_desc = $tree->getDescendants();
            foreach($tree_desc as $t){
                $text .= '<input type=\"checkbox\" id=\"cb_change_forgroup-'.$t->id.'\"';
                $ident="";
                for($y=1;$y<$t->nlevel;$y++) $ident .= "&nbsp;&nbsp;";
                if ( in_array($t->id,$user) ) $text .= ' checked';
                $text .= '>&nbsp;'.$ident.$t->title.'<br />';
                $prev_level = $t->nlevel;
            }
            
            echo 'document.getElementById("change_user_forgroups_list").innerHTML = "'.$text.'";';
            echo 'document.getElementById("selected_user").value = "'.$_POST['id'].'";';
                        
            //display dialogbox
            echo '$("#change_user_forgroups").dialog("open");';
        break;
        
        case "change_user_forgroups";
            //save data
            mysql_query("UPDATE ".$k['prefix']."users SET groupes_interdits = '".$_POST['list']."' WHERE id = ".$_POST['id']);
            
            //display information
            $text = "";
            $val = str_replace(';',',',$_POST['list']);
            $data = mysql_query("SELECT title,nlevel FROM ".$k['prefix']."nested_tree WHERE id IN (".$val.")");
            while ( $res = mysql_fetch_row($data) ){
                $ident="";
                for($y=1;$y<$res[1];$y++) $ident .= "&nbsp;&nbsp;";
                $text .= $ident.$res[0]."<br />";
            }
             echo 'document.getElementById("list_forgroups_user_'.$_POST['id'].'").innerHTML = "'.$text.'";';
        break;
        
    }
}
else if ( !empty($_POST['newlogin']) ){
    $id = explode('_',$_POST['id']);
    mysql_query("UPDATE ".$k['prefix']."users SET login = '".$_POST['newlogin']."' WHERE id = ".$id[1]);
    echo $_POST['newlogin'];
}
else if ( isset($_POST['newadmin']) ){
    $id = explode('_',$_POST['id']);
    mysql_query("UPDATE ".$k['prefix']."users SET admin = '".$_POST['newadmin']."' WHERE id = ".$id[1]) or die(mysql_error());
    if (  $_POST['newadmin'] == "1" ) echo "Oui"; else echo "Non";
}
?>
