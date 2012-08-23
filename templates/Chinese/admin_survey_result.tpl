<table width="70%" align="center" cellpadding="0" cellspacing="0">
  <tr class="grayboxheader">
    <td width="14"><img src="{$conf.images_html}/box_left.gif" border="0" width="14"></td>
    <td background="{$conf.images_html}/box_bg.gif">Survey System</td>
    <td width="14"><img src="{$conf.images_html}/box_right.gif" border="0" width="14"></td>
  </tr>
</table>
<table width="70%" align="center" class="bordered_table">
  <tr>
    <td class="whitebox">市調活動</td>
  </tr>
  <tr>
    <td>
      <div style="font-weight:bold;text-align:center">
        目前市調活動列表如下，請選擇您想參加的活動。
      </div>

      <div class="indented_cell">
      {section name="s" loop=$survey.public show=TRUE}
        {$smarty.section.s.iteration}. <a href="{$conf.html}/survey.php?sid={$survey.public[s].sid}">{$survey.public[s].display}</a>
        &nbsp;&nbsp;[ <a href="{$conf.html}/results.php?sid={$survey.public[s].sid}">View Results</a> ]
        <br />
      {sectionelse}
        There are no surveys available at this time.
      {/section}
      </div>
    </td>
  </tr>
  <tr>
    <td class="whitebox">Edit Surveys</td>
  </tr>
  <tr>
    <td>
      <form class="indented_cell" method="get" action="{$conf.html}/edit_survey.php">
        Survey:&nbsp;
        <select name="sid" size="1">
          {section name="as" loop=$survey.all_surveys.sid}
            <option value="{$survey.all_surveys.sid[as]}">{$survey.all_surveys.name[as]}</option>
          {/section}
        </select>
        &nbsp;<input type="submit" name="submit" value="Edit Survey">
      </form>
    </td>
  </tr>
  <tr>
    <td style="text-align:center">
      <br />
      [ <a href="{$conf.html}/new_survey.php">Create New Survey</a>
      &nbsp;|&nbsp;
      <a href="{$conf.html}/admin0302.php">Admin</a>
      &nbsp;|&nbsp;
      {section name="logout" loop=1 show=$show.logout}
        <a href="{$conf.html}/admin0302_surveys.php?action=logout">Logout</a>
        &nbsp;|&nbsp;
      {/section}
      <a href="{$conf.html}/docs/index.html">Documentation</a> ]
    </td>
  </tr>
</table>