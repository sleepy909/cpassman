<?php
/**
 * @file 		import.queries.php
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
header("Content-type: text/html; charset=".$k['charset']);
error_reporting (E_ERROR);
include('../includes/settings.php');

// connect to the server
require_once("class.database.php");
$db = new Database($server, $user, $pass, $database, $pre);
$db->connect();

//User's language loading
$k['langage'] = @$_SESSION['user_language'];
require_once('../includes/language/'.$_SESSION['user_language'].'.php');

// Construction de la requ?te en fonction du type de valeur
switch($_POST['type'])
{
    //Check if import CSV file format is what expected
	case "import_file_format_csv":        //clean divs
        echo '$(\'#import_status\').html(\'\');$(\'#import_from_file_info\').html(\'\').hide();';

        //Call nestedtree library and load full tree
        require_once ("NestedTree.class.php");
        $tree = new NestedTree($pre.'nested_tree', 'id', 'parent_id', 'title');
        $tree->rebuild();
        $tst = $tree->getDescendants();

        // do some initializations
        $file = "../files/".$_POST['file'];
        $size = 4096;
        $separator = ",";
        $enclosure = '"';
        $fields_expected = array("Account","Login Name","Password","Web Site","Comments");  //requiered fields from CSV
        $importation_possible = true;
        $display = "<table>";
        $line_number = $prev_level = 0;
        $account = $text = "";
        $continue_on_next_line = false;

        // Open file
        if($fp = fopen($file,"r")) {
            // extract one line
            while ( ($line = fgetcsv($fp, $size, $separator, $enclosure)) !== FALSE ) {
                //Check number of fields. MUST be 5. if not stop importation
                if ( $line_number == 0 ){
                    if ( count($line) != 5 ) $importation_possible = false;
                    //Stop if file has not expected structure
                    if ( $importation_possible == false ){
                        echo '$(\'#import_from_file_info\').html(\''.addslashes($txt['import_error_no_read_possible']).'\').show();';
                        break;
                    }
                }
                if ( $line_number> 0){
                    //If any comment is on several lines, then replace 'lf' character
                    $line[4] = str_replace(array("\r\n", "\n", "\r"),"<br />",$line[4]);

                    // Check if current line contains a "<br />" character in order to identify an ITEM on several CSV lines
                    if ( substr_count('<br />',$line[4]) > 0 || substr_count('<br />',$line[0]) > 0 ){
                        $continue_on_next_line = true;
                        $comment .= addslashes($line[0]);
                    }else
                        // Store in variable values from previous line
                        if ( !empty($line[0]) && !empty($line[2]) && !empty($account) ){
                        if ( $continue_on_next_line == false ){
                            // Prepare listing that will be shown to user
                            $display .= '<tr><td><input type="checkbox" class="item_checkbox" id="item_to_import-'.$line_number.'" /></td><td><span id="item_text-'.$line_number.'">'.$account.'</span><input type="hidden" value="'.$account.'@|@'.$login.'@|@'.$pw.'@|@'.$url.'@|@'.$comment.'@|@'.$line_number.'" id="item_to_import_values-'.$line_number.'" /></td></tr>';

                            // Initialize this variable in order to restart from scratch
                            $account = "";
                        }
                    }

                    // Get values of current line
                    if ( $account == "" && $continue_on_next_line == false){
                        $account = addslashes($line[0]);
                        $login = addslashes($line[1]);
                        $pw = $line[2];
                        $url = addslashes($line[3]);
                        $comment = htmlentities(addslashes($line[4]),ENT_QUOTES);

                        $continue_on_next_line = false;
                    }
                }

                //increment number of lines found
                $line_number++;
            }
            // close file
            fclose ($fp);
        } else {
            echo '$(\'#import_from_file_info\').html(\''.$txt['import_error_no_read_possible'].'\').show();';
        }

        if ( $line_number > 0 ){
        	//add last line
        	$display .= '<tr><td><input type="checkbox" class="item_checkbox" id="item_to_import-'.$line_number.'" /></td><td><span id="item_text-'.$line_number.'">'.$account.'</span><input type="hidden" value="'.$account.'@|@'.$login.'@|@'.$pw.'@|@'.$url.'@|@'.$comment.'@|@'.$line_number.'" id="item_to_import_values-'.$line_number.'" /></td></tr>';

            // Add a checkbox for select/unselect all others
            $display .= '<tr><td><input type="checkbox" id="item_all_selection" /></td><td>'.$txt['all'].'</td></tr>';
            //echo 'function selectAll() {$("input[type=\'checkbox\']:not([disabled=\'disabled\'])").attr(\'checked\', true);}';

            // Prepare a list of all folders that the user can choose
            $display .= '</table><div style="margin-top:10px;"><label><b>'.$txt['import_to_folder'].'</b></label>&nbsp;<select id="import_items_to">';
            foreach($tst as $t){
                if ( in_array($t->id,$_SESSION['groupes_visibles']) ){
                    $ident="";
                    for($x=1;$x<$t->nlevel;$x++) $ident .= "&nbsp;&nbsp;";
                    if ($prev_level != NULL && $prev_level < $t->nlevel ){
                        $display .= '<option value="'.$t->id.'">'.$ident.str_replace("&","&amp;",addslashes(utf8_decode($t->title))).'</option>';
                    }else if ($prev_level != NULL && $prev_level == $t->nlevel ){
                       $display .= '<option value="'.$t->id.'">'.$ident.str_replace("&","&amp;",addslashes(utf8_decode($t->title))).'</option>';
                    }else{
                        $display .= '<option value="'.$t->id.'">'.$ident.str_replace("&","&amp;",addslashes(utf8_decode($t->title))).'</option>';
                    }
                    $prev_level = $t->nlevel;
                }
            }
            $display .= '</select></div>';

            // Show results to user.
            echo '$(\'#import_status\').html(\''.$display.'\');';
            echo '$(\'#item_all_selection\').click(function(){if ( $(\'#item_all_selection\').attr(\'checked\') ) { $("input[class=\'item_checkbox\']:not([disabled=\'disabled\'])").attr(\'checked\', true); } else { $("input[class=\'item_checkbox\']:not([disabled=\'disabled\'])").removeAttr(\'checked\');  }}); ';
        }

        // close ajax loader
        echo '$(\'#import_status_ajax_loader\').hide();';
    break;

    //Insert into DB the items the user has selected
    case "import_items":
        include('main.functions.php');
        //show ajax loader
        echo '$(\'#import_status_ajax_loader\').show();';

        foreach( explode('@_#sep#_@',mysql_real_escape_string(stripslashes($_POST['data']))) as $item ){
            //For each item, insert into DB
            $item = explode('@|@',$item);   //explode item to get all fields

            // Insert new item in table ITEMS
            $new_id = $db->query_insert(
                "items",
                array(
                    'label' => $item[0],
                    'description' => $item[4],
                    'pw' => encrypt($item[2]),
                    'url' => $item[3],
                    'id_tree' => $_POST['folder'],
                    'login' => $item[1],
                    'anyone_can_modify' => $_POST['import_csv_anyone_can_modify'] == "true" ? 1 : 0
                )
            );

        	//if asked, anyone in role can modify
        	if (isset($_POST['import_csv_anyone_can_modify_in_role']) && $_POST['import_csv_anyone_can_modify_in_role'] == "true") {
        		foreach($_SESSION['arr_roles'] as $role){
        			$db->query_insert(
        			'restriction_to_roles',
        			array(
        			    'role_id' => $role['id'],
        			    'item_id' => $new_id
        			)
        			);
        		}
        	}


            // Insert new item in table LOGS_ITEMS
            $db->query_insert(
                'log_items',
                array(
                    'id_item' => $new_id,
                    'date' => mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('y')),
                    'id_user' => $_SESSION['user_id'],
                    'action' => 'at_creation'
                )
            );

        	//reload Cache table
        	UpdateCacheTable("reload", "");

            //after inserted, disable the checkbox in order to prevent against new insert
            echo '$("#item_to_import-'.$item[5].'").attr("disabled", true);$("#item_text-'.$item[5].'").css("textDecoration", "line-through");$(\'#import_status_ajax_loader\').hide();';
        }
    break;

    //Check if import KEEPASS file format is what expected
    case "import_file_format_keepass":
        // call needed functions
        require_once('main.functions.php');
        require_once('NestedTree.class.php');

        //Initialization
        $root = $meta = $group = $entry = $key = $title = $notes = $pw = $username = $url = $not_a_keepass_file = $new_item = $history = $generator_found = false;
        $name = $level_in_progress = $previous_level = $full_path = $history_level = $path = $display = $keepass_version = "";
        $num_groups = $num_items = 0;
        $temp_array = $arrFolders = array();
        $level_min = 2;
        $folders_separator = '@&##&@';
        $items_separator = '<=|#|=>';
        $line_end_separator = '@*1|#9*|@';

        //clean divs
        echo '$(\'#import_status\').html(\'\');$(\'#import_from_file_info\').html(\'\').hide();';

        //prepare CACHE files
        $cacheFile_name = "../files/cpassman_cache_".md5(time().mt_rand());
        $cacheFile_name_folder = $cacheFile_name."_folders";
        $cacheFile = fopen($cacheFile_name,"w");
        $cacheFileF = fopen($cacheFile_name_folder,"w");

        //read xml file
        $xml = simplexml_load_file("../files/".$_POST['file']);

        /**
        Recursive function that will permit to read each level of XML nodes
        */
        function Recursive_Keepass_XML($xml_root, $xml_level = 0) {
            global $meta, $root, $group, $name, $entry, $level_min, $key, $title, $notes, $pw, $username, $url, $new_item, $temp_array, $history, $level_in_progress, $history_level, $nb_items, $path, $previous_level, $generator_found, $cacheFile, $cacheFileF, $num_groups, $num_items, $folders_separator, $items_separator, $line_end_separator, $keepass_version, $arrFolders;

            // For each node, get the name and SimpleXML balise
            foreach($xml_root as $nom=>$elem) {
            	/*
            	* check if file is generated by keepass 1
            	* key "pwentry" is only used in KP1.xx XML files
            	*/
            	if ($nom == "pwentry") {
            		if (empty($keepass_version)) {
            			$keepass_version = 1;
            			$generator_found = true;
            			$entry = true;
            		}else{
            			$entry = true;
            		}

            		//get children
            		$xml_children = $elem->children();

            		//recursive call
            		Recursive_Keepass_XML($xml_children, $xml_level + 1);
            	}
            	//IMPORTING KEEPASS 1 XML FILE
            	if ($keepass_version == 1) {
            		if (  $entry == true && $nom == "expiretime" ){
            			//save previous keepass entry
            			$tree = preg_replace('/\\\\/', $folders_separator, $temp_array['tree']);
            			fputs($cacheFile,$tree.$items_separator.$temp_array['group'].$items_separator.$temp_array['title'].$items_separator.$temp_array['pw'].$items_separator.$temp_array['username'].$items_separator.$temp_array['notes'].$items_separator.$temp_array['url']."\n");

						if (!in_array($temp_array['tree'], $arrFolders)) {
							fwrite($cacheFileF, $tree."\n");
							array_push($arrFolders, $temp_array['tree']);
						}

            			$temp_array = array();
            			$new_item++;
            		}

            		if (  $entry == true && $nom == "group" ){
            			$temp_array['group'] = addslashes(preg_replace('#[\r\n]#', '', $elem));
            			foreach($elem->attributes() as $attributeskey0 => $attributesvalue1){
            				if ($attributeskey0 == "tree") {
            					$path = explode('\\', $attributesvalue1);
            					if (count($path) > 1) {
            						unset($path[0]);
            						$temp_array['tree'] = implode('\\', $path).'\\'.$temp_array['group'];
            					}else{
            						$temp_array['tree'] = $temp_array['group'];
            					}
            				}
            			}
            			$num_groups ++;
            		}else if (  $entry == true && $nom == "title" ){
            			$temp_array['title'] = addslashes(preg_replace('#[\r\n]#', '', $elem));
            		}else if (  $entry == true && $nom == "username" ){
            			$temp_array['username'] = addslashes(preg_replace('#[\r\n]#', '', $elem));
            		}else if (  $entry == true && $nom == "url" ){
            			$temp_array['url'] = addslashes(preg_replace('#[\r\n]#', '', $elem));
            		}else if (  $entry == true && $nom == "password" ){
            			$temp_array['pw'] = addslashes(preg_replace('#[\r\n]#', '', $elem));
            		}else if (  $entry == true && $nom == "notes" ){
            			$temp_array['notes'] = addslashes(preg_replace('#[\r\n]#', $line_end_separator, $elem));
            		}
            	}

            	/*
            	   * check if file is generated by keepass 2
            	*/
                if(trim($elem) == "" && $keepass_version != 1) {
                    //check if file is generated by keepass 2
                    if ( $nom == "Meta" ) $meta = true;
                    if ( $nom == "Root" ) $root = true;

                    if ( $nom == "Group" ){
                        $group = true;
                        $entry = false;
                        $name = "";

                        // recap previous info
                        if ( !empty($temp_array['title'])  ){
                            //store data
                            fputs($cacheFile,$temp_array['path'].$items_separator.$temp_array['group'].$items_separator.$temp_array['title'].$items_separator.$temp_array['pw'].$items_separator.$temp_array['username'].$items_separator.$temp_array['notes'].$items_separator.$temp_array['url']."\n");

                            //Clean temp array
                            $temp_array['title'] = $temp_array['notes'] = $temp_array['pw'] = $temp_array['username'] = $temp_array['url'] = "";

                            //increment number
                            $num_items++;
                        }
                        $history_level = 0;
                    }

                    //History node needs to be managed in order to not polluate final list
                    if ( $nom == "History" ){
                        $history = true;
                        $entry = false;
                        $history_level = $xml_level;
                    }

                    if ( $nom == "Entry" && ( $xml_level < $history_level || empty($history_level) ) ) {//
                        $entry = true;
                        $group = false;

                        // recap previous info
                        if ( !empty($temp_array['title']) ){
                            //store data
                            fputs($cacheFile,$temp_array['path'].$items_separator.$temp_array['group'].$items_separator.$temp_array['title'].$items_separator.$temp_array['pw'].$items_separator.$temp_array['username'].$items_separator.$temp_array['notes'].$items_separator.$temp_array['url']."\n");

                            //Clean temp array
                            $temp_array['title'] = $temp_array['notes'] = $temp_array['pw'] = $temp_array['username'] = $temp_array['url'] = "";

                            //increment number
                            $num_items++;
                        }
                        $history_level = 0;
                    }

                    //get children
                    $xml_children = $elem->children();

                    //recursive call
                    Recursive_Keepass_XML($xml_children, $xml_level + 1);
                }

				//IMPORTING KEEPASS 2 XML FILE
            	else if ($keepass_version != 1) {
                    // exit if XML file not generated by KeePass
                    if ( $meta == true && $nom == "Generator" && $elem == "KeePass" ){
                        $generator_found = true;
                    	$keepass_version = 2;
                        break;
                    }
                    else if ( $root == true && $xml_level > $level_min  ){
                        //Check each node name and get data from some of them
                        if (  $entry == true && $nom == "Key" && $elem == "Title" ){
                            $title = true;
                            $notes = $pw = $url = $username = false;
                        }else if (  $entry == true && $nom == "Key" && $elem == "Notes" ){
                            $notes = true;
                            $title = $pw = $url = $username = false;
                        }else if (  $entry == true && $nom == "Key" && $elem == "Password" ){
                            $pw = true;
                            $notes = $title = $url = $username = false;
                        }else if (  $entry == true && $nom == "Key" && $elem == "URL" ){
                            $url = true;
                            $notes = $pw = $title = $username = false;
                        }else if (  $entry == true && $nom == "Key" && $elem == "UserName" ){
                            $username = true;
                            $notes = $pw = $url = $title = false;
                        }else if ( $group == true && $nom == "Name" ){
                            $temp_array['group'] = addslashes(preg_replace('#[\r\n]#', '', $elem));
                            $temp_array['level'] = $xml_level;
                            //build current path
                            if ( $xml_level > $level_in_progress ) {
                                if ( !empty($temp_array['path']) ) $temp_array['path'] .= $folders_separator.$temp_array['group'];
                                else $temp_array['path'] = $temp_array['group'];
                            }else if ( $xml_level == $level_in_progress ) {
                                if ( $level_in_progress == 3 )
                                    $temp_array['path'] = $temp_array['group'];
                                else
                                    $temp_array['path'] = substr($temp_array['path'],0,strrpos($temp_array['path'],$folders_separator)+strlen($folders_separator)).$temp_array['group'];
                            }else{
                                $diff = abs($xml_level-$level_in_progress)+1;
                                $tmp = explode($folders_separator,$temp_array['path']);
                                $temp_array['path'] = "";
                                for($x=0;$x<(count($tmp)-$diff);$x++){
                                    if ( !empty($temp_array['path']) ) $temp_array['path'] = $temp_array['path']. $folders_separator.$tmp[$x];
                                    else $temp_array['path'] = $tmp[$x];
                                }
                                if ( !empty($temp_array['path']) ) $temp_array['path'] .= $folders_separator.$temp_array['group'];
                                else $temp_array['path'] = $temp_array['group'];
                            }

                            //store folders
                            if ( !in_array($temp_array['path'],$groups_array) ) {
                                fwrite($cacheFileF,$temp_array['path']."\n");

                                //increment number
                                $num_groups ++;
                            }

                            //Store actual level
                            $level_in_progress = $xml_level;
                            $previous_level = $temp_array['group'];
                        }
                        else if ( $title == true && $nom == "Value" ){
                            $title = false;
                            $temp_array['title'] = addslashes(preg_replace('#[\r\n]#', '', $elem));
                        }
                        else if ( $notes == true && $nom == "Value" ){
                            $notes = false;
                            $temp_array['notes'] = addslashes(preg_replace('#[\r\n]#', $line_end_separator, $elem));
                        }
                        else if ( $pw == true && $nom == "Value" ){
                            $pw = false;
                            $temp_array['pw'] = addslashes(preg_replace('#[\r\n]#', '', $elem));
                        }
                        else if ( $url == true && $nom == "Value" ){
                            $url = false;
                            $temp_array['url'] = addslashes(preg_replace('#[\r\n]#', '', $elem));
                        }
                        else if ( $username == true && $nom == "Value" ){
                            $username = false;
                            $temp_array['username'] = addslashes(preg_replace('#[\r\n]#', '', $elem));
                        }
                    }
                }
            }
        }

        // Go through each node of XML file
        Recursive_Keepass_XML($xml);

        //Stop if not a keepass file
        if ( $generator_found == false ){
            echo '$(\'#import_status\').html(\''.addslashes($txt['import_error_no_read_possible_kp']).'\');';
            echo '$("#import_status_ajax_loader").hide();';
            break;
        }

        //save last item
        if ( !empty($temp_array['title']) ){
            //store data
            fputs($cacheFile,$temp_array['path'].$items_separator.$temp_array['group'].$items_separator.$temp_array['title'].$items_separator.$temp_array['pw'].$items_separator.$temp_array['username'].$items_separator.$temp_array['notes'].$items_separator.$temp_array['url']."\n");

            //increment number
            $num_items++;
        }



        ##################
        ## STARTING IMPORTING IF NO ERRORS OR NOT EMPTY
        ##################
        if ( $num_items>0 || $num_groups>0 ){
        	$items_array = array();
            $text = '<img src="includes/images/folder_open.png" alt="" \>&nbsp;'.$txt['nb_folders'].': '.$num_groups.'<br /><img src="includes/images/tag.png" alt="" \>&nbsp;'.$txt['nb_items'].': '.$num_items.'<br /><br />';
            $text .= '<img src="includes/images/magnifier.png" alt="" \>&nbsp;<a href="#" onclick="toggle_importing_details()">'.$txt['importing_details'].'</a><div id="div_importing_kp_details" style="display:none;margin-left:20px;"><b>'.$txt['importing_folders'].':</b><br />';

            //if destination is not ROOT then get the complexity level
            if ( $_POST['destination'] > 0 ){
                $data = $db->fetch_row("
                    SELECT m.valeur AS value, t.nlevel AS nlevel
                    FROM ".$pre."misc AS m
                    INNER JOIN ".$pre."nested_tree AS t ON (m.intitule = t.id)
                    WHERE m.type = 'complex'
                    AND m.intitule = '".$_POST['destination']."'");
                $level_pw_complexity = $data[0];
                $start_path_level = $data[1];
            }else{
                $level_pw_complexity = 50;
                $start_path_level = 0;
            }

            //Get all folders from file
            fclose($cacheFileF);
            $cacheFileF = fopen($cacheFile_name_folder,"r");

            //Create folders
            $i=1;
            $level = 0;
            $folders_array = array();
            $nb_folders_imported = 0;

            while (!feof($cacheFileF)) {
                $folder = fgets($cacheFileF, 4096);
                if ( !empty($folder) ){
                    $folder = str_replace(array("\r\n", "\n", "\r"),'',$folder);
                    //get number of levels in path
                    $path = explode($folders_separator,$folder);
                    $folder_level = count($path);

                    //get folder name
                    if ( strrpos($folder,$folders_separator) > 0 ){
                        $fold = substr($folder,strrpos($folder,$folders_separator)+strlen($folders_separator));
                        $parent_id = $folders_array[$path[$folder_level-2]]['id'];
                    }else {
                        $fold = $folder;
                        $parent_id = $_POST['destination']; //permits to select the folder destination
                    }

                    //create folder - if not exists at the same level
                    $data = $db->fetch_row("SELECT COUNT(*) FROM ".$pre."nested_tree WHERE nlevel = ".($folder_level+$start_path_level)." AND title = \"".utf8_decode($fold)."\" AND parent_id = ".$parent_id);
                    if ( $data[0] == 0 ){
                        //do query
                        $id = $db->query_insert(
                            "nested_tree",
                            array(
                                'parent_id' => $parent_id,
                                'title' => utf8_decode(stripslashes($fold)),
                                'nlevel' => $folder_level
                            )
                        );
                        //Add complexity level => level is set to "medium" by default.
                        $db->query_insert(
                            'misc',
                            array(
                                'type' => 'complex',
                                'intitule' => $id,
                                'valeur' => $level_pw_complexity
                            )
                        );

                    	//For each role to which the user depends on, add the folder just created.
                    	foreach ($_SESSION['arr_roles'] as $role){
                    		$db->query_insert(
	                    		"roles_values",
	                    		array(
	                    		    'role_id' => $role['id'],
	                    		    'folder_id' => $id
	                    		)
                    		);
                    	}

                    	//Add this new folder to the list of visible folders for the user.
                    	array_push($_SESSION['groupes_visibles'], $id);

                        //show
                        $text .= '<img src="includes/images/plus-small.png" alt="" \>'.(($fold)).'<br />';
                        //increment number of imported folders
                        $nb_folders_imported++;
                    }else{
                        //get forlder actual ID
                        $data = $db->fetch_row("SELECT id FROM ".$pre."nested_tree WHERE nlevel = '".($folder_level+$start_path_level)."' AND title = '".$fold."' AND parent_id = '".$parent_id."'");
                        $id = $data[0];
                    }

                    //store in array
                    $folders_array[$fold] = array(
                        'folder' => $fold,
                        'nlevel' => $folder_level,
                        'id' => $id
                    );

                    $_SESSION['nb_folders'] ++;
                    $i++;
                }
            }
            //Close file & delete it
            fclose($cacheFileF);
            unlink($cacheFile_name_folder);

            //if no new folders them inform
            if ( $nb_folders_imported == 0 ) $text .= $txt['none'].'<br />';
            else{
                //Refresh the rights of actual user
                IdentifyUserRights(implode(';',$_SESSION['groupes_visibles']).';'.$new_id, $_SESSION['groupes_interdits'], $_SESSION['is_admin'], $_SESSION['fonction_id'], true);

                //rebuild full tree
                $tree = new NestedTree($pre.'nested_tree', 'id', 'parent_id', 'title');
                $tree->rebuild();
            }
            //show
            $text .= '<br /><b>'.$txt['importing_items'].':</b><br />';

            // Now import ITEMS
            $nb_items_imported = 0;

            //prepare file to be read
            fclose($cacheFile);
            $cacheFile = fopen($cacheFile_name,"r");

            while (!feof($cacheFile)) {
                //prepare an array with item to import
                $item = fgets($cacheFile, 4096);
                $item = explode($items_separator,str_replace(array("\r\n", "\n", "\r"),'',$item));

                if ( !empty($item[2]) ){
                    //check if not exists
                    $data = $db->fetch_row("SELECT COUNT(*) FROM ".$pre."items WHERE id_tree = '".$folders_array[$item[1]]['id']."' AND label = \"".utf8_decode($item[2])."\"");

                    if ( $data[0] == 0 ){
                        //ADD item
                        $new_id = $db->query_insert(
                            'items',
                            array(
                                'label' => utf8_decode(stripslashes($item[2])),
                                'description' => utf8_decode(str_replace($line_end_separator,'<br />',$item[5])),
                                'pw' => encrypt($item[3]),
                                'url' => utf8_decode(stripslashes($item[6])),
                                'id_tree' => $folders_array[$item[1]]['id'],
	                            'login' => utf8_decode(stripslashes($item[4])),
	                            'anyone_can_modify' => $_POST['import_kps_anyone_can_modify'] == "true" ? 1 : 0
                            )
                        );

                    	//if asked, anyone in role can modify
                    	if (isset($_POST['import_kps_anyone_can_modify_in_role']) && $_POST['import_kps_anyone_can_modify_in_role'] == "true") {
                    		foreach($_SESSION['arr_roles'] as $role){
                    			$db->query_insert(
                    			'restriction_to_roles',
                    			array(
                    			    'role_id' => $role['id'],
                    			    'item_id' => $new_id
                    			)
                    			);
                    		}
                    	}

                        //Add log
                        $db->query_insert(
                            'log_items',
                            array(
                                'id_item' => $new_id,
                                'date' => mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('y')),
                                'id_user' => $_SESSION['user_id'],
                                'action' => 'at_creation'
                            )
                        );
                        //show
                        $text .= '<img src="includes/images/plus-small.png" alt="" \>'.addslashes($item[2]).'<br />';

                        //increment number of imported items
                        $nb_items_imported++;
                    }
                }
            }

            //if no new items them inform
            if ( $nb_items_imported == 0 ) $text .= $txt['none'].'<br />';

            //SHow finished
            $text .= '</div><br /><br /><b>'.$txt['import_kp_finished'].'</b>';

            //Delete cache file
            fclose($cacheFile);
            unlink($cacheFile_name);

        	//reload Cache table
        	UpdateCacheTable("reload", "");

            //Display all messages to user
            echo '$(\'#import_status\').html(\''.$text.'\');$(\'#import_status_ajax_loader\').hide();';
        }
        break;
}
?>