<?php
$myQuery = "SELECT id, title, category, lecturer, latest, duration, hash, timer
			FROM dl_links ORDER BY category, latest";
$myResult = mysqli_query($GLOBALS['link'], $myQuery);
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

        $get_name_lecturer = mysqli_query($GLOBALS['link'], "SELECT name FROM dl_lecturers WHERE id = $lecturer_id LIMIT 1");
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
        $myResult_fio = mysqli_query($GLOBALS['link'], $myQuery_fio);
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
    echo "<td><p><a href=\"?action=edit_show&id=$id\" title=\"edit\">$title</a></p></td>\n";
    echo "<td><p>$lecturer</p></td>\n";
    echo "<td><p><i>$hours_s$minutes_s</i></p></td>\n";
    echo "<td><p>$hours$minutes</p></td>\n";
    echo "<td><p>$latest</p></td>\n";
    echo "<td><p><a href=\"?action=del_show&id=$id\">delete!</a></p></td>\n";
    echo "<td><p><a href=\"?action=send_mail&email=$category&id=$id\">send</a></p></td>\n";
    echo "</tr>\n";

    $i++;
}
echo "</table>\n";