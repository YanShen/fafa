<?

require("./classes/class.phpmailer.php");

$mail = new PHPMailer();
$address = $_POST['address'];
$mail->IsSMTP(); // set mailer to use SMTP
$mail->Host = "smtp.mail2000.com.tw"; // ���wmail host
$mail->SMTPAuth = true; // �Ұ�smtp�{��
$mail->Username = "carollee"; // SMTP username
$mail->Password = "yan789"; // SMTP password

$mail->From = "carollee@mail2000.com.tw"; //�H�H�a�}
$mail->FromName = "Service"; //�H�H�H�W��
$mail->AddAddress("jengyan@gmail.com");//����H�a�}�B�W��
//$mail->AddAddress(""); // ���H�a�}���t�@�إΪk
$mail->AddReplyTo("$replyTo, $replyToName);//�^��

//$mail->WordWrap = 50; // set word wrap to 50 characters
//$mail->AddAttachment("/var/tmp/file.tar.gz"); // add attachments
//$mail->AddAttachment("/tmp/image.jpg", "new.jpg"); // optional name
//$mail->IsHTML(true); // set email format to HTML
$mail->CharSet="utf-8";//�ϥ�utf8�s�X�A���y�q�Ϊ��榡
$mail->Encoding = "base64";//�ϥ�base64�[�K

$mail->Subject = "�H��D��";
$mail->Body ="�H�󤺮e";
$mail->AltBody = "�H����[���e";


if(!$mail->Send())
{
 echo "Message could not be sent. <p>";
 echo "Mailer Error: " . $mail->ErrorInfo;
 exit;
}

echo "�z���ݨD�w�H�X�A�A�ȤH���|�ɳt�P�z�pô�C�P��~~";

?>

