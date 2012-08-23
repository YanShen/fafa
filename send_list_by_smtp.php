<?
	
	require("classes/class.phpmailer.php");
	include('classes/main.class.php');
	include('classes/special_results.class.php');


	class SmtpMailSender {
		var $smtpServer = "mail.fetnet.net";
		var $charset = "UTF-8";
		var $fromAddr = "krlee8@gmail.com";
		var $fromName = "Carol Lee";
		var $replyTo = "krlee8@gmail.com";
		var $replyToName = "Carol Lee";
		var $userName = "carollee";
		var $password = "yan789";
		var $db;

/*
		function send($fn, $fa, $toAddr, $subject, $message) {
			//Going to Send email.		
			$mail = new phpmailer();
			$mail->Mailer = "smtp"; 
			$mail->Host = $this->smtpServer;
echo "mail.Host=".$mail->Host."<br>";
			$mail->SMTPAuth = true; 
			$mail->UserName = $userName;
			$mail->Password = $password;
			$mail->From = $fa;
			$mail->FromName = $fn;

			$mail->CharSet = $this->charset;
			
			foreach($toAddr as $ta) {
				$mail->AddAddress($ta, "");
			}

			$mail->AddCC($fa, $fn);
			$mail->AddReplyTo($replyTo, $replyName);
			$mail->IsHTML(true); // send as HTML
			$mail->Subject = $subject;
			$mail->Body = $message;
			$mail->AltBody = "此郵件為HTML格式."; //Text Body
			
			$result = $mail->Send();

			foreach($toAddr as $tvalue) {
				echo $tvalue."<br>";
			} 
			
			return $result;
		}
*/

		function send($fn, $fa, $toAddr, $subject, $message) {
			//Going to Send email.		
			$mail = new phpmailer();
			$mail->IsSMTP(); 
			$mail->Host = $this->smtpServer;
echo "mail.Host=".$mail->Host."<br>";
			$mail->SMTPAuth = true; 
			$mail->Username = $this->userName;
			$mail->Password = $this->password;
			$mail->From = $fa;
			$mail->FromName = $fn;

			$mail->CharSet = $this->charset;
			
			foreach($toAddr as $ta) {
				$mail->AddAddress($ta, "");
			}

			$mail->AddCC($fa, $fn);
			$mail->AddReplyTo($this->replyTo, $this->replyName);
			$mail->IsHTML(true); // send as HTML
			$mail->Subject = $subject;
			$mail->Body = $message;
			$mail->AltBody = "此郵件為HTML格式."; //Text Body
			
			$result = $mail->Send();

			foreach($toAddr as $tvalue) {
				echo $tvalue."<br>";
			} 

			return $result;
		}

		function getSurveyMailAttr($sid) {
			$result = array();
			
			$query = "SELECT mail_subject, mail_receiver
				FROM {$this->CONF['db_tbl_prefix']}surveys s
				WHERE sid= $sid";
			$rs =   $this->db->Execute($query);
			if($rs === FALSE)
			{ echo ("Error loading Survey #$sid: " . $this->db->ErrorMsg()); return; }
			elseif($r = $rs->FetchRow($rs)) {
				$result['mail_subject'] = $r['mail_subject'];
				$result['mail_receiver'] = preg_split("/\,/", $r['mail_receiver']);
				return $result;
			}
		}
		
		
		function sendBySid($sid, $message) {
			$result = $this->getSurveyMailAttr($sid);
			
			echo "正在寄送信件給".$result['mail_receiver']."...<br>\n";
			
			$sendingResult = $this->send($this->fromName, $this->fromAddr, $result['mail_receiver'], $result['mail_subject'], $message);
			
			echo "寄送完成!<br>\n";
			
			return $sendingResult;
		}
		
	
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
	
	$ini_file = "survey.ini.php";
	$ini = @parse_ini_file($ini_file);

	$smtpServer = $ini["mail.smtpServer"];	//"mail.fetnet.net";
	$fromAddr = $ini["mail.fromAddr"];		//"krlee8@gmail.com";
	$fromName = $ini["mail.fromName"];		//"李凱榕";
	$replyTo = $ini["mail.replyTo"];		 //"krlee8@gmail.com";
	$replyToName = $ini["mail.replyToName"];		//"李凱榕";
	$userName = $ini["mail.userName"];
  $password = $ini["mail.password"];
     
	//Get Message
	$survey = new UCCASS_Special_Results;
	$message .= $survey->results_email_by_marked(@$_REQUEST['sid'], @$_REQUEST['marked']);
   
    //Fetch from Database.
	$mailer = new SmtpMailSender();
	$mailer->smtpServer = $smtpServer;
	$mailer->Username = $userName;  
	$mailer->Password = $password; 
	$mailer->fromAddr = $fromAddr;
	$mailer->fromName = $fromName;
	$mailer->replyTo = $replyTo;
	$mailer->replyToName = replyToName;
	$mailer->db = $survey->db;

	$mailer->updateSelQid();
	
	$result = $mailer->sendBySid($_REQUEST['sid'], $message);
	
			
	if(!$result)
	{
		echo "<font color='red'>";
	  echo "寄送郵件失敗：".$mail->ErrorInfo."<br>";
	}
	else
	{
	  echo "郵件寄發送成功<br>";
	}

	echo "信件內容：<br>";
	echo $message; 
	
			
	if(!$result)
	{
		echo "</font>";
	}
?> 
<center>[<a href="#" onclick="javascript:window.close();">關閉</a>]  [<a href="<?=$ini['html']?>/admin0302_surveys.php">管理活動列表</a>]</center>
