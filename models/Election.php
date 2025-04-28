<?php

class Election {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function getAllElections() {
        try {
            $stmt = $this->conn->query("SELECT * FROM elections ORDER BY date_debut DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Erreur lors de la récupération des élections : " . $e->getMessage());
            return false;
        }
    }

    public function getElectionById($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM elections WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Erreur lors de la récupération de l'élection : " . $e->getMessage());
            return false;
        }
    }

    public function createElection($data) {
        try {
            $stmt = $this->conn->prepare(
                "INSERT INTO elections (titre, description, type, date_debut, date_fin, statut) 
                 VALUES (?, ?, ?, ?, ?, ?)"
            );
            
            return $stmt->execute([
                $data['titre'],
                $data['description'],
                $data['type'],
                $data['date_debut'],
                $data['date_fin'],
                'en_attente'
            ]);
        } catch(PDOException $e) {
            error_log("Erreur lors de la création de l'élection : " . $e->getMessage());
            return false;
        }
    }

    public function updateElection($id, $data) {
        try {
            $stmt = $this->conn->prepare(
                "UPDATE elections 
                 SET titre = ?, description = ?, type = ?, 
                     date_debut = ?, date_fin = ?, statut = ?
                 WHERE id = ?"
            );
            
            return $stmt->execute([
                $data['titre'],
                $data['description'],
                $data['type'],
                $data['date_debut'],
                $data['date_fin'],
                $data['statut'],
                $id
            ]);
        } catch(PDOException $e) {
            error_log("Erreur lors de la mise à jour de l'élection : " . $e->getMessage());
            return false;
        }
    }

    public function deleteElection($id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM elections WHERE id = ?");
            return $stmt->execute([$id]);
        } catch(PDOException $e) {
            error_log("Erreur lors de la suppression de l'élection : " . $e->getMessage());
            return false;
        }
    }

    public function updateStatus($id, $status) {
        try {
            $stmt = $this->conn->prepare("UPDATE elections SET statut = ? WHERE id = ?");
            return $stmt->execute([$status, $id]);
        } catch(PDOException $e) {
            error_log("Erreur lors de la mise à jour du statut : " . $e->getMessage());
            return false;
        }
    }

    public function getActiveElections() {
        try {
            $stmt = $this->conn->prepare(
                "SELECT * FROM elections 
                 WHERE statut = 'en_cours' 
                 AND NOW() BETWEEN date_debut AND date_fin
                 ORDER BY date_debut DESC"
            );
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Erreur lors de la récupération des élections actives : " . $e->getMessage());
            return false;
        }
    }
}