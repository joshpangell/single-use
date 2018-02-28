# Single use download script

## Demo
[http://cloud.joshpangell.com/singleuse](http://cloud.joshpangell.com/singleuse)

## Brief

This script was written to be a very easy way for non-programmers to be able to create a secure way to share a single file. It is ideal for bands looking to give a single song to a single person, and invalidating the link once the song has been downloaded. However, it will work for any type of file.

## Description

This script allows you to generate a unique link to download a file. This file will only be allowed to download one time. This link will also have also have an expiration date set on it.

For instance, if you wanted to sell a song for your band. You sold the song on your website for $1, you could use this script to allow that person to download your song only one time. It would only give them a limited number of hours/days/weeks/years to claim their download.

You can also mask the name of the file being downloaded, for further protection. For example, if your song was called "greatsong.zip", you could set the download link as "Band_Awesome-Awesome_Song.zip" (it is not a good idea to leave spaces in URL titles)

## Update

On Feb 28, 2018 a feature was added to allow remote files to be downloaded, in addition to local files

On July 11, 2016 a multi-file feature branch was merged with the single file. It is now possible to download multiple files at once. 

## Usage

All files must be uploaded to a directory on your server. 
This directory's permissions MUST be `chmod 755` 
(Also known as) 
`User: read/write/execute`
`Group: read/execute`
`World: read/execute`

The directory called `secret` must also have the same permissions set as the parent directory. 

You will need to modify the `variables.php` file and set your file specific info.

	// Arrays of content type, suggested names and protected names
	$PROTECTED_DOWNLOADS = array(
		array(
			'content_type' => 'application/zip', 
			'suggested_name' => 'computing.zip', 
			'protected_path' => 'secret/file1.zip'
		),
		array(
			'content_type' => 'application/zip', 
			'suggested_name' => 'star.zip', 
			'protected_path' => 'secret/file2.zip'
		)
	);

	// The path to the download.php file (probably same dir as this file)
 	define('DOWNLOAD_PATH','/singleuse/download.php');
	
	// The admin password to generate a new download link
	define('ADMIN_PASSWORD','1234');
	
	// The expiration date of the link (examples: +1 year, +5 days, +13 hours)
	define('EXPIRATION_DATE', '+1 month');

Once this is in place, you are ready to generate a new download key. To do this, you will need to use the password you set in the variables file. In the example above, that is `1234`

Navigate to `example.com/singleuse/generate.php?1234` (Notice the `?1234` a the end — that is your password)

Copy the link that is generated and send it off. Voila! Done.