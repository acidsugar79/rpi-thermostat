<?php

	$cmd=$_GET['cmd'];
	$data=$_GET['data'];
	$del=$_GET['del'];
	if ($cmd) {
		if ($cmd=="temp") {
			//emu change
			//include("change.php");
			$temp=file_get_contents("temp");
			$mode=file_get_contents("mode");
			$set=file_get_contents("set");
			//$link=file_get_contents("link");
                        echo "$temp:$set:$mode";
                }
		if ($cmd=="mode") {
			if ($data) file_put_contents("mode",$data);
			if ($data=="Auto") file_put_contents("set","5");
			if ($data=="On") file_put_contents("set","22");
			if ($data=="Off") file_put_contents("set","5");
			//$junk=exec("/usr/bin/php /var/www/html/notify.php \"mode: $data\"");
                }
		if ($cmd=="set") {
			if ($data) file_put_contents("set",$data);
                }
		if ($cmd=="shed") {
			$olddata=json_decode(file_get_contents("shed"),true);
			if ($data && !$del) {
				$data=preg_split('/,/',$data);
				$olddata[]=$data;
			}
			if ($data!="" && $del) {
				//echo "del $data ".print_r($olddata,true)."<br>";
				unset($olddata[$data]);
			}

			$olddata=array_values($olddata);
			//asort($olddata);

			file_put_contents("shed",json_encode($olddata));
			echo "<table>";
			//echo "<tr class=shed><td style=\"text-align: center;\">D</td>";
			//echo "<td style=\"text-align: center;\">H</td>";
			//echo "<td></td>";
			//echo "<td style=\"text-align: center;\">M</td>";
			//echo "<td></td>";
			//echo "<td colspan=2 style=\"text-align: center;\">T</td>";
			//echo "<td style=\"text-align: center;\">Dur</td></tr>";

			$index=0;
			foreach ($olddata as $d1) {
				$java="
				if (confirm('Delete? ".$d1[0]." ".$d1[1].":".$d1[2]." ".$d1[3]."&deg;')) {
                        		var thePost = $.ajax({
                           			url: '?cmd=shed&del=1&data=$index',
			                	data: {
                        		   	json: ''
                           		},
                           		error: function() {
						alert('error');
                           		},
                           		dataType: 'html',
                           		success: function(data) {
                                		document.getElementById('list2').innerHTML=data;
                           		},
                           		type: 'GET'
                        		});
				}
				";
				echo "<tr class=shed onmouseup=\"$java;\">";
				$c=0;
				foreach ($d1 as $d2) {
					//convert numbers to days... for view
					if ($c==0) {
						if ($d2=="*") $d2="All";
						if ($d2===0) $d2="Sun";
						if ($d2==1) $d2="Mon";
						if ($d2==2) $d2="Tue";
						if ($d2==3) $d2="Wed";
						if ($d2==4) $d2="Thu";
						if ($d2==5) $d2="Fri";
						if ($d2==6) $d2="Sat";
					}
					echo "<td style=\"text-align: center;\">$d2</td>";
					if ($c==1) echo "<td style=\"text-align: center;\">:</td>";
					if ($c==2) echo "<td style=\"text-align: center;\">&nbsp;</td>";
					if ($c==3) echo "<td style=\"text-align: center;\">&deg;</td>";
					$c++;
				}
				//echo "<td>X</td>";
				$index++;
			}
			echo "</table>";
			$cron="@reboot /var/www/html/startup.sh\n";
			foreach ($olddata as $d1) {
				$cron.=$d1[2]." ".$d1[1]." * * ".$d1[0]." /usr/bin/php /var/www/html/update.php ".$d1[3]." \n";
				$d1[2]+=$d1[4]; //min
				if ($d1[2]>=60) {
					$d1[2]-=60;
					$d1[1]++;
				}
				if ($d1[1]>=24) {
					$d1[1]-=24;
					if ($d1[0]!="*") {
						$d1[0]++;
						if ($d1[0]==7) $d1[0]=0;
					}
				}
				//fin
				$cron.=$d1[2]." ".$d1[1]." * * ".$d1[0]." /usr/bin/php /var/www/html/update.php 5 \n";
			}
			//$cron.="*/1 * * * * date>~/date.txt\n";
			file_put_contents("cron",$cron);
			//echo "<span style=\"font-size: 5vw;\">".preg_replace('/\n/',"<br>",$cron)."</span>";
                }
		//?cmd=shed&data='+data
		die();
	}
