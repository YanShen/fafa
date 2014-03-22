<head>
<style type="text/css">@import url(css/calendar-win2k-1.css);</style>
</head>
<script type="text/javascript" src="scripts/calendar.js"></script>
<script type="text/javascript" src="scripts/lang/calendar-en.js"></script>
<script type="text/javascript" src="scripts/calendar-setup.js"></script>

    <form method="POST" action="{$conf.html}/edit_survey.php">
      <div style="text-align:center">
        <input type="submit" name="edit_survey_submit" value="儲存">
      </div>
    <input type="hidden" name="mode" value="{$data.mode}">
    <input type="hidden" name="sid" value="{$data.sid}">

      <div class="whitebox">活動名稱 <a href="{$conf.html}/docs/index.html#ep_name">[?]</a></div>

      <div class="indented_cell">
        <input type="text" name="name" value="{$data.name}" size="50"> <br>
         範例:<span style="color:red">[</span>台北<span style="color:red">]</span> 清潔用品座談會<span style="color:red">-</span>車馬費2000<span style="color:blue">(</span>N<span style="color:red">-</span>4/9<span style="color:blue">)</span>
      </div>

      <div class="whitebox">讀取密碼</div>
      
      <div class="indented_cell">
        <input type="text" name="read_password" value="{$data.read_password}">
        報名人需輸入此密碼才能看到活動內容並填寫資料，如不須密碼請不要填值。
      </div>

      <div class="whitebox">顯示狀態</div>
      
      <div class="indented_cell">
        <input type="radio" name="display_state" value="0" {if $data.display_state == 0}checked{/if}>隱藏
        &nbsp;
        <input type="radio" name="display_state" value="1" {if $data.display_state == 1}checked{/if}>顯示
        <br>
        從列表中看不到「隱藏」的活動，但是可透過活動連結直接看到活動內容。管理列表可仍列出所有活動，不會因此選項而隱藏。
      </div>
      
      <div class="whitebox">e-mail標題</div>
      
      <div class="indented_cell">
        <input type="text" size="50" name="mail_subject" value="{$data.mail_subject}">
        請輸入寄出結果時的e-mail標題。
      </div>

      <div class="whitebox">介紹人</div>
      
      <div class="indented_cell">
        <!-- <input type="text" size="80" name="default_referrer" value="{$data.default_referrer}">  -->
        <select style="width:150px" name="default_referrer">
         <option value=""></option>
		 <option value="李凱榕(0955-215636)" {if $data.default_referrer == "李凱榕(0955-215636)"}selected{/if}>李凱榕(0955-215636)</option>
		 <option value="亞磊絲.泰吉華坦" {if $data.default_referrer == "亞磊絲.泰吉華坦"}selected{/if}>亞磊絲.泰吉華坦</option>
		 <option value="李錢玲珍" {if $data.default_referrer == "李錢玲珍"}selected{/if}>李錢玲珍</option>
		 <option value="許定曄" {if $data.default_referrer == "許定曄"}selected{/if}>許定曄</option>
         <option value="沈惠婷" {if $data.default_referrer == "沈惠婷"}selected{/if}>沈惠婷</option>
         <option value="沈政彥" {if $data.default_referrer == "沈政彥"}selected{/if}>沈政彥</option> 
         <option value="郭家榮" {if $data.default_referrer == "郭家榮"}selected{/if}>郭家榮</option>
         <option value="{$data.default_referrer}">{$data.default_referrer}</option>
        </select>
      </div>
      
      <div class="whitebox">收件人e-mail</div>
      
      <div class="indented_cell">
        <input type="text" size="80" name="mail_receiver" value="{$data.mail_receiver}">
        可輸入多個，請使用逗號","分隔。
      </div>

      <div class="whitebox">置頂</div>
      
      <div class="indented_cell">
        <input type="radio" name="on_top" value="1" {if $data.on_top == 1}checked{/if}>是
        &nbsp;
        <input type="radio" name="on_top" value="0" {if $data.on_top == 0}checked{/if}>否
      </div>

      <div class="whitebox">地區</div>
      <div class="indented_cell">
        <input type="radio" name="region" value="1" {if $data.region == 1}checked{/if}>跨區
        &nbsp;
        <input type="radio" name="region" value="2" {if $data.region == 2 || $data.region == 0}checked{/if}>北部
        &nbsp;
        <input type="radio" name="region" value="3" {if $data.region == 3}checked{/if}>中部
        &nbsp;
        <input type="radio" name="region" value="4" {if $data.region == 4}checked{/if}>南部
        &nbsp;
        <input type="radio" name="region" value="5" {if $data.region == 5}checked{/if}>其他 
      </div>
            
      <div class="whitebox">建立時間: </div>
      <div class="indented_cell">
         {$data.created} 	
      </div>

      <div class="whitebox">狀態 <a href="{$conf.html}/docs/index.html#ep_active">[?]</a></div>

      <div class="indented_cell">
        <input type="radio" name="active" value="1"{$data.active_selected}>啟用
        &nbsp;
        <input type="radio" name="active" value="0"{$data.inactive_selected}>停用
      </div>

      <div class="whitebox">開始日期<a href="{$conf.html}/docs/index.html#ep_dates">[?]</a></div>

      <div class="indented_cell">
        如果有指定開始日期與結束日期，則依照此兩日期決定活動是否可用。
        <br />
        若沒有指定開始與結束日期，則依照「狀態」的停/啟用決定活動是否可用。
        <br />
        <input type="text" name="start" id="startDate" size="11" maxlength="10" value="{$data.start_date}"> (yyyy-mm-dd)  <button id="editStartDate">Edit</button>
      </div>

      <div class="whitebox">結束日期</div>

      <div class="indented_cell">
        <input type="text" name="end" id="endDate" size="11" maxlength="10" value="{$data.end_date}"> (yyyy-mm-dd) <button id="editEndDate">Edit</button>
      </div>

      <div class="whitebox">備註(100字以內)</div>

      <div class="indented_cell">
        <textarea name="key_desc" id="keyDesc" cols="40" rows="5">{$data.key_desc}</textarea>
      </div>

      <div class="whitebox">Survey Template <a href="{$conf.html}/docs/index.html#ep_template">[?]</a></div>

      <div class="indented_cell">
        <select name="template" size="1">
          {section name="tem" loop=$data.templates show=TRUE}
            <option value="{$data.templates[tem]}"{$data.selected_template[tem]}>{$data.templates[tem]}</option>
          {/section}
        </select>
      </div>

      <div class="whitebox">Text Modes <a href="{$conf.html}/docs/index.html#ep_text_mode">[?]</a></div>

      <div class="indented_cell">
        These settings control the text modes for survey data (questions and answer values) and user data (answers
        supplied by users taking the survey).
        {section name="fullhtml_warning" loop=1 show=$data.show.fullhtmlwarning}
          Notice: Allowing Full HTML is a security risk. Malicious users can include HTML that will mess up the
          page design and possibly introduce vulernabilities to those who view the HTML they create. It is recommended
          that Full HTML mode not be used for the user_text_mode and only used for survey_text_mode under controlled circumstances.
        {/section}
        <br />
        Survey Text Mode:
          <select name="survey_text_mode" size="1">
            {section name="stm" loop=$data.survey_text_mode_options show=TRUE}
              <option value="{$data.survey_text_mode_options[stm]}"{$data.survey_text_mode_selected[stm]}>{$data.survey_text_mode_values[stm]}</option>
            {/section}
          </select>
        <br />
        User Text Mode:
          <select name="user_text_mode" size="1">
            {section name="utm" loop=$data.user_text_mode_options show=TRUE}
              <option value="{$data.user_text_mode_options[utm]}"{$data.user_text_mode_selected[utm]}>{$data.user_text_mode_values[utm]}</option>
            {/section}
          </select>
      </div>
      <div class="whitebox">Completion Redirect Page <a href="{$conf.html}/docs/index.html#ep_redirect">[?]</a></div>
      <div class="indented_cell">
        This is the page users will be sent to after they complete the survey.<br />
        <input type="radio" name="redirect_page" value="index"{$data.redirect_index}> Main Survey Page <span class="example">(index.php)</span><br />
        <input type="radio" name="redirect_page" value="results"{$data.redirect_results}> Survey Results Page <span class="example">(Results Access should be Public)</span><br />
        <input type="radio" name="redirect_page" value="custom"{$data.redirect_custom}> Custom URL <span class="example">(If URL does not start with http:// or https://, it is assumed to be a relative URL from {$conf.html})</span><br />
        <div style="margin-left:20px">
          URL: <input type="text" name="redirect_page_text" value="{$data.redirect_page_text}" size="30" maxlength="255">
        </div>
      </div>

      <div class="whitebox">Results Date Format <a href="{$conf.html}/docs/index.html#ep_results_date_format">[?]</a></div>

      <div class="indented_cell">
        Format used for Table Results and CSV Export. Must match specifications given for PHP
        <a href="http://www.php.net/date" target="_blank">date()</a> function.<br />
        <input type="text" name="date_format" size="20" value="{$data.date_format}">
      </div>

      <div class="whitebox">Time Limit <a href="{$conf.html}/docs/index.html#ep_time_limit">[?]</a></div>

      <div class="indented_cell">
        Optional time limit to take survey in minutes. Leave blank or zero for no time limit. Time limit begins from
        the time the first question is viewed. Only pages submitted before the time limit is up are saved in the
        results. If the time limit is 60 minutes and page 8 is submitted after 60 minutes, only pages 1 through 7
        are saved.<br />
        <input type="text" name="time_limit" size="5" value="{$data.time_limit}"> (minutes)
      </div>

      <div class="whitebox">Clear Results <a href="{$conf.html}/docs/index.html#ep_clear">[?]</a></div>

      <div class="indented_cell">
        <input type="checkbox" name="clear_answers" value="1">
        Check this box to clear current answers to this survey.
        Answers will be cleared when you press Save Changes below.
      </div>

      <div class="whitebox">Delete Survey <a href="{$conf.html}/docs/index.html#ep_delete">[?]</a></div>

      <div class="indented_cell">
        <input type="checkbox" name="delete_survey" value="1">
        Check this box to Delete the Survey. All questions and answers associated with
        this survey will be erased. There is no way to 'undelete' this information. The
        survey will be deleted when you click Save Changes below.
      </div>

      <br />

      <div style="text-align:center">
        <input type="submit" name="edit_survey_submit" value="儲存">
      </div>
    </form>    
<div id="calendar-container"></div>

<script type="text/javascript">
{literal}
Calendar.setup(
	{
		inputField:"endDate",//IDoftheinputfield
		ifFormat:"%Y-%m-%d",//thedateformat
		button:"editEndDate"//IDofthebutton
	}
);

Calendar.setup(
	{
		inputField:"startDate",//IDoftheinputfield
		ifFormat:"%Y-%m-%d",//thedateformat
		button:"editStartDate"//IDofthebutton
	}
);
{/literal}
</script>

