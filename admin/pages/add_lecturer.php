<?php
if($_GET['tern'] == '') {
    if (isset($_POST["submit"])) {
        if (strlen($_POST["name"]) == 0) {
            showError("You did not enter a name!");
        } elseif (empty($_FILES["photo_file"])) {
            showError("You did not enter a photo!");
        } elseif (strlen($_POST["description"]) == 0) {
            showError("You did not enter a description!");
        } else {
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
                file_put_contents('./../images/lecturers/' . $newFileName, file_get_contents($_FILES['photo_file']['tmp_name']));
                $photo_url = 'http://' . $_SERVER['HTTP_HOST'] . '/images/lecturers/' . $newFileName;
            }

            if (mysqli_query($GLOBALS['link'], "INSERT INTO dl_lecturers (id, name, photo_url, description) VALUES ('DEFAULT', '$name', '$photo_url', '$description')"))
                echo "<p><strong>:: lecturer was added ::</strong></p>\n";
            else {
                $errNo = mysqli_errno($myConn);
                $error = mysqli_error($myConn);
                showError("$errNo: $error");
            }
        }
    } else {
        $myResult = mysqli_query($GLOBALS['link'], "SELECT id FROM dl_lecturers");
        $totalFiles = mysqli_num_rows($myResult);
        $now = date("Y-m-d H:i:s");
        echo "<p>:: There is a total of <strong>$totalFiles</strong> lecturers registered. ::</p>\n";
        echo "<form method=\"post\" enctype=\"multipart/form-data\">\n";
        echo "<input type=\"hidden\" name=\"action\" value=\"add_lektor\">\n";
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
        $myResult = mysqli_query($GLOBALS['link'], "SELECT * FROM dl_lecturers");
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
        while ($myArray = mysqli_fetch_array($myResult)) {
            $id = $myArray["id"];
            $name = $myArray["name"];
            $photo_url = $myArray["photo_url"];
            $description = $myArray["description"];

            echo "<tr><td><p><i>$id</i></p></td>\n";
            echo "<td><p>$name</p></td>\n";
            echo "<td><p>$photo_url</p></td>\n";
            echo "<td><p>$description</p></td>\n";
            echo "<td><p><a href=\"?action=add_lecturer&tern=edit&id=$id\">edit</a></p></td>\n";
            echo "<td><p><a href=\"?action=add_lecturer&tern=del&id=$id\">delete!</a></p></td>\n";
            echo "</tr>\n";

            $i++;
        }
        echo "</table>\n";
    }
}elseif ($_GET['tern'] == 'del'){
    if($_GET["id"]) {
        $id = $_GET["id"];
        if(mysqli_query($GLOBALS['link'], "DELETE FROM dl_lecturers WHERE id = '$id'")) {
            echo "<p>:: Lecturer-id <strong>".$id."</strong> was deleted! ::</p>";
            echo '<script>setTimeout(\'location.replace("/admin/index.php?action=add_lecturer")\',3000);</script>';
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
        if($myResult = mysqli_query($GLOBALS['link'], "SELECT * FROM dl_lecturers WHERE id = '$id'")) {
            $myArray = mysqli_fetch_array($myResult);

            $myID = $myArray["id"];
            $myName = $myArray["name"];
            $myPhotoUrl = $myArray["photo_url"];
            $myDescription = $myArray["description"];

            echo "<p>:: You are editing <strong>$myName</strong>. ::</p>\n";
            echo "<form method=\"post\">\n";
            echo "<input type=\"hidden\" name=\"action\" value=\"update_lecturer\">\n";
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
            echo "<td colspan=\"2\" align=\"center\"><input type=\"submit\" name='update_lecturer' value=\"submit\"></td>\n";
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

    if(isset($_POST['update_lecturer'])) {
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

            if (mysqli_query($GLOBALS['link'], "UPDATE dl_lecturers SET name = '$name', photo_url = '$photo_url', description = '$description' WHERE id = '$id'")) {
                echo "<p>:: Lecturer-id: <strong>$id</strong> has been updated! ::</p>";
                echo "<script>setTimeout('location.replace(\"/admin/index.php?action=add_lecturer\")',2000);</script>";
            }
            else {
                $errNo = mysqli_errno($myConn);
                $error = mysqli_error($myConn);
                showError("$errNo: $error");
            }
        }
    }
}