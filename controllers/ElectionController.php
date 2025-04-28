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
}