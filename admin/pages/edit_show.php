<?php

if($_GET["id"]) {
    $id = $_GET["id"];
    $myQuery = "SELECT * FROM dl_links WHERE id = '$id'";
    if($myResult = mysqli_query($GLOBALS['link'], $myQuery)) {
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
        echo "<input type=\"hidden\" name=\"id\" value=\"$myID\">\n";
        echo "<table align=\"center\" border=\"0\" cellpadding=\"2\">\n";
        echo "<tr>\n <td><p>id: </p></td>\n";
        echo " <td><p><strong>$myID</strong></p></td>\n";
        echo "</tr>\n<tr>\n <td><p>Title: </p></td>\n";
        echo " <td><input type=\"text\" name=\"title\" size=\"25\" value=\"$myTitle\"></td>\n";
        echo "</tr>\n<tr>\n <td><p>Link to file: </p></td>\n";
        echo " <td><input type=\"text\" name=\"links\" size=\"25\" value=\"$myLink\"></td>\n";

        echo "<tr><td><p>Select Lecturer:</p></td>\n";
        echo "<td>";

        $myQuery = "SELECT id, name FROM dl_lecturers";
        $myResult = mysqli_query($GLOBALS['link'], $myQuery);

        echo '<select name="lecturer">';

        if ($myResult = mysqli_query($GLOBALS['link'], $myQuery)) {
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
        echo "<td colspan=\"2\" align=\"center\"><input type=\"submit\" name='edit_link' value=\"submit\"></td>\n";
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

if(isset($_POST["edit_link"])) {
    if(strlen($_POST["title"]) < 5) {
        showError("Title entered is to short!");
    }
    if(strlen($_POST["links"]) < 5) {
        showError("The link seems to be to short!");
    }
    else {
        $id = $_POST["id"];
        $title = $_POST["title"];
        $links = $_POST["links"];
        $category = $_POST["category"];
        $lecturer = $_POST["lecturer"];
        $duration = $_POST["duration"];

        $myQuery = "UPDATE dl_links SET title = '$title', link = '$links', category = '$category', lecturer = '$lecturer', duration = '$duration' WHERE id = '$id'";
        if (mysqli_query($GLOBALS['link'], $myQuery)) {
            echo "<p>:: Download-id: <strong>$id</strong> has been updated! ::</p>";
            echo "<script>setTimeout('location.replace(\"/admin/index.php?action=".$_GET['action']."&id=".$_GET['id']."\")',2000);</script>";
        }
        else {
            $errNo = mysqli_errno($myConn);
            $error = mysqli_error($myConn);
            showError("$errNo: $error");
        }
    }
}