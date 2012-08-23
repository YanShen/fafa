<script> 
{* checkbox with id="blockList_1" means name in block_list, and it should be hidden.*}
{literal}
window.onload = addEmailCheckbox;

function addEmailCheckbox() {
	var rtable = document.all["resultTable"];
	var emailIndex = -1;
	
	for(i=0; i<rtable.rows[0].cells.length; i++) {
		if(rtable.rows[0].cells[i].innerHTML.search(/E*mail/i)>-1) {
			emailIndex = i;
			
			rtable.rows[0].cells[i].innerHTML = rtable.rows[0].cells[i].innerHTML +
			'<input type="checkbox" onclick="checkAllEmailAddr(this.checked)">';
			
			break;
		}
	}
	//There's no email field found !
	if(emailIndex == -1) {
		return;
	} else {
		for(i=1; i<rtable.rows.length; i++) {
			//if this row is in block_list, there should be no email checkbox
			var node;
			for(j =0; j<rtable.rows[i].cells[0].childNodes.length; j++) {
				node = rtable.rows[i].cells[0].childNodes[j];
				if(node && node.type && node.type=="checkbox") {
					break;
				}
			}
			if(node && (node.type=="checkbox") && (node.id != "blockList_1")) {
				rtable.rows[i].cells[emailIndex].innerHTML =
				'<input type="checkbox" name="emailaddr" id="emailaddr" value="' +
				findEmailAddresses(rtable.rows[i].cells[emailIndex].innerHTML) +
				'" onclick="composeEmailList()">' +
				rtable.rows[i].cells[emailIndex].innerHTML;
			}	
		}	
	}
}

function checkAllEmailAddr(checked) {
	var emails = document.all["emailaddr"];
	if(!emails) return;
	
	if(emails.length) {
		for(i=0; i<emails.length; i++) {
			emails[i].checked = checked;
		}
	} else {
		emails.checked = checked;
	}
	composeEmailList();
}

function composeEmailList() {
	var emails = document.all["emailaddr"];
	var s = "";
	
	if(emails.length) {
		for(i=0; i<emails.length; i++) {
			if(emails[i].checked)
				s += emails[i].value.replace(/^\s+|\s+$/, '') + ',';
		}
	} else {
		if(emails.checked)
			s = emails.value;
	}
	document.all["emailList"].innerHTML = s;
}

function findEmailAddresses(StrObj) {
	var separateEmailsBy = ", ";
	var email = "<none>"; // if no match, use this
	var emailsArray = StrObj.match(/([a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z0-9._-]+)/gi);
	if (emailsArray) {
		email = "";
		for (var i = 0; i < emailsArray.length; i++) {
			if (i != 0) email += separateEmailsBy;
			email += emailsArray[i];
		}
	}
	return email;
}



{/literal}
</script>

<table align="center" cellpadding="0" cellspacing="0">
  <tr class="grayboxheader">
    <td width="14"><img src="{$conf.images_html}/box_left.gif" border="0" width="14"></td>
    <td background="{$conf.images_html}/box_bg.gif">Survey Results</td>
    <td width="14"><img src="{$conf.images_html}/box_right.gif" border="0" width="14"></td>
  </tr>
