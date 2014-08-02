<?php
 
/* this does user agent checking */
 
function lite_detection() {
  if (isset($_SERVER['HTTP_X_WAP_PROFILE']) ||
      isset($_SERVER['HTTP_PROFILE'])) {
    return true;
  }
  $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
  if (in_array(substr($user_agent, 0, 4), lite_detection_ua_prefixes())) {
    return true;
  }
  $accept = strtolower($_SERVER['HTTP_ACCEPT']);
  if (strpos($accept, 'wap') !== false) {
    return true;
  }
  if (preg_match("/(" . lite_detection_ua_contains() . ")/i", $user_agent)) {
    return true;
  }
  if (isset($_SERVER['ALL_HTTP']) && strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false) {
    return true;
  }
  return false;
}
 
function lite_detection_ua_prefixes() {
  return array( 'w3c ', 'w3c-', 'acs-', 'alav', 'alca', 'amoi', 'audi', 'avan', 'benq', 'bird', 'blac',
    'blaz', 'brew', 'cell', 'cldc', 'cmd-', 'dang', 'doco', 'eric', 'hipt', 'htc_', 'inno', 'ipaq', 'ipod',
    'jigs', 'kddi', 'keji', 'leno', 'lg-c', 'lg-d', 'lg-g', 'lge-', 'lg/u', 'maui', 'maxo', 'midp', 'mits',
    'mmef', 'mobi', 'mot-', 'moto', 'mwbp', 'nec-', 'newt', 'noki', 'palm', 'pana', 'pant', 'phil', 'play',
    'port', 'prox', 'qwap', 'sage', 'sams', 'sany', 'sch-', 'sec-', 'send', 'seri', 'sgh-', 'shar', 'sie-',
    'siem', 'smal', 'smar', 'sony', 'sph-', 'symb', 't-mo', 'teli', 'tim-', 'tosh', 'tsm-', 'upg1', 'upsi',
    'vk-v', 'voda', 'wap-', 'wapa', 'wapi', 'wapp', 'wapr', 'webc', 'winw', 'winw', 'xda ', 'xda-',
  );
}
 
function lite_detection_ua_contains() {
  return implode("|", array(
    'android', 'blackberry', 'hiptop', 'ipod', 'lge vx', 'midp', 'maemo', 'mmp', 'netfront', 'nintendo DS',
    'novarra', 'openweb', 'opera mobi', 'opera mini', 'palm', 'psp', 'phone', 'smartphone', 'symbian',
    'up.browser', 'up.link', 'wap', 'windows ce',
  ));
}

if(!empty($_GET['item'])){
	header("Location:item.php?item={$_GET['item']}");
}
?>

<!DOCTYPE html>
<html class="no-js">
	<head>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>datadreamer - the work of aaron siegel</title>
		<meta name="description" content="Computational information design, interactive art, and data visualizations created over the past decade by Aaron Siegel.">
		<?php
			if(lite_detection()){
				// use this to force the viewport of all detected mobile devices to 320px wide.
				//echo "<meta name=\"viewport\" content=\"target-densitydpi=device-dpi, width=320\"/>";
				echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">";
			} else {
				echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">";
			}
		?>
		
		<link rel="stylesheet" href="_css/normalize.min.css">
		<link rel="stylesheet" href="_css/fonts.css" />
		<link rel="stylesheet" href="_css/site.css" />

		<script src="_js/vendor/jquery-1.11.0.min.js"></script>
        <script src="_js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
		<script src="_js/processing.min.js"></script>
		<script src="_js/home.js"></script>
	</head>
	
	<body>
		
		<?php
			include_once("analyticstracking.php");
			include("_html/header.html");
		?>
		
		<div id="gallery">
			<div id="galleryimage">
				<div class="slideshow" id="slideshow">
					
				</div>
			</div>
		</div>
		
		<div id="container">
			
			<?php
				// query database to get list of items
				$db = mysql_connect("localhost", "asiegel_web", "buttslol!") or die("Failed to connect to server.");
				mysql_select_db("asiegel_site") or die("Failed to select database.");
				//$result = mysql_query("SELECT * FROM items ORDER BY realdate DESC");
				$result = mysql_query("SELECT items.id, items.title, items.dt, items.shortdesc, items.category, images.thumb, images.bwthumb FROM items JOIN images ON items.shortname=images.name WHERE items.visible=TRUE AND images.num=1 ORDER BY items.realdate DESC");
				
				while($row = mysql_fetch_row($result)){
					//print_r($row);
					echo "<div class=\"item\" onclick=\"location.href='item.php?item={$row[0]}'\">";
				?>
					
						<?php echo "<img class='thumb' src='_images/" . $row[5] . "' />"; ?>
						<div class="title">
							<?php echo $row[1]; ?>
						</div>
						<div class="date">
							<?php
								if(!empty($row[2]) && !empty($row[4])){
									echo strtoupper($row[2]) . " - " . strtoupper($row[4]);
								} else {
									echo "<br/>";
								}
							?>
						</div>
						<div class="description">
							<?php echo $row[3]; ?>
						</div>
					</div>
			<?php
				}
			?>
			
		</div>
		
		<?php
			include("_html/footer.html");
		?>
		
	</body>
	
</html>