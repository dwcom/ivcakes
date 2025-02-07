<?php
$name = trim($_POST['name']);
$phone = trim($_POST['phone']);
$email = trim($_POST['email']);
$message = trim($_POST['comment']);
$url = $_SERVER[SERVER_NAME];

$fromMail = 'res@'. $url;
$fromName = 'Информатор';

$emailTo = 'info@dwcom.ru';
$subject = 'Заявка с лендинга!';
$subject = '=?utf-8?b?'. base64_encode($subject) .'?=';
$headers = "Content-type: text/plain; charset=\"utf-8\"\r\n";
$headers .= "From: ". $fromName ." <". $fromMail ."> \r\n";

$body = "
  Имя: $name \n
  Телефон: $phone \n
  Email: $email \n
  Сообщение: $message \n
  Сайт: $url \n
";

$mail = mail($emailTo, $subject, $body, $headers, '-f'. $fromMail );
?>
