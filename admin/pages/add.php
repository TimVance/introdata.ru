<?php
if (isset($_POST["add_links"])) {

    if (strlen($_POST["title"]) < 5) {
        showError("You did not enter a title!");
    }
    /*			if (strlen($_POST["links"]) < 5) {
                    showError("You did not enter a links to the file!");
                }*/
    else {
        $now = date("Y-m-d H:i:s");
        $id = $_POST["id"];
        $title = $_POST["title"];
        $links = $_POST["links"];
        $lecturer = $_GET["lecturer"];
        $id_video = $_POST["id_video"];

        if (isset($_FILES['video_file'])) {

            $fileName = $_FILES['video_file']['name'];

            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            $newFileName = $fileName;

            $allowedfileExtensions = array('mp4');
            if (in_array($fileExtension, $allowedfileExtensions)) {
                file_put_contents('./../video/' . $newFileName, file_get_contents($_FILES['video_file']['tmp_name']));
                $links = 'http://' . $_SERVER['HTTP_HOST'] . '/video/' . $newFileName;
            }
        }

        $id = rand ( 1111111 , 999999999 ) ;
        $category = $_POST["category"];
        $stringToHash = $links.':'.$id.':'.$title;
        $hash = md5($stringToHash);

        if($_POST['life_id'] != ''){
            $life_id = $_POST['life_id_time'];
            $life_id_time = date("Y-m-d H:i:s", strtotime("+". $life_id." days", strtotime($now)));
        }else{
            $life_id = 0;
            $life_id_time = '';
        }
        if($_POST['life_link'] != ''){
            $life_link = $_POST["duration"];
            $life_link_time = date("Y-m-d H:i:s", strtotime("+". $life_link." minute", strtotime($now)));
            $duration = $_POST["duration"];
        }else{
            $life_link = 0;
            $life_link_time = '';
            $duration = '';
        }
        if($_POST['life_video'] != ''){
            $life_video = $_POST["life_video_time"];
            $life_video_time = date("Y-m-d H:i:s", strtotime("+". $life_video." minute", strtotime($now)));
            $link_last = 'http://link.introdata.ru/video/'.$_POST["next_video"];
        }else{
            $life_video = 0;
            $life_video_time = '';
            $link_last = '';
        }
        if($_POST['all_device'] != ''){
            $all_device = 1;
        }else{
            $all_device = 0;
        }
        if($_POST['life_link'] == '' and $_POST['life_video'] == ''){
            $active_session = 0;
        }else{
            $active_session = 1;
        }
        $test = mysqli_query($GLOBALS['link'],"INSERT INTO dl_links ( `id` , `link`, `title`, `category`, `lecturer`, `latest`, `hash`, `duration`, `created`, `life_id`, `life_id_time`, `life_link`, `life_link_time`, `life_video`, `life_video_time`, `link_last`, `all_device`, `active_session`, `id_video`) VALUES ( '$id', '$links', '$title', '$category', '$lecturer', '$now', '$hash', '$duration', '$now', '$life_id', '$life_id_time', '$life_link', '$life_link_time', '$life_video', '$life_video_time', '$link_last', '$all_device', '$active_session', '$id_video')");
        if ($test) {
            echo "<p><strong>:: link was added ::</strong></p>\n";
        }else {
            $errNo = mysqli_errno($myConn);
            $error = mysqli_error($myConn);
            showError("$errNo: $error");
        }
    }


}

    $myResult = mysqli_query($GLOBALS['link'],"SELECT id FROM dl_links");
    $totalFiles = mysqli_num_rows($myResult);

    $now = date("Y-m-d H:i:s");
    echo "<p>:: There is a total of <strong>$totalFiles</strong> files registered. ::</p>\n";
    echo "<form method=\"post\" enctype=\"multipart/form-data\">\n";
    echo "<input type=\"hidden\" name=\"action\" value=\"add\">\n";
    echo "<table align=\"center\" border=\"0\" cellpadding=\"2\">\n";


    echo "<tr><td><p><strong>Select Lecturer:</strong></p></td>\n";
    echo "<td>";
    $myResult = mysqli_query($GLOBALS['link'], "SELECT id, name FROM dl_lecturers");
    echo '<select onchange="document.location=this.options[this.selectedIndex].value" name="lecturer">';
    if ($myResult) {
        echo '<option value="index.php?action=add">Select</option>';
        while($row = mysqli_fetch_array($myResult)) {
            echo '<option '.($_GET["lecturer"] == $row["id"] ? 'selected ' : '').'value="index.php?action=add&lecturer=' . $row["id"] . '">' . $row["name"] . '</option>';
        }
    }
    echo '</select>';
    echo "</td></tr>\n";

    if (!empty($_GET["lecturer"])) {
        echo "<tr><td><p><strong>Select Video:</strong></p></td>\n";
        echo "<td>";
        $myResult = mysqli_query($GLOBALS['link'], "SELECT id, name FROM dl_video WHERE lecturer = ".$_GET["lecturer"]);
        echo '<select name="id_video">';
        if ($myResult) {
            while ($row = mysqli_fetch_array($myResult)) {
                echo '<option ' . ($_GET["lecturer"] == $row["id"] ? 'selected ' : '') . 'value="' . $row["id"] . '">' . $row["name"] . '</option>';
            }
        }
        echo '</select>';
        echo "</td></tr>\n";
    }


    echo "<tr><td><p><strong>Title of link:</strong></p></td>\n";
    echo "<td><input type=\"text\" name=\"title\" size=\"25\"></td></tr>\n";
    echo "<tr><td><p><strong>Doctors</strong></p><p class='email_select' style='color: green;'></p></td>\n";
    //echo "<td><input type=\"text\" name=\"category\" size=\"25\"></td></tr>\n";
    echo "<td><select name=\"category\" class='select_doct' style='width: 100%;'>";
    echo "<option value='not'>Выбрать клиента</option>";
    $myResult = mysqli_query($GLOBALS['link'], "SELECT * FROM `users` WHERE `status` = 2");
    if ($myResult) {
        while($row = mysqli_fetch_assoc($myResult)) {
            echo '<option value="' . $row["email"] . '">' . $row["name"] . $row["surname"] . $row["lastname"] . '</option>';
        }
    }

    echo "</select></td></tr>\n";
    echo "<tr><td><p><strong>Общее время жизни id:</strong></p></td>\n";
    echo "<td><input type=\"checkbox\" name=\"life_id\" id='box1' value=\"1\" onclick=\"showMe(this)\"></td></tr>\n";
    echo "<tr id=\"div_box1\" style=\"display:none;\"><td><p><strong>Жизнь id в сутках:</strong></p></td>\n";
