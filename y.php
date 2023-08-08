<?php
require 'function.php';
require 'config.php';

if (isset($_GET['id'])) $id = $_GET['id'];
if (isset($_GET['name'])) { $name = $_GET['name']; } else { $name = ""; }
if (isset($_GET['format'])) { $format = $_GET['format']; } else { $format = ""; };
if (isset($_GET['log'])) { $log = $_GET['log'];  } else { $log = ""; }

$actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$errors = 0;

echo pasteHeader($lang["Loader"]." $mySite");
?>
<script type="text/javascript">
    let myObj;
    let uriA = window.location.href;

    function addToList(item, index) {
	if (myObj[6][index] === "none") {
		document.getElementById("aformat").innerHTML += "<option value=" + item + ">" + myObj[4][index] + " - " + myObj[5][index] + " | "+ humanFileSize(myObj[3][index]) + "</option>";
	} else {
		document.getElementById("vformat").innerHTML += "<option value=" + item + ">" + myObj[4][index] + " - " + myObj[5][index] + " | "+ humanFileSize(myObj[3][index]) + "</option>";
	}
}

function UpdateSelect () {
    let video_index = myObj[1].indexOf(document.getElementById("vformat").options[document.getElementById("vformat").selectedIndex].value);
    let audio_index = myObj[1].indexOf(document.getElementById("aformat").options[document.getElementById("aformat").selectedIndex].value);
    let audio = myObj[7][video_index];
    if (audio == "none" || video_index == -1) {
		document.getElementById("aformat").style.color = document.getElementById("vformat").style.color
		document.getElementById("aformat").disabled = false;
		if (video_index == -1) {
			document.getElementById("info").innerHTML = "<h3>Всього: " + humanFileSize(myObj[3][audio_index]) + "</h3><br>" +
			"<h1><a class='button' href=" + uriA + "&format=" + document.getElementById("aformat").options[document.getElementById("aformat").selectedIndex].value + ">Download</a></h1>";

		} else {
			document.getElementById("info").innerHTML = "<small>" + myObj[4][video_index] + " - " + myObj[5][video_index] + " | " + humanFileSize(myObj[3][video_index]) +
			" + "  + myObj[4][audio_index] + " - " + myObj[5][audio_index] + " | " + humanFileSize(myObj[3][audio_index]) +
			"</small><br><h3>Всього: " + humanFileSize((myObj[3][audio_index] + myObj[3][video_index])) + "</h3><br>"
			+ "<h1><a class='button' href=" + uriA + "&format=" + document.getElementById("vformat").options[document.getElementById("vformat").selectedIndex].value
			+ "%2B" + document.getElementById("aformat").options[document.getElementById("aformat").selectedIndex].value + ">Download</a></h1>";
		}
	} else {
		document.getElementById("aformat").disabled = true;
		document.getElementById("aformat").style.color = "grey";
		document.getElementById("info").innerHTML = "<h3>Всього: " + humanFileSize((myObj[3][video_index])) + "</h3><br>"
		+ "<h1><a href=" + uriA + "&format=" + document.getElementById("vformat").options[document.getElementById("vformat").selectedIndex].value + ">Download</a></h1>";

	}

}

function GetInfo () {

    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
		myObj = JSON.parse(this.responseText);
		document.getElementById("vname").innerText = myObj[0];
		uriA = window.location.href + "&name=" + encodeURI(myObj[0]) + "&log=on";
		myObj[1].forEach(addToList);
		document.getElementById("vformat").innerHTML += "<option value=0>No Video</option>";

  }
};
<?php
echo("xmlhttp.open('GET', 'api.php?id=" . $id . "' , true);");
?>
xmlhttp.send();
}

function humanFileSize(bytes, si=false, dp=1) {
  const thresh = si ? 1000 : 1024;

  if (Math.abs(bytes) < thresh) {
    return bytes + ' B';
  }

  const units = si
    ? ['kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']
    : ['KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];
  let u = -1;
  const r = 10**dp;

  do {
    bytes /= thresh;
    ++u;
  } while (Math.round(Math.abs(bytes) * r) / r >= thresh && u < units.length - 1);


  return bytes.toFixed(dp) + ' ' + units[u];
}

let toHMS = (secs) => {
    let sec_num = parseInt(secs, 10);
    let hours = Math.floor(sec_num / 3600);
    let minutes = Math.floor(sec_num / 60) % 60;
    let seconds = sec_num % 60;

    return [hours,minutes,seconds]
        .map(v => v < 10 ? "0" + v : v)
        .filter((v,i) => v !== "00" || i > 0)
        .join(":")
}


GetInfo();
</script>

<body>
<div class="wrapper">
	<header class="header"><h2>Youtube</h2></header>

	<div class="middle">
		<div class="container">
			<main class="content">
<?php
	if ($format == "") {
		echo "<h2><strong>".$lang["Choose quality"]."</strong></h2>\r\n";
		echo "<h3>".$lang["Available quality"].": </h3><br>\r\n";
		echo('<select id="vformat" ONCHANGE="UpdateSelect();"></select>');
		echo('<select id="aformat" ONCHANGE="UpdateSelect();"></select>');
		$errors = 1;
	}
	else {
		echo "<h3>".$lang["Selected quality"].": <strong>".$format."</strong></h3><br>\r\n";
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
		$Command = 'python3 /usr/local/bin/youtube-dl -f '.$format.' -o "'.$downloadDirectory.DIRECTORY_SEPARATOR.$name.'.%(ext)s" '.$id;
	}

if ( $errors == 0) {
	if ($log == 'on'){
		exec( $Command.' > "'.$downloadDirectory.DIRECTORY_SEPARATOR.$name.'"_log.txt 2>&1 &' );
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
	echo "<br><div class='button' id=info>".$lang["ERROR"]."</div>";
}
?>

</main><!-- .content -->
		</div><!-- .container-->

		<aside class="left-sidebar">
<?php
echo ($lang["Video Name"].": <p id=vname></p><br>\r\n");
echo ("\t\t<img src='https://i.ytimg.com/vi/".$id."/mqdefault.jpg' alt='Пхото'/><br>\r\n");
echo ("<p>" . $lang["Video ID"] . ": <a id=vid>".formatName($id)."</a></p><br>\r\n");
?>
</aside><!-- .left-sidebar -->

	</div><!-- .middle-->

</div><!-- .wrapper -->

<footer class="footer">
<?php
echo ("<H2><a class='button' href='$mySiteHttps'>".$lang["Home"]."</a></H2><br>\r\n");

?>
</footer><!-- .footer -->

</body>
</html>
