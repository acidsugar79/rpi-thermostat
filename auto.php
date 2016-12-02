<?php
header("Content-type: image/svg+xml");
echo '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<!-- BERN STARK -->
<svg
   xmlns:svg="http://www.w3.org/2000/svg"
   xmlns="http://www.w3.org/2000/svg"
   width="150mm"
   height="150mm"
   viewBox="0 0 50 50"
   id="bern"
   version="1.1">
  <g id="layer1">
        <rect
                style="fill:#000000;fill-opacity:0;stroke:none"
                width="300"
                height="300"
                x="0"
                y="0"
                ry="0"
        />
        <path d="M 25 5
                A 20 20 0 1 0 25.01 5
                " stroke="#333333" fill="#F0A000" stroke-width="2" fill-opacity="1"
        />
';
$inc=0.105;
$x=25;
$y=25;
$scale=15;
$arc=6.5;
$k=0;
echo "<path d=\"M ".($x+sin($k)*$scale)." ".($y+cos($k)*$scale);
$n=5;
for ($k=0;$k<6.3;$k+=$inc) {
	echo " M ".($x+sin($k)*$scale)." ".($y+cos($k)*$scale);
	if ($n==5) {
		$n=0;
		echo " L ".($x+sin($k)*$scale/1.3)." ".($y+cos($k)*$scale/1.3);
	} else {
		echo " L ".($x+sin($k)*$scale/1.1)." ".($y+cos($k)*$scale/1.1);
	}
	$n++;
}
echo " \" stroke=\"#FFFFFF\" fill=\"#FFFFFF\" stroke-width=\"0.5\" fill-opacity=\"0\" />";

	$hlen=8;
	$mlen=11;
	$slen=10;
	$hx=25;
	$hy=25;
	$mx=25;
        $my=25;
	$sx=25;
        $sy=25;

	$hrad=(date("g")/12)*pi()*2;
	$mrad=(date("i")/60)*pi()*2;
	$srad=(date("s")/60)*pi()*2;

	//$hrad=(6/12)*pi()*2;
        //$mrad=(1/60)*pi()*2;

	$hx+=sin($hrad)*$hlen;
	$hy-=cos($hrad)*$hlen;
	$mx+=sin($mrad)*$mlen;
        $my-=cos($mrad)*$mlen;
        $sx+=sin($srad)*$mlen;
        $sy-=cos($srad)*$mlen;

	echo "
		<path d=\"M 25 25
      	        	L $hx $hy
                	\" stroke=\"#FFFFFF\" fill=\"#00F000\" stroke-width=\"1.8\" fill-opacity=\"1\"
	        />
		<path d=\"M 25 25
                	L $mx $my
                	\" stroke=\"#FFFFFF\" fill=\"#00F000\" stroke-width=\"1.1\" fill-opacity=\"1\"
        	/>
		<path d=\"M 25 25
                        L $sx $sy
                        \" stroke=\"#FF0000\" fill=\"#00F000\" stroke-width=\"0.8\" fill-opacity=\"1\"
                />
	";
/**/
echo '
	<path d="M 25 23
                A 2 2 0 1 0 25.01 23
                " stroke="#FFFFFF" fill="#FFFFFF" stroke-width="0.2" fill-opacity="1"
        />
<!--
	<path d="M 25 20
                L 22 29
                " stroke="#333333" fill="#00F000" stroke-width="1" fill-opacity="1"
        />
        <path d="M 25 20
                L 28 29
                " stroke="#333333" fill="#00F000" stroke-width="1" fill-opacity="1"
        />
	<path d="M 23 25
                L 27 25
                " stroke="#333333" fill="#00F000" stroke-width="1" fill-opacity="1"
        />
-->
';

echo '</g></svg>';
?>
