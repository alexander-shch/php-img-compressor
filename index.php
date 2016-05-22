<?php
/*

Replace the files with new compressed files


This is a free licance code so you can use it where ever you would like
Have fun!!!! :)

Author: Alexander Sasha Shcherbakov
*/





/*Disbale execution time*/
ini_set('max_execution_time', 0);
/*Set memory to max*/
ini_set('memory_limit', '-1');

/*between 1 - 100*/
$quality = 20;
/*This is just a coutner for the amount of files*/
$counter = 0;
/*until which size not to compress*/
$sizeLimit = 1000000;
/*the location of the folder */
$folder = __DIR__;

// Call the main function 
compressImagesIn($folder, $quality, $sizeLimit);

// main function compressImagesIn
function compressImagesIn($folder, $quality, $sizeLimit){
	// global counter
	global $counter;

	// if no path don't execute
	if(!$folder){
		return;
	}

	// get folder containing
	$containing = scandir($folder);

	// if folder empty go back
	if(!is_array($containing)){
		return;
	}

	// run on folder content
	foreach ($containing as $key => $value) {

		// jump ever folder links
		if($value === '.' || $value === '..'){
			continue;
		}

		// Define new folder path
		$filePath = $folder.'/'.$value;

		// if a folder self execute
		if(is_dir($filePath)){
			// Self execute
			compressImagesIn($filePath, $quality, $sizeLimit);
			continue;
		}

		// call image compression
		compress_image($filePath, $quality, $sizeLimit);

	}

	// This will execute after all the proccess was completed
	echo 'Successfully compressed '.$counter.' Files';

}

// compression function compress_image
function compress_image($source_url, $quality, $sizeLimit) {
	// global counter
	global $counter;

	// check file size if to execute and the ending of the string if it matches our file types
	if( (int) filesize($source_url) < (int) $sizeLimit 
		|| !preg_match('/(.jpg|.gif|.png)$/', $source_url) ){
		return;
	}

	// get image data
	$info = getimagesize($source_url);

	// check if can compress
	if ($info['mime'] == 'image/jpeg') {
		$image = imagecreatefromjpeg($source_url);
	}
	elseif ($info['mime'] == 'image/gif') {
		$image = imagecreatefromgif($source_url);
	}
	elseif ($info['mime'] == 'image/png') {
		$image = imagecreatefrompng($source_url);
	}
	} else {
		return;
	}

	// create image from bitmap with the quality decrease
	imagejpeg($image, $source_url, $quality);

	// add to global counter
	$counter++;

	// print the generated file path
	echo $counter.': '.$source_url.PHP_EOL;
}