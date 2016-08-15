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

echo date('h:m:s' , time());

/*between 1 - 100*/
$quality = 60;
/*This is just a coutner for the amount of files*/
$counter = 0;
/*until which size not to compress*/
$sizeLimit = 1000;
/*the location of the folder */
$folder = __DIR__.'/';
?>
<ol>
<?php
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

		// jump over folder links
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
	switch ( $info['mime'] ) {
		case 'image/jpeg':
			$image = imagecreatefromjpeg($source_url);
			imagejpeg($image, $source_url, $quality);
			break;
		case 'image/gif':
			$image = imagecreatefromgif($source_url);
			imagegif($image, $source_url, $quality);
			break;
		case 'image/png':
			$image = imagecreatefrompng($source_url);
			imagepng($image, $source_url, ceil($quality / 10));
			break;
		default:
			return;
	}

	// add to global counter
	$counter++;

	// print the generated file path
	echo '<li?'.$counter.': '.$source_url.'</li>';
}


echo '<li>Successfully compressed '.$counter. "Files</li>";
?>

</ol>


<?php echo date('h:m:s' , time());?>