?>
<html>
	<head>
	<title>Home</title>
	<link rel="apple-touch-icon" href="auto.svg"/>
	<meta name="viewport" content="width=250, user-scalable=no" />
	<!-- initial-scale=1.0, maximum-scale=1.0, -->
	<meta name="apple-mobile-web-app-capable" content="yes">
	<style>
		body {
			background: #000 url("") no-repeat fixed center bottom;
			color: white;
			font-family: "Verdana", sans-serif;
			font-size: 15vw;
		}
		.mode {
			float: right;
			color: white;
			font-family: "Verdana", sans-serif;
                        font-size: 5vw;
			padding-top: 3vw;
			padding-right: 3vw;
		}
		.dec,.inc {
			font-size: 7vw;
		}
		.set {
			display: inline-block;
			width: 22vw;
			font-size: 10vw;
		}
		.prog {
			font-size: 8vw;
		}
		.days {
			font-size: 10vw;
		}
		.picker {
			font-size: 6vw;
		}
		.shed {
			font-size: 9vw;
			color: #000;
		}
	</style>
	<script src="jquery-3.1.1.min.js"></script>
	<script>
		function changemode(setmode) {
			var mode=document.getElementById('mode');
			var hal=document.getElementById('hal');
			var nextmode='Auto';
			if (mode.innerHTML=='Mode: Auto') nextmode='On';
			if (mode.innerHTML=='Mode: On') nextmode='Off';
			if (mode.innerHTML=='Mode: Off') nextmode='Auto';

			if (setmode) nextmode=setmode;

			if (nextmode=='On') {
				//send ajax mode..
				hal.src='on.svg';
				mode.innerHTML='Mode: On';
			}
                        if (nextmode=='Off') {
                                //send ajax mode..
                                hal.src='off.svg';
                                mode.innerHTML='Mode: Off';
                        }
                        if (nextmode=='Auto') {
                                //send ajax mode..
                                hal.src='auto.svg';
                                mode.innerHTML='Mode: Auto';
                        }
			savevar('mode',nextmode);
		}
		function inc() {
			//var cur=document.getElementById('cur');
                        var set=document.getElementById('set');
			set.innerHTML=(parseFloat(set.innerHTML)+0.5);
			savevar('set',set.innerHTML);
		}
		function dec() {
                        var set=document.getElementById('set');
			set.innerHTML=(parseFloat(set.innerHTML)-0.5);
			savevar('set',set.innerHTML);
                }
		function savevar(what,data) {
			var thePost = $.ajax({
			   url: '?cmd='+what+'&data='+data,
			   data: {
			      json: ''
			   },
			   error: function() {
				document.getElementById('cur').innerHTML='error';
			   },
			   dataType: 'html',
			   success: function(data) {
				;
			   },
			   type: 'GET'
			});
		}
		function getdata() {
			var thePost = $.ajax({
			   url: '?cmd=temp',
			   data: {
			      json: ''
			   },
			   error: function() {
				document.getElementById('cur').innerHTML='error';
				setTimeout("getdata();",5000);
			   },
			   dataType: 'html',
			   success: function(data) {
				data=data.split(':');
			      	document.getElementById('cur').innerHTML=data[0];
				document.getElementById('set').innerHTML=data[1];
				//document.getElementById('link').innerHTML=data[3];

				var mode=document.getElementById('mode');
				var hal=document.getElementById('hal');
				var nextmode=data[2];
				if (nextmode=='On') {
					hal.src='on.svg';
					mode.innerHTML='Mode: On';
				}
		                if (nextmode=='Off') {
		                        hal.src='off.svg';
		                        mode.innerHTML='Mode: Off';
		                }
		                if (nextmode=='Auto') {
		                        hal.src='auto.svg';
		                        mode.innerHTML='Mode: Auto';
		                }
				//alert(nextmode);
				//alert(document.body.style.backgroundImage);
				if (nextmode=='Auto' || nextmode=='On') {
					if (parseFloat(data[1])<=parseFloat(data[0])) {
						//alert(parseFloat(data[1]));
						//alert(parseFloat(data[0]))
						document.getElementById('main').style.backgroundImage = "url('')";
					} else {
						//alert(data[0]);
						document.getElementById('main').style.backgroundImage = "url('gas.svg')";
					}
				} else {
					document.getElementById('main').style.backgroundImage = "url('')";
				}
				//document.getElementById('main').style.backgroundImage = "url('gas.svg')";
				setTimeout("getdata();",3000);
			   },
			   type: 'GET'
			});
		}
		setTimeout("getdata();",1000);
	</script>
	</head>
	<body id=main>
		<span class=mode>
			<!--<span id=link></span><br>-->
			<span id=mode style="float:right;">Mode: Auto</span>
		</span>
		<div style="text-align: center;">
			<br>