</table>
<table align="center" class="bordered_table">
  <tr>
    <td>

      <div style="text-align:center">
        [ <a href="{$conf.html}/admin0302_surveys.php">管理活動列表</a> ]
        &nbsp;&nbsp;
        [ <a href="{$conf.html}/index.php">主畫面</a> ]
        &nbsp;&nbsp;
        [ <a href="{$conf.html}/results.php?sid={$survey.sid}">圖表</a> ]
        &nbsp;&nbsp;
        [ <a href="{$conf.html}/results_xls.php?sid={$survey.sid}&marked={$smarty.get.marked}" target="_new">匯出成Excel</a> ]
        &nbsp;&nbsp;
        [ <a href="{$conf.html}/results_csv.php?sid={$survey.sid}">匯出成CSV</a>
          <a href="{$conf.html}/docs/index.html#csv_export">[?]</a> ]
        &nbsp;&nbsp;
        [ <a href="{$conf.html}/black_list.php">編輯黑名單</a>]
      </div>

      <div class="whitebox">
        報名結果 #{$survey.sid}: {$survey.name}
      </div>
      {if $survey.marked != "" || $smarty.request.marked != ""}
        {$smarty.request.marked|date_format:"%Y-%m-%d %H:%M:%S"} Exported |
      {/if}
        <a href="{$conf.html}/latest_results_table.php?sid={$survey.sid}">最新</a> | 
        <a href="{$conf.html}/history_results_table.php?sid={$survey.sid}">歷程</a> | 
        <a href="{$conf.html}/results_table.php?sid={$survey.sid}">全部</a>   | 
         <a href="{$conf.html}/results_table_by_mark.php?sid={$survey.sid}&marked=ALL_MARKED">全部標記紀錄</a>  | 
        <a href="{$conf.html}/survey.php?sid={$survey.sid}" target="preview">預覽</a>  |
				<a href="{$conf.html}/post_to_yahoo_groups.php?notifyType=normal&sid={$survey.sid}" target="_new">發佈YGroups</a>
       
      {section name="filter_text" loop=1 show=$filter_text}
        <br><span class="message">Notice: This result page shows the results filtered by the following questions:</span><br>
        <span style="font-size:x-small">{$filter_text}</span>
      {/section} 
      <form name="markExportForm" action="mark_result.php" method="post">
      <input type="hidden" name="sid" value="{$survey.sid}">
      <input type="hidden" name="marked" value="{$smarty.request.marked}">
      <div> 
      <b>備註:<br>
      <font size="+1" color="red"><b><i>{$survey.key_desc}</i></b></font>
      </b>
      <div>
      <div align="center">
        {if isset($smarty.request.marked) }
        	<button onclick="javascript:sendEmail()">將本表寄出<br><font size="-2">(姓名重複資料僅保留最新一筆)</font></button>
      	{else}
      	    <input type="submit" value="標記並匯出">
      	{/if}
      </div>
      <div align="center">
        <table id="resultTable" style="border-collapse: collapse;" border="1" bordercolor="#888888">
          <tr>
          	<th><input type="checkbox" name="ct" onclick="checkCheckboxes(this, 'seq[]')"></th>
          	<th>匯出時間</th> 
          	{* if all qids are not checked, assign selAllQid to "true", otherwise set it to "false" *}
          	{assign var="selAllQid" value="true"}
            {section name=q loop=$data.questions show=TRUE}
              {if $data.qid[q].checked == "checked"}
                {assign var="selAllQid" value="false"}
              {/if} 
            {/section}
            {section name=q loop=$data.questions show=TRUE} 
              {if ($selAllQid == "true") || ($data.qid[q].checked == "checked")}
               	{assign var="thisQidChecked" value="checked"}
              {else}
                {assign var="thisQidChecked" value=""}
              {/if}
              <th valign="top"><input type="checkbox" name="selQid[]" value="{$data.qid[q].id}" {$thisQidChecked}><br>{$data.questions[q]}</th>
            {/section}
            <th>填寫時間</th>
            <th>介紹人</th>
          </tr>
          {section name=x loop=$data.answers show=TRUE}
            <tr>
                <td>
                {* name in blockList should not have checkbox *}
                {if ($answers[x].marked == "") and (not $answers[x].block_list) }
                <input type="checkbox" name="seq[]" id="blockList_{$answers[x].block_list}" value="{$answers[x].seq}">
                {/if}
                </td>
                <td><a href="results_table_by_mark.php?sid={$survey.sid}&marked={$answers[x].marked}">{$answers[x].marked|date_format:"%Y-%m-%d %H:%M:%S"}</a>&nbsp;</td>
              {section name=a loop=$data.answers[x] show=TRUE}
		                {if $answers[x].block_list} 
						<td style="background-color:#ff0000;color:#AAff00;font-style:italic;font-weight:bolder;" alt="禁止名單">{$data.answers[x][a]}</td>   
                    {elseif $answers[x].duplicate && $answers[x].black_list} 
						<td style="color:#AA0000;font-style:italic;font-weight:bolder;" alt="重複 + 黑名單">{$data.answers[x][a]}</td>   
                    {elseif $answers[x].duplicate} 
						<td style="color:#AA0000;" alt="重複">{$data.answers[x][a]}</td>
                    {elseif $answers[x].black_list }
  
						<td style="color:#0000BB;font-style:italic;font-weight:bolder;" alt="黑名單">{$data.answers[x][a]}</td>
										{else}
											<td>{$data.answers[x][a]}</td>
										{/if}

              {/section}
               <td>{$survey.default_referrer}</td>
            </tr>
          {/section}
        </table>
      </div>
      <div align="center">
        {if isset($smarty.request.marked) }
        	<button onclick="javascript:sendEmail()">將本表寄出<br><font size="-2">(姓名重複資料僅保留最新一筆)</font></button>
      	{else}
      	    <input type="submit" value="標記並匯出">
      	{/if}
      </div>
      </form>
      
      <div style="text-align:center">
        [ <a href="{$conf.html}/admin0302_surveys.php">管理活動列表</a> ]
        &nbsp;&nbsp;
        [ <a href="{$conf.html}/index.php">主畫面</a> ]
        &nbsp;&nbsp;
        [ <a href="{$conf.html}/results.php?sid={$survey.sid}">圖表</a> ]
        &nbsp;&nbsp;
        [ <a href="{$conf.html}/results_xls.php?sid={$survey.sid}&marked={$smarty.request.marked}" target="_new">匯出成Excel</a> ]
        &nbsp;&nbsp;
        [ <a href="{$conf.html}/results_csv.php?sid={$survey.sid}">匯出成CSV</a>
          <a href="{$conf.html}/docs/index.html#csv_export">[?]</a> ]
      </div>

    </td>
  </tr>
</table>

勾選的Email:<div id="emailList"></div>

<script type="text/javascript">
{literal}
function checkCheckboxes(tcb, cname) {
  var cbs = document.getElementsByName(cname);
	for(i=0; i<cbs.length; i++) {
		cb = cbs[i];
		if(cb.type == 'checkbox')
			cb.checked = tcb.checked;
	}
}

function sendEmail() {
	document.markExportForm.action = "send_list_by_smtp.php";
	document.markExportForm.target = "_new";
	document.markExportForm.submit();
}

//Select all items by default.
document.markExportForm.ct.checked=true;
checkCheckboxes(document.markExportForm.ct, 'seq[]') ;

{/literal}
</script>