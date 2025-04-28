<?php
require_once __DIR__ . '/../core/Controller.php';

class ElecteurController extends Controller {
    private $db;
    private $conn;

    public function __construct() {
        // parent::__construct(); // Removed as Controller::__construct() does not exist
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function dashboard() {
        // Vérifier si l'utilisateur est connecté et est un électeur
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'electeur') {
            return $this->redirect('/auth/login');
        }

        try {
            // Récupérer les informations de l'électeur
            $stmt = $this->conn->prepare("SELECT * FROM utilisateurs WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $electeur = $stmt->fetch(PDO::FETCH_ASSOC);

            // Récupérer les élections en cours
            $stmt = $this->conn->query(
                "SELECT * FROM elections 
                 WHERE statut = 'en_cours' 
                 AND NOW() BETWEEN date_debut AND date_fin 
                 ORDER BY date_debut ASC"
            );
            $elections_en_cours = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Récupérer les élections à venir
            $stmt = $this->conn->query(
                "SELECT * FROM elections 
                 WHERE statut = 'en_attente' 
                 AND date_debut > NOW() 
                 ORDER BY date_debut ASC 
                 LIMIT 5"
            );
            $elections_a_venir = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $this->view('dashboard/electeur', [
                'electeur' => $electeur,
                'elections_en_cours' => $elections_en_cours,
                'elections_a_venir' => $elections_a_venir
            ]);

        } catch(PDOException $e) {
            error_log($e->getMessage());
            return $this->view('dashboard/electeur', [
                'error' => 'Une erreur est survenue lors du chargement du tableau de bord'
            ]);
        }
    }

    public function electionsEnCours() {
        try {
            $stmt = $this->conn->query(
                "SELECT * FROM elections 
                 WHERE statut = 'en_cours' 
                 AND NOW() BETWEEN date_debut AND date_fin 
                 ORDER BY date_fin ASC"
            );
            $elections = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $this->view('elections/en-cours', [
                'elections' => $elections
            ]);
        } catch(PDOException $e) {
            error_log($e->getMessage());
            return $this->view('elections/en-cours', [
                'error' => 'Erreur lors du chargement des élections'
            ]);
        }
    }

    public function mesVotes() {
        try {
            $stmt = $this->conn->prepare(
                "SELECT v.*, e.titre 
                 FROM votes v 
                 JOIN elections e ON v.election_id = e.id 
                 WHERE v.utilisateur_id = ? 
                 ORDER BY v.date_vote DESC"
            );
            $stmt->execute([$_SESSION['user_id']]);
            $votes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $this->view('votes/mes-votes', [
                'votes' => $votes
            ]);
        } catch(PDOException $e) {
            error_log($e->getMessage());
            return $this->view('votes/mes-votes', [
                'error' => 'Erreur lors du chargement de vos votes'
            ]);
        }
    }

    public function profile() {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM utilisateurs WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            return $this->view('profile/index', [
                'user' => $user
            ]);
        } catch(PDOException $e) {
            error_log($e->getMessage());
            return $this->view('profile/index', [
                'error' => 'Erreur lors du chargement du profil'
            ]);
        }
    }
}