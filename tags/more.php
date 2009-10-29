<?php
echo '
<div id="gallery">
    <div class="unique_col">
        <h2 class="line">What Keywords cPassMann uses?</h2>
        
        <span class="my_h3">Groups</span>&nbsp;&nbsp;
        cPassMan is based on a Tree structure made of Groups. This permits <span class="evidence">to categorized all passwords you have to manage</span>. It is possible to define as many groups level as wanted.
        <span class="evidence">Only Administrators and Managers car create, modify or delete a Group</span>.
        <br />
        <span class="my_h3">Items</span>&nbsp;&nbsp;
        In each group can be stored <span class="evidence">as many Items as possible</span>. An Item contains at least a title and a password.
        Any one can create a new Item in the Group he/she has access to. <span class="evidence">Only the creator can modify or delete an Item</span> (an Administrator or a Manager has also this right).
        Each Item can also include a description, a login, an url and a restriction list.
        <br />
        <span class="my_h3">Functions</span>&nbsp;&nbsp;
        A Function is <span class="evidence">equivalent to a user role</span>. For example, it can "developper", "manager", "IT", ... 
        For each Function, <span class="evidence">the Administrator can define what are the Groups accessible or not</span>. So when creating a new user, it has just to be associated to one or several Functions. Depending on that, the user will only access to the Groups he/she is authorized.
    </div>
    
    <div style="float:left;width:700px;">&nbsp</div>
    
    <div class="col_mid_left">
        <h2 class="line">Home page</h2>            
        <div style="">
            This is the home page which gives the user some information about his/hers account.<br />
            <span class="ui-icon ui-icon-lightbulb" style="float: left; margin-right: .3em;">&nbsp;</span><span class="notice">Notice the counter before the session expiration, and the days number before connection password expires.</span>
            <div class="img_center" style="width:250px;">
                <a class="lightbox" rel="gallery" href="images/album/identification.png" title="Connexion page">
                    <img src="images/album/t_identification.png"  alt="" />
                </a><br />
                <a class="lightbox" rel="gallery" href="images/album/home.png" title="Home page">
                    <img src="images/album/t_home.png"  alt="" />
                </a>
            </div>
        </div>
    </div>
    <div class="col_mid_right">
        <h2 class="line">Passwords page</h2>            
        <div style="">
            This page is the most important for the user because it <span class="evidence">contains all the items and passwords</span> he/she needs. 
            With the Tree structure, just click on the needed Group, choose the item in the full list and see the password and other information.
            It\'s in that page, that users may create and modify Items.<br />
            <span class="ui-icon ui-icon-lightbulb" style="float: left; margin-right: .3em;">&nbsp;</span><span class="notice">Notice that all possible actions are accessible using a contextual menu.</span><br />
            <span class="ui-icon ui-icon-lightbulb" style="float: left; margin-right: .3em;">&nbsp;</span><span class="notice">User can only have access to authorized Groups. All other are not clickable in Tree structure.</span>
            <div class="img_center" style="width:204px;">
                <a class="lightbox" rel="gallery" href="images/album/passwords.png" title="Passwords page">
                    <img src="images/album/t_passwords.png"  alt="" />
                </a>
            </div>
        </div>
    </div>
    
    <div style="float:left;width:700px;">&nbsp</div>
    
    <div class="col_mid_left">
        <h2 class="line">Search page</h2>            
        <div style="">
            This page contains all passwords existing in the datebase. So that the user can navigate through all of them.<br />
            <span class="ui-icon ui-icon-lightbulb" style="float: left; margin-right: .3em;"></span><span class="notice">Notice that the search field is dynamical and the result is immediatly displayed.</span><br />
            <span class="ui-icon ui-icon-lightbulb" style="float: left; margin-right: .3em;"></span><span class="notice">If user is not allowed to see the password, a specific symbol will be displayed instead of the text.</span>
            <div class="img_center" style="width:250px;">
                <a class="lightbox" rel="gallery" href="images/album/find.png" title="Search page">
                    <img src="images/album/t_find.png"  alt="" />
                </a>
            </div>
        </div>
    </div>
    <div class="col_mid_left">
        <h2 class="line">Manage Groups</h2>            
        <div style="">
            This page permits to manage Groups. Administrator car create, modify or delete Groups. He/She can also <span class="evidence">define the minimal complexity level for passwords</span>.<br />
            <span class="ui-icon ui-icon-lightbulb" style="float: left; margin-right: .3em;"></span><span class="notice">It is possible to define if you authorize a creation or modification with a password complexity level that is not enought complex.<br /></span>
            <span class="ui-icon ui-icon-lightbulb" style="float: left; margin-right: .3em;"></span><span class="notice">Notice that modification are done inside the table using Ajax without any page reloading.</span>
            <div class="img_center" style="width:168px;">
                <a class="lightbox" rel="gallery" href="images/album/mng_groups.png" title="Groups management page">
                    <img src="images/album/t_mng_groups.png"  alt="" />
                </a>
            </div>
        </div>
    </div>
    
    <div style="float:left;width:700px;">&nbsp</div>
        
    <div class="col_mid_right">
        <h2 class="line">Manage Users</h2>            
        <div style="">
            This page permits the Administrator to manage the users allowed to connect to the tool. 
            It is possible to associate each <span class="evidence">users to several Functions (roles)</span>, to force some other Groups to be authorized or forbidden.<br />
            <span class="ui-icon ui-icon-lightbulb" style="float: left; margin-right: .3em;"></span><span class="notice">Select here if user is an Administrator or a Manager.</span>
            <br /><span class="ui-icon ui-icon-lightbulb" style="float: left; margin-right: .3em;"></span><span class="notice">And Define his/hers email adresse, or change his/hers conection password.</span>
            <div class="img_center" style="width:173px;">
                <a class="lightbox" rel="gallery" href="images/album/mng_users.png" title="Users management page">
                    <img src="images/album/t_mng_users.png"  alt="" />
                </a>
            </div>
        </div>
    </div>
    <div class="col_mid_right">
        <h2 class="line">Manage Functions</h2>            
        <div style="">
            This page permits to define the existing Functions (or users roles). For each Function an Administrator defines, it is possible to define what are the <span class="evidence">Groups that will be authorized or not</span>.
            So that a user associated to this Function will only access to the groups defined in the related Function.<br />
            <span class="ui-icon ui-icon-lightbulb" style="float: left; margin-right: .3em;"></span><span class="notice">This permits a very quick deployment of the tool.</span>
            <div class="img_center" style="width:181px;">
                <a class="lightbox" rel="gallery" href="images/album/mng_functions.png" title="Functions management page">
                    <img src="images/album/t_mng_functions.png"  alt="" />
                </a>
            </div>
        </div>
    </div>
    
</div>';

?>
<script>
$(".lightbox").lightbox({
    fitToScreen: true,
    imageClickClose: false
});
</script>