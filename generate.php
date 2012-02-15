<?php
/**
 *	This file creates the single use download link
 *	The query string should be the password (which is set in variables.php)
 *	If the password fails, then 404 is rendered
 *
 *	Expects: generate.php?1234
 */
	include("variables.php");
	
	// Grab the query string as a password
	$password = trim($_SERVER['QUERY_STRING']);
	
	/*
	 *	Verify the admin password (in variables.php)
	 */ 
	if($password == ADMIN_PASSWORD) {
		// Create a new key
		$new = uniqid('key',TRUE);
		
		/*
		 *	Create a protected directory to store keys in
		 */
		if(!is_dir('keys')) {
			mkdir('keys');
			$file = fopen('keys/.htaccess','w');
			fwrite($file,"Order allow,deny\nDeny from all");
			fclose($file);
		}
		
		/*
		 *	Write the key key to the keys list
		 */
		$file = fopen('keys/keys','a');
		fwrite($file,"{$new}\n");
		fclose($file);
?>

<html>
	<head>
		<title>Download created</title>
		<style>
			nl { 
				font-family: monospace 
			}
		</style>
	</head>
	<body>
		<h1>Download key created</h1>
		Your new single-use download link:<br>
		<nl><?php 
			echo "http://" . $_SERVER['HTTP_HOST'] . DOWNLOAD_PATH . "?" . $new; 
		?></nl>
	</body>
</html>

<?php
	} else {
		/*
		 *	Someone stumbled upon this link with the wrong password
		 *	Fake a 404 so it does not look like this is a correct path
		 */
		header("HTTP/1.0 404 Not Found");
	}
?>