<?php
//�ΨӱN results_text ���̷s�� marked �ɶ��A�g�J surveys.last_marked ���A�H�[�t admin_surveys.php �e�{�t�סC

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