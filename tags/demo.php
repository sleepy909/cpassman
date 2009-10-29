<?php
echo '
<div class="content">
    <h2 class="line">Want to test ?</h2>
    <div class="">
        <p>
        The Demo will be opened in a new window.
        </p>
        <p>
        The existing accounts are :<br />
        <span class="ui-icon ui-icon-person" style="float: left; margin-right: .3em;">&nbsp;</span>Administrator ; login = <b>admin</b> ; pw = <i>test</i><br />
        <span class="ui-icon ui-icon-person" style="float: left; margin-right: .3em;">&nbsp;</span>Manager ; login = <b>manager</b> ; pw = <i>test</i><br />
        <span class="ui-icon ui-icon-person" style="float: left; margin-right: .3em;">&nbsp;</span>User ; login = <b>user</b> ; pw = <i>test</i><br />
        </p>
        <p style="text-align:center;">
            <input type="button" id="but_launch_demo" onclick="javascript:window.open(\'http://www.vag-technique.fr/divers/cpassman/\')" style="padding:3px;cursor:pointer;font-size:20px;" class="ui-state-default ui-corner-all" value="Launch Demo" />
        </p>
        <br />
        <p style="text-align:center;font-style:italic;">
        Please notice that the demo is hosted on another domain for technical reasons.
        </p>
    </div>
    
</div>';
?>
<script type="text/javascript">
//BUTTON
$('#but_launch_demo').hover(
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