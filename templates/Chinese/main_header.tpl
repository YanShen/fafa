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
<!-- MAIN CONTENT AREA -->

