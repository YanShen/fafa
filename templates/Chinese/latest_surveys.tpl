<script> 
if ('{$smarty.request.result}'=='ok')
    alert('恭喜您完成報名！\n請記得您的介紹人為<<李小姐>>。\n\n＊＊請((不要))說是網路上看到座談會訊息，否則會被市調公司((取消報名資格))喔！＊＊');
</script>
<div class="mainarea">
<div>
      <div class=div_title menuitem">
        {if $regionCountX[1]} <a href="#region1">跨區({$regionCountX[1]})</a> {else} 跨區({$regionCountX[1]}) {/if}|
        {if $regionCountX[2]} <a href="#region2">北部({$regionCountX[2]})</a> {else} 北部({$regionCountX[2]}) {/if}|
        {if $regionCountX[3]} <a href="#region3">中部({$regionCountX[3]})</a> {else} 中部({$regionCountX[3]}) {/if}|
        {if $regionCountX[4]} <a href="#region3">南部({$regionCountX[4]})</a> {else} 南部({$regionCountX[4]}) {/if}|
        {if $regionCountX[5]} <a href="#region4">其他({$regionCountX[5]})</a> {else} 其他({$regionCountX[5]}) {/if}
      </div>
	  
	  {foreach from=$survey.public key="region" item="item1"}
	    {if sizeof($item1) != 0 }
		<div class="regionitemarea">
        <table class="list_table2">
          <tr><td colspan="2" class="regionheader"> 
          <a name="region{$region}">
          {if $region == 1}最新跨區活動
          {elseif $region == 2}最新北部活動
          {elseif $region == 3}最新中部活動
          {elseif $region == 4}最新南部活動
          {elseif $region == 5}最新其他地區活動
          {/if}
          </a> ({$regionCountX[$region]})
		  </td></tr>
	      {foreach from=$item1 key="date" item="item2"}
		    <tr><td class="latest-date">{$date}</td></tr>
		    {foreach from=$item2 key="coru" item="item3"}
		      <tr><td>
			  <div>
			  {if $coru}
			    <div class="latest-update">更新</div>
			  {else}
			    <div class="latest-new">新增</div>
			  {/if}
			  </div>
			  <div style="float:left">
		      {foreach from=$item3 item="item4"}
			   <div>
			     <a href="{$conf.html}/survey.php?sid={$item4.sid}">
                 {$item4.display}
			     </a>
			   </div>
			  {/foreach}
			  </div>
			  </td></tr>
		    {/foreach}
		  {/foreach}
	  </table>
	  </div>
	  {/if}
	  {/foreach}
      
      </table>
      </div>

<!-- Footer -->
<div>
<!-- 贊助商連結 -->
</div>

</div> <!-- float to left -->