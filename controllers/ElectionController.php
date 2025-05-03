<?php
require_once __DIR__ . '/../core/Controller.php';

class ElectionController extends Controller {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function create() {
        // Vérifier si l'utilisateur est admin
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/public/auth/login');
            exit();
        }
        
        return $this->view('elections/create');
    }

    public function store() {
        // Vérifier si l'utilisateur est admin
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/public/auth/login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validation et nettoyage des données
                $titre = filter_input(INPUT_POST, 'titre', FILTER_SANITIZE_STRING);
                $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
                $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
                $date_debut = $_POST['date_debut'];
                $date_fin = $_POST['date_fin'];

                // Debug log
                error_log("Processing election creation with title: " . $titre);

                // Validation des dates
                $date_debut_obj = new DateTime($date_debut);
                $date_fin_obj = new DateTime($date_fin);
                $now = new DateTime();

                if ($date_debut_obj < $now) {
                    throw new Exception('La date de début ne peut pas être dans le passé');
                }

                if ($date_fin_obj <= $date_debut_obj) {
                    throw new Exception('La date de fin doit être postérieure à la date de début');
                }

                // Vérifier si une élection avec le même titre existe déjà
                $stmt = $this->conn->prepare("SELECT COUNT(*) FROM elections WHERE titre = ?");
                $stmt->execute([$titre]);
                $titleExists = $stmt->fetchColumn();

                if ($titleExists) {
                    error_log("Election title already exists: " . $titre);
                    return $this->view('elections/create', [
                        'error' => 'Une élection avec ce titre existe déjà.'
                    ]);
                }

                // Insérer l'élection dans la base de données
                $sql = "INSERT INTO elections (titre, description, type, date_debut, date_fin, statut) 
                        VALUES (?, ?, ?, ?, ?, 'en_attente')";
                
                $stmt = $this->conn->prepare($sql);
                $result = $stmt->execute([
                    $titre,
                    $description,
                    $type,
                    $date_debut_obj->format('Y-m-d H:i:s'),
                    $date_fin_obj->format('Y-m-d H:i:s')
                ]);

                if ($result) {
                    error_log("Election created successfully: " . $titre);
                    $_SESSION['success'] = "L'élection a été créée avec succès";
                    header('Location: ' . BASE_URL . '/public/elections');
                    exit();
                } else {
                    error_log("Database insert failed for election: " . $titre);
                    throw new PDOException("Database insert failed");
                }

            } catch (Exception $e) {
                error_log("Election creation error: " . $e->getMessage());
                return $this->view('elections/create', [
                    'error' => $e->getMessage(),
                    'old' => $_POST
                ]);
            }
        }

        return $this->view('elections/create');
    }

    public function index() {
        try {
            $sql = "SELECT * FROM elections ORDER BY date_debut DESC";
            $stmt = $this->conn->query($sql);
            $elections = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $this->view('elections/index', [
                'elections' => $elections
            ]);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return $this->view('elections/index', [
                'error' => 'Erreur lors du chargement des élections'
            ]);
        }
    }

    public function edit($id) {
        // Vérifier si l'utilisateur est admin
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/public/auth/login');
            exit();
        }

        try {
            $stmt = $this->conn->prepare("SELECT * FROM elections WHERE id = ?");
            $stmt->execute([$id]);
            $election = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$election) {
                throw new Exception("Élection non trouvée");
            }

            return $this->view('elections/edit', ['election' => $election]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            $_SESSION['error'] = "Erreur lors du chargement de l'élection";
            header('Location: ' . BASE_URL . '/public/elections');
            exit();
        }
    }

    public function update($id) {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/public/auth/login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $titre = filter_input(INPUT_POST, 'titre', FILTER_SANITIZE_STRING);
                $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
                $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
                $date_debut = $_POST['date_debut'];
                $date_fin = $_POST['date_fin'];

                // Validation des dates
                $date_debut_obj = new DateTime($date_debut);
                $date_fin_obj = new DateTime($date_fin);

                if ($date_fin_obj <= $date_debut_obj) {
                    throw new Exception('La date de fin doit être postérieure à la date de début');
                }

                $sql = "UPDATE elections 
                        SET titre = ?, description = ?, type = ?, 
                            date_debut = ?, date_fin = ? 
                        WHERE id = ?";
                
                $stmt = $this->conn->prepare($sql);
                $result = $stmt->execute([
                    $titre,
                    $description,
                    $type,
                    $date_debut_obj->format('Y-m-d H:i:s'),
                    $date_fin_obj->format('Y-m-d H:i:s'),
                    $id
                ]);

                if ($result) {
                    $_SESSION['success'] = "L'élection a été modifiée avec succès";
                    header('Location: ' . BASE_URL . '/public/elections');
                    exit();
                }

            } catch (Exception $e) {
                error_log($e->getMessage());
                return $this->view('elections/edit', [
                    'error' => $e->getMessage(),
                    'election' => $_POST
                ]);
            }
        }
    }

    public function showCandidats($id) {
        try {
            // Récupérer les détails de l'élection
            $stmt = $this->conn->prepare("SELECT * FROM elections WHERE id = ?");
            $stmt->execute([$id]);
            $election = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$election) {
                throw new Exception("Élection non trouvée");
            }

            // Vérifier si l'utilisateur a déjà voté
            $stmt = $this->conn->prepare("
                SELECT COUNT(*) FROM votes 
                WHERE election_id = ? AND electeur_id = ?
            ");
            $stmt->execute([$id, $_SESSION['user_id']]);
            $a_vote = $stmt->fetchColumn() > 0;

            // Récupérer les candidats
            $stmt = $this->conn->prepare("
                SELECT c.*, u.nom 
                FROM candidats c 
                JOIN utilisateurs u ON c.utilisateur_id = u.id 
                WHERE c.election_id = ? AND c.valide = 1
            ");
            $stmt->execute([$id]);
            $candidats = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $this->view('elections/candidats', [
                'election' => $election,
                'candidats' => $candidats,
                'a_vote' => $a_vote
            ]);

        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ' . BASE_URL . '/public/elections/en-cours');
            exit();
        }
    }

    public function listeResultats() {
        try {
            // Récupérer les élections terminées
            $stmt = $this->conn->prepare(
                "SELECT e.*, 
                        (SELECT COUNT(*) FROM votes v WHERE v.election_id = e.id) as nombre_votes,
                        (SELECT COUNT(DISTINCT utilisateur_id) FROM votes v WHERE v.election_id = e.id) as participants
                 FROM elections e 
                 WHERE e.date_fin < CURRENT_TIMESTAMP
                 ORDER BY e.date_fin DESC"
            );
            $stmt->execute();
            $elections = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $this->view('elections/resultats-liste', [
                'elections' => $elections
            ]);

        } catch(PDOException $e) {
            error_log("Erreur dans listeResultats: " . $e->getMessage());
            return $this->view('elections/resultats-liste', [
                'error' => 'Une erreur est survenue lors du chargement des résultats'
            ]);
        }
    }

    public function afficherResultats($id) {
        try {
            // Récupérer les détails de l'élection
            $stmt = $this->conn->prepare(
                "SELECT e.*, 
                        (SELECT COUNT(*) FROM votes v WHERE v.election_id = e.id) as nombre_votes,
                        (SELECT COUNT(DISTINCT utilisateur_id) FROM votes v WHERE v.election_id = e.id) as participants
                 FROM elections e 
                 WHERE e.id = ?"
            );
            $stmt->execute([$id]);
            $election = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$election) {
                throw new Exception('Élection non trouvée');
            }

            // Récupérer les résultats des candidats
            $stmt = $this->conn->prepare(
                "SELECT c.*, u.nom as nom_candidat, u.photo,
                        COUNT(v.id) as nombre_votes,
                        ROUND((COUNT(v.id) * 100.0) / 
                            (SELECT COUNT(*) FROM votes WHERE election_id = ?), 2) as pourcentage
                 FROM candidats c
                 INNER JOIN utilisateurs u ON c.utilisateur_id = u.id
                 LEFT JOIN votes v ON v.candidat_id = c.id
                 WHERE c.election_id = ? AND c.valide = 1
                 GROUP BY c.id
                 ORDER BY nombre_votes DESC"
            );
            $stmt->execute([$id, $id]);
            $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Récupérer les statistiques par département/région
            $stmt = $this->conn->prepare(
                "SELECT u.departement, COUNT(*) as nombre_votes,
                        ROUND((COUNT(*) * 100.0) / 
                            (SELECT COUNT(*) FROM votes WHERE election_id = ?), 2) as pourcentage
                 FROM votes v
                 INNER JOIN utilisateurs u ON v.utilisateur_id = u.id
                 WHERE v.election_id = ?
                 GROUP BY u.departement
                 ORDER BY nombre_votes DESC"
            );
            $stmt->execute([$id, $id]);
            $stats_geo = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $this->view('elections/resultats-detail', [
                'election' => $election,
                'resultats' => $resultats,
                'stats_geo' => $stats_geo
            ]);

        } catch(Exception $e) {
            error_log("Erreur dans afficherResultats: " . $e->getMessage());
            return $this->view('elections/resultats-detail', [
                'error' => 'Une erreur est survenue lors du chargement des résultats'
            ]);
        }
    }
}