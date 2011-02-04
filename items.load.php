<?php
/**
 * @file 		items.load.php
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

if ($_SESSION['CPM'] != 1)
	die('Hacking attempt...');


?>

<script type="text/javascript">

    function AddNewNode(){
        //Select first child node in tree
        $('#2').click();
        //Add new node to selected node
        simpleTreeCollection.get(0).addNode(1,'A New Node')
    }

    function EditNode(){
        //Select first child node in tree
        $('#2').click();
        //Add new node to selected node
        simpleTreeCollection.get(0).addNode(1,'A New Node')
    }

    function DeleteNode(){
        //Select first child node in tree
        $('#2').click();
        //Add new node to selected node
        simpleTreeCollection.get(0).delNode()
    }

    function showItemsInTree(type){
        if ( document.getElementById('img_funnel').src == "includes/images/funnel_plus.png" )
            document.getElementById('img_funnel').src="includes/images/funnel_minus.png"
        else
            document.getElementById('img_funnel').src="includes/images/funnel_plus.png"
    }

    //FUNCTION mask/unmask passwords characters
    function ShowPassword(pw){
        if ( $('#id_pw').html() =='<img src="includes/images/masked_pw.png">' )
            $('#id_pw').html($('#hid_pw').val());
        else
            $('#id_pw').html('<img src="includes/images/masked_pw.png" />');
    }

    function protectString(string){
		return string.replace(/"/g,'&quot;')
    }

    function unprotectString(string){
		return string.replace(/&quot;/g,'"')
    }


//###########
//## FUNCTION : Launch the listing of all items of one category
//###########
function ListerItems(groupe_id, restricted){
    if ( groupe_id != undefined ){
        LoadingPage();

        //clean form
        $('#id_label, #id_pw, #id_url, #id_desc, #id_login, #id_info, #id_restricted_to, #id_files, #id_tags, #items_list').html("");
        $("#items_list").css("display", "");
        $("#selected_items").val("");
        $("#hid_cat").val(groupe_id);

        //Disable menu buttons
        $('#menu_button_edit_item,#menu_button_del_item,#menu_button_add_fav,#menu_button_del_fav,#menu_button_show_pw,#menu_button_copy_pw,#menu_button_copy_login,#menu_button_copy_link,#menu_button_copy_item').attr('disabled', 'disabled');

        //ajax query
        $.post("sources/items.queries.php",
        	{
        		type 	: "lister_items_groupe",
        		id 		: groupe_id,
        		restricted : restricted
        	},
        	function(data){
                //decrypt data
                data = jsonParse(aes_decrypt(data));

                $("#recherche_group_pf").val(data.saltkey_is_required);

				if (data.error == "is_pf_but_no_saltkey") {
					//warn user about his saltkey
					$("#item_details_no_personal_saltkey").show();
					$("#item_details_ok").hide();
				}else{
					//Display items
					$("#item_details_no_personal_saltkey").hide();
					$("#item_details_ok").show();
	        		$("#items_list").show();
	        		$("#items_list").html(data.items_html);
	        		$("#items_path").html(data.arborescence);
	        		$("#items_list").val("");

	        		//If restriction for role
	        		if (restricted == 1) {
	        			$("#menu_button_add_item, #menu_button_copy_item").attr('disabled', 'disabled');
	        		}else{
	        			$("#menu_button_add_item, #menu_button_copy_item").removeAttr('disabled');
	        		}

					var clip;
					//clip.setHandCursor( true );

					//If no data then empty
					if (data.array_items == null) {

					}else{
		        		// Build clipboard for pw
		                for (var i=0; i < data.array_items.length; ++i) {
		                	//clipboard for password
		                	clip = new ZeroClipboard.Client();
		                	if (data.array_items[i][1] != "" && data.array_items[i][3] == "1"){
		                		clip.setText( data.array_items[i][1] );
		                		clip.addEventListener( "onMouseDown", function(client) {
		                			$("#message_box").html("<?php echo $txt['pw_copied_clipboard'];?>").show().fadeOut(1000);
		                		});
		                		clip.glue("icon_pw_"+data.array_items[i][0]);
		                	}else {
		                		clip.setText("");
		                		clip.glue("icon_pw_"+data.array_items[i][0]);
		                	}
		                	//clipboard for login
		                	clip = new ZeroClipboard.Client();
		                	if (data.array_items[i][2] != "" && data.array_items[i][3] == "1") {
		                		clip.setText( data.array_items[i][2] );
		                		clip.addEventListener( "onMouseDown", function(client) {
		                			$("#message_box").html("<?php echo $txt['login_copied_clipboard'];?>").show().fadeOut(1000);
		                		});
		                		clip.glue("icon_login_"+data.array_items[i][0]);
		                	}else {
		                		clip.setText("");
		                		clip.glue("icon_login_"+data.array_items[i][0]);
		                	}
		                }
		                $(".item_draggable").draggable({
		                	handle: '.file',
		                	cursorAt: { left:0 },
		                	appendTo: 'body',
		                	greedy: true,
		                	helper: 'clone',
		                	opacity: 0.3,
		                	stop: function(event, ui){
		                		$( this ).removeClass( "ui-state-highlight" );
		                	},
		                	start: function(event, ui){
		                		$( this ).addClass( "ui-state-highlight" );
		                	}
						});
						$(".folder").droppable({
							hoverClass: "ui-state-active",
							drop: function( event, ui ) {
								ui.draggable.hide();
								//move item
								$.post("sources/items.queries.php",
						      	{
						      		type 	: "move_item",
						      		item_id : ui.draggable.attr("id"),
						      		folder_id : $( this ).attr("id").substring(4)
						      	});
							}
						});
					}
	            }

        		//hide ajax loader
        		$("#div_loading").hide();
        	}
        );
    }
}

function pwGenerate(elem){
    if ( elem != "" ) elem = elem+"_";

    //show ajax image
    $("#"+elem+"pw_wait").show();

    var data = "type=pw_generate"+
                "&size="+$("#"+elem+'pw_size').text()+
                "&num="+document.getElementById(elem+'pw_numerics').checked+
                "&maj="+document.getElementById(elem+'pw_maj').checked+
                "&symb="+document.getElementById(elem+'pw_symbols').checked+
                "&secure="+document.getElementById(elem+'pw_secure').checked+
                "&elem="+elem;
    httpRequest("sources/items.queries.php",data+"&force=false");
}

function pwCopy(elem){
    if ( elem != "" ) elem = elem+"_";
    document.getElementById(elem+'pw2').value = document.getElementById(elem+'pw1').value;
}

function catSelected(val){
    document.getElementById("hid_cat").value= val;
}

function RecupComplexite(val,edit){
    var data = "type=recup_complex"+
                "&groupe="+val+
                "&edit="+edit;
    httpRequest("sources/items.queries.php",data);
}

function AjouterItem(){
    document.getElementById('error_detected').value = '';   //Refresh error foolowup
    var erreur = "";
    var  reg=new RegExp("[.|;|:|!|=|+|-|*|/|#|\"|'|&|]");

    if ( document.getElementById("label").value == "" ) erreur = "<?php echo $txt['error_label'];?>";
    else if ( document.getElementById("pw1").value == "" ) erreur = "<?php echo $txt['error_pw'];?>";
    else if ( document.getElementById("categorie").value == "na" ) erreur = "<?php echo $txt['error_group'];?>";
    else if ( document.getElementById("pw1").value != document.getElementById("pw2").value ) erreur = "<?php echo $txt['error_confirm'];?>";
    else if ( document.getElementById("item_tags").value != "" && reg.test(document.getElementById("item_tags").value) ) erreur = "<?php echo $txt['error_tags'];?>";
    else{
        //Check pw complexity level
        if (
            ( document.getElementById("bloquer_creation_complexite").value == 0 && parseInt(document.getElementById("mypassword_complex").value) >= parseInt(document.getElementById("complexite_groupe").value) )
            ||
            ( document.getElementById("bloquer_creation_complexite").value == 1 )
            ||
            ( $('#recherche_group_pf').val() == 1 && $('#personal_sk_set').val() == 1 )
        ){
            var annonce = 0;
            if ( document.getElementById('annonce').checked ) annonce = 1;

            //Manage restrictions
            var restriction = restriction_role = "";
            $("#restricted_to_list option:selected").each(function () {
            	//check if it's a role
            	if ($(this).val().indexOf('role_') != -1) {
            		restriction_role += $(this).val().substring(5) + ";";
            	}else{
					restriction += $(this).val() + ";";
				}
	        });
            if ( restriction != "" && restriction.indexOf($('#form_user_id').val()) == "-1" )
                restriction = $('#form_user_id').val()+";"+restriction
            if ( restriction == ";" ) restriction = "";

            //Manage diffusion list
            var diffusion = "";
            $("#annonce_liste_destinataires option:selected").each(function () {
            	diffusion += $(this).val() + ";";
            });
            if ( diffusion == ";" ) diffusion = "";

			//Manage description
            if (CKEDITOR.instances["desc"]) {
            	var description = CKEDITOR.instances["desc"].getData();
            }else{
            	var description = protectString($("#desc").val());
            }

            //Is PF
            if ($('#recherche_group_pf').val() == 1 && $('#personal_sk_set').val() == 1) {
				var is_pf = 1;
            }else{
            	var is_pf = 0;
            }

            //prepare data
            var data = '{"pw":"'+protectString($('#pw1').val())+'", "label":"'+protectString($('#label').val())+'", '+
            '"login":"'+protectString($('#item_login').val())+'", "is_pf":"'+is_pf+'", '+
            '"description":"'+description+'", "url":"'+protectString($('#url').val())+'", "categorie":"'+$('#categorie').val()+'", '+
            '"restricted_to":"'+restriction+'", "restricted_to_roles":"'+restriction_role+'", "salt_key_set":"'+$('#personal_sk_set').val()+'", "is_pf":"'+$('#recherche_group_pf').val()+
            '", "annonce":"'+annonce+'", "diffusion":"'+diffusion+'", "id":"'+$('#id_item').val()+'", '+
            '"anyone_can_modify":"'+$('#anyone_can_modify:checked').val()+'", "tags":"'+protectString($('#item_tags').val())+'"}';

            //Send query
            $.post(
                "sources/items.queries.php",
                {
                    type    : "new_item",
					data :	aes_encrypt(data)
                },
                function(data){
                    LoadingPage();
                    //Check errors
                    if (data[0].item_exists == "1") {
                        $("#div_formulaire_saisi").dialog("open");
                        $("#new_show_error").html('<?php echo $txt['error_item_exists'];?>');
                        $("#new_show_error").show();
                        LoadingPage();
                    }else if (data[0].new_id != "") {
                        $("#new_show_error").hide();
                        $("#random_id").val("");
                        //Refresh page
                        window.location.href = "index.php?page=items&group="+$('#categorie').val()+"&id="+data[0].new_id;
                    }
                    LoadingPage();
                },
                "json"
            );



        }else{
            document.getElementById('new_show_error').innerHTML = "<?php echo $txt['error_complex_not_enought'];?>";
            $("#new_show_error").show();
        }
    }
    if ( erreur != "") {
        document.getElementById('new_show_error').innerHTML = erreur;
        $("#new_show_error").show();
    }
}

function EditerItem(){
    var erreur = "";
    var  reg=new RegExp("[.|,|;|:|!|=|+|-|*|/|#|\"|'|&]");
    if ( document.getElementById("edit_label").value == "" ) erreur = "<?php echo $txt['error_label'];?>";
    else if ( document.getElementById("edit_pw1").value == "" ) erreur = "<?php echo $txt['error_pw'];?>";
    else if ( document.getElementById("edit_pw1").value != document.getElementById("edit_pw2").value ) erreur = "<?php echo $txt['error_confirm'];?>";
    else if ( document.getElementById("edit_tags").value != "" && reg.test(document.getElementById("edit_tags").value) ) erreur = "<?php echo $txt['error_tags'];?>";
    else{
        //Check pw complexity level
        if ( (
                document.getElementById("bloquer_modification_complexite").value == 0 &&
                parseInt(document.getElementById("edit_mypassword_complex").value) >= parseInt(document.getElementById("complexite_groupe").value)
            )
            ||
            ( document.getElementById("bloquer_modification_complexite").value == 1 )
            ||
            ($('#recherche_group_pf').val() == 1 && $('#personal_sk_set').val() == 1)
        ){
            LoadingPage();  //afficher image de chargement
            var annonce = 0;
            if ( document.getElementById('edit_annonce').checked ) annonce = 1;

            //Manage restriction
            var myselect = document.getElementById('edit_restricted_to_list');
            var restriction = "";
           for (var loop=0; loop < myselect.options.length; loop++) {
                if (myselect.options[loop].selected == true && myselect.options[loop].value != "") restriction = restriction + myselect.options[loop].value + ";";
            }
            if ( restriction == ";" ) restriction = "";

            //Manage diffusion list
            var myselect = document.getElementById('edit_annonce_liste_destinataires');
            var diffusion = "";
            for (var loop=0; loop < myselect.options.length; loop++) {
                if (myselect.options[loop].selected == true) diffusion = diffusion + myselect.options[loop].value + ";";
            }
            if ( diffusion == ";" ) diffusion = "";

			//Manage description
            if (CKEDITOR.instances["edit_desc"]) {
            	var description = CKEDITOR.instances["edit_desc"].getData();
            }else{
            	var description = protectString($("#edit_desc").val());
            }

            //Is PF
            if ($('#recherche_group_pf').val() == 1 && $('#personal_sk_set').val() == 1) {
				var is_pf = 1;
            }else{
            	var is_pf = 0;
            }

          	//prepare data
            var data = '{"pw":"'+protectString($('#edit_pw1').val())+'", "label":"'+protectString($('#edit_label').val())+'", '+
            '"login":"'+protectString($('#edit_item_login').val())+'", "is_pf":"'+is_pf+'", '+
            '"description":"'+description+'", "url":"'+protectString($('#edit_url').val())+'", "categorie":"'+$('#edit_categorie').val()+'", '+
            '"restricted_to":"'+restriction+'", "salt_key_set":"'+$('#personal_sk_set').val()+'", "is_pf":"'+$('#recherche_group_pf').val()+'", '+
            '"annonce":"'+annonce+'", "diffusion":"'+diffusion+'", "id":"'+$('#id_item').val()+'", '+
            '"anyone_can_modify":"'+$('#edit_anyone_can_modify:checked').val()+'", "tags":"'+protectString($('#edit_tags').val())+'"}';

            //send query
            $.post(
                "sources/items.queries.php",
                {
                    type    : "update_item",
                    data      : aes_encrypt(data)
                },
                function(data){
                    //decrypt data
                    data = jsonParse(aes_decrypt(data));

                    //check if format error
                    if (data.error == "format") {
                        $("#div_loading").hide();
                        document.getElementById('edit_show_error').innerHTML = data.error+" ERROR (JSON is broken)!!!!!";
                        $("#edit_show_error").show();
                    }
                    //if reload page is needed
                    else if (data.reload_page == "1") {
                        window.location.href = "index.php?page=items&group="+data.id_tree+"&id="+data.id;
                    }else{
                        //Refresh form
                        $("#id_label").text($('#edit_label').val());
                        $("#id_pw").text($('#edit_pw1').val());
                        $("#id_url").html($('#edit_url').val());
                        $("#id_desc").html(description);
                        $("#id_login").html($('#edit_item_login').val());
                        $("#id_restricted_to").html(restriction);
                        $("#id_tags").html($('#edit_tags').val());
                        $("#id_files").html(unprotectString(data.files));
                        $("#item_edit_list_files").html(data.files_edit);
                        $("#id_info").html(unprotectString(data.history));

                        //Refresh hidden data
                        $("#hid_label").val($('#edit_label').val());
                        $("#hid_pw").val($('#edit_pw1').val());
                        $("#hid_url").val($('#edit_url').val());
                        $("#hid_desc").val(description);
                        $("#hid_login").val($('#edit_item_login').val());
                        $("#hid_restricted_to").val(restriction);
                        $("#hid_tags").val($('#edit_tags').val());
                        $("#hid_files").val(data.files);
                        $("#id_categorie").html(data.id_tree);
                        $("#id_item").html(data.id);

                        //calling image lightbox when clicking on link
                        $("a.image_dialog").click(function(event){
                            event.preventDefault();
                            PreviewImage($(this).attr("href"),$(this).attr("title"));
                        });

                        //Clear upload queue
                        $('#item_edit_file_queue').html('');
                        //Select 1st tab
                        $( "#item_edit_tabs" ).tabs({ selected: 0 });
                        //Close dialogbox
                        $("#div_formulaire_edition_item").dialog('close');

                        //hide loader
                        $("#div_loading").hide();
                    }
                }
            );


        }else{
            document.getElementById('edit_show_error').innerHTML = "<?php echo $txt['error_complex_not_enought'];?>";
            $("#edit_show_error").show();
        }
    }

    if ( erreur != "") {
        document.getElementById('edit_show_error').innerHTML = erreur;
        $("#edit_show_error").show();
    }
}

function aes_encrypt(text) {
    return Aes.Ctr.encrypt(text, "<?php echo $_SESSION['cle_session'];?>", 256);
}

function aes_decrypt(text) {
    return Aes.Ctr.decrypt(text, "<?php echo $_SESSION['cle_session'];?>", 256);
}

function AjouterFolder(){
    if ( document.getElementById("new_rep_titre").value == "0" ) alert("<?php echo $txt['error_group_label'];?>");
    else if ( document.getElementById("new_rep_complexite").value == "" ) alert("<?php echo $txt['error_group_complex'];?>");
    else{
    	LoadingPage();
    	if ($("#new_rep_role").val() == undefined) {
    		role_id = "<?php echo $_SESSION['fonction_id'];?>";
    	}else{
    		role_id = $("#new_rep_role").val();
    	}
        var data = "type=new_rep"+
                    "&title="+escape(document.getElementById('new_rep_titre').value)+
                    "&complexite="+escape(document.getElementById('new_rep_complexite').value)+
                    "&groupe="+document.getElementById("new_rep_groupe").value+
                    "&role_id="+role_id;
        httpRequest("sources/items.queries.php",data);
    }
}

function EditerFolder(){
    if ( document.getElementById("edit_rep_titre").value == "" ) alert("<?php echo $txt['error_group_label'];?>");
    else if ( document.getElementById("edit_rep_groupe").value == "0" ) alert("<?php echo $txt['error_group'];?>");
    else if ( document.getElementById("edit_rep_complexite").value == "" ) alert("<?php echo $txt['error_group_complex'];?>");
    else{
        var data = "type=update_rep"+
                    "&title="+escape(document.getElementById('edit_rep_titre').value)+
                    "&complexite="+escape(document.getElementById('edit_rep_complexite').value)+
                    "&groupe="+document.getElementById("edit_rep_groupe").value;
        httpRequest("sources/items.queries.php",data);
    }
}

function SupprimerFolder(){
    if ( document.getElementById("delete_rep_groupe").value == "0" ) alert("<?php echo $txt['error_group'];?>");
    else if ( confirm("<?php echo $txt['confirm_delete_group'];?>") ) {
        var data = "type=delete_rep"+
                    "&groupe="+document.getElementById("delete_rep_groupe").value;
        httpRequest("sources/items.queries.php",data);
    }
}

function AfficherDetailsItem(id, salt_key_required, expired_item, restricted){
    LoadingPage();  //afficher image de chargement
    if ( document.getElementById("is_admin").value == "1" ){
        $('#menu_button_edit_item,#menu_button_del_item,#menu_button_copy_item').attr('disabled', 'disabled');
    }

    //Check if personal SK is needed and set
    if ( ($('#recherche_group_pf').val() == 1 && $('#personal_sk_set').val() == 0) && salt_key_required == 1 ){
    	$("#div_dialog_message_text").html("<div style='font-size:16px;'><span class='ui-icon ui-icon-alert' style='float: left; margin-right: .3em;'><\/span><?php echo $txt['alert_message_personal_sk_missing'];?><\/div>");
    	LoadingPage();
    	$("#div_dialog_message").dialog("open");
    }else if ($('#recherche_group_pf').val() == 0 || ($('#recherche_group_pf').val() == 1 && $('#personal_sk_set').val() == 1)) {
        $.post(
            "sources/items.queries.php",
            {
                type    			: 'show_details_item',
                id      			: id,
                folder_id 			: $('#hid_cat').val(),
                salt_key_required   : $('#recherche_group_pf').val(),
                salt_key_set        : $('#personal_sk_set').val(),
                expired_item        : expired_item,
                restricted        	: restricted
            },
            function(data){
                //decrypt data
                data = jsonParse(aes_decrypt(data));

                //Change the class of this selected item
                if ( $("#selected_items").val() != "") {
                    $("#fileclass"+$("#selected_items").val()).removeClass("fileselected");
                }
                $("#selected_items").val(data.id);

                //Show saltkey
                if (data.edit_item_salt_key == "1") {
                    $("#edit_item_salt_key").show();
                }else{
                    $("#edit_item_salt_key").hide();
                }

                //User can modify item
                if (data.user_can_modify == "1") {
                    $("#menu_button_edit_item, #menu_button_del_item").removeAttr("disabled");
                }else{
                    $("#menu_button_edit_item, #menu_button_del_item").attr("disabled","disabled");
                }

                //Show detail item
                if (data.show_detail_option == "0") {
                    $("#item_details_ok").show();
                    $("#item_details_expired").hide();
                    $("#item_details_expired_full").hide();
                }if (data.show_detail_option == "1") {
                    $("#item_details_ok").show();
                    $("#item_details_expired").show();
                    $("#item_details_expired_full").hide();
                }else if (data.show_detail_option == "2") {
                    $("#item_details_ok").hide();
                    $("#item_details_expired").hide();
                    $("#item_details_expired_full").hide();
                }
                $("#item_details_nok").hide();
                $("#fileclass"+data.id).addClass("fileselected");

                if (data.show_details == "1" && data.show_detail_option != "2"){
                    //unprotect data
                    data.login = unprotectString(data.login);

                    //Display details
                    $("#id_label").html(data.label).html();
                    $("#hid_label").val(data.label);
                    $("#id_pw").html('<img src="includes/images/masked_pw.png" />');
                    $("#hid_pw").val(data.pw);
                    if ( data.url != "") {
                        $("#id_url").html(data.url+data.link);
                        $("#hid_url").val(data.url);
                    }else{
                        $("#id_url").html("");
                        $("#hid_url").val("");
                    }
                    $("#id_desc").html(data.description);
                    $("#hid_desc").val(data.description);
                    $("#id_login").html(data.login).html();
                    $("#hid_login").val(data.login);
                    $("#id_info").html(htmlspecialchars_decode(data.historique));
                    $("#id_restricted_to").html(data.id_restricted_to);
                    $("#hid_restricted_to").val(data.pid_restricted_tow);
                    $("#id_tags").html(data.tags).html();
                    $("#hid_tags").val($("#id_tags").html());
                    $("#hid_anyone_can_modify").html(data.anyone_can_modify);
                    $("#id_categorie").val(data.folder);
                    $("#id_item").val(data.id);
                    $("#id_files").html(data.files_id).html();
                    $("#hid_files").val(data.files_id);
                    $("#item_edit_list_files").html(data.files_edit).html();
                    $("#div_last_items").html(htmlspecialchars_decode(data.div_last_items));

                    //Anyone can modify button
                    if (data.anyone_can_modify == "1") {
                    	$("#edit_anyone_can_modify").attr('checked', true);
                    }else{
                        $("#edit_anyone_can_modify").attr('checked', false);
                    }

                    //enable copy buttons
                    $("#menu_button_show_pw, #menu_button_copy_pw, #menu_button_copy_login, #menu_button_copy_link").removeAttr("disabled");

	                if (data.restricted == "1") {
	                	$("#menu_button_add_item, #menu_button_copy_item").attr("disabled","disabled");
	                }else{
	                    $("#menu_button_add_item, #menu_button_copy_item").removeAttr("disabled");
	                }

                    //Prepare clipboard copies
                    if ( data.pw != "" ) {
                        var clip = new ZeroClipboard.Client();
                        clip.setText( data.pw );
                        clip.addEventListener( "onMouseDown", function(client) {
                            $("#message_box").html("<?php echo $txt['pw_copied_clipboard'];?>").show().fadeOut(1000);
                        });
                        clip.glue("menu_button_copy_pw");
                    }
                    if ( data.login != "" ) {
                        var clip = new ZeroClipboard.Client();
                        clip.setText( data.login );
                        clip.addEventListener( "onMouseDown", function(client) {
                            $("#message_box").html("<?php echo $txt['login_copied_clipboard'];?>").show().fadeOut(1000);
                        });
                        clip.glue( "menu_button_copy_login" );
                    }
                    //prepare link to clipboard
                    var clip = new ZeroClipboard.Client();
                    clip.setText( "<?php echo $_SESSION['settings']['cpassman_url'];?>index.php?page=items&group="+data.folder+"&id="+data.id );
                    clip.addEventListener( "onMouseDown", function(client) {
                        $("#message_box").html("<?php echo $txt['url_copied'];?>").show().fadeOut(1000);
                    });
                    clip.glue( "menu_button_copy_link" );

                    // function calling image lightbox when clicking on link
                    $("a.image_dialog").click(function(event){
                        event.preventDefault();
                        PreviewImage($(this).attr("href"),$(this).attr("title"));
                    });

                    //Set favourites icon
                    if ( data.favourite == "1" ) {
                        $("#menu_button_add_fav").attr("disabled","disabled");
                        $("#menu_button_del_fav").removeAttr("disabled");
                    }else{
                        $("#menu_button_add_fav").removeAttr("disabled");
                        $("#menu_button_del_fav").attr("disabled","disabled");
                    }
                }else if (data.show_details == "1" && data.show_detail_option == "2") {
                	$("#item_details_nok").hide();
                    $("#item_details_ok").hide();
                    $("#item_details_expired_full").show();
                    $("#menu_button_edit_item, #menu_button_del_item, #menu_button_copy_item, #menu_button_add_fav, #menu_button_del_fav, #menu_button_show_pw, #menu_button_copy_pw, #menu_button_copy_login, #menu_button_copy_link").attr("disabled","disabled");
                }else{
                    //Dont show details
                    $("#item_details_nok").show();
                    $("#item_details_ok").hide();
                    $("#item_details_expired").hide();
                    $("#item_details_expired_full").hide();
                    $("#menu_button_edit_item, #menu_button_del_item, #menu_button_copy_item, #menu_button_add_fav, #menu_button_del_fav, #menu_button_show_pw, #menu_button_copy_pw, #menu_button_copy_login, #menu_button_copy_link").attr("disabled","disabled");
                }
                $("#div_loading").hide();
            }
        );
    }
}


//G?rer l'affichage d'une recherche
function AfficherRecherche(){
    if ( document.getElementById('recherche_id').value != "" ){
        ListerItems(document.getElementById('recherche_groupe').value);
        AfficherDetailsItem(document.getElementById('recherche_id').value);
    }else if ( document.getElementById('recherche_groupe').value != "" ){
        ListerItems(document.getElementById('recherche_groupe').value);
    }else
        ListerItems(<?php echo $first_group;?>);
}

/*
   * FUNCTION
   * Launch an action when clicking on a quick icon
   * $action = 0 => Make not favorite
   * $action = 1 => Make favorite
*/
function ActionOnQuickIcon(id, action){
	//change quick icon
	if (action == 1) {
		$("#quick_icon_fav_"+id).html("<img src='includes/images/mini_star_enable.png' onclick='ActionOnQuickIcon("+id+",0)' //>");
	}else if (action == 0) {
		$("#quick_icon_fav_"+id).html("<img src='includes/images/mini_star_disable.png' onclick='ActionOnQuickIcon("+id+",1)' //>");
	}

	//Send query
	$.post("sources/items.queries.php",
	    {
	        type    : 'action_on_quick_icon',
	        id      : id,
	        action  : action
	    }
	);
}

