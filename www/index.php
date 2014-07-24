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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>datadreamer - the work of aaron siegel</title>
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
					
						<div class="bwthumb">
							<?php echo "<img class='bwthumb' src='_images/" . $row[6] . "' onload='$(this).fadeIn();' />"; ?>
						</div>
						<div class="colorthumb">
							<?php echo "<img class='colorthumb' src='_images/" . $row[5] . "' />"; ?>
						</div>
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