<?php

$dir = './';

if ($dh = opendir($dir)){
	while (($file = readdir($dh)) !== false){
		$path = './'.$file;
		if(is_dir($path) && $file!=="." && $file!==".."){
			unlink($path.'/index.php');
			unlink($path.'/read.php');
			unlink($path.'/mobile/index.php');
			unlink($path.'/visits.db');
		}
	}
	closedir($dh);
}