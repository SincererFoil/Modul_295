<?php

namespace App\database;
use mysqli;
use mysqli_sql_exception;

class Database
{
    private mysqli $connection;


    public function __construct()
    {
        try {
            $this->connection = new mysqli("mysql", "root", "", "uek295", "3306");
            if ($this->connection->connect_error) {
                throw new mysqli_sql_exception("Couldn't connect to database with exception: " . $this->connection->connect_error);
            }
        } catch (mysqli_sql_exception $exception) {
            throw new mysqli_sql_exception("Couldn't connect to database with exception: " . $exception->getMessage());
        }

    }

    public function getConnection(): mysqli {
        return $this->connection;

    }
}