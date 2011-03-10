<?php
/**
 * @file 		load.php
 * @author		Nils Laumaillé
 * @version 	2.0
 * @copyright 	(c) 2009-2011 Nils Laumaillé
 * @licensing 	CC BY-ND (http://creativecommons.org/licenses/by-nd/3.0/legalcode)
 * @link		http://cpassman.org
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

if ($_SESSION['CPM'] != 1)
	die('Hacking attempt...');


//Common elements
$htmlHeaders = '
        <link rel="stylesheet" href="includes/css/passman.css" type="text/css" />
        <script type="text/javascript" src="includes/js/functions.js"></script>

        <script type="text/javascript" src="includes/jquery-ui/js/jquery-'.$k['jquery-version'].'.min.js"></script>
        <script type="text/javascript" src="includes/jquery-ui/js/jquery-ui-'.$k['jquery-ui-version'].'.custom.min.js"></script>
        <link rel="stylesheet" href="includes/jquery-ui/css/'.$k['jquery-ui-theme'].'/jquery-ui-'.$k['jquery-ui-version'].'.custom.css" type="text/css" />

        <script language="JavaScript" type="text/javascript" src="includes/js/jquery.tooltip.js"></script>
        <link rel="stylesheet" href="includes/css/jquery.tooltip.css" type="text/css" />

        <script language="JavaScript" type="text/javascript" src="includes/js/pwd_strength.js"></script>';




//For ITEMS page, load specific CSS files for treeview
if ( isset($_GET['page']) && $_GET['page'] == "items")
    $htmlHeaders .= '
		<link rel="stylesheet" type="text/css" href="includes/css/items.css" />
        <!--<link rel="stylesheet" type="text/css" href="includes/css/jquery.treeview.css" />
        <script type="text/javascript" src="includes/js/jquery.treeview.pack.js"></script>
        <script type="text/javascript" src="includes/js/jquery.cookie.pack.js"></script>-->
        <script type="text/javascript" src="includes/libraries/jstree/jquery.cookie.js"></script>
        <script type="text/javascript" src="includes/libraries/jstree/jquery.jstree.pack.js"></script>

        <script type="text/javascript" src="includes/libraries/zclip/jquery.zclip.min.js"></script>

        <link rel="stylesheet" type="text/css" href="includes/css/jquery.autocomplete.css" />
        <script type="text/javascript" src="includes/js/jquery.bgiframe.min.js"></script>
        <script type="text/javascript" src="includes/js/jquery.autocomplete.pack.js"></script>

        <link rel="stylesheet" type="text/css" href="includes/libraries/uploadify/uploadify.css" />
        <script type="text/javascript" src="includes/libraries/uploadify/jquery.uploadify.v2.1.4.min.js"></script>
        <script type="text/javascript" src="includes/libraries/uploadify/swfobject.js"></script>

		<script type="text/javascript" src="includes/libraries/ckeditor/ckeditor.js"></script>
		<script type="text/javascript" src="includes/libraries/ckeditor/dialog-patch.js"></script>
		<script type="text/javascript" src="includes/libraries/ckeditor/adapters/jquery.js"></script>

		<link rel="stylesheet" type="text/css" href="includes/libraries/multiselect/jquery.multiselect.css" />
        <script type="text/javascript" src="includes/libraries/multiselect/jquery.multiselect.min.js"></script>

        <script type="text/javascript" src="includes/libraries/crypt/aes.min.js"></script>';

else
if ( isset($_GET['page']) && $_GET['page'] == "manage_settings")
    $htmlHeaders .= '
        <link rel="stylesheet" type="text/css" href="includes/libraries/uploadify/uploadify.css" />
        <script type="text/javascript" src="includes/libraries/uploadify/jquery.uploadify.v2.1.4.min.js"></script>
        <script type="text/javascript" src="includes/libraries/uploadify/swfobject.js"></script>';

else
if ( isset($_GET['page']) && ( $_GET['page'] == "manage_users" ||$_GET['page'] == "manage_folders") )
    $htmlHeaders .= '
		<script type="text/javascript" src="includes/libraries/crypt/aes.min.js"></script>';

else
if ( isset($_GET['page']) && ($_GET['page'] == "find" || $_GET['page'] == "kb"))
	$htmlHeaders .= '
	    <link rel="stylesheet" type="text/css" href="includes/css/kb.css" />

	    <script type="text/javascript" src="includes/libraries/ckeditor/ckeditor.js"></script>
		<script type="text/javascript" src="includes/libraries/ckeditor/dialog-patch.js"></script>
		<script type="text/javascript" src="includes/libraries/ckeditor/adapters/jquery.js"></script>

        <link rel="stylesheet" type="text/css" href="includes/libraries/datatable/jquery.dataTablesUI.css" />
        <script type="text/javascript" src="includes/libraries/datatable/jquery.dataTables.min.js"></script>

        <link rel="stylesheet" type="text/css" href="includes/libraries/ui-multiselect/css/ui.multiselect.css" />
        <script type="text/javascript" src="includes/libraries/ui-multiselect/js/ui.multiselect.min.js"></script>';

else
if ( !isset($_GET['page']) )
	$htmlHeaders .= '
        <link rel="stylesheet" type="text/css" href="includes/libraries/uploadify/uploadify.css" />
        <script type="text/javascript" src="includes/libraries/uploadify/jquery.uploadify.v2.1.4.min.js"></script>
        <script type="text/javascript" src="includes/libraries/uploadify/swfobject.js"></script>';


//Get Favicon
$htmlHeaders .= isset($_SESSION['settings']['favicon']) ? '
        <link rel="icon" href="'. $_SESSION['settings']['favicon'] . '" type="image/vnd.microsoft.ico" />' : '';

$htmlHeaders .= '
<script type="text/javascript">
<!-- // --><![CDATA[
    //deconnexion
    function MenuAction(val){
        if ( val == "deconnexion" ) {
            $("#menu_action").val(val);
            document.main_form.submit();
        }
        else {
        	$("#menu_action").val("action");
            if ( val == "") document.location.href="index.php";
            else document.location.href="index.php?page="+val;
        }
    }

    //Identify user
    function identifyUser(redirect){
        $("#erreur_connexion").hide();
        if ( redirect == undefined ) redirect = ""; //Check if redirection
        if ( document.getElementById("login").value != "" && document.getElementById("pw").value != "" ){
            $("#pw").removeClass( "ui-state-error" );
            $("#ajax_loader_connexion").show();

            //create random string
            var randomstring = "";
            var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz".split("");
            for (var i = 0; i < 10; i++) {
                randomstring += chars[Math.floor(Math.random() * chars.length)];
            }

            //send query
            $.post("sources/main.queries.php", {
                    type :          "identify_user",
                    login :         escape($("#login").val()),
                    pw :            encodeURIComponent($("#pw").val()),
                    duree_session : $("#duree_session").val(),
                    hauteur_ecran : window.innerHeight,
                    randomstring :  randomstring
                },
                function(data){
                    if (data == randomstring){
                        $("#ajax_loader_connexion").hide();
                        $("#erreur_connexion").hide();
                        window.location.href="index.php";
                    }else if (data == "user_is_locked"){
                        $("#ajax_loader_connexion").hide();
                        $("#erreur_connexion").html("'.$txt['account_is_locked'].'");
                        $("#erreur_connexion").show();
                    }else if (!isNaN(parseFloat(data)) && isFinite(data)){
                        $("#ajax_loader_connexion").hide();
                        $("#erreur_connexion").html(data + "'.$txt['login_attempts_on'] . (@$_SESSION['settings']['nb_bad_authentication']+1) .'");
                        $("#erreur_connexion").show();
                    }else{
                        $("#erreur_connexion").show();
                        $("#ajax_loader_connexion").hide();
                    }
                }
            );
        }else{
            $("#pw").addClass( "ui-state-error" );
        }
    }

	/*
	* Manage generation of new password
	*/
    function GenerateNewPassword(key, login){
    	$("#ajax_loader_send_mail").show();
		//send query
		$.post("sources/main.queries.php", {
				type :	"generate_new_password",
				login:	login,
				key :	key
			},
			function(data){
				if (data == "done"){
					window.location.href="index.php";
				}else{
					$("#generate_new_pw_error").show().html(data);
				}
				$("#ajax_loader_send_mail").hide();
			}
		);
	}

    function OpenDiv(div){
        $("#"+div).slideToggle("slow");
    }

    function OpenDialogBox(id){
        $("#"+id).dialog("open");
    }

    /*
    * Clean disconnection of user for security reasons.
    *
   	$(window).bind("beforeunload", function(){
		if ( $("#menu_action").val() == ""){
			//Forces the disconnection of the user
			$.ajax({
				type: "POST",
				url : "error.php",
				data : "session=expired"
            });
		}
	});*/

    $(function() {
        //TOOLTIPS
        $("#main *, #footer *, #icon_last_items *, #top *, button, .tip").tooltip({
            delay: 0,
            showURL: false
        });

        //Display Tabs
        $("#item_edit_tabs, #item_tabs").tabs();

        //BUTTON
        $("#but_identify_user").hover(
            function(){
                $(this).addClass("ui-state-hover");
            },
            function(){
                $(this).removeClass("ui-state-hover");
            }
        ).mousedown(function(){
            $(this).addClass("ui-state-active");
        })
        .mouseup(function(){
                $(this).removeClass("ui-state-active");
        });

        //END SESSION DIALOG BOX
        $("#div_fin_session").dialog({
            bgiframe: true,
            modal: true,
            autoOpen: false,
            width: 400,
            height: 150,
            title: "'.$txt['index_alarm'].'",
            buttons: {
                "'.$txt['index_add_one_hour'].'": function() {
                    AugmenterSession();
                    document.getElementById("div_fin_session").style.display="none";
                    document.getElementById("countdown").style.color="white";
                    $(this).dialog("close");
                }
            }
        });

        //WARNING FOR QUERY ERROR
        $("#div_mysql_error").dialog({
            bgiframe: true,
            modal: true,
            autoOpen: false,
            width: 700,
            height: 150,
            title: "'.$txt['error_mysql'].'",
            buttons: {
                "'.$txt['ok'].'": function() {
                    $(this).dialog("close");
                }
            }
        });

        //MESSAGE DIALOG
        $("#div_dialog_message").dialog({
            bgiframe: true,
            modal: true,
            autoOpen: false,
            width: 300,
            height: 150,
            title: "'.$txt['div_dialog_message_title'].'",
            buttons: {
                "'.$txt['ok'].'": function() {
                    $(this).dialog("close");
                }
            }
        });

        //PREPARE MAIN MENU
        $("#main_menu button, #personal_menu_actions button").button();

        //PREPARE LANGUGAGE DROPDOWN
            $(".dropdown dt").click(function() {
                $(".dropdown dd ul").toggle();
            });

            $(".dropdown dd ul li a").click(function() {
                var text = $(this).html();
                $(".dropdown dt a span").html(text);
                $(".dropdown dd ul").hide();
                $("#result").html("Selected value is: " + getSelectedValue("sample"));
            });

            function getSelectedValue(id) {
                return $("#" + id).find("dt a span.value").html();
            }

            $(document).bind("click", function(e) {
                var $clicked = $(e.target);
                if (! $clicked.parents().hasClass("dropdown"))
                    $(".dropdown dd ul").hide();
            });
        //END
    });


	';

