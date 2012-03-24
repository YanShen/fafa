<table width="70%" align="center" cellpadding="0" cellspacing="0">
  <tr class="grayboxheader">
    <td width="14"><img src="{$conf.images_html}/box_left.gif" border="0" width="14"></td>
    <td background="{$conf.images_html}/box_bg.gif">請輸入密碼</td>
    <td width="14"><img src="{$conf.images_html}/box_right.gif" border="0" width="14"></td>
  </tr>
</table>

<table width="70%" align="center" class="bordered_table">
  {section name="message" show=$data.message}
    <tr><td class="error">{$data.message}</td></tr>
  {/section}
  <tr>
    <td align="center">
     此活動<span style="color: red">並未公開招募</span>，需加入會員並取得((活動密碼))才能完成報名!!
     
      <div style="text-align:center;margin-top:20px"> 
<table> 
<tr><td>
選擇1. <a href="http://hk.groups.yahoo.com/group/money_money/files">點選此處，登入並加入聯盟會員後取得((活動密碼))</a><br>
選擇2. <a href="/ms/index.php">點選此處參加其他免密碼活動</a>
</td></tr>
</table>
      </div>
      
      <form method="POST" action="{$conf.html}/{$data.page}" name="login_form">
        {section name="h" loop=$data.hiddenkey show=true}
          <input type="hidden" name="{$data.hiddenkey[h]}" value="{$data.hiddenval[h]}">
        {/section}
        請輸入密碼:
        <br>
        Password: <input type="password" name="read_password" size="15" maxlength="25">
        <br>
        <input type="submit" value="Enter">
      </form>
    </td>
  </tr>
</table>
<script language="JavaScript">
document.login_form.read_password.focus();
</script>