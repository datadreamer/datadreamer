<?php

$item = mysql_escape_string($_GET["item"]);
$type = mysql_escape_string($_GET["type"]);
$cols = mysql_escape_string($_GET["cols"]);

// query database to get list of items
$db = mysql_connect("localhost", "asiegel_web", "buttslol!") or die("Failed to connect to server.");
mysql_select_db("asiegel_site") or die("Failed to select database.");

if($type == "slideshow"){
	// load slideshow images to fill gallery div
	$colstr = "col" . $cols;
	$result = mysql_query("SELECT {$colstr} FROM images WHERE name='{$item}' ORDER BY num ASC");
	while($row = mysql_fetch_row($result)){
		echo "<img class='slide' src='/_images/{$row[0]}' width='100%' height='100%' />\n";
	}
} else if($type == "canvas"){
	// load a canvas object to fill gallery div
	$result = mysql_query("SELECT body FROM canvases WHERE name='{$item}'");
	$row = mysql_fetch_row($result);
	echo $row[0];
}

?>