<?php
include("includes/cfg.php");

echo '
<div id="slider">
    <ul>                
        <li><a href="#"><img src="images/fond_ident.png" alt="" /></a></li>
        <li><a href="#"><img src="images/fond_main.png" alt="" /></a></li>
        <li><a href="#"><img src="images/fond_passwords.png" alt="" /></a></li>
        <li><a href="#"><img src="images/fond_contextmenu.png" alt="" /></a></li>
        <li><a href="#"><img src="images/fond_mng.png" alt="" /></a></li>            
    </ul>
</div>

<div class="col_mid_left">
    <h2 class="line">What is cPassMan?</h2>            
    <div style="">
        cPassMan is a tool dedicated for <span class="evidence">managing passwords</span> in a collaborative way. 
        It is especially designed to provide passwords access security for allowed people. 
        This makes cPassMan really usefull in a Buisiness/Enterprise environment and will provide to IT a powerfull 
        and easy tool for <span class="evidence">customizing passwords access depending on the user\'s role</span>.
    </div><br />
    <span class="ui-icon ui-icon-star" style="float: left; margin-right: .3em;">&nbsp;</span><a href="more">Read more about cPassMan</a>
</div>
<div class="col_mid_right">
    <h2 class="line">Using cPassMan will ...</h2>            
    <div style="">
        <span class="ui-icon ui-icon-tag" style="float: left; margin-right: .3em;">&nbsp;</span>permits you to stop searching passwords all other the company,<br />
        <span class="ui-icon ui-icon-tag" style="float: left; margin-right: .3em;">&nbsp;</span>permits to get ride of all small txt files containing passwords,<br />
        <span class="ui-icon ui-icon-tag" style="float: left; margin-right: .3em;">&nbsp;</span>enhance the passwords follow-up,<br />
        <span class="ui-icon ui-icon-tag" style="float: left; margin-right: .3em;">&nbsp;</span>offers you the possibility to grant access to passwords,<br />
        <span class="ui-icon ui-icon-tag" style="float: left; margin-right: .3em;">&nbsp;</span>assure you that only allowed users will access to passwords.
    </div>
</div>

<div class="line" style="float:left;margin:15px;width:670px;">&nbsp;</div>

<div class="col_large">
    <h2>Main Functions</h2>            
    <div style="text-align:justify;">
        <span class="ui-icon ui-icon-pin-w" style="float: left; margin-right: .3em;">&nbsp;</span>Define your own Tree structure of passwords Groups,<br />
        <span class="ui-icon ui-icon-pin-w" style="float: left; margin-right: .3em;">&nbsp;</span>Define the users Functions you want. Each Function allows access to specific Groups,<br />
        <span class="ui-icon ui-icon-pin-w" style="float: left; margin-right: .3em;">&nbsp;</span>Associate each user to specific Functions, and customize his/hers groups access,<br />
        <span class="ui-icon ui-icon-pin-w" style="float: left; margin-right: .3em;">&nbsp;</span>Create the matrix traceability for Functions vs Groups,<br />
        <span class="ui-icon ui-icon-pin-w" style="float: left; margin-right: .3em;">&nbsp;</span>Audit trail on passwords,<br />
        <span class="ui-icon ui-icon-pin-w" style="float: left; margin-right: .3em;">&nbsp;</span>All passwords are encrypted in database,<br />
        <span class="ui-icon ui-icon-pin-w" style="float: left; margin-right: .3em;">&nbsp;</span>Clipboard copy of password and login for quick utilization,<br />
        <span class="ui-icon ui-icon-pin-w" style="float: left; margin-right: .3em;">&nbsp;</span>Define minimal password complexity for each Group,<br />
        <span class="ui-icon ui-icon-pin-w" style="float: left; margin-right: .3em;">&nbsp;</span>Auto log-off system when session is over,<br />
        <span class="ui-icon ui-icon-pin-w" style="float: left; margin-right: .3em;">&nbsp;</span>Deploy a strategy for "renewal passwords",<br />
        <span class="ui-icon ui-icon-pin-w" style="float: left; margin-right: .3em;">&nbsp;</span>All actions are launched using a contextual Menu,<br />
        <span class="ui-icon ui-icon-pin-w" style="float: left; margin-right: .3em;">&nbsp;</span>Passwords can be restricted to a set of user,<br />
        <span class="ui-icon ui-icon-pin-w" style="float: left; margin-right: .3em;">&nbsp;</span>Send emails to inform other users about a creation or a modification.<br />
    </div>
    
    <br />
    <h2>Rockin\' blog updates</h2> 
    <div style="text-align:justify;">';
        $res = mysql_query("SELECT * FROM website_blog ORDER BY id desc LIMIT 0, 5");
        while ( $data = mysql_fetch_array($res) ){
            $madate = explode(' ',$data['date']);
            echo '
            <div class="blog"><span>'.$madate[0].' '.$madate[1].', '.$madate[2].'</span> - <a href="blog-'.$data['id'].'.'.prepare_title($data['sujet']).'">'.$data['sujet'].'</a></div>';
        }
        echo '
    </div>
