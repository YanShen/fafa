<table align="center" style="font-size: 13px">
  <tr>
    <td>
      <div>
您好  ：  <br/>
<br/>
我是介紹人： {$survey.default_referrer} <br/>
       
        以下為報名 #{$survey.sid}: {$survey.mail_subject} 之名單
      </div>
      {if $survey.marked != "" || $smarty.request.marked != ""}
        於 {$smarty.request.marked|date_format:"%Y-%m-%d %H:%M:%S"} 前報名之新增名單
      {/if}
      {section name="filter_text" loop=1 show=$filter_text}
        <br><span class="message">Notice: This result page shows the results filtered by the following questions:</span><br>
        <span style="font-size:x-small">{$filter_text}</span>
      {/section}

      <div align="center">
        <table style="border-collapse: collapse; font-size: 13px" border="1" bordercolor="#888888">
          <tr>
          	<th>#</th>
            {section name=q loop=$data.questions show=TRUE}
              <th>{$data.questions[q]}</th>
            {/section}
            <th>介紹人</th>
          </tr> 
          {counter start=0 skip=1 print=false}
          {section name=x loop=$data.answers show=TRUE}
            <tr>
                <td>{counter}</td>
              {section name=a loop=$data.answers[x] show=TRUE}
                <td>{$data.answers[x][a]}</td>
              {/section}
              <td>{$survey.default_referrer}</td>
            </tr>
          {/section}
        </table>
      </div>
<div class="whitebox">

</div>
    </td>
  </tr>
</table>
