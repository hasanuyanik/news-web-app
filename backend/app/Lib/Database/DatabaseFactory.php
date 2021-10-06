<?php

namespace App\Lib\Database;

use App\Lib\Database\Engine\MySQLDatabase;

class DatabaseFactory
{
    public $db;

    public function __construct()
    {
        $configdir = __DIR__."/../../config.php";

        if (file_exists($configdir))
        {
            $config = require $configdir;
            $engine = $config["engine"];
            if($engine != "mysql")
            {
             return "No Database Connection!";
            }
        }

        $this->db = new MySQLDatabase();
    }
}