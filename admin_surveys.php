<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
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

$msg = "User-Agent".$_SERVER['HTTP_USER_AGENT'].", Client IP:".getRealIpAddr()." page: fake admin_survey.php. Unauthorized attempting to list surveys.";

error_log('['.date("F j, Y, g:i a e O").']'.$msg." \n", 3,  "C:\\Program Files\\Apache Group\\Apache2\\logs\\unauthorized_access.log");
?>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF8">
    <meta name="description" content="">
	</script>
    <link rel="stylesheet" type="text/css" href="/ms/templates/Chinese/style.css"> 
    <script>
		
      function changePageTitle() {
        if(document.all["pageTitle"]) {
	       	document.all["pageTitle"].display = "block";
	    }
      }
		
    </script>
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
  <body background="" onload="if(changePageTitle)changePageTitle();">
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
<table><tr><td>
<div class="menulayout">
    <div>
	<script src="http://connect.facebook.net/zh_TW/all.js#xfbml=1"></script>
    <div class="fb-like" style="text-align:center" data-href="http://www.5ifafa.com/ms/admin_surveys.php" data-send="true" data-layout="button_count" data-width="100" data-show-faces="true" data-action="recommend"></div>
	</div>
    <div class="menuitem"><a href="http://www.facebook.com/5ifafa88">粉絲團</a></div>
</div>
</tr></td><table>
<!-- MAIN CONTENT AREA -->
﻿<script> 
surveyURLs = new Array();
			  surveyURLs[surveyURLs.length] = "/ms/latest_results_table.php?sid=5590";
								  surveyURLs[surveyURLs.length] = "/ms/latest_results_table.php?sid=5580";
								  surveyURLs[surveyURLs.length] = "/ms/latest_results_table.php?sid=5587";
								  surveyURLs[surveyURLs.length] = "/ms/latest_results_table.php?sid=5582";
				  surveyURLs[surveyURLs.length] = "/ms/latest_results_table.php?sid=5581";
				  surveyURLs[surveyURLs.length] = "/ms/latest_results_table.php?sid=5577";
						  surveyURLs[surveyURLs.length] = "/ms/latest_results_table.php?sid=5579";
				  surveyURLs[surveyURLs.length] = "/ms/latest_results_table.php?sid=5576";
								  surveyURLs[surveyURLs.length] = "/ms/latest_results_table.php?sid=5574";
				  surveyURLs[surveyURLs.length] = "/ms/latest_results_table.php?sid=5575";
						  surveyURLs[surveyURLs.length] = "/ms/latest_results_table.php?sid=5572";
						  surveyURLs[surveyURLs.length] = "/ms/latest_results_table.php?sid=5570";
																																																						  surveyURLs[surveyURLs.length] = "/ms/latest_results_table.php?sid=5463";
																								

function openNewListInNewBrowsers() {
	for(i=0; i<surveyURLs.length; i++) {
		window.open(surveyURLs[i], ""+i);
	}
}

</script>
<table align="center" cellpadding="0" cellspacing="0">
  <tr class="grayboxheader">
    <td width="14"><img src="/ms/templates/Chinese/images/box_left.gif" border="0" width="14"></td>
    <td background="/ms/templates/Chinese/images/box_bg.gif">Survey System</td>
    <td width="14"><img src="/ms/templates/Chinese/images/box_right.gif" border="0" width="14"></td>
  </tr>
