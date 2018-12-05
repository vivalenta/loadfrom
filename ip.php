<?php

function getNameFromIp($iTag){
	switch ($iTag) {
                case '192.168.1.1': $Message = 'U00V';break;
                case '46.0.0.1': $Message = 'U00W';break;
                case '5.0.0.254':$Message = 'U01';break;
		default:$Message = preg_replace("/([0-9]{1,3}.[0-9]{1,3})$/", "xxx.xxx", $iTag);break;
        }
        return $Message;

}

?>
