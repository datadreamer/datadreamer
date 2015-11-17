<?php
  // group images by resolution
  $conn = mysqli_connect("localhost", "asiegel_web", "buttslol!", "asiegel_site");
  if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
  }
  $imgresult = mysqli_query($conn, "SELECT col2,col3,col4,col5,col6,col7,col8 FROM images WHERE name='{$item}' ORDER BY num ASC");
  $resolutions = array(array(), array(), array(), array(), array(), array(), array());
  while($imgrow = mysqli_fetch_array($imgresult)){
    for($i=0; $i<mysqli_num_fields($imgresult); $i++){
      array_push($resolutions[$i], $imgrow[$i]);
    }
  }

  // generate animation
  $numslides = mysqli_num_rows($imgresult);
  $midpoint = 100 / $numslides;
  $pointa = $midpoint / 2;
  $pointb = $midpoint + $pointa;
  $pointc = $midpoint * 2;
  $downz = $numslides-1;

  echo "<style>\n";
  echo "@keyframes xfade{\n";
  echo "0%{opacity:0;z-index:{$numslides};}\n";
  echo "{$pointa}%{opacity:1;}\n";
  echo "{$pointb}%{opacity:1;}\n";
  echo "{$pointc}%{opacity:0;z-index:{$downz};}\n";
  echo "}\n\n";

  // generate default styles
  for($i=0; $i<$numslides; $i++){
    $nexti = $i+1;
    $totalsecs = $numslides*4;
    $delaysecs = $i*4;
    echo "#slide{$nexti}{\n";
    echo "background-image: url('_images/{$resolutions[0][$i]}');\n";
    echo "animation: xfade {$totalsecs}s linear {$delaysecs}s infinite;\n";
    echo "}\n\n";
  }

  // generate breakpoints
  $breaks = array(590,900,1210,1520,1830,2140,2450);
  for($i=1; $i<count($resolutions); $i++){
    $lasti = $i-1;
    echo "@media only screen and (min-width: {$breaks[$lasti]}px){\n";
    for($n=0; $n<count($resolutions[$i]); $n++){
      $nextn = $n+1;
      echo "#slide{$nextn}{background-image:url('_images/{$resolutions[$i][$n]}');}\n";
    }
    echo "}\n\n";
  }
  echo "</style>\n\n";

  // generate html
  echo "<div id='slideshow'>\n";
  for($i=0; $i<$numslides; $i++){
    $nexti = $i+1;
    echo "<div class='slide' id='slide{$nexti}'></div>\n";
  }
  echo "</div>\n\n";
?>