echo "<td><input type=\"text\" name=\"life_id_time\" size=\"10\" value=\"0\"></td></tr>\n";

echo "<tr><td><p><strong>Общее время жизни ссылки:</strong></p></td>\n";
echo "<td><input type=\"checkbox\" name=\"life_link\" id='box2' value=\"1\" onclick=\"showMe(this)\"></td></tr>\n";
echo "<tr id=\"div_box2\" style=\"display:none;\"><td><p><strong>Жизнь ссылки в минутах:</strong></p></td>\n";
echo "<td><input type=\"text\" name=\"duration\" size=\"10\" value=\"0\"></td></tr>\n";

echo "<tr><td><p><strong>Общее время жизни видео:</strong></p></td>\n";
echo "<td><input type=\"checkbox\" name=\"life_video\" id='box3' value=\"1\" onclick=\"showMe(this)\"></td></tr>\n";
echo "<tr id=\"div_box3\" style=\"display:none;\"><td><p><strong>Жизнь видео в минутах:</strong></p></td>\n";
echo "<td><input type=\"text\" name=\"life_video_time\" size=\"10\" value=\"0\"></td>\n";
echo "<td><p><strong>Подменное видео:</strong></p></td>\n";
echo "<td>";

$video_files = array();
$video_files = scandir('./../video');

echo '<select class="input" name="next_video">';
foreach ($video_files as $video) {
    if ($video == '.' || $video == '..') continue;

    echo '<option value="' . $video . '">' . $video . '</option>';
}

echo '</select>';

echo "</td></tr>\n";

echo "<tr><td><p><strong>Вход с разных устройств:</strong></p></td>\n";
echo "<td><input type=\"checkbox\" name=\"all_device\" value=\"1\"></td></tr>\n";
    echo "<tr><td colspan=\"2\" align=\"center\">";
    echo "<input type=\"reset\" value=\"start over\">&nbsp;&nbsp;<input type=\"submit\" name='add_links' value=\"submit\"></td></tr>\n";
    echo "</table>\n</form>\n";



