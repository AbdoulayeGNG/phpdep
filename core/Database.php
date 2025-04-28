<?php

class Database {
    private $host = 'localhost';
    private $dbname = 'elections';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function __construct() {
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname}",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            throw new Exception("Database connection failed");
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}