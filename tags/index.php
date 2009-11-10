<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
session_start();
$debug = 1;

//Manage Language
if ( !isset($_SESSION['user_language']) ){
    if ( isset($_POST['language']) ) $_SESSION['user_language'] = $_POST['language'];
    else $_SESSION['user_language'] = "french";
}else{
    if ( isset($_POST['language']) ) $_SESSION['user_language'] = $_POST['language'];
}
include('includes/lang_'.$_SESSION['user_language'].'.php'); 
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>Collaborative Passwords Manager</title>
        <meta name="google-site-verification" content="YfC2qQYourPrV8CdqGQJ7n-3Auiy1wpadO1FbsbHk7A" />
        <meta name="msvalidate.01" content="B453C78B9AF1E605B9A61477F3F3015B" />
        <META name="y_key" content="9b3352950a5c9fd1" />
        
        <META NAME="Identifier-URL" CONTENT="http://cpassman.net23.net" />
        <META NAME="Keywords" CONTENT="mot de passe,password,sécurité,security,collaborative,IT,company,buisiness,entreprise" />
        <META NAME="Publisher" CONTENT="Nils Laumaillé" />
        <META NAME="Reply-to" CONTENT="nils@cpassman.net23.net" />
        <META NAME="Revisit-After" CONTENT="30 days" />
        <META NAME="Robots" CONTENT="all" />
        <META NAME="GOOGLEBOT" CONTENT="NOARCHIVE" /> 
        
        <link rel="stylesheet" href="includes/style.css" type="text/css" />
        
        <script type="text/javascript" src="includes/jquery-ui/js/jquery-1.3.2.min.js"></script>
        <script type="text/javascript" src="includes/jquery-ui/js/jquery-ui-1.7.2.custom.min.js"></script>
        <link rel="stylesheet" href="includes/jquery-ui/css/ui-lightness/jquery-ui-1.7.2.custom.css" type="text/css" />
        
        <script type="text/javascript" src="includes/lavalamp/jquery.easing.1.3.js"></script>
        <script type="text/javascript" src="includes/lavalamp/jquery.lavalamp.1.3.2-min.js"></script>
        
        <script type="text/javascript" src="includes/js/easySlider.js"></script> 
        
        <script type="text/javascript" src="includes/lightbox/jquery.lightbox.js"></script>
        <link rel="stylesheet" type="text/css" href="includes/lightbox/lightbox.css" media="screen" />
        
        <script type="text/javascript" src="includes/js/functions.js"></script>
        
        <script type="text/javascript">
        $(document).ready(function(){    
            $("#slider").easySlider({
                auto: true, 
                continuous: true,
                prevText: '',
                nextText: '',
                pause: 2500
            });
            
            //MENU
            var page
            var base_url = document.location.href.substr(document.location.href.lastIndexOf("/")+1,4);
            if ( base_url == "" || base_url == "home" ) page = 0;
            else if ( base_url == "more" ) page = 1;
            else if ( base_url == "demo" ) page = 2;
            else if ( base_url == "help" ) page = 3;
            else if ( base_url == "blog" ) page = 4;
            if ( document.location.href.lastIndexOf("/forum") > 0 ) page = 5;
            $('#lavaLampBasicImage').lavaLamp({
                fx: "easeOutExpo",
                speed: 800,
                startItem: page
            });
            
            $('#contactme').click(function() {
                $('#contact').dialog('open');
            })
            
            var contact_name = $("#contact_name"),
            contact_email = $("#contact_email"),
            contact_message = $("#contact_message"),
            allFields = $([]).add(contact_name).add(contact_email).add(contact_message),
            tips = $("#validateTips");

            function updateTips(t) {
                tips.text(t).effect("highlight",{},1500);
            }

            function checkLength(o,n,min,max) {
                if ( o.val().length > max || o.val().length < min ) {
                    o.addClass('ui-state-error');
                    updateTips("Length of " + n + " must be between "+min+" and "+max+".");
                    return false;
                } else {
                    return true;
                }
            }
            
            function checkRegexp(o,regexp,n) {
                if ( !( regexp.test( o.val() ) ) ) {
                    o.addClass('ui-state-error');
                    updateTips(n);
                    return false;
                } else {
                    return true;
                }
            }

            
            $("#contact").dialog({
                bgiframe: true,
                autoOpen: false,
                height: 500,
                width:600,
                modal: true,
                buttons: {
                    'Send email': function() {
                        var bValid = true;
                        allFields.removeClass('ui-state-error');

                        bValid = bValid && checkLength(contact_name,"name",3,16);
                        bValid = bValid && checkLength(contact_email,"email",6,80);
                        bValid = bValid && checkLength(contact_message,"message",6,1000);

                        bValid = bValid && checkRegexp(contact_email,/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i,"eg. ui@jquery.com");
                        
                        if (bValid) {
                            var data = "type=send_email&"+
                            "&name="+contact_name.val()+
                            "&email="+contact_email.val()+
                            "&message="+escape(contact_message.val());
                            httpRequest("includes/queries.php",data);
                            $(this).dialog('close');
                        }
                    },
                    Cancel: function() {
                        $(this).dialog('close');
                    }
                },
                close: function() {
                    allFields.val('').removeClass('ui-state-error');
                }
            });

        });
        
            

        
        function ChangePage(page){
            alert(page);   
        }
        </script>
    </head>
    
    <body>
    <div id="global">
