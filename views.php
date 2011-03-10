<?php
/**
 * @file 		views.php
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

?>
<script type="text/javascript">
$(function() {
    $("#tabs").tabs();
    $("#log_jours").datepicker({
        regional: 'fr',
        dateFormat : 'dd/mm/yy'
    });

    ListerElemDel();
});

function GenererLog(){
    LoadingPage();  //afficher image de chargement
    var data = "type=log_generate&date="+document.getElementById("log_jours").value;
    httpRequest("sources/views.queries.php",data);
}

function ListerElemDel(){
    LoadingPage();  //afficher image de chargement
    var data = "type=lister_suppression";
    httpRequest("sources/views.queries.php",data);
}

function restoreDeletedItems(){
    if ( confirm("<?php echo $txt['views_confirm_restoration'];?>") ){
        var list = "";
        $(".cb_deleted_item:checked").each(function() {
            if ( list == "" ) list = $(this).val();
            else list = list+';'+$(this).val();
        });

        var data = "type=restore_deleted__items&list="+list;
        httpRequest("sources/views.queries.php",data);
    }
}

function reallyDeleteItems(){
    if ( confirm("<?php echo $txt['views_confirm_items_deletion'];?>") ){
        var list = "";
        $(".cb_deleted_item:checked").each(function() {
            if ( list == "" ) list = $(this).val();
            else list = list+';'+$(this).val();
        });

        var data = "type=really_delete_items&list="+list;
        httpRequest("sources/views.queries.php",data);
    }
}

function displayLogs(type,page){
    LoadingPage();  //show waiting GIF
    //Show or not the column URL
    if ( type == "errors_logs" ) $("#th_url").show();
    else $("#th_url").hide();
    //launch ajax query
    var data = "type="+type+"&page="+page;
    httpRequest("sources/views.queries.php",data);
}

//This permits to launch ajax query for generate a listing of expired items
function generate_renewal_listing(){
    LoadingPage();  //show waiting GIF
    var data = "type=generate_renewal_listing&period="+document.getElementById("expiration_period").value;
    httpRequest("sources/views.queries.php",data);
}

//FUNCTION permits to generate a PDF file
function generate_renewal_pdf(){
    LoadingPage();  //show waiting GIF
    var data = "type=generate_renewal_pdf&text="+document.getElementById("list_renewal_items_pdf").value;
    httpRequest("sources/views.queries.php",data);
}
</script>

<?php
// show TABS permitting to select specific actions
echo '
<div id="tabs">
    <ul>
        <li><a href="#tabs-1">'.$txt['logs_passwords'].'</a></li>
        <li><a href="#tabs-2">'.$txt['deletion'].'</a></li>
        <li><a href="#tabs-3">'.$txt['logs'].'</a></li>
        <li><a href="#tabs-4">'.$txt['renewal_menu'].'</a></li>
    </ul>';

    //TAB 1 - Log password
    echo '
    <div id="tabs-1">
        <p>
        '.$txt['logs_1'].' : <input type="text" id="log_jours" /> <img src="includes/images/asterisk.png" onClick="GenererLog()" style="cursor:pointer;" />
        </p>
        <div id="lien_pdf" style="text-align:center; width:100%; margin-top:15px;"></div>
    </div>';

    //TAB 2 - DELETION
    echo '
    <div id="tabs-2">
        <h3>'.$txt['deletion_title'].'</h3>
        <div id="liste_elems_del" style="margin-left:30px;margin-top:10px;"></div>
    </div>';

    //TAB 3 - LOGS
    echo '
    <div id="tabs-3">
        <h3>'.$txt['logs'].' [ <a href="#" onclick="displayLogs(\'connections_logs\',1)">'.$txt['connections'].'</a> ]  [ <a href="#" onclick="displayLogs(\'errors_logs\',1)">'.$txt['errors'].'</a> ]</h3>
        <div id="div_show_system_logs" style="margin-left:30px;margin-top:10px;">
        <table>
            <thead>
                <tr>
                    <th>'.$txt['date'].'</th>
                    <th id="th_url">'.$txt['url'].'</th>
                    <th>'.$txt['label'].'</th>
                    <th>'.$txt['user'].'</th>
                </tr>
            </thead>
            <tbody id="tbody_logs">
            </tbody>
        </table>
        <div id="log_pages" style="margin-top:10px;"></div>
        </div>
    </div>';

    //TAB 4 - RENEWAL
    echo '
    <div id="tabs-4">
        '.$txt['renewal_selection_text'].'
        <select id="expiration_period">
            <option value="0">'.$txt['expir_today'].'</option>
            <option value="1month">'.$txt['expir_one_month'].'</option>
            <option value="6months">'.$txt['expir_six_months'].'</option>
            <option value="1year">'.$txt['expir_one_year'].'</option>
        </select>
        <img src="includes/images/asterisk.png" style="cursor:pointer;" alt="" onclick="generate_renewal_listing()" />
        <span id="renewal_icon_pdf" style="margin-left:15px;display:none;cursor:pointer;"><img src="includes/images/document-pdf-text.png" alt="" title="'.$txt['generate_pdf'].'" onclick="generate_renewal_pdf()" /></span>
        <div id="list_renewal_items" style="width:700px;margin:10px auto 0 auto;"></div>
        <input type="hidden" id="list_renewal_items_pdf" />
    </div>';

    echo '
</div>
';


?>