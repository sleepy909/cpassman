<?php
/**
 * @file 		roles.load.php
 * @author		Nils Laumaillé
 * @version 	2.0
 * @copyright 	(c) 2009-2011 Nils Laumaillé
 * @licensing 	CC BY-ND (http://creativecommons.org/licenses/by-nd/3.0/legalcode)
 * @link		http://www.teampass.net
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

if (!isset($_SESSION['CPM'] ) || $_SESSION['CPM'] != 1)
	die('Hacking attempt...');


?>

<script type="text/javascript">
$(function() {
$("#add_new_role").dialog({
        bgiframe: true,
        modal: true,
        autoOpen: false,
        width: 400,
        height: 150,
        title: "<?php echo $txt["give_function_title"];?>",
        buttons: {
			"<?php echo $txt["save_button"];?>": function() {
				$.post(
					"sources/roles.queries.php",
					{
						type	: "add_new_role",
						name	: $("#new_function").val()
					},
					function(data){
						if(data[0].error == "no"){
							$("#add_new_role").dialog("close");
							refresh_roles_matrix("reload");
						}
					},
					"json"
				);
            },
            "<?php echo $txt["cancel_button"];?>": function() {
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
        title: "<?php echo $txt["admin_action"];?>",
        buttons: {
            "<?php echo $txt["ok"];?>": function() {
            	$.post(
					"sources/roles.queries.php",
					{
						type	: "delete_role",
						id		: $("#delete_role_id").val()
					},
					function(data){
						if(data[0].error == "no"){
							$("#delete_role").dialog("close");
							refresh_roles_matrix("reload");
						}
					},
					"json"
				);
            },
            "<?php echo $txt["cancel_button"];?>": function() {
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
        title: "<?php echo $txt["admin_action"];?>",
        buttons: {
            "<?php echo $txt["ok"];?>": function() {
				$.post(
					"sources/roles.queries.php",
					{
						type    : "edit_role",
						id      : $("#edit_role_id").val(),
						start	: $('#role_start').val(),
						title  	: $("#edit_role_title").val()
					},
					function(data){
						$("#edit_role_title").val("");
		                $("#edit_role").dialog("close");
						refresh_roles_matrix("reload");
					}
				);
            },
            "<?php echo $txt["cancel_button"];?>": function() {
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
        title: "<?php echo $txt["admin_help"];?>",
        buttons: {
            "<?php echo $txt["close"];?>": function() {
                $(this).dialog("close");
            }
        },
        open: function(){
            $("#accordion").accordion({ autoHeight: false, navigation: true, collapsible: true, active: false });
        }
    });

    refresh_roles_matrix();
});

//###########
//## FUNCTION : Change the actual right of the role other the select folder
//###########
function tm_change_role(role,folder,cell_id,allowed){
    $("#div_loading").show();
    $.post(
		"sources/roles.queries.php",
		{
			type	: "change_role_via_tm",
			role	: role,
			folder	: folder,
			cell_id	: cell_id,
			allowed	: allowed
		},
		function(data){
			refresh_roles_matrix("reload");
			$("#div_loading").hide();
		}
	);
}

function delete_this_role(id,name){
	$("#delete_role_id").val(id);
	$("#delete_role_show").html(name);
    $("#delete_role").dialog("open");
}

function edit_this_role(id,name){
    $("#edit_role_id").val(id);
	$("#edit_role_show").html(name);
    $("#edit_role").dialog("open");
}


function allow_pw_change_for_role(id, value){
	$("#div_loading").show();
	//Send query
	$.post(
		"sources/roles.queries.php",
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

/**
 *
 * @access public
 * @return void
 **/
function refresh_roles_matrix(order){
	//clean up
	$("#roles_next, #roles_previous").hide();

	//manage start query
	if(order == "next"){
		var start = $('#next_role').val();
	}else if(order == "previous"){
		var start = $('#previous_role').val();
	}else if(order == "reload"){
		var start = $('#role_start').val();
	}else{
		var start = 0;
	}
	$('#role_start').val(start);

	//send query
	$.post(
		"sources/roles.queries.php",
		{
			type    : "refresh_roles_matrix",
			start    : start
		},
		function(data){
			//decrypt data
            data = $.parseJSON(data);
			$("#matrice_droits").html("");
			if (data.new_table != "") {
				$("#matrice_droits").html(data.new_table);
				if(data.next < data.all) {
					$("#roles_next").show();
				}
				if(data.next >= 9 && data.previous >= 0) {
					$("#roles_previous").show();
				}
				//manage next & previous arrows
				$('#next_role').val(data.next);
				$('#previous_role').val(data.previous);
			}
		}
	);
}
</script>