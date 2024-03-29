﻿<?php

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

class UCCASS_Special_Results extends UCCASS_Main
{
    function UCCASS_Special_Results()
    {
        $this->load_configuration();

        //Increase time limit of script to 2 minutes to ensure
        //very large results can be shown or exported
        set_time_limit(120);
    }

    function results_table($sid, $markedOnly)
    {
        $sid = (int)$sid;
        //by Yan. for later checking name repeating.
        $allNames = array();
        $nameQid = null;

        if(!$this->_CheckAccess($sid,RESULTS_PRIV,"results_table.php?sid=$sid"))
        {
            switch($this->_getAccessControl($sid))
            {
                case AC_INVITATION:
                    return $this->showInvite('results_table.php',array('sid'=>$sid));
                break;
                case AC_USERNAMEPASSWORD:
                default:
                    return $this->showLogin('results_table.php',array('sid'=>$sid));
                break;
            }
        }

        $data = array();
        $qid = array();
        $survey = array();

        $survey['sid'] = $sid;
		//by Yan. this variable is for storing previous selected qid.
		$savedSelQid = $this->getSavedSelQid($sid);

//        $query = "SELECT q.qid, q.question, s.name, s.user_text_mode, s.survey_text_mode, s.date_format
//                  FROM {$this->CONF['db_tbl_prefix']}questions q, {$this->CONF['db_tbl_prefix']}surveys s
//                  WHERE q.sid = $sid and s.sid = q.sid ORDER BY q.page, q.oid";
        $query = "SELECT q.qid, q.question, a.type, s.name, s.user_text_mode, s.survey_text_mode, s.date_format
                  , s.default_referrer
                  FROM {$this->CONF['db_tbl_prefix']}questions q, {$this->CONF['db_tbl_prefix']}surveys s,
                  {$this->CONF['db_tbl_prefix']}answer_types a
                  WHERE q.sid = $sid and s.sid = q.sid and q.aid=a.aid ORDER BY q.page, q.oid";
        $rs = $this->db->Execute($query);
        if($rs === FALSE)
        { $this->error('Error in query: ' . $this->db->ErrorMsg()); return; }

        $questions = array();
        if($r = $rs->FetchRow($rs))
        {
            $survey_text_mode = $r['survey_text_mode'];
            $user_text_mode = $r['user_text_mode'];
            $date_format = $r['date_format'];
            $survey['name'] = $this->SfStr->getSafeString($r['name'],$survey_text_mode);
            //Yan added. default_referrer is used in generating result table.
            $survey['default_referrer'] = $this->SfStr->getSafeString($r['default_referrer'],'');

            do{
            	if($r[type]!='N') {
	                $data['questions'][] = $this->SfStr->getSafeString($r['question'],$survey_text_mode);
	                //by Yan. added for get all names for later checking repeating.
//echo "question:".$r['question']."<br>";
//echo "pos:".strpos($r['question'], '姓名').", qid:"+$r['qid']."<br>";
	                if(!isset($nameQid) && strpos($r['question'], '姓名') < 3 ) {
//echo "find name field-".$r['qid']."<br>";
	                	$nameQid = $r['qid'];
	                	$allNames = $this->getValuesByQid($sid, $nameQid, $allNames);
//echo "sid:".$sid."<br>";	                	
//echo "nameQid:".$nameQid.", ".$r['question']."<br>";
	                	$this->smarty->assign_by_ref('allNames',$allNames);
	                }
	                //by Yan. added for selecting question id.
					$idx = count($data['qid']);
	                $data['qid'][$idx]['id'] = $r['qid'];
					
					//if there is no savedSelQid, then initial check/uncheck value has to be assigned separately in the lower part.
					if(count($savedSelQid)==0) {
						//This is the first time for this survey to save selected questions. set the initial values.
						if( (!strstr($r['question'], 'E-mail(請務必填寫，以聯絡"重要"注意事項)')) &&  (!strstr($r['question'], '一年內曾參加過的市調公司'))
							&& (!strstr($r['question'], '是否同意訂閱電子報'))) {
							$data['qid'][$idx]['checked'] = 'checked';
						}
					} else {
					//if there is savedSelQid, the follow the data inside the array.
						$data['qid'][$idx]['checked'] = in_array($r['qid'], $savedSelQid)?'checked':'';
					}
					
	                $qid[$r['qid']] = $r['qid'];
	              }
            }while($r = $rs->FetchRow($rs));
        }
        else
        { $this->error('No questions for this survey.'); return; }

        if(isset($_SESSION['filter_text'][$sid]) && isset($_SESSION['filter'][$sid]) && strlen($_SESSION['filter_text'][$sid])>0)
        { $this->smarty->assign_by_ref('filter_text',$_SESSION['filter_text'][$sid]); }
        else
        { $_SESSION['filter'][$sid] = ''; }
//Added 'marked' by Yan. 
		$markedCriterion = "";
		if($markedOnly)
			$markedCriterion = " and (r.marked is not null or rt.marked is not null) ";
        $query = "SELECT GREATEST(rt.qid, r.qid) AS qid, GREATEST(rt.sequence, r.sequence) AS seq,
                  GREATEST(rt.entered,r.entered) AS entered, GREATEST(rt.marked, r.marked) AS marked,
                  q.question, av.value, rt.answer FROM {$this->CONF['db_tbl_prefix']}questions q LEFT JOIN {$this->CONF['db_tbl_prefix']}results
                  r ON q.qid = r.qid LEFT JOIN {$this->CONF['db_tbl_prefix']}results_text rt ON q.qid = rt.qid LEFT JOIN
                  {$this->CONF['db_tbl_prefix']}answer_values av ON r.avid = av.avid WHERE q.sid = $sid {$_SESSION['filter'][$sid]}
                  $markedCriterion
                  ORDER BY seq, q.page, q.oid";
        $rs = $this->db->Execute($query);
        if($rs === FALSE)
        { $this->error('Error in query: ' . $this->db->ErrorMsg()); return; }

        $seq = '';
        $x = -1;
        while($r = $rs->FetchRow($rs))
        {
            if(!empty($r['qid']))
            {
                if($seq != $r['seq'])
                {
                    $x++;
                    $seq = $r['seq'];
                    $answers[$x]['date'] = date($date_format,$r['entered']);
                    //Added by Yan. for Marking marked.
                    $answers[$x]['seq'] = $r['seq'];
                    if(!empty($r['marked'])) {
                      $answers[$x]['marked'] = $r['marked'];
                    }
                
	                //By Yan. To mark duplicated
	                if($r['qid'] == $nameQid) {
	                 	//將姓名中的空白及全型空白都去掉
	                 	$inputName = str_replace(array(' ', '　'), "", $r['answer']);
	                 	
 						if($allNames[$inputName]['inBlackList']) {
	                		$answers[$x]['black_list'] = true;
	                	}
	                	if($allNames[$inputName]['repeat'] > 1) {
	                		$answers[$x]['duplicate'] = true;
	                	}
	                	if($allNames[$inputName]['inBlockList']) {
	                		$answers[$x]['block_list'] = true;
	                	}
	                }
                    //Added end.
                }
                if(isset($answers[$x][$r['qid']]))
                { $answers[$x][$r['qid']] .= MULTI_ANSWER_SEPERATOR . $this->SfStr->getSafeString($r['value'] . $r['answer'],$user_text_mode); }
                else
                { $answers[$x][$r['qid']] = $this->SfStr->getSafeString($r['value'] . $r['answer'],$user_text_mode); }
            }
            $last_date = date($date_format,$r['entered']);
        }
        $answers[$x]['date'] = $last_date;

        $xvals = array_keys($answers);

        foreach($xvals as $x)
        {
            foreach($qid as $qid_value)
            {
                if(isset($answers[$x][$qid_value]))
                { $data['answers'][$x][] = $answers[$x][$qid_value]; }
                else
                { $data['answers'][$x][] = '&nbsp;'; }
            }
            $data['answers'][$x][] = $answers[$x]['date'];
        }

        $this->smarty->assign_by_ref('data',$data);
        $this->smarty->assign_by_ref('survey',$survey);
        //Added by yan.
        $this->smarty->assign_by_ref('answers',$answers);
        return $this->smarty->fetch($this->template.'/latest_results_table.tpl');
    }

//Added by Yan.
		function getLastMarkedTime($sid) {
				$query = "SELECT MAX(r.marked) as marked from
									{$this->CONF['db_tbl_prefix']}results r where r.sid=$sid";
				$rs = $this->db->Execute($query);
				$r = $rs->FetchRow($rs);
				if(!empty($r['marked'])) {
					return $r['marked'];
				}
				
				$query = "SELECT MAX(r.marked) as marked from
									{$this->CONF['db_tbl_prefix']}results_text r where r.sid=$sid";
				$rs = $this->db->Execute($query);
				$r = $rs->FetchRow($rs);
				if(!empty($r['marked'])) {
					return $r['marked'];
				} else {
					return null;
				}
				
		}


