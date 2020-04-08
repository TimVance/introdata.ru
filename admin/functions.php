<?php
/*
 * Закрываем файл от прямого доступа
 */
if(!defined("HACK")) {

    die("Доступ запрещен!");

}
session_start();
require ('config.php');
/*
 * Функция генерирует случайный токен
 * по дефолту длина 6 символов
 */
function generateCode($length=6) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
    $code = "";
    $clen = strlen($chars) - 1;
    while (strlen($code) < $length) {
        $code .= $chars[mt_rand(0,$clen)];
    }
    return $code;
}

function auth(){
    if(isset($_POST['auth'])){
        if($_POST['login'] != '' and $_POST['password'] != ''){
            if(checkUser($_POST['login'],$_POST['password']) == true){
                $_SESSION['token'] = generateCode(16);
                $token = $_SESSION['token'];
                $login = $_POST['login'];
                mysqli_query($GLOBALS['link'], "UPDATE `admins` SET `token` = '".$token."' WHERE `login` = '".$login."' ");
                $result['redirect'] = 'location.replace("/admin/index.php");';
                $result['massage'] = '<b style="color:green">Вы успешно авторизовались!</b>';

            }
        }else{
            $result['massage'] = 'Ошибка авторизации!';
        }
        return $result;

    }

}

function checkUser($login, $password){
    $sql = mysqli_query($GLOBALS['link'], "SELECT * FROM admins WHERE login='".$login."' and password='".$password."' ");
    $total = mysqli_num_rows($sql);
    if($total == 1){
        return true;
    }else{
        return false;
    }
}

function showError($errMsg) {
    echo "<div align=\"center\">\n";
    echo "<p class=\"infotext\"><strong>:: error ::</strong></p>\n";
    echo "<p>$errMsg</p>\n";
    //echo "<script>setTimeout('location.replace(\"/admin/index.php?action=".$_GET['action']."\")',10000);</script>";
    echo "</div>\n";
    exit;

}

function RemoveDir($path){
    chmod($path, 0777);
    if(file_exists($path) && is_dir($path))
    {
        $dirHandle = opendir($path);
        while (false !== ($file = readdir($dirHandle)))
        {
            if ($file!='.' && $file!='..')
            {
                $tmpPath=$path.'/'.$file;
                chmod($tmpPath, 0777);

                if (is_dir($tmpPath))
                {  // если папка
                    RemoveDir($tmpPath);
                }
                else
                {
                    if(file_exists($tmpPath))
                    {
                        unlink($tmpPath);
                    }
                }
            }
        }
        closedir($dirHandle);
        if(file_exists($path))
        {
            rmdir($path);
        }
    }
}

