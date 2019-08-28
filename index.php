<?php 
/* BEGIN */

class content {
	static public $array_file = array();
}

/* A function to iterate file(s) inside a directory */
function iterate_dir($dirinfo){
	$dir = new DirectoryIterator($dirinfo->getPathName());

	while ($dir->valid()){
		// iterate over all file(s)
		foreach ($dir as $fileinfo) {
			if (!$fileinfo->isDot()) {
				if ($fileinfo->isDir()) {
					# iterate through the dir
					iterate_dir($fileinfo);
				} elseif ($fileinfo->isFile()) {
					// If it's a file then let's read it
			    	read_file_contents($fileinfo);
				}
			}
		}
	}
}

/* A function to read the file content then push in to array */
function read_file_contents($fileinfo) {
	$content = file_get_contents($fileinfo->getPathName());	
	# push into the static array
	content::$array_file[] = $content;	
}

###############################################################
# START
# Initial

$ini_array = parse_ini_file("config.ini"); // Parse the .ini file
if ($ini_array['path']) {
	$directory = $ini_array['path'];        // Directory		
} else {
	exit;
}


# initiate the first iterator
$dir = new DirectoryIterator($directory);

foreach ($dir as $fileinfo) {
	// Omit . and .. entries
	if (!$fileinfo->isDot()){
		// Let's see if it's a directory/folders
		if ($fileinfo->isDir()) {
			# iterate through the dir
			iterate_dir($fileinfo);
		} elseif ($fileinfo->isFile()) {
			// If it's a file then let's read it
	    	read_file_contents($fileinfo);
		}
	}	    
}

// Final section ---> print the max filename and occurences
echo '<pre>';
$arr = array_count_values(content::$array_file);
$maxs = array_keys($arr, max($arr));
print_r($maxs[0]);
print_r(max($arr));
echo '</pre>';
# FINISH
##############################################################


/* END */
?>