		function getSavedSelQid($sid) {
			$result = array();
			
			$query = "select * from survey_sel_qid where sid={$sid}";
			
	        $rs = $this->db->Execute($query);
	        if($rs === FALSE)
	        { $this->error('Error in query: ' . $this->db->ErrorMsg()); return; }
	        
	        while($r = $rs->FetchRow($rs) ) {
	        	$result[count($result)] = $r['qid'];
//echo "result=".$r['qid']."<br>";
	        }
	        
	        return $result;
		}

		//by yan. for retrive special qid values, say: names for later check.
		function getValuesByQid($sid, $qid, $result) {
			$query = "select answer from results_text where sid=$sid and qid=$qid";
			$rs = $this->db->Execute($query);

        	if($rs === FALSE)
        	{ $this->error('Error in query: ' . $this->db->ErrorMsg()); return; }
			while($r = $rs->FetchRow($rs)) { 
				$result[$r['answer']]['repeat'] += 1;
			}

			$query = "select name from black_list";
			$rs = $this->db->Execute($query); 
        	if($rs === FALSE)
        	{ $this->error('Error in query: ' . $this->db->ErrorMsg()); return; }
			while($r = $rs->FetchRow($rs)) { 
				$result[$r['name'].trim()]['inBlackList'] = 1;
//echo "blacklist:".$r['name']."<br>";
			}

			$query = "select name from block_list";
			$rs = $this->db->Execute($query); 
        	if($rs === FALSE)
        	{ $this->error('Error in query: ' . $this->db->ErrorMsg()); return; }
			while($r = $rs->FetchRow($rs)) { 
				$result[$r['name'].trim()]['inBlockList'] = 1;
//echo "blocklist:".$r['name']."<br>";
			}

			return $result;
		}

    function latest_results_table($sid)
    {
        $sid = (int)$sid;
        
        //by Yan. for later checking name repeating.
        $allNames = array();
        $nameQid = null;
        
        if(!$this->_CheckAccess($sid,RESULTS_PRIV,"results_table.php?sid=$sid"))
        {
            switch($this->_getAccessControl($sid))
            {
                case AC_INVITATION:
                    return $this->showInvite('results_table.php',array('sid'=>$sid));
                break;
                case AC_USERNAMEPASSWORD:
                default:
                    return $this->showLogin('results_table.php',array('sid'=>$sid));
                break;
            }
        }
        
        $data = array();
        $qid = array();
        $survey = array();

        $survey['sid'] = $sid;

		//by Yan. this variable is for storing previous selected qid.
		$savedSelQid = $this->getSavedSelQid($sid);
		
//        $query = "SELECT q.qid, q.question, s.name, s.user_text_mode, s.survey_text_mode, s.date_format
//                  FROM {$this->CONF['db_tbl_prefix']}questions q, {$this->CONF['db_tbl_prefix']}surveys s
//                  WHERE q.sid = $sid and s.sid = q.sid ORDER BY q.page, q.oid";
        $query = "SELECT q.qid, q.question, a.type, s.name, s.user_text_mode, s.survey_text_mode, s.date_format,
                  s.key_desc, s.default_referrer
                  FROM {$this->CONF['db_tbl_prefix']}questions q, {$this->CONF['db_tbl_prefix']}surveys s,
                  {$this->CONF['db_tbl_prefix']}answer_types a
                  WHERE q.sid = $sid and s.sid = q.sid and q.aid=a.aid ORDER BY q.page, q.oid";
        $rs = $this->db->Execute($query);
        if($rs === FALSE)
        { $this->error('Error in query: ' . $this->db->ErrorMsg()); return; }

        $questions = array();
        if($r = $rs->FetchRow($rs))
        {
            $survey_text_mode = $r['survey_text_mode'];
            $user_text_mode = $r['user_text_mode'];
            $date_format = $r['date_format'];
            $survey['name'] = $this->SfStr->getSafeString($r['name'],$survey_text_mode);
            $survey['key_desc'] = $this->SfStr->getSafeString($r['key_desc'],$survey_text_mode);
            //Yan added. default_referrer is used in generating result table.
            $survey['default_referrer'] = $this->SfStr->getSafeString($r['default_referrer'],'');

            do{
            	if($r[type]!='N') {
	                $data['questions'][] = $this->SfStr->getSafeString($r['question'],$survey_text_mode);
	                //by Yan. added for get all names for later checking repeating.
//echo "question:".$r['question']."<br>";
//echo "pos:".strpos($r['question'], '姓名').", qid:"+$r['qid']."<br>";
	                if(!isset($nameQid) && strpos($r['question'], '姓名') < 3 ) {
//echo "find name field-".$r['qid']."<br>";
	                	$nameQid = $r['qid'];
	                	$allNames = $this->getValuesByQid($sid, $nameQid, $allNames);
//echo "sid:".$sid."<br>";	                	
//echo "nameQid:".$nameQid.", ".$r['question']."<br>";
	                	$this->smarty->assign_by_ref('allNames',$allNames);
	                }
	                //by Yan. added for selecting question id.
					$idx = count($data['qid']);
	                $data['qid'][$idx]['id'] = $r['qid'];
					
					//if there is no savedSelQid, then initial check/uncheck value has to be assigned separately in the lower part.
					if(count($savedSelQid)==0) {
						//This is the first time for this survey to save selected questions. set the initial values.
						if( (!strstr($r['question'], 'E-mail(請務必填寫，以聯絡"重要"注意事項)')) &&  (!strstr($r['question'], '一年內曾參加過的市調公司'))
							&& (!strstr($r['question'], '是否同意訂閱電子報'))) {
							$data['qid'][$idx]['checked'] = 'checked';
						}
					} else {
					//if there is savedSelQid, the follow the data inside the array.
						$data['qid'][$idx]['checked'] = in_array($r['qid'], $savedSelQid)?'checked':'';
					}

	                $qid[$r['qid']] = $r['qid'];
	              }
            }while($r = $rs->FetchRow($rs));
        }
        else
        { $this->error('No questions for this survey.'); return; }

        if(isset($_SESSION['filter_text'][$sid]) && isset($_SESSION['filter'][$sid]) && strlen($_SESSION['filter_text'][$sid])>0)
        { $this->smarty->assign_by_ref('filter_text',$_SESSION['filter_text'][$sid]); }
        else
        { $_SESSION['filter'][$sid] = ''; }
//Removed 'query' by Yan.
				//1. Get latest marked time
				$markedTime = (int)$this->getLastMarkedTime($sid);
				//2. select results which entered after marked time.
//echo $markedTime;
        $query = "SELECT * from (
                  SELECT GREATEST(rt.qid, r.qid) AS qid, GREATEST(rt.sequence, r.sequence) AS seq,
                  GREATEST(rt.entered,r.entered) AS entered, GREATEST(rt.marked, r.marked) AS marked,
                  q.question, q.page, q.oid, av.value, rt.answer FROM {$this->CONF['db_tbl_prefix']}questions q LEFT JOIN {$this->CONF['db_tbl_prefix']}results
                  r ON q.qid = r.qid LEFT JOIN {$this->CONF['db_tbl_prefix']}results_text rt ON q.qid = rt.qid LEFT JOIN
                  {$this->CONF['db_tbl_prefix']}answer_values av ON r.avid = av.avid WHERE q.sid = $sid {$_SESSION['filter'][$sid]}
                  ) a where a.entered > $markedTime
                  ORDER BY seq, page, oid";
//echo $query;    		
//Remove End.
        $rs = $this->db->Execute($query);
        if($rs === FALSE)
        { $this->error('Error in query: ' . $this->db->ErrorMsg()); return; }

