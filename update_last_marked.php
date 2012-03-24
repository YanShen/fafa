<?php
//用來將 results_text 的最新的 marked 時間，寫入 surveys.last_marked 欄位，以加速 admin_surveys.php 呈現速度。

include('classes/main.class.php');
include("classes/special_results.class.php");

class TableResultUpdator extends UCCASS_Special_Results {
	  /**************
    * CONSTRUCTOR *
    **************/
    var $sid=0;
    var $marked=0;
    
    function TableResultUpdator()
    { $this->load_configuration(); }
	
	//duplicated with send_list_by_smtp.php
	function updateLastMarked() {
			$query = "select sid, max(marked) as marked from results_text group by sid";
			$rs = $this->db->Execute($query);
			
			while($r = $rs->FetchRow() ) {
				$query = "update surveys set last_marked = {$r['marked']} where sid = {$r['sid']}";
				$this->db->Execute($query);
			}
	}
	
}
$survey = new TableResultUpdator();
$survey->updateLastMarked();

?>