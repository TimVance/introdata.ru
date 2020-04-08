<?php
$myResult = mysqli_query($GLOBALS['link'], "SELECT * FROM dl_data");
if (!$myResult) {
    printf("Error: %s\n", mysqli_error($myConn));
    exit();
}
echo "<br><br>\n";
echo "<table border=\"1\" width=\"50%\">\n<tr>\n";
echo "<td><b>id</b></td>\n";
echo "<td><b>ip</b></td>\n";
echo "<td><b>agree</b></td>\n";
echo "<td><b>useragent</b></td>\n";
echo "<td><b>timein</b></td>\n";
echo "<td><b>timeout</b></td>\n";
echo "</tr>\n";

$i = 0;
while($myArray = mysqli_fetch_array($myResult)) {
    $id = $myArray["id"];
    $ip = $myArray["ip"];
    $useragent = $myArray["useragent"];
    $agree = $myArray["checkbox"];
    $timein = $myArray["timein"];
    $timeout = $myArray["timeout"];
    echo "<tr><td><p><i>$id</i></p></td>\n";
    echo "<td><p>$ip</p></td>\n";
    echo "<td><p>$agree</p></td>\n";
    echo "<td><p>$useragent</p></td>\n";
    echo "<td><p>$timein</p></td>\n";
    echo "<td><p>$timeout</p></td>\n";

    echo "</tr>\n";

    $i++;
}
echo "</table>\n";