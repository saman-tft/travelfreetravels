<?php
require("class.phpmailer.php");
$mail = new PHPMailer();

$mail->SMTPSecure = "ssl";  
$mail->Host='smtp.gmail.com';  
$mail->Port='465';   
$mail->Username   = 'hearaman@gmail.com'; // SMTP account username  
$mail->Password   = 'ramanraman';  
$mail->SMTPKeepAlive = true;  
$mail->Mailer = "smtp"; 
$mail->IsSMTP(); // telling the class to use SMTP  

$mail->AddReplyTo('hearaman@gmail.com', 'Hearaman');
$mail->AddAddress('hearaman@weforon.com', 'Hearaman');
$mail->SetFrom('hearaman@weforon.com', 'Hearaman');
$mail->Subject = "Hello";

$mail->Body = "This is a mail for u from PHP Mailer";

$mail->SMTPAuth   = true;                 // enable SMTP authentication  
$mail->CharSet = 'utf-8';  
$mail->SMTPDebug  = 0;  
 
if(!$mail->Send())
{
   echo "Error sending: " . $mail->ErrorInfo;;
}
else
{
   echo "Letter is sent";
}?>


 