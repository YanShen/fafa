  <tr>
    <td align="center">
      [ <a href="{$conf.html}/edit_survey.php?sid={$data.sid}&mode=properties">內容</a>
      &nbsp;|&nbsp;
      <a href="{$conf.html}/edit_survey.php?sid={$data.sid}&mode=questions">題目</a>
      &nbsp;|&nbsp;
      <a href="{$conf.html}/survey.php?sid={$data.sid}&preview_survey=1" target="_blank">預覽</a>

      {section name="admin_link" show=$$conf.show_admin_link}
        &nbsp;|&nbsp;<a href="{$conf.html}/post_to_yahoo_groups.php?notifyType=normal&sid={$data.sid}" target="_new">發佈YGroups</a>
        &nbsp;|&nbsp;<a href="#" target="_new" onclick="if(confirm('確定要發佈到VIP專區?')){literal}{{/literal} window.open('{$conf.html}/post_to_yahoo_groups.php?notifyType=vip&sid={$data.sid}');return false;{literal}}{/literal}else return false;"><b><font color="red">發佈VIP YGS</font></b></a>
      {/section} ]
    </td>
  </tr>