<?php
$to  = $_GET['email'].', mail@introdata.ru';
$idd = $_GET['id'];
$from = '=?UTF-8?B?'. base64_encode("Учебный центр Невромед-Клиника") .'?=';

$subject = "Активация видео";

$message = file_get_contents('http://'.$_SERVER['HTTP_HOST']."/mail_html.php?id=$idd");

$headers  = "Content-type: text/html; charset=utf-8 \r\n";
$headers .= "From: $from <mail@introdata.ru>\r\n";
$headers .= "Return-path: <mail@introdata.ru>\r\n";
$headers .= 'MIME-Version: 1.0';
$headers .= 'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);
$password = $_GET["password"];
?>

<h1>Письмо отправлено</h1>
<p>Сейчас вы будете перенаправлены на страницу show</p>
<script>setTimeout('location.replace("/admin/index.php?action=show")',3000);</script>