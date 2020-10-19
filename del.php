<?php
include 'config.php';
include 'function.php';
$trash = "";

if (isset($_GET['trash'])) { $myDirectory = $trashD; $trash = $_GET['trash'];}
$ugetFile = rawurldecode($_GET[file]);
$fileD = $myDirectory.DIRECTORY_SEPARATOR.$ugetFile;
$yes = "";
if (isset($_GET['yes'])) { $yes = $_GET['yes'];}
$actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

echo pasteHeader($lang["Loader"]." $mySite");
if (isset($_GET['file'])) {
	if(file_exists($fileD)){
		print ("<meta http-equiv='refresh' content='5; url=".$mySiteHttps."'></head><body><h1><a href='".$mySiteHttps."'>");
		//unlink($file);
		rename($fileD,$trashD.DIRECTORY_SEPARATOR.$ugetFile);
		print($lang["Done"]."</a></h1><br><br>\r\n<small>".$lang["Deleted"].": $ugetFile</small>");
	}
}
else {
	if ($ugetFile == "alllogs"){
		if ($yes == "yes"){
			print ("<meta http-equiv='refresh' content='5; url=".$mySiteHttps."'></head><body><h1><a href='".$mySiteHttps."'>");
			$mask = $myDirectory.DIRECTORY_SEPARATOR.'*_log.txt';
			array_map('unlink', glob($mask));
			print($lang["Done"]."</a></h1><br><br>\r\n<small>".$lang["Deleted"].": All Logs</small>");
		} else {
			print ("</head><body><h1>Delete ALL logs<br></h1>");
			print ("<a class='button' href='del.php?file=alllogs&yes=yes'>Yes</a> ");
			print ("<a class='button' href='index.php'>No</a> ");
		}
	} elseif (isset($_GET['trash'])) {
                if ($yes == "yes"){
                        print ("<meta http-equiv='refresh' content='5; url=".$mySiteHttps."'></head><body><h1><a href='".$mySiteHttps."'>");
                        $mask = $myDirectory.DIRECTORY_SEPARATOR.'*';
                        array_map('unlink', glob($mask));
                        print($lang["Done"]."</a></h1><br><br>\r\n<small>".$lang["Deleted"].": All Trash</small>");
                } else {
                        print ("</head><body><h1>Delete ALL Trash<br></h1>");
                        print ("<a class='button' href='del.php?trash=alltrash&yes=yes'>Yes</a> ");
                        print ("<a class='button' href='index.php'>No</a> ");
                }
	} else {
		print ("<meta http-equiv='refresh' content='5; url=".$mySiteHttps."'></head><body><h1><a href='".$mySiteHttps."'>");
		print($lang["Can not be deleted"].": $ugetFile</a><br>".$lang["does not exist"]."</h1>");
	}
}

print("\r\n</body>\r\n</html>");
?>
