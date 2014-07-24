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


// query database to get item details
$item = mysql_escape_string($_GET["item"]);
$db = mysql_connect("localhost", "asiegel_web", "buttslol!") or die("Failed to connect to server.");
mysql_select_db("asiegel_site") or die("Failed to select database.");
$result = mysql_query("SELECT * FROM items WHERE id={$item}");
$row = mysql_fetch_array($result);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>datadreamer - <?php echo $row[1]; ?></title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<?php
			if(lite_detection()){
				echo "<meta name=\"viewport\" content=\"target-densitydpi=device-dpi, width=320\"/>";
			}
		?>
		<meta name="description" content="Computational information design, interactive art, and data visualization created over the past decade by Aaron Siegel.">
		<link rel="stylesheet" type="text/css" href="_css/fonts.css" />
		<link rel="stylesheet" type="text/css" href="_css/site.css" />
		<script src="_js/jquery-1.9.1.min.js"></script>
		<script src="_js/jquery.masonry.min.js"></script>
		<script src="_js/jquery.waitforimages.js"></script>
		<script src="_js/site.js"></script>
		
		<script>
			<?php
				echo "type = '{$row[12]}';";
				echo "item = '{$row[7]}';";
				if(!empty($row[9])){
					echo "var videoAspectRatio = {$row[10]};";
				}
			?>
		</script>
		
		<?php
			echo "<script src=\"_js/processing.min.js\"></script>";
		?>
		
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
		
		<div id="contentcontainer">
			<div id="contenttitle">
				<?php
					echo $row[1];
				?>
			</div>
			<div id="contentdate">
				<?php
					echo strtoupper($row[2]);
				?>
			</div>
			<div id="contenttext">
				<?php
					echo $row[4];
				?>
			</div>
			<div id="contentlink">
				<?php
					if(!empty($row[5])){
						echo "<a class='title' href='{$row[5]}'>Enter Project</a>";
					}
				?>
			</div>
		</div>

		<?php
			if(!empty($row[9])){
				echo "<div id=\"videocontainer\">";
				echo "<div id=\"video\">";
				echo "<iframe id=\"videoframe\" src=\"{$row[9]}\" frameborder=\"0\" allowfullscreen></iframe>";
				echo "</div></div>";
			}
		?>
		
		<?php
			include("_html/footer.html");
		?>
		
	</body>

</html>