        $seq = '';
        $x = -1;
        while($r = $rs->FetchRow($rs))
        {
            if(!empty($r['qid']))
            {
                if($seq != $r['seq'])
                {
                    $x++;
                    $seq = $r['seq'];
                    $answers[$x]['date'] = date($date_format,$r['entered']);
                    //Added by Yan. for Marking marked.
                    $answers[$x]['seq'] = $r['seq'];
                    if(!empty($r['marked'])) {
                      $answers[$x]['marked'] = $r['marked'];
                    }
//echo "qid=".$r['qid'].", answer=".$r['answer']."<br>";                
	                //By Yan. To mark duplicated
	                if($r['qid'] == $nameQid) {
						//將姓名中的空白及全型空白都去掉
	                 	$inputName = str_replace(array(' ', '　'), "", $r['answer']);
//echo "inputName:".$inputName.", inBlackList:".$allNames[$inputName]['inBlackList'].", inBlockList:".$allNames[$inputName]['inBlockList'];	                 	
 						if($allNames[$inputName]['inBlackList']) {
	                		$answers[$x]['black_list'] = true;
	                	}
	                	if($allNames[$inputName]['repeat'] > 1) {
	                		$answers[$x]['duplicate'] = true;
	                	}
	                	if($allNames[$inputName]['inBlockList']) {
	                		$answers[$x]['block_list'] = true;
	                	}
	                }
                    //Added end.
                }
                if(isset($answers[$x][$r['qid']]))
                { $answers[$x][$r['qid']] .= MULTI_ANSWER_SEPERATOR . $this->SfStr->getSafeString($r['value'] . $r['answer'],$user_text_mode); }
                else
                { $answers[$x][$r['qid']] = $this->SfStr->getSafeString($r['value'] . $r['answer'],$user_text_mode); }
            }
            $last_date = date($date_format,$r['entered']);
        }
        if($x != -1) {
					$answers[$x]['date'] = $last_date;
	        $xvals = array_keys($answers);
	
	        foreach($xvals as $x)
	        {
	            foreach($qid as $qid_value)
	            {
	                if(isset($answers[$x][$qid_value]))
	                { $data['answers'][$x][] = $answers[$x][$qid_value]; }
	                else
	                { $data['answers'][$x][] = '&nbsp;'; }
	            }
	            $data['answers'][$x][] = $answers[$x]['date'];
	        }
				}
        $this->smarty->assign_by_ref('data',$data);
        $this->smarty->assign_by_ref('survey',$survey);
        //Added by yan.
        //$this->smarty->assign_by_ref('recordInfo',$recordInfo);
        $this->smarty->assign_by_ref('answers',$answers);
        return $this->smarty->fetch($this->template.'/latest_results_table.tpl');
    }

