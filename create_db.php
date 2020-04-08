<?

require ("config.php");

echo "<html>\n<head>\n <title>create table</title>\n";
if ($MyCSS)
	echo "<link rel=\"stylesheet\" href=\"$MyCSS\" type=\"text/css\">\n";
echo "</head>\n<body>\n";

if ($_GET["action"] == "create") {
	$myConn = mysqli_connect ($myHost, $myUN, $myPW, $myDB);
	if (mysqli_select_db ($myConn, $myDB)) {
		$myQuery = "CREATE TABLE dl_links (
			id int(11) unsigned zerofill NOT NULL auto_increment,
			link varchar(255) NOT NULL default '',
			title varchar(40) NOT NULL default '',
			filesize varchar(15) NOT NULL default '',
			category varchar(25) NOT NULL default '',
			downloads int(11) default NULL,
			latest varchar(20) NOT NULL default '0',
			description longtext,
			PRIMARY KEY  (id))";

		if(mysqli_query($myConn, $myQuery)) {
			echo "<p>the table <strong>dl_links</strong> has been created.</p>\n";
			echo "<p>remember to <strong>delete</strong> create_db.php from the server!</p>\n";
		}
		else {
			echo "<p>couldn't create the table!</p>\n";
			echo "<p>the error returned was:\n";
			echo "<strong>".mysqli_errno($myConn)."</strong><br>\n";
			echo mysqli_error($myConn)."</p>\n";
			exit;
		}
		mysqli_close($myConn);
	}
}
else if($_GET["action"] == "delete") {
	$myConn = mysqli_connect($myHost, $myUN, $myPW, $myDB);
	if (mysqli_select_db($myConn, $myDB)) {
		$myQuery = "DROP TABLE IF EXISTS dl_links";
		if(mysqli_query($myConn, $myQuery)) {
			echo "<p>the table <strong>dl_links</strong> has been dropped!</p>\n";
			echo "<p>remember to <strong>delete</strong> create_db.php from the server!</p>\n";
		}
		else {
			echo "<p>couldn't drop the table!</p>\n";
			echo "<p>the error returned was:\n";
			echo "<strong>".mysqli_errno($myConn)."</strong><br>\n";
			echo mysqli_error($myConn)."</p>\n";
			exit;
		}
	}
	mysqli_close($myConn);
}
else {
	echo "<div align=\"center\">\n";
	echo "<p>:: <a href=\"?action=create\">create the table</a> :: <a href=\"?action=delete\">delete the table</a> ::</p>";
	echo "<p>remember to delete <strong>create_db.php</strong> when you are finished!</p>";

}
echo "</body></html>";
?>