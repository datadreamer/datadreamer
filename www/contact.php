<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>datadreamer - contact aaron siegel</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<meta name="description" content="Computational information design, interactive art, and data visualization created over the past decade by Aaron Siegel.">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link rel="stylesheet" href="_css/normalize.min.css">
		<link rel="stylesheet" href="_css/fonts.css" />
		<link rel="stylesheet" href="_css/site.css" />

		<script src="_js/vendor/jquery-1.11.0.min.js"></script>
        <script src="_js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
		<script src="_js/site.js"></script>
		
		<script>
			<?php
				session_start();
				echo "type = 'canvas';";
				echo "item = 'about';";
			?>
		</script>
		
		<?php
			echo "<script src=\"_js/processing.min.js\"></script>";
		?>
		<script type="text/javascript">
			// convenience function to get the id attribute of generated sketch html element
			function getProcessingSketchId () { return 'aboutEmerge'; }
		</script>
		
	</head>
	
	<body>
		
		<?php
			include_once("analyticstracking.php");
			include("_html/header.html");
		?>
		
		<!--
		<div id="gallery">
			<div id="galleryimage">
				<div class='slideshow' id='slideshow'>
					<img class="slide" src='_images/about_1_900.jpg' width='100%' height='100%' />
				</div>
			</div>
		</div>
		-->
		
		<div id="contentcontainer">
			<div id="contenttitle">
				Contact
			</div>

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

				<div style="clear:both;">
					<form name="contact" action="contact.php" method="post">
						<div style="float: left;">
							<input class="items" type="text" id="name" name="name" value="Name"><br/>
							<input class="items" type="email" id="from" name="from" value="Email"><br/>
							<input class="items" type="text" id="subject" name="subject" value="Subject"><br/>

							<?php
							include("_php/simple-php-captcha.php");
							$_SESSION['captcha'] = simple_php_captcha(array(
								'backgrounds' => array('/home/asiegel/public_html/_php/backgrounds/rainbow.png'),
								'fonts'=>array('/home/asiegel/public_html/_php/fonts/bauhaus.ttf'),
								'characters' => 'abcdefghjkmnprstuvwxyz',
								'color' => '#fff'
							));
							echo '<div style="float:left;"><img src="' . $_SESSION['captcha']['image_src'] . '" id="captcha" /></div>';
							?>

							<div style="float:left;"><input class="items" type="text" id="captcha" name="captcha" value="Captcha" style="width:125px;height:38px"></div><br/>
						</div>
						<div style="float:left;">
							<textarea class="items" id="message" name="message" value="Message" rows="20" cols="50">Message</textarea><br/>
							<input type="submit" value="Send"><br/>
						</div>
					</form>
				</div>

				<?php 
  
				} else {  // the user has submitted the form
				    // Check if "from" email address is valid
				    $mailcheck = spamcheck($_POST["from"]);
				    if ($mailcheck==FALSE) {
				    	echo "<div id='contenttext'>Invalid input.</div>";
				    } else {
				    	if($_POST["captcha"] == $_SESSION['captcha']['code']){
					    	$name = $_POST["name"];
					      	$from = $_POST["from"]; 		// sender
					      	$subject = $_POST["subject"];
					      	$message = $_POST["message"];
					      	// message lines should not exceed 70 characters (PHP rule), so wrap it
					      	$message = wordwrap($message, 70);
					      	// send mail
					      	mail("datadreamerlabs@gmail.com", $subject, $message, "From: $name <$from>\n");
					      	echo "<div id='contenttext'>";
					      	echo "Thanks for getting in touch. You'll hear back from me soon. In the meantime, <a href='/'>check out some more projects</a>!";
					      	echo "</div>";
				      	} else {
				      		echo "<div id='contenttext'>Bad Captcha. <a href='/contact'>Try again</a>.</div>";
				      	}
				    }
				}

				?>

			<script>
			$(".items").focus(function(){
				if($(this).css('color') != 'rgb(255, 255, 255)'){
					$(this).css('color', '#fff');
					$(this).val("");
				}
			});
			</script>

		</div>
			
		<?php
			include("_html/footer.html");
		?>
    
	</body>
</html>