<?php

header('Content-Type: application/json');

//$json = convertfiletojson($config->jsonfilepath.session_id()."-cisalesordr.json");
//echo $json;
//

$billing = get_orderhead(session_id(), '0004238900', false);
$billing = getorderdetails(session_id(), '0004238900', false);
echo json_encode($billing);
/*	$ip = 'http://192.168.1.20:9000';

	$requestbody = array();
	$requestbody['DeviceID'] = '';
	$requestbody['WaitTime'] = 5;
	$requestbody['ConnectionType'] = 4;


	$requestheader = array();
	$requestheader['WaitTime'] = 4;
	$requestheader['ConnectionType'] = 4;



		$curl = curl_init();
		$url = $ip."/api/mtscrahost/RequestDeviceList";
		// Set the options
		curl_setopt($curl,CURLOPT_URL, $url);
		curl_setopt($curl,CURLOPT_PORT, 9000);
		curl_setopt($curl,CURLOPT_FOLLOWLOCATION,TRUE);
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($curl, CURLINFO_HEADER_OUT, true);

		//curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		// This is the fields to post in the form of an array.
		$payload = json_encode($requestheader);

		curl_setopt($curl,CURLOPT_POSTFIELDS, $payload);
		curl_setopt( $curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

			$result = curl_exec($curl);
		$information = curl_getinfo($curl);

	echo $result;

	*/
