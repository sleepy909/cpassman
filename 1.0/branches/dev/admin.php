
<?php
echo '
<h3 style="margin-top:15px;">'.$txt['admin_info'].'</h3>

<div id="CPM_infos" style="margin-top:10px;margin-left:15px;"></div>';
?>
<script type="text/javascript">
    function LoadCPMInfo(){
        var data = "type=cpm_status";
        httpRequest("sources/admin.queries.php",data);
    }
    
    $(function() {
        LoadCPMInfo();
    });
</script>