<?php

namespace App\Lib\Database;

use App\Lib\Database\Engine\DatabaseI;

interface DatabaseFactoryI
{
    public function createDBHandler(): DatabaseI;
}