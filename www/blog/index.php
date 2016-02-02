<?php
	// connect to db
	$db = mysql_connect("localhost", "asiegel_web", "buttslol!") or die("Failed to connect to server.");
	mysql_select_db("asiegel_blog") or die("Failed to select database.");

	// prep twitter stuff
	require_once '../_php/vendor/twitter/twitter.class.php';

	// enables caching (path must exists and must be writable!)
	// Twitter::$cacheDir = dirname(__FILE__) . '/temp';


	// ENTER HERE YOUR CREDENTIALS (see credentials.txt)

	$twitter = new Twitter($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

	$statuses = $twitter->load(Twitter::ME);

	date_default_timezone_set('America/Los_Angeles');

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
		<link rel='stylesheet' href='/blog/_css/site.css' />

		<script src='/_js/vendor/jquery-1.11.0.min.js'></script>
        <script src='/_js/vendor/modernizr-2.6.2-respond-1.1.0.min.js'></script>
		<script src='/_js/jquery.fittext.js'></script>
		<script src='/blog/_js/menu.js'></script>
	</head>

	<body>

		<?php
			include_once("../analyticstracking.php");
		?>

		<!-- HEADER -->

		<div id="header" class="noselect">
			<div id="logo">
				<a class="logo" href="/">datadreamer</a>
			</div>
			<div id="menubutton">
				<a href="javascript:toggleMenu();"><img src="/blog/_img/menubutton.png"></a>
			</div>
			<div id="sublogo">
				THE WORK OF <a class="sublogo" href="/about">AARON SIEGEL</a>
			</div>
		</div>

		<!-- MENU -->

		<div id="menu" class="noselect">
			<hr>

			<!-- menu titles -->

			<div id="menutitles">
				<div class="menutitle" id="tweetmenutitle">
					<div class="menutitletext">
						Tweets
					</div>
				</div>
				<div class="menutitle" id="postmenutitle">
					<div class="menutitletext">
						Posts
					</div>
				</div>
				<div class="menutitle" id="tagmenutitle">
					<div class="menutitletext">
						Tags
					</div>
				</div>
				<div class="menutitle" id="mainmenutitle">
					<div class="menutitletext">
						Menu
					</div>
				</div>
			</div>

			<!-- tweets -->

			<div class="submenu" id="tweetmenu">
				<?php
					// list all tweets fetched when the page loaded.
					foreach ($statuses as $status){
						echo "<a href='http://twitter.com/datadreamer/status/{$status->id_str}'>";
						echo "<div class='submenuitem'>";
						echo "<div class='submenutext'>$status->text</div>";
						echo "</div></a>";
					}
				?>

			</div>

			<!-- posts -->

			<div class="submenu" id="postmenu">
				<?php
					// list all blog posts as buttons to open permalinks.
					$result = mysql_query("SELECT title,link,r,g,b FROM posts ORDER BY id DESC");
					while($row = mysql_fetch_assoc($result)){
						$title = $row['title'];
						$link = $row['link'];
						$r = $row['r'];
						$g = $row['g'];
						$b = $row['b'];
						//echo "<a href='http://www.datadreamer.com/blog/{$link}' style='color:rgb({$r},{$g},{$b});'>{$title}</a>";
						echo "<a href='http://www.datadreamer.com/blog/{$link}'>";
						echo "<div class='submenuitem'>";
						echo "<div class='submenutext' style='color:rgb({$r},{$g},{$b});'>{$title}</div>";
						echo "</div></a>";
					}
				?>
			</div>

			<!-- tags -->

			<div class="submenu" id="tagmenu">
				<?php
					// list all tags as buttons to open list of relavent posts.
					$result = mysql_query("SELECT tag, count(tag) AS num FROM tags GROUP BY tag ORDER BY tag ASC");
					while($row = mysql_fetch_assoc($result)){
						$tag = $row['tag'];
						$num = $row['num'];
						//echo "<a href='/blog/tag/{$tag}'>{$tag} <font class='tagnum'>{$num}</font></a>";
						echo "<a href='/blog/tag/{$tag}'>";
						echo "<div class='submenuitem tagmenuitem'>";
						echo "<div class='submenutext tagmenutext'>{$tag} <font class='tagnum'>{$num}</font></div>";
						echo "</div></a>";
					}
				?>
			</div>

			<!-- main menu -->

			<div class="submenu" id="mainmenu">
				<a href="/">
					<div class="submenuitem mainmenuitem">
						<div class="submenutext mainmenutext">Home</div>
					</div>
				</a>
				<a href="http://datadreamer.com/blog">
					<div class="submenuitem mainmenuitem">
						<div class="submenutext mainmenutext">Blog</div>
					</div>
				</a>
				<a href="http://datadreamer.com/dailies">
					<div class="submenuitem mainmenuitem">
						<div class="submenutext mainmenutext">Dailies</div>
					</div>
				</a>
				<a href="http://datadreamer.com/about">
					<div class="submenuitem mainmenuitem">
						<div class="submenutext mainmenutext">About</div>
					</div>
				</a>
				<a href="http://datadreamer.com/contact">
					<div class="submenuitem mainmenuitem">
						<div class="submenutext mainmenutext">Contact</div>
					</div>
				</a>
			</div>
		</div>

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