<?php
## HEADER ##
echo '
    <div id="top">
        <div id="logo"><img src="images/logo.png" /></div>
        
        <div id="title">Collaborative Passwords Manager</div>
        <!--         
        <div style="float:right;margin-left:30px;margin-top:12px;">
            <img src="images/flag_fr.png" style="cursor:pointer;" onclick="ChangeLanguage(\'french\')" />
            <img src="images/flag_us.png" style="cursor:pointer;" onclick="ChangeLanguage(\'english\')" />
        </div>
        -->
    </div>';
    
## MENU ##
echo '
    <div id="menu_bar">
        <ul id="lavaLampBasicImage" class="lamp ui-corner-top">
            <li><a href="home">Home</a></li>
            <li><a href="more">More about</a></li>
            <li><a href="demo">Demo</a></li>
            <li><a href="help">Help</a></li>
            <li><a href="blog">Blog</a></li>
            <li><a href="forum">Forum</a></li>
        </ul>
    </div>';

## MAIN PAGE ##
echo '
    <div id="center" class="ui-corner-bottom">'; 
        if ( isset($_GET['page']) ){
            if ( $_GET['page'] == "home" || empty($_GET['page']) ) include('home.php');
            else if ( $_GET['page'] == "demo" ) include('demo.php');
            else if ( $_GET['page'] == "help" ) include('help.php');
            else if ( $_GET['page'] == "more" ) include('more.php');
            else if ( $_GET['page'] == "blog" ) include('blog.php');
            else if ( $_GET['page'] == "forum" ) include('forum.php');
            else if ( $_GET['page'] == "rss" ) include('includes/create-xml-rss.php');
        }else
            include('home.php');
    echo '
        <div style="float:left;margin-left:116px; margin-bottom:5px;margin-top:40px;">';
            ?>
            <script type="text/javascript"><!--
            google_ad_client = "pub-6796519999488555";
            /* 468x60, cpassman, date de création 20/10/09 */
            google_ad_slot = "9182995859";
            google_ad_width = 468;
            google_ad_height = 60;
            //-->
            </script>
            <script type="text/javascript"
            src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
            </script>
            <?php
        echo '
        </div>
        
        <div class="line" style="float:left;margin:5px 15px 5px 15px;width:670px;">&nbsp;</div>
        
        <div style="float:left;padding-left:15px;padding-bottom:15px;font-size:11px;width:500px;">
            Copyright 2009 © cPassMan - All rights reserved unless stated otherwise
        </div>
        <div style="float:right;text-align:right;padding-right:15px;padding-bottom:15px;font-size:11px;width:170px;">
            <a id="contactme" style="cursor:pointer;">Contact</a>
        </div>
    </div>';
    
echo '
<div id="contact" title="Send a Message">
    <p id="validateTips">All form fields are required.</p>

    <form>
    <fieldset class="contact">
        <label for="contact_name" class="contact">Name</label>
        <input type="text" name="contact_name" id="contact_name" class="text ui-widget-content ui-corner-all contact" />
        <label for="contact_email class="contact"">Email</label>
        <input type="text" name="contact_email" id="contact_email" value="" class="text ui-widget-content ui-corner-all contact" />
        <label for="contact_message" class="contact">Message</label>
        <textarea name="contact_message" id="contact_message" value="" class="text ui-widget-content ui-corner-all contact"></textarea>
    </fieldset>
    </form>
</div>';
    
?>
    </div>
    </body>
</html>

<?php
if ( $debug != 1 )
echo '
<!-- Piwik -->
<script type="text/javascript">
var pkBaseURL = (("https:" == document.location.protocol) ? "https://www.vag-technique.fr/piwik/" : "http://www.vag-technique.fr/piwik/");
document.write(unescape("%3Cscript src=\'" + pkBaseURL + "piwik.js\' type=\'text/javascript\'%3E%3C/script%3E"));
</script><script type="text/javascript">
try {
var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", 1);
piwikTracker.trackPageView();
piwikTracker.enableLinkTracking();
} catch( err ) {}
</script><noscript><p><img src="http://www.vag-technique.fr/piwik/piwik.php?idsite=1" style="border:0" alt=""/></p></noscript>
<!-- End Piwik Tag -->';
?>