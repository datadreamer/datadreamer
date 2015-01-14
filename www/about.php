<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>datadreamer - about aaron siegel</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<meta name="description" content="Computational information design, interactive art, and data visualization created over the past decade by Aaron Siegel.">
		<link rel="stylesheet" type="text/css" href="_css/fonts.css" />
		<link rel="stylesheet" type="text/css" href="_css/site.css" />
		<script src="_js/jquery-1.9.1.min.js"></script>
		<script src="_js/jquery.masonry.min.js"></script>
		<script src="_js/site.js"></script>
		
		<script>
			<?php
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
		
		<div id="gallery">
			<div id="galleryimage">
				<div class='slideshow' id='slideshow'>
					<img class="slide" src='_images/about_1_900.jpg' width='100%' height='100%' />
				</div>
			</div>
		</div>
		
		<div id="contentcontainer">
			<div id="contenttitle">
				About Aaron Siegel
			</div>
			<div id="contenttext">
				Aaron Siegel is a transdisciplinarian with a concentration in computational information design. He received his BFA in Digital Media Art from the <a href="http://cadre.sjsu.edu">Cadre Laboratory for New Media</a> at San Jose State University in 2006, and his MFA in <a href="http://dma.ucla.edu">Design|Media Art</a> from the University of California, Los Angeles in 2008.
				<br/><br/>
				His motivation comes from interesting data sets, interfaces, and public spaces. He utilizes data visualization as a medium to explore complex systems, aiming to create aesthetic representations of data while fostering scientific empiricism. His work strives to display relationships and correlations within information systems that would remain unseen from any other perspective.
				<br/><br/>
				He has created work for various institutions including the <a href="http://jpl.nasa.gov">NASA Jet Propulsion Laboratory</a>, <a href="http://www.electroland.net">Electroland</a>, <a href="http://www.directedplay.com">Directed Play</a>, the <a href="http://senseable.mit.edu">MIT SENSEable City Laboratory</a>, <a href="http://www.facebook.com">Facebook</a> and <a href="http://www.fabrica.it">Fabrica</a>. He has exhibited work in Los Angeles, San Jose, San Francisco, Salt Lake City, Reno, Memphis, Indianapolis, Seattle, New York, Guadalajara, Madrid, Trieste, Heidelberg, Singapore and Rome.
				<br/><br/>
				<a href="/cv.pdf">Learn more</a> or <a href="/contact">contact</a>.
			</div>
		</div>
			
		<?php
			include("_html/footer.html");
		?>
    
	</body>
</html>