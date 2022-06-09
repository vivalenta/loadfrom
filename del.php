<?php
include 'config.php';
include 'function.php';
$trash = "";
$FileName = "";
if (isset($_GET['trash'])) {
	$downloadDirectory = $trashD;
	$trash = $_GET['trash'];
}
if (isset($_GET['file'])) {
	$FileName = rawurldecode($_GET['file']);
	$fileD = $downloadDirectory . DIRECTORY_SEPARATOR . $FileName;
}
$yes = "";
if (isset($_GET['yes'])) {
	$yes = $_GET['yes'];
}
$actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

echo pasteHeader($lang["Loader"] . " $mySite");
print("<meta http-equiv='refresh' content='5; url=" . $mySiteHttps . "'></head><body><h1><a href='" . $mySiteHttps . "'>");

if (isset($_GET['file'])) {
	if ($trash == "alltrash") {
		if ($yes == "yes") {
			$mask = $downloadDirectory . DIRECTORY_SEPARATOR . '*';
			array_map('unlink', glob($mask));
			print($lang["Done"] . "</a></h1><br><br>\r\n<small>" . $lang["Deleted"] . ": All Trash</small>");
		} else {
			print("</head><body><h1>Delete ALL Trash<br></h1>");
			print("<a class='button' href='del.php?trash=alltrash&yes=yes&file=trash'>Yes</a> ");
			print("<a class='button' href='index.php'>No</a> ");
		}
	} elseif ($FileName == "alllogs") {
		if ($yes == "yes") {
			$mask = $downloadDirectory . DIRECTORY_SEPARATOR . '*_log.txt';
			array_map('unlink', glob($mask));
			print($lang["Done"] . "</a></h1><br><br>\r\n<small>" . $lang["Deleted"] . ": All Logs</small>");
		} else {
			print("</head><body><h1>Delete ALL logs<br></h1>");
			print("<a class='button' href='del.php?file=alllogs&yes=yes'>Yes</a> ");
			print("<a class='button' href='index.php'>No</a> ");
		}
	} elseif (file_exists($fileD)) {
		if ($trash == "yes") {
			unlink($fileD);
		} else {
			rename($fileD, $trashD . DIRECTORY_SEPARATOR . $FileName);
		}
		print($lang["Done"] . "</a></h1><br><br>\r\n<small>" . $lang["Deleted"] . ": $FileName</small>");
	} else {
		print($lang["Can not be deleted"] . ": $FileName</a><br>" . $lang["does not exist"] . "</h1>");
	}
} else {
	print($lang["Can not be deleted"] . ": $FileName</a><br>" . $lang["does not exist"] . "</h1>");
}

print("\r\n</body>\r\n</html>");
