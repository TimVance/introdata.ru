<?php
if(isset($_POST['status'])){
    $id = $_POST['id'];
    if($_POST['status'] == 'yes'){
        $statusss = 2;
    }else{
        $statusss = 1;
    }
    mysqli_query($GLOBALS['link'], "UPDATE `users` SET `status` = '".$statusss."' WHERE `id` = ".$id." ");
    header("Location: ?action=doctors");
}