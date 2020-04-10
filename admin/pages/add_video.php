<?php
if($_GET['tern'] == '') {
    if (isset($_POST["submit"])) {
        if (strlen($_POST["url"]) == 0) {
            showError("You did not enter a link!");
        } else {
            $id = '';
            $lecturer = '';
            $description = '';
            $url = '';
            $name = '';

            $lecturer = $_POST["lecturer"];
            $description = $_POST["description"];
            $url = $_POST["url"];
            $name = $_POST["name"];

            if (mysqli_query($GLOBALS['link'],
                "INSERT INTO dl_video (id, url, description, lecturer, name)
                        VALUES ('DEFAULT', '$url', '$description', '$lecturer', '$name')"))
                echo "<p><strong>:: Description was added ::</strong></p>\n";
            else {
                $errNo = mysqli_errno($myConn);
                $error = mysqli_error($myConn);
                showError("$errNo: $error");
            }
        }
    } else {
        $myResult = mysqli_query($GLOBALS['link'], "SELECT id FROM dl_video");
        $totalFiles = mysqli_num_rows($myResult);
        $now = date("Y-m-d H:i:s");
        echo "<p>:: There is a total of <strong>$totalFiles</strong> video registered. ::</p>\n";
        echo "<form method=\"post\" enctype=\"multipart/form-data\">\n";
        echo "<input type=\"hidden\" name=\"action\" value=\"add_description\">\n";
        echo "<table align=\"center\" border=\"0\" cellpadding=\"2\">\n";
        echo "<tr><td><p><strong>Lecture:</strong></p></td>\n";

        $myResult = mysqli_query($GLOBALS['link'], "SELECT * FROM dl_lecturers");
        if (!$myResult) {
            printf("Error: %s\n", mysqli_error($myConn));
            exit();
        }
        echo '<td><select name="lecturer" class="inputlink">';
        while ($myArray = mysqli_fetch_array($myResult)) {
            echo '<option value="'.$myArray["id"].'">'.$myArray["name"].'</option>';
        }
        echo '</select></td></tr>';

        echo "<tr><td><p><strong>Video Name:</strong></p></td>\n";
        echo "<td><input type=\"text\" name=\"name\" required></td></tr>\n";
        echo "<tr><td><p><strong>Video link:</strong></p></td>\n";
        echo "<td><textarea rows=\"5\" cols=\"50\" name=\"url\"></textarea></td></tr>\n";
        echo "<tr><td><p><strong>Video description:</strong></p></td>\n";
        echo "<td><textarea rows=\"5\" cols=\"50\" name=\"description\"></textarea></td></tr>\n";
        echo "<tr><td colspan=\"2\" align=\"center\">";
        echo "<input type=\"submit\" name=\"submit\" value=\"submit\"></td></tr>\n";
        echo "</table>\n</form>\n";

        //таблица
        $myResult = mysqli_query(
            $GLOBALS['link'],
            "SELECT v.id AS id, v.url AS url, v.description AS description,
                    v.name AS vname, l.name AS name
                    FROM dl_video AS v
                    RIGHT JOIN dl_lecturers AS l ON v.lecturer=l.id"
        );
        if (!$myResult) {
            printf("Error: %s\n", mysqli_error($myConn));
            exit();
        }

        echo "<br><br>\n";
        echo "<table border=\"1\" width=\"50%\">\n<tr>\n";
        echo "<td><b>id</b></td>\n";
        echo "<td><b>name</b></td>\n";
        echo "<td><b>video_name</b></td>\n";
        echo "<td><b>link</b></td>\n";
        echo "<td><b>description</b></td>\n";
        echo "<td><b>edit</b></td>\n";
        echo "<td><b>delete</b></td>\n";
        echo "</tr>\n";

        $i = 0;
        while ($myArray = mysqli_fetch_array($myResult)) {
            $id = $myArray["id"];
            $url = $myArray["url"];
            $description = $myArray["description"];
            $name = $myArray["name"];
            $video_name = $myArray["vname"];

            echo "<tr><td><p><i>$id</i></p></td>\n";
            echo "<td><p>$name</p></td>\n";
            echo "<td><p>$video_name</p></td>\n";
            echo "<td><p>$url</p></td>\n";
            echo "<td><p>$description</p></td>\n";
            echo "<td><p><a href=\"?action=add_video&tern=edit&id=$id\">edit</a></p></td>\n";
            echo "<td><p><a href=\"?action=add_video&tern=del&id=$id\">delete!</a></p></td>\n";
            echo "</tr>\n";

            $i++;
        }
        echo "</table>\n";
    }
}elseif ($_GET['tern'] == 'del'){
    if($_GET["id"]) {
        $id = $_GET["id"];
        if(mysqli_query($GLOBALS['link'], "DELETE FROM dl_video WHERE id = '$id'")) {
            echo "<p>:: Description-id <strong>".$id."</strong> was deleted! ::</p>";
            echo '<script>setTimeout(\'location.replace("/admin/index.php?action=add_video")\',3000);</script>';
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
elseif ($_GET['tern'] == 'edit'){
    if($_GET["id"]) {
        $id = $_GET["id"];
        if($myResult = mysqli_query($GLOBALS['link'], "SELECT v.id AS id, v.url AS url, v.description AS description,
                    l.id AS lecturer, v.name AS vname
                    FROM dl_video AS v
                    RIGHT JOIN dl_lecturers AS l ON v.lecturer=l.id
                    WHERE v.id=$id
                    ")) {
            $myArray = mysqli_fetch_array($myResult);

            $myID = $myArray["id"];
            $myLecturer = $myArray["lecturer"];
            $myDescription = $myArray["description"];
            $myUrl = $myArray["url"];
            $myLecturer = $myArray["lecturer"];
            $myVname = $myArray["vname"];

            echo "<p>:: You are editing <strong>$myID</strong>. ::</p>\n";
            echo "<form method=\"post\">\n";
            echo "<input type=\"hidden\" name=\"action\" value=\"update_description\">\n";
            echo "<input type=\"hidden\" name=\"id\" value=\"$myID\">\n";
            echo "<table align=\"center\" border=\"0\" cellpadding=\"2\">\n";
            echo "<tr>\n <td><p>id: </p></td>\n";
            echo "<td><p><strong>$myID</strong></p></td>\n";


            $myResult = mysqli_query($GLOBALS['link'], "SELECT * FROM dl_lecturers");
            if (!$myResult) {
                printf("Error: %s\n", mysqli_error($myConn));
                exit();
            }
            echo '</tr><tr><td><p>Lecturer</p></td><td><select name="lecturer" class="inputlink">';
            while ($myArray = mysqli_fetch_array($myResult)) {
                echo '<option '.($myArray["id"] == $myLecturer ? "selected " : "").'value="'.$myArray["id"].'">'.$myArray["name"].'</option>';
            }
            echo '</select></td></tr>';

            echo "</tr>\n<tr>\n <td><p>Video Name: </p></td>\n";
            echo "<td><textarea rows=\"5\" cols=\"50\" name=\"name\">$myVname</textarea></td>\n";
            echo "</tr>\n<tr>\n <td><p>Url: </p></td>\n";
            echo "<td><textarea rows=\"5\" cols=\"50\" name=\"url\">$myUrl</textarea></td>\n";
            echo "</tr>\n<tr>\n <td><p>Description: </p></td>\n";
            echo "<td><textarea rows=\"5\" cols=\"50\" name=\"description\">$myDescription</textarea></td>\n";
            echo "<td colspan=\"2\" align=\"center\"><input type=\"submit\" name='update_description' value=\"submit\"></td>\n";
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

    if(isset($_POST['update_description'])) {
        $id = 0;
        $name = '';
        $description = '';
        $lecturer = '';
        $url = '';

        $id = $_POST["id"];
        $description = $_POST["description"];
        $lecturer = $_POST["lecturer"];
        $name = $_POST["name"];
        $url = $_POST["url"];

        if (mysqli_query($GLOBALS['link'],
            "UPDATE dl_video SET url = '$url', description = '$description', lecturer = '$lecturer', name = '$name' WHERE id = '$id'")) {
            echo "<p>:: Description-id: <strong>$id</strong> has been updated! ::</p>";
            echo "<script>setTimeout('location.replace(\"/admin/index.php?action=add_video\")',2000);</script>";
        }
        else {
            $errNo = mysqli_errno($myConn);
            $error = mysqli_error($myConn);
            showError("$errNo: $error");
        }
    }
}