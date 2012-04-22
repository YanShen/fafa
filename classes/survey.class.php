<?php

//======================================================
// Copyright (C) 2004 John W. Holmes, All Rights Reserved
//
// This file is part of the Unit Command Climate
// Assessment and Survey System (UCCASS)
//
// UCCASS is free software; you can redistribute it and/or
// modify it under the terms of the Affero General Public License as
// published by Affero, Inc.; either version 1 of the License, or
// (at your option) any later version.
//
// http://www.affero.org/oagpl.html
//
// UCCASS is distributed in the hope that it will be
// useful, but WITHOUT ANY WARRANTY; without even the implied warranty
// of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// Affero General Public License for more details.
//======================================================

class UCCASS_Survey extends UCCASS_Main
{
    //Default variables
    var $smarty;
    var $db;
    var $survey_name = '';
    var $CONF;

    /**************
    * CONSTRUCTOR *
    **************/
    function UCCASS_Survey()
    { $this->load_configuration(); }


		function toDate($time) {
			if($time==0) return '';
			else return date('Y-m-d', $time);
		}

		function removeLastBarrels($str) {
			$leftPos = strrpos($str, "("); 
			if($leftPos) {
			  $rightPos = strrpos($str, ")");
			  if($rightPos) {
			   $str = substr($str, 0, $leftPos);
			  }  
			}
			return $str;
		}

    /********************
    * AVAILABLE SURVEYS *
    ********************/
    //function available_surveys()
    function available_surveys($tplName)
    {
    		//Added by yan.
    		if(! isset($tplName) ) {
    			$tplName = 'available_surveys.tpl';
    		}
    	
        if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'logout')
        {
            if(isset($_SESSION['priv']))
            { unset($_SESSION['priv']); }
        }

        $survey = array();

        $x = array(0, 0, 0, 0, 0, 0);         
        $y = 0;
        $now = time();

        //Turn on/off surveys depending on start/end date
        $rs = $this->Query("UPDATE {$this->CONF['db_tbl_prefix']}surveys SET active = 1 WHERE start_date != 0 AND (start_date < $now) OR (start_date < $now AND $now < end_date)");
        $rs = $this->Query("UPDATE {$this->CONF['db_tbl_prefix']}surveys SET active = 0 WHERE end_date != 0 AND ($now < start_date OR $now > end_date)");


        $query = "SELECT sid, name, start_date, end_date, active, region, survey_text_mode, display_state, on_top, created, last_modify_date FROM {$this->CONF['db_tbl_prefix']}surveys WHERE display_state= 1 and hidden=0 ORDER BY created DESC";
        $rs = $this->Query($query, '找不到活動存取設定的資訊 ');
        
        while($r = $rs->FetchRow())
        {
            $survey_name = $this->SfStr->getSafeString($r['name'],$r['survey_text_mode']);
            //remove the content inside the last ()
            $survey_name = $this->removeLastBarrels($survey_name);
            $regionCode = $r['region'];
            //default region is 2.
            if($regionCode=="" || $regionCode=="0") $regionCode = 2;
            
            if($r['active'] == 1)
            {
                $survey['public'][$regionCode][$x[$regionCode]]['display'] = $survey_name;
                $survey['public'][$regionCode][$x[$regionCode]]['sid'] = $r['sid'];
                $survey['public'][$regionCode][$x[$regionCode]]['createdWithinOneDay'] = (time() - $r['created']) < 60 * 60 * 24;
                $survey['public'][$regionCode][$x[$regionCode]]['updatedWithinOneDay'] = (time() - $r['last_modify_date']) < 60 * 60 * 24;
                $survey['results'][$regionCode][$x[$regionCode]] = TRUE;
                $survey['public'][$regionCode][$x[$regionCode]]['on_top'] = $r['on_top'];
                $survey['public'][$regionCode][$x[$regionCode]]['region'] = $r['region'];
                $survey['public'][$regionCode][$x[$regionCode]]['start_date'] = $this->toDate($r['start_date']);
                $survey['public'][$regionCode][$x[$regionCode]]['end_date'] = $this->toDate($r['end_date']);
                
                //testing code 
                //echo "regiondCode=".$regionCode."x[".$regionCode."]=".$x[$regionCode]." ".$survey['public'][$regionCode][$x[$regionCode]]['display']."<br>" ;
                $x[$regionCode] = $x[$regionCode]+1;
            }
        } 
        /*//testing code for list all surveys.
        for($i=0; $i<=5; $i++) {
           for($j=0; $j<$x[$i]; $j++) {
             echo "i=".$i." j=".$j." ".$survey['public'][$i][$j]['display']."<br>" ;
           }
        }
        */

        if(isset($_SESSION['priv']))
        { $show['logout'] = TRUE; }
        else
        { $show['logout'] = FALSE; }

        if(!$this->CONF['create_access'] || $this->_hasPriv(CREATE_PRIV))
        { $show['create_survey'] = TRUE; }
        else
        { $show['create_survey'] = FALSE; }

        $this->smarty->assign_by_ref('show',$show);

        if(isset($survey) && count($survey) > 0)
        { $this->smarty->assign_by_ref("survey",$survey); }
        
        if(isset($x) && count($x) > 0)
        { $this->smarty->assign_by_ref("regionCountX", $x); }

        $retval = $this->smarty->fetch($this->template.'/'.$tplName);
		
