<?php
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
	
	function markResult() {
		$sid = $_REQUEST["sid"];
//echo $sid;		
	  $key = "seq";
	  $value = $_REQUEST[$key];
		$now = time();
		$this->marked = $now;
		$this->sid = $sid;
		
		if (is_array($value)) {
//echo "now=".$now."<br>";
//echo "length=".count($value)."<br>";
			for ($i = 0; $i < count($value); $i++) {
//print("    ".$key."[".$i."] = ".$value[$i]."<br>");
				$query = "update results set marked = $now where sequence = $value[$i] and sid= $sid";
				$this->db->Execute($query);
//echo $query."<br>";
				$query = "update results_text set marked = $now where sequence = $value[$i] and sid= $sid";
				$this->db->Execute($query);
//echo $query."<br>";
				//Update last_marked = $now
				$query = "update surveys set last_marked = $now where sid= $sid";
				$this->db->Execute($query);
			}
		}
	}
	
	//duplicated with send_list_by_smtp.php
	function updateSelQid() {
		$sid = $_REQUEST["sid"];
		$value = $_REQUEST['selQid'];

		if (is_array($value)) {
			$query = "delete from survey_sel_qid where sid = $sid";
			$this->db->Execute($query);
			
			for ($i = 0; $i < count($value); $i++) {
				$query = "insert survey_sel_qid (sid, qid) values ($sid, {$value[$i]})";
				$this->db->Execute($query);
			}
		}
	}
	
}
$survey = new TableResultUpdator;
$survey->markResult();
$survey->updateSelQid();

$output = $survey->com_header("Survey Results");

?>
<script>
	document.location = "results_table_by_mark.php?sid=<?=@$_REQUEST['sid']?>&marked=<?=$survey->marked?>";
</script>