//###########
//## FUNCTION : prepare new folder dialogbox
//###########
function open_add_group_div() {
	//Select the actual forlder in the dialogbox
	$('#new_rep_groupe').val($('#hid_cat').val());
    $('#div_ajout_rep').dialog('open');
}

//###########
//## FUNCTION : prepare editing folder dialogbox
//###########
function open_edit_group_div() {
	//Select the actual forlder in the dialogbox
	$('#edit_rep_groupe').val($('#hid_cat').val());
    $('#div_editer_rep').dialog('open');
}

//###########
//## FUNCTION : prepare delete folder dialogbox
//###########
function open_del_group_div() {
    $('#div_supprimer_rep').dialog('open');
}

//###########
//## FUNCTION : prepare new item dialogbox
//###########
function open_add_item_div() {
    LoadingPage();

    //Check if personal SK is needed and set
    if ( $('#recherche_group_pf').val() == 1 && $('#personal_sk_set').val() == 0 ){
    	$("#div_dialog_message_text").html("<div style='font-size:16px;'><span class='ui-icon ui-icon-alert' style='float: left; margin-right: .3em;'><\/span><?php echo $txt['alert_message_personal_sk_missing'];?><\/div>");
    	LoadingPage();
    	$("#div_dialog_message").dialog("open");
    }
    else if ($('#recherche_group_pf').val() == 0 || ($('#recherche_group_pf').val() == 1 && $('#personal_sk_set').val() == 1)) {
	    //Select the actual forlder in the dialogbox
	    $('#categorie').val($('#hid_cat').val());

	    //Get the associated complexity level
	    RecupComplexite($('#hid_cat').val(),0);

        //Show WYGIWYS editor if enabled
        if ($('#richtext_on').val() == "1") {
            CKEDITOR.replace(
                "desc",
                {
                    toolbar :[["Bold", "Italic", "Strike", "-", "NumberedList", "BulletedList", "-", "Link","Unlink","-","RemoveFormat"]],
                    height: 100,
                    language: "<?php echo $k['langs'][$_SESSION['user_language']];?>"
                }
            );
        }

        //open dialog
        $("#div_formulaire_saisi").dialog("open");
	}
}

