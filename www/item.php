<?php
  ini_set('display_errors', 'On');
  error_reporting(E_ALL | E_STRICT);
  // query database to get item details
  $conn = mysqli_connect("localhost", "asiegel_web", "buttslol!", "asiegel_site");
  if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
  }
  if(!empty($_GET["item"])){
    $item = mysqli_real_escape_string($conn, $_GET["item"]);
    $result = mysqli_query($conn, "SELECT * FROM items WHERE id='{$item}'");
  } else if(!empty($_GET["name"])){
    $name = mysqli_real_escape_string($conn, $_GET["name"]);
    $result = mysqli_query($conn, "SELECT * FROM items WHERE shortname='{$name}'");
  }
  $itemrow = mysqli_fetch_assoc($result);
  $item = $itemrow['shortname'];
?>

<!DOCTYPE html>
<html class="no-js">
	<head>
		<meta charset="utf-8">
    <?php
		  echo "<title>datadreamer - {$itemrow['title']}</title>\n";
    ?>
		<meta name="description" content="Interaction design, media art, and data visualizations created over the past decade by Aaron Siegel.">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link rel="stylesheet" type="text/css" href="/_css/normalize.min.css" />
		<link rel="stylesheet" type="text/css" href="/_css/fonts.css" />
		<link rel="stylesheet" type="text/css" href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700,400italic' />
		<link rel="stylesheet" type="text/css" href="/_css/sitenew.css" />
		<link rel="stylesheet" type="text/css" href="/_css/about.css" />

		<script src="/_js/vendor/jquery-1.11.0.min.js"></script>
    <script src="/_js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
		<script src="/_js/menu.js"></script>
	</head>

	<body>

		<?php
			include_once("analyticstracking.php");
			include("_php/header.php");
      include("_php/slideshow.php");
		?>

    <div id="content">
			<div id="projecttitle" data-sr>
				<?php
					echo "<h1>{$itemrow['title']}</h1>";
				?>
			</div>
      <div id="projectdeets" data-sr>
				<div id="projectdate">
          <?php
  					echo strtoupper($itemrow['dt']);
  				?>
        </div>
        <div id="projecttags">
					<!-- tags go here -->
				</div>
        <?php
					if(!empty($itemrow['location'])){
            echo "<a href='{$itemrow['location']}' id='projectlink'>Enter Project &raquo</a>";
          }
				?>
      </div>
			<div id="projectbody">
        <p class="projectbodytext" data-sr>
          <?php
            echo $itemrow['longdesc'];
          ?>
        </p>

    		<?php
    			if(!empty($itemrow['videocode'])){
    				echo "<div class='projectvideo' data-sr>";
    				echo "<iframe class='videoframe' src='" . $itemrow['videocode'] . "' frameborder='0' allowfullscreen></iframe>";
    				echo "</div>";
    			}
    		?>
			</div>
		</div>

		<?php
			include("_html/footer.html");
		?>

    <script src="/_js/vendor/scrollReveal.min.js"></script>
		<script>
      window.sr = new scrollReveal();
    </script>

	</body>
</html>
