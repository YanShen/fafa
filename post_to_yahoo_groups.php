<meta http-equiv="Content-Type" content="text/html; charset=Big5">
<?
include("classes/http.php");
include('classes/main.class.php');

	$ini_file = "survey.ini.php";
	$ini = @parse_ini_file($ini_file);

	//if $debug == 0, no debug message wiil be output in the browser
	$debug = 0;
	$echoBody = 0;

	$notifyType = $_REQUEST["notifyType"];

	if($notifyType == "") {
		echo "No notifyType specified. Process end.";
		return;
	}
	
	$account = 		$ini[$notifyType . ".yahooGroup.account"];		//"guly0302";
	$passwd = 		$ini[$notifyType . ".yahooGroup.passwd"];		//"kai456";
	$loginUrl = 	$ini[$notifyType . ".yahooGroup.loginUrl"];	//"https://login.yahoo.com/config/login?";
	$postUrl = 		$ini[$notifyType . ".yahooGroup.postUrl"];		//"http://hk.groups.yahoo.com/group/daadadad/post";

	
	Function debug($message) {
		global $debug;
	
		if($debug) {
			echo $message;
			flush();
		}
	}
	
	Function UTF8ToBig5($utf8_str) {
	  $i=0;
	  $len = strlen($utf8_str);
	  $big5_str="";
	  for ($i=0;$i<$len;$i++) {
			$sbit = ord(substr($utf8_str,$i,1));
			if ($sbit < 128) {
				$big5_str.=substr($utf8_str,$i,1);
			} else if($sbit > 191 && $sbit < 224) {
				$new_word=iconv("UTF-8","Big5",substr($utf8_str,$i,2));
				$big5_str.=($new_word=="")?"■":$new_word;
				$i++;
			} else if($sbit > 223 && $sbit < 240) {
				$new_word=iconv("UTF-8","Big5",substr($utf8_str,$i,3));
				$big5_str.=($new_word=="")?"■":$new_word;
				$i+=2;
			} else if($sbit > 239 && $sbit < 248) {
				$new_word=iconv("UTF-8","Big5",substr($utf8_str,$i,4));
				$big5_str.=($new_word=="")?"■":$new_word;
				$i+=3;
			}
	  }
	  return $big5_str;
	}
	
	class YahooPoster extends UCCASS_Main {
		Function getTitle($sid) {
			$rs = $this->Query("SELECT sid, name from {$this->CONF['db_tbl_prefix']}surveys WHERE sid = $sid");
			if($rs === FALSE)
			{ $this->error("Error loading Survey #$sid: " . $this->db->ErrorMsg()); return; }
			elseif($r = $rs->FetchRow($rs)) {
				return $r["name"];
			}
		}
	
		Function getDesc($sid) {
			$query = "SELECT q.qid, q.aid, q.question, q.page, a.type
			FROM {$this->CONF['db_tbl_prefix']}questions q,
			{$this->CONF['db_tbl_prefix']}answer_types a
			WHERE   q.aid = a.aid and q.sid = $sid and a.type='".ANSWER_TYPE_N."'";
			$rs =   $this->db->Execute($query);
			if($rs === FALSE)
			{ $this->error("Error loading Survey #$sid: " . $this->db->ErrorMsg()); return; }
			elseif($r = $rs->FetchRow($rs)) {
				return $r["question"];
			}
		}
	}
	
	$sid = $_REQUEST['sid'];
	
	$so = new YahooPoster();
	
	$surveySubject = $so->getTitle($sid);
	$surveyDesc = $so->getDesc($sid);
	$surveyDesc = str_replace("%sid%", $sid, $surveyDesc);
	
	//We'll using Big-5 to submit content.
	//$surveySubject = iconv("UTF-8",   "BIG-5", $surveySubject);
	//$surveyDesc   = iconv("UTF-8", "BIG-5", $surveyDesc);
	$surveySubject = UTF8ToBig5($surveySubject);
	$surveyDesc = UTF8toBig5($surveyDesc);
	
	
	echo "<pre>subject=".$surveySubject."<br>\n";
	echo "desc=".$surveyDesc."<br></pre>\n";
	if($so->error) {
		echo "get survey information error.";
		flush();
		exit;
	}
	
	set_time_limit(0);
	$http=new http_class;
	$http->timeout=3000;
	$http->data_timeout=3000;
	$http->debug=$debug;
	$http->html_debug=1;
	$http->user_agent="Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)";
	$http->follow_redirect=1;
	$http->redirection_limit=5;
	
	debug("<BR><======== Login<br>");
	//Login
	$error=$http->GetRequestArguments($loginUrl, $arguments);
	/* Set additional request headers */
	$arguments["RequestMethod"]="POST";
	$arguments["PostValues"] =  array(
	"login"         =>          $account,
	"passwd"        =>          $passwd
	);
	
	$error=$http->Open($arguments);
	if($error=="") { debug("no error<br>"); } else { echo($error); }
	$error=$http->SendRequest($arguments);
	if($error=="") { debug("no error<br>"); debug($http->response_status."<br>"); } else { echo($error); }
	
	for(;;)
	{
		$error=$http->ReadReplyBody($body,1000);
		if($error!="" || strlen($body)==0) break;
		if($echoBody) { debug( HtmlSpecialChars($body)); }
	}
	flush();
	
	$http->close();
	
	debug("<BR><======== Get YCB<br>");
	$error=$http->GetRequestArguments($postUrl, $arguments);
	$arguments["RequestMethod"]="POST";
	
	//Get ycb from post form body
	$arguments["PostValues"] =  array(
	"referer"               =>          "/group/daadadad/messages",
	"messageNum"        =>          "0",
	"send"              =>          "send",
	"subject"           =>          "subject",
	"announce"          =>          "y",
	"lang"              =>          "03",
	"message"           =>          "message",
	"ycb"               =>          ""
	);
	
	$error=$http->Open($arguments);
	if($error=="") { debug("no error<br>"); } else { echo($error); }
	$error=$http->SendRequest($arguments);
	if($error=="") { debug("no error<br>"); /*debug($http->response_status."<br>"); */} else { ($error); }
	
	for(;;)
	{
		debug("<br><============================ i =================================><br>");
		$error=$http->ReadReplyBody($body, 50000);
		if($error!="" || strlen($body)==0) break;
	
		$pos1 = strpos($body, "name=\"ycb\" value=\"");
		$pos2 = strpos($body, "value=\"", $pos1);
		$pos3 = strpos($body, "\"", $pos2);
		debug("<br><========== for ycb value, pos1=".$pos1.", pos2=".$pos3.", distance=".($pos2-$pos1)." =================================>");
		if($pos2-$pos1 > 11) {
			debug("<br><========== distance between pos1, pos2 is too long for ycb value. current ycb=".$ycbValue." =======>");
			continue;
		} else {
			
		}
		$ycbValue = substr($body, $pos3+1, 11);
	
		if($echoBody) { debug( HtmlSpecialChars($body)); }
	
		debug("<br><============ ycbValue=".$ycbValue." ==================>");
	}
	flush();
	
	$http->close();
	
	debug("<BR><======== Submit Content<br>");
	$error=$http->GetRequestArguments($postUrl, $arguments);
	$arguments["RequestMethod"]="POST";
	
	//Get ycb from post form body
	$arguments["PostValues"] =  array(
	"referer"           =>          "/group/daadadad/messages",
	"messageNum"    =>          "0",
	"send"          =>          "發送",
	"subject"       =>          $surveySubject,
	"announce"      =>          "y",
	"lang"          =>          "03",
	"message"       =>          $surveyDesc,
	"ycb"           =>          $ycbValue
	);
	
	$error=$http->Open($arguments);
	if($error=="") { debug("no error<br>"); } else { echo($error); }
	$error=$http->SendRequest($arguments);
	if($error=="") { debug("no error<br>"); debug($http->response_status."<br>"); } else { echo($error); }
	
	for(;;)
	{
		$error=$http->ReadReplyBody($body, 30000);
		if($error!="" || strlen($body)==0) break;
	
		if( strpos($body, "已張貼訊息") ) {
			echo "<b>Posted to Yahoo Groups<b><br>";
		} else {
				echo "<font color='red'>Failed to Post to Yahoo Groups!</font><br>";
				flush();
		}
		if($echoBody) { debug( HtmlSpecialChars($body)); }
	}
	flush();
	$http->close();
?>