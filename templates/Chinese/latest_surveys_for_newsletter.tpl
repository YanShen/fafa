
	<div class="page_title">
	  <a href="{$conf.site_url}{$conf.html}/latest_surveys.php?&utm_source=newsletter&utm_medium=email&utm_campaign=regular">最新活動</a> |
	  <a href="{$conf.site_url}{$conf.html}/available_surveys.php?&utm_source=newsletter&utm_medium=email&utm_campaign=regular">全部活動</a> |
	  <a href="http://www.facebook.com/5ifafa88">粉絲團</a>
	  <!-- |
	  <a href="http://hk.groups.yahoo.com/group/MONEY_MONEY/join">訂閱電子報</a> -->
	</div>

	  <div class="page_title">
	    一週內最新活動 <a href="{$conf.site_url}{$conf.html}/available_surveys.php"><span class="page_title_link">(列出全部活動)</span></a>
	  </div>
		<div class="regionitemarea">
        <table class="list_table2" border="1" style="border:1;boarder-color:black;width:100%">

	      {foreach from=$survey.public key="date" item="item2"}
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
			     <a href="{$conf.site_url}{$conf.html}/survey.php?sid={$item4.sid}&utm_source=newsletter&utm_medium=email&utm_campaign=regular">
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
		    {/foreach}
		  {/foreach}
		</table>
		</div>

      
      </table>
      </div>