</div>

<div class="col_small">
    <br />
    <div class="ui-state-focus ui-corner-all" style="padding:3px;text-align:center;">
        See the Gallery<br />
        <a class="lightbox-json" href="images/album/identification.png" title="Connection page"><img src="images/album/ts_identification.png"  alt="" /></a>
        <a class="lightbox-json" href="images/album/home.png" title="Home page"><img src="images/album/ts_home.png"  alt="" /></a>
    </div>
    <br />
    <div class="ui-state-focus ui-corner-all" style="padding:3px;text-align:center;">
        Subsribe to feed<br />
        <a href="http://feeds.feedburner.com/cPassMan" target="_blank"><img src="images/rss_icon.png"  alt="" /></a>
    </div>
    <br />
    <div class="ui-state-focus ui-corner-all" style="padding:3px;text-align:center;">
        Are you using it too? <br />
        <div style="margin:auto;width:60px;"><script type="text/javascript" src="http://www.ohloh.net/p/468653/widgets/project_users.js?style=gray"></script></div>
    </div>
    <br />
    <div class="ui-state-focus ui-corner-all" style="padding:3px;text-align:center;">
        cPassMan is hosted at <br />        
        <a href="http://code.google.com/p/cpassman/" target="_blank"><img src="http://www.gstatic.com/codesite/ph/images/google_code_tiny.png" alt="" /></a>
    </div>    
</div>


<div class="col_mid_left">
    <h2 class="line">Free to use</h2>            
    <div style="">
        cPassMan is <span class="evidence">under Creative Common</span> rights. <br />
        It may be <span class="evidence">shared and used</span> under conditions:<br />
        <span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em; color:#FF8000;">&nbsp;</span>You must attribute the work in the manner specified by the author,<br />
        <span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em; color:#FFFFFF;">&nbsp;</span>You may not use this work for commercial purposes,<br />
        <span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em; color:#FFFFFF;">&nbsp;</span>You may not alter, transform, or build upon this work.
    </div>
    <div style="text-align:center;">
        <a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/2.0/" title="Collaborative Passwords Manager by Nils Laumaill&#233; is licensed under a Creative Commons Attribution-Noncommercial-No Derivative Works 2.0 France License"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-nd/2.0/fr/80x15.png" /></a>
    </div>
</div>
<div class="col_mid_right">
    <h2 class="line">About this project</h2>            
    <div style="">
        <span class="ui-icon ui-icon-link" style="float: left; margin-right: .3em; color:#FF8000;">&nbsp;</span><a href="http://code.google.com/p/cpassman/issues/list" target="_blank">Bugs &amp; Request</a><br />
    </div>
</div>';

echo '
<div id="pub_webhost" style="float:left;width:700px;">
<a href="http://www.000webhost.com/" onClick="this.href=\'http://www.000webhost.com/216238.html\'" target="_blank">
    <div id="pub_image"></div>
</a>
</div>

<script type="text/javascript">
    $("#pub_image").html("<div class=\'page-peel-adjuster\' style=\'width:120px; margin:auto; height: 60px; display:block; position:relative; background:url(http://www.000webhost.com/images/banners/120x60/banner1.gif) no-repeat;\'");
          
    $(".page-peel-adjuster").hover(
        function(){
            $(this).css("z-index","9999");
            $(this).css({
                width: "600px",
                height: "400px",
                background: "url(http://www.000webhost.com/images/banners/600x400/banner1.gif) top right no-repeat"
            });
        },
        function(){
            $(this).css({
                width: "120px",
                height: "60px",
                background: "url(http://www.000webhost.com/images/banners/120x60/banner1.gif) top right no-repeat"
            });
            $(this).css("z-index","1000");
        }
    );
            
    //GALLERY
    base_url = document.location.href.substring(0, document.location.href.lastIndexOf("/")+1);
    $(".lightbox-json").lightbox({
        fitToScreen: true,
        jsonData: new Array(
            {url: base_url + "images/album/identification.png", title: "User has to be identified" },
            {url: base_url + "images/album/home.png", title: "This is the Home page" },
            {url: base_url + "images/album/menu.png", title: "Possible actions using the Menu" },
            {url: base_url + "images/album/passwords.png", title: "Main page containing all Passwords" },
            {url: base_url + "images/album/groupcontextmenu.png", title: "Using the Groups contextual menu to launch actions" },
            {url: base_url + "images/album/itemscontextmenu.png", title: "Using the Items contextual menu to launch actions" },
            {url: base_url + "images/album/find.png", title: "Search Items in the global database" },
            {url: base_url + "images/album/mng_groups.png", title: "How to manage the Groups" },
            {url: base_url + "images/album/mng_functions.png", title: "How to manage the Functions" },
            {url: base_url + "images/album/mng_users.png", title: "How to manage the Users" },
            {url: base_url + "images/album/mng_views.png", title: "Specific views to IT management and passwords follow-up" }
        ),
        loopImages: true,
        imageClickClose: false,
        disableNavbarLinks: true
    });

</script>';

?>