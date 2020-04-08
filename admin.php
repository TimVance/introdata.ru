<?php

session_start();
require("config.php");
if(isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['lastname']) && isset($_POST['bithday']) && isset($_POST['country']) && isset($_POST['phone_number']) && isset($_POST['email']) && isset($_POST['learn']) && isset($_POST['job'])){
    // Пути загрузки файлов
    $path = 'documents/';
    $tmp_path = 'tmp/';
// Массив допустимых значений типа файла
    $types = array('image/gif', 'image/png', 'image/jpeg');
// Максимальный размер файла
    $size = 5242880;

// Обработка запроса
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['email'])){
        $password = $_POST["password"];
        $error = false;
        foreach ($_FILES as $key=>$picturess){
            if ($picturess['size'] > $size){
                echo 'size'.$key;
                header("Location: ?action=doctors&password=$password&mes=size");
                exit();
            }
            if (!in_array($picturess['type'], $types)){
                $error = true;
                header("Location: ?action=doctors&password=$password&mes=type");
                exit();
            }
        }
        if(!$error) {
            $destPath = $path . $_POST['email'] . '';
            if (!is_dir($destPath)) {
                mkdir($destPath, 0777, true);
            }
            foreach ($_FILES as $key=>$picturess) {
                // Загрузка файла и вывод сообщения
                if (!@copy($picturess['tmp_name'], $destPath . '/' .$key.'_'.$picturess['name'])) {
                    $error = true;
                    header("Location: ?action=doctors&password=$password&mes=dowloand");
                    exit();
                }else{
                    $url_img[] = $destPath . '/' .$key.'_'.$picturess['name'];
                }
            }
            if(!$error) {
                $name = $_POST['name'];
                $surname = $_POST['surname'];
                $lastname = $_POST['lastname'];
                $bithday = $_POST['bithday'];
                $country = $_POST['country'];
                $phone_number = $_POST['phone_number'];
                $email = $_POST['email'];
                $learn = $_POST['learn'];
                $job = $_POST['job'];


                $myConn = mysqli_connect($myHost, $myUN, $myPW, $myDB);
                if ($myConn) {
                    mysqli_select_db($myConn, $myDB);
                    mysqli_query($myConn,"SET NAMES utf8");
                    $myQuery0 = "INSERT INTO `users` (`name`, `surname`, `lastname`, `bithday`, `country`, `phone_number`, `email`, `learn`, `job`) VALUES ('$name', '$surname', '$lastname', '$bithday', '$country', '$phone_number', '$email', '$learn', '$job')";
                    $myResult0 = mysqli_query($myConn, $myQuery0);
                    if(!$myResult0){
                        $error = true;
                        header("Location: ?action=doctors&password=$password&mes=insert");
                        exit();
                    }else{
                        header("Location: ?action=doctors&password=$password&mes=ok");
                    }
                }
            }
        }
    }
}




function RemoveDir($path){
    chmod($path, 0777);
    if(file_exists($path) && is_dir($path))
    {
        $dirHandle = opendir($path);
        while (false !== ($file = readdir($dirHandle)))
        {
            if ($file!='.' && $file!='..')
            {
                $tmpPath=$path.'/'.$file;
                chmod($tmpPath, 0777);

                if (is_dir($tmpPath))
                {  // если папка
                    RemoveDir($tmpPath);
                }
                else
                {
                    if(file_exists($tmpPath))
                    {
                        unlink($tmpPath);
                    }
                }
            }
        }
        closedir($dirHandle);
        if(file_exists($path))
        {
            rmdir($path);
        }
    }
}
$myConn = mysqli_connect($myHost, $myUN, $myPW, $myDB);
mysqli_select_db($myConn, $myDB); // suppress error-messages
mysqli_set_charset($myConn, 'utf8');

if($_GET['action'] == 'send_mail' && !empty($_GET['email'])){
    $to  = $_GET['email'];
    $idd = $_GET['id'];
    $from = '=?UTF-8?B?'. base64_encode("Учебный центр 'Невромед-Клиника'") .'?=';

    $subject = "Активация видео";

    $message = file_get_contents('http://'.$_SERVER['HTTP_HOST']."/mail_html.php?id=$idd");

    $headers  = "Content-type: text/html; charset=utf-8 \r\n";
    $headers .= "From: $from <mail@introdata.ru>\r\n";
    $headers .= "Reply-To: <mail@introdata.ru>\r\n";
    $headers .= "Return-path: <mail@introdata.ru>\r\n";
    $headers .= 'MIME-Version: 1.0';
    $headers .= 'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers);
    $password = $_GET["password"];
    header("Location: ?action=show&password=$password");
}
if($_GET['action'] == 'change_doct' && isset($_GET["password"])){
    $iddd = $_POST['id'];
    if(isset($_POST['status']) && $_POST['status'] == 'yes'){
        $statusss = 2;
    }else{
        $statusss = 1;
    }
    $myQuery = "UPDATE `users` SET `status` = '$statusss' WHERE `id` = $iddd";
    $password = $_GET["password"];
    mysqli_query($myConn, $myQuery);
    header("Location: ?action=doctors&password=$password");
}

