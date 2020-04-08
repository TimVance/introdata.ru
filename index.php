<?php
session_start();
require("config.php");

if(isset($_GET["id"])){
	$id = $_GET["id"];
	$myConn = mysqli_connect($myHost, $myUN, $myPW, $myDB);
	if ($myConn) {
		$now = date("Y-m-d H:i:s");
		mysqli_select_db($myConn,$myDB);
		$myQuery = "SELECT title, link, latest, hash, started, session, duration FROM dl_links WHERE id = '$id'";
		$myResult = mysqli_query($myConn, $myQuery);
		
		if (!($myArray = mysqli_fetch_array($myResult))) {
			header("Location: /downloader.php?err=2");
//echo("err1");
		}
		
		
		else {
			
			
			$hash = $myArray["hash"];
			//$myConn1 = mysqli_connect($myHost, $myUN, $myPW, $myDB);
			if ($myConn) {
			$ip = $_SERVER['REMOTE_ADDR'];
			$useragent = $_SERVER['HTTP_USER_AGENT'];
			$timein = date('d-m-Y H:i', $_SERVER['REQUEST_TIME']);
			$myQuery1 = "INSERT INTO dl_data ( id , ip, timein, checkbox, useragent) VALUES ( '$id', '$ip','$timein', '1', '$useragent')";

			$myResult1 = mysqli_query($myConn, $myQuery1);
			}
	header("Location: /downloader.php?id=".$hash."&user_id=".$id);
	//echo($hash);
			}}
			else {
header("Location: /downloader.php?err=2");
//echo("err3");
}
}
?>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="initial-scale=1, user-scalable=no">
	<title><?=$myTitle;?> АНО Учебный центр "Невромед-Клиник"</title>

	<link rel="stylesheet" type="text/css" href="css/style1.css?1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<?php if ($MyCSS) { ?>
		<link rel="stylesheet" href="<?=$MyCSS;?>" type="text/css">
	<?php } ?>

</head>
<body>

	        <?php include_once("include/header.php"); ?>

<div class="content ost">
<?php
echo "<div align=\"center\">\n";
echo "<p class=\"infotext\">Доступ к учебному центру:</p>\n";
echo "<hr width=\"100%\">\n";
echo "<form action=\"$PHP_SELF\" method=\"get\">\n";
echo "<p><strong>Введите ваш пин-код:</strong></p>\n";
echo "<input type=\"text\" name=\"id\"><br><br>\n";
echo "<input type=\"submit\" value=\"Начать просмотр\" >&nbsp;&nbsp;<input type=\"reset\" value=\"Очистить\">\n";
echo "</form>\n</div>";
?>
	</div>
	
	<?php include_once("include/footer.php"); ?>


</body>
</html>