//###########
//## FUNCTION : prepare editing item dialogbox
//###########
function open_edit_item_div() {
    LoadingPage();
    $('#edit_display_title').html($('#hid_label').val());
    $('#edit_label').val($('#hid_label').val());
    $('#edit_desc').val($('#hid_desc').val());
    $('#edit_pw1').val($('#hid_pw').val());
    $('#edit_pw2').val($('#hid_pw').val());
    $('#edit_item_login').val($('#hid_login').val());
    $('#edit_url').val($('#hid_url').val());
    $('#edit_categorie').val($('#id_categorie').val());
    $('#edit_restricted_to').val($('#hid_restricted_to').val());
    $('#edit_tags').val($('#hid_tags').val());
	if ($('$id_anyone_can_modify:checked').val() == "on") {
		$('#edit_anyone_can_modify').attr("checked","checked");
		$('#edit_anyone_can_modify').button("refresh");
	}else{
		$('#edit_anyone_can_modify').attr("checked",false);
		$('#edit_anyone_can_modify').button("refresh");
	}

	//Get pw complexity level
	runPassword(document.getElementById('edit_pw1').value, 'edit_mypassword');

	//Get complexity level for this folder
	RecupComplexite(document.getElementById('hid_cat').value,1);

	//Get list of people in restriction list
	var myselect = document.getElementById('edit_restricted_to_list');
	myselect.options.length = 0;
	var liste = document.getElementById('input_liste_utilisateurs').value.split(';');
	for (var i=0; i<liste.length; i++) {
	    var elem = liste[i].split('#');
	    if ( elem[0] != "" ){
	        myselect.options[myselect.options.length] = new Option(elem[1], elem[0]);
	        var index = document.getElementById('edit_restricted_to').value.lastIndexOf(elem[0]+";");
	        if ( index != -1 ) {
	            myselect.options[i].selected = true;
	        }else myselect.options[i].selected = false;
	    }
	}

    //Show WYGIWYS editor if enabled
    if ($('#richtext_on').val() == "1") {
        CKEDITOR.replace(
            "edit_desc",
            {
                toolbar :[["Bold", "Italic", "Strike", "-", "NumberedList", "BulletedList", "-", "Link","Unlink","-","RemoveFormat"]],
                height: 100,
                language: "<?php echo $k['langs'][$_SESSION['user_language']];?>"
            }
        );
    }

    //Prepare multiselect widget
    $("#edit_restricted_to_list").multiselect({
        selectedList: 7,
        minWidth: 430,
        height: 145,
        checkAllText: "'.$txt['check_all_text'].'",
        uncheckAllText: "<?php echo$txt['uncheck_all_text'];?>",
        noneSelectedText: "<?php echo$txt['none_selected_text'];?>"
    });

    //open dialog
    $("#div_formulaire_edition_item").dialog("open");
}

