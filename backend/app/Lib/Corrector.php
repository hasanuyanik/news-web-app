<?php
namespace App\Lib;

class Corrector
{
    public function charCorrectorSentenceToUrl(string $val): string
    {
        $strLength = strlen($val);
        $SentenceChar = array(' ','Ç','Ğ','İ','Ö','Ş','Ü','ç','ğ','ı','ö','ş','ü',',',';',':','/','@','#','?','=','©','&','"');
        $UniChar = array('-','C','G','I','O','S','U','c','g','i','o','s','u','-','-','-','-','@','#','','','','','');

        $result = str_replace($SentenceChar,$UniChar,$val);

        return $result;
    }

    public function charCorrectorTr(string $val): string
    {
        $strLength = strlen($val);
        $jqueryChar = array('%20','%C3%87','%C4%9E','%C4%B0','%C3%96','%C5%9E','%C3%9C','%C3%A7','%C4%9F','%C4%B1','%C3%B6','%C5%9F','%C3%BC','%2C','%3B','%3A','%2F','%40','%23','%3F','%3D','%C2%A9','%26','%22');
        $trChar = array(' ','Ç','Ğ','İ','Ö','Ş','Ü','ç','ğ','ı','ö','ş','ü',',',';',':','/','@','#','?','=','©','&','"');

        $result = str_replace($jqueryChar,$trChar,$val);

        return $result;
    }
    public function charCorrectorEmbed(string $val): string
    {
        $strLength = strlen($val);
        $jqueryChar = array('%20','%C3%87','%C4%9E','%C4%B0','%C3%96','%C5%9E','%C3%9C','%C3%A7','%C4%9F','%C4%B1','%C3%B6','%C5%9F','%C3%BC','%3C','%3D','%22','%3A','%2F','%3B','%3E','%3F','&lt;','&gt;','&quot;');
        $trChar = array(' ','Ç','Ğ','İ','Ö','Ş','Ü','ç','ğ','ı','ö','ş','ü','<','=','"',':','/',';','>','?','<','>','"');

        $result = str_replace($jqueryChar,$trChar,$val);

        return $result;
    }
    public function charCorrectorForFile(string $val): string
    {
        $strLength = strlen($val);
        $jqueryChar = array('%20','%C3%87','%C4%9E','%C4%B0','%C3%96','%C5%9E','%C3%9C','%C3%A7','%C4%9F','%C4%B1','%C3%B6','%C5%9F','%C3%BC');
        $trChar = array(' ','Ç','Ğ','İ','Ö','Ş','Ü','ç','ğ','ı','ö','ş','ü');
        $fileChar = array(' ','C','G','I','O','S','U','c','g','i','o','s','u');
        $symbolsBefore = array(",","'","#","!","%","/","&","(",")","=","?","*","{","}","[","]","\"",".",'"');
        $symbolsAfter = array("","","","","","","","","","","","","","","","","","","");
        $result = str_replace($jqueryChar,$fileChar,$val);
        $result = str_replace($trChar,$fileChar,$result);
        $result = str_replace($symbolsBefore,$symbolsAfter,$result);

        return $result;
    }
}