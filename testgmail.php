<?php
$to_address="jengyan@gmail.com";
$to_name="aaa";
$subject="�ϥ�phpmailer�o�e�l��";
$body="�ϥ�phpmailer�o�e�l���";

send_mail($to_address, $to_name ,$subject, $body);

function send_mail($to_address, $to_name ,$subject, $body, $attach = "")
{
  //�ϥ�phpmailer�o�e�l��
  require_once("./classes/class.phpmailer.php");
  $mail = new PHPMailer();
  $mail->IsSMTP(); // set mailer to use SMTP
  $mail->CharSet = "UTF-8";
  $mail->Encoding = "base64";
  $mail->From = "krlee8@gmail.com";
  $mail->FromName = "�H��H";

  $mail->Host = 'ssl://smtp.gmail.com';
  $mail->Port = 465; //default is 25, gmail is 465 or 587
  $mail->SMTPAuth = true;
  $mail->Username = "krlee8@gmail.com";
  $mail->Password = "yan789";

  $mail->addAddress($to_address, $to_name);
  $mail->WordWrap = 50;
  if (!empty($attach))
  $mail->AddAttachment($attach);
  $mail->IsHTML(false);
  $mail->Subject = $subject;
  $mail->Body = $body;

  if(!$mail->Send())
  {
    echo "�l��e�X���ѡI ";
    echo "���~�T���G " . $mail->ErrorInfo . " ";
    return false;
  }
  else
  {
    echo("�H�H $attach �� $to_name <$to_address> �����I ");
    return true;
  }
}
?>
