<?php
require_once __DIR__ . '/../core/Controller.php';

class ElecteurController extends Controller {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function dashboard() {
        try {
            if (!isset($_SESSION['user_id'])) {
                header('Location: ' . BASE_URL . '/auth/login');
                exit;
            }

            // Récupérer les élections en cours
            $stmt = $this->conn->prepare(
                "SELECT e.*, 
                       COALESCE((
                           SELECT 1 FROM votes v 
                           WHERE v.election_id = e.id 
                           AND v.utilisateur_id = ?
                           LIMIT 1
                       ), 0) as a_vote
                 FROM elections e 
                 WHERE e.statut = 'en_cours' 
                 AND CURRENT_TIMESTAMP BETWEEN e.date_debut AND e.date_fin 
                 ORDER BY e.date_fin ASC"
            );
            $stmt->execute([$_SESSION['user_id']]);
            $elections_en_cours = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Récupérer le nombre total de votes de l'utilisateur
            $stmt = $this->conn->prepare(
                "SELECT COUNT(*) as nombre_votes 
                 FROM votes 
                 WHERE utilisateur_id = ?"
            );
            $stmt->execute([$_SESSION['user_id']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $nombre_votes = $result['nombre_votes'];

            // Récupérer les élections à venir
            $stmt = $this->conn->prepare(
                "SELECT * FROM elections 
                 WHERE statut = 'en_cours' 
                 AND date_debut > CURRENT_TIMESTAMP 
                 ORDER BY date_debut ASC"
            );
            $stmt->execute();
            $elections_a_venir = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Récupérer les informations de l'utilisateur
            $stmt = $this->conn->prepare("SELECT * FROM utilisateurs WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            return $this->view('dashboard/electeur', [
                'user' => $user,
                'elections_en_cours' => $elections_en_cours,
                'elections_a_venir' => $elections_a_venir,
                'nombre_votes' => $nombre_votes
            ]);

        } catch(PDOException $e) {
            error_log("Erreur dans le dashboard électeur: " . $e->getMessage());
            return $this->view('dashboard/electeur', [
                'error' => 'Une erreur est survenue lors du chargement du tableau de bord',
                'elections_en_cours' => [],
                'elections_a_venir' => [],
                'nombre_votes' => 0
            ]);
        }
    }

