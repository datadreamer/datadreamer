<?php

require_once '../_php/vendor/twitter/twitter.class.php';

// enables caching (path must exists and must be writable!)
// Twitter::$cacheDir = dirname(__FILE__) . '/temp';


// ENTER HERE YOUR CREDENTIALS (see credentials.txt)

$twitter = new Twitter($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

$statuses = $twitter->load(Twitter::ME);

date_default_timezone_set('America/Los_Angeles');

?>

<!doctype html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Twitter timeline demo</title>

<?php
foreach ($statuses as $status):
echo "<a href='http://twitter.com/datadreamer/status/{$status->id_str}'>";
echo $status->text ."\n";
?>
<div class="tweetdate">
<?php
echo date("F j, Y h:i a", strtotime($status->created_at));
?>
</div>
</a>
<?php
endforeach
?>