//###########
//## FUNCTION : prepare new item dialogbox
//###########
function open_del_item_div() {
    $('#div_del_item').dialog('open');
}

//###########
//## FUNCTION : copy an existing item
//###########
function open_copy_item_div() {
	LoadingPage();
	//Send query
	$.post(
		"sources/items.queries.php",
		{
			type    : "copy_item",
			item_id : $('#id_item').val()
		},
		function(data){
			data = jsonParse(data);
			//check if format error
            if (data.error == "no_item") {
                $("#div_loading").hide();
                document.getElementById('edit_show_error').innerHTML = data.error_text;
                $("#edit_show_error").show();
            }

			//if OK
			if (data.status == "ok") {
				window.location.href = "index.php?page=items&group="+$('#categorie').val()+"&id="+data.new_id;
			}
			LoadingPage();
		},
		"json"
	);
}

//###########
//## FUNCTION : Clear HTML tags from a string
//###########
function clear_html_tags(){
    var data = "type=clear_html_tags"+
                "&id_item="+document.getElementById('id_item').value;
    httpRequest("sources/items.queries.php",data);
}

//###########
//## FUNCTION : Permits to start uploading files in EDIT ITEM mode
//###########
function upload_attached_files_edit_mode() {
    // Pass dynamic ITEM id
    var post_id = $('#selected_items').val();
    var user_id = $('#form_user_id').val();

    $('#item_edit_files_upload').uploadifySettings('scriptData', {'post_id':post_id,'user_id':user_id,'type':'modification'});

    // Launch upload
    $("#item_edit_files_upload").uploadifyUpload();
}