        return $retval;
    }

	function get_latest_modify_date()
	{
	    $query = "SELECT last_modify_date FROM {$this->CONF['db_tbl_prefix']}surveys WHERE display_state= 1 and hidden=0 ORDER BY last_modify_date DESC limit 1";
//echo $query."<br>";
        $rs = $this->Query($query, '找不到活動存取設定的資訊 ');
        $r = $rs->FetchRow();
//echo "last_modify_date=".$r["last_modify_date"]."<br>";
		if(!r) return date();
		return $r["last_modify_date"];
	}
	
    /********************
    * LATEST SURVEYS *
    ********************/
    //function latest_surveys()
    function latest_surveys($tplName)
    {
	    $withinDays = 7;
		
   		//Added by yan.
  		if(! isset($tplName) ) {
   			$tplName = 'latest_surveys.tpl';
   		}
    	
        if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'logout')
        {
            if(isset($_SESSION['priv']))
            { unset($_SESSION['priv']); }
        }

        $survey = array();

        $x = array(0, 0, 0, 0, 0, 0);
		$x1 = array();
		
        $y = 0;
        $now = time();

        //Turn on/off surveys depending on start/end date
        $rs = $this->Query("UPDATE {$this->CONF['db_tbl_prefix']}surveys SET active = 1 WHERE start_date != 0 AND (start_date < $now) OR (start_date < $now AND $now < end_date)");
        $rs = $this->Query("UPDATE {$this->CONF['db_tbl_prefix']}surveys SET active = 0 WHERE end_date != 0 AND ($now < start_date OR $now > end_date)");

		$lastModifyDate = ($this->get_latest_modify_date()) - 60 * 60 * 24 * $withinDays;
//echo "lastModifyDate=".$lastModifyDate."<br>";		
        $query = "SELECT sid, name, start_date, end_date, active, survey_text_mode, display_state, on_top, created, last_modify_date, region FROM {$this->CONF['db_tbl_prefix']}surveys WHERE display_state= 1 and hidden=0 and last_modify_date >= ${lastModifyDate} ORDER BY region, last_modify_date DESC";
//echo $query;
        $rs = $this->Query($query, '找不到活動存取設定的資訊 ');
        
        while($r = $rs->FetchRow())
        {
            $survey_name = $this->SfStr->getSafeString($r['name'],$r['survey_text_mode']);
            //remove the content inside the last ()
            $survey_name = $this->removeLastBarrels($survey_name);
            $changeDate=$this->toDate($r['last_modify_date']);
			$createDate=$this->toDate($r['created']);
			$regionCode = $r['region'];
			
			$xIndex="";
			
			if($createDate == $changeDate) {
				$createOrUpdate = 0;
				"update" ;
				$xIndex = $createDate . "." . $createOrUpdate;
			}
			else {
				$createOrUpdate = 1;
				$xIndex = $changeDate . "." . $createOrUpdate;
			}
						
            if($r['active'] == 1)
            {
                $survey['public'][$regionCode][$changeDate][$createOrUpdate][$x1[$xIndex]]['display'] = $survey_name;
                $survey['public'][$regionCode][$changeDate][$createOrUpdate][$x1[$xIndex]]['sid'] = $r['sid'];
                $survey['public'][$regionCode][$changeDate][$createOrUpdate][$x1[$xIndex]]['createdWithinOneDay'] = (time() - $r['created']) < 60 * 60 * 24;
                $survey['public'][$regionCode][$changeDate][$createOrUpdate][$x1[$xIndex]]['updatedWithinOneDay'] = (time() - $r['last_modify_date']) < 60 * 60 * 24;
                $survey['public'][$regionCode][$changeDate][$createOrUpdate][$x1[$xIndex]]['on_top'] = $r['on_top'];
                $survey['public'][$regionCode][$changeDate][$createOrUpdate][$x1[$xIndex]]['region'] = $r['region'];
                $survey['public'][$regionCode][$changeDate][$createOrUpdate][$x1[$xIndex]]['start_date'] = $this->toDate($r['start_date']);
                $survey['public'][$regionCode][$changeDate][$createOrUpdate][$x1[$xIndex]]['end_date'] = $this->toDate($r['end_date']);
                
                $x[$regionCode] = $x[$regionCode]+1;
				
				//testing code
                //echo "regionCode=".$regionCode." changeDate=".$changeDate." createOrUpdate=".$createOrUpdate." x[".$xIndex."]=".$x1[$xIndex]." ".$survey['public'][$regionCode][$changeDate][$createOrUpdate][$x1[$xIndex]]['display']."<br>" ;
                $x1[$xIndex] = $x1[$xIndex]+1;
				
				}
        } 
        //testing code for list all surveys.
		/*
        for($i=0; $i<=5; $i++) {
		echo "i".sizeof($survey['public'][$i]);
           foreach($survey['public'][$i] as $key1 => $value1) {
		echo "j".count($survey['public'][$i][$j]);
		     foreach($value1 as $key2 => $value2) {
		echo "k".count($survey['public'][$i][$j][$k]);
				foreach($value2 as $key3 => $value3) {
					echo "i=".$i." k1=".$key1." k2=".$key2." k3=".$key3." ".$value3['display']."<br>" ;
				}
			 }
           }
        }
		*/
        
        if(isset($_SESSION['priv']))
        { $show['logout'] = TRUE; }
        else
        { $show['logout'] = FALSE; }

        if(!$this->CONF['create_access'] || $this->_hasPriv(CREATE_PRIV))
        { $show['create_survey'] = TRUE; }
        else
        { $show['create_survey'] = FALSE; }

        $this->smarty->assign_by_ref('show',$show);

        if(isset($survey) && count($survey) > 0)
        { $this->smarty->assign_by_ref("survey",$survey); }
        
        if(isset($x) && count($x) > 0)
        { $this->smarty->assign_by_ref("regionCountX", $x); }

        $retval = $this->smarty->fetch($this->template.'/'.$tplName);

        return $retval;
    }
	
	
		//activeType 分 1:active; 0:inactive; -1:all
    function active_surveys($tplName, $activeType)
    {
    		//Added by yan.
    		if(! isset($tplName) ) {
    			$tplName = 'admin_surveys.tpl';
    		}
    	
        if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'logout')
        {
            if(isset($_SESSION['priv']))
            { unset($_SESSION['priv']); }
        }

        $survey = array();

        $x = 0;
        $y = 0;
        $now = time();

        //Turn on/off surveys depending on start/end date
        $rs = $this->Query("UPDATE {$this->CONF['db_tbl_prefix']}surveys SET active = 1 WHERE start_date != 0 AND (start_date < $now) OR (start_date < $now AND $now < end_date)");
        $rs = $this->Query("UPDATE {$this->CONF['db_tbl_prefix']}surveys SET active = 0 WHERE end_date != 0 AND ($now < start_date OR $now > end_date)");

        //Added for verify is new answers are entered.選出包含新答案的活動，整理出這些活動的sid陣列。
        $sidWithNewAns = array();
        //在這裡假設每個 survey 都會有 results_text 的資料，但不一定有 results
        //同時，改用新方法來判斷是否有新的報名紀錄：在surveys.last_marked內存放最後標示的時間
        //再來比較results_text內的最後時間。
        $query = "select a.sid, count(qid) as cn from results_text a, surveys b where a.sid = b.sid and (a.entered > b.last_marked or b.last_marked is null) group by sid";
        $rs = $this->Query($query, '找不到標示是否有新答案的資訊');
        while($r = $rs->FetchRow())
        {
            $sidWithNewAns[$r['sid']] = $r['cn'];
        }
