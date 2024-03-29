<?php
include 'config.php';
function pasteHeader($title){
return "<!DOCTYPE html>\r\n<meta http-equiv='content-type' content='text/html; charset=utf-8' />\r\n<title>$title</title>\r\n<link rel='stylesheet' href='style.css'>\r\n";
}

function filesize_formatted($size){
    $units = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $power = $size > 0 ? floor(log($size, 1024)) : 0;
    return number_format($size / pow(1024, $power), 3, '.', ',') . ' ' . $units[$power];
}

function getDirContents($directoryF, $original = ''){
	if (empty($original)) $original = $directoryF;
	$files = scandir($directoryF);
	foreach($files as $key => $value){
		$path = realpath($directoryF.DIRECTORY_SEPARATOR.$value);
		if(!is_dir($path)) {
			$results[] = str_replace($original.DIRECTORY_SEPARATOR,"", $path);
		} elseif($value != "." && $value != "..") {
			$results[] = str_replace($original.DIRECTORY_SEPARATOR,"", $path);
			$results = array_merge($results, getDirContents($path, $original));
		}
	}
    if (isset($results)) return $results;
    else return null;
}

function GetVideoSourceUrl($videoURL){
    $YtVideoID = explode('v=', $videoURL);
    $YtVideoID = end($YtVideoID);
    $results = substr($YtVideoID, 0, 11);
    if (isset($results)) return $results;
    else return null;
}

function GetVideoSourceUrl1($videoURL){
    $YtVideoID = explode('v=', $videoURL);
    $YtVideoID = end($YtVideoID);
    $YtVideoID = substr($YtVideoID, 0, 11);
    $Links = array();
    $opts = array(
      'http'=>array(
        'method'=>"GET",
        'header'=>"Accept-language: en\r\n" .
                  "Cookie: foo=bar\r\n"
      )
    );

    $context = stream_context_create($opts);
    $Source = file_get_contents('https://www.youtube.com/get_video_info?&video_id='.$YtVideoID.'&hl=en', false, $context);
    parse_str($Source,$Results2);
	$Results2['player_response'] = isset($Results2['player_response'])?$Results2['player_response']:false;
	$Results = json_decode($Results2['player_response'], true);
	$Title = $Results['videoDetails']["title"];
	if(isset($Results['streamingData']['adaptiveFormats'])){
	    $UrlInformation = $Results['streamingData']['adaptiveFormats'];
	    foreach($UrlInformation as $VideoInformation){
	        if (isset($VideoInformation['audioChannels']) && $VideoInformation['audioChannels']){
	            $VideoUrl = urldecode($VideoInformation['url']);
	            $Formats[] = $VideoInformation['itag'];
	            $Links[] = urlencode($VideoUrl);
	            $size[] = filesize_formatted($VideoInformation['contentLength']);
	            $mimetypes[] = explode("/", explode(";", $VideoInformation['mimeType'])[0])[1];
	        }
	    }
	}
	if(isset($Results['streamingData']['formats'])){
	    $UrlInformation = $Results['streamingData']['formats'];
	    foreach($UrlInformation as $VideoInformation){
	        $VideoUrl = urldecode($VideoInformation['url']);
	        $Formats[] = $VideoInformation['itag'];
	        if (isset($VideoUrl)) { $Links[] = urlencode($VideoUrl); } else { $Links[] = ""; }
	        if (isset($VideoInformation['contentLength'])) { $size[] = filesize_formatted($VideoInformation['contentLength']); } else { $size[] = 0;}
	        if (isset( $VideoInformation['qualityLabel'])) { $bitrate[] = $VideoInformation['qualityLabel']." (".filesize_formatted($VideoInformation['bitrate'])."/s)"; } else { $bitrate[] = ""; }
	        $mimetypes[] = explode("/", explode(";", $VideoInformation['mimeType'])[0])[1];
	    }
	}
	if (isset($Title) && isset($Formats) && isset($Links) && isset($size) && isset($mimetypes)) return array($Title, $Formats, $Links, $size, $mimetypes);
	else return null;
}


