<?php

namespace App\Controller;

use App\Lib\Encoder\Encoder;
use App\Lib\FileManager\FileManager;

class UserController extends BaseController
{
    public function index()
    {
        ?>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="file" name="dosya"/>
            <input type="submit" value="Yükle">
        </form>
        <?php

        if($_FILES){
            $filemanager = new FileManager();



            $result = $filemanager->uploadFile($_FILES["dosya"],"News/".$_FILES["dosya"]["name"]);

            echo $result;
        }

    }

    public function show(string $name): void
    {
        /*
        echo "User:{$name}";

            $filemanager = new FileManager();
            $result = $filemanager->deleteFile("abc.jpg");

            echo $result;
        */

        $encoder = new Encoder();
        echo "Tuzsuz: ".$encoder->encode("Pass123");

        echo "<br><br>Tuzlu: ".$encoder->salt($encoder->encode("Pass123"));


    }
}