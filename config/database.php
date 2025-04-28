<?php

class Database {
    private $config;

    public function __construct() {
        $this->config = [
            'host' => 'localhost',
            'db_name' => 'elections',
            'username' => 'root',
            'password' => '',
            'options' => [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
            ]
        ];
    }

    public function getConnection() {
        $this->conn = null;

        try {
            // Configuration pour PHP 5.6
            $dsn = "mysql:host=" . $this->config['host'] . ";dbname=" . $this->config['db_name'];
            $this->conn = new PDO($dsn, $this->config['username'], $this->config['password'], $this->config['options']);

        } catch (PDOException $e) {
            error_log("Erreur de connexion : " . $e->getMessage());
            throw new Exception("La connexion à la base de données a échoué. Veuillez vérifier vos paramètres de connexion.");
        }

        return $this->conn;
    }
}
