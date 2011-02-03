<?php
/*******************************************************************************
* File : admin.settings.php
* Author : Nils Laumaillé
* Description : Settings page
*
* DON'T CHANGE !!!
*
*******************************************************************************/

/*
* FUNCTION permitting to store into DB the settings changes
*/
function UpdateSettings($setting, $val, $type=''){
    global $server, $user, $pass, $database, $pre;

    if ( empty($type) ) $type = 'admin';

    //Connect to database
    require_once("sources/class.database.php");
    $db = new Database($server, $user, $pass, $database, $pre);
    $db->connect();

    //Check if setting is already in DB. If NO then insert, if YES then update.
    $data = $db->fetch_row("SELECT COUNT(*) FROM ".$pre."misc WHERE type='".$type."' AND intitule = '".$setting."'");
    if ( $data[0] == 0 ){
        $db->query_insert(
            "misc",
            array(
                'valeur' => $val,
                'type' => $type,
                'intitule' => $setting
            )
        );
        //in case of stats enabled, add the actual time
        if ( $setting == 'send_stats' )
            $db->query_insert(
                "misc",
                array(
                    'valeur' => time(),
                    'type' => $type,
                    'intitule' => $setting.'_time'
                )
            );
    }else{
        $db->query_update(
            "misc",
            array(
                'valeur' => $val
            ),
            "type='".$type."' AND intitule = '".$setting."'"
        );
        //in case of stats enabled, update the actual time
    	if ($setting == 'send_stats'){
    		//Check if previous time exists, if not them insert this value in DB
    		$data_time = $db->fetch_row("SELECT COUNT(*) FROM ".$pre."misc WHERE type='".$type."' AND intitule = '".$setting."_time'");
    		if ( $data_time[0] == 0 ){
    			$db->query_insert(
	    			"misc",
	    			array(
	    			    'valeur' => 0,
	    			    'type' => $type,
	    			    'intitule' => $setting.'_time'
	    			)
    			);
    		}else {
    			$db->query_update(
	    			"misc",
	    			array(
	    			    'valeur' => 0
	    			),
	    			"type='".$type."' AND intitule = '".$setting."_time'"
    			);
    		}
    	}

    }
    //save in variable
    if ( $type == "admin" ) $_SESSION['settings'][$setting] = $val;
    else if ( $type == "settings" ) $settings[$setting] = $val;
}

