<?php

class Database
{
    private $host = "localhost";

    private $dbname = "bakeryqu_db";

    private $username = "root";

    private $password = "";

    private $connection;

    public function connect()
    {
        if ($this->connection === null) {

            try {

                $this->connection = new PDO(

                    "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",

                    $this->username,

                    $this->password

                );

                $this->connection->setAttribute(

                    PDO::ATTR_ERRMODE,

                    PDO::ERRMODE_EXCEPTION

                );

                $this->connection->setAttribute(

                    PDO::ATTR_DEFAULT_FETCH_MODE,

                    PDO::FETCH_ASSOC

                );

            } catch (PDOException $e) {

                die("Koneksi Database Gagal : " . $e->getMessage());

            }
        }

        return $this->connection;
    }
}