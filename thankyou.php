<?
	require("config.php");
	mysqli_select_db($myConn, $myDB); // suppress error-messages
	mysqli_set_charset($myConn, 'utf8');
	
	//исходя из хеша получаем ID лектора
	if (isset($_GET['id'])) {
		
		$hash = mysqli_real_escape_string($myConn, $_GET['id']);

        $get_hash = "SELECT lecturer, id_video FROM dl_links WHERE hash = '$hash'";
		if($myResult = mysqli_query($myConn, $get_hash)) {
			$myArray = mysqli_fetch_array($myResult);
			$lecturer = $myArray["lecturer"];
			$id_video = $myArray["id_video"];
		}
		
		if (is_numeric($lecturer) and $lecturer > 0) {
			$get_lecturer = "SELECT * FROM dl_lecturers WHERE id = $lecturer";
			if($myResult = mysqli_query($myConn, $get_lecturer)) {
				$myArray = mysqli_fetch_array($myResult);
				$name = $myArray["name"];
				$photo_url = $myArray["photo_url"];
				$description = $myArray["description"];
			}

            $get_video = "SELECT * FROM dl_video WHERE id = $id_video";
            if($myResult = mysqli_query($myConn, $get_video)) {
                $myArray           = mysqli_fetch_array($myResult);
                $link              = $myArray["url"];
                $description_video = $myArray["description"];
            }
		}
	}	
	
?>


<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="initial-scale=1, user-scalable=no">
	<title><?=$myTitle;?> АНО Учебный центр "Невромед-Клиник"</title>

	<link rel="stylesheet" type="text/css" href="css/style.css?1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<?php if ($MyCSS) { ?>
		<link rel="stylesheet" href="<?=$MyCSS;?>" type="text/css">
	<?php } ?>
	<script src="https://vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js"></script>
	<link href="https://unpkg.com/video.js/dist/video-js.min.css" rel="stylesheet">
	<script src="https://unpkg.com/video.js/dist/video.min.js"></script>
</head>
<body>

	     <?php include_once("include/header.php"); ?>
		
		 <?php include_once("include/lektor.php"); ?>
		 
		 <?php include_once("include/video.php"); ?>

         <?php include_once("include/send.php"); ?>
	  
		 <?php include_once("include/footer.php"); ?>

  
	<div id="query-form-layer">
		<div class="query-form-block">
			<button type="button" class="_close_btn">x</button>
			
			<div class="_title">Вопрос лектору</div>
			<form id="query-form" action="query.php" method="POST" target="for_forms">
				<input type="text" name="name"  required placeholder="Представьтесь, пожалуйста"></p>
				<input type="text" name="email" required placeholder="E-mail"></p>
				<p class="noClear">
					<textarea name="message" placeholder="Вопрос"></textarea>
				</p>
				<input type="submit" name="submit" value="Отправить">
			</form>
		</div>
	</div>
	<iframe name="for_forms" style="display: none"></iframe>
  
  <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
       <?php include_once("include/thankyouScripts.php"); ?>
</html>