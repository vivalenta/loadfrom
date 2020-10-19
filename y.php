<?php
include 'function.php';
include 'config.php';

$id = "";
$name = "";
$format = "";
$log = "";

if (isset($_GET['id'])) { $id = $_GET['id'];}
if (isset($_GET['name'])) { $name = $_GET['name'];}
if (isset($_GET['format'])) { $format = $_GET['format'];}
if (isset($_GET['log'])) { $log = $_GET['log'];}

$actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$errors = 0;

echo pasteHeader($lang["Loader"]." $mySite");
?>
<script type="text/javascript">
var myObj;
var uriA = window.location.href;
function addToList(item, index) {
	if (myObj[6][index] == "none") {
		document.getElementById("aformat").innerHTML += "<option value=" + item + ">" + myObj[4][index] + " - " + myObj[5][index] + " | "+ humanFileSize(myObj[3][index]) + "</option>";
	} else {
		document.getElementById("vformat").innerHTML += "<option value=" + item + ">" + myObj[4][index] + " - " + myObj[5][index] + " | "+ humanFileSize(myObj[3][index]) + "</option>";
	}
}

function UpdateSelect () {
	var vindex = myObj[1].indexOf(document.getElementById("vformat").options[document.getElementById("vformat").selectedIndex].value);
        var aindex = myObj[1].indexOf(document.getElementById("aformat").options[document.getElementById("aformat").selectedIndex].value);
	var audio = myObj[7][vindex];
	if (audio == "none" || vindex == -1) {
		document.getElementById("aformat").style.color = document.getElementById("vformat").style.color
		document.getElementById("aformat").disabled = false;
		if (vindex == -1) {
			document.getElementById("info").innerHTML = "<h3>Всього: " + humanFileSize(myObj[3][aindex]) + "</h3><br>" +
			"<h1><a href=" + uriA + "&format=" + document.getElementById("aformat").options[document.getElementById("aformat").selectedIndex].value + ">Download</a></h1>";

		} else {
			document.getElementById("info").innerHTML = "<small>" + myObj[4][vindex] + " - " + myObj[5][vindex] + " | " + humanFileSize(myObj[3][vindex]) +
			" + "  + myObj[4][aindex] + " - " + myObj[5][aindex] + " | " + humanFileSize(myObj[3][aindex]) +
			"</small><br><h3>Всього: " + humanFileSize((myObj[3][aindex] + myObj[3][vindex])) + "</h3><br>"
			+ "<h1><a href=" + uriA + "&format=" + document.getElementById("vformat").options[document.getElementById("vformat").selectedIndex].value
			+ "%2B" + document.getElementById("aformat").options[document.getElementById("aformat").selectedIndex].value + ">Download</a></h1>";
		}
	} else {
		document.getElementById("aformat").disabled = true;
		document.getElementById("aformat").style.color = "grey";
		document.getElementById("info").innerHTML = "<h3>Всього: " + humanFileSize((myObj[3][vindex])) + "</h3><br>"
		+ "<h1><a href=" + uriA + "&format=" + document.getElementById("vformat").options[document.getElementById("vformat").selectedIndex].value + ">Download</a></h1>";

	}

}

function GetInfo () {

var xmlhttp = new XMLHttpRequest();
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

var toHHMMSS = (secs) => {
    var sec_num = parseInt(secs, 10)
    var hours   = Math.floor(sec_num / 3600)
    var minutes = Math.floor(sec_num / 60) % 60
    var seconds = sec_num % 60

    return [hours,minutes,seconds]
        .map(v => v < 10 ? "0" + v : v)
        .filter((v,i) => v !== "00" || i > 0)
        .join(":")
}


GetInfo();
</script>

<body>
<div class="wrapper">
	<header class="header">
		<h2>Youtube</h2>
	</header><!-- .header-->

	<div class="middle">
		<div class="container">
			<main class="content">
<?php
	if ($format == "") {
	echo "<h2><strong>".$lang["Choose quality"]."</strong></h2>\r\n";
	echo "<h3>".$lang["Avitable quality"].": </h3><br>\r\n";
echo('<select id="vformat" ONCHANGE="UpdateSelect();"></select>');
echo('<select id="aformat" ONCHANGE="UpdateSelect();"></select>');
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
		echo $lang["File name"].": $name - $format<br><br>";
		$Command = 'youtube-dl -f '.$format.' -o "'.$myDirectory.DIRECTORY_SEPARATOR.$name.'.%(ext)s" '.$id;
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
	echo "<br><div id=info>".$lang["ERROR"]."</div>";
}
?>

</main><!-- .content -->
		</div><!-- .container-->

		<aside class="left-sidebar">
<?php
echo ($lang["Video Name"].": <a id=vname></a><br>\r\n");
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
