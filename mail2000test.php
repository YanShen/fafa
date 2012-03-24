<?

require("./classes/class.phpmailer.php");

$mail = new PHPMailer();
$address = $_POST['address'];
$mail->IsSMTP(); // set mailer to use SMTP
$mail->Host = "smtp.mail2000.com.tw"; // 指定mail host
$mail->SMTPAuth = true; // 啟動smtp認證
$mail->Username = "carollee"; // SMTP username
$mail->Password = "yan789"; // SMTP password

$mail->From = "carollee@mail2000.com.tw"; //寄信地址
$mail->FromName = "Service"; //寄信人名稱
$mail->AddAddress("jengyan@gmail.com");//收件人地址、名稱
//$mail->AddAddress(""); // 收信地址的另一種用法
$mail->AddReplyTo("$replyTo, $replyToName);//回條

//$mail->WordWrap = 50; // set word wrap to 50 characters
//$mail->AddAttachment("/var/tmp/file.tar.gz"); // add attachments
//$mail->AddAttachment("/tmp/image.jpg", "new.jpg"); // optional name
//$mail->IsHTML(true); // set email format to HTML
$mail->CharSet="utf-8";//使用utf8編碼，全球通用的格式
$mail->Encoding = "base64";//使用base64加密

$mail->Subject = "信件主旨";
$mail->Body ="信件內容";
$mail->AltBody = "信件附加內容";


if(!$mail->Send())
{
 echo "Message could not be sent. <p>";
 echo "Mailer Error: " . $mail->ErrorInfo;
 exit;
}

echo "您的需求已寄出，服務人員會盡速與您聯繫。感謝~~";

?>

