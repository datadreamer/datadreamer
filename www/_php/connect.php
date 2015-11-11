<?php
// connect to db
$db = mysql_connect("localhost", "asiegel_web", "buttslol!") or die("Failed to connect to server.");
mysql_select_db("asiegel_blog") or die("Failed to select database.");

// prep twitter stuff
require_once 'vendor/twitter/twitter.class.php';

// ENTER HERE YOUR CREDENTIALS (see credentials.txt)

$twitter = new Twitter($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
$statuses = $twitter->load(Twitter::ME,30);
date_default_timezone_set('America/Los_Angeles');
?>
