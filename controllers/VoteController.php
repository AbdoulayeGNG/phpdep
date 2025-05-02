<?php
require_once __DIR__ . '/../core/Controller.php';

class VoteController extends Controller {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/public/elections/en-cours');
            exit();
        }

        try {
            $election_id = filter_input(INPUT_POST, 'election_id', FILTER_VALIDATE_INT);
            $candidat_id = filter_input(INPUT_POST, 'candidat_id', FILTER_VALIDATE_INT);
            $electeur_id = $_SESSION['user_id'];

            // Vérifier si l'électeur n'a pas déjà voté pour cette élection
            $stmt = $this->conn->prepare("SELECT id FROM votes WHERE election_id = ? AND electeur_id = ?");
            $stmt->execute([$election_id, $electeur_id]);
            
            if ($stmt->fetch()) {
                throw new Exception("Vous avez déjà voté pour cette élection");
            }

            // Enregistrer le vote
            $stmt = $this->conn->prepare("
                INSERT INTO votes (election_id, candidat_id, electeur_id, date_vote) 
                VALUES (?, ?, ?, NOW())
            ");
            
            if ($stmt->execute([$election_id, $candidat_id, $electeur_id])) {
                $_SESSION['success'] = "Votre vote a été enregistré avec succès";
            } else {
                throw new Exception("Erreur lors de l'enregistrement du vote");
            }

        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: ' . BASE_URL . '/public/elections/en-cours');
        exit();
    }
}
