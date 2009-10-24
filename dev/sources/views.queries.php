<?php
// Report all errors except E_NOTICE
session_start();
include('../includes/language/'.$_SESSION['user_language'].'.php');
include('../includes/settings.php'); 
header("Content-type: text/html; charset=".$k['charset']); 
include('main.functions.php'); 

// Construction de la requête en fonction du type de valeur
switch($_POST['type'])
{ 
    case "log_generate":
        require_once ("NestedTree.class.php");
        $tree = new NestedTree('nested_tree', 'id', 'parent_id', 'title');
        
        //préparer le PDF
        include('../includes/fpdf/pdf.fonctions.php');
        $pdf=new FPDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(0,10,$txt['pdf_del_title'],0,1,'C',false);
        $pdf->SetFont('Arial','I',12);
        $pdf->Cell(0,10,$txt['pdf_del_date'].date("d/m/Y à H:i:s",mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))),0,1,'C',false);
        $pdf->SetFont('Arial','B',10);
        $pdf->SetFillColor(15,86,145);
        $pdf->cell(80,6,$txt['label'],1,0,"C",1);
        $pdf->cell(75,6,$txt['group'],1,0,"C",1);
        $pdf->cell(21,6,$txt['date'],1,0,"C",1);
        $pdf->cell(15,6,$txt['author'],1,1,"C",1);
        $pdf->SetFont('Arial','',10);

        $res = mysql_query("SELECT * FROM log_items WHERE action = 'Modification' AND raison = 'Mot de passe changé'");
        while ( $data = mysql_fetch_array($res) ){
            if ( date("d/m/Y",$data['date']) == $_POST['date'] ){
                //récup de l'auteur
                $res_user = mysql_query("SELECT login FROM users WHERE id = '".$data['id_user']."'");
                $data_user = mysql_fetch_row($res_user);
                //recup info sur mdp
                $res_item = mysql_query("SELECT label, id_tree FROM items WHERE id = '".$data['id_item']."'");
                $data_item = mysql_fetch_row($res_item);
                //recup de l'arbo
                $arbo = $tree->getPath($data_item[1], true);
                $arboTxt = "";
                foreach($arbo as $elem){
                    if ( empty($arboTxt) ) $arboTxt = $elem->title;
                    else $arboTxt .= " > ".$elem->title;
                }
                $pdf->cell(80,6,$data_item[0],1,0,"L");
                $pdf->cell(75,6,$arboTxt,1,0,"L");
                $pdf->cell(21,6,$_POST['date'],1,0,"C");
                $pdf->cell(15,6,$data_user[0],1,1,"C");
                //$pdf->Cell(0,6,"   # '".$data_item[0]."' [".$arboTxt."] le ".$_POST['date']." par ".$data_user[0],0,1,'L',false);
            }
        }
        list($d,$m,$y) = explode('/',$_POST['date']);
        $nomFichier = "log_followup_passwords_".date("Y-m-d",mktime(0,0,0,$m,$d,$y)).".pdf";
        $pdf->Output($chemin_passman."/files/".$nomFichier);
        echo 'document.getElementById("lien_pdf").innerHTML = "<a href=\''.$url_passman.'/fichiers/'.$nomFichier.'\' target=\'_blank\'>'.$txt['pdf_download'].'</a>";';
        echo 'LoadingPage();';
    break;
    
    
    case "lister_suppression":
        $texte = "<table cellpadding=3>";
        $res = mysql_query("SELECT * FROM items i, log_items l WHERE l.id_item = i.id AND i.inactif = '1' AND l.action = 'Suppression' GROUP BY l.id_item");
        while ( $data = mysql_fetch_array($res) ){
            $res2 = mysql_query("SELECT login FROM users WHERE id=".$data['id_user']);
            $data2 = mysql_fetch_row($res2);
            
            $texte .= '<tr><td><img src=\"includes/images/arrow-repeat.png\" title=\"Restaurer\" style=\"cursor:pointer;\" onclick=\"restaurerItem(\''.$data['id'].'\')\">&nbsp;&nbsp;'.$data['label'].'</td><td width=\"100px\" align=\"center\">'.date("d/m/Y",$data['date']).'</td><td width=\"70px\" align=\"center\">'.$data2[0].'</td></tr>';
        }
        echo 'document.getElementById("liste_elems_del").innerHTML = "'.$texte.'</table>";';
        echo 'LoadingPage();';
    break;
    
    case "restaurer_item":
        $sql = "UPDATE items SET inactif = '0' WHERE id = ".$_POST['id'];
        mysql_query($sql) or die($sql.'  =>  '.mysql_error());
        //log
        mysql_query("INSERT INTO log_items VALUES ('".$_POST['id']."','".mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('y'))."','".$_SESSION['id']."','Restauration','')");
        //echo 'document.getElementById("num_tab").value = "1";';
        //recharger
        echo 'window.location.href = "index.php?page=manage_views";';
    break;
}
?>
