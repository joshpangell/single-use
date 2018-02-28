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
	$key = trim($_GET['key']);
	$i = trim($_GET['i']);
	
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
		$contenttype = $PROTECTED_DOWNLOADS[$i]['content_type'];
		$filename = $PROTECTED_DOWNLOADS[$i]['suggested_name'];
		$file = $PROTECTED_DOWNLOADS[$i]['protected_path'];
		$remote_file = $PROTECTED_DOWNLOADS[$i]['remote_path'];

		set_time_limit(0);

		// If a remote file is set
		if($remote_file) {

			$file=fopen($remote_file,'r');
			header("Content-Type:text/plain");
			header("Content-Disposition: attachment; filename=\"{$filename}\"");
			fpassthru($file);

		// This is a local file
		} else {
		
			header("Content-Description: File Transfer");
			header("Content-type: {$contenttype}");
			header("Content-Disposition: attachment; filename=\"{$filename}\"");
			header("Content-Length: " . filesize($file));
			header('Pragma: public');
			header("Expires: 0");
			readfile($file);

		}
		
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