<?php
	$temp=file_get_contents("temp");
	$mode=file_get_contents("mode");
	$set=file_get_contents("set");
	//echo "$mode $set $temp\n";
	if ($mode=="On" || $mode=="Auto") {
		if ($set>$temp) {
			$temp=($temp+0.5);
			file_put_contents("temp",$temp);
		} else {
			if ($temp>$set) {
				if ($temp>15) {
					$temp=($temp-0.5);
					file_put_contents("temp",$temp);
				}
			}
		}
	} else {
		if ($temp>15) {
			$temp=($temp-0.5);
			file_put_contents("temp",$temp);
		}
	}
?>
