<?php
if($_GET["id"]) {
    $id = $_GET["id"];
    $myQuery = "DELETE FROM dl_links WHERE id = '$id'";

    if(mysqli_query($GLOBALS['link'], $myQuery)) {
        echo "<p>:: Download-id <strong>".$id."</strong> was deleted! ::</p>";
        echo "<script>setTimeout('location.replace(\"/admin/index.php?action=show\")',2000);</script>";
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