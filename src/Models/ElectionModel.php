<?php
namespace App\Models;

use PDO;
use DateTime;

class ElectionModel {
    private $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function create(array $data) {
        try {
            $sql = "INSERT INTO elections (titre, description, type, date_debut, date_fin, statut) 
                    VALUES (:titre, :description, :type, :date_debut, :date_fin, :statut)";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':titre' => $data['titre'],
                ':description' => $data['description'],
                ':type' => $data['type'],
                ':date_debut' => $data['date_debut'],
                ':date_fin' => $data['date_fin'],
                ':statut' => 'en_attente'
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            throw $e;
        }
    }
}