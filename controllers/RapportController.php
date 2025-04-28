<?php
require_once __DIR__ . '/../core/Controller.php';

class RapportController extends Controller {
    private $conn;

    public function __construct() {
        // Verify if user is logged in and is admin
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/public/auth/login');
            exit();
        }
        
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function index() {
        try {
            // Get elections statistics
            $elections = $this->conn->query("
                SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN statut = 'en_cours' THEN 1 ELSE 0 END) as en_cours,
                    SUM(CASE WHEN statut = 'terminee' THEN 1 ELSE 0 END) as terminees
                FROM elections
            ")->fetch(PDO::FETCH_ASSOC);

            // Get candidates statistics
            $candidats = $this->conn->query("
                SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN valide = 1 THEN 1 ELSE 0 END) as valides
                FROM candidats
            ")->fetch(PDO::FETCH_ASSOC);

            // Statistiques des électeurs
            $statsElecteurs = $this->conn->query("
                SELECT COUNT(*) as total_electeurs
                FROM utilisateurs 
                WHERE role = 'electeur'
            ")->fetch(PDO::FETCH_ASSOC);

            return $this->view('rapports/index', [
                'elections' => $elections,
                'candidats' => $candidats,
                'statsElecteurs' => $statsElecteurs
            ]);

        } catch(PDOException $e) {
            error_log("Error in RapportController: " . $e->getMessage());
            return $this->view('error', [
                'message' => 'Une erreur est survenue lors de la génération des rapports'
            ]);
        }
    }
}