<table align="center" class="bordered_table">
  <tr>
    <td>
      <div class="whitebox">
        Results for Survey #{$survey.sid}: {$survey.name} 
      </div>
      {if $survey.marked != "" || $smarty.request.marked != ""}
        {$smarty.request.marked|date_format:"%Y-%m-%d %H:%M:%S"} Exported
      {/if}
      {section name="filter_text" loop=1 show=$filter_text}
        <br><span class="message">Notice: This result page shows the results filtered by the following questions:</span><br>
        <span style="font-size:x-small">{$filter_text}</span>
      {/section}
      <form name="markExportForm" action="mark_result.php" method="post">
      <input type="hidden" name="sid" value="{$survey.sid}">
      <div align="center">
        <table style="border-collapse: collapse;" border="1" bordercolor="#888888">
          <tr>
          	<th>匯出時間</th>
            {section name=q loop=$data.questions show=TRUE}
              <th>{$data.questions[q]}</th>
            {/section}
            <th>填寫時間</th>
            <th>介紹人</td>
          </tr>
          {section name=x loop=$data.answers show=TRUE}
            <tr>
                <td><a href="results_table_by_mark.php?sid={$survey.sid}&marked={$answers[x].marked}">{$answers[x].marked|date_format:"%Y-%m-%d %H:%M:%S"}</a>&nbsp;</td>
              {section name=a loop=$data.answers[x] show=TRUE}
                <td>'{$data.answers[x][a]}</td>
              {/section}
              <td>{$survey.default_referrer}</td>
            </tr>
          {/section}
        </table>
      </div>
      </form>

    </td>
  </tr>
</table>