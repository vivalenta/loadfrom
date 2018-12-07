<?php
include 'config.php';
include 'function.php';
$ugetFile = rawurldecode($_GET[file]);
$file = $myDirectory.DIRECTORY_SEPARATOR.$ugetFile;
echo pasteHeader($lang["Loader"]." $mySite");
print ("<meta http-equiv='refresh' content='5; url=".$mySiteHttps."'></head><body><h1><a href='".$mySiteHttps."'>");
if (preg_match("/(^\.\.)|(\/)/iu", $ugetFile)) {
	header("HTTP/1.0 404 Not Found");
	print("HTTP/1.0 404 Not Found</a></h1>");
}
else {
	if(file_exists($file)){
		unlink($file);
		print($lang["Done"]."</a></h1><br><br>\r\n<small>".$lang["Deleted"].": $ugetFile</small>");
	}
	else {
		print($lang["Can not be deleted"].": $ugetFile</a><br>".$lang["does not exist"]."</h1>");
	}
}
print("\r\n</body>\r\n</html>");
?>