//Modified by Yan.
//if $markedTime is empty, it lists all marked records.    
    function results_table_by_marked($sid, $markedTime)
    {
        $sid = (int)$sid;
        
        //by Yan. for later checking name repeating.
        $allNames = array();
        $nameQid = null;

        if(!$this->_CheckAccess($sid,RESULTS_PRIV,"results_table.php?sid=$sid"))
        {
            switch($this->_getAccessControl($sid))
            {
                case AC_INVITATION:
                    return $this->showInvite('results_table.php',array('sid'=>$sid));
                break;
                case AC_USERNAMEPASSWORD:
                default:
                    return $this->showLogin('results_table.php',array('sid'=>$sid));
                break;
            }
        }
        
        $data = array();
        $qid = array();
        $survey = array();

        $survey['sid'] = $sid;
        //Added by Yan. add selQid and get mail_subject for display.
		$selQid = $_REQUEST['selQid'];
		
		//by Yan. this variable is for storing previous selected qid.
		$savedSelQid = $this->getSavedSelQid($sid);

//        $query = "SELECT q.qid, q.question, s.name, s.user_text_mode, s.survey_text_mode, s.date_format
//                  FROM {$this->CONF['db_tbl_prefix']}questions q, {$this->CONF['db_tbl_prefix']}surveys s
//                  WHERE q.sid = $sid and s.sid = q.sid ORDER BY q.page, q.oid";
        $query = "SELECT q.qid, q.question, a.type, s.name, s.user_text_mode, s.survey_text_mode, s.date_format
                  , s.default_referrer
                  FROM {$this->CONF['db_tbl_prefix']}questions q, {$this->CONF['db_tbl_prefix']}surveys s,
                  {$this->CONF['db_tbl_prefix']}answer_types a
                  WHERE q.sid = $sid and s.sid = q.sid and q.aid=a.aid ORDER BY q.page, q.oid";
        $rs = $this->db->Execute($query);
        if($rs === FALSE)
        { $this->error('Error in query: ' . $this->db->ErrorMsg()); return; }

        $questions = array();
        if($r = $rs->FetchRow($rs))
        {
            $survey_text_mode = $r['survey_text_mode'];
            $user_text_mode = $r['user_text_mode'];
            $date_format = $r['date_format'];
            $survey['name'] = $this->SfStr->getSafeString($r['name'],$survey_text_mode);
            //Yan added. default_referrer is used in generating result table.
            $survey['default_referrer'] = $this->SfStr->getSafeString($r['default_referrer'],'');
            
            do{
            	if($r[type]!='N') {
	                $data['questions'][] = $this->SfStr->getSafeString($r['question'],$survey_text_mode);
	                //by Yan. added for get all names for later checking repeating.
//echo "question:".$r['question']."<br>";
//echo "pos:".strpos($r['question'], '姓名').", qid:"+$r['qid']."<br>";
	                if(!isset($nameQid) && strpos($r['question'], '姓名') < 3 ) {
//echo "find name field-".$r['qid']."<br>";
	                	$nameQid = $r['qid'];
	                	$allNames = $this->getValuesByQid($sid, $nameQid, $allNames);
//echo "sid:".$sid."<br>";	                	
//echo "nameQid:".$nameQid.", ".$r['question']."<br>";
	                	$this->smarty->assign_by_ref('allNames',$allNames);
	                }
	                //by Yan. added for selecting question id.
					$idx = count($data['qid']);
	                $data['qid'][$idx]['id'] = $r['qid'];
					
					//if there is no savedSelQid, then initial check/uncheck value has to be assigned separately in the lower part.
					if(count($savedSelQid)==0) {
						//This is the first time for this survey to save selected questions. set the initial values.
						if( (!strstr($r['question'], 'E-mail(請務必填寫，以聯絡"重要"注意事項)')) &&  (!strstr($r['question'], '一年內曾參加過的市調公司'))
							&& (!strstr($r['question'], '是否同意訂閱電子報'))) {
							$data['qid'][$idx]['checked'] = 'checked';
						}
					} else {
					//if there is savedSelQid, the follow the data inside the array.
						$data['qid'][$idx]['checked'] = in_array($r['qid'], $savedSelQid)?'checked':'';
					}
	                
	                $qid[$r['qid']] = $r['qid'];
	              }
            }while($r = $rs->FetchRow($rs));
        }
        else
        { $this->error('No questions for this survey.'); return; }

        if(isset($_SESSION['filter_text'][$sid]) && isset($_SESSION['filter'][$sid]) && strlen($_SESSION['filter_text'][$sid])>0)
        { $this->smarty->assign_by_ref('filter_text',$_SESSION['filter_text'][$sid]); }
        else
        { $_SESSION['filter'][$sid] = ''; }
//Removed 'query' by Yan.
				//select results which entered after marked time.
//echo $markedTime;
        $query = "SELECT * from (
                  SELECT GREATEST(rt.qid, r.qid) AS qid, GREATEST(rt.sequence, r.sequence) AS seq,
                  GREATEST(rt.entered,r.entered) AS entered, GREATEST(rt.marked, r.marked) AS marked,
                  q.question, q.page, q.oid, av.value, rt.answer FROM {$this->CONF['db_tbl_prefix']}questions q LEFT JOIN {$this->CONF['db_tbl_prefix']}results
                  r ON q.qid = r.qid LEFT JOIN {$this->CONF['db_tbl_prefix']}results_text rt ON q.qid = rt.qid LEFT JOIN
                  {$this->CONF['db_tbl_prefix']}answer_values av ON r.avid = av.avid WHERE q.sid = $sid {$_SESSION['filter'][$sid]}
                  ) a where ";
        if($markedTime != 'ALL_MARKED') {
        	$query .= "a.marked = $markedTime ";
        } else {
         	$query .= "a.marked is not null ";
        }
         
        $query .= "ORDER BY seq, page, oid";
//echo $query;    		
//Remove End.
        $rs = $this->db->Execute($query);
        if($rs === FALSE)
        { $this->error('Error in query: ' . $this->db->ErrorMsg()); return; }

        $seq = '';
        $x = -1;
        while($r = $rs->FetchRow($rs))
        {
            if(!empty($r['qid']))
            {
                if($seq != $r['seq'])
                {
                    $x++;
                    $seq = $r['seq'];
                    $answers[$x]['date'] = date($date_format,$r['entered']);
                    //Added by Yan. for Marking marked.
                    $answers[$x]['seq'] = $r['seq'];
                    if(!empty($r['marked'])) {
                      $answers[$x]['marked'] = $r['marked'];
                    }
                    
	                //By Yan. To mark duplicated
	                if($r['qid'] == $nameQid) {
	                 	//將姓名中的空白及全型空白都去掉
	                 	$inputName = str_replace(array(' ', '　'), "", $r['answer']);
	                 	
 						if($allNames[$inputName]['inBlackList']) {
	                		$answers[$x]['black_list'] = true;
	                	}
	                	if($allNames[$inputName]['repeat'] > 1) {
	                		$answers[$x]['duplicate'] = true; 
	                	}
 										if($allNames[$inputName]['inBlockList']) {
	                		$answers[$x]['block_list'] = true;
	                	}
	                }
                    //Added end.
                }
                if(isset($answers[$x][$r['qid']]))
                { $answers[$x][$r['qid']] .= MULTI_ANSWER_SEPERATOR . $this->SfStr->getSafeString($r['value'] . $r['answer'],$user_text_mode); }
                else
                { $answers[$x][$r['qid']] = $this->SfStr->getSafeString($r['value'] . $r['answer'],$user_text_mode); }
            }
            $last_date = date($date_format,$r['entered']);
        }
        if($x != -1) {
					$answers[$x]['date'] = $last_date;
	        $xvals = array_keys($answers);
	
	        foreach($xvals as $x)
	        {
	            foreach($qid as $qid_value)
	            {
	                if(isset($answers[$x][$qid_value]))
	                { $data['answers'][$x][] = $answers[$x][$qid_value]; }
	                else
	                { $data['answers'][$x][] = '&nbsp;'; }
	            }
	            $data['answers'][$x][] = $answers[$x]['date'];
	        }
				}
        $this->smarty->assign_by_ref('data',$data);
        $this->smarty->assign_by_ref('survey',$survey);
        //Added by yan.
        //$this->smarty->assign_by_ref('recordInfo',$recordInfo);
        $this->smarty->assign_by_ref('answers',$answers);
        return $this->smarty->fetch($this->template.'/latest_results_table.tpl');
    }

