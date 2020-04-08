<?php
$myResult = mysqli_query($GLOBALS['link'], "SELECT * FROM lect_mails");
if (!$myResult) {
    printf("Error: %s\n", mysqli_error($myConn));
    exit();
}
echo "<br><br>\n";
echo "<table border=\"1\" width=\"50%\">\n<tr>\n";
echo "<td><b>id</b></td>\n";
echo "<td><b>sent_date</b></td>\n";
echo "<td><b>mail_from</b></td>\n";
echo "<td><b>author</b></td>\n";
echo "<td><b>text</b></td>\n";
echo "</tr>\n";

$i = 0;
while($myArray = mysqli_fetch_array($myResult)) {
    $id = $myArray["id"];
    $sent_date = $myArray["sent_date"];
    $mail_from = $myArray["mail_from"];
    $author = $myArray["author"];
    $text = $myArray["text"];
    echo "<tr><td><p><i>$id</i></p></td>\n";
    echo "<td><p>$sent_date</p></td>\n";
    echo "<td><p>$mail_from</p></td>\n";
    echo "<td><p>$author</p></td>\n";
    echo "<td><p>$text</p></td>\n";

    echo "</tr>\n";

    $i++;
}
echo "</table>\n";