//SAVE CHANGES
if (isset($_POST['save_button'])) {
    //Update last seen items
    if ( isset($_SESSION['settings']['max_latest_items']) && $_SESSION['settings']['max_latest_items'] != $_POST['max_last_items'] ){
        UpdateSettings('max_latest_items',$_POST['max_last_items']);
    }

    //Update favourites
    if ( isset($_SESSION['settings']['enable_favourites']) && $_SESSION['settings']['enable_favourites'] != $_POST['enable_favourites'] ){
        UpdateSettings('enable_favourites',$_POST['enable_favourites']);
    }

    //Update last shown items
    if ( isset($_SESSION['settings']['show_last_items']) && $_SESSION['settings']['show_last_items'] != $_POST['show_last_items'] ){
        UpdateSettings('show_last_items',$_POST['show_last_items']);
    }

    //Update personal feature
    if ( isset($_SESSION['settings']['enable_pf_feature']) && $_SESSION['settings']['enable_pf_feature'] != $_POST['enable_pf_feature'] ){
        UpdateSettings('enable_pf_feature',$_POST['enable_pf_feature']);
    }

    //Update loggin connections setting
    if ( isset($_SESSION['settings']['log_connections']) && $_SESSION['settings']['log_connections'] != $_POST['log_connections'] ){
        UpdateSettings('log_connections',$_POST['log_connections']);
    }

    //Update date format setting
    if ( isset($_SESSION['settings']['date_format']) && $_SESSION['settings']['date_format'] != $_POST['date_format'] ){
        UpdateSettings('date_format',$_POST['date_format']);
    }

    //Update time format setting
    if ( isset($_SESSION['settings']['time_format']) && $_SESSION['settings']['time_format'] != $_POST['time_format'] ){
        UpdateSettings('time_format',$_POST['time_format']);
    }

    //Update duplicate folder setting
    if ( isset($_SESSION['settings']['duplicate_folder']) && $_SESSION['settings']['duplicate_folder'] != $_POST['duplicate_folder'] ){
        UpdateSettings('duplicate_folder',$_POST['duplicate_folder']);
    }

    //Update duplicate item setting
    if ( isset($_SESSION['settings']['duplicate_item']) && $_SESSION['settings']['duplicate_item'] != $_POST['duplicate_item'] ){
        UpdateSettings('duplicate_item',$_POST['duplicate_item']);
    }

    //Update number_of_used_pw setting
    if ( isset($_SESSION['settings']['number_of_used_pw']) && $_SESSION['settings']['number_of_used_pw'] != $_POST['number_of_used_pw'] ){
        UpdateSettings('number_of_used_pw',$_POST['number_of_used_pw']);
    }

    //Update duplicate Manager edit
    if ( isset($_SESSION['settings']['manager_edit']) && $_SESSION['settings']['manager_edit'] != $_POST['manager_edit'] ){
        UpdateSettings('manager_edit',$_POST['manager_edit']);
    }

    //Update cpassman_dir
    if ( isset($_SESSION['settings']['cpassman_dir']) && $_SESSION['settings']['cpassman_dir'] != $_POST['cpassman_dir'] ){
        UpdateSettings('cpassman_dir',$_POST['cpassman_dir']);
    }

    //Update cpassman_url
    if ( isset($_SESSION['settings']['cpassman_url']) && $_SESSION['settings']['cpassman_url'] != $_POST['cpassman_url'] ){
        UpdateSettings('cpassman_url',$_POST['cpassman_url']);
    }

    //Update pw_life_duration
    if ( isset($_SESSION['settings']['pw_life_duration']) && $_SESSION['settings']['pw_life_duration'] != $_POST['pw_life_duration'] ){
        UpdateSettings('pw_life_duration',$_POST['pw_life_duration']);
    }

    //Update favicon
    if ( isset($_SESSION['settings']['favicon']) && $_SESSION['settings']['favicon'] != $_POST['favicon'] ){
        UpdateSettings('favicon',$_POST['favicon']);
    }

    //Update activate_expiration setting
    if ( isset($_SESSION['settings']['activate_expiration']) && $_SESSION['settings']['activate_expiration'] != $_POST['activate_expiration'] ){
        UpdateSettings('activate_expiration',$_POST['activate_expiration']);
    }

    //Update maintenance mode
    if ( @$_SESSION['settings']['maintenance_mode'] != $_POST['maintenance_mode'] ){
        UpdateSettings('maintenance_mode',$_POST['maintenance_mode']);
    }

    //Update richtext
    if ( @$_SESSION['settings']['richtext'] != $_POST['richtext'] ){
        UpdateSettings('richtext',$_POST['richtext']);
    }

    //Update send_stats
    if ( @$_SESSION['settings']['send_stats'] != $_POST['send_stats'] ){
        UpdateSettings('send_stats',$_POST['send_stats']);
    }

	//Update allow_print
	if ( @$_SESSION['settings']['allow_print'] != $_POST['allow_print'] ){
		UpdateSettings('allow_print',$_POST['allow_print']);
	}

	//Update LDAP mode
	if ( @$_SESSION['settings']['ldap_mode'] != $_POST['ldap_mode'] ){
		UpdateSettings('ldap_mode',$_POST['ldap_mode']);
	}

	//Update LDAP ldap_suffix
	if ( @$_SESSION['settings']['ldap_suffix'] != $_POST['ldap_suffix'] ){
		UpdateSettings('ldap_suffix',$_POST['ldap_suffix']);
	}

	//Update LDAP ldap_domain_dn
	if ( @$_SESSION['settings']['ldap_domain_dn'] != $_POST['ldap_domain_dn'] ){
		UpdateSettings('ldap_domain_dn',$_POST['ldap_domain_dn']);
	}

	//Update LDAP ldap_domain_controler
	if ( @$_SESSION['settings']['ldap_domain_controler'] != $_POST['ldap_domain_controler'] ){
		UpdateSettings('ldap_domain_controler',$_POST['ldap_domain_controler']);
	}

	//Update LDAP ssl
	if ( @$_SESSION['settings']['ldap_ssl'] != $_POST['ldap_ssl'] ){
		UpdateSettings('ldap_ssl',$_POST['ldap_ssl']);
	}

	//Update LDAP tls
	if ( @$_SESSION['settings']['ldap_tls'] != $_POST['ldap_tls'] ){
		UpdateSettings('ldap_tls',$_POST['ldap_tls']);
	}

	//Update anyone_can_modify
	if ( @$_SESSION['settings']['anyone_can_modify'] != $_POST['anyone_can_modify'] ){
		UpdateSettings('anyone_can_modify',$_POST['anyone_can_modify']);
	}

	//Update enable_kb
	if ( @$_SESSION['settings']['enable_kb'] != $_POST['enable_kb'] ){
		UpdateSettings('enable_kb',$_POST['enable_kb']);
	}

    //Update nb_bad_identification
    if ( @$_SESSION['settings']['nb_bad_authentication'] != $_POST['nb_bad_authentication'] ){
        UpdateSettings('nb_bad_authentication',$_POST['nb_bad_authentication']);
    }

	//Update restricted_to_roles
	if ( @$_SESSION['settings']['restricted_to_roles'] != $_POST['restricted_to_roles'] ){
		UpdateSettings('restricted_to_roles',$_POST['restricted_to_roles']);
	}
}