//Modified by Yan.
//if $markedTime is empty, it lists all marked records.   
    function results_xls_by_marked($sid, $markedTime)
    {
        $sid = (int)$sid;
        
        if(!$this->_CheckAccess($sid,RESULTS_PRIV,"results_table.php?sid=$sid"))
        {
            switch($this->_getAccessControl($sid))
            {
                case AC_INVITATION:
                    return $this->showInvite('results_table.php',array('sid'=>$sid));
                break;
                case AC_USERNAMEPASSWORD:
                default:
                    return $this->showLogin('results_table.php',array('sid'=>$sid));
                break;
            }
        }
        
        $data = array();
        $qid = array();
        $survey = array();

        $survey['sid'] = $sid;

//        $query = "SELECT q.qid, q.question, s.name, s.user_text_mode, s.survey_text_mode, s.date_format
//                  FROM {$this->CONF['db_tbl_prefix']}questions q, {$this->CONF['db_tbl_prefix']}surveys s
//                  WHERE q.sid = $sid and s.sid = q.sid ORDER BY q.page, q.oid";
        $query = "SELECT q.qid, q.question, a.type, s.name, s.user_text_mode, s.survey_text_mode, s.date_format
                  , s.default_referrer
                  FROM {$this->CONF['db_tbl_prefix']}questions q, {$this->CONF['db_tbl_prefix']}surveys s,
                  {$this->CONF['db_tbl_prefix']}answer_types a
                  WHERE q.sid = $sid and s.sid = q.sid and q.aid=a.aid ORDER BY q.page, q.oid";
        $rs = $this->db->Execute($query);
        if($rs === FALSE)
        { $this->error('Error in query: ' . $this->db->ErrorMsg()); return; }

        $questions = array();
        if($r = $rs->FetchRow($rs))
        {
            $survey_text_mode = $r['survey_text_mode'];
            $user_text_mode = $r['user_text_mode'];
            $date_format = $r['date_format'];
            $survey['name'] = $this->SfStr->getSafeString($r['name'],$survey_text_mode); 
            //Yan added. default_referrer is used in generating result table.
            $survey['default_referrer'] = $this->SfStr->getSafeString($r['default_referrer'],'');

            do{
            	if($r[type]!='N') {
	                $data['questions'][] = $this->SfStr->getSafeString($r['question'],$survey_text_mode);
	                //by Yan. added for selecting question id.
	                $data['qid'][] = $r['qid'];
	                
	                $qid[$r['qid']] = $r['qid'];
	              }
            }while($r = $rs->FetchRow($rs));
        }
        else
        { $this->error('No questions for this survey.'); return; }

        if(isset($_SESSION['filter_text'][$sid]) && isset($_SESSION['filter'][$sid]) && strlen($_SESSION['filter_text'][$sid])>0)
        { $this->smarty->assign_by_ref('filter_text',$_SESSION['filter_text'][$sid]); }
        else
        { $_SESSION['filter'][$sid] = ''; }
//Removed 'query' by Yan.
				//select results which entered after marked time.
//echo $markedTime;
        $query = "SELECT * from (
                  SELECT GREATEST(rt.qid, r.qid) AS qid, GREATEST(rt.sequence, r.sequence) AS seq,
                  GREATEST(rt.entered,r.entered) AS entered, GREATEST(rt.marked, r.marked) AS marked,
                  q.question, q.page, q.oid, av.value, rt.answer FROM {$this->CONF['db_tbl_prefix']}questions q LEFT JOIN {$this->CONF['db_tbl_prefix']}results
                  r ON q.qid = r.qid LEFT JOIN {$this->CONF['db_tbl_prefix']}results_text rt ON q.qid = rt.qid LEFT JOIN
                  {$this->CONF['db_tbl_prefix']}answer_values av ON r.avid = av.avid WHERE q.sid = $sid {$_SESSION['filter'][$sid]}
                  ) a where ";
        if($markedTime != 'ALL_MARKED') {
        	$query .= "a.marked = $markedTime ";
        } else {
         	$query .= "a.marked is not null ";
        }
         
        $query .= "ORDER BY seq, page, oid";
//echo $query;    		
//Remove End.
        $rs = $this->db->Execute($query);
        if($rs === FALSE)
        { $this->error('Error in query: ' . $this->db->ErrorMsg()); return; }

        $seq = '';
        $x = -1;
        while($r = $rs->FetchRow($rs))
        {
            if(!empty($r['qid']))
            {
                if($seq != $r['seq'])
                {
                    $x++;
                    $seq = $r['seq'];
                    $answers[$x]['date'] = date($date_format,$r['entered']);
                    //Added by Yan. for Marking marked.
                    $answers[$x]['seq'] = $r['seq'];
                    if(!empty($r['marked'])) {
                      $answers[$x]['marked'] = $r['marked'];
                    }
                    //Added end.
                }
                if(isset($answers[$x][$r['qid']]))
                { $answers[$x][$r['qid']] .= MULTI_ANSWER_SEPERATOR . $this->SfStr->getSafeString($r['value'] . $r['answer'],$user_text_mode); }
                else
                { $answers[$x][$r['qid']] = $this->SfStr->getSafeString($r['value'] . $r['answer'],$user_text_mode); }
            }
            $last_date = date($date_format,$r['entered']);
        }

        if($x != -1) {
					$answers[$x]['date'] = $last_date;
	        $xvals = array_keys($answers);
	
	        foreach($xvals as $x)
	        {
	            foreach($qid as $qid_value)
	            {
	                if(isset($answers[$x][$qid_value]))
	                { $data['answers'][$x][] = $answers[$x][$qid_value]; }
	                else
	                { $data['answers'][$x][] = '&nbsp;'; }
	            }
	            $data['answers'][$x][] = $answers[$x]['date'];
	        }
				}
        $this->smarty->assign_by_ref('data',$data);
        $this->smarty->assign_by_ref('survey',$survey);
        //Added by yan.
        //$this->smarty->assign_by_ref('recordInfo',$recordInfo);
        $this->smarty->assign_by_ref('answers',$answers);
        return $this->smarty->fetch($this->template.'/latest_results_xls.tpl');
    }