    public function electionsEnCours() {
        try {
            // Vérifier la session
            if (!isset($_SESSION['user_id'])) {
                error_log("Session utilisateur non trouvée");
                return $this->view('electeur/elections-en-cours', [
                    'error' => 'Session expirée. Veuillez vous reconnecter.',
                    'elections' => []
                ]);
            }

            // Vérifier la connexion à la base de données
            if (!$this->conn) {
                error_log("Connexion à la base de données non établie");
                throw new PDOException('Erreur de connexion à la base de données');
            }

            // Requête corrigée avec un seul paramètre
            $stmt = $this->conn->prepare(
                "SELECT e.*, 
                       COALESCE((
                           SELECT 1 FROM votes v 
                           WHERE v.election_id = e.id 
                           AND v.utilisateur_id = ?
                           LIMIT 1
                       ), 0) as a_vote
                 FROM elections e 
                 WHERE e.statut = 'en_cours' 
                 AND CURRENT_TIMESTAMP BETWEEN e.date_debut AND e.date_fin 
                 ORDER BY e.date_fin ASC"
            );
            
            // Supprimé $currentTime car on utilise CURRENT_TIMESTAMP dans la requête
            if (!$stmt->execute([$_SESSION['user_id']])) {
                error_log("Erreur lors de l'exécution de la requête : " . print_r($stmt->errorInfo(), true));
                throw new PDOException('Erreur lors de l\'exécution de la requête des élections');
            }
            
            $elections = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Log pour debug
            error_log("Nombre d'élections trouvées : " . count($elections));

            // Récupération des candidats
            $candidatsStmt = $this->conn->prepare(
                "SELECT c.*, u.nom as nom_candidat 
                 FROM candidats c
                 LEFT JOIN utilisateurs u ON c.utilisateur_id = u.id 
                 WHERE c.election_id = ?
                 ORDER BY u.nom ASC"
            );

            foreach ($elections as &$election) {
                if (!$candidatsStmt->execute([$election['id']])) {
                    error_log("Erreur lors de la récupération des candidats pour l'élection " . $election['id']);
                    throw new PDOException('Erreur lors de la récupération des candidats');
                }
                $election['candidats'] = $candidatsStmt->fetchAll(PDO::FETCH_ASSOC);
            }

            return $this->view('electeur/elections-en-cours', [
                'elections' => $elections,
                'debug' => [
                    'user_id' => $_SESSION['user_id'],
                    'elections_count' => count($elections)
                ]
            ]);

        } catch(PDOException $e) {
            error_log("Erreur détaillée dans electionsEnCours: " . $e->getMessage());
            return $this->view('electeur/elections-en-cours', [
                'error' => 'Erreur lors du chargement des élections: ' . $e->getMessage(),
                'elections' => []
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

    public function afficherCandidats($id) {
        try {
            // Get election details
            $stmt = $this->conn->prepare(
                "SELECT * FROM elections 
                 WHERE id = ? AND statut = 'en_cours' 
                 AND CURRENT_TIMESTAMP BETWEEN date_debut AND date_fin"
            );
            $stmt->execute([$id]);
            $election = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$election) {
                return $this->view('electeur/voter', [
                    'error' => 'Cette élection n\'est pas disponible pour le vote.'
                ]);
            }

            // Get candidates with user details using proper aliases
            $candidatsStmt = $this->conn->prepare(
                "SELECT 
                    c.id,
                    c.election_id,
                    c.photo,
                    c.programme,
                    c.valide,
                    u.nom,
                    u.Nid
                 FROM candidats c
                 INNER JOIN utilisateurs u ON c.utilisateur_id = u.id
                 WHERE c.election_id = ? AND c.valide = 1
                 ORDER BY u.nom ASC"
            );
            $candidatsStmt->execute([$id]);
            $candidats = $candidatsStmt->fetchAll(PDO::FETCH_ASSOC);

            return $this->view('electeur/voter', [
                'election' => $election,
                'candidats' => $candidats
            ]);

        } catch(PDOException $e) {
            error_log("Erreur dans afficherCandidats: " . $e->getMessage());
            return $this->view('electeur/voter', [
                'error' => 'Une erreur est survenue lors du chargement des candidats.'
            ]);
        }
    }

    public function enregistrerVote() {
        try {
            if (!isset($_POST['election_id']) || !isset($_POST['candidat_id'])) {
                throw new Exception('Données de vote manquantes');
            }

            // Vérifier si l'utilisateur n'a pas déjà voté
            $checkStmt = $this->conn->prepare(
                "SELECT COUNT(*) FROM votes 
                 WHERE election_id = ? AND utilisateur_id = ?"
            );
            $checkStmt->execute([$_POST['election_id'], $_SESSION['user_id']]);
            if ($checkStmt->fetchColumn() > 0) {
                $_SESSION['error'] = 'Vous avez déjà voté pour cette élection';
                header('Location: ' . BASE_URL . '/public/elections/en-cours');
                exit;
            }

            // Enregistrer le vote
            $stmt = $this->conn->prepare(
                "INSERT INTO votes (utilisateur_id, candidat_id, election_id, date_vote) 
                 VALUES (?, ?, ?, NOW())"
            );
            $stmt->execute([
                $_SESSION['user_id'],
                $_POST['candidat_id'],
                $_POST['election_id']
            ]);

            $_SESSION['success'] = 'Votre vote a été enregistré avec succès';
            header('Location: ' . BASE_URL . '/public/elections/en-cours');
            exit;

        } catch(Exception $e) {
            error_log("Erreur dans enregistrerVote: " . $e->getMessage());
            $_SESSION['error'] = 'Erreur lors de l\'enregistrement du vote';
            header('Location: ' . BASE_URL . '/public/elections/en-cours');
            exit;
        }
    }
}