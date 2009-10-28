<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  

  

  <head>
  	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>
      /trunk/plugins/treeview/jquery.treeview.edit.js -
      jQuery - Development
    </title>
    <link rel="stylesheet" href="http://static.jquery.com/files/rocker/css/reset.css" type="text/css" />
        <link rel="search" href="/search" />
        <link rel="help" href="/wiki/TracGuide" />
        <link rel="alternate" href="/browser/trunk/plugins/treeview/jquery.treeview.edit.js?rev=5179&amp;format=txt" type="text/plain" title="Plain Text" /><link rel="alternate" href="/export/5179/trunk/plugins/treeview/jquery.treeview.edit.js" type="text/x-javascript; charset=utf-8" title="Original Format" />
        <link rel="up" href="/browser/trunk/plugins/treeview?rev=5179" title="Parent directory" />
        <link rel="start" href="/wiki" />
        <link rel="stylesheet" href="/chrome/common/css/trac.css" type="text/css" /><link rel="stylesheet" href="/chrome/common/css/code.css" type="text/css" /><link rel="stylesheet" href="/chrome/common/css/browser.css" type="text/css" />
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        <link rel="icon" href="/favicon.ico" type="image/x-icon" />
      <link type="application/opensearchdescription+xml" rel="search" href="/search/opensearch" title="Search jQuery" />
    <link rel="stylesheet" href="http://static.jquery.com/files/rocker/css/screen.css" type="text/css" />
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js"></script>
	<script type="text/javascript" src="http://static.jquery.com/files/rocker/scripts/custom.js"></script>
	<link rel="shortcut icon" href="http://static.jquery.com/favicon.ico" type="image/x-icon" />
  </head>
  <body id="jq-interior">
	<div id="jq-siteContain">
			<div id="jq-header">
				<a id="jq-siteLogo" href="http://jquery.com/" title="jQuery Home"><img src="http://static.jquery.com/files/rocker/images/logo_jquery_215x53.gif" width="215" height="53" alt="jQuery: Write Less, Do More." /></a>
				<div id="jq-primaryNavigation">
					<ul>
						<li class="jq-jquery jq-current"><a href="http://jquery.com/" title="jQuery Home">jQuery</a></li>
						<li class="jq-plugins"><a href="http://plugins.jquery.com/" title="jQuery Plugins">Plugins</a></li>
						<li class="jq-ui"><a href="http://ui.jquery.com/" title="jQuery UI">UI</a></li>
						<li class="jq-blog"><a href="http://blog.jquery.com/" title="jQuery Blog">Blog</a></li>
						<li class="jq-about"><a href="http://docs.jquery.com/About" title="About jQuery">About</a></li>
						<li class="jq-donate"><a href="http://docs.jquery.com/Donate" title="Donate to jQuery">Donate</a></li>
					</ul>
				</div><!-- /#primaryNavigation -->
				<div id="jq-secondaryNavigation">
					<ul>
						<li class="jq-download jq-first"><a href="http://docs.jquery.com/Downloading_jQuery">Download</a></li>
						<li class="jq-documentation"><a href="http://docs.jquery.com/">Documentation</a></li>
						<li class="jq-tutorials"><a href="http://docs.jquery.com/Tutorials">Tutorials</a></li>
						<li class="jq-bugTracker jq-current"><a href="http://dev.jquery.com/">Bug Tracker</a></li>
						<li class="jq-discussion jq-last"><a href="http://docs.jquery.com/Discussion">Discussion</a></li>
					</ul>
				</div><!-- /#secondaryNavigation -->
				<h1>Bug Tracker</h1>
		<form id="jq-primarySearchForm" action="/search" method="get"><div>
        <label for="primarySearch">Search <span class="jq-jquery">Tickets</span></label>
        <input type="text" value="" title="Search jQuery" name="q" id="jq-primarySearch" />
		<button type="submit" name="go" id="jq-searchGoButton"><span>Go</span></button>
        <input type="hidden" name="wiki" value="on" />
        <input type="hidden" name="changeset" value="on" />
        <input type="hidden" name="ticket" value="on" />
      </div></form>
			</div><!-- /#header -->
			<div id="jq-content" class="jq-clearfix">
				<div id="jq-interiorNavigation">
					<div class="jq-metanav" id="jq-metanav">
						<h5>Tracker Account</h5>
				 		<div id="jq-metanav">
    <ul>
      <li class="first"><a href="/login">Login</a></li><li><a href="/about">About Trac</a></li><li><a href="/prefs">Preferences</a></li><li><a href="/wiki/TracGuide">Help/Guide</a></li><li class="last"><a href="/register">Register</a></li>
    </ul>
  </div>
					</div>
					<div class="jq-mainnav" id="jq-mainnav">
						<h5>Bug Tracker</h5>
						<div id="jq-mainnav">
    <ul>
      <li class="first"><a href="/wiki">Wiki</a></li><li><a href="/roadmap">Roadmap</a></li><li class="active"><a href="/browser">Browse Source</a></li><li><a href="/report">View Tickets</a></li><li><a href="/search">Search</a></li><li class="last"><a href="/timeline">Timeline</a></li>
    </ul>
  </div>
					</div>
				</div><!-- /#interiorNavigation -->
				<div id="jq-primaryContent">
    <div id="main">
    <div id="ctxtnav" class="nav">
      <ul>
        <li class="first"><a href="/changeset/5179/trunk/plugins/treeview/jquery.treeview.edit.js">Last Change</a></li>
        <li>
          <a href="/browser/trunk/plugins/treeview/jquery.treeview.edit.js?annotate=True&amp;rev=5179" title="Annotate each line with the last changed revision. This can be time consuming...">
            Annotate
          </a>
        </li>
        <li class="last"><a href="/log/trunk/plugins/treeview/jquery.treeview.edit.js?rev=5179">Revision Log</a></li>
      </ul>
    </div>
    <div id="content" class="browser">
      <h1>
        <a class="first" title="Go to root directory" href="/browser?rev=5179">root</a>
        <span class="sep">/</span>
        <a title="View trunk" href="/browser/trunk?rev=5179">trunk</a>
        <span class="sep">/</span>
        <a title="View plugins" href="/browser/trunk/plugins?rev=5179">plugins</a>
        <span class="sep">/</span>
        <a title="View treeview" href="/browser/trunk/plugins/treeview?rev=5179">treeview</a>
        <span class="sep">/</span>
        <a title="View jquery.treeview.edit.js" href="/browser/trunk/plugins/treeview/jquery.treeview.edit.js?rev=5179">jquery.treeview.edit.js</a>
    <span class="sep">@</span>
      <a href="/changeset/5179" title="View changeset 5179">5179</a>
  </h1>
      <div id="jumprev">
        <form action="" method="get">
          <div>
            <label for="rev">
              View revision:</label>
            <input type="text" id="rev" name="rev" value="5179" size="6" />
          </div>
        </form>
      </div>
      <div id="jumploc">
        <form action="" method="get">
          <div class="buttons">
            <label for="preselected">Visit:</label>
            <select id="preselected" name="preselected">
              <option selected="selected"></option>
              <optgroup label="branches">
                <option value="/browser/trunk">trunk</option><option value="/browser/branches/1.2">branches/1.2</option><option value="/browser/branches/1.2-fx">branches/1.2-fx</option><option value="/browser/branches/jake-dev">branches/jake-dev</option><option value="/browser/branches/joern-dev">branches/joern-dev</option><option value="/browser/branches/john-dev">branches/john-dev</option><option value="/browser/branches/kelvin-dev">branches/kelvin-dev</option><option value="/browser/branches/marc-dev">branches/marc-dev</option><option value="/browser/branches/micheil-dev">branches/micheil-dev</option><option value="/browser/branches/offset_enhancements">branches/offset_enhancements</option><option value="/browser/branches/paul-dev">branches/paul-dev</option><option value="/browser/branches/paulm-dev">branches/paulm-dev</option><option value="/browser/branches/plugins">branches/plugins</option><option value="/browser/branches/richard-dev">branches/richard-dev</option><option value="/browser/branches/sean-dev">branches/sean-dev</option><option value="/browser/branches/stefan-dev">branches/stefan-dev</option><option value="/browser/branches/tane-dev">branches/tane-dev</option><option value="/browser/branches/yehuda-dev">branches/yehuda-dev</option>
              </optgroup><optgroup label="tags">
                <option value="/browser/tags/1.0?rev=509">tags/1.0</option><option value="/browser/tags/1.0.1?rev=509">tags/1.0.1</option><option value="/browser/tags/1.0.2?rev=481">tags/1.0.2</option><option value="/browser/tags/1.0.3?rev=506">tags/1.0.3</option><option value="/browser/tags/1.0.4?rev=697">tags/1.0.4</option><option value="/browser/tags/1.1?rev=1075">tags/1.1</option><option value="/browser/tags/1.1.1?rev=1156">tags/1.1.1</option><option value="/browser/tags/1.1.2?rev=1465">tags/1.1.2</option><option value="/browser/tags/1.1.3?rev=2200">tags/1.1.3</option><option value="/browser/tags/1.1.3.1?rev=2243">tags/1.1.3.1</option><option value="/browser/tags/1.1.3a?rev=1938">tags/1.1.3a</option><option value="/browser/tags/1.1.4?rev=2862">tags/1.1.4</option><option value="/browser/tags/1.1a?rev=932">tags/1.1a</option><option value="/browser/tags/1.1b?rev=996">tags/1.1b</option><option value="/browser/tags/1.2?rev=3219">tags/1.2</option><option value="/browser/tags/1.2.1?rev=3353">tags/1.2.1</option><option value="/browser/tags/1.2.2?rev=4454">tags/1.2.2</option><option value="/browser/tags/1.2.2b?rev=4197">tags/1.2.2b</option><option value="/browser/tags/1.2.2b2?rev=4269">tags/1.2.2b2</option><option value="/browser/tags/1.2.3?rev=4663">tags/1.2.3</option><option value="/browser/tags/1.2.3a?rev=4550">tags/1.2.3a</option><option value="/browser/tags/1.2.3b?rev=4625">tags/1.2.3b</option><option value="/browser/tags/1.2.4?rev=5631">tags/1.2.4</option><option value="/browser/tags/1.2.4a?rev=5225">tags/1.2.4a</option><option value="/browser/tags/1.2.4b?rev=5589">tags/1.2.4b</option><option value="/browser/tags/1.2.5?rev=5651">tags/1.2.5</option><option value="/browser/tags/1.2.6?rev=5685">tags/1.2.6</option><option value="/browser/tags/1.3b1?rev=5993">tags/1.3b1</option><option value="/browser/tags/1.3b2?rev=6056">tags/1.3b2</option><option value="/browser/tags/plugins?rev=5956">tags/plugins</option>
              </optgroup>
            </select>
            <input type="submit" value="Go!" title="Jump to the chosen preselected path" />
          </div>
        </form>
      </div>
      <table id="info" summary="Revision info">
        <tr>
          <th scope="col">
            Revision <a href="/changeset/5179">5179</a>, <span title="1570 bytes">1.5 kB</span>
            (checked in by joern.zaefferer, <a class="timeline" href="/timeline?from=2008-04-01T19%3A31%3A18Z%2B0000&amp;precision=second" title="2008-04-01T19:31:18Z+0000 in Timeline">9 months</a> ago)
          </th>
        </tr>
        <tr>
          <td class="message searchable">
              <p>
