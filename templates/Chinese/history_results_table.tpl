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
       	[ <a href="{$conf.html}/results_table.php?sid={$survey.sid}&markedOnly=true">全部標記紀錄</a> ]
        [ <a href="{$conf.html}/admin0302_surveys.php">管理活動列表</a> ]
        &nbsp;&nbsp;
        [ <a href="{$conf.html}/index.php">主畫面</a> ]
      </div>

      <div class="whitebox">
        匯出紀錄 #{$survey.sid}: {$survey.name}
      </div>
        
      <div align="center">
        <table style="border-collapse: collapse; width:100%" border="1" bordercolor="#888888">
          <tr>
          	<th>匯出時間</th>
          </tr>
          {section name=x loop=$markedTime show=TRUE}
            <tr>
                <td><a href="results_table_by_mark.php?sid={$survey.sid}&marked={$markedTime[x]}">{$markedTime[x]|date_format:"%Y-%m-%d %H:%M:%S"}</a></td>
            </tr>
          {/section}
        </table>
      </div>
      </form>
      
      <div style="text-align:center">
       	[ <a href="{$conf.html}/results_table.php?sid={$survey.sid}&markedOnly=true">全部標記紀錄</a> ]
        [ <a href="{$conf.html}/admin0302_surveys.php">管理活動列表</a> ]
        &nbsp;&nbsp;
        [ <a href="{$conf.html}/index.php">主畫面</a> ]
      </div>

    </td>
  </tr>
</table>