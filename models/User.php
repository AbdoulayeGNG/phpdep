<?php

class User {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function getAllUsers() {
        try {
            $stmt = $this->conn->query("SELECT id, nom, email, role, date_naissance, sexe, statut FROM utilisateurs ORDER BY nom");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getUserById($id) {
        try {
            $stmt = $this->conn->prepare("SELECT id, nom, email, role, date_naissance, sexe, statut FROM utilisateurs WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function updateUser($id, $data) {
        try {
            $stmt = $this->conn->prepare(
                "UPDATE utilisateurs 
                 SET nom = ?, email = ?, role = ?, date_naissance = ?, sexe = ?, statut = ?
                 WHERE id = ?"
            );
            
            return $stmt->execute([
                $data['nom'],
                $data['email'],
                $data['role'],
                $data['date_naissance'],
                $data['sexe'],
                $data['statut'],
                $id
            ]);
        } catch(PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function deleteUser($id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM utilisateurs WHERE id = ?");
            return $stmt->execute([$id]);
        } catch(PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function toggleStatus($id) {
        try {
            $stmt = $this->conn->prepare("UPDATE utilisateurs SET statut = NOT statut WHERE id = ?");
            return $stmt->execute([$id]);
        } catch(PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function emailExists($email) {
        try {
            $stmt = $this->conn->prepare("SELECT COUNT(*) FROM utilisateurs WHERE email = ?");
            $stmt->execute(array($email));
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function createUser($data) {
        try {
            $sql = "INSERT INTO utilisateurs (Nid, nom, email, mot_de_passe, date_naissance, sexe, role, statut) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([
                $data['Nid'],
                $data['nom'],
                $data['email'],
                $data['mot_de_passe'],
                $data['date_naissance'],
                $data['sexe'],
                $data['role'],
                $data['statut']
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}