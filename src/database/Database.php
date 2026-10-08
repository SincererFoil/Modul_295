<?php

namespace App\database;
use mysqli;
use mysqli_sql_exception;

class Database {
    private mysqli $connection;


    /**
     *  Constructs a new connection with multiple env variables
     */
    public function __construct() {
        try {
            $this->connection = new mysqli(getenv("DB_HOST"), getenv("DB_USERNAME"), getenv("DB_PASSWORD"), getenv("DB_DATABASE"), getenv("DB_PORT"));
            // Checks if an error happened during the connection
            if ($this->connection->connect_error) {
                throw new mysqli_sql_exception("Couldn't connect to database");
            }
        } catch (mysqli_sql_exception $exception) {
            throw new mysqli_sql_exception("Couldn't connect to database");
        }

    }

    /**
     *  This Method returns a database connection
     *
     * @return mysqli the connection
     */
    public function getConnection(): mysqli {
        return $this->connection;
    }
}