treeview plugin: make branches removeable, first draft <br />
</p>
          </td>
        </tr>
      </table>
      <div id="preview" class="searchable">
    <table class="code"><thead><tr><th class="lineno" title="Line numbers">Line</th><th class="content"> </th></tr></thead><tbody><tr><th id="L1"><a href="#L1">1</a></th><td>(function($) {</td></tr><tr><th id="L2"><a href="#L2">2</a></th><td>    var CLASSES = $.fn.treeview.classes;</td></tr><tr><th id="L3"><a href="#L3">3</a></th><td>    var proxied = $.fn.treeview;</td></tr><tr><th id="L4"><a href="#L4">4</a></th><td>    $.fn.treeview = function(settings) {</td></tr><tr><th id="L5"><a href="#L5">5</a></th><td>        settings = $.extend({}, settings);</td></tr><tr><th id="L6"><a href="#L6">6</a></th><td>        if (settings.add) {</td></tr><tr><th id="L7"><a href="#L7">7</a></th><td>            return this.trigger("add", [settings.add]);</td></tr><tr><th id="L8"><a href="#L8">8</a></th><td>        }</td></tr><tr><th id="L9"><a href="#L9">9</a></th><td>        if (settings.remove) {</td></tr><tr><th id="L10"><a href="#L10">10</a></th><td>            return this.trigger("remove", [settings.remove]);</td></tr><tr><th id="L11"><a href="#L11">11</a></th><td>        }</td></tr><tr><th id="L12"><a href="#L12">12</a></th><td>        return proxied.apply(this, arguments).bind("add", function(event, branches) {</td></tr><tr><th id="L13"><a href="#L13">13</a></th><td>            $(branches).prev()</td></tr><tr><th id="L14"><a href="#L14">14</a></th><td>                .removeClass(CLASSES.last)</td></tr><tr><th id="L15"><a href="#L15">15</a></th><td>                .removeClass(CLASSES.lastCollapsable)</td></tr><tr><th id="L16"><a href="#L16">16</a></th><td>                .removeClass(CLASSES.lastExpandable)</td></tr><tr><th id="L17"><a href="#L17">17</a></th><td>            .find("&gt;.hitarea")</td></tr><tr><th id="L18"><a href="#L18">18</a></th><td>                .removeClass(CLASSES.lastCollapsableHitarea)</td></tr><tr><th id="L19"><a href="#L19">19</a></th><td>                .removeClass(CLASSES.lastExpandableHitarea);</td></tr><tr><th id="L20"><a href="#L20">20</a></th><td>            $(branches).find("li").andSelf().prepareBranches(settings).applyClasses(settings, $(this).data("toggler"));</td></tr><tr><th id="L21"><a href="#L21">21</a></th><td>        }).bind("remove", function(event, branches) {</td></tr><tr><th id="L22"><a href="#L22">22</a></th><td>            var prev = $(branches).prev();</td></tr><tr><th id="L23"><a href="#L23">23</a></th><td>            var parent = $(branches).parent();</td></tr><tr><th id="L24"><a href="#L24">24</a></th><td>            $(branches).remove();</td></tr><tr><th id="L25"><a href="#L25">25</a></th><td>            prev.filter(":last-child").addClass(CLASSES.last)</td></tr><tr><th id="L26"><a href="#L26">26</a></th><td>                .filter("." + CLASSES.expandable).replaceClass(CLASSES.last, CLASSES.lastExpandable).end()</td></tr><tr><th id="L27"><a href="#L27">27</a></th><td>                .find("&gt;.hitarea").replaceClass(CLASSES.expandableHitarea, CLASSES.lastExpandableHitarea).end()</td></tr><tr><th id="L28"><a href="#L28">28</a></th><td>                .filter("." + CLASSES.collapsable).replaceClass(CLASSES.last, CLASSES.lastCollapsable).end()</td></tr><tr><th id="L29"><a href="#L29">29</a></th><td>                .find("&gt;.hitarea").replaceClass(CLASSES.collapsableHitarea, CLASSES.lastCollapsableHitarea);</td></tr><tr><th id="L30"><a href="#L30">30</a></th><td>            if (parent.is(":not(:has(&gt;))") &amp;&amp; parent[0] != this) {</td></tr><tr><th id="L31"><a href="#L31">31</a></th><td>                parent.parent().removeClass(CLASSES.collapsable).removeClass(CLASSES.expandable)</td></tr><tr><th id="L32"><a href="#L32">32</a></th><td>                parent.siblings(".hitarea").andSelf().remove();</td></tr><tr><th id="L33"><a href="#L33">33</a></th><td>            }</td></tr><tr><th id="L34"><a href="#L34">34</a></th><td>        });</td></tr><tr><th id="L35"><a href="#L35">35</a></th><td>    };</td></tr><tr><th id="L36"><a href="#L36">36</a></th><td>    </td></tr><tr><th id="L37"><a href="#L37">37</a></th><td>})(jQuery);</td></tr></tbody></table>
      </div>
      <div id="help">
        <strong>Note:</strong> See <a href="/wiki/TracBrowser">TracBrowser</a>
        for help on using the browser.
      </div>
      <div id="anydiff">
        <form action="/diff" method="get">
          <div class="buttons">
            <input type="hidden" name="new_path" value="/trunk/plugins/treeview/jquery.treeview.edit.js" />
            <input type="hidden" name="old_path" value="/trunk/plugins/treeview/jquery.treeview.edit.js" />
            <input type="hidden" name="new_rev" value="5179" />
            <input type="hidden" name="old_rev" value="5179" />
            <input type="submit" value="View changes..." title="Select paths and revs for Diff" />
          </div>
        </form>
      </div>
    </div>
      <div id="altlinks">
        <h3>Download in other formats:</h3>
        <ul>
          <li class="first">
            <a href="/browser/trunk/plugins/treeview/jquery.treeview.edit.js?rev=5179&amp;format=txt">Plain Text</a>
          </li><li class="last">
            <a href="/export/5179/trunk/plugins/treeview/jquery.treeview.edit.js">Original Format</a>
          </li>
        </ul>
      </div>
    </div>
				</div><!-- /#primaryContent -->
			</div><!-- /#content -->
			<div id="jq-footer" class="jq-clearfix">
				<div id="jq-credits">
					<p id="jq-copyright">© 2008 <a href="http://ejohn.org/">John Resig</a> and the <a href="http://docs.jquery.com/Contributors">jQuery Team</a>.</p>
					<p id="jq-hosting">Hosting provided by <a href="http://mediatemple.net/" class="jq-mediaTemple">Media Temple</a></p>
				</div>
				<div id="jq-footerNavigation">
					<ul>
						<li class="jq-download jq-first"><a href="http://docs.jquery.com/Downloading_jQuery">Download</a></li>
						<li class="jq-documentation jq-current"><a href="http://docs.jquery.com/">Documentation</a></li>
						<li class="jq-tutorials"><a href="http://docs.jquery.com/Tutorials">Tutorials</a></li>
						<li class="jq-bugTracker"><a href="http://dev.jquery.com/">Bug Tracker</a></li>
						<li class="jq-discussion jq-last"><a href="http://docs.jquery.com/Discussion">Discussion</a></li>
					</ul>
				</div><!-- /#secondaryNavigation -->
			</div><!-- /#footer -->
	</div><!-- /#siteContain -->
	<script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
	<script type="text/javascript">_uacct="UA-1076265-1";urchinTracker();</script>
	</body>
</html>