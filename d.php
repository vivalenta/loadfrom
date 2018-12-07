<?php
include 'function.php';
include 'config.php';

$url = $_GET['url'];
$name = $_GET['name'];
$format = $_GET['format'];
$log =  $_GET['log'];
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$errors = 0;
echo pasteHeader($lang["Loader"]." $mySite");

if (preg_match('/^(?:https?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/i', $url, $founded)){
	echo "<h2>Youtube</h2><br>\r\n";
	echo ($lang["Video ID"].": ".formatName($founded[1])."<br>\r\n");
	$Results = GetVideoSourceUrl($founded[1]);
        $Title = $Results[0];
        echo ($lang["Video Name"].": ".$Title."<br>\r\n");
	$SourceFormats = $Results[1];
	$SourceFormat = '';
        foreach ($SourceFormats as $key => $Sonuc) {
		if (preg_match('/([0-9]{2}$)/',$Sonuc)) {
			$SourceFormat .= "<a class='button' href='$actual_link&format=$Sonuc'>".Qualitys($Sonuc)."</a>,  ";
		}
	}
	//$SourceFormat = trim($SourceFormat, ', ');
	if ($format == "") {
	echo "<h1><strong>".$lang["Choose quality"]."</strong></h1><br><h3>".$lang["Avitable quality"].": </h3><br>\r\n";
	echo ($SourceFormat." <a class='button' href='$actual_link&format=000'>".$lang["Best"]."</a><br>");
	$errors = 1;
	}
	else {
		echo "<h3>".$lang["Selected quality"].": <strong>".Qualitys($format)."</strong></h3><br>\r\n";
		if (trim($name) != ''){
			$name = formatName($name);
		}
		else {
			$name = formatName($Title);
		}
		echo $lang["File name"].": $name<br><br>";
		if ($format == '999'){
			$Command = 'youtube-dl -f "bestvideo+bestaudio/bestvideo+bestaudio" --merge-output-format webm -o "'.$myDirectory.DIRECTORY_SEPARATOR.$name.'.%(ext)s" '.$url;
		}
		elseif ($format == '000'){
			$Command = 'youtube-dl -o "'.$myDirectory.DIRECTORY_SEPARATOR.$name.'.%(ext)s" '.$url;
		}
		else{
			$Command = 'youtube-dl -f '.$format.' -o "'.$myDirectory.DIRECTORY_SEPARATOR.$name.'.%(ext)s" '.$url;
		}
	}
}
elseif (preg_match("/^https?:\/\/.*\.(m3u8)$/i", $url) ) {
	echo "M3U8<br>\r\n";
	if ($name == '') {
		echo $lang["The name is not specified, the random name will be used"]." <br><br>";
		$name =  makeRandomString();
	}
	$name = formatName($name);
	$filename = $myDirectory.DIRECTORY_SEPARATOR.$name;
	$Command = 'ffmpeg -i '.$url.' -bsf:a aac_adtstoasc -c copy "'.$filename.'.mp4"';
}
elseif (preg_match('/^https?:\/\/[a-z0-9_-]*?.googlevideo.com\/videoplayback\?/iu', $url)) {
	preg_match("/(?<=title\=)(?s)(.*$)/iu", $url, $matches);
        echo "GoogleVideo<br>\r\n";
	$name = formatName(urldecode($matches[0]));
        $Command = 'wget -O "'.$myDirectory.DIRECTORY_SEPARATOR.$name.'" "'.$url.'"';

}
elseif (preg_match('/^https?:\/\/.*/', $url)) {
        echo "HTTP<br>\r\n";
        $Command = 'wget -P '.$myDirectory.DIRECTORY_SEPARATOR.' '.$url;
	if (trim($name) != ''){
		echo lang["File name"].": $name<br><br>";
		$Command = 'wget -O "'.$myDirectory.DIRECTORY_SEPARATOR.$name.'" "'.$url.'"';
         }

}

else if ($url == '') {
echo "<!DOCTYPE html><meta charset='utf-8'><link rel='stylesheet' href='style.css'><html><body><div align='center' width='80%'><form action='d.php' method='get' enctype='multipart/form-data' style='width:600px; border: 4px double #2C5F10;'>".$lang["URL (https?://)"].":    <input type='url' name='url' id='url' required><br>".$lang["How to name it"].":             <input type='text' name='name' id='name'><br>                        ".$lang["Enable log"].":              <input type='checkbox' name='log' id='log'><br><input type='submit'> <input type='reset'> <br><br></form>        </div></body></html>";
	$errors = 255;
}

if (file_exists($name.'.mp4')) {
	echo $lang["Error: There is already exist"]."<br><br>";
	$errors = 1;
}

//echo $Command;
if ( $errors == 0) {
	if ($log == 'on'){
		exec( $Command.' > "'.$myDirectory.DIRECTORY_SEPARATOR.$name.'"_log.txt 2>&1' );
	}
	else {
		exec( $Command.' > /dev/null &' );
	}
	//echo $Command;
	echo '<br><font size="30" color="green">Ok!</font>';
}
else if ($errors == 255) {
}
else {
	echo "<br><font size='30' color='red'>".$lang["Error"]."</font>";
}
echo "<H2 align='center'><a class='button' href='$mySiteHttps'>".$lang["Home"]."</a></H2><br>\r\n";

?>
