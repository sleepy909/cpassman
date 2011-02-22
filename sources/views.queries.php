<?php
/**
 * @file 		views.queries.php
 * @author		Nils Laumaillé
 * @version 	2.0
 * @copyright 	(c) 2009-2011 Nils Laumaillé
 * @licensing 	CC BY-NC-ND (http://creativecommons.org/licenses/by-nc-nd/3.0/legalcode)
 * @link		http://cpassman.org
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

session_start();
if ($_SESSION['CPM'] != 1)
	die('Hacking attempt...');


include('../includes/language/'.$_SESSION['user_language'].'.php');
include('../includes/settings.php');
include('../includes/include.php');
header("Content-type: text/html; charset=utf-8");
include('main.functions.php');

// connect to the server
    require_once("class.database.php");
    $db = new Database($server, $user, $pass, $database, $pre);
    $db->connect();

//Constant used
$nb_elements = 20;

// Construction de la requ?te en fonction du type de valeur
switch($_POST['type'])
{
    #CASE generating the log for passwords renewal
    case "log_generate":
        require_once ("NestedTree.class.php");
        $tree = new NestedTree($pre.'nested_tree', 'id', 'parent_id', 'title');

        //Prepare the PDF file
        include('../includes/libraries/tfpdf/tfpdf.php');
    	$pdf=new tFPDF();

    	//Add font for utf-8
    	$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);

        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('DejaVu','',16);
        $pdf->Cell(0,10,$txt['pdf_del_title'],0,1,'C',false);
        $pdf->SetFont('DejaVu','',12);
        $pdf->Cell(0,10,$txt['pdf_del_date'].date($_SESSION['settings']['date_format']." ".$_SESSION['settings']['time_format'],mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))),0,1,'C',false);
        $pdf->SetFont('DejaVu','',10);
        $pdf->SetFillColor(15,86,145);
        $pdf->cell(80,6,$txt['label'],1,0,"C",1);
        $pdf->cell(75,6,$txt['group'],1,0,"C",1);
        $pdf->cell(21,6,$txt['date'],1,0,"C",1);
        $pdf->cell(15,6,$txt['author'],1,1,"C",1);
        $pdf->SetFont('DejaVu','',10);

        $rows = $db->fetch_all_array("
            SELECT u.login AS login, i.label AS label, i.id_tree AS id_tree
            FROM ".$pre."log_items AS l
            INNER JOIN ".$pre."users AS u ON (u.id=l.id_user)
            INNER JOIN ".$pre."items AS i ON (i.id=l.id_item)
            WHERE l.action = 'Modification'
            AND l.raison = 'Mot de passe chang?'
        ");
        foreach( $rows as $reccord ){
            if ( date($_SESSION['settings']['date_format'],$reccord['date']) == $_POST['date'] ){
                //information about the pw creator
                $res_user = mysql_query("SELECT login FROM ".$pre."users WHERE id = '".$reccord['id_user']."'");
                $data_user = mysql_fetch_row($res_user);
                //information about the pw itself
                $res_item = mysql_query("SELECT label, id_tree FROM ".$pre."items WHERE id = '".$reccord['id_item']."'");
                $data_item = mysql_fetch_row($res_item);
                //get the tree grid
                $arbo = $tree->getPath($reccord['id_tree'], true);
                $arboTxt = "";
                foreach($arbo as $elem){
                    if ( empty($arboTxt) ) $arboTxt = $elem->title;
                    else $arboTxt .= " > ".$elem->title;
                }
                $pdf->cell(80,6,$reccord['label'],1,0,"L");
                $pdf->cell(75,6,$arboTxt,1,0,"L");
                $pdf->cell(21,6,$_POST['date'],1,0,"C");
                $pdf->cell(15,6,$reccord['login'],1,1,"C");
            }
        }
        list($d,$m,$y) = explode('/',$_POST['date']);
        $nomFichier = "log_followup_passwords_".date("Y-m-d",mktime(0,0,0,$m,$d,$y)).".pdf";
        //send the file
        $pdf->Output($_SESSION['settings']['cpassman_dir'].'\files/'.$nomFichier);
        echo 'document.getElementById("lien_pdf").innerHTML = "<a href=\''.$_SESSION['settings']['cpassman_url'].'/files/'.$nomFichier.'\' target=\'_blank\'>'.$txt['pdf_download'].'</a>";';
        //reload
        echo 'LoadingPage();';
    break;

    #----------------------------------
    #CASE display a full listing with all items deleted
    case "lister_suppression":
        $texte = "<table cellpadding=3>";
        $rows = $db->fetch_all_array("
            SELECT u.login AS login, i.id AS id, i.label AS label, l.date AS date
            FROM ".$pre."log_items AS l
            INNER JOIN ".$pre."items AS i ON (l.id_item=i.id)
            INNER JOIN ".$pre."users AS u ON (l.id_user=u.id)
            WHERE i.inactif = '1'
            AND l.action = 'at_delete'
            GROUP BY l.id_item");
        foreach( $rows as $reccord ){
            $texte .= '<tr><td><input type=\'checkbox\' class=\'cb_deleted_item\' value=\''.$reccord['id'].'\' id=\'item_deleted_'.$reccord['id'].'\' />&nbsp;<b>'.$reccord['label'].'</b></td><td width=\"100px\" align=\"center\">'.date($_SESSION['settings']['date_format'],$reccord['date']).'</td><td width=\"70px\" align=\"center\">'.$reccord['login'].'</td></tr>';
        }
        echo 'document.getElementById("liste_elems_del").innerHTML = "'.$texte.'</table><div style=\'margin-left:5px;\'><input type=\'checkbox\' id=\'item_deleted_select_all\' />&nbsp;<img src=\"includes/images/arrow-repeat.png\" title=\"'.$txt['restore'].'\" style=\"cursor:pointer;\" onclick=\"restoreDeletedItems()\">&nbsp;<img src=\"includes/images/bin_empty.png\" title=\"'.$txt['delete'].'\" style=\"cursor:pointer;\" onclick=\"reallyDeleteItems()\"></div>";';
        echo '$(\'#item_deleted_select_all\').click(function(){if ( $(\'#item_deleted_select_all\').attr(\'checked\') ) { $("input[type=\'checkbox\']:not([disabled=\'disabled\'])").attr(\'checked\', true); } else { $("input[type=\'checkbox\']:not([disabled=\'disabled\'])").removeAttr(\'checked\');  }}); ';
        //reload
        echo 'LoadingPage();';
    break;

    #----------------------------------
    #CASE admin want to restaure a list of deleted items
    case "restore_deleted__items":
        foreach( explode(';',$_POST['list']) as $id ){
            $db->query_update(
                "items",
                array(
                    'inactif' => '0'
                ),
                'id = '.$id
            );
            //log
            $db->query("INSERT INTO ".$pre."log_items VALUES ('".$id."','".mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('y'))."','".$_SESSION['user_id']."','at_restored','')");
        }
        //reload
        echo 'window.location.href = "index.php?page=manage_views";';
    break;

    #----------------------------------
    #CASE admin want to delete a list of deleted items
    case "really_delete_items":
        foreach( explode(';',$_POST['list']) as $id ){
            //delete from ITEMS
            $db->query("DELETE FROM ".$pre."items WHERE id=".$id);
            //delete from LOG_ITEMS
            $db->query("DELETE FROM ".$pre."log_items WHERE id_item=".$id);
            //delete from FILES
            $db->query("DELETE FROM ".$pre."files WHERE id_item=".$id);
            //delete from TAGS
            $db->query("DELETE FROM ".$pre."tags WHERE item_id=".$id);
        }
        //reload
        echo 'window.location.href = "index.php?page=manage_views";';
    break;

    #----------------------------------
    #CASE admin want to see COONECTIONS logs
    case "connections_logs":
        $logs = "";
        $nb_pages = 1;
        $pages = '<table style=\'border-top:1px solid #969696;\'><tr><td>'.$txt['pages'].'&nbsp;:&nbsp;</td>';

        //get number of pages
        $data = $db->fetch_row("
            SELECT COUNT(*)
            FROM ".$pre."log_system AS l
            INNER JOIN ".$pre."users AS u ON (l.qui=u.id)
            WHERE l.type = 'user_connection'");
        if ( $data[0] != 0 ){
            $nb_pages = ceil($data[0]/$nb_elements);
            for($i=1;$i<=$nb_pages;$i++){
                $pages .= '<td onclick=\'displayLogs(\"connections_logs\",'.$i.')\'><span style=\'cursor:pointer;' . ($_POST['page'] == $i ? 'font-weight:bold;font-size:18px;\'>'.$i:'\'>'.$i ) . '</span></td>';
            }
        }
        $pages .= '</tr></table>';

        //define query limits
        if ( isset($_POST['page']) && $_POST['page'] > 1 ){
            $start = ($nb_elements*($_POST['page']-1)) + 1;
        }else{
            $start = 0;
        }

        //launch query
        $rows = $db->fetch_all_array("
            SELECT l.date AS date, l.label AS label, l.qui AS who, u.login AS login
            FROM ".$pre."log_system AS l
            INNER JOIN ".$pre."users AS u ON (l.qui=u.id)
            WHERE l.type = 'user_connection'
            ORDER BY date DESC
            LIMIT $start, $nb_elements");

        foreach( $rows as $reccord)
            $logs .= '<tr><td>'.date($_SESSION['settings']['date_format']." ".$_SESSION['settings']['time_format'],$reccord['date']).'</td><td align=\"center\">'.$txt[$reccord['label']].'</td><td align=\"center\">'.$reccord['login'].'</td></tr>';

        //Append logs to table
        echo '$("#tbody_logs").empty().append("'.$logs.'");';

        //Append number of pages
        echo '$("#log_pages").empty().append("'.$pages.'");';

        //hide gif
        echo 'LoadingPage();';
    break;

    #----------------------------------
    #CASE admin want to see CONNECTIONS logs
    case "errors_logs":
        $logs = "";
        $nb_pages = 1;
        $pages = '<table style=\'border-top:1px solid #969696;\'><tr><td>'.$txt['pages'].'&nbsp;:&nbsp;</td>';

        //get number of pages
        $data = $db->fetch_row("
            SELECT COUNT(*)
            FROM ".$pre."log_system AS l
            INNER JOIN ".$pre."users AS u ON (l.qui=u.id)
            WHERE l.type = 'error'");
        if ( $data[0] != 0 ){
            $nb_pages = ceil($data[0]/$nb_elements);
            for($i=1;$i<=$nb_pages;$i++){
                $pages .= '<td onclick=\'displayLogs(\"errors_logs\",'.$i.')\'><span style=\'cursor:pointer;' . ($_POST['page'] == $i ? 'font-weight:bold;font-size:18px;\'>'.$i:'\'>'.$i ) . '</span></td>';
            }
        }
        $pages .= '</tr></table>';

        //define query limits
        if ( isset($_POST['page']) && $_POST['page'] > 1 ){
            $start = ($nb_elements*($_POST['page']-1)) + 1;
        }else{
            $start = 0;
        }

        //launch query
        $rows = $db->fetch_all_array("
            SELECT l.date AS date, l.label AS label, l.qui AS who, u.login AS login
            FROM ".$pre."log_system AS l
            INNER JOIN ".$pre."users AS u ON (l.qui=u.id)
            WHERE l.type = 'error'
            ORDER BY date DESC
            LIMIT $start, $nb_elements");

        foreach( $rows as $reccord){
            $label = explode('@',addslashes(CleanString($reccord['label'])));
            $logs .= '<tr><td>'.date($_SESSION['settings']['date_format']." ".$_SESSION['settings']['time_format'],$reccord['date']).'</td><td align=\"center\">'.@$label[1].'</td><td align=\"left\">'.$label[0].'</td><td align=\"center\">'.$reccord['login'].'</td></tr>';
        }

        //Append logs to table
        echo '$("#tbody_logs").empty().append("'.$logs.'");';

        //Append number of pages
        echo '$("#log_pages").empty().append("'.$pages.'");';

        //hide gif
        echo 'LoadingPage();';
    break;

    #----------------------------------
    #CASE display a full listing with items EXPRIED
    case "generate_renewal_listing":

        if ( $_POST['period'] == "0" )
            $date = (mktime(date('h'),date('i'),date('s'),date('m'),date('d'),date('y')));
        else if ( $_POST['period'] == "1month" )
            $date = (mktime(date('h'),date('i'),date('s'),date('m')+1,date('d'),date('y')));
        else if ( $_POST['period'] == "6months" )
            $date = (mktime(date('h'),date('i'),date('s'),date('m')+6,date('d'),date('y')));
        else if ( $_POST['period'] == "1year" )
            $date = (mktime(date('h'),date('i'),date('s'),date('m'),date('d'),date('y')+1));

        $id_item = "";
        $texte = "<table cellpadding=3><thead><tr><th>".$txt['label']."</th><th>".$txt['creation_date']."</th><th>".$txt['expiration_date']."</th><th>".$txt['group']."</th><th>".$txt['auteur']."</th></tr></thead>";
        $text_pdf = "";
        $rows = $db->fetch_all_array("
            SELECT u.login AS login,
            i.id AS id, i.label AS label, i.id_tree AS id_tree,
            l.date AS date, l.id_item AS id_item, l.action AS action, l.raison AS raison,
            n.renewal_period AS renewal_period, n.title AS title
            FROM ".$pre."log_items AS l
            INNER JOIN ".$pre."items AS i ON (l.id_item=i.id)
            INNER JOIN ".$pre."users AS u ON (l.id_user=u.id)
            INNER JOIN ".$pre."nested_tree AS n ON (n.id=i.id_tree)
            WHERE i.inactif = '0'
            AND (l.action = 'at_creation' OR (l.action = 'at_modification' AND l.raison LIKE 'at_pw :%') )
            AND n.renewal_period != '0'
            ORDER BY i.label ASC, l.date DESC");
        $id_managed = '';
        foreach( $rows as $reccord ){
            if ( empty($id_managed) || $id_managed != $reccord['id'] ){
                //manage the date limit
                $item_date = $reccord['date'] + ($reccord['renewal_period'] * $k['one_month_seconds']);

                if ( $item_date <= $date ){
                    //Save data found
                    $texte .= '<tr><td width=\"250px\"><span class=\"ui-icon ui-icon-link\" style=\"float: left; margin-right: .3em; cursor:pointer;\" onclick=\"javascript:window.location.href = \'index.php?page=items&amp;group='.$reccord['id_tree'].'&amp;id='.$reccord['id'].'\'\">&nbsp;</span>'.$reccord['label'].'</td><td width=\"100px\" align=\"center\">'.date($_SESSION['settings']['date_format'],$reccord['date']).'</td><td width=\"100px\" align=\"center\">'.date($_SESSION['settings']['date_format'],$item_date).'</td><td width=\"150px\" align=\"center\">'.$reccord['title'].'</td><td width=\"100px\" align=\"center\">'.$reccord['login'].'</td></tr>';

                    //save data for PDF
                    if (empty($text_pdf) )
                        $text_pdf = $reccord['label'].'@;@'.date($_SESSION['settings']['date_format'],$reccord['date']).'@;@'.date($_SESSION['settings']['date_format'],$item_date).'@;@'.$reccord['title'].'@;@'.$reccord['login'];
                    else
                        $text_pdf .= '@|@'.$reccord['label'].'@;@'.date($_SESSION['settings']['date_format'],$reccord['date']).'@;@'.date($_SESSION['settings']['date_format'],$item_date).'@;@'.$reccord['title'].'@;@'.$reccord['login'];
                }
            }
            $id_managed = $reccord['id'];
        }

        //Display and store data
        echo 'document.getElementById("list_renewal_items").innerHTML = "'.$texte.'</table>";';
        echo '$("#renewal_icon_pdf").show();';
        echo 'document.getElementById("list_renewal_items_pdf").value = "'.$text_pdf.'";';
        //reload
        echo 'LoadingPage();';
    break;

    #----------------------------------
    #CASE generating the pdf of items to rennew
    case "generate_renewal_pdf":
        require_once ("NestedTree.class.php");
        $tree = new NestedTree($pre.'nested_tree', 'id', 'parent_id', 'title');

        //Prepare the PDF file
    	include('../includes/libraries/tfpdf/tfpdf.php');
    	$pdf=new tFPDF();

    	//Add font for utf-8
    	$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);

        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('DejaVu','',16);
        $pdf->Cell(0,10,$txt['renewal_needed_pdf_title'],0,1,'C',false);
        $pdf->SetFont('DejaVu','',12);
        $pdf->Cell(0,10,$txt['pdf_del_date'].date($_SESSION['settings']['date_format']." ".$_SESSION['settings']['time_format'],mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))),0,1,'C',false);
        $pdf->SetFont('DejaVu','',10);
        $pdf->SetFillColor(192,192,192);
        $pdf->cell(70,6,$txt['label'],1,0,"C",1);
        $pdf->cell(25,6,$txt['creation_date'],1,0,"C",1);
        $pdf->cell(25,6,$txt['expiration_date'],1,0,"C",1);
        $pdf->cell(45,6,$txt['group'],1,0,"C",1);
        $pdf->cell(25,6,$txt['author'],1,1,"C",1);
        $pdf->SetFont('DejaVu','',9);


        foreach( explode('@|@',addslashes($_POST['text'])) as $line ){
            $elem = explode('@;@',$line);
            if ( !empty($elem[0]) ){
                $pdf->cell(70,6,$elem[0],1,0,"L");
                $pdf->cell(25,6,$elem[1],1,0,"C");
                $pdf->cell(25,6,$elem[2],1,0,"C");
                $pdf->cell(45,6,$elem[3],1,0,"C");
                $pdf->cell(25,6,$elem[4],1,1,"C");
            }
        }

        $pdf_file = "renewal_pdf_".date("Y-m-d",mktime(0,0,0,date('m'),date('d'),date('y'))).".pdf";
        //send the file
        $pdf->Output($_SESSION['settings']['cpassman_dir']."\\files\\".$pdf_file);
        //Open PDF
        echo 'window.open(\''.$_SESSION['settings']['cpassman_url'].'/files/'.$pdf_file.'\', \'_blank\');';
        //reload
        echo 'LoadingPage();';
    break;
}
?>