//###########
//## FUNCTION : Permits to start uploading files in NEW ITEM mode
//###########
function upload_attached_files() {
    // Pass dynamic ITEM id
    var post_id  = "";
    var user_id = $('#form_user_id').val();

    //generate fake id if needed
    if ( document.getElementById("random_id").value == "" ) var post_id = CreateRandomString(9,"num");
    else var post_id = $("#random_id").val();

    //Save fake id
    $("#random_id").val(post_id);

    $('#item_files_upload').uploadifySettings('scriptData', {'post_id':post_id,'user_id':user_id,'type':'creation'});

    // Launch upload
    $("#item_files_upload").uploadifyUpload();
}

//###########
//## FUNCTION : Permits to delete an attached file
//###########
function delete_attached_file(file_id){
    var data = "type=delete_attached_file"+
                "&file_id="+file_id;
    httpRequest("sources/items.queries.php",data);
}

//###########
//## FUNCTION : Permits to preview an attached image
//###########
PreviewImage = function(uri,title) {
    //Get the HTML Elements
    imageDialog = $("#dialog_files");
    imageTag = $('#image_files');

    //Split the URI so we can get the file name
    uriParts = uri.split("/");

    //Set the image src
    imageTag.attr('src', uri);

    //When the image has loaded, display the dialog
    imageTag.load(function(){
        imageDialog.dialog({
            modal: true,
            resizable: false,
            draggable: false,
            width: 'auto',
            title: title
        });
    });
}

