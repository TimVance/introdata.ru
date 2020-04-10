<?php
	require ("config.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

// $to  = "zotovi1962@mail.ru"; 
$to = "zotovi1962@mail.ru, ".$_POST['email'];

if ($_SERVER['REMOTE_ADDR'] == '88.84.214.201') {
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	
	$to  = "zotovi1962@mail.ru";
	
	echo 'test_incom';
	exit;
}


$subject = "Вопрос лектору"; 

$from = $_POST['email'];

$_POST['message'] = strip_tags($_POST['message']);
$_POST['name']    = strip_tags($_POST['name']);

$message  = '';
$message .= "Имя: {$_POST['name']} <br>\n";
$message .= "Вопрос: {$_POST['message']} <br>\n";

$headers  = "Content-type: text/html; charset=utf8 \n"; 
$headers .= "From: $from \n"; 
$headers .= "Reply-To: $from \n"; 

if ($to && $subject && $message && $headers) {
	$result = mail($to, $subject, $message, $headers); 
}
else {
	$result = false;
}


if ($result ) {
	echo "<html>\n<head>\n <title>Ваше письмо отправлено!</title>\n</head>\n";
	echo "<body><pre>\n <b>Ваше письмо отправлено!</b> \n";
	echo "<body><pre>\n Если у Вас возникли сложности, звоните! \n";
	echo "<body><pre>\n Техподдержка работает 24/7 \n";
	echo "</pre>\n";
	echo "<a href=\"http://www.nevromed.ru\">Вернуться на сайт продовца</a>\n";
	echo "</body>\n</html>";
	
	$dbConnect = new mysqli($myHost, $myUN, $myPW, $myDB);
	if (!$dbConnect->connect_error) {
		
		$mail_date = date('d-m-Y H:i', $_SERVER['REQUEST_TIME']);
		$author = $_POST['name'];
		$text = $_POST['message'];
		$dbConnect->query("SET NAMES 'utf8'");
		$dbConnect->query("INSERT INTO lect_mails (sent_date, mail_from, author, text) VALUES ('$mail_date','$from','$author','$text')");
	}
	
	exit();
}
else {
	echo "<html>\n<head>\n <title>Возникла ошибка при отправке письма!</title>\n</head>\n";
	echo "<body><pre>\n <b>Возникла ошибка при отправке письма!</b> <br> Попробуйте повторить отправку позже. \n";
	echo "<body><pre>\n Если у Вас возникли сложности, звоните! \n";
	echo "<body><pre>\n Техподдержка работает 24/7 \n";
	echo "</pre>\n";
	echo "<a href=\"http://www.nevromed.ru\">Вернуться на сайт продовца</a>\n";
	echo "</body>\n</html>";
	exit();
}