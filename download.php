<?php
/**
 *	This file does the actual downloading
 *	It will take in a query string and return either the file, 
 *	or failure
 *
 *	Expects: download.php?key1234567890
 */
 
	include("variables.php");
	
	// The input string
	$key = trim($_SERVER['QUERY_STRING']);
	
	/*
	 *	Retrive the keys
	 */
	$keys = file('keys/keys');
	$match = false;
	
	/*
	 *	Loop through the keys to find a match
	 *	When the match is found, remove it
	 */
	foreach($keys as &$one) {
		if(rtrim($one)==$key) {
			$match = true;
			$one = '';
		}
	}
	
	/*
	 *	Puts the remaining keys back into the file
	 */
	file_put_contents('keys/keys',$keys);
	
	/*
	 * If we found a match
	 */
	if($match !== false) {
		
		/*
		 *	Forces the browser to download a new file
		 */
		$contenttype = CONTENT_TYPE;
		$filename = SUGGESTED_FILENAME;
		set_time_limit(0);
		header("Content-type: {$contenttype}");
		header("Content-Disposition: attachment; filename=\"{$filename}\"");
		readfile(PROTECTED_DOWNLOAD);
		
		// Exit
		exit;
	
	} else {
	
	/*
	 * 	We did NOT find a match
	 *	OR the link expired
	 *	OR the file has been downloaded already
	 */

?>

<html>
	<head>
		<title>Download expired</title>
	</head>
	<body>
		<h1>Download expired</h1>
	</body>
</html>

<?php
	} // end matching
?>