<div style="position: relative;height: 0;">
<div id=prog style="padding: 10vw;display: none;text-align: center;background-color: #FFF;color: #000;">
	Program<span style="float:right;" class=prog onmouseup="
                document.getElementById('prog').style.display='none';
                document.getElementById('list').style.display='none';
        ">X</span>
	<br>
	<div style="display: inline-block;width: 20vw;font-size: 12vw;">
		<select class=picker id=day>
			<option value="*">ALL</option>
			<option value="0">Sun</option>
                        <option value="1">Mon</option>
                        <option value="2">Tue</option>
                        <option value="3">Wed</option>
                        <option value="4">Thu</option>
                        <option value="5">Fri</option>
                        <option value="6">Sat</option>
		</select>
	</div>
	<div style="display: inline-block;width: 20vw;font-size: 12vw;">
		<select class=picker id=timeh>
		<?php
			for ($k=0;$k<12;$k++) {
				echo "<option>".str_pad($k,2,"0",STR_PAD_LEFT)."</option>";
			}
			echo "<option selected>12</option>";
			for ($k=13;$k<24;$k++) {
                                echo "<option>$k</option>";
                        }
		?>
		</select>
	</div>
	<div style="display: inline-block;width: 20vw;font-size: 12vw;">
		<select class=picker id=timem>
                <?php
                        for ($k=0;$k<30;$k++) {
                                echo "<option>".str_pad($k,2,"0",STR_PAD_LEFT)."</option>";
                        }
                        echo "<option selected>30</option>";
                        for ($k=30;$k<60;$k++) {
                                echo "<option>$k</option>";
                        }
                ?>
                </select>
	</div>
	<br><br>
	<span style="color:#000;font-size: 5vw;">Temperature and Duration</span><br>
	<div style="display: inline-block;">
		<select class=picker id=temp>
		<?php
			echo "<option value=5>5&deg;</option>";
			for ($k=15;$k<26;$k++) {
                		echo "<option value=$k>$k&deg;</option>";
                	}
		?>
                </select>
	</div>

        <div style="display: inline-block;">
		<select class=picker id=dur>
			<option>05</option>
			<option>10</option>
			<option>15</option>
			<option>30</option>
			<option>60</option>
		</select>
        </div>

	<br>
	<span class=prog onmouseup="
		var data='';
		data+=document.getElementById('day').value+',';
                data+=document.getElementById('timeh').value+',';
                data+=document.getElementById('timem').value+',';
                data+=document.getElementById('temp').value+',';
                data+=document.getElementById('dur').value;
                  	var thePost = $.ajax({
                           url: '?cmd=shed&data='+data,
                           data: {
                              json: ''
                           },
                           error: function() {
                                document.getElementById('list2').innerHTML='error';
                           },
                           dataType: 'html',
                           success: function(data) {
                                document.getElementById('list2').innerHTML=data;
                           },
                           type: 'GET'
                        });
		document.getElementById('prog').style.display='none';
		document.getElementById('list').style.display='';
	">Add</span>
	<br>
</div>
</div>

<div style="position: relative;height: 0;">
<div id=list style="padding: 5vw;display: none;text-align: center;background-color: #FFF;color: #000;">
	Shedule<span style="float:right;" class=prog onmouseup="
                document.getElementById('prog').style.display='none';
                document.getElementById('list').style.display='none';
        ">X</span><br>
	<div id=list2 style="padding-left: 4vw;">
	LIST<br>
	</div>
</div>
</div>
<script>
                        var thePost = $.ajax({
                           url: '?cmd=shed&data=',
                           data: {
                              json: ''
                           },
                           error: function() {
                                document.getElementById('list2').innerHTML='error';
                           },
                           dataType: 'html',
                           success: function(data) {
                                document.getElementById('list2').innerHTML=data;
                           },
                           type: 'GET'
                        });
</script>
			<span id=cur>22.5</span>&deg;<br>
			<span style="font-size: 3vw">
			<img onmouseup="changemode('Off');" style="width: 15vw;" src="off.svg">&nbsp;&nbsp;&nbsp;
			<img onmouseup="changemode('Auto');" style="width: 15vw;" src="auto.svg">&nbsp;&nbsp;&nbsp;
			<img onmouseup="changemode('On');" style="width: 15vw;" src="on.svg">
			<br>
			<img onmouseup="if (0) {changemode();}" id=hal style="width: 50vw;" src="off.svg"><br>
			</span>
			<span class=dec onmouseup="dec();">&#10134;</span>&nbsp;
			<span class=set id=set onmouseup="this.innerHTML=parseFloat(prompt('Temprature?'))-0.5;inc();">22.5</span>&deg;&nbsp;
			<span class=inc onmouseup="inc();">&#10133;</span><br>
			<span class=prog><br></span>
			<span class=prog onmouseup="document.getElementById('prog').style.display='';">Program</span>
			&nbsp;&nbsp;
			<span class=prog onmouseup="document.getElementById('list').style.display='';">List</span>
		</div>
	</body>
</html>
