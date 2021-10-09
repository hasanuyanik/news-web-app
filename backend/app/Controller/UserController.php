<?php

namespace App\Controller;

use App\Lib\Activity\Activity;
use App\Lib\Database\DatabaseFactory;
use App\Lib\Encoder\Encoder;
use App\Lib\FileManager\FileManager;
use App\Lib\Logger\Logger;
use App\Lib\Logger\LogLevel;
use App\Lib\User\User;
use App\Lib\User\UserRepository;
use App\Lib\User\UserWiper;

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

        echo "<br><br>Tuzlu: ".$encoder->salt($encoder->encode("Pass123"))."<br><br>";

        $factory = new DatabaseFactory();

        var_dump($factory->db->delete("user",
            ["id" => 4]));
        echo "<br><br><br>";

        $gUser = new UserWiper();

        var_dump($gUser->getRequests());

        var_dump($gUser->edit(6,1));

        $gUser->delete(1);

    }
}