if ( !isset($_GET['page']) ){
    $htmlHeaders .= '
    $(function() {
        //build nice buttonset
        $("#radio_import_type, #connect_ldap_mode").buttonset();
        $("#personal_sk").button();

        //Clear text when clicking on buttonset
        $(".import_radio").click(function() {
            $("#import_status").html("");
        });

        // DIALOG BOX FOR CHANGING PASSWORD
        $("#div_changer_mdp").dialog({
            bgiframe: true,
            modal: true,
            autoOpen: false,
            width: 300,
            height: 190,
            title: "'.$txt['index_change_pw'].'",
            buttons: {
                "'.$txt['index_change_pw_button'].'": function() {
                    ChangerMdp("'. (isset($_SESSION['last_pw']) ? $_SESSION['last_pw'] : ''). '");
                },
                "'.$txt['cancel_button'].'": function() {
                    $(this).dialog("close");
                }
            }
        });

        // DIALOG BOX FOR ASKING PASSWORD
        $("#div_forgot_pw").dialog({
            bgiframe: true,
            modal: true,
            autoOpen: false,
            width: 300,
            height: 250,
            title: "'.$txt['forgot_my_pw'].'",
            buttons: {
                "'.$txt['send'].'": function() {
					$("#div_forgot_pw_alert").html("");
                    var data = "type=send_pw_by_email&email="+$("#forgot_pw_email").val()+"&login="+$("#forgot_pw_login").val();
                    httpRequest("sources/main.queries.php",data);
                },
                "'.$txt['cancel_button'].'": function() {
					$("#div_forgot_pw_alert").html("");
                    $("#forgot_pw_email").val("");
                    $(this).dialog("close");
                }
            }
        });

        // DIALOG BOX FOR CSV IMPORT
        $("#div_import_from_csv").dialog({
            bgiframe: true,
            modal: true,
            autoOpen: false,
            width: 600,
            height: 500,
            title: "'.$txt['import_csv_menu_title'].'",
            buttons: {
                "'.$txt['import_button'].'": function() {
                    if ( $(\'#radio1\').attr(\'checked\') ) ImportItemsFromCSV();
                    else $(this).dialog("close");
                },
                "'.$txt['cancel_button'].'": function() {
                    $("#import_status").html("");
                    $(this).dialog("close");
                }
            }
        });

        //CALL TO UPLOADIFY FOR CSV IMPORT
        $("#fileInput_csv").uploadify({
            "uploader"  : "includes/libraries/uploadify/uploadify.swf",
            "scriptData": {"type_upload":"import_items_from_file"},
            "script"    : "includes/libraries/uploadify/uploadify.php",
            "cancelImg" : "includes/libraries/uploadify/cancel.png",
            "auto"      : true,
            "folder"    : "files",
            "fileDesc"  : "csv",
            "fileExt"   : "*.csv",
            "onComplete": function(event, queueID, fileObj, reponse, data){$("#import_status_ajax_loader").show();ImportCSV(fileObj.name);},
            "buttonText": \''.$txt['csv_import_button_text'].'\'
        });

        //CALL TO UPLOADIFY FOR KEEPASS IMPORT
        $("#fileInput_keepass").uploadify({
            "uploader"  : "includes/libraries/uploadify/uploadify.swf",
            "scriptData": {"type_upload":"import_items_from_file"},
            "script"    : "includes/libraries/uploadify/uploadify.php",
            "cancelImg" : "includes/libraries/uploadify/cancel.png",
            "auto"      : true,
            "folder"    : "files",
            "fileDesc"  : "xml",
            "fileExt"   : "*.xml",
            "onComplete": function(event, queueID, fileObj, reponse, data){$("#import_status_ajax_loader").show();ImportKEEPASS(fileObj.name);},
            "buttonText": \''.$txt['keepass_import_button_text'].'\'
        });

        // DIALOG BOX FOR PRINT OUT ITEMS
        $("#div_print_out").dialog({
            bgiframe: true,
            modal: true,
            autoOpen: false,
            width: 400,
            height: 400,
            title: "'.$txt['print_out_menu_title'].'",
            buttons: {
                "'.$txt['print'].'": function() {
					//Get list of selected folders
					var ids = "";
					$("#selected_folders :selected").each(function(i, selected){
						if (ids == "" ) ids = $(selected).val();
						else ids = ids + ";" + $(selected).val();
					});

                	//Send query
                    var data = "type=print_out_items&ids="+ids;
                    httpRequest("sources/main.queries.php",data);
                },
                "'.$txt['cancel_button'].'": function() {
                    $(this).dialog("close");
                }
            }
        });
    })

    //Change the Users password when he asks for
    function ChangerMdp(old_pw){
        if ( document.getElementById("new_pw").value != "" && document.getElementById("new_pw").value == document.getElementById("new_pw2").value ){
            var data = "type=change_pw&new_pw="+encodeURIComponent(document.getElementById("new_pw").value)+"&old_pw="+old_pw;
            httpRequest("sources/main.queries.php",data);
        }else{
            $("#change_pwd_error").addClass("ui-state-error ui-corner-all");
            document.getElementById("change_pwd_error").innerHTML = "'.$txt['index_pw_error_identical'].'";
        }
    }

    //Permits to upload passwords from KEEPASS file
    function ImportKEEPASS(file){
        //check if file has good format
        var data = "type=import_file_format_keepass&file="+file+"&destination="+$("#import_keepass_items_to").val();
        httpRequest("sources/import.queries.php",data);
    }

    //Permits to upload passwords from CSV file
    function ImportCSV(file){
        //check if file has good format
        var data = "type=import_file_format_csv&file="+file;
        httpRequest("sources/import.queries.php",data);
    }

    //get list of items checked by user
    function ImportItemsFromCSV(){
        var items = "";

        //Get data checked
        $("input[class=item_checkbox]:checked").each(function() {
            var elem = $(this).attr("id").split("-");
            if ( items == "") items = $("#item_to_import_values-"+elem[1]).val();
            else items = items + "@_#sep#_@" + $("#item_to_import_values-"+elem[1]).val();

        });

        //Lauchn ajax query that will insert items into DB
        var data = "type=import_items"+
        	"&folder="+document.getElementById("import_items_to").value+
        	"&data="+escape(items)+
        	"&import_csv_anyone_can_modify="+$("#import_csv_anyone_can_modify").attr("checked")+
        	"&import_csv_anyone_can_modify_in_role="+$("#import_csv_anyone_can_modify_in_role").attr("checked");
        httpRequest("sources/import.queries.php",data);
    }

    //Toggle details importation
    function toggle_importing_details() {
        $("#div_importing_kp_details").toggle();
    }

    //PRINT OUT: select folders
    function print_out_items() {
    	//Lauchn ajax query that will build the select list
        var data = "type=get_folders_list&div_id=selected_folders";
        httpRequest("sources/main.queries.php",data);

    	//Open dialogbox
        $(\'#div_print_out\').dialog(\'open\');
    }

	//Store PSK
	function StorePersonalSK(){
		var data = "type=store_personal_saltkey&sk="+encodeURIComponent($("#input_personal_saltkey").val());
        httpRequest("sources/main.queries.php",data);
	}
	';
}

else
//JAVASCRIPT FOR FIND PAGE
if ( isset($_GET['page']) && $_GET['page'] == "find"){
    $htmlHeaders .= '
    $(function() {
        //Launch the datatables pluggin
        $("#t_items").dataTable({
            "aaSorting": [[ 1, "asc" ]],
            "sPaginationType": "full_numbers",
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "sources/find.queries.php",
            "bJQueryUI": true,
            "oLanguage": {
                "sUrl": "includes/language/datatables.'.$_SESSION['user_language'].'.txt"
            }
        });
    });';
}

else
//JAVASCRIPT FOR KB PAGE
if ( isset($_GET['page']) && $_GET['page'] == "kb"){
	$htmlHeaders .= '
	//Function opening
	function openKB(id){
		LoadingPage();  //show loading div
		var data = "type=open_kb&"+
		    "&id="+id;
		httpRequest("sources/kb.queries.php",data);
	}

	//Function deleting
	function deleteKB(id){
		$("#kb_id").val(id);
		$("#div_kb_delete").dialog("open");
	}

	$(function() {
		//buttons
		$("#button_new_kb").button();

	    //Launch the datatables pluggin
	    $("#t_kb").dataTable({
	        "aaSorting": [[ 1, "asc" ]],
	        "sPaginationType": "full_numbers",
	        "bProcessing": true,
	        "bServerSide": true,
	        "sAjaxSource": "sources/kb.queries.table.php",
	        "bJQueryUI": true,
	        "oLanguage": {
	            "sUrl": "includes/language/datatables.'.$_SESSION['user_language'].'.txt"
	        }
	    });

	    //Dialogbox for deleting KB
	    $("#div_kb_delete").dialog({
	    	bgiframe: true,
			modal: true,
			autoOpen: false,
			width: 300,
			height: 150,
			title: "'.$txt['item_menu_del_elem'].'",
			buttons: {
				"'.$txt['del_button'].'": function() {
					$.post(
						"sources/kb.queries.php",
						"type=delete_kb&"+
					    "&id="+$("#kb_id").val(),
					    function(data){
							$("#div_kb_delete").dialog("close");
							oTable = $("#t_kb").dataTable();
							oTable.fnDraw();
						}
					)
	            },
	            "'.$txt['cancel_button'].'": function() {
	                $(this).dialog("close");
	            }
			}
	    });

	    //Dialogbox for new KB
	    $("#kb_form").dialog({
			bgiframe: true,
			modal: true,
			autoOpen: false,
			width: 900,
			height: 600,
			title: "'.$txt['kb_form'].'",
			buttons: {
				"'.$txt['save_button'].'": function() {
					if($("#kb_label").val() == "") {
						$("#kb_label").addClass( "ui-state-error" );
					}else if($("#kb_category").val() == "") {
						$("#kb_category").addClass( "ui-state-error" );
					}else if($("#kb_description").val() == "") {
						$("#kb_description").addClass( "ui-state-error" );
					}else{
						LoadingPage();  //show loading div

                        //selected items associated to KB
                        var itemsvalues = [];
                        $("#kb_associated_to :selected").each(function(i, selected) {
                            itemsvalues[i] = $(selected).val();
                        });

						var data = "type=kb_in_db&"+
						    "&label="+encodeURIComponent($("#kb_label").val())+
						    "&category="+encodeURIComponent($("#kb_category").val())+
						    "&anyone_can_modify="+$("input[name=modify_kb]:checked").val()+
						    "&id="+$("#kb_id").val()+
                            "&kb_associated_to="+itemsvalues+
						    "&description="+escape(CKEDITOR.instances["kb_description"].getData());
						httpRequest("sources/kb.queries.php",data);
					}
				},
				"'.$txt['cancel_button'].'": function() {
					$(this).dialog("close");
				}
			},
			open:function(event, ui) {
				$("#kb_label, #kb_description, #kb_category").removeClass( "ui-state-error" );
				$("#kb_associated_to").multiselect();
				var instance = CKEDITOR.instances["kb_description"];
			    if(instance)
			    {
			    	CKEDITOR.replace("kb_description",{toolbar:"Full", height: 250,language: "'. $k['langs'][$_SESSION['user_language']].'"});
			    }else{
					$("#kb_description").ckeditor({toolbar:"Full", height: 250,language: "'. $k['langs'][$_SESSION['user_language']].'"});
			    }
			},
	        close: function(event,ui) {
	        	if(CKEDITOR.instances["kb_description"]){
	        		CKEDITOR.instances["kb_description"].destroy();
	        	}
	        	$("#kb_id,#kb_label, #kb_description, #kb_category, #full_list_items_associated").val("");
	        }
		});

		//category listing
		$( "#kb_category" ).autocomplete({
			source: "sources/kb.queries.categories.php",
			minLength: 1
		}).focus(function(){
			if (this.value == "")
				$(this).trigger("keydown.autocomplete");
		});

		//BUILD BUTTONSET
        //$(".div_radio").buttonset();
	});';
}

else
//JAVASCRIPT FOR ADMIN PAGE
if ( isset($_GET['page']) && $_GET['page'] == "manage_main" ){
    $htmlHeaders .= '
            //Function loads informations from cpassman FTP
            function LoadCPMInfo(){
                var data = "type=cpm_status";
                httpRequest("sources/admin.queries.php",data);
            }
            //Load function on page load
            $(function() {
                LoadCPMInfo();
            });';

}

else
//JAVASCRIPT FOR FAVOURITES PAGE
if ( isset($_GET['page']) && $_GET['page'] == "favourites" ){
    $htmlHeaders .= '
    $(function() {
        // DIALOG BOX FOR DELETING FAVOURITE
        $("#div_delete_fav").dialog({
            bgiframe: true,
            modal: true,
            autoOpen: false,
            width: 300,
            height: 60,
            title: "'.$txt['item_menu_del_from_fav'].'",
            buttons: {
                "'.$txt['index_change_pw_confirmation'].'": function() {
                    var data = "type=del_fav"+
                                "&id="+document.getElementById(\'detele_fav_id\').value;
                    httpRequest("sources/favourites.queries.php",data);
                },
                "'.$txt['cancel_button'].'": function() {
                    $(this).dialog("close");
                }
            }
        });
    })

    function prepare_delete_fav(id){
        document.getElementById("detele_fav_id").value = id;
        OpenDialogBox(\'div_delete_fav\');
    }';
}

else
//JAVASCRIPT FOR ADMIN_SETTIGNS PAGE
if ( isset($_GET['page']) && $_GET['page'] == "manage_settings" ){
    $htmlHeaders .= '
    $(function() {
        //CALL TO UPLOADIFY FOR RESTORE SQL FILE
        $("#fileInput_restore_sql").uploadify({
            "uploader"  : "includes/libraries/uploadify/uploadify.swf",
            "script"    : "includes/libraries/uploadify/uploadify.php",
            "cancelImg" : "includes/libraries/uploadify/cancel.png",
            "auto"      : true,
            "folder"    : "files",
            "fileDesc"  : "sql",
            "fileExt"   : "*.sql",
            "height"   : "18px",
            "width"   : "18px",
            "wmode"     : "transparent",
            "buttonImg" : "includes/images/inbox--plus.png",
            "onComplete": function(event, queueID, fileObj, reponse, data){
                var key = prompt("'.$txt['admin_action_db_restore_key'].'","'.$txt['encrypt_key'].'");
                if ( key != "" ) LaunchAdminActions("admin_action_db_restore",fileObj.name+"&key="+key);
            }
        });

        // Build Tabs
        $("#tabs").tabs({
        	//MASK SAVE BUTTON IF tab 3 selected
        	select: function(event, ui) {
        		if (ui.index == 2) {
					$("#save_button").hide();
		        }else{
		        	$("#save_button").show();
        		}
        		return true;
			}
		});

        //BUILD BUTTONS
        $("#save_button").button();

        //BUILD BUTTONSET
        $(".div_radio").buttonset();
    });

    //###########
    //## FUNCTION : Launch the action the admin wants
    //###########
    function LaunchAdminActions(action,option){
        LoadingPage();
        if ( action == "admin_action_db_backup" ) option = $("#result_admin_action_db_backup_key").val();
        var data = "type="+action+"&option="+option;
        httpRequest("sources/admin.queries.php",data);
    }
    ';
}

else
//JAVASCRIPT FOR MANAGE ROLES PAGE
if ( isset($_GET['page']) && $_GET['page'] == "manage_roles" ){
    $htmlHeaders .= '
    //###########
    //## FUNCTION : Change the actual right of the role other the select folder
    //###########
    function tm_change_role(role,folder,cell_id,allowed){
        $("#div_loading").show()
        var data = "type=change_role_via_tm&role="+role+"&folder="+folder+"&cell_id="+cell_id+"&allowed="+allowed;
        httpRequest("sources/roles.queries.php",data);
    }

    function delete_this_role(id,name){
        document.getElementById("delete_role_id").value = id;
        document.getElementById("delete_role_show").innerHTML = name;
        $("#delete_role").dialog("open");
    }

    function edit_this_role(id,name){
        document.getElementById("edit_role_id").value = id;
        document.getElementById("edit_role_show").innerHTML = name;
        $("#edit_role").dialog("open");
    }

    function refresh_matrice(){
        $("#div_loading").show();
        var data = "type=rafraichir_matrice";
        httpRequest("sources/roles.queries.php",data);
    }

    function allow_pw_change_for_role(id, value){
    	$("#div_loading").show();
    	//Send query
		$.post("sources/roles.queries.php",
		    {
		        type    : "allow_pw_change_for_role",
		        id      : id,
		        value  	: value
		    },
		    function(data){
		    	if (value == 0)
		    		$("#img_apcfr_"+id).attr("src","includes/images/ui-text-field-password-red.png");
		    	else
		    		$("#img_apcfr_"+id).attr("src","includes/images/ui-text-field-password-green.png");
		    	$("#div_loading").hide();
		    }
		);
    }

    $(function() {
        $("#add_new_role").dialog({
            bgiframe: true,
            modal: true,
            autoOpen: false,
            width: 400,
            height: 150,
            title: "'.$txt["give_function_title"].'",
            buttons: {
                "'.$txt["save_button"].'": function() {
                    LoadingPage();  //show loading div
                    var data = "type=add_new_role&"+
                        "&name="+document.getElementById("new_function").value;
                    httpRequest("sources/roles.queries.php",data);
                    $(this).dialog("close");
                },
                "'.$txt["cancel_button"].'": function() {
                    $(this).dialog("close");
                }
            }
        });

        $("#delete_role").dialog({
            bgiframe: true,
            modal: true,
            autoOpen: false,
            width: 400,
            height: 150,
            title: "'. $txt["admin_action"] .'",
            buttons: {
                "'.$txt["ok"].'": function() {
                    LoadingPage();  //show loading div
                    var data = "type=delete_role&id="+document.getElementById("delete_role_id").value;
                    httpRequest("sources/roles.queries.php",data);
                    $(this).dialog("close");
                },
                "'.$txt["cancel_button"].'": function() {
                    $(this).dialog("close");
                }
            }
        });

        $("#edit_role").dialog({
            bgiframe: true,
            modal: true,
            autoOpen: false,
            width: 400,
            height: 200,
            title: "'. $txt["admin_action"] .'",
            buttons: {
                "'.$txt["ok"].'": function() {
                    LoadingPage();  //show loading div
                    var data = "type=edit_role&id="+document.getElementById("edit_role_id").value+"&title="+escape(document.getElementById("edit_role_title").value);
                    httpRequest("sources/roles.queries.php",data);
                    $(this).dialog("close");
                },
                "'.$txt["cancel_button"].'": function() {
                    $(this).dialog("close");
                }
            }
        });

        $("#help_on_roles").dialog({
            bgiframe: false,
            modal: false,
            autoOpen: false,
            width: 850,
            height: 500,
            title: "'. $txt["admin_help"] .'",
            buttons: {
                "'.$txt["close"].'": function() {
                    $(this).dialog("close");
                }
            },
            open: function(){
                $("#accordion").accordion({ autoHeight: false, navigation: true, collapsible: true, active: false });
            }
        });

        refresh_matrice();
    });';
}

else
//JAVASCRIPT FOR MANAGE USERS PAGE
if ( isset($_GET['page']) && $_GET['page'] == "manage_users" ){
    $htmlHeaders .= '
    $(function() {
        //inline editing
        $(".editable_textarea").editable("sources/users.queries.php", {
              indicator : "<img src=\'includes/images/loading.gif\' />",
              type   : "textarea",
              select : true,
              submit : " <img src=\'includes/images/disk_black.png\' />",
              cancel : " <img src=\'includes/images/cross.png\' />",
              name : "newlogin"
        });

        $("#change_user_functions").dialog({
            bgiframe: true,
            modal: true,
            autoOpen: false,
            width: 400,
            height: 400,
            title: "'.$txt['change_user_functions_title'].'",
            buttons: {
                "'. $txt['save_button'].'": function() {
                    Change_user_rights(document.getElementById("selected_user").value,"functions");
                    $(this).dialog("close");
                },
                "'. $txt['cancel_button'].'": function() {
                    $(this).dialog("close");
                }
            }
        });

        $("#change_user_autgroups").dialog({
            bgiframe: true,
            modal: true,
            autoOpen: false,
            width: 400,
            height: 400,
            title: "'. $txt['change_user_autgroups_title'].'",
            buttons: {
                "'. $txt['save_button'].'": function() {
                    Change_user_rights(document.getElementById("selected_user").value,"autgroups");
                    $(this).dialog("close");
                },
                "'. $txt['cancel_button'].'": function() {
                    $(this).dialog("close");
                }
            }
        });

        $("#change_user_forgroups").dialog({
            bgiframe: true,
            modal: true,
            autoOpen: false,
            width: 400,
            height: 400,
            title: "'. $txt['change_user_forgroups_title'].'",
            buttons: {
                "'. $txt['save_button'].'": function() {
                    Change_user_rights(document.getElementById("selected_user").value,"forgroups");
                    $(this).dialog("close");
                },
                "'.$txt['cancel_button'].'": function() {
                    $(this).dialog("close");
                }
            }
        });

        $("#add_new_user").dialog({
            bgiframe: true,
            modal: true,
            autoOpen: false,
            width: 320,
            height: 380,
            title: "'.$txt['new_user_title'].'",
            buttons: {
                "'.$txt['save_button'].'": function() {
					if ($("#new_login").val() == "" || $("#new_pwd").val()=="" || $("#new_email").val()==""){
						$("#add_new_user_error").show().html("'.$txt['error_must_enter_all_fields'].'");
					}else{
	                    LoadingPage();  //show loading div
	                    var data = "type=add_new_user&"+
	                        "&login="+escape($("#new_login").val())+
	                        "&pw="+encodeURIComponent($("#new_pwd").val())+
	                        "&email="+$("#new_email").val()+
	                        "&admin="+$("#new_admin").attr("checked")+
	                        "&manager="+$("#new_manager").attr("checked")+
	                        "&personal_folder="+$("#new_personal_folder").attr("checked")+
	                        "&new_folder_role_domain="+$("#new_folder_role_domain").attr("checked")+
	                        "&domain="+$("#new_domain").val();
	                    httpRequest("sources/users.queries.php",data);
	                    $("#add_new_user_error").hide();
	                    $(this).dialog("close");
	                }
                },
                "'.$txt['cancel_button'].'": function() {
                    $(this).dialog("close");
                }
            }
        });

        $("#delete_user").dialog({
            bgiframe: true,
            modal: true,
            autoOpen: false,
            width: 400,
            height: 150,
            title: "'.$txt['admin_action'].'",
            buttons: {
                "'.$txt['ok'].'": function() {
                    LoadingPage();  //show loading div
                    var data = "type=supprimer_user&id="+document.getElementById("delete_user_id").value;
                    httpRequest("sources/users.queries.php",data);
                    $(this).dialog("close");
                },
                "'.$txt['cancel_button'].'": function() {
                    $(this).dialog("close");
                }
            }
        });

        $("#change_user_pw").dialog({
            bgiframe: true,
            modal: true,
            autoOpen: false,
            width: 400,
            height: 200,
            title: "'.$txt['admin_action'].'",
            buttons: {
                "'.$txt['save_button'].'": function() {
                    if ( document.getElementById("change_user_pw_newpw").value == document.getElementById("change_user_pw_newpw_confirm").value ){
                        LoadingPage();  //show loading div
                        var data = "type=modif_mdp_user&"+
                        "id="+document.getElementById("change_user_pw_id").value+
                        "&newmdp="+encodeURIComponent(document.getElementById("change_user_pw_newpw").value);
                        httpRequest("sources/users.queries.php",data);
                        document.getElementById("change_user_pw_error").innerHTML = "";
                        $("#change_user_pw_error").hide();
                        document.getElementById("change_user_pw_newpw_confirm").value = "";
                        document.getElementById("change_user_pw_newpw").value = "";
                        $(this).dialog("close");
                    }else{
                        document.getElementById("change_user_pw_error").innerHTML = "'.$txt['error_password_confirmation'].'"
                        $("#change_user_pw_error").show();
                    }
                },
                "'.$txt['cancel_button'].'": function() {
                    $(this).dialog("close");
                }
            }
        });

        $("#change_user_email").dialog({
            bgiframe: true,
            modal: true,
            autoOpen: false,
            width: 400,
            height: 200,
            title: "'.$txt['admin_action'].'",
            buttons: {
                "'.$txt['save_button'].'": function() {
                    var data = "type=modif_mail_user"+
                    "&id="+document.getElementById("change_user_email_id").value+
                    "&newemail="+document.getElementById("change_user_email_newemail").value;
                    httpRequest("sources/users.queries.php",data);
                    $(this).dialog("close");
                },
                "'.$txt['cancel_button'].'": function() {
                    $(this).dialog("close");
                }
            }
        });

        $("#help_on_users").dialog({
            bgiframe: false,
            modal: false,
            autoOpen: false,
            width: 850,
            height: 500,
            title: "'. $txt["admin_help"] .'",
            buttons: {
                "'.$txt["close"].'": function() {
                    $(this).dialog("close");
                }
            },
            open: function(){
                $("#accordion").accordion({ autoHeight: false, navigation: true, collapsible: true, active: false });
            }
        });
    });

    function pwGenerate(elem){
        var data = "type=pw_generate"+
                    "&size="+(Math.floor((8-5)*Math.random()) + 6)+
                    "&num=true"+
                    "&maj=true"+
                    "&symb=false"+
                    "&fixed_elem=1"+
                    "&elem="+elem;
        httpRequest("sources/items.queries.php",data+"&force=false");
    }

    function supprimer_user(id,login){
        document.getElementById("delete_user_login").value = login;
        document.getElementById("delete_user_id").value = id;
        document.getElementById("delete_user_show_login").innerHTML = login;
        $("#delete_user").dialog("open");
    }

    function mdp_user(id,login){
        document.getElementById("change_user_pw_id").value = id;
        document.getElementById("change_user_pw_show_login").innerHTML = login;
        $("#change_user_pw").dialog("open");
    }

    function mail_user(id,login,email){
        document.getElementById("change_user_email_id").value = id;
        document.getElementById("change_user_email_show_login").innerHTML = login;
        document.getElementById("change_user_email_newemail").value = email;
        $("#change_user_email").dialog("open");
    }

    function ChangeUserParm(id, parameter) {
        if (parameter == "can_create_root_folder") {
            var val = $("#"+parameter+"_"+id+":checked").val();
            if (val == "on" ) val = 1;
            else val = 0;
        }else if (parameter == "personal_folder") {
            var val = $("#"+parameter+"_"+id+":checked").val();
            if (val == "on" ) val = 1;
            else val = 0;
        }else if (parameter == "gestionnaire") {
            var val = $("#"+parameter+"_"+id+":checked").val();
            if (val == "on" ) val = 1;
            else val = 0;
        }else if (parameter == "admin") {
            var val = $("#"+parameter+"_"+id+":checked").val();
            if (val == "on" ) val = 1;
            else val = 0;
        }
        $.post("sources/users.queries.php",
            {
                type    : parameter,
                value   : val,
                id      : id
            },
            function(data){
                $("#div_dialog_message_text").html("<div style=\"font-size:16px; text-align:center;\"><span class=\"ui-icon ui-icon-info\" style=\"float: left; margin-right: .3em;\"></span>'.$txt['alert_message_done'].'</div>");$("#div_dialog_message").dialog("open");
            }
        );
    }

    function Open_Div_Change(id,type){
        LoadingPage();  //show loading div
        var data = "type=open_div_"+type+"&id="+id;
        httpRequest("sources/users.queries.php",data);
    }

    function Change_user_rights(id,type){
        var list = "";
        if ( type == "functions" ) var form = document.forms.tmp_functions;
        if ( type == "autgroups" ) var form = document.forms.tmp_autgroups;
        if ( type == "forgroups" ) var form = document.forms.tmp_forgroups;

        for (i=0 ; i<= form.length-1 ; i++){
            if (form[i].type == "checkbox" && form[i].checked){
                function_id = form[i].id.split("-")
                if ( list == "" ) list = function_id[1];
                else list = list + ";" + function_id[1];
            }
        }
        if ( type == "functions" ) var data = "type=change_user_functions&id="+id+"&list="+list;
        if ( type == "autgroups" ) var data = "type=change_user_autgroups&id="+id+"&list="+list;
        if ( type == "forgroups" ) var data = "type=change_user_forgroups&id="+id+"&list="+list;
        httpRequest("sources/users.queries.php",data);
    }

    function unlock_user(id){
        $.post("sources/users.queries.php",
            {
                type    : "unlock_account",
                id      : id
            },
            function(data){
                document.form_utilisateurs.submit();
            }
        );
    };

	function check_domain(email){
		$("#ajax_loader_new_mail").show();

		//extract domain from email
		var atsign = email.substring(0,email.lastIndexOf("@")+1);
		var domain = email.substring(atsign.length,email.length+1);

		//check if domain exists
		$.post("sources/users.queries.php",
            {
                type    	: "check_domain",
                domain      : domain
            },
            function(data){
            	data = $.parseJSON(data);
            	$("#new_folder_role_domain").attr("disabled", "disabled");
                if (data.folder == "not_exists" && data.role == "not_exists") {
                	$("#new_folder_role_domain").attr("disabled", "");
                	$("#auto_create_folder_role_span").html(domain);
                	$("#new_domain").val(domain);
                }
                $("#ajax_loader_new_mail").hide();
            }
        );
	}
    ';
}

$htmlHeaders .= '
// ]]>
</script>';
?>