echo '
<div style="margin-top:10px;">
    <form name="form_settings" method="post" action="">';
        // Main div for TABS
        echo '
        <div style="width:900px;margin:auto; line-height:20px; padding:10px;" id="tabs">';
            // Tabs menu
            echo '
            <ul>
                <li><a href="#tabs-1">'.$txt['admin_settings_title'].'</a></li>
                <li><a href="#tabs-3">'.$txt['admin_misc_title'].'</a></li>
                <li><a href="#tabs-2">'.$txt['admin_actions_title'].'</a></li>
				<li><a href="#tabs-4">'.$txt['admin_ldap_menu'].'</a></li>
            </ul>';
            // --------------------------------------------------------------------------------
            // TAB N°1
            echo '
            <div id="tabs-1">
				<table border="0">';
                //cpassman_dir
                echo '
                <tr style="margin-bottom:3px">
                    <td>
                    	<span class="ui-icon ui-icon-disk" style="float: left; margin-right: .3em;">&nbsp;</span>
                    	<label for="cpassman_dir">'.$txt['admin_misc_cpassman_dir'].'</label>
					</td>
					<td>
                    	<input type="text" size="80" id="cpassman_dir" name="cpassman_dir" value="', isset($_SESSION['settings']['cpassman_dir']) ? $_SESSION['settings']['cpassman_dir'] : '', '" class="text ui-widget-content ui-corner-all" />
					<td>
                </tr>';

                //cpassman_url
				echo '
				<tr style="margin-bottom:3px">
				    <td>
				    	<span class="ui-icon ui-icon-disk" style="float: left; margin-right: .3em;">&nbsp;</span>
                    	<label for="cpassman_url">'.$txt['admin_misc_cpassman_url'].'</label>
					</td>
					<td>
                    	<input type="text" size="80" id="cpassman_url" name="cpassman_url" value="', isset($_SESSION['settings']['cpassman_url']) ? $_SESSION['settings']['cpassman_url'] : '', '" class="text ui-widget-content ui-corner-all" />
                	<td>
                </tr>';

                //Favicon
                echo '
                <tr style="margin-bottom:3px">
				    <td>
	                    <span class="ui-icon ui-icon-disk" style="float: left; margin-right: .3em;">&nbsp;</span>
	                    <label for="favicon">'.$txt['admin_misc_favicon'].'</label>
					</td>
					<td>
                    	<input type="text" size="80" id="favicon" name="favicon" value="', isset($_SESSION['settings']['favicon']) ? $_SESSION['settings']['favicon'] : '', '" class="text ui-widget-content ui-corner-all" />
					<td>
                </tr>
            </table>';

                //DATE format
                echo '
			<table>
                <tr style="margin-bottom:3px">
				    <td>
	                    <span class="ui-icon ui-icon-disk" style="float: left; margin-right: .3em;">&nbsp;</span>
	                    <label for="date_format">'.$txt['date_format'].'</label>
					</td>
					<td>
                    	<input type="text" size="10" id="date_format" name="date_format" value="', isset($_SESSION['settings']['date_format']) ? $_SESSION['settings']['date_format'] : 'd/m/Y', '" class="text ui-widget-content ui-corner-all" />
                	<td>
                </tr>';

                //TIME format
                echo '
                <tr style="margin-bottom:3px">
				    <td>
	                    <span class="ui-icon ui-icon-disk" style="float: left; margin-right: .3em;">&nbsp;</span>
	                    <label for="time_format">'.$txt['time_format'].'</label>
					</td>
					<td>
                    	<input type="text" size="10" id="time_format" name="time_format" value="', isset($_SESSION['settings']['time_format']) ? $_SESSION['settings']['time_format'] : 'H:i:s', '" class="text ui-widget-content ui-corner-all" />
					<td>
                </tr>';

                //Number of used pw
                echo '
                <tr style="margin-bottom:3px">
				    <td>
	                    <span class="ui-icon ui-icon-disk" style="float: left; margin-right: .3em;">&nbsp;</span>
	                    <label for="number_of_used_pw">'.$txt['number_of_used_pw'].'</label>
					</td>
					<td>
                    	<input type="text" size="10" id="number_of_used_pw" name="number_of_used_pw" value="', isset($_SESSION['settings']['number_of_used_pw']) ? $_SESSION['settings']['number_of_used_pw'] : '5', '" class="text ui-widget-content ui-corner-all" />
                	<td>
                </tr>';

                //Number days before changing pw
                echo '
                <tr style="margin-bottom:3px">
				    <td>
	                    <span class="ui-icon ui-icon-disk" style="float: left; margin-right: .3em;">&nbsp;</span>
	                    <label for="pw_life_duration">'.$txt['pw_life_duration'].'</label>
					</td>
					<td>
                    	<input type="text" size="10" id="pw_life_duration" name="pw_life_duration" value="', isset($_SESSION['settings']['pw_life_duration']) ? $_SESSION['settings']['pw_life_duration'] : '5', '" class="text ui-widget-content ui-corner-all" />
                	<td>
                </tr>';

                //Number of bad authentication tentations before disabling user
                echo '
                <tr style="margin-bottom:3px">
                    <td>
                        <span class="ui-icon ui-icon-disk" style="float: left; margin-right: .3em;">&nbsp;</span>
                        <label for="nb_bad_authentication">'.$txt['nb_false_login_attempts'].'</label>
                    </td>
                    <td>
                        <input type="text" size="10" id="nb_bad_authentication" name="nb_bad_authentication" value="', isset($_SESSION['settings']['nb_bad_authentication']) ? $_SESSION['settings']['nb_bad_authentication'] : '0', '" class="text ui-widget-content ui-corner-all" />
                    <td>
                </tr>';

                //Maintenance mode
                echo '
                <tr style="margin-bottom:3px">
				    <td>
	                    <span class="ui-icon ui-icon-disk" style="float: left; margin-right: .3em;">&nbsp;</span>
	                    <label>'.
	                        $txt['settings_maintenance_mode'].'
	                        &nbsp;<img src="includes/images/question-small-white.png" class="tip" alt="" title="'.$txt['settings_maintenance_mode_tip'].'" />
	                    </label>
					</td>
					<td>
						<div class="div_radio">
							<input type="radio" id="maintenance_mode_radio1" name="maintenance_mode" value="1"', isset($_SESSION['settings']['maintenance_mode']) && $_SESSION['settings']['maintenance_mode'] == 1 ? ' checked="checked"' : '', ' /><label for="maintenance_mode_radio1">'.$txt['yes'].'</label>
							<input type="radio" id="maintenance_mode_radio2" name="maintenance_mode" value="0"', isset($_SESSION['settings']['maintenance_mode']) && $_SESSION['settings']['maintenance_mode'] != 1 ? ' checked="checked"' : (!isset($_SESSION['settings']['maintenance_mode']) ? ' checked="checked"':''), ' /><label for="maintenance_mode_radio2">'.$txt['no'].'</label>
						</div>
	                <td>
                </tr>';

                //Enable send_stats
                echo '
                <tr style="margin-bottom:3px">
				    <td>
	                    <span class="ui-icon ui-icon-disk" style="float: left; margin-right: .3em;">&nbsp;</span>
	                    <label>'.
	                        $txt['settings_send_stats'].'
	                        &nbsp;<img src="includes/images/question-small-white.png" class="tip" alt="" title="'.$txt['settings_send_stats_tip'].'" />
	                    </label>
					</td>
					<td>
						<div class="div_radio">
							<input type="radio" id="send_stats_radio1" name="send_stats" value="1"', isset($_SESSION['settings']['send_stats']) && $_SESSION['settings']['send_stats'] == 1 ? ' checked="checked"' : '', ' /><label for="send_stats_radio1">'.$txt['yes'].'</label>
							<input type="radio" id="send_stats_radio2" name="send_stats" value="0"', isset($_SESSION['settings']['send_stats']) && $_SESSION['settings']['send_stats'] != 1 ? ' checked="checked"' : (!isset($_SESSION['settings']['send_stats']) ? ' checked="checked"':''), ' /><label for="send_stats_radio2">'.$txt['no'].'</label>
						</div>
	                <td>
                </tr>
                </table>
            </div>';
            // --------------------------------------------------------------------------------

            // --------------------------------------------------------------------------------
            // TAB N°2
            echo '
            <div id="tabs-2">';

                //Update Personal folders for users
                echo '
                <div style="margin-bottom:3px">
                    <span class="ui-icon ui-icon-gear" style="float: left; margin-right: .3em;">&nbsp;</span>
                    <a href="#" onclick="LaunchAdminActions(\'admin_action_check_pf\')" style="cursor:pointer;">'.$txt['admin_action_check_pf'].'</a>
                    <span id="result_admin_action_check_pf" style="margin-left:10px;display:none;"><img src="includes/images/tick.png" alt="" /></span>
                </div>';

                //Clean DB with orphan items
                echo '
                <div style="margin-bottom:3px">
                    <span class="ui-icon ui-icon-gear" style="float: left; margin-right: .3em;">&nbsp;</span>
                    <a href="#" onclick="LaunchAdminActions(\'admin_action_db_clean_items\')" style="cursor:pointer;">'.$txt['admin_action_db_clean_items'].'</a>
                    <span style="margin-left:0px;"><img src="includes/images/question-small-white.png" class="tip" alt="" title="'.$txt['admin_action_db_clean_items_tip'].'" /></span>
                    <span id="result_admin_action_db_clean_items" style="margin-left:10px;"></span>
                </div>';

                //Optimize the DB
                echo '
                <div style="margin-bottom:3px">
                    <span class="ui-icon ui-icon-gear" style="float: left; margin-right: .3em;">&nbsp;</span>
                    <a href="#" onclick="LaunchAdminActions(\'admin_action_db_optimize\')" style="cursor:pointer;">'.$txt['admin_action_db_optimize'].'</a>
                    <span id="result_admin_action_db_optimize" style="margin-left:10px;"></span>
                </div>';

                //Backup the DB
                echo '
                <div style="margin-bottom:3px">
                    <span class="ui-icon ui-icon-gear" style="float: left; margin-right: .3em;">&nbsp;</span>
                    <a href="#" onclick="javascript:$(\'#result_admin_action_db_backup_get_key\').toggle();" style="cursor:pointer;">'.$txt['admin_action_db_backup'].'</a>
                    <span style="margin-left:0px;"><img src="includes/images/question-small-white.png" class="tip" alt="" title="'.$txt['admin_action_db_backup_tip'].'" /></span>
                    <span id="result_admin_action_db_backup" style="margin-left:10px;"></span>
                    <span id="result_admin_action_db_backup_get_key" style="margin-left:10px;display:none;">
                        &nbsp;'.$txt['encrypt_key'].'<input type="text" size="20" id="result_admin_action_db_backup_key" />
                        <img src="includes/images/question-small-white.png" class="tip" alt="" title="'.$txt['admin_action_db_backup_key_tip'].'" />
                        <img src="includes/images/asterisk.png" class="tip" alt="" title="'.$txt['admin_action_db_backup_start_tip'].'" onclick="LaunchAdminActions(\'admin_action_db_backup\')" style="cursor:pointer;" />
                    </span>
                </div>';

                //Restore the DB
                echo '
                <div style="margin-bottom:3px">
                    <span class="ui-icon ui-icon-gear" style="float: left; margin-right: .3em;">&nbsp;</span>
                    <a href="#" onclick="javascript:$(\'#result_admin_action_db_restore_get_file\').toggle();" style="cursor:pointer;">'.$txt['admin_action_db_restore'].'</a>
                    <span style="margin-left:0px;"><img src="includes/images/question-small-white.png" class="tip" alt="" title="'.$txt['admin_action_db_restore_tip'].'" /></span>
                    <span id="result_admin_action_db_restore" style="margin-left:10px;"></span>
                    <span id="result_admin_action_db_restore_get_file" style="margin-left:10px;display:none;"><input id="fileInput_restore_sql" name="fileInput_restore_sql" type="file" /></span>
                </div>';

                //Purge old files
                echo '
                <div style="margin-bottom:3px">
                    <span class="ui-icon ui-icon-gear" style="float: left; margin-right: .3em;">&nbsp;</span>
                    <a href="#" onclick="LaunchAdminActions(\'admin_action_purge_old_files\')" style="cursor:pointer;">'.$txt['admin_action_purge_old_files'].'</a>
                    <span style="margin-left:0px;"><img src="includes/images/question-small-white.png" class="tip" alt="" title="'.$txt['admin_action_purge_old_files_tip'].'" /></span>
                    <span id="result_admin_action_purge_old_files" style="margin-left:10px;"></span>
                </div>';

            echo '
            </div>';
            // --------------------------------------------------------------------------------

            // --------------------------------------------------------------------------------
            // TAB N°3
            echo '
            <div id="tabs-3">
            	<table>';

                //Managers can edit & delete items they are allowed to see
                echo '
                <tr><td>
                    <span class="ui-icon ui-icon-wrench" style="float: left; margin-right: .3em;">&nbsp;</span>
                    <label>'.$txt['settings_manager_edit'].'</label>
					</td><td>
				    <div class="div_radio">
						<input type="radio" id="manager_edit_radio1" name="manager_edit" value="1"', isset($_SESSION['settings']['manager_edit']) && $_SESSION['settings']['manager_edit'] == 1 ? ' checked="checked"' : '', ' /><label for="manager_edit_radio1">'.$txt['yes'].'</label>
						<input type="radio" id="manager_edit_radio2" name="manager_edit" value="0"', isset($_SESSION['settings']['manager_edit']) && $_SESSION['settings']['manager_edit'] != 1 ? ' checked="checked"' : (!isset($_SESSION['settings']['manager_edit']) ? ' checked="checked"':''), ' /><label for="manager_edit_radio2">'.$txt['no'].'</label>
					</div>
                </td</tr>';

                //max items
                echo '
                <tr><td>
                    <span class="ui-icon ui-icon-wrench" style="float: left; margin-right: .3em;">&nbsp;</span>
                    <label for="max_last_items">'.$txt['max_last_items'].'</label>
					</td><td>
                    <input type="text" size="4" id="max_last_items" name="max_last_items" value="', isset($_SESSION['settings']['max_latest_items']) ? $_SESSION['settings']['max_latest_items'] : '', '" class="text ui-widget-content ui-corner-all" />
                <tr><td>';

                //Show last items
                echo '
                <tr><td>
                    <span class="ui-icon ui-icon-wrench" style="float: left; margin-right: .3em;">&nbsp;</span>
                    <label>'.$txt['show_last_items'].'</label>
				    </td><td>
				    <div class="div_radio">
						<input type="radio" id="show_last_items_radio1" name="show_last_items" value="1"', isset($_SESSION['settings']['show_last_items']) && $_SESSION['settings']['show_last_items'] == 1 ? ' checked="checked"' : '', ' /><label for="show_last_items_radio1">'.$txt['yes'].'</label>
						<input type="radio" id="show_last_items_radio2" name="show_last_items" value="0"', isset($_SESSION['settings']['show_last_items']) && $_SESSION['settings']['show_last_items'] != 1 ? ' checked="checked"' : (!isset($_SESSION['settings']['show_last_items']) ? ' checked="checked"':''), ' /><label for="show_last_items_radio2">'.$txt['no'].'</label>
					</div>
                </td</tr>';

                //Duplicate folder
                echo '
                <tr><td>
                    <span class="ui-icon ui-icon-wrench" style="float: left; margin-right: .3em;">&nbsp;</span>
                    <label>'.$txt['duplicate_folder'].'</label>
				    </td><td>
				    <div class="div_radio">
						<input type="radio" id="duplicate_folder_radio1" name="duplicate_folder" value="1"', isset($_SESSION['settings']['duplicate_folder']) && $_SESSION['settings']['duplicate_folder'] == 1 ? ' checked="checked"' : '', ' /><label for="duplicate_folder_radio1">'.$txt['yes'].'</label>
						<input type="radio" id="duplicate_folder_radio2" name="duplicate_folder" value="0"', isset($_SESSION['settings']['duplicate_folder']) && $_SESSION['settings']['duplicate_folder'] != 1 ? ' checked="checked"' : (!isset($_SESSION['settings']['duplicate_folder']) ? ' checked="checked"':''), ' /><label for="duplicate_folder_radio2">'.$txt['no'].'</label>
					</div>
                </td</tr>';

                //Duplicate item name
                echo '
                <tr><td>
                    <span class="ui-icon ui-icon-wrench" style="float: left; margin-right: .3em;">&nbsp;</span>
                    <label>'.$txt['duplicate_item'].'</label>
				    </td><td>
				    <div class="div_radio">
						<input type="radio" id="duplicate_item_radio1" name="duplicate_item" value="1"', isset($_SESSION['settings']['duplicate_item']) && $_SESSION['settings']['duplicate_item'] == 1 ? ' checked="checked"' : '', ' /><label for="duplicate_item_radio1">'.$txt['yes'].'</label>
						<input type="radio" id="duplicate_item_radio2" name="duplicate_item" value="0"', isset($_SESSION['settings']['duplicate_item']) && $_SESSION['settings']['duplicate_item'] != 1 ? ' checked="checked"' : (!isset($_SESSION['settings']['duplicate_item']) ? ' checked="checked"':''), ' /><label for="duplicate_item_radio2">'.$txt['no'].'</label>
					</div>
                </td</tr>';

                //enable FAVOURITES
                echo '
                <tr><td>
                    <span class="ui-icon ui-icon-wrench" style="float: left; margin-right: .3em;">&nbsp;</span>
                    <label>'.$txt['enable_favourites'].'</label>
				    </td><td>
				    <div class="div_radio">
						<input type="radio" id="enable_favourites_radio1" name="enable_favourites" value="1"', isset($_SESSION['settings']['enable_favourites']) && $_SESSION['settings']['enable_favourites'] == 1 ? ' checked="checked"' : '', ' /><label for="enable_favourites_radio1">'.$txt['yes'].'</label>
						<input type="radio" id="enable_favourites_radio2" name="enable_favourites" value="0"', isset($_SESSION['settings']['enable_favourites']) && $_SESSION['settings']['enable_favourites'] != 1 ? ' checked="checked"' : (!isset($_SESSION['settings']['enable_favourites']) ? ' checked="checked"':''), ' /><label for="enable_favourites_radio2">'.$txt['no'].'</label>
					</div>
                </td</tr>';

                //enable PF
                echo '
                <tr><td>
                    <span class="ui-icon ui-icon-wrench" style="float: left; margin-right: .3em;">&nbsp;</span>
                    <label>'.$txt['enable_personal_folder_feature'].'</label>
				    </td><td>
				    <div class="div_radio">
						<input type="radio" id="enable_pf_feature_radio1" name="enable_pf_feature" value="1"', isset($_SESSION['settings']['enable_pf_feature']) && $_SESSION['settings']['enable_pf_feature'] == 1 ? ' checked="checked"' : '', ' /><label for="enable_pf_feature_radio1">'.$txt['yes'].'</label>
						<input type="radio" id="enable_pf_feature_radio2" name="enable_pf_feature" value="0"', isset($_SESSION['settings']['enable_pf_feature']) && $_SESSION['settings']['enable_pf_feature'] != 1 ? ' checked="checked"' : (!isset($_SESSION['settings']['enable_pf_feature']) ? ' checked="checked"':''), ' /><label for="enable_pf_feature_radio2">'.$txt['no'].'</label>
					</div>
                </td</tr>';

                //Enable log connections
                echo '
                <tr><td>
                    <span class="ui-icon ui-icon-wrench" style="float: left; margin-right: .3em;">&nbsp;</span>
                    <label>'.$txt['settings_log_connections'].'</label>
				    </td><td>
				    <div class="div_radio">
						<input type="radio" id="log_connections_radio1" name="log_connections" value="1"', isset($_SESSION['settings']['log_connections']) && $_SESSION['settings']['log_connections'] == 1 ? ' checked="checked"' : '', ' /><label for="log_connections_radio1">'.$txt['yes'].'</label>
						<input type="radio" id="log_connections_radio2" name="log_connections" value="0"', isset($_SESSION['settings']['log_connections']) && $_SESSION['settings']['log_connections'] != 1 ? ' checked="checked"' : (!isset($_SESSION['settings']['log_connections']) ? ' checked="checked"':''), ' /><label for="log_connections_radio2">'.$txt['no'].'</label>
					</div>
                </td</tr>';

                //Enable activate_expiration
                echo '
                <tr><td>
                    <span class="ui-icon ui-icon-wrench" style="float: left; margin-right: .3em;">&nbsp;</span>
                    <label>
                        '.$txt['admin_setting_activate_expiration'].'
                        <span style="margin-left:0px;"><img src="includes/images/question-small-white.png" class="tip" alt="" title="'.$txt['admin_setting_activate_expiration_tip'].'" /></span>
                    </label>
				    </td><td>
				    <div class="div_radio">
						<input type="radio" id="activate_expiration_radio1" name="activate_expiration" value="1"', isset($_SESSION['settings']['activate_expiration']) && $_SESSION['settings']['activate_expiration'] == 1 ? ' checked="checked"' : '', ' /><label for="activate_expiration_radio1">'.$txt['yes'].'</label>
						<input type="radio" id="activate_expiration_radio2" name="activate_expiration" value="0"', isset($_SESSION['settings']['activate_expiration']) && $_SESSION['settings']['activate_expiration'] != 1 ? ' checked="checked"' : (!isset($_SESSION['settings']['activate_expiration']) ? ' checked="checked"':''), ' /><label for="activate_expiration_radio2">'.$txt['no'].'</label>
					</div>
                </td</tr>';

                //Enable richtext
                echo '
                <tr><td>
                    <span class="ui-icon ui-icon-wrench" style="float: left; margin-right: .3em;">&nbsp;</span>
                    <label>
                        '.$txt['settings_richtext'].'
                        <span style="margin-left:0px;"><img src="includes/images/question-small-white.png" class="tip" alt="" title="'.$txt['settings_richtext_tip'].'" /></span>
                    </label>
				    </td><td>
				    <div class="div_radio">
						<input type="radio" id="richtext_radio1" name="richtext" value="1"', isset($_SESSION['settings']['richtext']) && $_SESSION['settings']['richtext'] == 1 ? ' checked="checked"' : '', ' /><label for="richtext_radio1">'.$txt['yes'].'</label>
						<input type="radio" id="richtext_radio2" name="richtext" value="0"', isset($_SESSION['settings']['richtext']) && $_SESSION['settings']['richtext'] != 1 ? ' checked="checked"' : (!isset($_SESSION['settings']['richtext']) ? ' checked="checked"':''), ' /><label for="richtext_radio2">'.$txt['no'].'</label>
					</div>
                </td</tr>';

				//Enable Printing
				echo '
				<tr><td>
				    <span class="ui-icon ui-icon-wrench" style="float: left; margin-right: .3em;">&nbsp;</span>
				    <label>
				        '.$txt['settings_printing'].'
				        <span style="margin-left:0px;"><img src="includes/images/question-small-white.png" class="tip" alt="" title="'.$txt['settings_printing_tip'].'" /></span>
				    </label>
				    </td><td>
				    <div class="div_radio">
						<input type="radio" id="allow_print_radio1" name="allow_print" value="1"', isset($_SESSION['settings']['allow_print']) && $_SESSION['settings']['allow_print'] == 1 ? ' checked="checked"' : '', ' /><label for="allow_print_radio1">'.$txt['yes'].'</label>
						<input type="radio" id="allow_print_radio2" name="allow_print" value="0"', isset($_SESSION['settings']['allow_print']) && $_SESSION['settings']['allow_print'] != 1 ? ' checked="checked"' : (!isset($_SESSION['settings']['allow_print']) ? ' checked="checked"':''), ' /><label for="allow_print_radio2">'.$txt['no'].'</label>
					</div>
				</td></tr>';

				//Enable Item modification by anyone
				echo '
				<tr><td>
				    <span class="ui-icon ui-icon-wrench" style="float: left; margin-right: .3em;">&nbsp;</span>
				    <label>
				        '.$txt['settings_anyone_can_modify'].'
				        <span style="margin-left:0px;"><img src="includes/images/question-small-white.png" class="tip" alt="" title="'.$txt['settings_anyone_can_modify_tip'].'" /></span>
				    </label>
				    </td><td>
				    <div class="div_radio">
						<input type="radio" id="anyone_can_modify_radio1" name="anyone_can_modify" value="1"', isset($_SESSION['settings']['anyone_can_modify']) && $_SESSION['settings']['anyone_can_modify'] == 1 ? ' checked="checked"' : '', ' /><label for="anyone_can_modify_radio1">'.$txt['yes'].'</label>
						<input type="radio" id="anyone_can_modify_radio2" name="anyone_can_modify" value="0"', isset($_SESSION['settings']['anyone_can_modify']) && $_SESSION['settings']['anyone_can_modify'] != 1 ? ' checked="checked"' : (!isset($_SESSION['settings']['anyone_can_modify']) ? ' checked="checked"':''), ' /><label for="anyone_can_modify_radio2">'.$txt['no'].'</label>
					</div>
				</td></tr>';

				//Enable KB
				echo '
				<tr><td>
				    <span class="ui-icon ui-icon-wrench" style="float: left; margin-right: .3em;">&nbsp;</span>
				    <label>
				        '.$txt['settings_kb'].'
				        <span style="margin-left:0px;"><img src="includes/images/question-small-white.png" class="tip" alt="" title="'.$txt['settings_kb_tip'].'" /></span>
				    </label>
				    </td><td>
				    <div class="div_radio">
						<input type="radio" id="enable_kb_radio1" name="enable_kb" value="1"', isset($_SESSION['settings']['enable_kb']) && $_SESSION['settings']['enable_kb'] == 1 ? ' checked="checked"' : '', ' /><label for="enable_kb_radio1">'.$txt['yes'].'</label>
						<input type="radio" id="enable_kb_radio2" name="enable_kb" value="0"', isset($_SESSION['settings']['enable_kb']) && $_SESSION['settings']['enable_kb'] != 1 ? ' checked="checked"' : (!isset($_SESSION['settings']['enable_kb']) ? ' checked="checked"':''), ' /><label for="enable_kb_radio2">'.$txt['no'].'</label>
					</div>
				</td></tr>';


				//enable restricted_to_roles
				echo '
				<tr><td>
				    <span class="ui-icon ui-icon-wrench" style="float: left; margin-right: .3em;">&nbsp;</span>
				    <label>'.$txt['restricted_to_roles'].'</label>
				    </td><td>
				    <div class="div_radio">
						<input type="radio" id="restricted_to_roles_radio1" name="restricted_to_roles" value="1"', isset($_SESSION['settings']['restricted_to_roles']) && $_SESSION['settings']['restricted_to_roles'] == 1 ? ' checked="checked"' : '', ' /><label for="restricted_to_roles_radio1">'.$txt['yes'].'</label>
						<input type="radio" id="restricted_to_roles_radio2" name="restricted_to_roles" value="0"', isset($_SESSION['settings']['restricted_to_roles']) && $_SESSION['settings']['restricted_to_roles'] != 1 ? ' checked="checked"' : (!isset($_SESSION['settings']['restricted_to_roles']) ? ' checked="checked"':''), ' /><label for="restricted_to_roles_radio2">'.$txt['no'].'</label>
					</div>
				</td</tr>';

            echo '
			</table>
            </div>';
			// --------------------------------------------------------------------------------

			// --------------------------------------------------------------------------------
			// TAB N°4
			echo '
			<div id="tabs-4">';

			//Enable LDAP mode
			echo '
			<div style="margin-bottom:3px">
			    <label for="ldap_mode">'.
			        $txt['settings_ldap_mode'].'
			        &nbsp;<img src="includes/images/question-small-white.png" class="tip" alt="" title="'.$txt['settings_ldap_mode_tip'].'" />
                </label>
			    <span class="div_radio">
					<input type="radio" id="ldap_mode_radio1" name="ldap_mode" value="1"', isset($_SESSION['settings']['ldap_mode']) && $_SESSION['settings']['ldap_mode'] == 1 ? ' checked="checked"' : '', ' onclick="javascript:$(\'#div_ldap_configuration\').show();" /><label for="ldap_mode_radio1">'.$txt['yes'].'</label>
					<input type="radio" id="ldap_mode_radio2" name="ldap_mode" value="0"', isset($_SESSION['settings']['ldap_mode']) && $_SESSION['settings']['ldap_mode'] != 1 ? ' checked="checked"' : (!isset($_SESSION['settings']['ldap_mode']) ? ' checked="checked"':''), ' onclick="javascript:$(\'#div_ldap_configuration\').hide();" /><label for="ldap_mode_radio2">'.$txt['no'].'</label>
				</span>
            </div>';

			// AD inputs
			echo '
			<div id="div_ldap_configuration" ', (isset($_SESSION['settings']['ldap_mode']) && $_SESSION['settings']['ldap_mode'] == 1) ? '':' style="display:none;"' ,'>
				<div style="font-weight:bold;font-size:14px;margin:15px 0px 8px 0px;">'.$txt['admin_ldap_configuration'].'</div>
				<table>';
				// Domain
				echo '
					<tr>
						<td><label for="ldap_suffix">'.$txt['settings_ldap_domain'].'</label></td>
						<td><input type="text" size="50" id="ldap_suffix" name="ldap_suffix" class="text ui-widget-content ui-corner-all" value="', isset($_SESSION['settings']['ldap_suffix']) ? $_SESSION['settings']['ldap_suffix'] : '', '" /></td>
					</tr>';
				// Domain DN
				echo '
					<tr>
						<td><label for="ldap_domain_dn">'.$txt['settings_ldap_domain_dn'].'</label></td>
						<td><input type="text" size="50" id="ldap_domain_dn" name="ldap_domain_dn" class="text ui-widget-content ui-corner-all" value="', isset($_SESSION['settings']['ldap_domain_dn']) ? $_SESSION['settings']['ldap_domain_dn'] : '', '" /></td>
					</tr>';
				// Domain controler
				echo '
					<tr>
						<td><label for="ldap_domain_controler">'.$txt['settings_ldap_domain_controler'].'&nbsp;<img src="includes/images/question-small-white.png" class="tip" alt="" title="'.$txt['settings_ldap_domain_controler_tip'].'" /></label></td>
						<td><input type="text" size="50" id="ldap_domain_controler" name="ldap_domain_controler" class="text ui-widget-content ui-corner-all" value="', isset($_SESSION['settings']['ldap_domain_controler']) ? $_SESSION['settings']['ldap_domain_controler'] : '', '" /></td>
					</tr>';
				// AD SSL
				echo '
					<tr>
						<td><label>'.$txt['settings_ldap_ssl'].'</label></td>
						<td>
						    <div class="div_radio">
								<input type="radio" id="ldap_ssl_radio1" name="ldap_ssl" value="1"', isset($_SESSION['settings']['ldap_ssl']) && $_SESSION['settings']['ldap_ssl'] == 1 ? ' checked="checked"' : '', ' /><label for="ldap_ssl_radio1">'.$txt['yes'].'</label>
								<input type="radio" id="ldap_ssl_radio2" name="ldap_ssl" value="0"', isset($_SESSION['settings']['ldap_ssl']) && $_SESSION['settings']['ldap_ssl'] != 1 ? ' checked="checked"' : (!isset($_SESSION['settings']['ldap_ssl']) ? ' checked="checked"':''), ' /><label for="ldap_ssl_radio2">'.$txt['no'].'</label>
							</div>
			            </td>
					</tr>';
				// AD TLS
				echo '
					<tr>
						<td><label>'.$txt['settings_ldap_tls'].'</label></td>
						<td>
						    <div class="div_radio">
								<input type="radio" id="ldap_tls_radio1" name="ldap_tls" value="1"', isset($_SESSION['settings']['ldap_tls']) && $_SESSION['settings']['ldap_tls'] == 1 ? ' checked="checked"' : '', ' /><label for="ldap_tls_radio1">'.$txt['yes'].'</label>
								<input type="radio" id="ldap_tls_radio2" name="ldap_tls" value="0"', isset($_SESSION['settings']['ldap_ssl']) && $_SESSION['settings']['ldap_tls'] != 1 ? ' checked="checked"' : (!isset($_SESSION['settings']['ldap_tls']) ? ' checked="checked"':''), ' /><label for="ldap_tls_radio2">'.$txt['no'].'</label>
							</div>
			            </td>
					</tr>';
				echo '
	            </table>
	        </div>';

			echo '
			</div>';
			// --------------------------------------------------------------------------------


			//Save button
			echo '
			<div style="margin:auto;">
				<input type="submit" id="save_button" name="save_button" value="'.$txt['save_button'].'" />
			</div>';

        echo '
        </div>';

        echo '
    </form>
</div>';
?>