/*
* FUNCTION : permits to copy data to clipboard
*/
function copy_to_clipboard(elem_to_copy,item_id,icon_id){
    if ( $("#clipboard_loaded_"+icon_id).val() == "" ){
        var data = "type=copy_to_clipboard"+
                    "&elem_to_copy="+elem_to_copy+
                    "&item_id="+item_id+
                    "&icon_id="+icon_id;
        httpRequest("sources/items.queries.php",data);
    }
}

//###########
//## EXECUTE WHEN PAGE IS LOADED
//###########
$(function() {
    //Disable menu buttons
    $('#menu_button_edit_item,#menu_button_del_item,#menu_button_add_fav,#menu_button_del_fav').attr('disabled', 'disabled');

    // Autoresize Textareas
    //$("#desc, #edit_desc").autoResizable();
    $(".items_tree, #items_content, #item_details_ok").addClass("ui-corner-all");

    //automatic height
    var hauteur = $(window).height();
    $("#div_items, #content").height( (hauteur-150) );
    $("#items_center").height( (hauteur-390) );
    $("#items_list").height(hauteur-420);
    $(".items_tree").height(hauteur-160);
    $("#jstree").height(hauteur-185);


    // Build buttons
    $("#custom_pw, #edit_custom_pw").buttonset();
    $(".cpm_button, #anyone_can_modify, #annonce, #edit_anyone_can_modify, #edit_annonce").button();

    //Build multiselect box
    $("#restricted_to_list").multiselect({
    	selectedList: 7,
    	minWidth: 430,
    	height: 145,
    	checkAllText: "<?php echo $txt['check_all_text'];?>",
    	uncheckAllText: "<?php echo $txt['uncheck_all_text'];?>",
    	noneSelectedText: "<?php echo $txt['none_selected_text'];?>"
    });

    //autocomplete for TAGS
    $("#item_tags, #edit_tags").focus().autocomplete('sources/items.queries.php?type=autocomplete_tags', {
        width: 300,
        multiple: true,
        matchContains: false,
        multipleSeparator: " "
    });

	//Build tree
    $("#jstree").jstree({
    	"plugins" : ["themes", "html_data", "cookies"]
    });


    $("#add_folder").click(function() {
        var posit = document.getElementById('item_selected').value;
        alert($("ul").text());
    });

    $("#for_searchtext").hide();
    $("#copy_pw_done").hide();
    $("#copy_login_done").hide();

    //PREPARE DIALOGBOXES
    //=> ADD A NEW GROUP
    $("#div_ajout_rep").dialog({
        bgiframe: true,
        modal: true,
        autoOpen: false,
        width: 400,
        height: 200,
        title: "<?php echo $txt['item_menu_add_rep'];?>",
        buttons: {
            "<?php echo $txt['save_button'];?>": function() {
                AjouterFolder();
                $(this).dialog('close');
            },
            "<?php echo $txt['cancel_button'];?>": function() {
                $(this).dialog('close');
            }
        }
    });
    //<=
    //=> EDIT A GROUP
    $("#div_editer_rep").dialog({
        bgiframe: true,
        modal: true,
        autoOpen: false,
        width: 400,
        height: 200,
        title: "<?php echo $txt['item_menu_edi_rep'];?>",
        buttons: {
            "<?php echo $txt['save_button'];?>": function() {
                EditerFolder();
                $(this).dialog('close');
            },
            "<?php echo $txt['cancel_button'];?>": function() {
                $(this).dialog('close');
            }
        }
    });
    //<=
    //=> DELETE A GROUP
    $("#div_supprimer_rep").dialog({
        bgiframe: true,
        modal: true,
        autoOpen: false,
        width: 300,
        height: 200,
        title: "<?php echo $txt['item_menu_del_rep'];?>",
        buttons: {
            "<?php echo $txt['save_button'];?>": function() {
                SupprimerFolder();
                $(this).dialog('close');
            },
            "<?php echo $txt['cancel_button'];?>": function() {
                $(this).dialog('close');
            }
        }
    });
    //<=
    //=> ADD A NEW ITEM
    $("#div_formulaire_saisi").dialog({
        bgiframe: true,
        modal: true,
        autoOpen: false,
        width: 505,
        height: 600,
        title: "<?php echo $txt['item_menu_add_elem'];?>",
        buttons: {
            "<?php echo $txt['save_button'];?>": function() {
            	$("#div_loading").show();
                AjouterItem();
            },
            "<?php echo $txt['cancel_button'];?>": function() {
                //Clear upload queue
                $('#item_file_queue').html('');
                //Select 1st tab
                $( "#item_tabs" ).tabs({ selected: 0 });
                $(this).dialog('close');
            }
        },
        close: function(event,ui) {
        	if(CKEDITOR.instances["desc"]){
        		CKEDITOR.instances["desc"].destroy();
        	}
        	$("#div_loading").hide();
        }
    });
    //<=
    //=> EDITER UN ELEMENT
    $("#div_formulaire_edition_item").dialog({
        bgiframe: true,
        modal: true,
        autoOpen: false,
        width: 505,
        height: 600,
        title: "<?php echo $txt['item_menu_edi_elem'];?>",
        buttons: {
            "<?php echo $txt['save_button'];?>": function() {
                EditerItem();
            },
            "<?php echo $txt['cancel_button'];?>": function() {
                //Clear upload queue
                $('#item_edit_file_queue').html('');
                //Select 1st tab
                $( "#item_edit_tabs" ).tabs({ selected: 0 });
                //Close dialog box
                $(this).dialog('close');
            }
        },
        close: function(event,ui) {
        	if(CKEDITOR.instances["edit_desc"]){
        		CKEDITOR.instances["edit_desc"].destroy();
        	}
        	$("#div_loading").hide();
        }

    });
    //<=
    //=> SUPPRIMER UN ELEMENT
    $("#div_del_item").dialog({
        bgiframe: true,
        modal: true,
        autoOpen: false,
        width: 300,
        height: 150,
        title: "<?php echo $txt['item_menu_del_elem'];?>",
        buttons: {
            "<?php echo $txt['del_button'];?>": function() {
                var data = "type=del_item"+
                            "&groupe="+document.getElementById('hid_cat').value+
                            "&id="+document.getElementById('id_item').value;
                httpRequest("sources/items.queries.php",data);
                $(this).dialog('close');
            },
            "<?php echo $txt['cancel_button'];?>": function() {
                $(this).dialog('close');
            }
        }
    });
    //<=
    //=> SHOW LINK COPIED DIALOG
    $("#div_item_copied").dialog({
        bgiframe: true,
        modal: true,
        autoOpen: false,
        width: 500,
        height: 200,
        title: "<?php echo $txt['admin_main'];?>",
        buttons: {
            "<?php echo $txt['close'];?>": function() {
                $(this).dialog('close');
            }
        }
    });
    //<=

    //display first group items
    AfficherRecherche();

    //CALL TO UPLOADIFY FOR FILES UPLOAD in EDIT ITEM
    $("#item_edit_files_upload").uploadify({
        "uploader"  : "includes/libraries/uploadify/uploadify.swf",
        "script"    : "includes/libraries/uploadify/uploadify.php",
        "cancelImg" : "includes/libraries/uploadify/cancel.png",
        "auto"      : false,
        "multi"     : true,
        "folder"    : "upload",
        "sizeLimit" : 16777216,
        "queueID"   : "item_edit_file_queue",
        "onComplete": function(event, queueID, fileObj, reponse, data){document.getElementById("item_edit_list_files").append(fileObj.name+"<br />");},
        "buttonText": "<?php echo $txt['upload_button_text'];?>"
    });

    //CALL TO UPLOADIFY FOR FILES UPLOAD in NEW ITEM
    $("#item_files_upload").uploadify({
        "uploader"  : "includes/libraries/uploadify/uploadify.swf",
        "script"    : "includes/libraries/uploadify/uploadify.php",
        "cancelImg" : "includes/libraries/uploadify/cancel.png",
        "auto"      : false,
        "multi"     : true,
        "folder"    : "upload",
        "sizeLimit" : 16777216,
        "queueID"   : "item_file_queue",
        "onComplete": function(event, queueID, fileObj, reponse, data){document.getElementById("item_files_upload").append(fileObj.name+"<br />");},
        "buttonText": "<?php echo $txt['upload_button_text'];?>"
    });
});

