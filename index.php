<?php
include 'function.php';
include 'config.php';
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$myDirectorySize = get_dir_size($myDirectory);
date_default_timezone_set('Europe/Kiev');
$dirArray = getDirContents($myDirectory);
$indexCount = count($dirArray);
echo pasteHeader($lang["Loader"]." $mySite");


print("<TABLE>\r\n");
print("<TR><TH>".$lang["Name"]."</TH><th width=200px >".$lang["Data"]."</th><th width=120px >".$lang["Size"]."</th><th width=40px >".$lang["Del"]."</th></TR>\r\n");
for($index=0; $index < $indexCount; $index++) {
       if ((substr("$dirArray[$index]", 0, 1) != "." ) and !is_dir($myDirectory.DIRECTORY_SEPARATOR.$dirArray[$index])){  // don't list hidden files
        print("<TR><td style='text-align: left;'><a href='http://$mySite/downloads/"); print $dirArray[$index];print("'>$dirArray[$index]</a></td>");
	    print("<td>");  print(date("Y-m-d H:i:s", filemtime ($myDirectory.DIRECTORY_SEPARATOR.$dirArray[$index]))); print("</td>");
        print("<td>");  print(filesize_formatted(filesize($myDirectory.DIRECTORY_SEPARATOR.$dirArray[$index]))); print("</td>");
        print("<td>");  print("<a href='http://$mySite/del.php?file=");print $dirArray[$index];print("'>X</a>"); print("</td>");
        print("</TR>\r\n");
    }
}
print("</TABLE>\r\n <small>".$lang["Actual on"].": ".date($lang["DataTimeFormat"])." || ".$lang["Busy"].": ".filesize_formatted($myDirectorySize)." ".$lang["From"]." ".filesize_formatted(107374182400)." (".round($myDirectorySize/1073741824, 2)." % )  <a class='mini'>".$lang["Free"]." ".filesize_formatted(disk_free_space("/"))."</a></small>");
print("<br> <progress max='107374182400' value='".$myDirectorySize."'></progress><br><br><H2>\r\n");
print ("<a class='button' href='.'>".$lang["Refresh"]."</a> ");
print ("<a class='button' href='d.php'>".$lang["Add"]."</a> ");
print ("<H1>".$lang["Urls"].": </H1>\n");

for($index=0; $index < $indexCount; $index++) {
	if ((substr("$dirArray[$index]", 0, 1) != "." ) and !is_dir($myDirectory.DIRECTORY_SEPARATOR.$dirArray[$index])){
		if (!endsWith($dirArray[$index],"_log.txt")) {
		print("http://$mySite/downloads/");	print(str_replace("%2F","/", rawurlencode($dirArray[$index])));	print("<br><br>\r\n");
		}
	}
	else { print('<hr><br>=============== '); print($dirArray[$index]); print(" ===============<br><br>\r\n"); }
}
echo "</html>";
?>
