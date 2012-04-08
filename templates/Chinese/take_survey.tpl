<div class="mainarea">
<!-- Site Search Google
<table class="bordered_table" width="100%" align="center" cellpadding="0" cellspacing="0">
  <tr><td>
  <table width="100%" bgcolor="white" border="0"><tr>
  <td style="font-size: 18px;font-weight:bold" width="100%">
  [<a style="color:#EE1111;" href="/ms/index.php">*** 更多活動!! ***</a>]


  </td>
     <td align="right"> 
       <div align="left">尋找您感興趣的活動</div>
       
<form method="get" action="http://www.google.com/custom" target="google_window" style="display: inline">
<table border="0" bgcolor="#ffffff">
<tr><td nowrap="nowrap" valign="top" align="left" height="32">

</td>
<td nowrap="nowrap">
<input type="hidden" name="domains" value="www.5ifafa.com"></input>
<label for="sbi" style="display: none">輸入您的搜尋字詞</label>
<input type="text" name="q" size="40" maxlength="255" value="" id="sbi"></input>
<label for="sbb" style="display: none">提交搜尋表單</label>
<input type="submit" name="sa" value="Google 搜尋" id="sbb"></input>
</td></tr>

<tr>
<td>&nbsp;</td>
<td nowrap="nowrap">
<table>
<tr>
<td>
<input type="radio" name="sitesearch" value="" id="ss0"></input>
<label for="ss0" title="搜尋網頁"><font size="-1" color="black">Web</font></label></td>
<td>
<input type="radio" name="sitesearch" value="www.5ifafa.com" checked id="ss1"></input>
<label for="ss1" title="搜尋 www.5ifafa.com"><font size="-1" color="black">www.5ifafa.com</font></label></td>
</tr>
</table>
<input type="hidden" name="client" value="pub-7712562274412431"></input>
<input type="hidden" name="forid" value="1"></input>
<input type="hidden" name="channel" value="8841119104"></input>
<input type="hidden" name="ie" value="UTF-8"></input>
<input type="hidden" name="oe" value="UTF-8"></input>
<input type="hidden" name="cof" value="GALT:#E9382F;GL:1;DIV:#CCCCCC;VLC:7E3939;AH:center;BGC:FFFFFF;LBGC:FF3333;ALC:E9382F;LC:E9382F;T:000000;GFNT:7E3939;GIMP:7E3939;FORID:1"></input>
<input type="hidden" name="hl" value="zh-TW"></input>
</td></tr></table>
</form>

      </td> 
     </tr>
    </table>
   </td>
  </tr>
</table>
Site Search Google -->

<form method="POST" action="survey.php" style="display: inline">
  <input type="hidden" name="sid" value="{$survey.sid}">
<table class="list_table2">      
  <tr>
    <td class="div_title">{$survey.name}</td>
  </tr>
    <tr>
      <td>
        {*MESSAGE*}
        {section name="message" loop=1 show=$message}
          <div class="message">{$message}</div>
        {/section}

        {*ERROR*}
        {section name="error" loop=1 show=$error}
          <div class="error">{$error}</div>
        {/section}

        {*NUMBER OF PAGES*}
        {section name="page" loop=1 show=$show.page_num}
	      {if $survey.total_pages <> 1}
            <div >
              第 {$survey.page} of {$survey.total_pages} 頁
            </div>
		  {/if}
        {/section}

        {*TIME LIMIT*}
        {section name="time_limit" loop=1 show=$survey.time_limit}
          <div>
            Time Limit: {$survey.time_limit} minutes. Approximate Elapsed Time: {$survey.elapsed_minutes}:{$survey.elapsed_seconds}
          </div>
        {/section}

        {*WELCOME MESSAGE*}
        {section name="welcome" loop=1 show=$show.welcome|default:FALSE}
          <div style="font-size:11pt">{$survey.welcome_text}</div>
        {/section}
      </td></tr>
	  <tr><td>
        {*QUESTIONS*}
        {section name="question" loop=1 show=$show.question|default:FALSE}
          <div style="font-size:11pt">
		  {$question_text}
		  </div>
          <table width="100%">
            <tr><td class="whitebox">驗證碼-請輸入下圖之文字
            </td></tr>
            <tr>
              <td>
              <table border="0">
                 <tr>
                   <td><input type="text" name="captcha_code" value=""><img src="captcha.php" width="200" height="60" alt="Visual CAPTCHA" /> </td>
                 
                   <td>
<!-- 贊助商連結<br> -->

                   </td>
                 </tr>
               </table>
              </td></tr>
          </table>
        {/section}

        {*THANK YOU MESSAGE*}
        {section name="thank_you" loop=1 show=$show.thank_you|default:FALSE}
          <div>{$survey.thank_you_text}</div>
        {/section}

        {*QUIT SURVEY MESSAGE*}
        {section name="quit" loop=1 show=$show.quit|default:FALSE}
          <div>
            您將離開報名程序，目前的內容將不被儲存。
          </div>
        {/section}

        {*MAIN LINK*}
        {section name="main_url" loop=1 show=$show.main_url|default:FALSE}
          <div style="text-align:center">
            <br />
            [ <a href="{$conf.html}/index.php">回主畫面</a> ]
          </div>
        {/section}

        {*BUTTONS*}
          <div style="text-align:right">
            {section name="quit" loop=1 show=$show.quit_button}
              <input type="submit" name="quit" value="取消填寫">
            {/section}

            {section name="previous" loop=1 show=$show.previous_button}
              &nbsp;
              <input type="submit" name="previous" value="{$button.previous|default:"&lt;&lt;&nbsp;上一頁"}">
            {/section}

            {section name="next" loop=1 show=$show.next_button}
              &nbsp;
              <input type="submit" name="next" value="{$button.next|default:"下一頁&nbsp;&gt;&gt;"}">
            {/section}
          </div>
      </td>
    </tr> 
  </table>
</form>

<!-- Footer -->

</div> <!-- float to left -->