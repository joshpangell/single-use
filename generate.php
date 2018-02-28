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

	// Get a human readable file size from bytes
	function human_filesize($bytes, $decimals = 2) {
		$sz = 'BKMGTP';
		$factor = floor((strlen($bytes) - 1) / 3);
		return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
	}
	
	/*
	 *	Verify the admin password (in variables.php)
	 */ 
	if($password == ADMIN_PASSWORD) {
		// Create a list of files to download from
		$download_list = array();
		
		if(is_array($PROTECTED_DOWNLOADS)) {
			foreach ($PROTECTED_DOWNLOADS as $key => $download) {
				// Create a new key
				$new = uniqid('key',TRUE);
				
				// get download link and file size
				$download_link = "http://" . $_SERVER['HTTP_HOST'] . DOWNLOAD_PATH . "?key=" . $new . "&i=" . $key; 
				$filesize = (isset($download['file_size'])) ? $download['file_size'] : human_filesize(filesize($download['protected_path']), 2);

				// Add to the download list
				$download_list[] = array(
					'download_link' => $download_link,
					'filesize' => $filesize
				);

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
			}
		}
		
?>

<html>
	<head>
		<title>Download created</title>
		<link href="bootstrap/css/bootstrap.css" rel="stylesheet">
	    <link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
	    <link href="bootstrap/css/docs.css" rel="stylesheet">
	    <link href="bootstrap/google-code-prettify/prettify.css" rel="stylesheet">
		<style>
			body {
	    		padding-top: 25px;
	    	}
		</style>
	</head>
	<body>
		 <div class="container">
			<h1>Download key created</h1>
			<h6>Your new single-use download links:<h6><br>
			<? foreach ($download_list as $download) { ?>			
			<h4>
				<a href="<?= $download['download_link'] ?>"><?= $download['download_link'] ?></a><br>
				Size: <?= $download['filesize'] ?>
			</h4>
			<? } ?>
			
			<br><br>
			<a href="/singleuse">Back to the demo</a>
		</div>
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