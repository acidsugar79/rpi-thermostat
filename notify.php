<?php
	$nmessage=$argv[1];
	if (!$nmessage) $nmessage="none";
	curl_setopt_array($ch = curl_init(), array(
  		CURLOPT_URL => "https://api.pushover.net/1/messages.json",
  		CURLOPT_POSTFIELDS => array(
    			"token" => "a82vsev35hkff6b6bz8uuxoc48xir3",
    			"user" => "gives4qgc8faaebgnp5d35u2b46vr7",
    			"message" => "$nmessage",
  		),
  		CURLOPT_SAFE_UPLOAD => true,
	));
	curl_exec($ch);
	curl_close($ch);
?>
