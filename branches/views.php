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

function restaurerItem(id){
    if ( confirm("<?php echo $txt['views_confirm_restoration'];?>") ){
        var data = "type=restaurer_item&id="+id;
        httpRequest("sources/views.queries.php",data);
    }
}
</script>

<?php
echo '
<div id="tabs">
    <ul>
        <li><a href="#tabs-1">'.$txt['logs'].'</a></li>
        <li><a href="#tabs-2">'.$txt['deletion'].'</a></li>
    </ul>
    <div id="tabs-1">
        <p>
        '.$txt['logs_1'].' : <input type="text" id="log_jours" /> <img src="includes/images/asterisk.png" onClick="GenererLog()" style="cursor:pointer;" />
        </p>
        <div id="lien_pdf" style="text-align:center; width:100%; margin-top:15px;"></div>
    </div>
    <div id="tabs-2">
        <h3>'.$txt['deletion_title'].'</h3>
        <div id="liste_elems_del" style="margin-left:30px;margin-top:10px;"></div>
    </div>
</div>
';


?>
