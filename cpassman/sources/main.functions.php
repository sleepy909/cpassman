<?php

function nbjours($datedeb, $datefin) { 
    list ($yearF, $monthF, $dayF) = explode ('-', $datedeb);
    list ($yearC, $monthC, $dayC) = explode ('-', $datefin);
    if(strlen($yearF)===4){
        if (false === @checkdate ($monthF, $dayF, $yearF) || false === @checkdate ($monthC, $dayC, $yearC)) {
            return false;
        } else {
            $tFar = mktime (0,0,0,$monthF, $dayF, $yearF);
            $tClose = mktime (0,0,0,$monthC, $dayC, $yearC);
            $tDistance = $tFar - $tClose;
            return round ($tDistance/(24*60*60));
        }
    }else{
            return false;
    }
}

function encrypt($text)
{    
    return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, SALT, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
}

function decrypt($text)
{
    return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, SALT, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
} 

function TrimElement($chaine,$element){
    $chaine = trim($chaine);
    if ( substr($chaine,0,1) == $element ) $chaine = substr($chaine,1);
    if ( substr($chaine,strlen($chaine)-1,1) == $element ) $chaine = substr($chaine,0,strlen($chaine)-1);
    return $chaine;
}

function IdentificationDesDroits($groupes_visibles_user,$groupes_interdits_user,$is_admin,$id_fonctions,$refresh){    
    include('../includes/settings.php');
    //Vérifier si utilisateur est ADMIN DIEU
    if ( $is_admin == 1 ){
        $groupes_visibles = array();
        $res = mysql_query("SELECT id FROM ".$k['prefix']."nested_tree");
        while($data=mysql_fetch_row($res)){
            array_push($groupes_visibles,$data[0]);
        } 
        $_SESSION['groupes_visibles'] = $groupes_visibles;
        $_SESSION['is_admin'] = $is_admin;
    }else{
        //init
        $_SESSION['groupes_visibles'] = array();
        $groupes_visibles = array();
        $groupes_interdits = array();
        if ( !empty($groupes_interdits_user) && count($groupes_interdits_user)>0 ) $groupes_interdits = $groupes_interdits_user;
        $_SESSION['is_admin'] = $is_admin;
        $fonctions_associees = explode(';',TrimElement($id_fonctions,";"));
        $new_liste_gp_visibles = array();
        $liste_gp_interdits = array();
        
        #echo "=>".$groupes_visibles_user." - ".$groupes_interdits_user." - ".$id_fonctions." - ";
                
        require_once ("NestedTree.class.php");
        $tree = new NestedTree($k['prefix'].'nested_tree', 'id', 'parent_id', 'title');    
            
        //rechercher tous les groupes visibles en fonction des fonctions associées à l'utilisateur
        foreach($fonctions_associees as $fonc_id){
            if ( !empty($fonc_id) ){
                $res = mysql_query("SELECT groupes_visibles,groupes_interdits FROM ".$k['prefix']."functions WHERE id=".$fonc_id);
                $data=mysql_fetch_row($res);
                $gp_visibles_tmp = explode(';',TrimElement($data[0],";"));
                $gp_interdits_tmp = explode(';',TrimElement($data[1],";"));
                #echo "**** => ".$fonc_id." --- ";
                
                //gérer les groupes visibles
                if (!empty($data[0]) ){
                    foreach($gp_visibles_tmp as $gp_id_visible){    #echo " - id visible : ".$gp_id_visible.";";
                        //récupérer tous les sous groupes
                        $mytree = $tree->getDescendants($gp_id_visible,true);
                        foreach($mytree as $t){
                            if ( !in_array($t->id,$groupes_interdits) && !in_array($t->id,$groupes_visibles) )array_push($groupes_visibles,$t->id); #ne pas rajouter comme visibles si ce groupe est interdit
                        }
                    }
                }
                #echo " ::: groupes visibles : ";print_r($groupes_visibles);echo " ::: ";
                                
                //gérer les groupes interdits
                if (!empty($data[1]) ){
                    foreach($gp_interdits_tmp as $gp_id_interdit){
                        //supprimer tous les sous groupes
                        $mytree = $tree->getDescendants($gp_id_interdit,true);
                        foreach($mytree as $t){
                            /*if ( in_array($t->id,$groupes_visibles) ){
                                //supprimer l'id du groupe s'il existe dans le tableau des visibles
                                unset($groupes_visibles[array_search($t->id, $groupes_visibles)]);
                            }*/
                             if ( !in_array($t->id,$liste_gp_interdits) )array_push($liste_gp_interdits,$t->id);
                        }
                    }
                }

                //merger les 2 tableaux
                foreach($groupes_visibles as $gpv){
                    if ( !in_array($gpv,$liste_gp_interdits) )array_push($new_liste_gp_visibles,$gpv);
                }

                //ajouter les groupes spécifiques à l'utilisateurs
                $groupes_visibles_by_user = explode(';',$groupes_visibles_user);
                foreach($groupes_visibles_by_user as $id_visible){
                    if ( !in_array($id_visible,$new_liste_gp_visibles) ) array_push($new_liste_gp_visibles,$id_visible);
                }      
            }
        }
        
        #echo " -- ";
        #print_r($new_liste_gp_visibles);

        $_SESSION['groupes_visibles'] = array_unique($new_liste_gp_visibles);//implode(';',$new_liste_gp_visibles);
        
        #if ( $refresh == true ) return "ok";
    }
}

?>