//By Yan. This method is for generating email content,
//it takes selQid[] from Request to decide which comumns are displayed.
//if $markedTime is empty, it lists all marked records.
    function results_email_by_marked($sid, $markedTime)
    {
        $sid = (int)$sid;
        //by Yan. for later checking name repeating.
        $allNames = array();
        $nameQid = null;
        
        if(!$this->_CheckAccess($sid,RESULTS_PRIV,"results_table.php?sid=$sid"))
        {
            switch($this->_getAccessControl($sid))
            {
                case AC_INVITATION:
                    return $this->showInvite('results_table.php',array('sid'=>$sid));
                break;
                case AC_USERNAMEPASSWORD:
                default:
                    return $this->showLogin('results_table.php',array('sid'=>$sid));
                break;
            }
        }
        
        $data = array(); 
        $qid = array();
        $survey = array();

        $survey['sid'] = $sid;
        
        //Added by Yan. add selQid and get mail_subject for display.
		$selQid = $_REQUEST['selQid'];
		
		//by Yan. this variable is for storing previous selected qid.
		$savedSelQid = array();
		
		$savedSelQid = $this->getSavedSelQid($sid);
		
//        $query = "SELECT q.qid, q.question, s.name, s.user_text_mode, s.survey_text_mode, s.date_format
//                  FROM {$this->CONF['db_tbl_prefix']}questions q, {$this->CONF['db_tbl_prefix']}surveys s
//                  WHERE q.sid = $sid and s.sid = q.sid ORDER BY q.page, q.oid";
        $query = "SELECT q.qid, q.question, a.type, s.name, s.mail_subject, s.user_text_mode, s.survey_text_mode, s.date_format
                  , s.default_referrer
                  FROM {$this->CONF['db_tbl_prefix']}questions q, {$this->CONF['db_tbl_prefix']}surveys s,
                  {$this->CONF['db_tbl_prefix']}answer_types a
                  WHERE q.sid = $sid and s.sid = q.sid and q.aid=a.aid ORDER BY q.page, q.oid";
        $rs = $this->db->Execute($query);
        if($rs === FALSE)
        { $this->error('Error in query: ' . $this->db->ErrorMsg()); return; }

        $questions = array();
        if($r = $rs->FetchRow($rs))
        {
            $survey_text_mode = $r['survey_text_mode'];
            $user_text_mode = $r['user_text_mode'];
            $date_format = $r['date_format'];
            $survey['name'] = $this->SfStr->getSafeString($r['name'],$survey_text_mode);
            $survey['mail_subject'] = $this->SfStr->getSafeString($r['mail_subject'],$survey_text_mode);
            //Yan added. default_referrer is used in generating result table.
            $survey['default_referrer'] = $this->SfStr->getSafeString($r['default_referrer'],'');

            do{
            	if($r[type]!='N') {
	           		//By Yan. If no selQid is set then list all result
	            	//If selQid is set, only displays questions in selQid.
	            	if( !isset($selQid) || in_array($r['qid'], $selQid) ) {
		                $data['questions'][] = $this->SfStr->getSafeString($r['question'],$survey_text_mode);
		                //by Yan. added for get all names for later checking repeating.
//echo "question:".$r['question']."<br>";
//echo "pos:".strpos($r['question'], '姓名').", qid:"+$r['qid']."<br>";
	                if(!isset($nameQid) && strpos($r['question'], '姓名') < 3 ) {
//echo "find name field-".$r['qid']."<br>";
	                	$nameQid = $r['qid'];
	                	$allNames = $this->getValuesByQid($sid, $nameQid, $allNames);
//echo "sid:".$sid."<br>";	                	
//echo "nameQid:".$nameQid.", ".$r['question']."<br>";
	                	$this->smarty->assign_by_ref('allNames',$allNames);
	                }
		                //by Yan. added for selecting question id.
						$idx = count($data['qid']);
		                $data['qid'][$idx]['id'] = $r['qid'];
		                
						//if there is no savedSelQid, then initial check/uncheck value has to be assigned separately in the lower part.
						if(count($savedSelQid)==0) {
							//This is the first time for this survey to save selected questions. set the initial values.
							if( (!strstr($r['question'], 'E-mail(請務必填寫，以聯絡"重要"注意事項)')) &&  (!strstr($r['question'], '一年內曾參加過的市調公司'))
								&& (!strstr($r['question'], '是否同意訂閱電子報'))) {
								$data['qid'][$idx]['checked'] = 'checked';
							}
						} else {
						//if there is savedSelQid, the follow the data inside the array.
							$data['qid'][$idx]['checked'] = in_array($r['qid'], $savedSelQid)?'checked':'';
						}
		                
		                $qid[$r['qid']] = $r['qid'];
		            }
	            }
            }while($r = $rs->FetchRow($rs));
        }
        else
        { $this->error('No questions for this survey.'); return; }

        if(isset($_SESSION['filter_text'][$sid]) && isset($_SESSION['filter'][$sid]) && strlen($_SESSION['filter_text'][$sid])>0)
        { $this->smarty->assign_by_ref('filter_text',$_SESSION['filter_text'][$sid]); }
        else
        { $_SESSION['filter'][$sid] = ''; }
//Removed 'query' by Yan.
				//select results which entered after marked time.
//echo $markedTime;
        $query = "SELECT * from (
                  SELECT GREATEST(rt.qid, r.qid) AS qid, GREATEST(rt.sequence, r.sequence) AS seq,
                  GREATEST(rt.entered,r.entered) AS entered, GREATEST(rt.marked, r.marked) AS marked,
                  q.question, q.page, q.oid, av.value, rt.answer FROM {$this->CONF['db_tbl_prefix']}questions q LEFT JOIN {$this->CONF['db_tbl_prefix']}results
                  r ON q.qid = r.qid LEFT JOIN {$this->CONF['db_tbl_prefix']}results_text rt ON q.qid = rt.qid LEFT JOIN
                  {$this->CONF['db_tbl_prefix']}answer_values av ON r.avid = av.avid WHERE q.sid = $sid {$_SESSION['filter'][$sid]}
                  ) a where ";
        if($markedTime != 'ALL_MARKED') {
        	$query .= "a.marked = $markedTime ";
        } else {
         	$query .= "a.marked is not null ";
        }
         
        $query .= "ORDER BY seq, page, oid";
//echo $query;

