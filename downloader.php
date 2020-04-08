<?php

session_start();
require("config.php");

$saveFileInfoToCookie = function($id) use($sessionsKey) {
	$sessionFile = $_SESSION['session_file_'.$id];
	$sessionTime = $_SESSION['session_time_'.$id];
	$sessionTimeVideo = $_SESSION['session_time_video_'.$id];
	$sessionLasttime = $_SESSION['session_time_last_'.$id];
		
		// eval check var
	$sessionCheck = md5($sessionFile . $sessionTime . $sessionLasttime . $sessionsKey);
	$ex = time() + $sessionTime;
	$exVideo = time() + $sessionTimeVideo;

		// save to cookie
	setcookie('session_file_'.$id, $sessionFile, $ex);
	setcookie('session_time_'.$id, $sessionTime, $ex);
	setcookie('session_time_video_'.$id, $sessionTimeVideo, $exVideo);
	setcookie('session_time_last_'.$id, $sessionLasttime, $ex);
	setcookie('session_check_'.$id, $sessionCheck, $ex);
};

$tryToLoadFileInfoToCookie = function($id) use($sessionsKey) {
	if (isset($_COOKIE['session_file_'.$id])) {
			// read from cookie
		$sessionFile     = $_COOKIE['session_file_'.$id];
		$sessionTime     = $_COOKIE['session_time_'.$id];
		$sessionTimeVideo     = $_COOKIE['session_time_video_'.$id];
		$sessionLasttime = $_COOKIE['session_time_last_'.$id];
		$sessionsCheck   = $_COOKIE['session_check_'.$id];
		
		$trueSessionCheck = md5($sessionFile . $sessionTime . $sessionLasttime . $sessionsKey);
		
			// renew session
		if ($sessionsCheck === $trueSessionCheck) {
			$_SESSION['session_file_'.$id] = $sessionFile;
			$_SESSION['session_time_'.$id] = $sessionTime;
			$_SESSION['session_time_video_'.$id] = $sessionTimeVideo;
			$_SESSION['session_time_last_'.$id] = $sessionLasttime;
		}
	}
};


$errMessages = array("file-id not submitted", "fatal error: the file could not be opened!", "file-id does not exist");

if (isset($_GET["err"])) {
	$errNum = $_GET["err"];
	$error = $errMessages[$errNum];
	echo "<html>\n<head>\n <title>Ваше видео уже активировано!</title>\n</head>\n";
	echo "<body><pre>\n <b>Ваше видео уже активировано, или истекло время просмотра!</b> \n";
	echo "<body><pre>\n Если у Вас возникли сложности, звоните ! \n";
	echo "<body><pre>\n Техподдержка работает 24/7 \n";
	echo "</pre>\n";
	echo "<a href=\"http://www.med.ru\">Вернуться на сайт продовца</a>\n";
	echo "</body>\n</html>";
	exit();
}
if (isset($_GET["error"])) {
	if ($_GET["error"] == 1) {
		echo '<h1>Видео не доступно! Истек срок жизни id</h1>';
	}elseif ($_GET["error"] == 2) {
		echo '<h1>Смотреть видео одновременно с 2-х и более устройств, запрещено!</h1>';
	}
}

if (isset($_GET["id"])) {
	$id = $_GET["id"];
	$myConn = mysqli_connect($myHost, $myUN, $myPW, $myDB);
	if ($myConn) {
		$now = date("Y-m-d H:i:s");
		mysqli_select_db($myConn,$myDB);
		$myQuery = "SELECT title, link, latest, hash, started, session, duration, `created`, `life_id`, `life_id_time`, `life_link`, `life_link_time`, `life_video`, `life_video_time`, `link_last`, `all_device`, `created2`, online, active_session FROM dl_links WHERE hash = '$id'";
		$myResult = mysqli_query($myConn, $myQuery);

		if (!($myArray = mysqli_fetch_array($myResult))) {
			header("Location: ".$PHP_SELF."?err=2");
		}
		else {
			if ($myArray["life_id"] == '0') {
				$life_id_time = 0;
			} else {
				$life_id_time = $myArray["life_id_time"];
				if ($now >= $life_id_time){
					header("Location: ".$PHP_SELF."?error=1");
				}
			}


				$skipLink = $PHP_SELF . "?id=" . $id . "&skip=1";
				$title = $myArray["title"];
				$link = $myArray["link"];
				// var_dump($_SESSION['session_time']);
				// die();
				if (empty($myArray['started'])) {
					$sessionFile = md5(date("Y-m-d H:i:s"));
					$sessionTime = $myArray['duration'] * 60;
					if($myArray['life_video'] != null){
						$sessionTimeVideo = $myArray['life_video'] * 60;
					}else{
						$sessionTimeVideo = null;
					}

					$sessionLasttime = $myArray['duration'];

					$_SESSION['session_file_' . $id] = $sessionFile;
					$_SESSION['session_time_' . $id] = $sessionTime;
					$_SESSION['session_time_video_' . $id] = $sessionTimeVideo;
					$_SESSION['session_time_last_' . $id] = $sessionLasttime;

					// save to cookie
					$saveFileInfoToCookie($id);

					$myQuery = ("UPDATE dl_links SET latest = '$now', started = NOW(), session = '$sessionFile' WHERE hash = '$id'");
				} else {
					$sessionFlag = false;

					if (!isset($_SESSION['session_file_' . $id])) {
						$tryToLoadFileInfoToCookie($id);

					}

					if (isset($_SESSION['session_file_' . $id])) {
						if ($_SESSION['session_file_' . $id] == $myArray['session']) {
							if (isset($_SESSION['session_time_' . $id])) {
								if ($myArray['duration'] != $_SESSION['session_time_last_' . $id]) {
									$timeSum = $myArray['duration'] - $_SESSION['session_time_last_' . $id];
									$timeSum2 = $myArray['life_video'] - $_SESSION['session_time_last_' . $id];
									$_SESSION['session_time_' . $id] = $_SESSION['session_time_' . $id] + $timeSum * 60;
									$_SESSION['session_time_video_' . $id] = $_SESSION['session_time_video' . $id] + $timeSum2 * 60;
									$_SESSION['session_time_last_' . $id] = $myArray['duration'];

									$saveFileInfoToCookie($id);
								}


								if ($_SESSION['session_time_' . $id] <= 0 and $myArray['active_session'] != 0) {
									$myQuery = "DELETE FROM dl_links WHERE hash = '$id'";
									mysqli_query($myConn, $myQuery);
									header("Location: " . $PHP_SELF . "?err=2");
								}
								$sessionFlag = true;
							}
						}
					}

					if ($sessionFlag) {
						$myQuery = ("UPDATE dl_links SET latest = '$now' WHERE hash = '$id'");
					}else {
						if($myArray['all_device'] == '1'){
							//$saveFileInfoToCookie($id);
							if($myArray['online'] == '1'){
								header("Location: " . $PHP_SELF . "?error=2");
							}
						}else{
							header("Location: " . $PHP_SELF . "?err=2");
						}

					}
					//var_dump($myArray['all_device']);
				}

				if (mysqli_query($myConn, $myQuery)) {
					if (@fopen($link, "r")) {
						if (($ThankU) && (!(isset($_GET["skip"])))) {
							include("$ThankU");
						} else {
							//header("Location: " . $link);
						}
					} else {
						header("Location: " . $PHP_SELF . "?err=1");
					}
				} else {
					header("Location: " . $PHP_SELF . "?err=1");
				}
		}
	}
	else {
		header("Location: ".$PHP_SELF."?err=1");
	}
}

else {
	//header("Location: ".$PHP_SELF."?err=1");
}

?>
