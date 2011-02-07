<?php
/**
 * @file 		main.queries.php
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

global $k, $settings;
include('../includes/settings.php');
header("Content-type: text/html; charset=".$k['charset']);
error_reporting (E_ERROR);
require_once('main.functions.php');

// connect to the server
    require_once("class.database.php");
    $db = new Database($server, $user, $pass, $database, $pre);
    $db->connect();

//User's language loading
$k['langage'] = @$_SESSION['user_language'];
require_once('../includes/language/'.$_SESSION['user_language'].'.php');

//Manage type of action asked
switch($_POST['type'])
{
    case "change_pw":
        //Get a string with the old pw array
        $last_pw = explode(';',$_SESSION['last_pw']);

        $new_pw = encrypt(string_utf8_decode($_POST['new_pw']));

        //if size is bigger then clean the array
        if ( sizeof($last_pw) > $_SESSION['settings']['number_of_used_pw'] && $_SESSION['settings']['number_of_used_pw'] > 0 ){
            for($x=0;$x<$_SESSION['settings']['number_of_used_pw'];$x++)
                unset($last_pw[$x]);

            //reinit SESSION
            $_SESSION['last_pw'] = implode(';',$last_pw);
        }
        //specific case where admin setting "number_of_used_pw" is 0
        else if ( $_SESSION['settings']['number_of_used_pw'] == 0 ){
            $_SESSION['last_pw'] = "";
            $last_pw = array();
        }

        //check if new pw is different that old ones
        if ( in_array($new_pw,$last_pw) ){
            echo 'document.getElementById("new_pw").value = "";';
            echo 'document.getElementById("new_pw2").value = "";';
            echo '$("#change_pwd_error").addClass("ui-state-error ui-corner-all").show().html("<span>'.$txt['pw_used'].'</span>");';
        }else{
            //update old pw with new pw
            if ( sizeof($last_pw) == ($_SESSION['settings']['number_of_used_pw']+1) ){
                unset($last_pw[0]);
            }else{
                array_push($last_pw,$new_pw);
            }

            //create a list of last pw based on the table
            $old_pw = "";
            foreach($last_pw as $elem){
                if ( !empty($elem) ){
                    if (empty($old_pw)) $old_pw = $elem;
                    else $old_pw .= ";".$elem;
                }
            }

            //update sessions
            $_SESSION['last_pw'] = $old_pw;
            $_SESSION['last_pw_change'] = mktime(0,0,0,date('m'),date('d'),date('y'));
            $_SESSION['validite_pw'] = true;

            //update DB
            $db->query_update(
                "users",
                array(
                    'pw' => $new_pw,
                    'last_pw_change' => mktime(0,0,0,date('m'),date('d'),date('y')),
                    'last_pw' => $old_pw
                ),
                "id = ".$_SESSION['user_id']
            );

            //reload page
            echo 'document.main_form.submit();';
        }

    break;

    case "identify_user":
        session_start();

        require_once ("main.functions.php");
        require_once ("../sources/NestedTree.class.php");

        //GET SALT KEY LENGTH
        if ( strlen(SALT) > 32 ) {
            $_SESSION['error']['salt'] = TRUE;
        }

        $_SESSION['user_language'] = $k['langage'];
        $ldap_connection = false;
        $username = mysql_real_escape_string(stripslashes($_POST['login']));

        //Manage password encryption
        $received_password = (urldecode($_POST['pw']));
        $password = encrypt($received_password);

        //Build tree of folders
    	$tree = new NestedTree($pre.'nested_tree', 'id', 'parent_id', 'title');

        /* LDAP connection */
    	//create temp file
    	$dbg_ldap = fopen("../files/ldap.debug.txt","w");

        if ( isset($_SESSION['settings']['ldap_mode']) && $_SESSION['settings']['ldap_mode'] == 1 ){
        	fputs($dbg_ldap, "Get all ldap params : \n".
        		'base_dn : ' . $_SESSION['settings']['ldap_domain_dn'] . "\n".
        		'account_suffix : ' . $_SESSION['settings']['ldap_suffix'] . "\n".
        		'domain_controllers : ' . $_SESSION['settings']['ldap_domain_controler'] . "\n".
        		'use_ssl : ' . $_SESSION['settings']['ldap_ssl'] . "\n".
        		'use_tls : ' . $_SESSION['settings']['ldap_tls'] . "\n*********\n\n"
        	);	//Debug
            require_once ("../includes/libraries/adLDAP/adLDAP.php");
            $adldap = new adLDAP(array(
            	'base_dn' => $_SESSION['settings']['ldap_domain_dn'],
	            'account_suffix' => $_SESSION['settings']['ldap_suffix'],
	            'domain_controllers' => array($_SESSION['settings']['ldap_domain_controler']),
	            'use_ssl' => $_SESSION['settings']['ldap_ssl'],
	            'use_tls' => $_SESSION['settings']['ldap_tls']
            ));
        	fputs($dbg_ldap, "Create new adldap object : ".$adldap->get_last_error()."\n\n\n");	//Debug

            //authenticate the user
            if ($adldap -> authenticate($username,$password)){
                $ldap_connection = true;
            }else{
                $ldap_connection = false;
            }
        	fputs($dbg_ldap, "After authenticate : ".$adldap->get_last_error()."\n\n\n".
        		"ldap status : ".$ldap_connection."\n\n\n");	//Debug
        }

        $sql="SELECT * FROM ".$pre."users WHERE login = '".$username."'";
        $row = $db->query($sql);
        if (mysql_num_rows($row) > 0 ){
            //User exists in the DB
            $data = $db->fetch_array($row);

            //manage md5 to new encryption
            if (md5($received_password) == $data['pw']) {
                $db->query_update(
                    "users",
                    array(
                        'pw'=>$password
                    ),
                    "id=".$data['id']
                );
                $data['pw'] = $password;
            }

            if ( (isset($_SESSION['settings']['ldap_mode']) && $_SESSION['settings']['ldap_mode'] == 0 && ($password) == $data['pw'] && $data['disabled'] == 0)
                 ||
                 (isset($_SESSION['settings']['ldap_mode']) && $_SESSION['settings']['ldap_mode'] == 1 && $ldap_connection == true && $data['disabled'] == 0)
            ) {
                $_SESSION['autoriser'] = true;

                // Generate a ramdom ID
                $key = "";
                include('../includes/libraries/pwgen/pwgen.class.php');
	            $pwgen = new PWGen();
	            $pwgen->setLength(50);
	            $pwgen->setSecure(true);
                $pwgen->setSymbols(false);
                $pwgen->setCapitalize(true);
                $pwgen->setNumerals(true);
	            $key = $pwgen->generate();

                //Log into DB the user's connection
                if ( isset($_SESSION['settings']['log_connections']) && $_SESSION['settings']['log_connections'] == 1 )
                    logEvents('user_connection','connection',$data['id']);

                //Save account in SESSION
                   $_SESSION['login'] = $username;
                   $_SESSION['user_id'] = $data['id'];
                   $_SESSION['user_admin'] = $data['admin'];
                   $_SESSION['user_gestionnaire'] = $data['gestionnaire'];
                   $_SESSION['last_pw_change'] = $data['last_pw_change'];
                   $_SESSION['last_pw'] = $data['last_pw'];
                   $_SESSION['can_create_root_folder'] = $data['can_create_root_folder'];
                   $_SESSION['cle_session'] = $key;
                   $_SESSION['fin_session'] = time() + $_POST['duree_session'] * 60;
                   if ( empty($data['last_connexion']) ) $_SESSION['derniere_connexion'] = mktime(date('h'),date('m'),date('s'),date('m'),date('d'),date('y'));
                   else $_SESSION['derniere_connexion'] = $data['last_connexion'];
                   if ( !empty($data['latest_items']) ) $_SESSION['latest_items'] = explode(';',$data['latest_items']);
                   else $_SESSION['latest_items'] = array();
                   if ( !empty($data['favourites']) ) $_SESSION['favourites'] = explode(';',$data['favourites']);
                   else $_SESSION['favourites'] = array();


        	    if (!empty($data['groupes_visibles'])) {
        		    $_SESSION['groupes_visibles'] = @implode(';',$data['groupes_visibles']);
        	    }else{
        		    $_SESSION['groupes_visibles'] = array();
        	    }
        	    if (!empty($data['groupes_interdits'])) {
        		    $_SESSION['groupes_interdits'] = @implode(';',$data['groupes_interdits']);
        	    }else{
        		    $_SESSION['groupes_interdits'] = array();
        	    }

                   $_SESSION['fonction_id'] = $data['fonction_id'];

       		    //build array of roles
       		    $_SESSION['arr_roles'] = array();
        	    foreach(array_filter(explode(';', $_SESSION['fonction_id'])) as $role){
       			    $res_roles = $db->query_first("SELECT title FROM ".$pre."roles_title WHERE id = ".$role);
       			    $_SESSION['arr_roles'][$role] = array(
       				    'id' => $role,
       				    'title' => $res_roles['title']
       			    );
        	    }

           		//build complete array of roles
       		    $_SESSION['arr_roles_full'] = array();
            	$rows = $db->fetch_all_array("
								SELECT id, title
								FROM ".$pre."roles_title A
								ORDER BY title ASC");
            	foreach ($rows as $reccord){
            		$_SESSION['arr_roles_full'][$reccord['id']] = array(
       				    'id' => $reccord['id'],
       				    'title' => $reccord['title']
       			    );
            	}

            	//Set some settings
                $_SESSION['user']['find_cookie'] = false;
                $_SESSION['settings']['update_needed'] = "";

                // Update table
                $db->query_update(
                    "users",
                    array(
                        'key_tempo'=>$_SESSION['cle_session'],
                        'last_connexion'=>mktime(date("h"),date("i"),date("s"),date("m"),date("d"),date("Y")),
                        'disabled'=>0,
                        'no_bad_attempts'=>0
                    ),
                    "id=".$data['id']
                );

                //Get user's rights
                IdentifyUserRights($data['groupes_visibles'],$_SESSION['groupes_interdits'],$data['admin'],$data['fonction_id'],false);

                //Get some more elements
                $_SESSION['hauteur_ecran'] = $_POST['hauteur_ecran'];

                //Get last seen items
                $_SESSION['latest_items_tab'][] = "";
                foreach($_SESSION['latest_items'] as $item){
                    if ( !empty($item) ){
                        $data = $db->query_first("SELECT label,id_tree FROM ".$pre."items WHERE id = ".$item);
                        $_SESSION['latest_items_tab'][$item] = array(
                            'label'=>$data['label'],
                            'url'=>'index.php?page=items&amp;group='.$data['id_tree'].'&amp;id='.$item
                        );
                    }
                }
                //send back the random key
                $return = $_POST['randomstring'];
            }
            else if ($data['disabled'] == 1) {
                //User and password is okay but account is locked
                $return = "user_is_locked";
            }
            else{
                //User exists in the DB but Password is false
                //check if user is locked
                $user_is_locked = 0;
                $nb_attempts = intval($data['no_bad_attempts'] + 1);
                if ($_SESSION['settings']['nb_bad_authentication'] > 0 && intval($_SESSION['settings']['nb_bad_authentication']) < $nb_attempts) {
                    $user_is_locked = 1;

                    //log it
                    if ( isset($_SESSION['settings']['log_connections']) && $_SESSION['settings']['log_connections'] == 1 )
                    logEvents('user_locked','connection',$data['id']);
                }
                $db->query_update(
                    "users",
                    array(
                        'key_tempo'=>$_SESSION['cle_session'],
                        'last_connexion'=>mktime(date("h"),date("i"),date("s"),date("m"),date("d"),date("Y")),
                        'disabled'=>$user_is_locked,
                        'no_bad_attempts'=>$nb_attempts
                    ),
                    "id=".$data['id']
                );

                //What return shoulb we do
                if ($user_is_locked == 1) {
                    $return = "user_is_locked";
                }else if ($_SESSION['settings']['nb_bad_authentication'] == 0) {
                    $return = "false";
                }else{
                    $return = $nb_attempts;
                }
            }
        }
        else{
            $return = "false";
        }
        echo $return;

    break;

    case "augmenter_session":
        $_SESSION['fin_session'] = $_SESSION['fin_session']+3600;
        echo 'document.getElementById(\'temps_restant\').value = "'.$_SESSION['fin_session'].'";';
    break;

    //Used in order to send the password to the user by email
    case "send_pw_by_email":
    	echo '$("#div_forgot_pw_alert").removeClass("ui-state-error");';
    	//found account and pw associated to email
    	$data = $db->fetch_row("SELECT COUNT(*) FROM ".$pre."users WHERE email = '".mysql_real_escape_string(stripslashes(($_POST['email'])))."'");
    	if ( $data[0] != 0 ){
    		$data = $db->fetch_array("SELECT login,pw FROM ".$pre."users WHERE email = '".mysql_real_escape_string(stripslashes(($_POST['email'])))."'");

    		// Generate a ramdom ID
    		$key = "";
    		include('../includes/libraries/pwgen/pwgen.class.php');
    		$pwgen = new PWGen();
    		$pwgen->setLength(50);
    		$pwgen->setSecure(true);
    		$pwgen->setSymbols(false);
    		$pwgen->setCapitalize(true);
    		$pwgen->setNumerals(true);
    		$key = $pwgen->generate();

    		//load library
    		require_once("../includes/libraries/phpmailer/class.phpmailer.php");

    		//send to user
    		$mail = new PHPMailer();
    		$mail->SetLanguage("en","../includes/libraries/phpmailer/language/");
    		$mail->IsSMTP();	// send via SMTP
    		$mail->Host     = $smtp_server; // SMTP servers
    		$mail->SMTPAuth = $smtp_auth;     // turn on SMTP authentication
    		$mail->Username = $smtp_auth_username;  // SMTP username
    		$mail->Password = $smtp_auth_password; // SMTP password
    		$mail->From     = $email_from;
    		$mail->FromName = $email_from_name;
    		$mail->AddAddress($_POST['email']);     //Destinataire
    		$mail->WordWrap = 80;                              // set word wrap
    		$mail->IsHTML(true);                               // send as HTML
    		$mail->Subject  =  $txt['forgot_pw_email_subject'];
    		$mail->AltBody  =  $txt['forgot_pw_email_altbody_1']." ".$txt['at_login']." : ".$data['login']." - ".$txt['index_password']." : ".md5($data['pw']);
    		$mail->Body     =  $txt['forgot_pw_email_body_1']." ".$_SESSION['settings']['cpassman_url']."/index.php?action=password_recovery&key=".$key."&login=".$_POST['login'];

    		//Check if email has already a key in DB
    		$data = $db->fetch_row("SELECT COUNT(*) FROM ".$pre."misc WHERE intitule = '".$_POST['login']."' AND type = 'password_recovery'");
    		if ( $data[0] != 0 ){
    			$db->query_update(
	    			"misc",
	    			array(
	    			    'valeur' => $key
	    			),
	    			array(
		    			'type' => 'password_recovery',
		    			'intitule' => $_POST['login']
		    		)
    			);
    		}else{
    			//store in DB the password recovery informations
    			$db->query_insert(
	    			'misc',
	    			array(
	    			    'type' => 'password_recovery',
	    			    'intitule' => $_POST['login'],
	    			    'valeur' => $key
	    			)
    			);
    		}

			//send email
    		if(!$mail->Send())
    		{
    			echo '$("#div_forgot_pw_alert").html("'.$mail->ErrorInfo.'").addClass("ui-state-error").show();';
    		}
    		else
    		{
    			echo '$("#div_forgot_pw_alert").html("'.$txt['forgot_my_pw_email_sent'].'");$("#div_forgot_pw").dialog("close");';
    		}
        }else{
            //no one has this email ... alert
            echo '$("#div_forgot_pw_alert").html("'.$txt['forgot_my_pw_error_email_not_exist'].'").addClass("ui-state-error").show();';
        }
    break;

    //Send to user his new pw if key is conform
    case "generate_new_password":
    	//check if key is okay
    	$data = $db->fetch_row("SELECT valeur FROM ".$pre."misc WHERE intitule = '".$_POST['login']."' AND type = 'password_recovery'");
    	if($_POST['key'] == $data[0]) {
    		//Generate and change pw
    		$new_pw = "";
    		include('../includes/libraries/pwgen/pwgen.class.php');
    		$pwgen = new PWGen();
    		$pwgen->setLength(10);
    		$pwgen->setSecure(true);
    		$pwgen->setSymbols(false);
    		$pwgen->setCapitalize(true);
    		$pwgen->setNumerals(true);
    		$new_pw_not_crypted = $pwgen->generate();
    		$new_pw = encrypt(string_utf8_decode($new_pw_not_crypted));

    		//update DB
    		$db->query_update(
    		"users",
    		array(
    			'pw' => $new_pw
    		),
    		"login = '".$_POST['login']."'"
    		);

    		//Delete recovery in DB
    		$db->query_delete(
    		"misc",
    		array(
    			'type' => 'password_recovery',
    			'intitule' => $_POST['login'],
    			'valeur' => $key
    		)
    		);

    		//Get email
    		$data_user = $db->query_first("SELECT email FROM ".$pre."users WHERE login = '".$_POST['login']."'");

    		$_SESSION['validite_pw'] = false;

    		//load library
    		require_once("../includes/libraries/phpmailer/class.phpmailer.php");

    		//send to user
    		$mail = new PHPMailer();
    		$mail->SetLanguage("en","../includes/libraries/phpmailer/language/");
    		$mail->IsSMTP();						// send via SMTP
    		$mail->Host     = $smtp_server; 		// SMTP servers
    		$mail->SMTPAuth = $smtp_auth;     		// turn on SMTP authentication
    		$mail->Username = $smtp_auth_username;  // SMTP username
    		$mail->Password = $smtp_auth_password; 	// SMTP password
    		$mail->From     = $email_from;
    		$mail->FromName = $email_from_name;
    		$mail->AddAddress($data_user['email']); //Destinataire
    		$mail->WordWrap = 80;					// set word wrap
    		$mail->IsHTML(true);					// send as HTML
    		$mail->Subject  =  $txt['forgot_pw_email_subject_confirm'];
    		$mail->AltBody  =  strip_tags($txt['forgot_pw_email_body'])." ".$new_pw_not_crypted;
    		$mail->Body     =  $txt['forgot_pw_email_body']." ".$new_pw_not_crypted;

    		//send email
    		if($mail->Send())
    		{
    			echo 'done';
    		}
    		else
    		{
    			echo $mail->ErrorInfo;
    		}
    	}
    break;

    case "get_folders_list":
    	echo '$("#'.$_POST['div_id'].'").empty();';

    	/* Get full tree structure */
    	require_once ("NestedTree.class.php");
    	$tree = new NestedTree($pre.'nested_tree', 'id', 'parent_id', 'title');
    	$folders = $tree->getDescendants();

		/* Build list of all folders */
    	$folders_list = "\'0\':\'".$txt['root']."\'";
		foreach($folders as $f){
			//Be sure that user can only see folders he/she is allowed to
			if ( !in_array($f->id,$_SESSION['forbiden_pfs']) ) {
				$display_this_node = false;
				// Check if any allowed folder is part of the descendants of this node
				$node_descendants = $tree->getDescendants($f->id, true, false, true);
				foreach ($node_descendants as $node){
					if (in_array($node, $_SESSION['groupes_visibles'])) {
						$display_this_node = true;
						break;
					}
				}

				if ($display_this_node == true) {
					if ( $f->title ==$_SESSION['user_id'] && $f->nlevel == 1 ) $f->title = $_SESSION['login'];
					echo '$("#'.$_POST['div_id'].'").append("<option value=\''.$f->id.'\'>'.str_replace("&","&amp;",$f->title).'</option>");';
				}
			}
		}
    	break;

    case "print_out_items":
        echo 'LoadingPage();';
    	include('main.functions.php');
    	$full_listing = array();

    	foreach (explode(';', $_POST['ids']) as $id){
    		if (!in_array($id, $_SESSION['forbiden_pfs']) && in_array($id, $_SESSION['groupes_visibles'])) {

	   			$rows = $db->fetch_all_array("
	                   SELECT i.id AS id, i.restricted_to AS restricted_to, i.perso AS perso, i.label AS label, i.description AS description, i.pw AS pw, i.login AS login,
	                       l.date AS date,
	                       n.renewal_period AS renewal_period
	                   FROM ".$pre."items AS i
	                   INNER JOIN ".$pre."nested_tree AS n ON (i.id_tree = n.id)
	                   INNER JOIN ".$pre."log_items AS l ON (i.id = l.id_item)
	                   WHERE i.inactif = 0
	                   AND i.id_tree=".$id."
	                   AND (l.action = 'at_creation' OR (l.action = 'at_modification' AND l.raison LIKE 'at_pw :%'))
	                   ORDER BY i.label ASC, l.date DESC
                ");

	   			$id_managed = '';
	   			$i = 0;
	   			$items_id_list = array();
	   			foreach( $rows as $reccord ) {
                    $restricted_users_array = explode(';',$reccord['restricted_to']);
	   				//exclude all results except the first one returned by query
	   				if ( empty($id_managed) || $id_managed != $reccord['id'] ){
	   					if (
                            (in_array($id, $_SESSION['personal_visible_groups']) && !($reccord['perso'] == 1 && $_SESSION['user_id'] == $reccord['restricted_to']) && !empty($reccord['restricted_to']))
                            ||
                            (!empty($reccord['restricted_to']) && !in_array($_SESSION['user_id'],$restricted_users_array))
                        ){
	   						//exclude this case
	   					}else {
	   						//encrypt PW
	   						if ( !empty($_POST['salt_key']) && isset($_POST['salt_key']) ){
	   							$pw = decrypt($reccord['pw'], mysql_real_escape_string(stripslashes($_POST['salt_key'])));
	   						}else
	   							$pw = decrypt($reccord['pw']);

	   						$full_listing[$reccord['id']] = array(
		   						'id' => $reccord['id'],
		   						'label' => $reccord['label'],
		   						'pw' => $pw,
		   						'login' => $reccord['login']
							);
	   					}
	    			}
	   				$id_managed = $reccord['id'];
	   			}
   			}

    	}

    	//Build PDF
    	if (!empty($full_listing)) {
    		//Prepare the PDF file
    		include('../includes/libraries/fpdf/pdf.fonctions.php');
    		$pdf=new FPDF();
    		$pdf->AliasNbPages();
    		$pdf->AddPage();
    		$pdf->SetFont('Arial','B',16);
    		$pdf->Cell(0,10,$txt['print_out_pdf_title'],0,1,'C',false);
    		$pdf->SetFont('Arial','I',12);
    		$pdf->Cell(0,10,$txt['pdf_del_date'].date($_SESSION['settings']['date_format']." ".$_SESSION['settings']['time_format'],mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"))).' '.$txt['by'].' '.$_SESSION['login'],0,1,'C',false);
    		$pdf->SetFont('Arial','B',10);
    		$pdf->SetFillColor(192,192,192);
    		$pdf->cell(65,6,$txt['label'],1,0,"C",1);
    		$pdf->cell(55,6,$txt['login'],1,0,"C",1);
    		$pdf->cell(70,6,$txt['pw'],1,1,"C",1);
    		$pdf->SetFont('Arial','',9);

    		foreach( $full_listing as $item ){
   				$pdf->cell(65,6,stripslashes($item['label']),1,0,"L");
   				$pdf->cell(55,6,stripslashes($item['login']),1,0,"C");
   				$pdf->cell(70,6,stripslashes($item['pw']),1,1,"C");
    		}

    		$pdf_file = "print_out_pdf_".date("Y-m-d",mktime(0,0,0,date('m'),date('d'),date('y'))).".pdf";
    		//send the file
    		$pdf->Output($_SESSION['settings']['cpassman_dir']."\\files\\".$pdf_file);
    		//Open PDF
    		echo 'window.open(\''.$_SESSION['settings']['cpassman_url'].'/files/'.$pdf_file.'\', \'_blank\');';
    		//reload
    		echo 'LoadingPage();';
    	}
    	break;

		case "store_personal_saltkey":
			$_SESSION['my_sk'] = str_replace(" ","+",urldecode($_POST['sk']));
			//Open dialogbox
			echo '$("#div_dialog_message_text").html("<div style=\"font-size:16px;\"><span class=\"ui-icon ui-icon-info\" style=\"float: left; margin-right: .3em;\"></span>'.$txt['alert_message_done'].'</div>");$("#div_dialog_message").dialog("open");';
		break;
}

?>
