<?php
// IDX Broker API key
$api_key = 'YourAPIKey';

// file to store return
$file = 'featured_listings.txt';


// access URL and request method
$url = 'https://api.idxbroker.com/clients/featured';
$method = 'GET';

// headers (required and optional)
$headers = array(
	'Content-Type: application/x-www-form-urlencoded', // required
	'accesskey: '.$api_key, // required - replace with your own
	'outputtype: json' // optional - overrides the preferences in our API control page
);

// set up cURL
$handle = curl_init();
curl_setopt($handle, CURLOPT_URL, $url);
curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);

// exec the cURL request and returned information. Store the returned HTTP code in $code for later reference
$response = curl_exec($handle);
$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

if ($code >= 200 || $code < 300)
	$response = json_decode($response,true);
else
	$error = $code;
//variable for the markers
$markers = '';
//clear storage file
file_put_contents($file, $markers);

//loop through the returned properties
foreach ($response as $key => $value) {
  //ignore any listings with no longitude
  if($value["longitude"] == '0.000000000000'){
		$marker = '';
	}
	else{
		//set the JS for a pin and bubble contents
		$prop_bubble = '<img src=\''.$value["image"][0]["url"].'\' width=100px><div id=\'address\'>'.$value["address"].'</div><div>'.$value["listingPrice"].'</div>';
	  $marker = 'L.marker(['.$value["latitude"].', '.$value["longitude"].']).addTo(mymap).bindPopup("'.$prop_bubble.'");';

		//store with all the other pins
	  $markers = $markers.$marker;
}
}

//save pins JS to file
file_put_contents($file, $markers);
 ?>
