<!DOCTYPE html>
<html class="no-js">
	<head>
		<meta charset="utf-8">
		<title>datadreamer - contact aaron siegel</title>
		<meta name="description" content="Interaction design, media art, and data visualizations created over the past decade by Aaron Siegel.">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link rel="stylesheet" type="text/css" href="_css/normalize.min.css" />
		<link rel="stylesheet" type="text/css" href="_css/fonts.css" />
		<link rel="stylesheet" type="text/css" href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700,400italic' />
		<link rel="stylesheet" type="text/css" href="_css/sitenew.css" />
		<link rel="stylesheet" type="text/css" href="_css/contact.css" />

		<script src="_js/vendor/jquery-1.11.0.min.js"></script>
    <script src="_js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
		<script src="_js/menu.js"></script>
	</head>

	<body>

		<?php
			include_once("analyticstracking.php");
			include("_php/header.php");
		?>

		<div id="slideshow">
			<div class="slide" id="slide1"></div>
		</div>

		<div id="content">
			<div id="projecttitle" data-sr>
				<h1>Contact</h1>
			</div>
			<div id="projectbody" data-sr>

				<?php

				function spamcheck($field) {
				  // Sanitize e-mail address
				  $field=filter_var($field, FILTER_SANITIZE_EMAIL);
				  // Validate e-mail address
				  if(filter_var($field, FILTER_VALIDATE_EMAIL)) {
				    return TRUE;
				  } else {
				    return FALSE;
				  }
				}

				// display form if user has not clicked submit
				if (!isset($_POST["from"])) {

				?>

				<div id="formdiv" style="clear:both;">
				</div>

				<?php

				} else {  // the user has submitted the form
				    // Check if "from" email address is valid
				    $mailcheck = spamcheck($_POST["from"]);
				    if ($mailcheck==FALSE) {
				    	echo "<p class=\"projectbodytext\">Invalid input.</p>";
				    } else {
				    	$name = $_POST["name"];
			      	$from = $_POST["from"]; 		// sender
			      	$subject = $_POST["subject"];
			      	$message = $_POST["message"];
			      	// message lines should not exceed 70 characters (PHP rule), so wrap it
			      	$message = wordwrap($message, 70);
			      	// send mail
			      	mail("datadreamerlabs@gmail.com", $subject, $message, "From: $name <$from>\n");
			      	echo "<p class=\"projectbodytext\">";
			      	echo "Thanks for getting in touch. You'll hear back from me soon. In the meantime, <a href='/'>check out some more projects</a>!";
			      	echo "</p>";
				    }
				}

				?>

			</div>
		</div>

		<?php
			include("_html/footer.html");
		?>

		<script src="_js/vendor/scrollReveal.min.js"></script>
		<script>
      window.sr = new scrollReveal();
			$(function() {
				$.get("form.html", function(data){
						$("#formdiv").html(data);
				});
			});
    </script>

	</body>
</html>
