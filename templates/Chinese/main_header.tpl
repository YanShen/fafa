<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset={$conf.charset}">
    <meta name="description" content="">
    <link rel="stylesheet" type="text/css" href="{$conf.template_html}/style.css"> 
    <script>
		{literal}
      function changePageTitle() {
        if(document.all["pageTitle"]) {
	       	document.all["pageTitle"].display = "block";
	    }
      }
		{/literal}
    </script>
  </head>
  <body background="{$conf.images_html}/" onload="if(changePageTitle)changePageTitle();">
<table><tr><td>
<div class="menulayout">
    <div>
	<script src="http://connect.facebook.net/zh_TW/all.js#xfbml=1"></script>
    <div class="fb-like" style="text-align:center" data-href="http://{$smarty.server.HTTP_HOST}{$smarty.server.REQUEST_URI}" data-send="true" data-layout="button_count" data-width="100" data-show-faces="true" data-action="recommend"></div>
	</div>
    <div class="menuitem"><a href="http://www.facebook.com/5ifafa88">粉絲團</a></div>
</div>
</tr></td><table>
<!-- MAIN CONTENT AREA -->

