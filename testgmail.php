<?php
$to_address="jengyan@gmail.com";
$to_name="aaa";
$subject="使用phpmailer發送郵件";
$body="使用phpmailer發送郵件～";

send_mail($to_address, $to_name ,$subject, $body);

function send_mail($to_address, $to_name ,$subject, $body, $attach = "")
{
  //使用phpmailer發送郵件
  require_once("./classes/class.phpmailer.php");
  $mail = new PHPMailer();
  $mail->IsSMTP(); // set mailer to use SMTP
  $mail->CharSet = "UTF-8";
  $mail->Encoding = "base64";
  $mail->From = "krlee8@gmail.com";
  $mail->FromName = "寄件人";

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
    echo "郵件送出失敗！ ";
    echo "錯誤訊息： " . $mail->ErrorInfo . " ";
    return false;
  }
  else
  {
    echo("寄信 $attach 給 $to_name <$to_address> 完成！ ");
    return true;
  }
}
?>