function formatName($string) {
  $q = chr(226).chr(128).chr(179); $s = chr(226).chr(128).chr(178);
  $chars = array(
  chr(195).chr(128)=>'A', chr(195).chr(129)=>'A', chr(195).chr(130)=>'A',
  chr(195).chr(131)=>'A', chr(195).chr(132)=>'A', chr(195).chr(133)=>'A',
  chr(195).chr(135)=>'C', chr(195).chr(136)=>'E', chr(195).chr(137)=>'E',
  chr(195).chr(138)=>'E', chr(195).chr(139)=>'E', chr(195).chr(140)=>'I',
  chr(195).chr(141)=>'I', chr(195).chr(142)=>'I', chr(195).chr(143)=>'I',
  chr(195).chr(145)=>'N', chr(195).chr(146)=>'O', chr(195).chr(147)=>'O',
  chr(195).chr(148)=>'O', chr(195).chr(149)=>'O', chr(195).chr(150)=>'O',
  chr(195).chr(153)=>'U', chr(195).chr(154)=>'U', chr(195).chr(155)=>'U',
  chr(195).chr(156)=>'U', chr(195).chr(157)=>'Y', chr(195).chr(159)=>'s',
  chr(195).chr(160)=>'a', chr(195).chr(161)=>'a', chr(195).chr(162)=>'a',
  chr(195).chr(163)=>'a', chr(195).chr(164)=>'a', chr(195).chr(165)=>'a',
  chr(195).chr(167)=>'c', chr(195).chr(168)=>'e', chr(195).chr(169)=>'e',
  chr(195).chr(170)=>'e', chr(195).chr(171)=>'e', chr(195).chr(172)=>'i',
  chr(195).chr(173)=>'i', chr(195).chr(174)=>'i', chr(195).chr(175)=>'i',
  chr(195).chr(177)=>'n', chr(195).chr(178)=>'o', chr(195).chr(179)=>'o',
  chr(195).chr(180)=>'o', chr(195).chr(181)=>'o', chr(195).chr(182)=>'o',
  chr(195).chr(182)=>'o', chr(195).chr(185)=>'u', chr(195).chr(186)=>'u',
  chr(195).chr(187)=>'u', chr(195).chr(188)=>'u', chr(195).chr(189)=>'y',
  chr(195).chr(191)=>'y',
  chr(196).chr(128)=>'A', chr(196).chr(129)=>'a', chr(196).chr(130)=>'A',
  chr(196).chr(131)=>'a', chr(196).chr(132)=>'A', chr(196).chr(133)=>'a',
  chr(196).chr(134)=>'C', chr(196).chr(135)=>'c', chr(196).chr(136)=>'C',
  chr(196).chr(137)=>'c', chr(196).chr(138)=>'C', chr(196).chr(139)=>'c',
  chr(196).chr(140)=>'C', chr(196).chr(141)=>'c', chr(196).chr(142)=>'D',
  chr(196).chr(143)=>'d', chr(196).chr(144)=>'D', chr(196).chr(145)=>'d',
  chr(196).chr(146)=>'E', chr(196).chr(147)=>'e', chr(196).chr(148)=>'E',
  chr(196).chr(149)=>'e', chr(196).chr(150)=>'E', chr(196).chr(151)=>'e',
  chr(196).chr(152)=>'E', chr(196).chr(153)=>'e', chr(196).chr(154)=>'E',
  chr(196).chr(155)=>'e', chr(196).chr(156)=>'G', chr(196).chr(157)=>'g',
  chr(196).chr(158)=>'G', chr(196).chr(159)=>'g', chr(196).chr(160)=>'G',
  chr(196).chr(161)=>'g', chr(196).chr(162)=>'G', chr(196).chr(163)=>'g',
  chr(196).chr(164)=>'H', chr(196).chr(165)=>'h', chr(196).chr(166)=>'H',
  chr(196).chr(167)=>'h', chr(196).chr(168)=>'I', chr(196).chr(169)=>'i',
  chr(196).chr(170)=>'I', chr(196).chr(171)=>'i', chr(196).chr(172)=>'I',
  chr(196).chr(173)=>'i', chr(196).chr(174)=>'I', chr(196).chr(175)=>'i',
  chr(196).chr(176)=>'I', chr(196).chr(177)=>'i', chr(196).chr(178)=>'IJ',
  chr(196).chr(179)=>'ij',chr(196).chr(180)=>'J', chr(196).chr(181)=>'j',
  chr(196).chr(182)=>'K', chr(196).chr(183)=>'k', chr(196).chr(184)=>'k',
  chr(196).chr(185)=>'L', chr(196).chr(186)=>'l', chr(196).chr(187)=>'L',
  chr(196).chr(188)=>'l', chr(196).chr(189)=>'L', chr(196).chr(190)=>'l',
  chr(196).chr(191)=>'L', chr(197).chr(128)=>'l', chr(197).chr(129)=>'L',
  chr(197).chr(130)=>'l', chr(197).chr(131)=>'N', chr(197).chr(132)=>'n',
  chr(197).chr(133)=>'N', chr(197).chr(134)=>'n', chr(197).chr(135)=>'N',
  chr(197).chr(136)=>'n', chr(197).chr(137)=>'N', chr(197).chr(138)=>'n',
  chr(197).chr(139)=>'N', chr(197).chr(140)=>'O', chr(197).chr(141)=>'o',
  chr(197).chr(142)=>'O', chr(197).chr(143)=>'o', chr(197).chr(144)=>'O',
  chr(197).chr(145)=>'o', chr(197).chr(146)=>'OE',chr(197).chr(147)=>'oe',
  chr(197).chr(148)=>'R', chr(197).chr(149)=>'r', chr(197).chr(150)=>'R',
  chr(197).chr(151)=>'r', chr(197).chr(152)=>'R', chr(197).chr(153)=>'r',
  chr(197).chr(154)=>'S', chr(197).chr(155)=>'s', chr(197).chr(156)=>'S',
  chr(197).chr(157)=>'s', chr(197).chr(158)=>'S', chr(197).chr(159)=>'s',
  chr(197).chr(160)=>'S', chr(197).chr(161)=>'s', chr(197).chr(162)=>'T',
  chr(197).chr(163)=>'t', chr(197).chr(164)=>'T', chr(197).chr(165)=>'t',
  chr(197).chr(166)=>'T', chr(197).chr(167)=>'t', chr(197).chr(168)=>'U',
  chr(197).chr(169)=>'u', chr(197).chr(170)=>'U', chr(197).chr(171)=>'u',
  chr(197).chr(172)=>'U', chr(197).chr(173)=>'u', chr(197).chr(174)=>'U',
  chr(197).chr(175)=>'u', chr(197).chr(176)=>'U', chr(197).chr(177)=>'u',
  chr(197).chr(178)=>'U', chr(197).chr(179)=>'u', chr(197).chr(180)=>'W',
  chr(197).chr(181)=>'w', chr(197).chr(182)=>'Y', chr(197).chr(183)=>'y',
  chr(197).chr(184)=>'Y', chr(197).chr(185)=>'Z', chr(197).chr(186)=>'z',
  chr(197).chr(187)=>'Z', chr(197).chr(188)=>'z', chr(197).chr(189)=>'Z',
  chr(197).chr(190)=>'z', chr(197).chr(191)=>'s',
  chr(208).chr(144)=>'A', chr(208).chr(176)=>'a', chr(208).chr(145)=>'B',
  chr(208).chr(177)=>'b', chr(208).chr(146)=>'V', chr(208).chr(178)=>'v',
  chr(208).chr(147)=>'G', chr(208).chr(179)=>'g', chr(208).chr(148)=>'D',
  chr(208).chr(180)=>'d', chr(208).chr(149)=>'E', chr(208).chr(181)=>'e',
  chr(208).chr(129)=>'Jo',chr(209).chr(145)=>'jo',chr(208).chr(150)=>'Zh',
  chr(208).chr(182)=>'zh',chr(208).chr(151)=>'Z', chr(208).chr(183)=>'z',
  chr(208).chr(152)=>'I', chr(208).chr(184)=>'i', chr(208).chr(153)=>'Jj',
  chr(208).chr(185)=>'jj',chr(208).chr(154)=>'K', chr(208).chr(186)=>'k',
  chr(208).chr(155)=>'L', chr(208).chr(187)=>'l', chr(208).chr(156)=>'M',
  chr(208).chr(188)=>'m', chr(208).chr(157)=>'N', chr(208).chr(189)=>'n',
  chr(208).chr(158)=>'O', chr(208).chr(190)=>'o', chr(208).chr(159)=>'P',
  chr(208).chr(191)=>'p', chr(208).chr(160)=>'R', chr(209).chr(128)=>'r',
  chr(208).chr(161)=>'S', chr(209).chr(129)=>'s', chr(208).chr(162)=>'T',
  chr(209).chr(130)=>'t', chr(208).chr(163)=>'U', chr(209).chr(131)=>'u',
  chr(208).chr(164)=>'F', chr(209).chr(132)=>'f', chr(208).chr(165)=>'Kh',
  chr(209).chr(133)=>'kh',chr(208).chr(166)=>'C', chr(209).chr(134)=>'c',
  chr(208).chr(167)=>'Ch',chr(209).chr(135)=>'ch',chr(208).chr(168)=>'Sh',
  chr(209).chr(136)=>'sh',chr(208).chr(169)=>'Shh',chr(209).chr(137)=>'shh',
  chr(208).chr(170)=>$q,  chr(209).chr(138)=>$q,  chr(208).chr(171)=>'Y',
  chr(209).chr(139)=>'y', chr(208).chr(172)=>$s,  chr(209).chr(140)=>$s,
  chr(208).chr(173)=>'Eh',chr(209).chr(141)=>'eh',chr(208).chr(174)=>'Ju',
  chr(209).chr(142)=>'ju',chr(208).chr(175)=>'Ya',chr(209).chr(143)=>'ja');
  $string = strtr($string, $chars);
  $string = trim(preg_replace('/[^a-zA-Z0-9-_!().]/ui', ' ', $string));
  return $string;
}

