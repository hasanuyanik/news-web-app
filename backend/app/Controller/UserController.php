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



        var_dump(Activity::getActivities(0));

        echo "<br><br><br>";
        $user = new User();

        $cUser = new UserRepository();
        $cUser->username = "userName";
        $cUser->fullname = "User Name";
        $cUser->password = "UserPassword";
        $cUser->email = "User@Name.com";
        $cUser->phone = "0555 444 33 22";

        $cResult = $user->add($cUser);

        echo "<br><br>Kullanıcı Ekleme : $cResult <br><br>";

        $eUser = new UserRepository();
        $eUser->id = 3;
        $eUser->username = "userNameUpdate";
        $eUser->fullname = "User NameUpdate";
        $eUser->password = "UserPasswordUpdate";
        $eUser->email = "User@NameUpdate.com";
        $eUser->phone = "0555 444 33 2200";

        $eResult = $user->edit($eUser);

        echo "<br><br>Kullanıcı Düzenleme : $eResult <br><br>";

        $dUser = new UserRepository();
        $dUser->id = 5;

        $dResult = $user->delete($dUser);

        echo "<br><br>Kullanıcı Silme : $dResult <br><br>";

        $gUser = new UserRepository();

        var_dump($user->getUsers($gUser));

    }
}