if($_GET['action'] == 'del_doctors' && isset($_GET["password"])){
    $iddd = $_GET['id'];
    $emaill = $_GET['email'];
    $myQuery = "DELETE FROM `users` WHERE `id` = $iddd";
    $password = $_GET["password"];
    mysqli_query($myConn, $myQuery);
    $path = 'documents/'.$emaill;
    RemoveDir($path);
    header("Location: ?action=doctors&password=$password");
}

// we need a function to display error-messages
function showError($errMsg) {
	echo "<div align=\"center\">\n";
	echo "<p class=\"infotext\"><strong>:: error ::</strong></p>\n";
	echo "<p>$errMsg</p>\n";
	echo "<p>:: go <a href=\"javascript.history.back(1);\">back</a> ::</p>\n";
	echo "</div>\n";
	exit;

}

echo "<html>\n<head>\n <title>".$MyTitle." Links admin]</title>\n";
echo '<link rel="stylesheet" type="text/css" href="css/style3.css">';
if ($MyCSS) 
	echo "<link rel=\"stylesheet\" href=\"$MyCSS\" type=\"text/css\">\n";

echo "</head>\n<body>\n";


if (isset($_POST["password"])) // check if the user is logging in
	$password = $_POST["password"];

if (isset($_GET["password"])) // check if the user already is logged in
	$password = $_GET["password"];

if ($password == $AdminPW) // check if password matches
	$login = "1"; // it did, user can log in
else
	$login = "0"; // it didnt, show user the login-screen




