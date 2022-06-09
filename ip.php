<?php

function getNameFromIp($iTag){
	switch ($iTag) {
                case '192.168.137.1': $Message = 'U00V';break;
                case '46.175.16.186': $Message = 'U00W';break;
        case '178.133.111.199':
        case '46.133.40.254':
        case '46.133.23.163':$Message = 'U01';break;
        case '46.133.73.139':
        case '46.133.143.16':
        case '46.133.13.26':$Message = 'U00';break;
        default:$Message = preg_replace("/([0-9]{1,3}.[0-9]{1,3})$/", "xxx.xxx", $iTag);break;
        }
        return $Message;

}

?>
