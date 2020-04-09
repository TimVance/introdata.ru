<?php
if($_GET['tern'] == '') {
    if (isset($_POST["submit"])) {
        if (strlen($_POST["name"]) == 0) {
            showError("You did not enter a name!");
        } elseif (strlen($_POST["description"]) == 0) {
            showError("You did not enter a description!");
        } else {
            $id = '';
            $name = '';
            $description = '';

            $name = $_POST["name"];
            $description = $_POST["description"];

            if (mysqli_query($GLOBALS['link'], "INSERT INTO dl_descriptions (id, name, description) VALUES ('DEFAULT', '$name', '$description')"))
                echo "<p><strong>:: Description was added ::</strong></p>\n";
            else {
                $errNo = mysqli_errno($myConn);
                $error = mysqli_error($myConn);
                showError("$errNo: $error");
            }
        }
    } else {
        $myResult = mysqli_query($GLOBALS['link'], "SELECT id FROM dl_descriptions");
        $totalFiles = mysqli_num_rows($myResult);
        $now = date("Y-m-d H:i:s");
        echo "<p>:: There is a total of <strong>$totalFiles</strong> Descriptions registered. ::</p>\n";
        echo "<form method=\"post\" enctype=\"multipart/form-data\">\n";
        echo "<input type=\"hidden\" name=\"action\" value=\"add_description\">\n";
        echo "<table align=\"center\" border=\"0\" cellpadding=\"2\">\n";
        echo "<tr><td><p><strong>Name:</strong></p></td>\n";
        $video_files = array();
        $video_files = scandir('./../video');

        echo '<select name="name" class="inputlink">';
        foreach ($video_files as $video) {
            if ($video == '.' || $video == '..') continue;

            echo '<option value="' . $video . '">' . $video . '</option>';
        }

        echo '</select>';
        echo "<tr><td><p><strong>Description:</strong></p></td>\n";
        echo "<td><textarea rows=\"5\" cols=\"50\" name=\"description\"></textarea></td></tr>\n";
        echo "<tr><td colspan=\"2\" align=\"center\">";
        echo "<input type=\"submit\" name=\"submit\" value=\"submit\"></td></tr>\n";
        echo "</table>\n</form>\n";

        //таблица
        $myResult = mysqli_query($GLOBALS['link'], "SELECT * FROM dl_descriptions");
        if (!$myResult) {
            printf("Error: %s\n", mysqli_error($myConn));
            exit();
        }

        echo "<br><br>\n";
        echo "<table border=\"1\" width=\"50%\">\n<tr>\n";
        echo "<td><b>id</b></td>\n";
        echo "<td><b>name</b></td>\n";
        echo "<td><b>description</b></td>\n";
        echo "<td><b>edit</b></td>\n";
        echo "<td><b>delete</b></td>\n";
        echo "</tr>\n";

        $i = 0;
        while ($myArray = mysqli_fetch_array($myResult)) {
            $id = $myArray["id"];
            $name = $myArray["name"];
            $description = $myArray["description"];

            echo "<tr><td><p><i>$id</i></p></td>\n";
            echo "<td><p>$name</p></td>\n";
            echo "<td><p>$description</p></td>\n";
            echo "<td><p><a href=\"?action=add_descriptions&tern=edit&id=$id\">edit</a></p></td>\n";
            echo "<td><p><a href=\"?action=add_descriptions&tern=del&id=$id\">delete!</a></p></td>\n";
            echo "</tr>\n";

            $i++;
        }
        echo "</table>\n";
    }
}elseif ($_GET['tern'] == 'del'){
    if($_GET["id"]) {
        $id = $_GET["id"];
        if(mysqli_query($GLOBALS['link'], "DELETE FROM dl_descriptions WHERE id = '$id'")) {
            echo "<p>:: Description-id <strong>".$id."</strong> was deleted! ::</p>";
            echo '<script>setTimeout(\'location.replace("/admin/index.php?action=add_descriptions")\',3000);</script>';
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
        if($myResult = mysqli_query($GLOBALS['link'], "SELECT * FROM dl_descriptions WHERE id = '$id'")) {
            $myArray = mysqli_fetch_array($myResult);

            $myID = $myArray["id"];
            $myName = $myArray["name"];
            $myDescription = $myArray["description"];

            echo "<p>:: You are editing <strong>$myName</strong>. ::</p>\n";
            echo "<form method=\"post\">\n";
            echo "<input type=\"hidden\" name=\"action\" value=\"update_description\">\n";
            echo "<input type=\"hidden\" name=\"id\" value=\"$myID\">\n";
            echo "<table align=\"center\" border=\"0\" cellpadding=\"2\">\n";
            echo "<tr>\n <td><p>id: </p></td>\n";
            echo "<td><p><strong>$myID</strong></p></td>\n";
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
        if (strlen($_POST["description"]) == 0) {
            showError("You did not enter a description!");
        }
        else {
            $id = 0;
            $name = '';
            $description = '';

            $id = $_POST["id"];
            $description = $_POST["description"];

            if (mysqli_query($GLOBALS['link'], "UPDATE dl_descriptions SET description = '$description' WHERE id = '$id'")) {
                echo "<p>:: Description-id: <strong>$id</strong> has been updated! ::</p>";
                echo "<script>setTimeout('location.replace(\"/admin/index.php?action=add_descriptions\")',2000);</script>";
            }
            else {
                $errNo = mysqli_errno($myConn);
                $error = mysqli_error($myConn);
                showError("$errNo: $error");
            }
        }
    }
}