if ($login == "1") {
	echo "<div align=\"center\">\n";
	echo "<table width=\"50%\" border=0><tr>\n";
	echo "<tr><td align=\"center\"><p class=\"infotext\">:: myLink - admin ::</p></td></tr>\n";
	echo "<tr><td align=\"center\"><p><a href=\"?action=add&password=$password\">Add a link</a> :: ";
    echo "<a href=\"?action=doctors&password=$password\">Doctors</a> :: ";
	echo "<a href=\"?action=show&password=$password\">Show all link</a> :: <a href=\"?action=add_lecturer&password=$password\">Add lecturer</a> :: <a href=\"?action=statistics&password=$password\">Statistics</a> :: <a href=\"?action=mails&password=$password\">Rating</a> :: <a href=\"?action=lect_mails&password=$password\">Questions</a> :: <a href=\"?action=logout\">Log out</a></p>";
	echo "</td></tr></table>\n<hr width=\"100%\">\n";
	
	if($_GET["action"] == "logout") {
		$password = "";
		$login = "0";
		exit;
	}


    if($_GET["action"] == "doctors") {
	    echo '<button class="btn_reg" style="    width: 10%;">Add Doctor</button>';
        $myQuery = "SELECT * FROM `users`";
        if ($myResult = mysqli_query($myConn, $myQuery)) {
            ?>
            <table class="table_doctotrs">
                <tr>
                    <th>Ф.И.О</th>
                    <th>Дата р.</th>
                    <th>Место р.</th>
                    <th>Телефон</th>
                    <th>E-mail</th>
                    <th>Образование</th>
                    <th>Профессия</th>

                    <th>Статус</th>
                    <th>Del</th>
                </tr>
                <?
                while($myArray = mysqli_fetch_assoc($myResult)) {
                    if($myArray['status']>1){
                        $class = 'active_status';
                        $check = 'checked';
                    }else{
                        $class = 'dis_status';
                        $check = '';
                    }
                ?>
                <tr>
                    <td>
                        <?=$myArray['name']?><br>
                        <?=$myArray['surname']?><br>
                        <?=$myArray['lastname']?>
                    </td>
                    <td>
                        <?=$myArray['bithday']?>
                    </td>
                    <td>
                        <?=$myArray['country']?>
                    </td>
                    <td><?=$myArray['phone_number']?></td>
                    <td><?=$myArray['email']?></td>
                    <td><?=$myArray['learn']?></td>
                    <td><?=$myArray['job']?></td>
                    <td class="<?if(isset($class)) echo $class;?>">
                        <form class="set_status_form" action="?action=change_doct&password=<?=$password?>" method="post">
                            <input type="text" name="id" hidden value="<?=$myArray['id']?>">
                            <input class="set_status" value="yes" name="status" type="checkbox" <?if(isset($check)) echo $check;?>>
                        </form>
                    </td>
                    <td>
                        <a href="?action=del_doctors&password=<?=$password?>&id=<?=$myArray['id']?>&email=<?=$myArray['email']?>" >del</a>
                    </td>
                </tr>
                <?}?>
            </table>
        <?}
        else {
            $errNo = mysqli_errno($myConn);
            $error = mysqli_error($myConn);
            showError("$errNo: $error");
        }
    }
	
	if($_POST["action"] == "update") {
		if(strlen($_POST["title"]) < 5) {
			showError("Title entered is to short!");
		}
		if(strlen($_POST["link"]) < 5) {
			showError("The link seems to be to short!");
		}
		else {
			$id = $_POST["id"];
			$title = $_POST["title"];
			$link = $_POST["link"];
			$category = $_POST["category"];
			$lecturer = $_POST["lecturer"];
			$duration = $_POST["duration"];
			
			$myQuery = "UPDATE dl_links SET title = '$title', link = '$link', category = '$category', lecturer = '$lecturer', duration = '$duration' WHERE id = '$id'";
			if (mysqli_query($myConn, $myQuery)) {
				echo "<p>:: Download-id: <strong>$id</strong> has been updated! ::</p>";
			}
			else {
				$errNo = mysqli_errno($myConn);
				$error = mysqli_error($myConn);
				showError("$errNo: $error");
			}
		}
	}
	
	if($_POST["action"] == "update_lecturer") {
		if (strlen($_POST["name"]) == 0) {
			showError("You did not enter a name!");
		}
		elseif (strlen($_POST["photo_file"]) == 0) {
			showError("You did not enter a photo url!");
		}
		elseif (strlen($_POST["description"]) == 0) {
			showError("You did not enter a description!");
		}
		else {
			$id = 0;
			$name = '';
			$photo_url = '';
			$description = '';

			$id = $_POST["id"];
			$name = $_POST["name"];
			$photo_url = $_POST["photo_file"];
			$description = $_POST["description"];
			
			$myQuery = "UPDATE dl_lecturers SET name = '$name', photo_url = '$photo_url', description = '$description' WHERE id = '$id'";
			if (mysqli_query($myConn, $myQuery)) {
				echo "<p>:: Lecturer-id: <strong>$id</strong> has been updated! ::</p>";
			}
			else {
				$errNo = mysqli_errno($myConn);
				$error = mysqli_error($myConn);
				showError("$errNo: $error");
			}
		}
	}
	
	if($_GET["action"] == "edit") {
		if($_GET["id"]) {
			$id = $_GET["id"];
			$myQuery = "SELECT * FROM dl_links WHERE id = '$id'";
			if($myResult = mysqli_query($myConn, $myQuery)) {
				$myArray = mysqli_fetch_array($myResult);
				$myID = $myArray["id"];
				$myTitle = $myArray["title"];
				$myLink = $myArray["link"];
				$myCategory = $myArray["category"];
				$MyLecturer = $myArray["lecturer"];
				$myLatest = $myArray["latest"];
				$myDuration = $myArray["duration"];
				echo "<p>:: You are editing <strong>$myTitle</strong>. ::</p>\n";
				echo "<form method=\"post\">\n";
				echo "<input type=\"hidden\" name=\"action\" value=\"update\">\n";
				echo "<input type=\"hidden\" name=\"password\" value=\"$password\">\n";
				echo "<input type=\"hidden\" name=\"id\" value=\"$myID\">\n";
				echo "<table align=\"center\" border=\"0\" cellpadding=\"2\">\n";
				echo "<tr>\n <td><p>id: </p></td>\n";
				echo " <td><p><strong>$myID</strong></p></td>\n";
				echo "</tr>\n<tr>\n <td><p>Title: </p></td>\n";
				echo " <td><input type=\"text\" name=\"title\" size=\"25\" value=\"$myTitle\"></td>\n";
				echo "</tr>\n<tr>\n <td><p>Link to file: </p></td>\n";
				echo " <td><input type=\"text\" name=\"link\" size=\"25\" value=\"$myLink\"></td>\n";

				echo "<tr><td><p>Select Lecturer:</p></td>\n";
				echo "<td>";

				$myQuery = "SELECT id, name FROM dl_lecturers";
				$myResult = mysqli_query($myConn, $myQuery);
				
				echo '<select name="lecturer">';
				
				if ($myResult = mysqli_query($myConn, $myQuery)) {
					while($row = mysqli_fetch_array($myResult)) {
						if ($MyLecturer == $row["id"]) {
							echo '<option value="' . $row["id"] . '" selected>' . $row["name"] . '</option>';
						} else {
							echo '<option value="' . $row["id"] . '">' . $row["name"] . '</option>';
						}
						
					}
				}

				echo '</select>';
				echo "</td></tr>\n";

				echo "<tr><td><p><strong>Lifetime(min):</strong></p></td>\n";
				echo "<td><input type=\"text\" name=\"duration\" size=\"10\" value=\"$myDuration\"></td></tr>\n";
				echo "</tr>\n<tr>\n <td><p>Email: </p></td>\n";
				echo " <td><input type=\"text\" name=\"category\" size=\"25\" value=\"$myCategory\"></td>\n";
				echo "</tr>\n<tr>\n <td><p>Timestamp for latest download: </p></td>\n";
				echo " <td><p><strong>$myLatest</strong></p></td>\n";
				echo "<td colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"submit\"></td>\n";
				echo "</tr>\n</table>\n</form>\n";
				
			}
			else {
				$errNo = mysqli_errno($myConn);
				$error = mysqli_error($myConn);
				showError("$errNo: $error");
			}
			
		}
		else {
			showError("no file-id provided!");
		}
	}
	
	if($_GET["action"] == "edit_lecturer") {
		if($_GET["id"]) {
			$id = $_GET["id"];
			$myQuery = "SELECT * FROM dl_lecturers WHERE id = '$id'";
			if($myResult = mysqli_query($myConn, $myQuery)) {
				$myArray = mysqli_fetch_array($myResult);
				
				$myID = $myArray["id"];
				$myName = $myArray["name"];
				$myPhotoUrl = $myArray["photo_url"];
				$myDescription = $myArray["description"];

				echo "<p>:: You are editing <strong>$myTitle</strong>. ::</p>\n";
				echo "<form method=\"post\">\n";
				echo "<input type=\"hidden\" name=\"action\" value=\"update_lecturer\">\n";
				echo "<input type=\"hidden\" name=\"password\" value=\"$password\">\n";
				echo "<input type=\"hidden\" name=\"id\" value=\"$myID\">\n";
				echo "<table align=\"center\" border=\"0\" cellpadding=\"2\">\n";
				echo "<tr>\n <td><p>id: </p></td>\n";
				echo "<td><p><strong>$myID</strong></p></td>\n";
				echo "</tr>\n<tr>\n <td><p>Name: </p></td>\n";
				echo "<td><input type=\"text\" name=\"name\" size=\"25\" value=\"$myName\"></td>\n";
				echo "</tr>\n<tr>\n <td><p>Photo: </p></td>\n";
				echo "<td><input type=\"text\" name=\"photo_file\" size=\"25\" value=\"$myPhotoUrl\"></td></tr>\n";
				echo "</tr>\n<tr>\n <td><p>Description: </p></td>\n";
				echo "<td><textarea rows=\"5\" cols=\"50\" name=\"description\">$myDescription</textarea></td>\n";
				echo "<td colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"submit\"></td>\n";
				echo "</tr>\n</table>\n</form>\n";
				
			}
			else {
				$errNo = mysqli_errno($myConn);
				$error = mysqli_error($myConn);
				showError("$errNo: $error");
			}
			
		}
		else {
			showError("no file-id provided!");
		}
	}

	if($_GET["action"] == "delete") {
		if($_GET["id"]) {
			$id = $_GET["id"];
			$myQuery = "DELETE FROM dl_links WHERE id = '$id'";
			
			if(mysqli_query($myConn, $myQuery)) {
				echo "<p>:: Download-id <strong>".$id."</strong> was deleted! ::</p>";
			}
			else {
				$errNo = mysqli_errno($myConn);
				$error = mysqli_error($myConn);
				showError("$errNo: $error");
			}

		}
		else {
			showError("no file-id provided!");
		}
	}
	
	if($_GET["action"] == "delete_lecturer") {
		if($_GET["id"]) {
			$id = $_GET["id"];
			$myQuery = "DELETE FROM dl_lecturers WHERE id = '$id'";
			
			if(mysqli_query($myConn, $myQuery)) {
				echo "<p>:: Lecturer-id <strong>".$id."</strong> was deleted! ::</p>";
			}
			else {
				$errNo = mysqli_errno($myConn);
				$error = mysqli_error($myConn);
				showError("$errNo: $error");
			}

		}
		else {
			showError("no file-id provided!");
		}
	}
	
	if($_GET["action"] == "add") {
		if (isset($_POST["title"])) {
			if (strlen($_POST["title"]) < 5) {
				showError("You did not enter a title!");
			}
/*			if (strlen($_POST["link"]) < 5) {
				showError("You did not enter a link to the file!");
			}*/
			else {
				$now = date("Y-m-d H:i:s");
				$id = $_POST["id"];
				$title = $_POST["title"];
				$link = $_POST["link"];
				$lecturer = $_POST["lecturer"];

				if (isset($_FILES['video_file'])) {

					$fileName = $_FILES['video_file']['name'];
					
					$fileNameCmps = explode(".", $fileName);
					$fileExtension = strtolower(end($fileNameCmps));

					$newFileName = $fileName;

					$allowedfileExtensions = array('mp4');
					if (in_array($fileExtension, $allowedfileExtensions)) {
						file_put_contents('./video/' . $newFileName, file_get_contents($_FILES['video_file']['tmp_name']));
						$link = 'http://' . $_SERVER['HTTP_HOST'] . '/video/' . $newFileName;
					}
				}

				$id = rand ( 1111111 , 999999999 ) ;
				$category = $_POST["category"];
				$stringToHash = $link.':'.$id.':'.$title;
				$hash = md5($stringToHash);
				$duration = $_POST["duration"];
				
				$myQuery = "INSERT INTO dl_links ( id , link, title, category, lecturer, latest, hash, duration) VALUES ( '$id', '$link', '$title', '$category', '$lecturer', '$now', '$hash', '$duration')";
				if (mysqli_query($myConn, $myQuery))
					echo "<p><strong>:: link was added ::</strong></p>\n";
				else {
					$errNo = mysqli_errno($myConn);
					$error = mysqli_error($myConn);
					showError("$errNo: $error");
				}
			}


		}
		else {
			$myQuery = "SELECT id FROM dl_links";
			$myResult = mysqli_query($myConn, $myQuery);
			$totalFiles = mysqli_num_rows($myResult);
			$now = date("Y-m-d H:i:s");
			echo "<p>:: There is a total of <strong>$totalFiles</strong> files registered. ::</p>\n";
			echo "<form method=\"post\" enctype=\"multipart/form-data\">\n";
			echo "<input type=\"hidden\" name=\"action\" value=\"add\">\n";
			echo "<input type=\"hidden\" name=\"password\" value=\"$password\">\n";
			echo "<table align=\"center\" border=\"0\" cellpadding=\"2\">\n";
			echo "<tr><td><p><strong>Title of link:</strong></p></td>\n";
			echo "<td><input type=\"text\" name=\"title\" size=\"25\"></td></tr>\n";
			echo "<tr><td><p><strong>Link to file:</strong></p></td>\n";
			echo "<td><input type=\"text\" id=\"link\" name=\"link\" size=\"25\"></td></tr>\n";
			echo "<tr><td><p><strong>Choose from uploads:</strong></p></td>\n";
			echo "<td>";

			$video_files = array();
			$video_files = scandir('./video');

			echo '<select class="inputlink">';
			foreach ($video_files as $video) {
				if ($video == '.' || $video == '..') continue;

				echo '<option value="' . $video . '">' . $video . '</option>';
			}

			echo '</select>';

			echo "</td></tr>\n";			

			echo "<tr><td><p><strong>Select Lecturer:</strong></p></td>\n";
			echo "<td>";

			$myQuery = "SELECT id, name FROM dl_lecturers";
			$myResult = mysqli_query($myConn, $myQuery);
			
			echo '<select name="lecturer">';
			
			if ($myResult = mysqli_query($myConn, $myQuery)) {
				while($row = mysqli_fetch_array($myResult)) {
					echo '<option value="' . $row["id"] . '">' . $row["name"] . '</option>';
				}
			}

			echo '</select>';
			echo "</td></tr>\n";

			echo "<tr><td><p><strong>Or... Upload the video:</strong></p></td>\n";
			echo "<td><input type=\"file\" name=\"video_file\" size=\"25\"></td></tr>\n";
			echo "<tr><td><p><strong>Lifetime(min):</strong></p></td>\n";
			echo "<td><input type=\"text\" name=\"duration\" size=\"10\" value=\"1\"></td></tr>\n";
			echo "<tr><td><p><strong>Doctors</strong></p><p class='email_select' style='color: green;'></p></td>\n";
			//echo "<td><input type=\"text\" name=\"category\" size=\"25\"></td></tr>\n";
            echo "<td><select name=\"category\" class='select_doct' style='width: 100%;'>";
            echo "<option value='not'>Выбрать клиента</option>";
            $myQuery = "SELECT * FROM `users` WHERE `status` = 2";
            $myResult = mysqli_query($myConn, $myQuery);
            if ($myResult = mysqli_query($myConn, $myQuery)) {
                while($row = mysqli_fetch_assoc($myResult)) {
                    echo '<option value="' . $row["email"] . '">' . $row["name"] . $row["surname"] . $row["lastname"] . '</option>';
                }
            }

            echo "</select></td></tr>\n";
			echo "<tr><td colspan=\"2\" align=\"center\">";
			echo "<input type=\"reset\" value=\"start over\">&nbsp;&nbsp;<input type=\"submit\" value=\"submit\"></td></tr>\n";
			echo "</table>\n</form>\n";
		} // else
	}




	if($_GET["action"] == "add_lecturer") {
		if (isset($_POST["submit"])) {
			if (strlen($_POST["name"]) == 0) {
				showError("You did not enter a name!");
			}
			elseif (empty($_FILES["photo_file"])) {
				showError("You did not enter a photo!");
			}
			elseif (strlen($_POST["description"]) == 0) {
				showError("You did not enter a description!");
			}
			else {	
				$id = '';			
				$name = '';
				$photo_url = '';
				$description = '';

				$name = $_POST["name"];
				$description = $_POST["description"];

				$fileName = $_FILES['photo_file']['name'];
				
				$fileNameCmps = explode(".", $fileName);
				$fileExtension = strtolower(end($fileNameCmps));

				$newFileName = $fileName;

				$allowedfileExtensions = array('png', 'jpg');
				if (in_array($fileExtension, $allowedfileExtensions)) {
					file_put_contents('./images/lecturers/' . $newFileName, file_get_contents($_FILES['photo_file']['tmp_name']));
					$photo_url = 'http://' . $_SERVER['HTTP_HOST'] . '/images/lecturers/' . $newFileName;
				}
				
				$myQuery = "INSERT INTO dl_lecturers (id, name, photo_url, description) VALUES ('DEFAULT', '$name', '$photo_url', '$description')";
				if (mysqli_query($myConn, $myQuery))
					echo "<p><strong>:: lecturer was added ::</strong></p>\n";
				else {
					$errNo = mysqli_errno($myConn);
					$error = mysqli_error($myConn);
					showError("$errNo: $error");
				}
			}
		}
		else {
			$myQuery = "SELECT id FROM dl_lecturers";
			$myResult = mysqli_query($myConn, $myQuery);
			$totalFiles = mysqli_num_rows($myResult);
			$now = date("Y-m-d H:i:s");
			echo "<p>:: There is a total of <strong>$totalFiles</strong> lecturers registered. ::</p>\n";
			echo "<form method=\"post\" enctype=\"multipart/form-data\">\n";
			echo "<input type=\"hidden\" name=\"action\" value=\"add_lektor\">\n";
			echo "<input type=\"hidden\" name=\"password\" value=\"$password\">\n";
			echo "<table align=\"center\" border=\"0\" cellpadding=\"2\">\n";
			echo "<tr><td><p><strong>Name:</strong></p></td>\n";
			echo "<td><input type=\"text\" name=\"name\" size=\"25\"></td></tr>\n";
			echo "<tr><td><p><strong>Upload the photo:</strong></p></td>\n";
			echo "<td><input type=\"file\" name=\"photo_file\" size=\"25\"></td></tr>\n";
			echo "<tr><td><p><strong>Description:</strong></p></td>\n";
			echo "<td><textarea rows=\"5\" cols=\"50\" name=\"description\"></textarea></td></tr>\n";
			echo "<tr><td colspan=\"2\" align=\"center\">";
			echo "<input type=\"submit\" name=\"submit\" value=\"submit\"></td></tr>\n";
			echo "</table>\n</form>\n";
			
			//таблица
			$myQuery = "SELECT * FROM dl_lecturers";
			$myResult = mysqli_query($myConn, $myQuery);
			if (!$myResult) {
				printf("Error: %s\n", mysqli_error($myConn));
				exit();
			}

			echo "<br><br>\n";
			echo "<table border=\"1\" width=\"50%\">\n<tr>\n";
			echo "<td><b>id</b></td>\n";
			echo "<td><b>name</b></td>\n";
			echo "<td><b>photo_url</b></td>\n";
			echo "<td><b>description</b></td>\n";
			echo "<td><b>edit</b></td>\n";
			echo "<td><b>delete</b></td>\n";
			echo "</tr>\n";

			$i = 0;
			while($myArray = mysqli_fetch_array($myResult)) {
				$id = $myArray["id"];
				$name = $myArray["name"];
				$photo_url = $myArray["photo_url"];
				$description = $myArray["description"];

				echo "<tr><td><p><i>$id</i></p></td>\n";
				echo "<td><p>$name</p></td>\n";
				echo "<td><p>$photo_url</p></td>\n";
				echo "<td><p>$description</p></td>\n";
				echo "<td><p><a href=\"?action=edit_lecturer&password=$password&id=$id\">edit</a></p></td>\n";
				echo "<td><p><a href=\"?action=delete_lecturer&password=$password&id=$id\">delete!</a></p></td>\n";
				echo "</tr>\n";

				$i++;
			}
			echo "</table>\n";
		}
	}

	if($_GET["action"] == "show") {
		$myQuery = "SELECT id, title, category, lecturer, latest, duration, hash, timer
			FROM dl_links ORDER BY category, latest";
		$myResult = mysqli_query($myConn, $myQuery);
		if (!$myResult) {
			printf("Error: %s\n", mysqli_error($myConn));
			exit();
		}

		echo "<br><br>\n";
		echo "<table border=\"1\">\n<tr>\n";
		echo "<td><p>id</p></td>\n";
		echo "<td><p>Options</p></td>\n";
		echo "<td><p>Doctor</p></td>\n<td><p>title</p></td>\n<td><p>lecturer</p></td>\n<td><p>Lifetime</p></td>\n<td><p>Time left</p></td>\n";
		echo "<td><p><i>Data</i></p></td></tr>\n";
		$i = 0;
		while($myArray = mysqli_fetch_array($myResult)) {
			$id = $myArray["id"];
			$title = $myArray["title"];

			if (isset($myArray["lecturer"])) {
				$lecturer_id = $myArray["lecturer"];
				
				$get_name_lecturer = mysqli_query($myConn, "SELECT name FROM dl_lecturers WHERE id = $lecturer_id LIMIT 1");
				while($row = mysqli_fetch_array($get_name_lecturer)) {
					$lecturer = $row["name"];
				}
			}

			$category = $myArray["category"];
			$latest = $myArray["latest"];
			$duration = $myArray["duration"];
			$hash = $myArray["hash"];
			if ($myArray["timer"] == null){$timer = $duration * 60;}
			else {$timer = $myArray["timer"];}

			$last_time = strtotime($latest); 
			$last_time_d = $last_time + $timer;
			$last_secs = $last_time_d - time();

			$hours = floor($timer / 3600) . "h";
			$minutes = floor(($timer - $hours * 3600) / 60) . "m";
			$hours_s = floor($duration / 60) . "h";
			$minutes_s = $duration - $hours_s * 60 . "m";

			if(!empty($category)){
                $myQuery_fio = "SELECT name, surname, lastname FROM users WHERE email = '$category' LIMIT 1";
                $myResult_fio = mysqli_query($myConn, $myQuery_fio);
                while($fio = mysqli_fetch_assoc($myResult_fio)) {
                    $fio_tml = $fio["name"].'<br>'.$fio["surname"].'<br>'.$fio["lastname"];
                }
            }else{
                $fio_tml = '';
            }



			echo "<tr>\n<input id=\"id$i\" style='display: none;' value=\"http://{$_SERVER['HTTP_HOST']}/downloader.php?id=$hash\" />";
			echo "<td><p><a href=\"/downloader.php?id=$hash\"><strong>$id</strong></p>\n</td>";
			echo "<td><button onclick=\"copyToClipboard('#id$i')\">copy</button></td>\n</td>";
			echo "<td><p style='text-align: center;'><i>$fio_tml</i></p></td>\n";
			echo "<td><p><a href=\"?action=edit&password=$password&id=$id\" title=\"edit\">$title</a></p></td>\n";
			echo "<td><p>$lecturer</p></td>\n";
			echo "<td><p><i>$hours_s$minutes_s</i></p></td>\n";
			echo "<td><p>$hours$minutes</p></td>\n";
			echo "<td><p>$latest</p></td>\n";
			echo "<td><p><a href=\"?action=delete&password=$password&id=$id\">delete!</a></p></td>\n";
            echo "<td><p><a href=\"?action=send_mail&password=$password&email=$category&id=$id\">send</a></p></td>\n";
			echo "</tr>\n";

			$i++;
		}
		echo "</table>\n";
	}
	
	if($_GET["action"] == "statistics") {
		$myQuery = "SELECT * FROM dl_data";
		$myResult = mysqli_query($myConn, $myQuery);
		if (!$myResult) {
			printf("Error: %s\n", mysqli_error($myConn));
			exit();
		}
		echo "<br><br>\n";
		echo "<table border=\"1\" width=\"50%\">\n<tr>\n";
		echo "<td><b>id</b></td>\n";
		echo "<td><b>ip</b></td>\n";
		echo "<td><b>agree</b></td>\n";
		echo "<td><b>useragent</b></td>\n";
		echo "<td><b>timein</b></td>\n";
		echo "<td><b>timeout</b></td>\n";
		echo "</tr>\n";
		
		$i = 0;
		while($myArray = mysqli_fetch_array($myResult)) {
			$id = $myArray["id"];
			$ip = $myArray["ip"];
			$useragent = $myArray["useragent"];
			$agree = $myArray["checkbox"];
			$timein = $myArray["timein"];
			$timeout = $myArray["timeout"];
			echo "<tr><td><p><i>$id</i></p></td>\n";
			echo "<td><p>$ip</p></td>\n";
			echo "<td><p>$agree</p></td>\n";
			echo "<td><p>$useragent</p></td>\n";
			echo "<td><p>$timein</p></td>\n";
			echo "<td><p>$timeout</p></td>\n";
			
			echo "</tr>\n";
			
			$i++;
		}
		echo "</table>\n";
	}
	
	if($_GET["action"] == "mails") {
		$myQuery = "SELECT * FROM mails";
		$myResult = mysqli_query($myConn, $myQuery);
		if (!$myResult) {
			printf("Error: %s\n", mysqli_error($myConn));
			exit();
		}
		echo "<br><br>\n";
		echo "<table border=\"1\" width=\"50%\">\n<tr>\n";
		echo "<td><b>id</b></td>\n";
		echo "<td><b>sent_date</b></td>\n";
		echo "<td><b>mail_from</b></td>\n";
		echo "<td><b>material_mark</b></td>\n";
		echo "<td><b>service_mark</b></td>\n";
		echo "<td><b>text</b></td>\n";
		echo "</tr>\n";
		
		$i = 0;
		while($myArray = mysqli_fetch_array($myResult)) {
			$id = $myArray["id"];
			$sent_date = $myArray["sent_date"];
			$mail_from = $myArray["mail_from"];
			$material_mark = $myArray["material_mark"];
			$service_mark = $myArray["service_mark"];
			$text = $myArray["text"];
			echo "<tr><td><p><i>$id</i></p></td>\n";
			echo "<td><p>$sent_date</p></td>\n";
			echo "<td><p>$mail_from</p></td>\n";
			echo "<td><p>$material_mark</p></td>\n";
			echo "<td><p>$service_mark</p></td>\n";
			echo "<td><p>$text</p></td>\n";
			
			echo "</tr>\n";
			
			$i++;
		}
		echo "</table>\n";
	}
	
	if($_GET["action"] == "lect_mails") {
		$myQuery = "SELECT * FROM lect_mails";
		$myResult = mysqli_query($myConn, $myQuery);
		if (!$myResult) {
			printf("Error: %s\n", mysqli_error($myConn));
			exit();
		}
		echo "<br><br>\n";
		echo "<table border=\"1\" width=\"50%\">\n<tr>\n";
		echo "<td><b>id</b></td>\n";
		echo "<td><b>sent_date</b></td>\n";
		echo "<td><b>mail_from</b></td>\n";
		echo "<td><b>author</b></td>\n";
		echo "<td><b>text</b></td>\n";
		echo "</tr>\n";
		
		$i = 0;
		while($myArray = mysqli_fetch_array($myResult)) {
			$id = $myArray["id"];
			$sent_date = $myArray["sent_date"];
			$mail_from = $myArray["mail_from"];
			$author = $myArray["author"];
			$text = $myArray["text"];
			echo "<tr><td><p><i>$id</i></p></td>\n";
			echo "<td><p>$sent_date</p></td>\n";
			echo "<td><p>$mail_from</p></td>\n";
			echo "<td><p>$author</p></td>\n";
			echo "<td><p>$text</p></td>\n";
			
			echo "</tr>\n";
			
			$i++;
		}
		echo "</table>\n";
	}
	
	
	echo "<hr width=\"100%\">\n";

}
else {
echo "<div align=\"center\">\n";
echo "<p class=\"infotext\">:: login ::</p>\n";
echo "<hr width=\"100%\">\n";
echo "<form action=\"$PHP_SELF\" method=\"post\">\n";
echo "<p><strong>password: </strong></p>\n";
echo "<input type=\"password\" name=\"password\"><br><br>\n";
echo "<input type=\"submit\" value=\"login\">&nbsp;&nbsp;<input type=\"reset\" value=\"reset\">\n";
echo "</form>\n</div>";
}
echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>';
?>


