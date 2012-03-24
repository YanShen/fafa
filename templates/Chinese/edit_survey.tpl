<table width="70%" align="center" cellpadding="0" cellspacing="0">
  <tr class="grayboxheader">
    <td width="14"><img src="{$conf.images_html}/box_left.gif" border="0" width="14"></td>
    <td background="{$conf.images_html}/box_bg.gif">Edit Survey</td>
    <td width="14"><img src="{$conf.images_html}/box_right.gif" border="0" width="14"></td>
  </tr>
</table>
<table width="70%" align="center" class="bordered_table">
	<tr>
	  <td align="center">
      {section name="admin_link" show=$$conf.show_admin_link}
        [<a href="{$conf.html}/index.php">主畫面</a>]
        &nbsp;|&nbsp;[<a href="{$conf.html}/new_survey.php">建立</a>]
        &nbsp;|&nbsp;[<a href="{$conf.html}/black_list.php">黑名單</a>]
        &nbsp;|&nbsp;[<a href="{$conf.html}/block_list.php">禁止名單</a>]
      {/section}
	  </td>
	</tr>
{$data.links}

  <tr>
    <td>
      [<a href="/ms/admin_surveys.php">列表</a>] {$data.content}
    </td>
  </tr>

  <tr>
    <td align="center">
      <br />
      [<a href="{$conf.html}/new_answer_type.php?sid={$data.sid}">新增答案類型</a>
      &nbsp;|&nbsp;
      <a href="{$conf.html}/edit_answer.php?sid={$data.sid}">編輯答案類型</a>
      &nbsp;|&nbsp;
      <a href="{$conf.html}/edit_survey.php?sid={$data.sid}&mode=access_control">存取控制</a>
      &nbsp;|&nbsp;
      <a href="{$conf.html}/index.php">主畫面</a>
      &nbsp;|&nbsp;
      <a href="{$conf.html}/admin.php">管理</a> ]
    </td>
  </tr>
</table>