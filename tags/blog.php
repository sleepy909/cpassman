<script type="text/javascript">
    function PostComment(id){
        var data = "type=poster_comment&"+
        "&name="+document.getElementById("name").value+
        "&email="+document.getElementById("email").value+
        "&web="+document.getElementById("web").value+
        "&comment="+escape(document.getElementById("comment").value)+
        "&id="+id;
        httpRequest("includes/queries.php",data);
        document.getElementById('form').submit();
    }
</script>
<?php
include("includes/cfg.php");
if ( isset($_GET['page']) && !isset($_GET['article']) ){
    echo '
    <h1>cPassMan\'s Blog</h1>

    <div id="bloc_rss">
        <a href="http://feeds.feedburner.com/cPassMan" target="_blank"><img src="images/blog_rss_bloc.png"></a>
    </div>

    <hr style="width:640px;margin:auto;margin-top:20px;margin-bottom:20px;">';

    $res = mysql_query("SELECT * FROM website_blog ORDER BY id desc");
    while ( $data = mysql_fetch_array($res) ){
        //nb commentaires
        $count = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM website_comments WHERE blog_id=".$data['id']));
        $madate = explode(' ',$data['date']);
        $chaine = ereg_replace("<[^>]*>", "", $data['texte']); 
        echo '
        <div class="blog_bloc">
            <div class="blog_bloc_1">
                <div style="font-size:20pt;text-align:center;margin:12px 0px 0px 0px;line-height:12pt;color:#FF8000;">'.$madate[0].'</div>
                <div style="font-size:10pt;margin-top:-3px;text-align:center;padding-top:15px;color:#161616;line-height:10pt;">'.$madate[1].'<br />'.$madate[2].'</div>
            </div>
            <div style="position:absolute;margin-left:600px;margin-top:-10px;background:url(images/blog_bubble.png);width:40px;height:34px;">
                 <div style="color:#585858;font-size:15pt;text-align:center;margin-top:5px;">'.$count[0].'</div>
            </div>
            <div class="blog_bloc_2">
                <h2 style="margin-bottom:-5px;"><a href="blog-'.$data['id'].'.'.prepare_title($data['sujet']).'">'.$data['sujet'].'</a></h2>
                '.substr($chaine,0,180).' ...
            </div>
            <br /><a class="back_to_top" href="blog-'.$data['id'].'.'.prepare_title($data['sujet']).'"><span class="ui-icon ui-icon-extlink" style="float: left; margin-right: .3em; color:#FF8000;">&nbsp;</span>More</a><br />
        </div>

        <hr style="width:640px;margin:auto;margin-top:20px;margin-bottom:20px;">';
    }
}
else if ( isset($_GET['article']) ){
    $data = mysql_fetch_array(mysql_query("SELECT * FROM website_blog WHERE id =".$_GET['article']));
    echo '
    <form id="form"></form>
    <h1>'.$data['sujet'].'</h1>
    <div id="blog_article">
        <div style="float:left;width:420px;color:#FF8000;font-style:italic;">
        Written by '.$data['auteur'].' on '.$data['date'].'
        </div>
        <div style="float:right;width:250px;text-align:right;">&nbsp;
        <a target="_blank" href="http://digg.com/submit?phase=2&url=http://cpassman.net23.net/blog-'.$data['id'].'.'.prepare_title($data['sujet']).'"><img src="images/ico_digg.png" /></a>&nbsp;
        <a target="_blank" href="http://del.icio.us/post?url=http://cpassman.net23.net/blog-'.$data['id'].'.'.prepare_title($data['sujet']).'&jump=no"><img src="images/ico_delicious.png" /></a>&nbsp;
        <a target="_blank" href="http://reddit.com/submit?url=http://cpassman.net23.net/blog-'.$data['id'].'.'.prepare_title($data['sujet']).'"><img src="images/ico_reddit.png" /></a>&nbsp;
        <a target="_blank" href="http://www.stumbleupon.com/submit?url=http://cpassman.net23.net/blog-'.$data['id'].'.'.prepare_title($data['sujet']).'"><img src="images/ico_stumbleupon.png" /></a>&nbsp;
        <a target="_blank" href="http://twitthis.com/twit?url=http://cpassman.net23.net/blog-'.$data['id'].'.'.prepare_title($data['sujet']).'"><img src="images/ico_twitter.png" /></a>&nbsp;
        <a target="_blank" href="http://feeds.feedburner.com/cPassMan" target="_blank"><img src="images/ico_rss.png" /></a>&nbsp;
        </div><br />
        <hr style="width:660px;margin:20px 0px 20px 0px ;">
        <p style="float:right; margin:-17px 5px 5px 10px;">
        <script type="text/javascript" src="http://tweetmeme.com/i/scripts/button.js"></script>
        </p>
        <p style="text-align:justify;">
        '.nl2br($data['texte']).'
        </p>
        
        <hr style="width:660px;margin:20px 0px 20px 00px ;">
        <h2>Comments</h2>
        <div id="comments" style="">';
            $count = mysql_fetch_row(mysql_query("SELECT COUNT(*) FROM website_comments WHERE blog_id=".$_GET['article']." ORDER BY id desc"));
            if ( $count[0] > 0 ){
                $res = mysql_query("SELECT * FROM website_comments WHERE blog_id=".$_GET['article']." ORDER BY id desc");
                while ( $data = mysql_fetch_array($res) ){
                    echo '
                    <div style="border-bottom:1px solid #E2E2E2;padding-bottom:5px;">
                    <font size="2pt"><i><a href="'.$data['web'].'" target="_blank" title="visit">'.$data['name'].'</a> on '.date("d M, Y",$data['date']).' wrote:</i></font><br />
                    '.nl2br($data['comment']).'
                    </div>';
                }
            }else{
                echo 'Be the first one to post a Comment';
            }
            echo '
        </div>
        
        <hr style="width:660px;margin:20px 0px 20px 00px ;">
        <h2>Post a Comment</h2>
        <div id="your_comment">
            <label class="blog_comment" style="padding:5px;">Name</label>
            <input type="text" id="name" style="padding:5px; font-size:14pt; width:470px; " />
            <br /><br />
            
            <label class="blog_comment" style="padding:5px;">Email (hidden)</label>
            <input type="text" id="email" style="padding:5px; font-size:14pt; width:470px;" />
            <br /><br />
            
            <label class="blog_comment" style="padding:5px;">Web</label>
            <input type="text" id="web" style="padding:5px; font-size:14pt; width:470px;" />
            <br /><br />
            
            <label class="blog_comment" style="padding:5px;">Comment</label>
            <textarea id="comment" style="padding:5px; font-size:12pt; width:470px; height:100px;font-family: \"trebuchet MS\",sans-serif;"></textarea>
            <br />
            
            <p style="text-align:center;">
                <input type="button" id="but_send_comment" onclick="PostComment(\''.$_GET['article'].'\')" style="padding:3px;cursor:pointer;font-size:20px;" class="ui-state-default ui-corner-all" value="Post Comment" />
            </p>
            
        </div>
        
        <br /><br />
        <a class="back_to_top" href="blog"><span class="ui-icon ui-icon-extlink" style="float: left; margin-right: .3em; color:#FF8000;">&nbsp;</span>Back to Blog</a>
    </div>
    
    <script type="text/javascript">
    //BUTTON
    $("#but_send_comment").hover(
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
    ';
}
?>
