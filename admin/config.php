<?php
if(!defined("HACK")) {

    die("Доступ запрещен!");

}
$myHost = "localhost"; // usually 'localhost'
$myUN   = "u0786031_school1";
$myPW   = "D3c9W0s5";
$myDB   = "u0786031_school1";
$GLOBALS['link'] = mysqli_connect($myHost, $myUN, $myPW, $myDB);
mysqli_set_charset($GLOBALS['link'], 'utf8');