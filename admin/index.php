<?php
define("HACK", true);
include_once ('config.php');
include_once ('functions.php');
if(isset($_GET['exit'])){
    session_destroy();
    header('Location:/admin/index.php');
}
if($_SESSION['token'] == ''){
    include_once ('login.php');
}else{
    include_once ('pages/sections/header.php');
    if($_GET['action'] != ''){
        $action = $_GET['action'];
        $page_path = 'pages/'.$action.'.php';
        if(file_exists($page_path))
        {
            include_once ('pages/'.$action.'.php');
        }else{
            echo '<h1>Ошибка! Такой страницы не существует</h1>';
        }
    }else{
        include_once ('pages/main.php');
    }
    include_once ('pages/sections/footer.php');
}
