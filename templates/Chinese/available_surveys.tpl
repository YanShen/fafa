<script> 
if ('{$smarty.request.result}'=='ok')
    alert('恭喜您完成報名！\n請記得您的介紹人為<<李小姐>>。\n\n＊＊請((不要))說是網路上看到座談會訊息，否則會被市調公司((取消報名資格))喔！＊＊');
</script>
<div align="center">

</div>

<div align="center">

<table border="0">
<tr><td>

<table class="bordered_table" width="100%" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>
     <table width="100%" bgcolor="white" border="0">
      <tr>
         
       <td align="center" class="whitebox" style="color: red;text-align:center;">

         <a href="http://hk.groups.yahoo.com/group/MONEY_MONEY/join">訂閱[市調活動電子報]</a>，優先取得報名資格，較高額座談會將只發佈給加入會員！
         <br>
         
       </td>
     <td align="right">  
<!--
       <div align="left">尋找您感興趣的活動</div>
-->   
<!-- SiteSearch Google -->
<!--
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
<label for="ss0" title="搜尋網頁"><font size="-1" color="#000000">Web</font></label></td>
<td>
<input type="radio" name="sitesearch" value="www.5ifafa.com" checked id="ss1"></input>
<label for="ss1" title="搜尋 www.5ifafa.com"><font size="-1" color="#000000">www.5ifafa.com</font></label></td>
</tr>
</table>
<input type="hidden" name="client" value="pub-7712562274412431"></input>
<input type="hidden" name="forid" value="1"></input>
<input type="hidden" name="channel" value="9758906635"></input>
<input type="hidden" name="ie" value="UTF-8"></input>
<input type="hidden" name="oe" value="UTF-8"></input>
<input type="hidden" name="cof" value="GALT:#008000;GL:1;DIV:#336699;VLC:663399;AH:center;BGC:FFFFFF;LBGC:336699;ALC:0000FF;LC:0000FF;T:000000;GFNT:0000FF;GIMP:0000FF;FORID:1"></input>
<input type="hidden" name="hl" value="zh-TW"></input>
</td></tr></table>
</form> -->
<!-- SiteSearch Google --> 

<div align="left">

<!-- 推薦 -->

<!-- 推薦 -->

</div>
      </td>
       </td>
       </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <div style="font-weight:bold;text-align:center">
        {if $regionCountX[1]} <a href="#region1">跨區({$regionCountX[1]})</a> {else} 跨區({$regionCountX[1]}) {/if}|
        {if $regionCountX[2]} <a href="#region2">北部({$regionCountX[2]})</a> {else} 北部({$regionCountX[2]}) {/if}|
        {if $regionCountX[3]} <a href="#region3">中部({$regionCountX[3]})</a> {else} 中部({$regionCountX[3]}) {/if}|
        {if $regionCountX[4]} <a href="#region3">南部({$regionCountX[4]})</a> {else} 南部({$regionCountX[4]}) {/if}|
        {if $regionCountX[5]} <a href="#region4">其他({$regionCountX[5]})</a> {else} 其他({$regionCountX[5]}) {/if}
      </div>
      <div align="center">
      <table class="list_table">

{section name="r" loop=6 start=1 show=TRUE}
      {if sizeof($survey.public[r]) != 0 }
        <tr><td colspan="2" class="regionheader"> 
          <a name="region{$smarty.section.r.iteration}">
          {if $smarty.section.r.iteration == 1}跨區活動
          {elseif $smarty.section.r.iteration == 2}北部活動
          {elseif $smarty.section.r.iteration == 3}中部活動
          {elseif $smarty.section.r.iteration == 4}南部活動
          {elseif $smarty.section.r.iteration == 5}其他地區
          {/if}
          </a> ({$regionCountX[r]})
        </td>
        {section name="s" loop=$survey.public[r] show=TRUE}
         <tr><td>
        {$smarty.section.s.iteration}
         </td><td>
           <a href="{$conf.html}/survey.php?sid={$survey.public[r][s].sid}">
             <div style="{if $survey.public[r][s].on_top == 1}font-weight:bold; color:red{/if}">
             {$survey.public[r][s].display}
             {if $survey.public[r][s].createdWithinOneDay}             
               <sup style="color:green;">最新!!</sup>
             {elseif $survey.public[r][s].updatedWithinOneDay}
               <sup style="color:blue;">更新!!</sup>
             {/if}
             </div>
           </a>
         </td></tr>
        {/section}
      {/if}
{/section}
      
      </table>
      </div>
    </td>
  </tr>
</table>
</tr></td>
<td valign="top"> 
<!--贊助商連結 

<div>

</div>
-->
</td></tr>

<!-- Footer -->
<tr><td>
<!-- 贊助商連結<br> -->

</td></tr>

</table>

<div> <!-- float to left -->