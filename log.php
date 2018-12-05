<?php
include 'function.php';
include 'config.php';
include 'ip.php';
if ($_GET['api'] == $pass) {
	if (!empty($_GET['f'])) {$filter = "|grep ".$_GET['f'];}
	echo pasteHeader("Log $mySite");
        exec("cat $logfile|grep -v favicon.ico|grep -v style.css|grep -v deluge $filter 2>&1", $result);
        if ($_GET['m'] == 'raw'){
		var_dump($result);
	}
	else {
		print("<TABLE>\r\n");
	        print("<TR><th width=130px>IP</th><th width=205px>Дата</th><th width=150px>Дія</th><th>Що</th></TR>\r\n");
		foreach ($result as $format) {
			preg_match('/^([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}) - -( *)\[([0-9]{1,2}\/(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\/[0-9]{4}:[0-9]{2}:[0-9]{2}:[0-9]{2}) \+0000\] ("GET \/)(.*)((d|del|u)\.php\?(file|url)=)(.*) HTTP\/2.0" 200/ui', $format, $output_array);
			if ($output_array[3] != ''){
				print("<TR><td>".getNameFromIp($output_array[1])."</td><td>$output_array[3]</td><td>".workNames($output_array[8])."</td><td style='text-align: left;'>".urldecode($output_array[10])."</td></tr>");
			}
		}
	}
}
?>