/*
        $query = "select a.sid, count(qid) as cn from results_text a, (select sid, max(marked) as marked from results_text group by sid) b where a.sid = b.sid and (a.entered > b.marked or b.marked is null) group by sid";
        $rs = $this->Query($query, '找不到標示是否有新答案的資訊');
        while($r = $rs->FetchRow())
        {
            $sidWithNewAns[$r['sid']] = $r['cn'];
        }
        $query = "select a.sid, count(qid) as cn from results a, (select sid, max(marked) as marked from results group by sid) b where a.sid = b.sid and (a.entered > b.marked or b.marked is null) group by sid";
        $rs = $this->Query($query, '找不到標示是否有新答案的資訊');
        while($r = $rs->FetchRow())
        {
            $sidWithNewAns[$r['sid']] = $sidWithNewAns[$r['sid']] + $r['cn'];
        }
*/

        $query = "SELECT sid, name, region, start_date, end_date, active, survey_text_mode, display_state, on_top FROM {$this->CONF['db_tbl_prefix']}surveys WHERE hidden=0 ORDER BY start_date DESC, end_date DESC, name ASC";
        $rs = $this->Query($query, '找不到活動存取設定的資訊');
        while($r = $rs->FetchRow())
        { 
            $displayThisSurvey = false;
        	  switch($activeType) {
        	   	case 0: 
        	   	    if($r['active'] != 1) {
        	   	     	$displayThisSurvey = true;
        	   	    }
        	   	    break;
        	   	case 1: 
        	   	    if($r['active'] == 1) {
        	   	     	$displayThisSurvey = true;
        	   	    }
        	   	    break;
        	   	default:
        	   	    $displayThisSurvey = true;
        	  }
        	   
        	  if($displayThisSurvey) {
                $survey_name = $this->SfStr->getSafeString($r['name'],$r['survey_text_mode']);
                $survey['all_surveys']['name'][] = $survey_name;
                $survey['all_surveys']['sid'][] = $r['sid'];
                $survey['all_surveys']['region'][] = $r['region'];
                $survey['all_surveys']['start_date'][] = $this->toDate($r['start_date']);;
                $survey['all_surveys']['end_date'][] = $this->toDate($r['end_date']);
                $survey['all_surveys']['active'][] = $r['active'];

                //置頂的則標示置頂
                $survey['all_surveys']['on_top'][] = $r['on_top'];
                //隱藏的則標示隱藏
                $survey['all_surveys']['display_state'][] = $r['display_state']; 
                
                //如果目前處理的活動也出現在有新答案的活動內，則標withNew = true;
                if(isset($sidWithNewAns[$r['sid']])) {
                	$survey['all_surveys']['withNew'][] = $sidWithNewAns[$r['sid']];
                } else {
                	$survey['all_surveys']['withNew'][] = 0;
                }
            }
        }

        if(isset($_SESSION['priv']))
        { $show['logout'] = TRUE; }
        else
        { $show['logout'] = FALSE; }

        if(!$this->CONF['create_access'] || $this->_hasPriv(CREATE_PRIV))
        { $show['create_survey'] = TRUE; }
        else
        { $show['create_survey'] = FALSE; }

        $this->smarty->assign_by_ref('show',$show);

        if(isset($survey) && count($survey) > 0)
        { $this->smarty->assign_by_ref("survey",$survey); }

        $this->smarty->assign_by_ref('activeType', $activeType);

        $retval = $this->smarty->fetch($this->template.'/'.$tplName);

        return $retval;
    }

    //New method for reader to input password for survey which requires read password
    function showReadPassword($page, $hidden)
    {
        //Set required data for login page
        //and show login form
        $data['page'] = $page;
        if(is_array($hidden))
        {
            foreach($hidden as $key=>$val)
            {
                $data['hiddenkey'][] = $key;
                $data['hiddenval'][] = $val;
            }
        }
        $this->smarty->assign_by_ref('data',$data);
        return $this->smarty->Fetch($this->template.'/read_password.tpl');
    }


    /**************
    * TAKE SURVEY *
    **************/
    function take_survey($sid)
    {
        $sid = (int)$sid;
        $check = $this->_CheckAccess($sid,TAKE_PRIV,"survey.php?sid=$sid");
        if($check !== TRUE)
        {
            $ac_control = $this->_getAccessControl($sid);
            if($check === ALREADY_COMPLETED || ($ac_control != AC_USERNAMEPASSWORD && $ac_control != AC_INVITATION))
            { 
                $this->setMessageRedirect("index.php");
                $this->setMessage('注意事項','您已經完成了這個市調活動的報名程序。',MSGTYPE_NOTICE);
            }
            else
            {
                switch($this->_getAccessControl($sid))
                {
                    case AC_USERNAMEPASSWORD:
                        return $this->showLogin('survey.php',array('sid'=>$sid));
                    break;
                    case AC_INVITATION:
                        return $this->showInvite('survey.php',array('sid'=>$sid));
                    break;
                }
            }
        }
        //defaults
        $show['previous_button'] = TRUE;
        $show['next_button'] = TRUE;
        $show['quit_button'] = TRUE;
        $show['page_num'] = TRUE;
        $now = time();
        $stay_on_same_page = 0;

        if(!isset($_SESSION['take_survey']['sid']))
        { $_SESSION['take_survey']['sid'] = $sid; }
        elseif($_SESSION['take_survey']['sid'] != $sid)
        {
            unset($_SESSION['take_survey']);
            $_SESSION['take_survey']['sid'] = $sid;
        }

        $survey['sid'] = $sid;
        if(!isset($_SESSION['take_survey']['page']))
        { $_SESSION['take_survey']['page'] = 1; }

        if(!isset($_SESSION['take_survey']['start_time']))
        { $_SESSION['take_survey']['start_time'] = time(); }

        if(isset($_REQUEST['preview_survey']))
        { $_SESSION['take_survey']['preview_survey'] = TRUE; }

        //Retrieve survey information
        $rs = $this->db->Execute("SELECT s.name, s.start_date, s.end_date, s.redirect_page,
            s.active, MAX(q.page) AS max_page, s.template, s.survey_text_mode, s.user_text_mode, s.time_limit,read_password
            FROM {$this->CONF['db_tbl_prefix']}surveys s, {$this->CONF['db_tbl_prefix']}questions q
            WHERE s.sid = $sid AND s.sid = q.sid GROUP BY q.sid");

        if($rs === FALSE) { $this->error("Error retrieving Survey:" . $this->db->ErrorMsg());return; }
        if($r = $rs->FetchRow($rs))
        {
            if(($r['active'] == 0 || $now < $r['start_date'] || ($now > $r['end_date'] && $r['end_date'] != 0)) && !isset($_SESSION['take_survey']['preview_survey']))
            { $this->error("Survey #$sid. <em>{$r['name']}</em> in not active at this time");return; }
        }
        else
        { $this->error("Survey $sid does not exist or has no questions."); return; }

        //check reader has permission
        if((strlen($r['read_password']) > 0 ) && ($_REQUEST['read_password'] == $r['read_password'])) {
          $_SESSION['sid_'.$sid] = "checked";
        }
        $survey['read_password'] = $r['read_password'];
        $this->smarty->assign('survey', $survey);
        //if he has no permission, prompt password input screen.
        if( !isset($_SESSION['priv'][0][ADMIN_PRIV]) &&
            (strlen($r['read_password']) > 0 ) &&
            !isset($_SESSION['sid_'.$sid]) ) {
         	return $this->showReadPassword('survey.php', array('sid'=>$sid));
        }

        $survey = array_merge($survey,$r);
        //Set survey name to be used outside
        //of class to set page title
        $this->survey_name = $this->SfStr->getSafeString($r['name']);
        $survey['name'] = $this->SfStr->getSafeString($survey['name'],$survey['survey_text_mode']);
        //remove last barrels
        $survey['name'] = $this->removeLastBarrels($survey['name']);
        
        //read_password  
        $survey['read_password'] = $r['read_password'];
        $_SESSION['take_survey']['redirect_page'] = $r['redirect_page'];

        if($this->CONF['default_template'] != $survey['template'])
        {
            if(!$this->set_template_paths($survey['template']))
            { $this->error("Unable to load template for survey. Expecting to find template in {$this->CONF['template_path']}"); return; }
        }

        $survey['total_pages'] = $r['max_page'];
        $now = time();
        $survey['elapsed_hours'] = floor(($now - $_SESSION['take_survey']['start_time']) / 3600);
        $survey['elapsed_minutes'] = floor(($now - $_SESSION['take_survey']['start_time']) / 60);
        $survey['elapsed_seconds'] = sprintf('%02d',($now - $_SESSION['take_survey']['start_time']) % 60);

        if(isset($_REQUEST['quit']))
        { $_SESSION['take_survey']['page'] = $survey['total_pages']+2; }

        /////////////////////////
        // PROCESS SURVEY PAGE //
        /////////////////////////
        //Verify answers to required questions have been provided
        $page = $_SESSION['take_survey']['page'];

        if(isset($_SESSION['take_survey']['req'][$page]) && !isset($_REQUEST['previous']) && !isset($_SESSION['take_survey']['preview_survey']))
        {
            foreach($_SESSION['take_survey']['req'][$page] as $qid=>$num_required)
            {
                //Check for no answers submitted or less than required
                if(!isset($_REQUEST['answer'][$qid]))
                {
                    $error = "您有必填欄位未填寫";
                    $stay_on_same_page = 1;
                }
                else
                {
                    $num_answered = 0;
                    foreach($_REQUEST['answer'][$qid] as $value)
                    {
                        if(is_array($value))
                        {
                            foreach($value as $value2)
                            {
                                if(strlen($value2) > 0)
                                { $num_answered++; }
                            }
                        }
                        else
                        {
                            if(strlen($value) > 0)
                            { $num_answered++; }
                        }
                    }

                    if($num_answered < $num_required)
                    {
                        $error = '[您有必填欄位未填寫]';
                        $stay_on_same_page = 1;
                    }
                }
            }
            if(!PhpCaptcha::Validate($_POST['captcha_code'])) {
              $error = $error.'[驗證碼錯誤或未輸入]';
              $stay_on_same_page = 1;
            }
        }

        //Check for answers being present and only
        //save answers into session if time limit hasn't
        //been passed
        if(isset($_REQUEST['answer']) && ($survey['time_limit']==0 || ($now < $_SESSION['take_survey']['start_time'] + (60 * $survey['time_limit']) + 5)))
        {
            foreach($_REQUEST['answer'] as $qid=>$value)
            {
                $qid = (int)$qid;

                if(isset($_SESSION['take_survey']['answer'][$qid]))
                { unset($_SESSION['take_survey']['answer'][$qid]); }
                if(!empty($value))
                {
                    foreach($value as $answernum=>$avid)
                    {
                        $cnt = 0;
                        if(is_array($avid))
                        {
                            foreach($avid as $answernum2=>$avid2)
                            {
                                if(strlen($avid2) > 0)
                                {
                                    $_SESSION['take_survey']['answer'][$qid][$answernum][$answernum2] = $avid2;
                                    $_SESSION['take_survey']['lookback'][$qid][$cnt++] = $avid2;
                                }
                            }
                        }
                        else
                        {
                            if(strlen($avid) > 0)
                            {
                                $_SESSION['take_survey']['answer'][$qid][$answernum] = $avid;
                                $_SESSION['take_survey']['lookback'][$qid][$cnt++] = $avid;
                            }
                        }
                    }
                }
            }
        }

        if(!$stay_on_same_page)
        {
            if(isset($_REQUEST['next']) && $_SESSION['take_survey']['page'] < $survey['total_pages']+1)
            { $_SESSION['take_survey']['page']++; }
            elseif(isset($_REQUEST['previous']) && $_SESSION['take_survey']['page'] > 1)
            { $_SESSION['take_survey']['page']--; }
        }

        if($survey['time_limit'] && ($now > $_SESSION['take_survey']['start_time'] + (60 * $survey['time_limit']) + 5))
        {
            $_SESSION['take_survey']['page'] = $survey['total_pages']+1;
            $this->setMessage('Time Limit Exceeded','You exceeded the time limit set for the survey. Your last page of results were not saved.');
        }

        //////////////////////
        // SHOW SURVEY PAGE //
        //////////////////////
        switch($_SESSION['take_survey']['page'])
        {
            //Process answers to survey
            case $survey['total_pages']+1:
                if(!isset($_SESSION['take_survey']['preview_survey']))
                { $this->process_answers($_SESSION['take_survey']); }

                switch($_SESSION['take_survey']['redirect_page'])
                {
                    case 'index':
                    case '': 
//                        $url = $this->CONF['html'] . '/index.php';
                         //Yan: for displaying ok message.
                        $url = $this->CONF['html'] . '/index.php?result=ok';
                    break;
                    case 'results':
                        $url = $this->CONF['html'] . '/results.php?sid=' . $sid;
                    break;
                    default:
                        if(preg_match('!^https?://!',$_SESSION['take_survey']['redirect_page']))
                        { $url = $_SESSION['take_survey']['redirect_page']; }
                        else
                        {
                            if(substr($_SESSION['take_survey']['redirect_page'],0,1) != '/')
                            { $_SESSION['take_survey']['redirect_page'] = '/' . $_SESSION['take_survey']['redirect_page']; }
                            $url = $this->CONF['html'] . $_SESSION['take_survey']['redirect_page'];
                        }
                    break;
                }

                unset($_SESSION['take_survey']);
                header("Location: $url");
                exit();
                break;

            //Quit survey message
            case $survey['total_pages']+2:
                $show['quit'] = TRUE;
                $show['main_url'] = TRUE;
                $show['previous_button'] = FALSE;
                $show['next_button'] = FALSE;
                $show['quit_button'] = FALSE;
                $show['page_num'] = FALSE;

                $etime = $now - $_SESSION['take_survey']['start_time'];
                $sequence = $this->db->GenID($this->CONF['db_tbl_prefix'].'sequence');
                $query = "INSERT INTO {$this->CONF['db_tbl_prefix']}time_limit (sequence,sid,elapsed_time,quitflag)
                          VALUES ($sequence,$sid,$etime,1)";
                $rs = $this->db->Execute($query);
                if($rs === FALSE) { $this->error('Error updating elapsed time: ' . $this->db->ErrorMsg()); }
                unset($_SESSION['take_survey']);
                break;

            //Questions
            case $survey['total_pages']:
                $button['next'] = '報名';

            default:
                $show['question'] = TRUE;

                //Get all questions for current page
                $page = $_SESSION['take_survey']['page'];

                //Clear requirements for current page
                $_SESSION['take_survey']['req'][$page] = array();

                $qpage = $_SESSION['take_survey']['page'];

                if(!isset($_SESSION['take_survey']['qstart'][1]))
                { $_SESSION['take_survey']['qstart'][1] = 1; }

                $qstart = $_SESSION['take_survey']['qstart'][$page];

                //Retrieve dependencies for current page
                $sql = "SELECT d.qid, d.dep_qid, d.dep_aid, d.dep_option FROM {$this->CONF['db_tbl_prefix']}dependencies d,
                        {$this->CONF['db_tbl_prefix']}questions q WHERE d.sid = $sid AND d.qid = q.qid AND
                        q.page = $qpage";
                $rs = $this->db->Execute($sql);
                if($rs === FALSE)
                { $this->error("Error retrieving dependencies: " . $this->db->ErrorMsg()); return; }

                if($r = $rs->FetchRow($rs))
                {
                    $check_dependencies = 1;
                    do
                    {
                        $depend[$r['qid']]['dep_qid'][] = $r['dep_qid'];
                        $depend[$r['qid']]['dep_aid'][] = $r['dep_aid'];
                        $depend[$r['qid']]['dep_option'][] = $r['dep_option'];
                    }while($r = $rs->FetchRow($rs));

                    $depend_keys = array_keys($depend);
                }
                else
                { $check_dependencies = 0; }

                //Retrieve questions for current page
                $sql = "select q.qid, q.question, q.num_answers, q.num_required, q.orientation, a.type, a.label, a.aid  from
                        {$this->CONF['db_tbl_prefix']}questions q, {$this->CONF['db_tbl_prefix']}answer_types a
                        where q.sid = $sid and q.aid = a.aid and q.page=$qpage order by q.oid ASC";

                $rs = $this->db->Execute($sql);
                if($rs === FALSE) { $this->error("Error selecting questions: " . $this->db->ErrorMsg()); return(FALSE);}
                $x = 0;
                $no_counts = 0;
                $question_text = '';
                $matrix_aid = FALSE;
                $end_matrix = FALSE;
                $begin_matrix = FALSE;

                while($r = $rs->FetchRow())
                {
                    $hide_question = 0;
                    $require_question = 0;
                    $show_question = 0;
                    $q = array();

                    //Check if current question has any dependencies
                    if($check_dependencies && in_array($r['qid'],$depend_keys))
                    {
                        //current question has dependencies, so loop
                        //through the dependent question
                        foreach($depend[$r['qid']]['dep_qid'] as $key => $dep_qid)
                        {
                            //and see if user has given an answer for each
                            //dependant question
                            if(isset($_SESSION['take_survey']['answer'][$dep_qid]))
                            {
                                //user has given answer, so see if dependant answer
                                //is present in the answers the user chose
                                //First check if answer saved in session is an
                                //array or not
                                if(is_array($_SESSION['take_survey']['answer'][$dep_qid]))
                                {
                                    //Answer is an array (such as MM). Loop through
                                    //answer array and look for matching dependant answer
                                    foreach($_SESSION['take_survey']['answer'][$dep_qid] as $aid)
                                    {
                                        if(is_array($aid))
                                        {
                                            if(in_array($depend[$r['qid']]['dep_aid'][$key],$aid))
                                            {
                                                switch($depend[$r['qid']]['dep_option'][$key])
                                                {
                                                    case 'Hide':
                                                        $hide_question = 1; break;
                                                    case 'Require':
                                                        $require_question = 1; break;
                                                    case 'Show':
                                                        $show_question = 1; break;
                                                }
                                            }
                                        }
                                        elseif($aid == $depend[$r['qid']]['dep_aid'][$key])
                                        {
                                            switch($depend[$r['qid']]['dep_option'][$key])
                                            {
                                                case 'Hide':
                                                    $hide_question = 1; break;
                                                case 'Require':
                                                    $require_question = 1; break;
                                                case 'Show':
                                                    $show_question = 1; break;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if($hide_question && !$show_question)
                    { unset($_SESSION['take_survey']['answer'][$r['qid']]); }
                    else
                    {
                        $q['qid'] = $r['qid'];

                        //Look for lookback text within the question
                        if(strpos($r['question'],LOOKBACK_START_DELIMITER.LOOKBACK_TEXT)!== FALSE)
                        { $q['question'] = $this->_process_Lookback($r['question'],$survey['survey_text_mode'],$survey['user_text_mode']); }
                        else
                        { $q['question'] = nl2br($this->SfStr->getSafeString($r['question'],$survey['survey_text_mode'])); }

                        $q['num_answers'] = $r['num_answers'];

                        if($require_question)
                        { $r['num_required'] = $r['num_answers']; }

                        if($r['num_required'] > 0 && $r['type'] != ANSWER_TYPE_N)
                        {
                            $_SESSION['take_survey']['req'][$page][$r['qid']] = $r['num_required'];
                            $q['num_required'] = $r['num_required'];

                            if($r['num_answers'] > 1)
                            { $q['req_label'] = $r['num_required']; }

                            $q['required_text'] = $this->smarty->fetch($this->template.'/question_required.tpl');
                        }

                        $q['label'] = $this->SfStr->getSafeString($r['label'],$survey['survey_text_mode']);

                        //if this is the first question (introduction), replace @sid to sid.
                        if($r['type']==ANSWER_TYPE_N) {
                         	 $q['question'] = str_replace("%sid%", $sid, $q['question']);
                        }

                        if($r['type'] == ANSWER_TYPE_T || $r['type'] == ANSWER_TYPE_S || $r['type'] == ANSWER_TYPE_N)
                        {
                            $q[$r['type']][$x] = TRUE;
                            $q['value'][$x] = '';

                            if(isset($_SESSION['take_survey']['answer'][$r['qid']]))
                            { $q['answer'] = $this->SfStr->getSafeString($_SESSION['take_survey']['answer'][$r['qid']],SAFE_STRING_TEXT,1); }

                            if($matrix_aid)
                            {
                                $matrix_aid = FALSE;
                                $end_matrix = TRUE;
                            }

                            $template = "take_survey_question_{$r['type']}.tpl";
                        }
                        else
                        {
                            //Get arrays of answers values and answer avid numbers
                            //Answer values are returned properly escaped according
                            //to the survey_text_mode setting for the survey
                            $tmp = $this->get_answer_values($r['aid'],BY_AID,$survey['survey_text_mode']);
                            $q['value'] = $tmp['value'];
                            $q['avid'] = $tmp['avid'];

                            $q['num_values'] = count($q['value']);

                            $r['orientation'] = substr($r['orientation'],0,1);

                            $xx = 0;

                            switch($r['orientation'])
                            {
                                //Vertical & Horizontal
                                case ANSWER_ORIENTATION_V:
                                case ANSWER_ORIENTATION_H:
                                    $template = "take_survey_question_{$r['type']}_{$r['orientation']}.tpl";
                                    $selected_text = FORM_CHECKED;
                                    if($matrix_aid)
                                    {
                                        $matrix_aid = FALSE;
                                        $end_matrix = TRUE;
                                    }
                                break;

                                //Dropdown
                                case ANSWER_ORIENTATION_D:
                                    $template = "take_survey_question_{$r['type']}_{$r['orientation']}.tpl";
                                    $selected_text = FORM_SELECTED;
                                    if($matrix_aid)
                                    {
                                        $matrix_aid = FALSE;
                                        $end_matrix = TRUE;
                                    }
                                break;

                                //Matrix
                                case ANSWER_ORIENTATION_M:
                                    $selected_text = FORM_CHECKED;
                                    if($matrix_aid != $r['aid'])
                                    {
                                        if($matrix_aid !== FALSE)
                                        { $end_matrix = TRUE; }

                                        $begin_matrix = TRUE;
                                        $matrix_aid = $r['aid'];
                                    }
                                    $template = "take_survey_question_{$r['type']}_{$r['orientation']}.tpl";
                                break;
                            }

                            if(isset($_SESSION['take_survey']['answer'][$r['qid']]))
                            {
                                switch($r['type'])
                                {
                                    case ANSWER_TYPE_MM:
                                        foreach($_SESSION['take_survey']['answer'][$r['qid']] as $value)
                                        {
                                            if(is_array($value))
                                            {
                                                foreach($value as $val)
                                                {
                                                    $key = array_search($val,$q['avid']);
                                                    $q['selected'][$xx][$key] = $selected_text;
                                                }
                                                $xx++;
                                            }
                                        }
                                    break;

                                    case ANSWER_TYPE_MS:
                                        foreach($_SESSION['take_survey']['answer'][$r['qid']] as $value)
                                        {
                                            $key = array_search($value,$q['avid']);
                                            $q['selected'][$xx++][$key] = $selected_text;
                                        }
                                    break;
                                }
                            }
                        }

                        if($r['type'] != ANSWER_TYPE_N)
                        { $q['question_num'] = $qstart + $x - $no_counts; }
                        else
                        { $no_counts++; }

                        $this->smarty->assign_by_ref('q',$q);


                        if($end_matrix)
                        {
                            $question_text .= $this->smarty->fetch($this->template.'/take_survey_question_MF.tpl');
                            $end_matrix = FALSE;
                        }

                        if($begin_matrix)
                        {
                            $question_text .= $this->smarty->fetch($this->template.'/take_survey_question_MH.tpl');
                            $begin_matrix = FALSE;
                        }

                        $question_text .= $this->smarty->fetch($this->template.'/'.$template);

                        $x++;
                    }
                }

                if($matrix_aid !== FALSE)
                {
                    $matrix_aid = FALSE;
                    $end_matrix = FALSE;
                    $begin_matrix = FALSE;
                    $question_text .= $this->smarty->fetch($this->template.'/take_survey_question_MF.tpl');
                }

                $_SESSION['take_survey']['qstart'][$page+1] = $qstart + $x - $no_counts;

                if(empty($question_text))
                { return $this->take_survey($sid); }

            //End default display
            break;
        }

        if(isset($_SESSION['take_survey']['page']))
        { $survey['page'] = $_SESSION['take_survey']['page']; }

        if(isset($button))
        { $this->smarty->assign("button",$button); }

        $this->smarty->assign("survey",$survey);
        $this->smarty->assign("show",$show);

        if(isset($question_text))
        { $this->smarty->assign('question_text',$question_text); }

        if(isset($error))
        { $this->smarty->assign('error',$error); }
        if(isset($message))
        { $this->smarty->assign('message',$message); }

        return $this->smarty->fetch($this->template.'/take_survey.tpl');
    }

    /*****************************************
    * PROCESS LOOKBACKS WITHIN QUESTION TEXT *
    *****************************************/
    function _process_Lookback($question, $survey_text_mode, $user_text_mode)
    {
        $_SESSION['take_survey']['lookback_user_text_mode'] = $user_text_mode;
        $_SESSION['take_survey']['lookback_survey_text_mode'] = $survey_text_mode;

        $pattern = '/(^|.*)' . preg_quote(LOOKBACK_START_DELIMITER . LOOKBACK_TEXT) . '\.([0-9]+)' . preg_quote(LOOKBACK_END_DELIMITER) . '(.*|$)/sU';
        return preg_replace_callback($pattern,array($this,'_lookback_callback'),$question);
    }

    //Function to conduct lookback replacements
    function _lookback_callback($matches)
    {
        $retval = '';
        if(isset($matches[2]) && isset($_SESSION['take_survey']['lookback'][$matches[2]]));
        {
            $qid = $matches[2];
            $answers = $this->get_answer_values($qid,BY_QID,$_SESSION['take_survey']['lookback_survey_text_mode']);

            $ans = array();
            foreach($_SESSION['take_survey']['lookback'][$qid] as $avid)
            {
                if(empty($answers['avid']))
                { $ans[] = nl2br($this->SfStr->getSafeString($avid,$_SESSION['take_survey']['lookback_user_text_mode']));}
                else
                {
                    $avid_key = array_search($avid,$answers['avid']);
                    if($avid_key !== FALSE)
                    { $ans[] = $answers['value'][$avid_key]; }
                    else
                    { $ans[] = nl2br($this->SfStr->getSafeString($avid,$_SESSION['take_survey']['lookback_user_text_mode'])); }
                }
            }
            $retval = implode(', ',$ans);
        }

        if(empty($retval))
        { $retval = $matches[0]; }
        else
        { $retval = nl2br($this->SfStr->getSafeString($matches[1],$_SESSION['take_survey']['lookback_survey_text_mode'])) . $retval . nl2br($this->SfStr->getSafeString($matches[3],$_SESSION['take_survey']['lookback_survey_text_mode'])); }

        return $retval;
    }

    /****************************
    * PROCESS ANSWERS TO SURVEY *
    ****************************/
    function process_answers($survey)
    {
        //Get sequence number to identify this answer set
        $id = $this->db->GenID($this->CONF['db_tbl_prefix'].'sequence');
        $now = time();

        $access_control = $this->_getAccessControl($survey['sid']);

        //Track the IP address of user and the survey
        //they are answering if enabled
        if($this->CONF['track_ip'] || $access_control == AC_IP)
        {
            $ip = $this->SfStr->getSafeString($_SERVER['REMOTE_ADDR'],SAFE_STRING_DB);
            $this->db->Execute("INSERT INTO {$this->CONF['db_tbl_prefix']}ip_track (ip,sid,completed) VALUES({$ip},{$survey['sid']},$now)");
        }

        switch($access_control)
        {
            case AC_COOKIE:
                $name = 'uccass'.md5($survey['sid']);
                $now = time();

                if(isset($_COOKIE[$name]))
                {
                    $value = unserialize($_COOKIE[$name]);
                    if(is_array($value))
                    { $value[] = $now; }
                    else
                    { $value = array($now); }
                }
                else
                { $value = array($now); }

                $value = serialize($value);
                setcookie($name,$value,time()+31557600);
            break;
            case AC_INVITATION:
            case AC_USERNAMEPASSWORD:
                $now = time();
                $query = "INSERT INTO {$this->CONF['db_tbl_prefix']}completed_surveys (uid, sid, completed) VALUES ({$_SESSION['priv'][$survey['sid']]['uid']},{$survey['sid']},$now)";
                $rs = $this->db->Execute($query);
            break;
        }

        //Get all question numbers for current survey
        $results_text = array();
        $results = array();

        //remove spaces from after commas in filter text
        //and create array to search within
        if(isset($this->CONF['text_filter']) && !empty($this->CONF['text_filter']))
        {
            $this->CONF['text_filter'] = str_replace(', ',',',$this->CONF['text_filter']);
            $text_filter = explode(',',$this->CONF['text_filter']);
        }
        else
        { $text_filter = array(); }

        $rs = $this->db->Execute("SELECT q.qid, a.type FROM {$this->CONF['db_tbl_prefix']}questions q,
                                  {$this->CONF['db_tbl_prefix']}answer_types a WHERE q.aid = a.aid AND
                                  q.sid = {$survey['sid']}");
        if($rs === FALSE) { $this->error("Error selecting questions: " . $this->db->ErrorMsg()); }
        while($r = $rs->FetchRow($rs))
        {
            if(isset($survey['answer'][$r['qid']]))
            {
                foreach($survey['answer'][$r['qid']] as $answer)
                {
                    switch($r['type'])
                    {
                        case "T":
                        case "S":
                            //Do not save answer if it's empty or matches a word
                            //in the text filter list set in the INI file.
                            if(!empty($answer) && !in_array(strtolower($answer),$text_filter))
                            {
                                $rid = $this->db->GenID($this->CONF['db_tbl_prefix'].'results_text_sequence');
                                $answer = $this->SfStr->getSafeString($answer,SAFE_STRING_DB);
                                $results_text[] = "($rid,$id,{$survey['sid']},{$r['qid']},$answer,$now)";
                            }
                            break;

                        case "MM":
                            if(is_array($answer))
                            {
                                foreach($answer as $a)
                                {
                                    $a = (int)$a;
                                    if($a)
                                    {
                                        $rid = $this->db->GenID($this->CONF['db_tbl_prefix'].'results_sequence');
                                        $results[] = "($rid,$id,{$survey['sid']},{$r['qid']},$a,$now)";
                                    }
                                }
                            }
                            break;

                        case "MS":
                            $answer = (int)$answer;
                            if($answer)
                            {
                                $rid = $this->db->GenID($this->CONF['db_tbl_prefix'].'results_sequence');
                                $results[] = "($rid,$id,{$survey['sid']},{$r['qid']},$answer,$now)";
                            }
                            break;
                    }
                }
            }
        }

        //insert answers to questions in each table
        if(count($results_text) > 0)
        
        {
            $t_string = implode(",",$results_text);
             //Yan: 去掉答案頭尾的空白
            $t_string = trim($t_string);
            $rs = $this->db->Execute("INSERT INTO {$this->CONF['db_tbl_prefix']}results_text (rid, sequence, sid, qid, answer, entered) VALUES $t_string");
            if($rs === FALSE)
            { $this->error("Error inserting text answers: " . $this->db->ErrorMsg()); }
        }

        if(count($results) > 0)
        {
            $r_string = implode(",",$results);
            $rs = $this->db->Execute("INSERT INTO {$this->CONF['db_tbl_prefix']}results (rid, sequence, sid, qid, avid, entered) VALUES $r_string");
            if($rs === FALSE)
            { $this->error("Error inserting numeric answers: " . $this->db->ErrorMsg()); }
        }

        //Insert elapsed time to take survey
        $etime = $now - $survey['start_time'];
        $query = "INSERT INTO {$this->CONF['db_tbl_prefix']}time_limit (sequence,sid,elapsed_time) VALUES ($id,{$survey['sid']},$etime)";
        $rs = $this->db->Execute($query);
        if($rs === FALSE)
        { $this->error('Error inserting elapsed time: ' . $this->db->ErrorMsg()); }

        if(isset($_SESSION['priv'][$survey['sid']][TAKE_PRIV]))
        { unset($_SESSION['priv'][$survey['sid']][TAKE_PRIV]); }

        return;
    }

}

?>