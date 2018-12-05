<?php
include 'config.php';
include 'function.php';
$ugetFile = rawurldecode($_GET[file]);
$file = $myDirectory.DIRECTORY_SEPARATOR.$ugetFile;

echo pasteHeader('Завантаження $mySite');
print ("<meta http-equiv='refresh' content='2; url=".$mySiteHttps."'></head><body><h1><a href='".$mySiteHttps."'>");
if (preg_match("/(^\.\.)|(\/)/iu", $ugetFile)) {
	header("HTTP/1.0 404 Not Found");
	print("HTTP/1.0 404 Not Found</a></h1>");
}
elseif (preg_match("/\.temp$/iu", $ugetFile)) {
        print("Неможливо видалити: $ugetFile</a><br>Ще триває завантаження</h1>");

}
else {
	if(file_exists($file)){
		unlink($file);
		print("Готово</a></h1><br><br>\r\n<small>Видалено: $ugetFile</small>");
	}
	else {
		print("Неможливо видалити: $ugetFile</a><br>Немає такого</h1>");
	}
}
print("\r\n</body>\r\n</html>");
?>
