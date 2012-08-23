<html>
  <head>
  <script>
  function ajaxRequest(){
	 var activexmodes=["Msxml2.XMLHTTP", "Microsoft.XMLHTTP"] //activeX versions to check for in IE
	 if (window.ActiveXObject){ //Test for support for ActiveXObject in IE first (as XMLHttpRequest in IE7 is broken)
	  for (var i=0; i<activexmodes.length; i++){
	   try{
		return new ActiveXObject(activexmodes[i])
	   }
	   catch(e){
		//suppress error
	   }
	  }
	 }
	 else if (window.XMLHttpRequest) // if Mozilla, Safari etc
	  return new XMLHttpRequest()
	 else
	  return false
  }
  </script>
  </head>
<body background="" onload="">
Fail to post on Yahoo Groups! <br>
<div id="result" name="result"> </div>

<script>
<?php
//This javascript approach is not working in IE.
?>
function myIP() { 
    xmlhttp = new ajaxRequest(); 
 
    xmlhttp.open("GET","http://api.hostip.info/get_html.php",false); 
    xmlhttp.send(); 
 
    hostipInfo = xmlhttp.responseText.split("\n"); 
 
    for (i=0; hostipInfo.length >= i; i++) { 
        ipAddress = hostipInfo[i].split(":"); 
        if ( ipAddress[0] == "IP" ) return ipAddress[1]; 
    } 
 
    return false; 
} 


	var mygetrequest=new ajaxRequest()
	mygetrequest.onreadystatechange=function(){
	 if (mygetrequest.readyState==4){
	  if (mygetrequest.status==200 || window.location.href.indexOf("http")==-1){
	   document.getElementById("result").innerHTML=mygetrequest.responseText
	  }
	  else{
	   alert("An error has occured making the request")
	  }
	 }
	}
	var ca=myIP();
	var ap=document.location;
	mygetrequest.open("GET", "log_guest.php?ca="+ca+"&ap="+escape(ap), true)
	mygetrequest.send(null)

</script>

<?php
function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

$msg = "User-Agent".$_SERVER['HTTP_USER_AGENT'].", Client IP:".getRealIpAddr()." page: fake confirm_yahoo_groups.php. Unauthorized attempting to post on yahoo groups.";

error_log('['.date("F j, Y, g:i a e O").']'.$msg." \n", 3,  "C:\\Program Files\\Apache Group\\Apache2\\logs\\unauthorized_access.log");
?>

</body>
</html>