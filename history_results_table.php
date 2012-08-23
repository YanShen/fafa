<?php
include('classes/main.class.php');

class HistoryResults extends UCCASS_Main
{
	  function TableResultUpdator()
    { $this->load_configuration(); }
	
	  function listHistory() {
	  	$survey['sid']=$_REQUEST['sid'];
	  
        if(!$this->_CheckLogin($sid,EDIT_PRIV,"history_results_table.php?sid=".$survey['sid']))
        { return $this->showLogin("history_results_table.php?sid=".$survey['sid']); }
	  
	  	$tables[0] = "results";
	  	$tables[1] = "results_text";
	  	
	  	$markedTime = array();
	  	
	  	$query = "select * from surveys where sid='".$survey['sid']."'";
//echo $query.'<br>';
	  	$rs = $this->query($query);
	  	if( ($r = $rs->FetchRow()) ) {
	  		$survey['name'] = $r['name'];
	  	}
	  	
	  	for($i=0; $i<2; $i++) {
	  		
	  	  $query = "select distinct(marked) as marked from ".$tables[$i]." where sid='".$survey['sid']."'";
//echo $query.'<br>';
	  	  $rs = $this->query($query);
	  	  
	  	  while($r = $rs->FetchRow()) {
	  	  	if($r['marked'] != null) {
	  	  	  $markedTime[$r['marked']] = $r['marked'];
//echo $markedTime[$r['marked']].'<br>';
	  	  	}
	  	  }
	    }
	    //依照時間排序
      sort($markedTime);

	    $this->smarty->assign_by_ref('markedTime', $markedTime);
	    $this->smarty->assign_by_ref('survey', $survey);
	    
	    $retval = $this->smarty->fetch($this->template.'/history_results_table.tpl');
	    
	    return $retval;
	  }
}

$history = new HistoryResults();

echo $history->com_header();
echo $history->listHistory();
?>