function makeRandomString($max=12) {
    $i = 0; //Reset the counter.
    $possible_keys = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $keys_length = strlen($possible_keys);
    $str = ""; //Let's declare the string, to add later.
    while($i<$max) {
        $rand = mt_rand(1,$keys_length-1);
        $str.= $possible_keys[$rand];
        $i++;
    }
    return $str;
}

function get_dir_size($directory) {
    $size = 0;
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS)) as $file) {
        $size += $file->getSize();
    }
    return $size;
}


function endsWith($haystack, $needle) {
    $length = strlen($needle);
    if ($length == 0) return true;
    return (substr($haystack, -$length) === $needle);
}

function getFromYDL($id) {
    $outArray = json_decode(shell_exec("python3 /usr/local/bin/youtube-dl -j $id"), true);
	$Title = $outArray['title'];
	foreach($outArray["formats"] as $information){
	    $VideoUrl[] = urldecode($information['url']);
	    $Formats[] = explode(" ", $information['format'])[0];
	    if ($information['acodec'] != 'none') {
	        $descr[] = explode("-", $information['format'])[1];
	    } else {
	        $descr[] = explode("-", $information['format'])[1];
	    }
	    $size[] = $information['filesize'];
	    $mmetype[] = $information['ext'];
	    $vcodec[] = $information['vcodec'];
	    $acodec[] = $information['acodec'];
	}
	return array($Title, $Formats, $VideoUrl, $size, $mmetype, $descr, $vcodec, $acodec);
}
