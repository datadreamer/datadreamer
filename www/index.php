<?php
  ini_set('display_errors', 'On');
  error_reporting(E_ALL | E_STRICT);
  if(!empty($_GET['item'])){
  	header("Location:item.php?item={$_GET['item']}");
  }
?>

<!DOCTYPE html>
<html class="no-js">
	<head>
		<meta charset="utf-8">
		<title>datadreamer - the work of aaron siegel</title>
		<meta name="description" content="Computational information design, interactive art, and data visualizations created over the past decade by Aaron Siegel.">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link rel="stylesheet" type="text/css" href="_css/normalize.min.css" />
		<link rel="stylesheet" type="text/css" href="_css/fonts.css" />
		<link rel="stylesheet" type="text/css" href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700,400italic' />
		<link rel="stylesheet" type="text/css" href="_css/sitenew.css" />
		<link rel="stylesheet" type="text/css" href="_css/about.css" />

		<script src="_js/vendor/jquery-1.11.0.min.js"></script>
    <script src="_js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
		<script src="_js/menu.js"></script>

    <script src="_js/vendor/three.min.js"></script>
    <script src="_js/vendor/CopyShader.js"></script>
    <script src="_js/vendor/SMAAShader.js"></script>
    <script src="_js/vendor/EffectComposer.js"></script>
    <script src="_js/vendor/SMAAPass.js"></script>
    <script src="_js/vendor/RenderPass.js"></script>
    <script src="_js/vendor/MaskPass.js"></script>
    <script src="_js/vendor/ShaderPass.js"></script>

    <style>
      header{
        display: none;
      }
    </style>
	</head>

	<body>

		<?php
			include_once("analyticstracking.php");
			include("_php/header.php");
		?>

    <!-- FULL WINDOW JAVASCRIPT CANVAS ELEMENT -->

		<div id="splash" class="noselect">
		</div>
		<div id="splashtext" class="noselect">
			<p>
				Hi, I'm Aaron.<br/><br/>
				I design systems,<br/>
				interactions, and<br/>
				experiences.<br/>
			</p>
			<br/>
		</div>
		<div id="downarrow" class="noselect">
			<a href="#container">&or;</a>
		</div>

		<!-- CONTAINER HOLDS ITEMS -->

		<div id="container">

			<?php
				// query database to get list of items
        $conn = mysqli_connect("localhost", "asiegel_web", "buttslol!", "asiegel_site");
        if(!$conn){
          die("Connection failed: " . mysqli_connect_error());
        }
				//$result = mysql_query("SELECT * FROM items ORDER BY realdate DESC");
				//$result = mysql_query("SELECT items.id, items.title, items.dt, items.shortdesc, items.category, images.thumb, images.bwthumb FROM items JOIN images ON items.shortname=images.name WHERE items.visible=TRUE AND images.num=1 ORDER BY items.realdate DESC");
        $result = mysqli_query($conn, "SELECT items.id, items.title, items.dt, items.shortdesc, items.category, images.thumb, items.shortname FROM items JOIN images ON items.shortname=images.name WHERE items.visible=TRUE AND images.num=1 ORDER BY items.realdate DESC");

				while($row = mysqli_fetch_assoc($result)){
          if($row['shortname'] == 'about'){
            echo "<a href=\"/about\" class=\"item\" data-sr>";
          } else {
            echo "<a href=\"/item/" . $row['shortname'] . "\" class=\"item\" data-sr>";
          }
          echo "<div class=\"itemtext\">";
          echo "<div class=\"title\">" . $row['title'] . "</div>";
          if(empty($row['dt'])){
            echo "<div class=\"date\">&nbsp;</div>";
          } else {
            echo "<div class=\"date\">" . $row['dt'] . " - " . $row['category'] . "</div>";
          }
          echo "<div class=\"description\">" . $row['shortdesc'] . "</div>";
          echo "<div class=\"itemimg\" style=\"background-image: url('_images/" . $row['thumb'] . "');\"></div>";
          echo "</div></a>";
        }
			?>

  		<?php
  			include("_html/footer.html");
  		?>

		</div>

    <script src="_js/splash/delta.js"></script>

    <script src="_js/vendor/scrollReveal.min.js"></script>
    <script>
      window.sr = new scrollReveal();
			$(function(){
				$(window).scroll(function(){
					if ($(this).scrollTop() > 100) {
                $('header').fadeIn();
            } else {
                $('header').fadeOut();
            }
				});
			});
    </script>

	</body>

</html>
