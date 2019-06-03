<?php
include 'config.php';
include 'function.php';
$ugetFile = rawurldecode($_GET[file]);
$fileD = $myDirectory.DIRECTORY_SEPARATOR.$ugetFile;
echo pasteHeader($lang["Loader"]." $mySite");
print ("<meta http-equiv='refresh' content='5; url=".$mySiteHttps."'></head><body><h1><a href='".$mySiteHttps."'>");
	if(file_exists($fileD)){
		//unlink($file);
		rename($fileD,'/var/www/load.wuddi.in.ua/trash/'.$ugetFile);
		print($lang["Done"]."</a></h1><br><br>\r\n<small>".$lang["Deleted"].": $ugetFile</small>");
	}
	else {
		print($lang["Can not be deleted"].": $ugetFile</a><br>".$lang["does not exist"]."</h1>");
	}

print("\r\n</body>\r\n</html>");
?>
