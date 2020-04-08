<?php



	    echo '<button class="btn_reg" style=" width: 10%;">Add Doctor</button>';
        if ($myResult = mysqli_query($GLOBALS['link'], "SELECT * FROM `users`")) {
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
                        $active = 'ON';
                    }else{
                        $class = 'dis_status';
                        $check = '';
                        $active = 'OFF';
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
                        <form class="set_status_form" action="?action=doctors&status=yes&id=<?=$myArray['id']?>" method="post">
                            <input type="text" name="id" hidden value="<?=$myArray['id']?>">
                            <input type="text" name="status" hidden value="<?=$myArray['status']?>">
                            <input type="submit" name="sta" value="<?=$active?>">
                        </form>
                    </td>
                    <td>
                        <form class="" action="?action=doctors&delete=yes&idd=<?=$myArray['id']?>" method="post">
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
if(isset($_GET['id']) and isset($_POST['sta'])){
    $id = $_POST['id'];
    if($_POST['status'] == '1'){
        $statusss = 2;
    }else{
        $statusss = 1;
    }
    mysqli_query($GLOBALS['link'], "UPDATE `users` SET `status` = '".$statusss."' WHERE `id` = ".$id." ");
    echo "<script>location.replace(\"/admin/index.php?action=doctors\"); </script>";
    //exit();
}

if($_GET['delete'] == 'yes' and isset($_GET['idd'])){
        $id = $_GET['idd'];
        mysqli_query($GLOBALS['link'], "DELETE FROM `users` WHERE `id` = $id");
    echo "<p>:: Doctor <strong>".$id."</strong> was deleted! ::</p>";
    echo "<script>setTimeout('location.replace(\"/admin/index.php?action=doctors\")',2000);</script>";
        exit();
}

?>

    <div class="mask" >
        <form enctype="multipart/form-data" action="?action=doctors&regs=yes" method="post" class="modal_reg">
            <input type="hidden" name="MAX_FILE_SIZE" value="5242880" />
            <input type="text" hidden name="password" value="1234">
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
            <input type="submit" name="regdocs" class="send_reg" value="Регистрация">
        </form>
    </div>

<?php

if($_GET['regs'] == 'yes' and isset($_POST['regdocs'])){
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $lastname = $_POST['lastname'];
    $bithday = $_POST['bithday'];
    $country = $_POST['country'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];
    $learn = $_POST['learn'];
    $job = $_POST['job'];
    $myQuery0 = "INSERT INTO `users` (`name`, `surname`, `lastname`, `bithday`, `country`, `phone_number`, `email`, `learn`, `job`) VALUES ('$name', '$surname', '$lastname', '$bithday', '$country', '$phone_number', '$email', '$learn', '$job')";
    $myResult0 = mysqli_query($GLOBALS['link'], $myQuery0);
    if(!$myResult0){
        echo 'Ошибка!';
    }else{
        echo 'Доктор успешно добавлен!';
        echo "<script>location.replace(\"/admin/index.php?action=doctors\"); </script>";
    }
}