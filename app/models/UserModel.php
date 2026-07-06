<?php

require_once "../config/database.php";

class UserModel
{
    private PDO $conn;

    //------------------------------------------------
    // Membuat koneksi database
    //------------------------------------------------
    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    //------------------------------------------------
    // Mencari user berdasarkan email
    //------------------------------------------------
    public function findByEmail(string $email)
    {
        $sql = "SELECT *
                FROM users
                WHERE email = :email
                LIMIT 1";

        $statement = $this->conn->prepare($sql);

        $statement->bindValue(':email', $email);

        $statement->execute();

        return $statement->fetch();
    }

    //------------------------------------------------
    // Menambahkan user baru
    //------------------------------------------------
    public function create(array $data)
    {
        $sql = "INSERT INTO users
                (
                    name,
                    email,
                    password,
                    role
                )
                VALUES
                (
                    :name,
                    :email,
                    :password,
                    :role
                )";

        $statement = $this->conn->prepare($sql);

        return $statement->execute([
            ':name'     => $data['name'],
            ':email'    => $data['email'],
            ':password' => $data['password'],
            ':role'     => 'customer'
        ]);
    }

    //------------------------------------------------
    // Mengambil user berdasarkan id
    //------------------------------------------------
    public function findById(int $id)
    {
        $sql = "SELECT *
                FROM users
                WHERE id = :id
                LIMIT 1";

        $statement = $this->conn->prepare($sql);

        $statement->bindValue(':id', $id);

        $statement->execute();

        return $statement->fetch();
    }
}