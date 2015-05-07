<?php 
// Configuration for our PHP Server 
set_time_limit(0);
ini_set('default_socket_timeout', 300);
session_start();

// Make constants using define.
define('client_ID', 'fd04566da7d14dc2a24ad582266be648');
define('client_Secret', '3ff93939b2c84c57a9c5545abfafc330');
define('redirectURI', 'http://localhost:8888/appacademyapi/index.php');
define('ImageDirectory', 'pics/');
 ?>

<!doctype	html>
<html>
<head>
	<meta	charset="utf-8">
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
	<a href="https:api.instagram/oauth/authorize/?client_id=<?php echo client_ID; ?>&redirect_uri=<?php echo redirectURI; ?>&response_type=code">LOGIN</a>
	<script src="js/main.js"></script>
</body>
</html>