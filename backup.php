<?php
include 'function.php';
include 'config.php';
echo pasteHeader("$mySite");

if ($_GET['api'] == $pass) {
	if ($_GET['m'] == 'full'){
		$Command = 'tar cvfz "'.$myDirectory.DIRECTORY_SEPARATOR.'backup_$(date +"%Y-%m-%d").tar.gz" "'.getcwd().DIRECTORY_SEPARATOR.'"';
	}
	else {
		$Command = 'tar cvfz "'.$myDirectory.DIRECTORY_SEPARATOR.'backup_$(date +"%Y-%m-%d").tar.gz" --exclude="'.$myDirectory.DIRECTORY_SEPARATOR.'*" "'.getcwd().DIRECTORY_SEPARATOR.'"';
	}
	exec( $Command.' > /dev/null &' );
        echo 'yes';

}
?>
