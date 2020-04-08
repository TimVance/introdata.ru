<?php



echo '<button class="btn_reg" style=" width: 10%;">Add Admin</button>';
if ($myResult = mysqli_query($GLOBALS['link'], "SELECT * FROM `admins`")) {
    ?>
    <table class="table_doctotrs">
        <tr>
            <th>ИД</th>
            <th>login</th>
            <th>Del</th>
        </tr>
        <?
        while($myArray = mysqli_fetch_assoc($myResult)) {
            ?>
            <tr>
                <td>
                    <?=$myArray['id']?>
                </td>
                <td>
                    <?=$myArray['login']?>
                </td>
                <td>
                    <form class="" action="?action=admins&delete=yes&idd=<?=$myArray['id']?>" method="post">
                        <input type="text" name="idd" hidden value="<?=$myArray['id']?>">
                        <input type="submit" name="del" value="del">
                    </form>

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

if($_GET['delete'] == 'yes' and isset($_GET['idd'])){
    $id = $_GET['idd'];
    mysqli_query($GLOBALS['link'], "DELETE FROM `admins` WHERE `id` = $id");
    echo "<p>:: ADMIN <strong>".$id."</strong> was deleted! ::</p>";
    echo "<script>setTimeout('location.replace(\"/admin/index.php?action=admins\")',2000);</script>";
    exit();
}

?>

    <div class="mask" >
        <form enctype="multipart/form-data" action="?action=admins&reg=yes" method="post" class="modal_reg">
            <input type="hidden" name="MAX_FILE_SIZE" value="5242880" />
            <input type="text" hidden name="password" value="1234">
            <div class="close_modal">X</div>
            <h3>Логин: </h3>
            <input required name="login" type="text" placeholder="login">
            <h3>Пароль: </h3>
            <input required name="password" type="text" placeholder="password">
            <input type="submit" name="regdoc" class="send_reg" value="Добавить">
        </form>
    </div>

<?php

if($_GET['reg'] == 'yes' and isset($_POST['regdoc'])){
    $login = $_POST['login'];
    $pass = $_POST['password'];

    $myQuery0 = "INSERT INTO `admins` (`login`, `password`) VALUES ('$login', '$pass')";
    $myResult0 = mysqli_query($GLOBALS['link'], $myQuery0);
    if(!$myResult0){
        echo 'Ошибка!';
    }else{
        echo 'Админ успешно добавлен!';
        echo "<script>location.replace(\"/admin/index.php?action=admins\"); </script>";
    }
}