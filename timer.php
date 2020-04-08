<?php
session_start();
require("config.php");
$myConn = mysqli_connect($myHost, $myUN, $myPW, $myDB);

$renewFileInfoToCookie = function($id) use($sessionsKey) {
	$sessionFile = $_SESSION['session_file_'.$id];
	$sessionTime = $_SESSION['session_time_'.$id];
	$sessionTimeVideo = $_SESSION['session_time_video_'.$id];
	$sessionLasttime = $_SESSION['session_time_last_'.$id];
		
		// eval check var
	$sessionCheck = md5($sessionFile . $sessionTime . $sessionLasttime . $sessionsKey);
	$ex = time() + $sessionTime;
	$exVideo = time() + $sessionTimeVideo;

    //var_dump($sessionFile, $sessionTime, $sessionLasttime);
    
		// save to cookie
	setcookie('session_file_'.$id, $sessionFile, $ex);
	setcookie('session_time_'.$id, $sessionTime, $ex);
	setcookie('session_time_video_'.$id, $sessionTimeVideo, $exVideo );
	setcookie('session_time_last_'.$id, $sessionLasttime, $ex);
	setcookie('session_check_'.$id, $sessionCheck, $ex);
};

if(isset($_POST['action']) && isset($_POST['id'])){
	$id = $_POST['id'];
	$reqAction = $_POST['action'];
	
	
	$myQuery = "SELECT title, link, latest, hash, started, session, duration, `created`, `life_id`, `life_id_time`, `life_link`, `life_link_time`, `life_video`, `life_video_time`, `life_video_timer`, `link_last`, `all_device`, `active_session`, `online`, `timer` FROM dl_links WHERE hash = '$id'";
	$myResult = mysqli_query($myConn, $myQuery);
	
	if (!($myArray = mysqli_fetch_array($myResult))) {
		header("Location: /downloader.php?err=2");
	}
	
	
	if($reqAction == "getTimer"){
		
		echo $_SESSION['session_time_'.$id];
		echo $_SESSION['session_time_video_'.$id];


		die();
	}

    if($reqAction == "userOnline"){
        if($myArray['online'] == '' or $myArray['online'] == '0'){
            if($myArray['session'] != $_SESSION['session_file_' . $id]) {
                if ($_SESSION['session_file_' . $id] == '') {
                    $sessionFile = md5(date("Y-m-d H:i:s"));

                    $myQuery = ("UPDATE dl_links SET session = '$sessionFile' WHERE hash = '$id'");
                    $myResult = mysqli_query($myConn, $myQuery);
                    $_SESSION['session_file_' . $id] = $sessionFile;
                }
                $myQuery = ("UPDATE dl_links SET online = '1' WHERE hash = '$id'");
                $myResult = mysqli_query($myConn, $myQuery);
                $sessionTime = $myArray['timer'];
                if ($myArray['life_video'] != '' and $myArray['life_video_timer'] != '0' or $myArray['life_video'] != '' and $myArray['life_video_timer'] != '') {
                    $sessionTimeVideo = $myArray['life_video_timer'];
                } else {
                    $sessionTimeVideo = $myArray['life_video'] * 60;
                }
                $sessionLasttime = $myArray['duration'];
                $_SESSION['session_time_' . $id] = $sessionTime;
                $_SESSION['session_time_video_' . $id] = $sessionTimeVideo;
                $_SESSION['session_time_last_' . $id] = $sessionLasttime;

            }else{
                $myQuery = ("UPDATE dl_links SET online = '1' WHERE hash = '$id'");
                $myResult = mysqli_query($myConn, $myQuery);
            }


        }else{
            if($myArray['session'] == $_SESSION['session_file_' . $id]) {
                if ($_SESSION['session_time_' . $id] == null) {
                    $sessionTime = $myArray['timer'];
                    if ($myArray['life_video'] != null) {
                        $sessionTimeVideo = $myArray['life_video_timer'];
                    } else {
                        $sessionTimeVideo = $myArray['life_video'] * 60;
                    }
                    $sessionLasttime = $myArray['duration'];
                    $_SESSION['session_time_' . $id] = $sessionTime;
                    $_SESSION['session_time_video_' . $id] = $sessionTimeVideo;
                    $_SESSION['session_time_last_' . $id] = $sessionLasttime;
                }

            }else{

            }

        }

        die();
    }

    if($reqAction == "userSession"){
        $now = date("Y-m-d H:i:s");
        $myQuery = ("UPDATE dl_links SET created2 = '$now', online='0' WHERE hash = '$id'");
        $myResult = mysqli_query($myConn, $myQuery);
        die();
    }
	
	if($reqAction == "countTimer") {
        if ($myArray['active_session'] == 0) {
            echo 'session_inactive';
        } else {
        if ($_SESSION['session_time_' . $id] <= 0) {
            $sessionFile = $_SESSION['session_file_' . $id];
            $myQuery = ("UPDATE dl_links SET session = '$sessionFile', online=0 WHERE hash = '$id'");
            $myResult = mysqli_query($myConn, $myQuery);
            $sessionTime = $myArray['timer'];
            if ($myArray['life_video'] != '' and $myArray['life_video_timer'] != '') {
                $sessionTimeVideo = $myArray['life_video_timer'];
            } else {
                $sessionTimeVideo = $myArray['life_video'] * 60;
            }
            $_SESSION['session_time_' . $id] = $sessionTime;
            $_SESSION['session_time_video_' . $id] = $sessionTimeVideo;
            echo 'reload';
            //var_dump($_SESSION['session_time_'.$id]);
            die();
        }
            if($myArray['timer'] != ''){
                $curTime = $myArray['timer'];
            }else{
                $curTime = $_SESSION['session_time_' . $id];
            }

        $curTime = $curTime - 1;
        $_SESSION['session_time_' . $id] = $curTime;


        $timer = $_SESSION['session_time_' . $id];
        $myQuery = "UPDATE dl_links SET timer = $timer WHERE hash = '$id'";
        $myResult = mysqli_query($myConn, $myQuery);


        $renewFileInfoToCookie($id);

        echo $curTime;
        die();
    }
	}
    if($reqAction == "countTimerVideo"){
        if ($myArray['active_session'] == 0) {
            echo 'session_inactive';
        } else {
            if ($myArray['life_video_timer'] == 0 and $myArray['life_video_timer'] != '') {
                if ($myArray['link_last'] != 'null' and $myArray['link_last'] != '') {
                    echo 'video';
                    $new_link = $myArray['link_last'];
                    if ($myArray['link'] != $new_link and $new_link != '' and $new_link != 'null') {
                        $myQuery = "UPDATE dl_links SET link = '$new_link' WHERE hash = '$id'";
                        mysqli_query($myConn, $myQuery);
                    }
                    $myQuery2 = "UPDATE dl_links SET link_last = 'null', online=0 WHERE hash = '$id'";
                    mysqli_query($myConn, $myQuery2);
                }

                //var_dump($_SESSION['session_time_'.$id]);

            } else {
                if($myArray['life_video_timer'] != ''){
                    $curTimeVideo = $myArray['life_video_timer'];
                }else{
                    $curTimeVideo = $_SESSION['session_time_video_' . $id];
                }

                $curTimeVideo = $curTimeVideo - 1;
                $_SESSION['session_time_video_' . $id] = $curTimeVideo;


                $life_video_timer = $_SESSION['session_time_video_' . $id];
                $myQuery = "UPDATE dl_links SET life_video_timer = $life_video_timer WHERE hash = '$id'";
                $myResult = mysqli_query($myConn, $myQuery);


                $renewFileInfoToCookie($id);

                echo $curTimeVideo;
                die();
            }
        }
    }
	if($reqAction == "updateTimeOut"){
		$user_id = $_POST['user_id'];
		$ip = $_SERVER['REMOTE_ADDR'];
		$timeout = date('d-m-Y H:i', $_SERVER['REQUEST_TIME']);
		$myNewQuery = "UPDATE dl_data SET timeout = '$timeout' WHERE id = $user_id AND ip = '$ip' ORDER BY timein DESC LIMIT 1";
		$myNewResult = mysqli_query($myConn, $myNewQuery);
	}
}