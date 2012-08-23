<script> 
surveyURLs = new Array();
	{section name="s1" loop=$survey.all_surveys.sid show=TRUE}
	{if $survey.all_surveys.withNew[s1] > 0 }
	  surveyURLs[surveyURLs.length] = "{$conf.html}/latest_results_table.php?sid={$survey.all_surveys.sid[s1]}";
	{/if}
	{/section}

{literal}
function openNewListInNewBrowsers() {
	for(i=0; i<surveyURLs.length; i++) {
		window.open(surveyURLs[i], ""+i);
	}
}
{/literal}
</script>
<table align="center" cellpadding="0" cellspacing="0">
  <tr class="grayboxheader">
    <td width="14"><img src="{$conf.images_html}/box_left.gif" border="0" width="14"></td>
    <td background="{$conf.images_html}/box_bg.gif">Survey System</td>
    <td width="14"><img src="{$conf.images_html}/box_right.gif" border="0" width="14"></td>
  </tr>
</table>
<table align="center" class="bordered_table">
	<tr>
	  <td>
      {section name="admin_link" show=$$conf.show_admin_link}
        [<a href="{$conf.html}/index.php">主畫面</a>]
        &nbsp;|&nbsp;[<a href="{$conf.html}/new_survey.php">建立</a>]
        &nbsp;|&nbsp;[<a href="{$conf.html}/black_list.php">黑名單</a>]
        &nbsp;|&nbsp;[<a href="{$conf.html}/block_list.php">禁止名單</a>]
      {/section}
	  </td>
	</tr>
  <tr>
    <td>
      <div style="font-weight:bold;text-align:center">
        {if $activeType == 1} 啟用的 {elseif $activeType == 0} 停用的 {else} 所有 {/if}
         {if $activeType != 0} [<a href="admin0302_surveys.php?activeType=0">停用的</a>] {/if}
         {if $activeType != 1} [<a href="admin0302_surveys.php?activeType=1">啟用的</a>] {/if}
         {if $activeType != -1} [<a href="admin0302_surveys.php?activeType=-1">全部</a>] {/if}
          [<a href="#" onclick="openNewListInNewBrowsers()">開啟所有新名單</a>]
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
      {section name="s" loop=$survey.all_surveys.sid show=TRUE}
        <tr>
          <td>{$smarty.section.s.iteration}.</td>
        <td> {if  $survey.all_surveys.region[s] == 1}跨區
             {elseif  $survey.all_surveys.region[s] == 2}北部
             {elseif  $survey.all_surveys.region[s] == 3}中部
             {elseif  $survey.all_surveys.region[s] == 4}南部
             {elseif  $survey.all_surveys.region[s] == 5}其他
             {/if}
         </td>   
        <td>
          <a href="{$conf.html}/edit_survey.php?sid={$survey.all_surveys.sid[s]}">
            {if $survey.all_surveys.on_top[s] == 1}<b><font color='red'>{/if} {$survey.all_surveys.name[s]}{if $survey.all_surveys.display_state[s] == 0}(隱藏){/if}{if $survey.all_surveys.on_top[s] == 1}</b></font>{/if} </a>
        </td>
      <!-- 20090710 Hide these two fields
        <td> {$survey.all_surveys.start_date[s]} </td>
        <td> {$survey.all_surveys.end_date[s]} </td>
      -->
        <td align="right">
        {if $survey.all_surveys.withNew[s] > 0 } <span style="color:red;">有新的</span>{/if}
        <a href="{$conf.html}/latest_results_table.php?sid={$survey.all_surveys.sid[s]}">最新</a> | 
        <a href="{$conf.html}/history_results_table.php?sid={$survey.all_surveys.sid[s]}">歷程</a> | 
        <a href="{$conf.html}/post_to_yahoo_groups.php?notifyType=normal&sid={$survey.all_surveys.sid[s]}" target="_new">發佈</a>  |  
        <a href="{$conf.html}/results_table_by_mark.php?sid={$survey.all_surveys.sid[s]}&marked=ALL_MARKED">標記</a>  | 
         <a href="{$conf.html}/survey.php?sid={$survey.all_surveys.sid[s]}" target="preview">預覽</a>
        </td>
     
        </tr>
      {sectionelse}
        <tr><td>目前沒有活動</td></tr>
      {/section}
      </table>
      </div>
     </td>
  </tr>
  <tr>
    <td style="text-align:center">
      <br />
        [<a href="{$conf.html}/admin0302.php">管理</a>]
        &nbsp;|&nbsp;[<a href="{$conf.html}/new_answer_type.php?sid={$data.sid}">新增答案類型</a>]
        &nbsp;|&nbsp;[<a href="{$conf.html}/edit_answer.php?sid={$data.sid}">編輯答案類型</a>]
      {section name="logout" loop=1 show=$show.logout}
        &nbsp;|&nbsp;[<a href="{$conf.html}/admin0302_surveys.php?action=logout">登出</a>]
        &nbsp;|&nbsp;
      {/section}
      [<a href="{$conf.html}/docs/index.html">說明文件</a>]
    </td>
  </tr>
</table>