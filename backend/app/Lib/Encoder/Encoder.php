<?php

namespace App\Lib\Encoder;

class Encoder implements EncoderI
{

    public function encode(mixed $data): string
    {
        $hash = hash("sha256",$data);
        return $hash;
    }

    public function salt(mixed $data): string
    {
        $saltText = str_split("TeknasyonBootcamp");
        $saltLength = count($saltText);
        $saltCount = 0;

        $data = str_split($data);
        $dataLength = count($data);

        $newData = "";

        for($i = 0; $i<$dataLength; $i++)
        {
            if($i%2 != 0 && $saltLength > $saltCount){
                $newData .= $saltText[$saltCount];
                $saltCount++;
            } else {
                $newData .= $data[$i];
            }
        }

        return $newData;
    }
}