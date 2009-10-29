<?php
echo '<div class="help_main">

    <h3>Requirements</h3>
    <div class="help_div">
        You need to check that your server has:<br />
        <span class="ui-icon ui-icon-pin-w" style="float: left; margin-right: .3em;">&nbsp;</span>Apache,<br />
        <span class="ui-icon ui-icon-pin-w" style="float: left; margin-right: .3em;">&nbsp;</span>MySQL,<br />
        <span class="ui-icon ui-icon-pin-w" style="float: left; margin-right: .3em;">&nbsp;</span>PHP5,<br />
        <span class="ui-icon ui-icon-pin-w" style="float: left; margin-right: .3em;">&nbsp;</span>PHP extension "mcrypt" enabled.<br />
    </div>
    
    <h3>Operations</h3>
    <div class="help_div">
        <span class="ui-icon ui-icon-arrowthick-1-e" style="float: left; margin-right: .3em;">&nbsp;</span>Download last package,<br />
        <span class="ui-icon ui-icon-arrowthick-1-e" style="float: left; margin-right: .3em;">&nbsp;</span>Unzip package into a temporary folder,<br />
        <span class="ui-icon ui-icon-arrowthick-1-e" style="float: left; margin-right: .3em;">&nbsp;</span>Open folder <i>/cpassman/install</i>,<br />
        <span class="ui-icon ui-icon-arrowthick-1-e" style="float: left; margin-right: .3em;">&nbsp;</span>If you want to change the prefix given for each table then open file <i>cpassman_db_creation.sql</i> and replace "cpm_" with your wanted prefix,<br />
        <span class="ui-icon ui-icon-arrowthick-1-e" style="float: left; margin-right: .3em;">&nbsp;</span>Now customize file "settings.php",<br />
        <span class="ui-icon ui-icon-arrowthick-1-e" style="float: left; margin-right: .3em;">&nbsp;</span>Copy file "settings.php" into <i>/cpassman/includes</i> folder,<br />
        <span class="ui-icon ui-icon-arrowthick-1-e" style="float: left; margin-right: .3em;">&nbsp;</span>Upload folder <i>/cpassman</i> in your www server location,<br />
        <span class="ui-icon ui-icon-arrowthick-1-e" style="float: left; margin-right: .3em;">&nbsp;</span>Open your database administration (such as PhpMyAdmin),<br />
        <span class="ui-icon ui-icon-arrowthick-1-e" style="float: left; margin-right: .3em;">&nbsp;</span>Create a database "cpassman" if wanted,<br />
        <span class="ui-icon ui-icon-arrowthick-1-e" style="float: left; margin-right: .3em;">&nbsp;</span>In the selected database, import file <i>/cpassman/install/cpassman_db_creation.sql</i><br />
        <span class="ui-icon ui-icon-arrowthick-1-e" style="float: left; margin-right: .3em;">&nbsp;</span>You can now connect to <i>http://your_domain/cpassman</i>,<br />
        <span class="ui-icon ui-icon-arrowthick-1-e" style="float: left; margin-right: .3em;">&nbsp;</span>The by default login/password is <b>admin</b>.<br />
    </div>
</div>';
?>