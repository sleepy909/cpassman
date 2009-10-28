
<?php
echo '
<h2 style="margin-top:15px;">'.$txt['admin'].'</h2>

<div style="width:900px;margin-left:50px; line-height:25px;height:100%;overflow:auto;">    
    <div id="CPM_infos" style="float:left;margin-top:10px;margin-left:15px;width:500px;"></div>    
    
    <div style="float:right;width:130px;padding:10px;" class="ui-content-highlight">
        <a href="http://sourceforge.net/donate/index.php?group_id=280505"><img src="http://images.sourceforge.net/images/project-support.jpg" width="88" height="32" border="0" alt="Support This Project" /> </a>
    </div>
</div>

';
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