//Remove End.
        $rs = $this->db->Execute($query);
        if($rs === FALSE)
        { $this->error('Error in query: ' . $this->db->ErrorMsg()); return; }

        $seq = '';
        $x = -1;
        while($r = $rs->FetchRow($rs))
        {
            if(!empty($r['qid']))
            {
            	//By Yan. If no selQid is set then list all result
            	//If selQid is set, only displays questions in selQid.
            	if( !isset($selQid) || in_array($r['qid'], $selQid) ) {
	                if($seq != $r['seq'])
	                {
	                    $x++;
	                    $seq = $r['seq'];
	                    $answers[$x]['date'] = date($date_format,$r['entered']);
	                    //Added by Yan. for Marking marked.
	                    $answers[$x]['seq'] = $r['seq'];
	                    if(!empty($r['marked'])) {
	                      $answers[$x]['marked'] = $r['marked'];
	                    }
	                    
		                //By Yan. To mark duplicated
		                if($r['qid'] == $nameQid) {
		                 	//將姓名中的空白及全型空白都去掉
		                 	$inputName = str_replace(array(' ', '　'), "", $r['answer']);
		                 	
	 						if($allNames[$inputName]['inBlackList']) {
		                		$answers[$x]['black_list'] = true;
		                	}
		                	if($allNames[$inputName]['repeat'] > 1) {
		                		$answers[$x]['duplicate'] = true;
		                		//record the latest appearance of each $inputName.僅保留重複姓名時的最後一筆紀錄的位置
		                		$allNames[$inputName]['positionInAnswers'] = $x;
//echo $inputName.", pos=".$x."<br>";
		                	}
	                		if($allNames[$inputName]['inBlockList']) {
	                			$answers[$x]['block_list'] = true;
	                		}
		                }
	                     
	                    //Added end.
	                }
	                if(isset($answers[$x][$r['qid']]))
	                { $answers[$x][$r['qid']] .= MULTI_ANSWER_SEPERATOR . $this->SfStr->getSafeString($r['value'] . $r['answer'],$user_text_mode); }
	                else
	                { $answers[$x][$r['qid']] = $this->SfStr->getSafeString($r['value'] . $r['answer'],$user_text_mode); }
	            }
            }
            $last_date = date($date_format,$r['entered']);
        }

        if($x != -1) {
					$answers[$x]['date'] = $last_date;
	        $xvals = array_keys($answers);
			
			$dataPos = 0;
	        foreach($xvals as $x)
	        { 
	         	//By Yan. Leave the last one duplicated marked records. 
	         	//如果姓名存在重複列表內，但是目前的資料位置不是最後一筆資料位置，則跳過不複製至data變數內。
	         	$latestRecordPos = $allNames[$answers[$x][$nameQid]]['positionInAnswers'];
	         	if(isset($latestRecordPos) && $latestRecordPos != $x) {
//echo $answers[$x][$nameQid].", pos=".$x." is not equal to the latest pos:".$latestRecordPos."<br>"; 
	         		continue;
	         	}
	            foreach($qid as $qid_value)
	            {
	                if(isset($answers[$x][$qid_value]))
	                {
	                 	//By Yan. Leave the last one duplicated marked records.
	                	$data['answers'][$dataPos][] = $answers[$x][$qid_value];
	                }
	                else
	                { $data['answers'][$dataPos][] = '&nbsp;'; }
	            }
	            //by Yan. If selQid is not set, put entered date in the result.
	        	if(!isset($selQid)) { 
	            	$data['answers'][$dataPos][] = $answers[$x]['date'];
	            } 
	            $dataPos ++;
	        }
		}
        $this->smarty->assign_by_ref('data',$data);
        $this->smarty->assign_by_ref('survey',$survey);
        //Added by yan.
        //$this->smarty->assign_by_ref('recordInfo',$recordInfo);
        $this->smarty->assign_by_ref('answers',$answers);
        return $this->smarty->fetch($this->template.'/latest_results_for_email.tpl');
    }

