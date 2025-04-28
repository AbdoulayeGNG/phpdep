<?php
namespace App\Core;

use PDO;
use PDOException;
use Exception;

class Database {
    private $conn;
    private $config;

    public function __construct() {
        $this->config = require __DIR__ . '/../../config/database.php';
    }

    public function getConnection() {
        if ($this->conn === null) {
            try {
                $dsn = "mysql:host=" . $this->config['host'] . ";dbname=" . $this->config['db_name'];
                $this->conn = new PDO(
                    $dsn,
                    $this->config['username'],
                    $this->config['password'],
                    $this->config['options']
                );
            } catch (PDOException $e) {
                error_log("Erreur de connexion : " . $e->getMessage());
                throw new Exception("La connexion à la base de données a échoué. Veuillez vérifier vos paramètres de connexion.");
            }
        }
        return $this->conn;
    }
}