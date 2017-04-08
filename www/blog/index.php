<?php
	//ini_set('display_errors', 'On');
	//error_reporting(E_ALL | E_STRICT);
	// connect to db
	$db = mysql_connect("localhost", "asiegel_web", "buttslol!") or die("Failed to connect to server.");
	mysql_select_db("asiegel_blog") or die("Failed to select database.");

?>

<!DOCTYPE html>
<html class="no-js">
	<head>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <?php
        	// get article title if linking directly
        	if(!empty($_GET['link'])){
				$linkname = mysql_escape_string($_GET['link']);
				$result = mysql_query("SELECT * FROM posts WHERE link = '{$linkname}'");
				$row = mysql_fetch_assoc($result);
				$title = $row["title"];
				echo "<title>{$title} - datadreamer blog</title>";
			} else if(!empty($_GET['tag'])){
				$searchtag = mysql_escape_string($_GET['tag']);
				echo "<title>{$searchtag} - datadreamer blog</title>";
			} else {
				echo "<title>datadreamer blog</title>";
			}
		?>
		<meta name="description" content="Explorations of data, systems, interactions, and environments.">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700,400italic' rel='stylesheet' type='text/css'>
		<link rel='stylesheet' href='/_css/normalize.min.css'>
		<link rel='stylesheet' href='/_css/fonts.css' />
		<link rel='stylesheet' href='/_css/sitenew.css' />
		<link rel='stylesheet' href='/blog/_css/blog.css' />

		<script src='/_js/vendor/jquery-1.11.0.min.js'></script>
    <script src='/_js/vendor/modernizr-2.6.2-respond-1.1.0.min.js'></script>
		<script src='/_js/jquery.fittext.js'></script>
		<script src='/blog/_js/menu.js'></script>
	</head>

	<body>

		<?php
			include_once("../analyticstracking.php");
			include("../_php/header.php");
		?>

		<!-- BLOG ENTRIES -->

		<div id="entries">
			<?php
				// output blog posts.
				if(!empty($_GET['link'])){
					$linkname = mysql_escape_string($_GET['link']);
					$result = mysql_query("SELECT * FROM posts WHERE link = '{$linkname}'");
				} else if(!empty($_GET['tag'])){
					$result = mysql_query("SELECT * FROM posts INNER JOIN tags ON posts.id = tags.post_id WHERE tags.tag = '{$searchtag}'");
				} else {
					$result = mysql_query("SELECT * FROM posts ORDER BY id DESC LIMIT 1");
				}
				while($row = mysql_fetch_assoc($result)){
					$id = $row['id'];
					$title = $row['title'];
					$link = $row['link'];
					$titleimg = $row['titleimg'];
					$date = date('F jS, Y',strtotime($row['dt']));
					$body = $row['body'];
					$r = $row['r'];
					$g = $row['g'];
					$b = $row['b'];
					// entry specific colors
					echo "<style>";
					echo "#entry{$id} a{color:rgb($r, $g, $b);}";
					echo "#entry{$id} .end{color:rgb($r, $g, $b);line-height:1em;}";
					echo "</style>";
					echo "<div class='entry' id='entry{$id}'>";
					// entry title block
					echo "<div class='entrytitle' style='background-color: rgba({$r}, {$g}, {$b}, 0.7);'>";
					echo "<div class='entrytitletext'>{$title}</div>";
					echo "<div class='entrydate'>{$date}</div>";
					echo "<div class='entrytitleimg' style='background-image:url(\"_img/{$titleimg}\");'></div>";
					echo "</div>";
					// entry body block
					echo "<div class='entrybody'>{$body}</div>";
					// entry tags block
					echo "<div class='tags'>";
					$tagresults = mysql_query("SELECT * FROM tags WHERE post_id = '{$id}'");
					while($tagrow = mysql_fetch_assoc($tagresults)){
						$tag = $tagrow['tag'];
						echo "<a href='/blog/tag/{$tag}'>$tag</a>, ";
					}
					echo "</div>";
					// entry social media sharing block
					echo "<div class='share'>";
					echo "<div class='sharebutton'>";
					echo "<a href='http://www.facebook.com/sharer/sharer.php?u=http://www.datadreamer.com/blog/{$link}'><img src='/blog/_img/share_facebook.png'></a>";
					echo "</div>";
					echo "<div class='sharebutton'>";
					echo "<a href='http://twitter.com/share?text={$title}&url=http://www.datadreamer.com/blog/{$link}'><img src='/blog/_img/share_twitter.png'>";
					echo "</div>";
					echo "<div class='sharebutton'>";
					echo "<a href='https://plus.google.com/share?url=http://www.datadreamer.com/blog/{$link}'><img src='/blog/_img/share_gplus.png'>";
					echo "</div>";
					echo "<div class='sharebutton'>";
					echo "<a href='{$link}'><img src='/blog/_img/share_link.png'></a>";
					echo "</div>";
					echo "</div>";
					echo "</div>";
				}
			?>
		</div>

	</body>

	<script>
		jQuery(".entrytitletext").fitText(1.4, { minFontSize: '18px', maxFontSize: '140px' });
	</script>

</html>
