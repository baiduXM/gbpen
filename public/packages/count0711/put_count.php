<?php

$dir = './';

if ($dh = opendir($dir)){
	while (($file = readdir($dh)) !== false){
		$path = './'.$file;
		if(is_dir($path) && $file!=="." && $file!==".."){
			// if(!file_exists($path.'/index.php')){
				@copy('./count0711/index.php',$path.'/index.php');
			// }
			// if(!file_exists($path.'/read.php')){
				@copy('./count0711/read.php',$path.'/read.php');
			// }
			// if(!file_exists($path.'/visits.db')){
				@copy('./count0711/visits.db',$path.'/visits.db');
			// }
			// if(!file_exists($path.'/mobile/index.php')){
				@copy('./count0711/mobile/index.php',$path.'/mobile/index.php');
			// }			
		}
	}
	closedir($dh);
}