function htmlspecialchars_decode (string, quote_style) {
    // Convert special HTML entities back to characters
    var optTemp = 0, i = 0, noquotes= false;
    if (typeof quote_style === 'undefined') {        quote_style = 2;
    }
    string = string.toString().replace(/&lt;/g, '<').replace(/&gt;/g, '>');
    var OPTS = {
        'ENT_NOQUOTES': 0,
        'ENT_HTML_QUOTE_SINGLE' : 1,
        'ENT_HTML_QUOTE_DOUBLE' : 2,
        'ENT_COMPAT': 2,
        'ENT_QUOTES': 3,
        'ENT_IGNORE' : 4
    };
    if (quote_style === 0) {
        noquotes = true;
    }
    if (typeof quote_style !== 'number') { // Allow for a single string or an array of string flags
        quote_style = [].concat(quote_style);
        for (i=0; i < quote_style.length; i++) {
            // Resolve string input to bitwise e.g. 'PATHINFO_EXTENSION' becomes 4
            if (OPTS[quote_style[i]] === 0) {
                noquotes = true;            }
            else if (OPTS[quote_style[i]]) {
                optTemp = optTemp | OPTS[quote_style[i]];
            }
        }        quote_style = optTemp;
    }
    if (quote_style & OPTS.ENT_HTML_QUOTE_SINGLE) {
        string = string.replace(/&#0*39;/g, "'"); // PHP doesn't currently escape if more than one 0, but it should
        // string = string.replace(/&apos;|&#x0*27;/g, "'"); // This would also be useful here, but not a part of PHP
    }
    if (!noquotes) {
        string = string.replace(/&quot;/g, '"');
    }

    string = string.replace(/&nbsp;/g, ' ');

    // Put this in last place to avoid escape being double-decoded    string = string.replace(/&amp;/g, '&');

    return string;
}
</script>