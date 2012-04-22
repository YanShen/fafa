<script style="border: 1px solid #888888;"> 
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
	  
{section name="r" loop=6 start=1 show=TRUE}
      {if sizeof($survey.public[r]) != 0 }
      <div class="regionitemarea">
      <table class="list_table2">
        <tr><td colspan="2" class="regionheader"> 
          <a name="region{$smarty.section.r.iteration}">
          {if $smarty.section.r.iteration == 1}跨區活動
          {elseif $smarty.section.r.iteration == 2}北部活動
          {elseif $smarty.section.r.iteration == 3}中部活動
          {elseif $smarty.section.r.iteration == 4}南部活動
          {elseif $smarty.section.r.iteration == 5}其他地區
          {/if}
          </a> ({$regionCountX[r]})
        </td></tr>
        {section name="s" loop=$survey.public[r] show=TRUE}
         <tr><td>
        {$smarty.section.s.iteration}
         </td><td>
           <a href="{$conf.html}/survey.php?sid={$survey.public[r][s].sid}">
             {$survey.public[r][s].display}
             {if $survey.public[r][s].on_top == 1}
			   <sup style="color:red;">急缺!!</sup>
			 {elseif $survey.public[r][s].createdWithinOneDay}
               <sup style="color:green;">最新!!</sup>
             {elseif $survey.public[r][s].updatedWithinOneDay}
               <sup style="color:blue;">更新!!</sup>
             {/if}
           </a>
         </td></tr>
        {/section}
	  </table>
	  </div>
      {/if}
{/section}
      
      </table>
      </div>

<!-- Footer -->
<div>
<!-- 贊助商連結 -->
</div>

</div> <!-- float to left -->