<script>
    $('.select_doct').on('change',function () {
        if($(this).val() != 'not'){
            $('.email_select').html('Email: '+$(this).val());
        }else{
            $('.email_select').html('');
        }
    });
    $('.set_status').on('click',function () {
        $('.set_status_form').submit();
    });
	function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).val()).select();
  document.execCommand("copy");
  $temp.remove();
}

$('.inputlink').click(function() {
	$('#link').val("http://<?php echo $_SERVER['HTTP_HOST'];?>/video/" + $(this).val());
});

window.jQuery(function($) {
    $('a[href^="?action=delete"]').click(function(e) {
        var $link = $(e.currentTarget);
        var $tr   = $link.closest('tr');
        var href  = $link.attr('href');
        
        $link.text('deleting...');

        $.ajax({
            url: href,
            success: function() {
                $tr.animate({opacity: 0}, {complete: function() {
                    $tr.remove();
                }});
            }
        });
        return false;
    });
});

</script>
    <div class="mask">
        <form enctype="multipart/form-data" action="" method="post" class="modal_reg">
            <input type="hidden" name="MAX_FILE_SIZE" value="5242880" />
            <input type="text" hidden name="password" value="<?=$_GET['password']?>">
            <div class="close_modal">X</div>
            <h3>Имя: </h3>
            <input required name="name" type="text" placeholder="Имя">
            <h3>Фамилия: </h3>
            <input required name="surname" type="text" placeholder="Фамилия">
            <h3>Отчество: </h3>
            <input required name="lastname" type="text" placeholder="Отчество">
            <h3>Дата рождения: </h3>
            <input required name="bithday" type="text" placeholder="Дата рождения">
            <h3>Место рождения: </h3>
            <input required name="country" type="text" placeholder="Место рождения">
            <h3>Контактный телефон: </h3>
            <input required name="phone_number" type="text" placeholder="Контактный телефон">
            <h3>E-mail: </h3>
            <input required name="email" type="text" placeholder="E-mail">
            <h3>Образование: </h3>
            <input required name="learn" type="text" placeholder="Образование">
            <h3>Профессия: </h3>
            <input required name="job" type="text" placeholder="Профессия">
            <button class="send_reg">Регистрация</button>
        </form>
    </div>
    <script>
        $('.btn_reg').on('click',function () {
            $('.mask').fadeIn();
        });
        $('.close_modal').on('click',function () {
            console.log('dasdasd');
            $('.mask').fadeOut();
        });
    </script>
<?
if(isset($_GET['mes'])){
    $class = 'error_mes';
    switch ($_GET['mes']){
        case 'ok':
            $class = 'ok_mes';
            $message_nap = 'Спасибо за регистрацию, ожидайте данные авторизации на почту.';
            break;
        case 'insert';
            $message_nap = 'Введены неправильные данные.';
            break;
        case 'dowloand':
            $message_nap = 'Ошибка загрузки изображений.';
            break;
        case 'type':
            $message_nap = 'Неправильный тип файлов.';
            break;
        case 'size':
            $message_nap = 'Неправильный размер файлов > 5мб.';
            break;
    }?>
    <div class="nap_mess <?=$class?>">
        <p><?=$message_nap?></p>
    </div>
    <script>
        $(document).ready(function () {
            $('.nap_mess').fadeIn(500,'linear',function () {
                setTimeout(function () {
                    $('.nap_mess').fadeOut(500,'linear');
                },10000);
            });
        });
    </script>
<?}
?>
<?php
//echo "<script src=\"./js/clipboard.js\"></script>";
echo "</body>\n</html>\n";

?>