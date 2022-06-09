<?php
require 'function.php';
require 'config.php';

$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$trash = "";

if (isset($_GET['trash'])) {
	$downloadDirectory = $trashD;
	$trash = "1";
}

$myDirectorySize = get_dir_size($downloadDirectory) + get_dir_size($trashD);
$dirArray = getDirContents($downloadDirectory);


$indexCount = isset($dirArray) ? count($dirArray) : 0;
echo pasteHeader($lang["Loader"] . " $mySite");

print("<TABLE>\r\n");
if ($trash == "1") {
	print("<TR><TH>" . $lang["Name"] . "</TH><th style=\"width:220px;\">" . $lang["Data"] . "</th><th style=\"width:120px;\"  >" . $lang["Size"] . "</th><th style=\"width:40px;\"  >" . $lang["Restore"] . "</th><th style=\"width:40px;\"  >" . $lang["Del"] . "</th></TR>\r\n");
} else {
	print("<TR><TH>" . $lang["Name"] . "</TH><th style=\"width:220px;\">" . $lang["Data"] . "</th><th style=\"width:120px;\"  >" . $lang["Size"] . "</th><th style=\"width:40px;\"  >" . $lang["Del"] . "</th></TR>\r\n");
}
for ($index = 0; $index < $indexCount; $index++) {
	if ((substr("$dirArray[$index]", 0, 1) != ".") and !is_dir($downloadDirectory . DIRECTORY_SEPARATOR . $dirArray[$index])) {  // don't list hidden files
		if ($trash == "1") {
			print("<TR><td style='text-align: left;'><a href='http://$mySite/trash/$dirArray[$index]'>$dirArray[$index]</a></td>");
			print("<td>" . date("Y-m-d H:i:s", filemtime($downloadDirectory . DIRECTORY_SEPARATOR . $dirArray[$index])) . "</td>");
			print("<td>" . filesize_formatted(filesize($downloadDirectory . DIRECTORY_SEPARATOR . $dirArray[$index])) . "</td>");
			print("<td><a href='http://$mySite/del.php?trash=restore&file=$dirArray[$index]'>R</a></td>\r\n");
			print("<td><a href='http://$mySite/del.php?trash=yes&file=$dirArray[$index]'>X</a></td></TR>\r\n");
		} else {
			print("<TR><td style='text-align: left;'><a href='http://$mySite/downloads/$dirArray[$index]'>$dirArray[$index]</a></td>");
			print("<td>" . date("Y-m-d H:i:s", filemtime($downloadDirectory . DIRECTORY_SEPARATOR . $dirArray[$index])) . "</td>");
			print("<td>" . filesize_formatted(filesize($downloadDirectory . DIRECTORY_SEPARATOR . $dirArray[$index])) . "</td>");
			print("<td><a href='http://$mySite/del.php?file=$dirArray[$index]'>X</a></td></TR>\r\n");
		}
	}
}
$percent = round(($myDirectorySize * 100 / disk_free_space("/")), 2);

print("</TABLE>\r\n <small>" . $lang["Actual on"] . ": " . date($lang["DataTimeFormat"]) . " || " .
	$lang["Busy"] . ": " . filesize_formatted($myDirectorySize) . " " . $lang["From"] . " " . filesize_formatted(disk_free_space("/")) . " (" . $percent . " % ) <a class='mini'>" . $lang["Count"] . $indexCount . "</a></small>");
print("<br> <progress max='" . disk_free_space("/") . "' value='" . $myDirectorySize . "'></progress><br><br><H2>\r\n");
print("<a class='button' href='.'>" . $lang["Refresh"] . "</a> ");
if ($percent > 95) {
	print("<a class='button' href='index.php'>" . $lang["Add"] . " - NO FREE SPACE</a> ");
} else {
	print("<a class='button' href='d.php'>" . $lang["Add"] . "</a> ");
}
print("<a class='button' href='del.php?file=alllogs'>Del Logs</a> ");
if ($trash != "1") {
	print("<a class='button' href='index.php?trash=all'>Show Trash</a> ");
	print("<H1>" . $lang["Urls"] . ": </H1>\n");
	for ($index = 0; $index < $indexCount; $index++) {
		if ((substr("$dirArray[$index]", 0, 1) != ".") and !is_dir($downloadDirectory . DIRECTORY_SEPARATOR . $dirArray[$index])) {
			if (!endsWith($dirArray[$index], "_log.txt")) {
				print("http://$mySite/downloads/");
				print(str_replace("%2F", "/", rawurlencode($dirArray[$index])));
				print("<br><br>\r\n");
			}
		} else {
			print('<hr><br>=============== ');
			print($dirArray[$index]);
			print(" ===============<br><br>\r\n");
		}
	}
} else {
	print("<a class='button' href='del.php?trash=alltrash&file=alltrash'>DEL Trash</a> ");
}
echo "</html>";
