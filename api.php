<?php
include 'function.php';
include 'config.php';
$CacheDir = '/var/www/hard.wuddi.in.ua/cache';
$id = "";

if (isset($_GET['id'])) { $id = $_GET['id'];}

if (file_exists($CacheDir.DIRECTORY_SEPARATOR.$id)) {
        echo file_get_contents($CacheDir.DIRECTORY_SEPARATOR.$id);
} else {
	$Results = json_encode(getFromYDL($id));
	file_put_contents($CacheDir.DIRECTORY_SEPARATOR.$id, $Results);
	echo $Results;
}
?>
