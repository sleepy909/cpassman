<?php
echo '
<div class="content">
    <div id="tabs" style="font-size:11pt;">
        <ul>
            <li><a href="includes/lang_help_user.php"><span>User</span></a></li>
            <li><a href="includes/lang_help_admin.php"><span>Administration</span></a></li>
            <li><a href="includes/lang_help_install.php"><span>Installation</span></a></li>
        </ul>
        <a name="top">&nbsp;</a>
    </div>
    
</div>';
?>

<script type="text/javascript">
$(function() {
    $("#tabs").tabs({ spinner: '<img src="images/ajax-loader.gif" />'});
});
</script>


<!-- Piwik -->
<script type="text/javascript">
var pkBaseURL = (("https:" == document.location.protocol) ? "https://www.vag-technique.fr/piwik/" : "http://www.vag-technique.fr/piwik/");
document.write(unescape("%3Cscript src='" + pkBaseURL + "piwik.js' type='text/javascript'%3E%3C/script%3E"));
</script><script type="text/javascript">
try {
var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", 1);
piwikTracker.trackPageView();
piwikTracker.enableLinkTracking();
} catch( err ) {}
</script><noscript><p><img src="http://www.vag-technique.fr/piwik/piwik.php?idsite=1" style="border:0" alt=""/></p></noscript>
<!-- End Piwik Tag -->