</table>
<table align="center" class="bordered_table">
	<tr>
	  <td>
              [<a href="/ms/index.php">主畫面</a>]
        &nbsp;|&nbsp;[<a href="/ms/new_survey.php">建立</a>]
        &nbsp;|&nbsp;[<a href="/ms/black_list.php">黑名單</a>]
        &nbsp;|&nbsp;[<a href="/ms/block_list.php">禁止名單</a>]
      	  </td>
	</tr>
  <tr>
    <td>
      <div style="font-weight:bold;text-align:center">
         啟用的           [<a href="admin_surveys.php?activeType=0">停用的</a>]                    [<a href="admin_surveys.php?activeType=-1">全部</a>]           [<a href="#" onclick="openNewListInNewBrowsers()">開啟所有新名單</a>]
      </div>

      <div>
      <table class="list_table">
        <tr style="text-align:center; font-weight:bold; background-color:#DDDDDD; color:#666666">
          <td>#</td>
          <td>地區</td>
          <td>活動名稱</td>
      <!-- 20090710 Hide these two fields
          <td>開始日期</td>
          <td>結束日期</td>
      -->
          <td>報名狀況</td>
        </tr>
              <tr>
          <td>1.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5590">
             [台北] 飲料座談會-車馬費1500元(O-8/29) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-22 </td>
        <td> 2012-08-29 </td>
      -->
        <td align="right">
         <span style="color:red;">有新的</span>        <a href="/ms/latest_results_table.php?sid=5590">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5590">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5590" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5590&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5590" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>2.</td>
        <td> 跨區
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5585">
             [全國] 3G/3.5G無線網卡電話訪問-車馬費100元禮券(T-10/9) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-21 </td>
        <td> 2012-10-09 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5585">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5585">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5585" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5585&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5585" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>3.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5586">
             [台北] IT人員約訪-車馬費1500元(B-9/13) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-21 </td>
        <td> 2012-09-13 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5586">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5586">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5586" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5586&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5586" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>4.</td>
        <td> 跨區
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5580">
             [台北/高雄] 飮品座談會-車馬費2200元(N-9/12) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-21 </td>
        <td> 2012-09-12 </td>
      -->
        <td align="right">
         <span style="color:red;">有新的</span>        <a href="/ms/latest_results_table.php?sid=5580">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5580">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5580" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5580&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5580" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>5.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5583">
             [台北] 電動機車座談會-車馬費1200元(Un-9/12) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-21 </td>
        <td> 2012-09-12 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5583">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5583">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5583" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5583&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5583" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>6.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5584">
             [台北] 電動自行車座談會-車馬費1200元(Un-9/12) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-21 </td>
        <td> 2012-09-12 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5584">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5584">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5584" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5584&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5584" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>7.</td>
        <td> 跨區
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5587">
             [全國] vino 車主問卷填寫-車馬費500元禮券(L-9/10) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-21 </td>
        <td> 2012-09-10 </td>
      -->
        <td align="right">
         <span style="color:red;">有新的</span>        <a href="/ms/latest_results_table.php?sid=5587">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5587">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5587" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5587&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5587" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>8.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5589">
            <b><font color='red'> [台北] 貴婦愛美座談會-車馬費3500元(N-8/31)</b></font> </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-21 </td>
        <td> 2012-08-31 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5589">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5589">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5589" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5589&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5589" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>9.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5588">
            <b><font color='red'> [台北] 保養品家庭訪問-車馬費1800元(I-8/29)</b></font> </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-21 </td>
        <td> 2012-08-29 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5588">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5588">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5588" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5588&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5588" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>10.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5582">
             [台北] 乳品座談會-車馬費1000元(A-8/25) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-21 </td>
        <td> 2012-08-25 </td>
      -->
        <td align="right">
         <span style="color:red;">有新的</span>        <a href="/ms/latest_results_table.php?sid=5582">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5582">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5582" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5582&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5582" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>11.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5581">
             [台北] 影片欣賞訪問-車馬費500元(I-8/25) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-21 </td>
        <td> 2012-08-25 </td>
      -->
        <td align="right">
         <span style="color:red;">有新的</span>        <a href="/ms/latest_results_table.php?sid=5581">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5581">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5581" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5581&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5581" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>12.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5577">
            <b><font color='red'> [台北] 小資女保養品座談會-車馬費1500元(I-8/30)</b></font> </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-20 </td>
        <td> 2012-08-30 </td>
      -->
        <td align="right">
         <span style="color:red;">有新的</span>        <a href="/ms/latest_results_table.php?sid=5577">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5577">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5577" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5577&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5577" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>13.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5578">
             [台北] 數位生活座談會-車馬費1200元(G-8/30) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-20 </td>
        <td> 2012-08-30 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5578">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5578">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5578" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5578&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5578" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>14.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5579">
            <b><font color='red'> [台北] 親子同樂座談會-車馬費3500元(I-8/29)</b></font> </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-20 </td>
        <td> 2012-08-29 </td>
      -->
        <td align="right">
         <span style="color:red;">有新的</span>        <a href="/ms/latest_results_table.php?sid=5579">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5579">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5579" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5579&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5579" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>15.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5576">
             [台北] 頭痛問題訪問-車馬費2500~3500元(H-9/14) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-19 </td>
        <td> 2012-09-14 </td>
      -->
        <td align="right">
         <span style="color:red;">有新的</span>        <a href="/ms/latest_results_table.php?sid=5576">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5576">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5576" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5576&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5576" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>16.</td>
        <td> 南部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5067">
            <b><font color='red'> [高雄] 高級車車主座談會 車馬費3500~4000元(I-10/20)</b></font> </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-17 </td>
        <td> 2012-10-17 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5067">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5067">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5067" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5067&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5067" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>17.</td>
        <td> 中部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5573">
            <b><font color='red'> [中部] 日用品研究-車馬費2200元(DY-9/28)</b></font> </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-17 </td>
        <td> 2012-09-28 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5573">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5573">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5573" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5573&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5573" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>18.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5574">
             [台北] 清潔用品概念/產品測試-車馬費300~600元(N-9/17) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-17 </td>
        <td> 2012-09-17 </td>
      -->
        <td align="right">
         <span style="color:red;">有新的</span>        <a href="/ms/latest_results_table.php?sid=5574">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5574">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5574" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5574&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5574" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>19.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5575">
             [台北] 濾水器座談會-車馬費1500元(RC-9/15) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-17 </td>
        <td> 2012-09-15 </td>
      -->
        <td align="right">
         <span style="color:red;">有新的</span>        <a href="/ms/latest_results_table.php?sid=5575">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5575">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5575" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5575&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5575" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>20.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5106">
             [台北/台中/高雄] 金融產品電訪 車馬費100禮券(Z-12/31) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-16 </td>
        <td> 2012-12-31 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5106">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5106">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5106" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5106&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5106" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>21.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5572">
             [台北] 臉部皮膚保養品購物行為訪問-車馬費1000~1500元(B-9/20) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-16 </td>
        <td> 2012-09-20 </td>
      -->
        <td align="right">
         <span style="color:red;">有新的</span>        <a href="/ms/latest_results_table.php?sid=5572">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5572">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5572" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5572&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5572" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>22.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5571">
            <b><font color='red'> [台北] 染髮座談會-車馬費2000~4000元(Y-9/5)</b></font> </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-16 </td>
        <td> 2012-09-05 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5571">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5571">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5571" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5571&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5571" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>23.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5570">
             [台北] 健康座談會-車馬費1500元(G-8/26) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-16 </td>
        <td> 2012-08-26 </td>
      -->
        <td align="right">
         <span style="color:red;">有新的</span>        <a href="/ms/latest_results_table.php?sid=5570">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5570">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5570" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5570&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5570" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>24.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5568">
             [台北] vino機車車主問卷調查-車馬費800元(L-8/25) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-15 </td>
        <td> 2012-08-25 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5568">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5568">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5568" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5568&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5568" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>25.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5567">
             [台北] 泡麵座談會-車馬費1500元(N-8/29) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-14 </td>
        <td> 2012-08-29 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5567">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5567">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5567" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5567&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5567" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>26.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5566">
            <b><font color='red'> [台北] 享健康座談會-車馬費1200~1500元(X-8/27)</b></font> </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-14 </td>
        <td> 2012-08-27 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5566">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5566">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5566" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5566&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5566" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>27.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5564">
            <b><font color='red'> [台北] 保養品家庭訪問+陪伴購物-車馬費3200元(Y-9/6)</b></font> </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-13 </td>
        <td> 2012-09-06 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5564">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5564">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5564" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5564&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5564" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>28.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5565">
             [台北] 女性髮用產品座談會-車馬費1500~1800元(R-8/24) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-13 </td>
        <td> 2012-08-24 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5565">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5565">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5565" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5565&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5565" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>29.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5563">
            <b><font color='red'> [台北] 癌病患座談會-車馬費1500元(T-8/31)</b></font> </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-11 </td>
        <td> 2012-08-31 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5563">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5563">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5563" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5563&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5563" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>30.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5561">
            <b><font color='red'> [台北] 酷男座談會-車馬費1500元(I-8/29)</b></font> </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-10 </td>
        <td> 2012-08-29 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5561">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5561">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5561" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5561&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5561" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>31.</td>
        <td> 南部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5560">
            <b><font color='red'> [高雄] 居家生活座談會-車馬費1500元(T-8/24)</b></font> </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-10 </td>
        <td> 2012-08-24 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5560">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5560">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5560" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5560&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5560" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>32.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5562">
             [高雄] 酷男座談會(2)-車馬費1500元(T-8/23) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-10 </td>
        <td> 2012-08-23 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5562">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5562">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5562" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5562&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5562" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>33.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5554">
            <b><font color='red'> [台北] 日用品座談會-車馬費2500元(N-9/28)</b></font> </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-09 </td>
        <td> 2012-09-28 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5554">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5554">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5554" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5554&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5554" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>34.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5557">
             [台北] 機車車主座談會-車馬費1200元(B-9/1) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-09 </td>
        <td> 2012-09-01 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5557">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5557">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5557" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5557&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5557" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>35.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5558">
            <b><font color='red'> [台北] 到處走走座談會-車馬費1200~1500元(I-8/24)</b></font> </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-09 </td>
        <td> 2012-08-24 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5558">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5558">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5558" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5558&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5558" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>36.</td>
        <td> 南部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5553">
             [高雄] 實車展示訪問-車馬費1200元(T-9/9) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-08 </td>
        <td> 2012-09-09 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5553">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5553">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5553" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5553&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5553" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>37.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5552">
             [台北] 保養品一對一問卷調查-車馬費1000元(L-9/7) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-08 </td>
        <td> 2012-09-07 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5552">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5552">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5552" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5552&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5552" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>38.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5550">
             [台北] 酷男座談會-車馬費300元(F-9/20) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-07 </td>
        <td> 2012-09-20 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5550">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5550">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5550" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5550&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5550" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>39.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5549">
             [台北] 衛生棉座談會-車馬費500元(Un-8/30) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-07 </td>
        <td> 2012-08-30 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5549">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5549">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5549" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5549&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5549" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>40.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5548">
             [台北] 女性衛生用品座談會-車馬費1200元(T-8/29) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-07 </td>
        <td> 2012-08-29 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5548">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5548">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5548" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5548&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5548" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>41.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5547">
             [新竹以北] 國內旅遊座談會-車馬費1500元(B-8/22) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-06 </td>
        <td> 2012-08-22 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5547">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5547">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5547" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5547&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5547" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>42.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5542">
            <b><font color='red'> [台北] 頂級車車主座談會-車馬費3500~4000元(I-9/30)</b></font> </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-03 </td>
        <td> 2012-09-30 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5542">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5542">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5542" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5542&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5542" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>43.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5541">
             [台北] 汽車訪問-車馬費1200元(T-8/30) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-03 </td>
        <td> 2012-08-30 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5541">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5541">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5541" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5541&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5541" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>44.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5544">
             [台北] 羽球運動座談會-車馬費1500元(Fsr-8/25) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-08-03 </td>
        <td> 2012-08-25 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5544">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5544">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5544" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5544&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5544" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>45.</td>
        <td> 南部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5536">
            <b><font color='red'> [高雄] 酷男座談會-車馬費1500元(T-8/23)</b></font> </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-07-31 </td>
        <td> 2012-08-23 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5536">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5536">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5536" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5536&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5536" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>46.</td>
        <td> 跨區
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=4980">
             [全國] 餐廳神秘訪客(論件計酬) 車馬費200~250元(I-12/31) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-07-23 </td>
        <td> 2012-12-31 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=4980">最新</a> | 
        <a href="/ms/history_results_table.php?sid=4980">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=4980" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=4980&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=4980" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>47.</td>
        <td> 跨區
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5488">
             [全國] 保險商品電話訪問-車馬費150元禮券(I-8/31) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-07-17 </td>
        <td> 2012-08-31 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5488">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5488">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5488" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5488&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5488" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>48.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5471">
             [台北] 民眾飲食習慣研究-車馬費1500元(G-8/31) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-07-12 </td>
        <td> 2012-08-31 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5471">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5471">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5471" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5471&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5471" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>49.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5463">
             [台北] 消費產品訪問-車馬費600~800元(T-9/10) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-07-10 </td>
        <td> 2012-09-10 </td>
      -->
        <td align="right">
         <span style="color:red;">有新的</span>        <a href="/ms/latest_results_table.php?sid=5463">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5463">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5463" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5463&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5463" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>50.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5457">
             [台北] 車展活動(看車評比)-車馬費2000元(N-9/2) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-07-06 </td>
        <td> 2012-09-02 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5457">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5457">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5457" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5457&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5457" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>51.</td>
        <td> 南部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5453">
            <b><font color='red'> [高雄/台南] 銀行服務調查秘密客-車馬費1000元起(I-9/30)</b></font> </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-07-05 </td>
        <td> 2012-09-30 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5453">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5453">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5453" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5453&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5453" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>52.</td>
        <td> 南部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5423">
             [高雄/台南] 精品商店神秘客-車馬費500元(論件計酬)(I-8/31) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-06-29 </td>
        <td> 2012-08-31 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5423">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5423">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5423" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5423&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5423" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>53.</td>
        <td> 跨區
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5415">
            <b><font color='red'> [台北/桃園/新竹] 銀行服務調查秘密客-車馬費1500元(I-8/31)</b></font> </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-06-27 </td>
        <td> 2012-08-31 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5415">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5415">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5415" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5415&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5415" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>54.</td>
        <td> 跨區
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5402">
            <b><font color='red'> [台北/中壢/新竹] 精品神秘訪客-車馬費500元(論件計酬)(I-12/31)</b></font> </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-06-22 </td>
        <td> 2012-12-31 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5402">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5402">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5402" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5402&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5402" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>55.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5208">
            <b><font color='red'> [台北] 高級車訪問-車馬費6000元(I-11/20)</b></font> </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-04-10 </td>
        <td> 2012-11-20 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5208">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5208">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5208" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5208&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5208" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>56.</td>
        <td> 北部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5072">
             [台北] (男)外食電話訪問 車馬費100禮券(I-12/31) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-02-23 </td>
        <td> 2012-12-31 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5072">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5072">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5072" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5072&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5072" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>57.</td>
        <td> 跨區
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5056">
             [全省+澎湖] 神秘客招募 車馬費150~450元(論件計酬)(LB-12/31) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-02-18 </td>
        <td> 2012-12-31 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5056">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5056">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5056" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5056&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5056" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>58.</td>
        <td> 中部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5052">
             [中部] 2012年度神秘客(論件計酬) 車馬費400(F-12/31) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-02-17 </td>
        <td> 2012-12-31 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5052">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5052">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5052" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5052&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5052" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>59.</td>
        <td> 跨區
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=5053">
             [南部/東部] 2012年度神秘客(論件計酬) 車馬費400(F-12/31) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2012-02-17 </td>
        <td> 2012-12-31 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=5053">最新</a> | 
        <a href="/ms/history_results_table.php?sid=5053">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=5053" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=5053&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=5053" target="preview">預覽</a>
        </td>
     
        </tr>
              <tr>
          <td>60.</td>
        <td> 南部
                      </td>   
        <td>
          <a href="/ms/edit_survey.php?sid=3542">
             *[雲林/嘉義/彰化/屏東] 餐廳神秘訪客 車馬費200~250元(I-12/31) </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> 2011-12-27 </td>
        <td> 2012-12-31 </td>
      -->
        <td align="right">
                <a href="/ms/latest_results_table.php?sid=3542">最新</a> | 
        <a href="/ms/history_results_table.php?sid=3542">歷程</a> | 
        <a href="/ms/confirm_yahoo_groups.php?notifyType=normal&sid=3542" target="_new">發佈</a>  |  
        <a href="/ms/results_table_by_mark.php?sid=3542&marked=ALL_MARKED">標記</a>  | 
         <a href="/ms/survey.php?sid=3542" target="preview">預覽</a>
        </td>
     
        </tr>
            </table>
      </div>
     </td>
  </tr>
  <tr>
    <td style="text-align:center">
      <br />
        [<a href="/ms/admin.php">管理</a>]
        &nbsp;|&nbsp;[<a href="/ms/new_answer_type.php?sid=">新增答案類型</a>]
        &nbsp;|&nbsp;[<a href="/ms/edit_answer.php?sid=">編輯答案類型</a>]
            [<a href="/ms/docs/index.html">說明文件</a>]
    </td>
  </tr>
</table><div align="center">
Copyright www.5ifafa.com. All Rights Reserved.
</div>
<!-- END MAIN CONTENT AREA -->
</div> <!-- page -->
  </body>
</html>