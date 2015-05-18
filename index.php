<?php 
// Configuration for our PHP Server 
set_time_limit(0);
ini_set('default_socket_timeout', 300);
session_start();

// Make constants using define.
define('clientID', 'fd04566da7d14dc2a24ad582266be648');
define('clientSecret', '3ff93939b2c84c57a9c5545abfafc330');
define('redirectURI', 'http://localhost/appacademyapi/index.php');
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
	curl_close($ch);
	return $result;
}

// Function to get userID cause userName doesn't allow us to get pictures.
function getUserID($userName) {
	$url = 'https://api.instagram.com/v1/users/search?q=\"lixnna\"&client_id='.clientID; // to get id
	$instagramInfo = connectToInstagram($url); // connecting to instagram
	$results = json_decode($instagramInfo, true); // creating a local variable to decode json information.
 
	return $results['data']['0']['id']; // echoing out userID
}

// Function to print out images onto the screen
function printImages($userID) {
	$url = 'https://api.instagram.com/v1/users/'.$userID.'/media/recent?client_id='.clientID.'&count=6';
	$instagramInfo = connectToInstagram($url);
	$results = json_decode($instagramInfo, true);
	// Parse through the info one by one.
	foreach($results['data'] as $items) {
		$image_url = $items['images']['low_resolution']['url']; // going to go through all of my results and give myself back the URL of those pictures because we want to save it in the PHP Server.
		echo '<img src=" '.$image_url.' "/><br/>';
		// calling a function to save that $image_url
		savePictures($image_url);
	}
}
// Function to save image to server
function savePictures($image_url) {
	//echo $image_url .'<br>'; 
	$filename = basename($image_url); // the filename is what we are storing. basename is the PHP built in method that we are using to store $image_url
	//echo $filename . '<br>'; 

	$destination = ImageDirectory . $filename; // making sure that the image doesn't exist in the storage.
	file_put_contents($destination, file_get_contents($image_url)); // goes and grabs an imagefile and stores it into our server
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

$userName = $results['user']['username'];

$userID = getUserID($userName);

?>
<!DOCTYPE html>
<html>
<head>
	<link href='http://fonts.googleapis.com/css?family=Oleo+Script' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/main.css">
	<script src="https://bloggerxtutorials.googlecode.com/files/whitesparkle-min.js" type="text/javascript"></script>
</head>
<body>
 <u><h1>Instagram Photos</h1></u>
	<div class = "div2">
	 		<?php printImages($userID); ?>
	</div>
</body>
</html>

<?php 
} else {
?>



<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="description" content="">
	<meta name="viewport" content="mjmoiuo=device-width, initial-scale=1">
	<title>Instagram API</title>
	<link rel="stylesheet" href="css/style.css">
	<link rel="author" href="humans.txt">
	<link rel="stylesheet" href="css/main.css">
	<link href='http://fonts.googleapis.com/css?family=Biryani' rel='stylesheet' type='text/css'>
	<script src="https://bloggerxtutorials.googlecode.com/files/whitesparkle-min.js" type="text/javascript"></script>
</head>
<body id="body">
	<!-- Creating a login or for people to go and give approval for our web app the access their instagram account
	After getting the approval, we are now going to have the information so that we can play with it.
	 -->
	<!-- redirects to authorize account -->
	<a href="https:api.instagram.com/oauth/authorize/?client_id=<?php echo clientID; ?>&redirect_uri=<?php echo redirectURI; ?>&response_type=code" class= "button1">Login</a>
	<script src="js/main.js"></script>
</body>
</html>
<?php
}
?>