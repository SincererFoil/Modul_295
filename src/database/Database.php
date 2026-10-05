<?php

namespace App\database;
use mysqli;

class Database
{
    private mysqli $connection;


    public function __construct()
    {
        $this->connection = new mysqli("localhost", "root", "", "uek295", "3306");
    }

    public function getConnection(): mysqli {
        return $this->connection;

    }
}