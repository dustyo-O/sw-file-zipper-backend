<?php
require_once "init.php";
function create_zip($files = array(),$destination = '',$overwrite = false) {
	//if the zip file already exists and overwrite is false, return false
	if(file_exists($destination) && !$overwrite) { return false; }
	//vars
	$valid_files = array();
	//if files were passed in...
	if(is_array($files)) {
		//cycle through each file
		foreach($files as $file) {
			//make sure the file exists
			if(file_exists($file)) {
				$valid_files[] = $file;
			}
		}
	}
	//if we have good files...
	if(count($valid_files)) {
		//create the archive
		$zip = new ZipArchive();
		if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
			return false;
		}
		//add the files
		foreach($valid_files as $file) {
			$zip->addFile($file,$file);
		}
		//debug
		//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
		
		//close the zip -- done!
		$zip->close();
		
		//check to make sure the file exists
		return file_exists($destination);
	}
	else
	{
		return false;
	}
}

$folder_name = isset($_GET['hash']) ? $_GET['hash'] : null;
$archive_name = isset($_GET['archive']) ? $_GET['archive'] : null;

$files_to_zip = array();

if ($folder_name && $archive_name) {
    $folder = @opendir($folder_name);

    while($file_name = @readdir($folder)) {
        if (($file_name === '.') || ($file_name === '..')) {
            continue;
        }

        $files_to_zip[] = $folder_name . '/' . $file_name;
    }

    //if true, good; if false, zip creation failed
    $result = create_zip( $files_to_zip, $folder_name . '/' . $archive_name . '.zip');

    if ($result) {
        foreach($files_to_zip as $file_name) {
            @unlink($file_name);
        }
        echo '{ "message": "Все отлично" }';
    } else {
        header('HTTP/1.1 404 Not Found');

        echo '{ "message": "не удалось создать архив"}';
    }

} else {
    header('HTTP/1.1 404 Not Found');
    
    echo '{ "message": "Не переданы необходимые данные" }';
}

