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
	//print_r($Results[5]);
        $Title = $Results[0];
        echo ($lang["Video Name"].": ".$Title."<br>\r\n");
	$SourceFormats = $Results[1];
	$SourceFormat = '';
	$i = -1;
        foreach ($Results[1] as $key => $Sonuc) {
		$i++;
		if (preg_match('/([0-9]{2,3}$)/',$Sonuc)) {
			//print_r($Results[1][i]);
			$SourceFormat .= "<a class='button' href='$actual_link&format=$Sonuc&log=on&name=".urlencode(formatName($Title)).".mp4'>".Qualitys($Sonuc)." (".$Results[3][$i].")</a>,  ";
			$SourceFormat .= "<a class='button' href='http://$_SERVER[HTTP_HOST]/d.php?url=".$Results[2][$i]."&log=on&name=".urlencode(formatName($Title)).".".$Results[4][$i]."'>ALTERA ".Qualitys($Sonuc)." (".$Results[3][$i]."</a><br>  \r\n";
		}
	}

	$SourceFiles = $Results[2];
        $SourceFile = '';
	echo ("<img src='http://img.youtube.com/vi/".$founded[1]."/mqdefault.jpg' alt='Пхото'/><br>\r\n");
	if ($format == "") {
	echo "<h1><strong>".$lang["Choose quality"]."</strong></h1><br><h3>".$lang["Avitable quality"].": </h3><br>\r\n";
	echo ($SourceFormat." <a class='button' href='$actual_link&format=000'>".$lang["Best"]."</a><br>");
	echo "<br>\r\n".$SourceFile."<br>\r\n";
	$errors = 1;
	}
	else {
		echo "<h3>".$lang["Selected quality"].": <strong>".Qualitys($format)."</strong></h3><br>\r\n";
		if (trim($name) != ''){
			$name = formatName($name);
		}
		elseif (trim(formatName($Title)) != ''){
			$name = formatName($Title);
		}
		else {
			$name = formatName($founded[1]);
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
elseif (preg_match("/^https?:\/\/.*\.(m3u8)$/i", $url)) {
	echo "M3U8<br>\r\n";
	if ($name == '') {
		echo $lang["The name is not specified, the random name will be used"]." <br><br>";
		$name =  makeRandomString();
	}
	$name = formatName($name);
	$filename = $myDirectory.DIRECTORY_SEPARATOR.$name;
	$Command = 'ffmpeg -i '.$url.' -bsf:a aac_adtstoasc -c copy "'.$filename.'.mp4"';
}
elseif (preg_match('/^https:\/\/soundcloud.com*/iu', $url)) {
	$sckey = "6QvdRwJ2FQEAWlMafWqRjnI9hsdVKNeE";
        $url_api='https://api.soundcloud.com/resolve.json?url='.$url.'&client_id='.$sckey;
	$json = file_get_contents($url_api);
        $obj=json_decode($json);
	//var_dump($obj);
        echo ("<img src='".str_replace("large", "crop", $obj->artwork_url)."' title='".str_replace("'", "",$obj->description)."'/><br><br>\r\n\r\n");
        if($obj->kind=='playlist'){
                $index=0;
                foreach ($obj->tracks as $key) {
                        $index++;
                        $name = formatName($key->title).'.mp3';
	                $Command = $Command.' wget -O "'.$myDirectory.DIRECTORY_SEPARATOR.$name.'" "'.$key->stream_url.'?client_id='.$sckey.'" &';
			echo "<a href='".$key->stream_url."?client_id=".$sckey."'>".$key->title.'</a><br><br>';
		}
        } else {
		$name = formatName($obj->title).'.mp3';
		$Command = 'wget -O "'.$myDirectory.DIRECTORY_SEPARATOR.$name.'" "'.$obj->stream_url.'?client_id='.$sckey.'"';
		echo "<a href='".$obj->stream_url."?client_id=".$sckey."'>".$obj->title."</a><br><br><br>\r\n";
	}
	if(!isset($_GET["donwload"])){ $errors = 1; echo ("<a class='button' href='$actual_link&donwload=yes'>".$lang["download"]."</a><br>"); }
}
elseif (preg_match('/^https?:\/\/.*/', $url)) {
        echo "HTTP<br>\r\n";
        $Command = 'wget --progress=bar -P '.$myDirectory.DIRECTORY_SEPARATOR.' '.$url;
	if (trim($name) != ''){
		echo lang["File name"].": $name<br><br>";
		$Command = 'wget --progress=bar -O "'.$myDirectory.DIRECTORY_SEPARATOR.$name.'" "'.$url.'"';
	} else {$name = basename($url);}
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
		exec( $Command.' > "'.$myDirectory.DIRECTORY_SEPARATOR.$name.'"_log.txt 2>&1 &' );
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
echo "<H2><a class='button' href='$mySiteHttps'>".$lang["Home"]."</a></H2><br>\r\n";

?>
