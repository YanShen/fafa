<script> 
if ('{$smarty.request.result}'=='ok')
    alert('恭喜您完成報名！\n請記得您的介紹人為<<李小姐>>。\n\n＊＊請((不要))說是網路上看到座談會訊息，否則會被市調公司((取消報名資格))喔！＊＊');
</script>
<div class="mainarea">
<div>
      <div class="page_title">
	    一週內最新活動 <a href="available_surveys.php"><span class="page_title_link">(列出全部活動)</span></a>
	  </div>
		<div class="regionitemarea">
        <table class="list_table2">

	      {foreach from=$survey.public  key="date" item="item2"}
		    <tr><td class="latest-date">{$date}</td></tr>
		    {section name=item3 loop=$item2}
				{if item3 }
				  <tr><td>
				  <div>
				  {if $smarty.section.item3.index == 0}
					<div class="latest-update">更新</div>
				  {else}
					<div class="latest-new">新增</div>
				  {/if}
				  </div>
				  <div style="float:left">
				  {foreach from=$item2[item3] item="item4"}
				   <div>
					 <a href="{$conf.html}/survey.php?sid={$item4.sid}">
					 {$item4.display}
					 {if $item4.on_top == 1}
					   <sup style="color:red;">急缺!!</sup>
					 {elseif $item4.createdWithinOneDay}
					   <sup style="color:green;">最新!!</sup>
					 {elseif $item4.updatedWithinOneDay}
					   <sup style="color:blue;">更新!!</sup>
					 {/if}
					 </a>
				   </div>
				  {/foreach}
				  </div>
				  </td></tr>
				{/if}
		    {/section}
		  {/foreach}
		</table>
		</div>

      
      </table>
      </div>

<!-- Footer -->
<div>
<!-- 贊助商連結 -->
</div>

</div> <!-- float to left -->