//Added End.

    function results_csv($sid, $export_type=EXPORT_CSV_TEXT)
    {
        $sid = (int)$sid;


        $retval = '';

        if(!$this->_CheckAccess($sid,RESULTS_PRIV,"results_csv.php?sid=$sid"))
        {
            switch($this->_getAccessControl($sid))
            {
                case AC_INVITATION:
                    return $this->showInvite('results_csv.php',array('sid'=>$sid));
                break;
                case AC_USERNAMEPASSWORD:
                default:
                    return $this->showLogin('results_csv.php',array('sid'=>$sid));
                break;
            }
        }

        header("Content-Type: text/plain; charset={$this->CONF['charset']}");
        header("Content-Disposition: attachment; filename=Export.csv");

        $query = "SELECT q.qid, q.question, s.date_format
                  FROM {$this->CONF['db_tbl_prefix']}questions q, {$this->CONF['db_tbl_prefix']}surveys s
                  WHERE q.sid = $sid and s.sid = q.sid ORDER BY q.page, q.oid";
        $rs = $this->db->Execute($query);
        if($rs === FALSE)
        { $this->error('Error in query: ' . $this->db->ErrorMsg()); return; }

        $questions = array();
        if($r = $rs->FetchRow($rs))
        {
            $date_format = $r['date_format'];
            do{
                $questions[$r['qid']] = $r['question'];
            }while($r = $rs->FetchRow($rs));
        }
        else
        { $this->error('No questions for this survey'); return; }

        if(isset($_SESSION['filter_text'][$sid]) && isset($_SESSION['filter'][$sid]) && strlen($_SESSION['filter_text'][$sid])>0)
        { $this->smarty->assign_by_ref('filter_text',$_SESSION['filter_text'][$sid]); }
        else
        { $_SESSION['filter'][$sid] = ''; }


        $query = "SELECT GREATEST(rt.qid, r.qid) AS qid, GREATEST(rt.sequence, r.sequence) AS seq,
                  GREATEST(rt.entered, r.entered) AS entered,
                  q.question, av.value, av.numeric_value, rt.answer FROM {$this->CONF['db_tbl_prefix']}questions q LEFT JOIN {$this->CONF['db_tbl_prefix']}results
                  r ON q.qid = r.qid LEFT JOIN {$this->CONF['db_tbl_prefix']}results_text rt ON q.qid = rt.qid LEFT JOIN
                  {$this->CONF['db_tbl_prefix']}answer_values av ON r.avid = av.avid WHERE q.sid = $sid {$_SESSION['filter'][$sid]}
                  ORDER BY seq, q.page, q.oid";

        $rs = $this->db->Execute($query);
        if($rs === FALSE)
        { $this->error('Error in query: ' . $this->db->ErrorMsg()); return; }

        $seq = '';
        $x = 0;
        while($r = $rs->FetchRow($rs))
        {
            if(!empty($r['qid']))
            {
                if($seq != $r['seq'])
                {
                    $x++;
                    $seq = $r['seq'];
                    $answers[$x]['date'] = date($date_format,$r['entered']);
                }

                switch($export_type)
                {
                    case EXPORT_CSV_NUMERIC:
                        if(empty($r['answer']))
                        { $value = $r['numeric_value']; }
                        else
                        { $value = $r['answer']; }
                    break;

                    case EXPORT_CSV_TEXT:
                    default:
                        if(empty($r['answer']))
                        { $value = $r['value']; }
                        else
                        { $value = $r['answer']; }
                    break;
                }

                if(isset($answers[$x][$r['qid']]))
                { $answers[$x][$r['qid']] .= MULTI_ANSWER_SEPERATOR . $value; }
                else
                { $answers[$x][$r['qid']] = $value; }
            }
            $last_date = date($date_format,$r['entered']);
        }
        $answers[$x]['date'] = $last_date;

        $line = '';
        foreach($questions as $question)
        { $line .= "\"" . str_replace('"','""',$question) . "\","; }
        $retval .= $line . "Datetime\n";

        $xvals = array_keys($answers);

        foreach($xvals as $x)
        {
            $line = '';
            foreach($questions as $qid=>$question)
            {
                if(isset($answers[$x][$qid]))
                {
                    if(is_numeric($answers[$x][$qid]))
                    { $line .= "{$answers[$x][$qid]},"; }
                    else
                    { $line .= "\"" . str_replace('"','""',$answers[$x][$qid]) . "\","; }
                }
                else
                { $line .= ","; }
            }
            $retval .= $line . '"' . $answers[$x]['date'] . "\"\n";
        }

        return $retval;
    }
	
	// LOAD EXISTING PROPERTIES FOR A SURVEY - copied from editsurvey.class.php//
    function loadProperties($sid)
    {
        $sid = (int)$sid;

        //load survey properties and set default values
        //by Yan. select read_password, mail_subject and mail_reveiver as well.
        $query = "SELECT sid, name, start_date, end_date, active,
                  template, redirect_page, survey_text_mode, user_text_mode, created, date_format, time_limit,
                  read_password, mail_subject, mail_receiver, default_referrer, display_state, on_top, region, key_desc FROM
                  {$this->CONF['db_tbl_prefix']}surveys WHERE sid = $sid";
        $rs = $this->db->Execute($query);
        if($rs === FALSE)
        { $this->error("Error loading Survey #$sid: " . $this->db->ErrorMsg()); return; }
        elseif($r = $rs->FetchRow($rs))
        {
            $this->data['name'] = $this->SfStr->getSafeString($r['name'],SAFE_STRING_TEXT);

            $this->data['date_format'] = $this->SfStr->getSafeString($r['date_format'],SAFE_STRING_TEXT);
            $this->data['created'] = $this->SfStr->getSafeString(date($this->CONF['date_format'],$r['created']),SAFE_STRING_TEXT);
            $this->data['time_limit'] = $this->SfStr->getSafeString($r['time_limit'],SAFE_STRING_TEXT);
            
            //by Yan. set read_password, mail_subject and mail_receiver for editing.
            $this->data['read_password'] = $this->SfStr->getSafeString($r['read_password'],SAFE_STRING_TEXT);
            //by Yan. set key_desc for editing.
            $this->data['key_desc'] = $this->SfStr->getSafeString($r['key_desc'],SAFE_STRING_TEXT);
            
            //by Yan. if mail_subject is empty, extract string from survey.name.
            if(empty($r['mail_subject'])) {
            	$splitRegEx = "";
            	
            	if( strstr($r['name'], '$') )
            	  $splitRegEx = "/[\],\$]/";
            	else
            	  $splitRegEx = "/[\],\-]/";
            	
            	$aa=preg_split($splitRegEx, $r['name'] );
            	if(sizeOf($aa)<=1) { 
            		$this->data['mail_subject'] = "[報名]".$aa[0];
            	} else { 
            		$this->data['mail_subject'] = "[報名]".$aa[1];
            	}
            } else {
            	$this->data['mail_subject'] = $this->SfStr->getSafeString($r['mail_subject'],SAFE_STRING_TEXT);
            }
            //by Yan. if mail_subject is empty, extract string from survey.name.
            $this->data['mail_receiver'] = $this->SfStr->getSafeString($r['mail_receiver'],SAFE_STRING_TEXT);
            
            //by Yan. add on_top to make a survey on top of a list. (1:true, 0:false)
            $this->data['on_top'] = $this->SfStr->getSafeString($r['on_top'],SAFE_STRING_TEXT);
            //by Yan. add region to make a survey to be grouped by region (1:all, 2:taipei, 3:taichung, 4: kaohsuang)
            $this->data['region'] = $this->SfStr->getSafeString($r['region'],SAFE_STRING_TEXT);
            //by Yan. add default_referrer
            $this->data['default_referrer'] = $this->SfStr->getSafeString($r['default_referrer'],SAFE_STRING_TEXT);
            //by Yan. add display_state
            $this->data['display_state'] = $this->SfStr->getSafeString($r['display_state'],SAFE_STRING_TEXT);


            if($r['active'] == 1)
            { $this->data['active_selected'] = ' checked'; }
            else
            { $this->data['inactive_selected'] = ' checked'; }

            if($r['start_date'] == 0)
            { //$this->data['start_date'] = '';
            	$this->data['start_date'] = strtoupper(date('Y-m-d',$r['created']));
            	}
            else
            { $this->data['start_date'] = strtoupper(date('Y-m-d',$r['start_date'])); }

            if($r['end_date'] == 0)
            { //$this->data['end_date'] = '';
            	//by Yan: try to parse from title.
            	$endDateFromTitle = false;
            	$tempa = substr(strrchr($r['name'], "("), 1);
            	if(strlen($tempa)!=0) {
	                $date = $tempa;
	                $dateBeginPos = strpos($tempa, "-");
	                if($dateBeginPos !== false) {
	                	$dateBeginPos += 1;
	                	$tempa = substr($tempa, $dateBeginPos);
	                }
	                $this->data['end_date'] = $tempa;
	                for($i=0; $i < strlen($tempa); $i++) {
	                	if(!is_numeric($tempa[$i]) and ($tempa[$i] != "/")) {
	                		$tempa = substr($tempa, 0, $i);
	                		break;
	                	}	
	                }
	                $this->data['end_date'] = $tempa;
	                
	                $pieces = explode("/", $tempa);
	                
	                if(count($pieces)>=2) {
	                	$this->data['end_date'] = sprintf('%s-%02d-%02d', date('Y',$r['created']), $pieces[0], $pieces[1]);
	                	$endDateFromTitle = true;
	                }
	            }
                if(!$endDateFromTitle) {
                  $this->data['end_date'] = strtoupper(date('Y-m-d',$r['created']));
                }
            	//Yan: try to parse from title. END;
            }
            else
            { $this->data['end_date'] = strtoupper(date('Y-m-d',$r['end_date'])); }

            switch($r['redirect_page'])
            {
                case 'index':
                case '':
                    $this->data['redirect_index'] = ' checked';
                break;
                case 'results':
                    $this->data['redirect_results'] = ' checked';
                break;
                default:
                    $this->data['redirect_custom'] = ' checked';
                    $this->data['redirect_page_text'] = $this->SfStr->getSafeString($r['redirect_page'],SAFE_STRING_TEXT);
                break;
            }

            //Set arrays for holding text mode values, options, and selected element to
            //create drop down boxes
            $survey_text_mode = array_slice($this->CONF['text_modes'],0,$this->CONF['survey_text_mode']+1);
            $this->data['survey_text_mode_values'] = array_values($survey_text_mode);
            $this->data['survey_text_mode_options'] = array_keys($survey_text_mode);
            $this->data['survey_text_mode_selected'][$r['survey_text_mode']] = ' selected';

            $user_text_mode = array_slice($this->CONF['text_modes'],0,$this->CONF['user_text_mode']+1);
            $this->data['user_text_mode_values'] = array_values($user_text_mode);
            $this->data['user_text_mode_options'] = array_keys($user_text_mode);
            $this->data['user_text_mode_selected'][$r['user_text_mode']] = ' selected';

            if(in_array(2,$this->data['survey_text_mode_options']) || in_array(2,$this->data['user_text_mode_options']))
            { $this->data['show']['fullhtmlwarning'] = TRUE; }

            $dh = opendir($this->CONF['path'] . '/templates');
            while($file = readdir($dh))
            {
                if($file != '.' && $file != '..')
                {
                    $this->data['templates'][] = $this->SfStr->getSafeString($file,SAFE_STRING_TEXT);
                    if($r['template'] == $file)
                    { $this->data['selected_template'][] = ' selected'; }
                    else
                    { $this->data['selected_template'][] = ''; }
                }
            }

            sort($this->data['templates']);
        }
        else
        { $this->error("Survey #$sid does not exist."); return; }
    }
}

?>