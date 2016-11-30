<?php
	error_reporting(0);
	$cal=-1600;

	$junk=exec("crontab /var/www/html/cron");
	//echo exec("/var/www/html/testwifi.sh");
	//echo exec("/bin/ping 192.168.1.1 -c 1");
	//$link=exec("/sbin/iwconfig wlan0 | /bin/grep -i quality");
	//$link=preg_replace('/.*Quality/',"Q",$link);
	//$link=preg_replace('/Signal level/',"S",$link);
	//$link="";
	//file_put_contents("/var/www/html/link",$link);

	$toset=$argv[1];

        $real=file_get_contents("/sys/bus/w1/devices/28-021619b062ee/w1_slave");
        if (preg_match('/YES/',$real)) {
                $real=preg_replace('/\n/',"",$real);
                $real=preg_replace('/.*t=/',"",$real);
		$real+=$cal;
                $real=round(($real/1000),2);
        } else {
                $real=100; //ie off
        }

        $temp=$real;
        file_put_contents("/var/www/html/temp",$real);

        $mode=file_get_contents("/var/www/html/mode");
	if ($toset && ($mode=="Auto")) {
	        $set=$toset;
		file_put_contents("/var/www/html/set",$set);
	} else {
		$set=file_get_contents("/var/www/html/set");
	}
        //echo "$mode $set $temp $real\n";
        //die();

        if ($mode=="On" || $mode=="Auto") {
                if ($set>$temp) {
                        $junk=exec("/usr/bin/python /var/www/html/gpio_on.py 2> /dev/null");
                        echo "s:$set t:$real on\n";
			//$junk=exec("/usr/bin/php /var/www/html/notify.php \"Heating\"");
                } else {
                        $junk=exec("/usr/bin/python /var/www/html/gpio_off.py 2> /dev/null");
                        echo "s:$set t:$real off\n";
                }
        } else {
                $junk=exec("/usr/bin/python /var/www/html/gpio_off.py 2> /dev/null");
                echo "s:$set t:$real off\n";
        }
?>
