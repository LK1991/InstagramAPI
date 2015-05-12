<?php 
// Configuration for our PHP Server 
set_time_limit(0);
ini_set('default_socket_timeout', 300);
session_start();

// Make constants using define.
define('clientID', 'fd04566da7d14dc2a24ad582266be648');
define('clientSecret', '3ff93939b2c84c57a9c5545abfafc330');
define('redirectURI', 'http://localhost:8888/appacademyapi/index.php');
define('ImageDirectory', 'pics/');

// Function that is going to connect to Instagram 
function connectToInstagram($url) {
	$ch = curl_init();

	curl_setopt_array($ch, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSL_VERIFYHOST => 2,
	));
	$result = curl_exec($ch);
	curl_close();
	return $result;
}

// Function to get userID cause userName doesn't allow us to get pictures.
function getUserID($userName) {
	$url = 'http://api.instagram.com/v1/users/search?q='.$userName.'&client_id='.$clientID;
	$instagramInfo = connectionToInstagram($url);
	$results = json_decode($instagramInfo, true);

	echo $results['data']['0']['id'];
}

if (isset($_GET['code'])) {
	$code = ($_GET['code']);
	$url = 'https://api.instagram.com/oauth/access_token';
	$access_token_settings = array('client_id' => clientID,
									'client_secret' => clientSecret,
									'grant_type' => 'authorization_code',
									'redirect_uri' => redirectURI,
									'code' => $code
									);
// cURL is what we use in PHP, it's a library calls to other API's
$curl = curl_init($url); // setting a cURL session and we put in $url because that's where we are getting the data from.
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $access_token_settings); // setting the POSTFIELDS to the array setup that we created.
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // setting it equal to 1 because we are getting strings back.
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // but in live work-production we want to set this to true.

$result = curl_exec($curl);
curl_close($curl);

$results = json_decode($result, true);
getUserID($results['user']['username']);
} else {
?>

<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Untitled</title>
	<link rel="stylesheet" href="css/style.css">
	<link rel="author" href="humans.txt">
</head>
<body>
	<!-- Creating a login or for people to go and give approval for our web app the access their instagram account
	After getting the approval, we are now going to have the information so that we can play with it.
	 -->
	<!-- redirects to authorize account -->
	<a href="https:api.instagram.com/oauth/authorize/?client_id=<?php echo clientID; ?>&redirect_uri=<?php echo redirectURI; ?>&response_type=code">Login</a>
	<script src="js/main.js"></script>
</body>
</html>
<?php
}
?>