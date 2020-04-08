<?php
define("HACK", true);
session_start();
include_once ('functions.php');
if($_SESSION['token'] != ''){
    header('Refresh: 1; URL='.$_SERVER['HTTP_REFERER']);
}
?>
<html>
<head>
    <title>introdata.ru:  Links admin]</title>
    <link rel="stylesheet" type="text/css" href="../css/style3.css"><link rel="stylesheet" href="../css/stylesheet.css" type="text/css">
    <link id="avast_os_ext_custom_font" href="moz-extension://1061611b-9505-42fe-9e86-6e42ef202a76/common/ui/fonts/fonts.css" rel="stylesheet" type="text/css">
</head>
<body>
<div align="center">
    <p class="infotext">:: login ::</p>
    <hr width="100%">
    <form action="" method="post">
        <p><strong>login: </strong></p>
        <input type="text" name="login" value=""><br><br>
        <p><strong>password: </strong></p>
        <input type="password" name="password" value=""><br><br>
        <input type="submit" name="auth" value="Войти в панель"><br>
        <?= auth()['massage']?>
    </form>
</div>
<script type="text/javascript">

    <?= auth()['redirect']?>

</script>
</body>
</html>

