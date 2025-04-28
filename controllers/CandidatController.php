<?php
require_once __DIR__ . '/../core/Controller.php';

class CandidatController extends Controller {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function index() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/public/auth/login');
            exit();
        }

        try {
            $sql = "SELECT c.*, u.nom as nom_utilisateur, e.titre as titre_election 
                    FROM candidats c 
                    LEFT JOIN utilisateurs u ON c.utilisateur_id = u.id 
                    LEFT JOIN elections e ON c.election_id = e.id 
                    ORDER BY e.titre, u.nom";
            
            $stmt = $this->conn->query($sql);
            $candidats = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $this->view('candidats/index', [
                'candidats' => $candidats,
                'success' => isset($_SESSION['success']) ? $_SESSION['success'] : null
            ]);

        } catch(PDOException $e) {
            error_log("Database error in CandidatController@index: " . $e->getMessage());
            return $this->view('candidats/index', [
                'error' => 'Erreur lors de la récupération des candidats',
                'candidats' => []
            ]);
        }
    }

    public function create() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/public/auth/login');
            exit();
        }

        try {
            // Récupérer la liste des élections et des utilisateurs
            $elections = $this->conn->query("SELECT id, titre FROM elections WHERE statut = 'en_attente'")->fetchAll();
            $utilisateurs = $this->conn->query("SELECT id, nom FROM utilisateurs WHERE role = 'electeur'")->fetchAll();

            return $this->view('candidats/create', [
                'elections' => $elections,
                'utilisateurs' => $utilisateurs
            ]);

        } catch(PDOException $e) {
            error_log("Database error in CandidatController@create: " . $e->getMessage());
            $_SESSION['error'] = "Erreur lors du chargement du formulaire";
            header('Location: ' . BASE_URL . '/public/candidats');
            exit();
        }
    }

    public function store() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/public/auth/login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validation
                if (empty($_POST['utilisateur_id'])) throw new Exception("L'utilisateur est requis");
                if (empty($_POST['election_id'])) throw new Exception("L'élection est requise");
                if (empty($_POST['parti_politique'])) throw new Exception("Le parti politique est requis");
                if (empty($_POST['programme'])) throw new Exception("Le programme est requis");

                // Traitement de l'image
                $photo = null;
                if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = __DIR__ . '/../public/uploads/candidats/';
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $photoName = uniqid() . '_' . basename($_FILES['photo']['name']);
                    $photoPath = $uploadDir . $photoName;

                    if (move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath)) {
                        $photo = 'uploads/candidats/' . $photoName;
                    }
                }

                $sql = "INSERT INTO candidats (utilisateur_id, election_id, parti_politique, programme, photo, valide) 
                        VALUES (?, ?, ?, ?, ?, FALSE)";
                
                $stmt = $this->conn->prepare($sql);
                $result = $stmt->execute([
                    $_POST['utilisateur_id'],
                    $_POST['election_id'],
                    $_POST['parti_politique'],
                    $_POST['programme'],
                    $photo
                ]);

                if ($result) {
                    $_SESSION['success'] = "Le candidat a été ajouté avec succès";
                    header('Location: ' . BASE_URL . '/public/candidats');
                    exit();
                }

            } catch(Exception $e) {
                error_log("Error in CandidatController@store: " . $e->getMessage());
                return $this->view('candidats/create', [
                    'error' => $e->getMessage(),
                    'old' => $_POST
                ]);
            }
        }
    }

    public function edit($id) {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/auth/login');
            exit();
        }

        try {
            // Debug log
            error_log("Editing candidat ID: " . $id);

            $stmt = $this->conn->prepare("
                SELECT c.*, u.nom as nom_utilisateur, e.titre as titre_election
                FROM candidats c
                LEFT JOIN utilisateurs u ON c.utilisateur_id = u.id
                LEFT JOIN elections e ON c.election_id = e.id
                WHERE c.id = ?
            ");
            $stmt->execute([$id]);
            $candidat = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$candidat) {
                $_SESSION['error'] = "Candidat non trouvé";
                header('Location: ' . BASE_URL . '/candidats');
                exit();
            }

            // Récupérer la liste des élections
            $elections = $this->conn->query("
                SELECT id, titre FROM elections WHERE statut = 'en_attente' OR id = " . $candidat['election_id']
            )->fetchAll(PDO::FETCH_ASSOC);

            return $this->view('candidats/edit', [
                'candidat' => $candidat,
                'elections' => $elections
            ]);

        } catch(PDOException $e) {
            error_log("Database error in edit: " . $e->getMessage());
            $_SESSION['error'] = "Erreur lors du chargement du candidat";
            header('Location: ' . BASE_URL . '/candidats');
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
                // Validation
                if (empty($_POST['election_id'])) throw new Exception("L'élection est requise");
                if (empty($_POST['parti_politique'])) throw new Exception("Le parti politique est requis");
                if (empty($_POST['programme'])) throw new Exception("Le programme est requis");

                // Traitement de la photo si une nouvelle est uploadée
                $photo = null;
                if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = __DIR__ . '/../public/uploads/candidats/';
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $photoName = uniqid() . '_' . basename($_FILES['photo']['name']);
                    $photoPath = $uploadDir . $photoName;

                    if (move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath)) {
                        $photo = 'uploads/candidats/' . $photoName;

                        // Supprimer l'ancienne photo si elle existe
                        $stmt = $this->conn->prepare("SELECT photo FROM candidats WHERE id = ?");
                        $stmt->execute([$id]);
                        $oldPhoto = $stmt->fetchColumn();
                        if ($oldPhoto && file_exists(__DIR__ . '/../public/' . $oldPhoto)) {
                            unlink(__DIR__ . '/../public/' . $oldPhoto);
                        }
                    }
                }

                // Préparation des données pour la mise à jour
                $sql = "UPDATE candidats SET 
                        election_id = ?,
                        parti_politique = ?,
                        programme = ?,
                        valide = ?";
                $params = [
                    $_POST['election_id'],
                    $_POST['parti_politique'],
                    $_POST['programme'],
                    isset($_POST['valide']) ? 1 : 0
                ];

                // Ajouter la photo à la requête si une nouvelle a été uploadée
                if ($photo) {
                    $sql .= ", photo = ?";
                    $params[] = $photo;
                }

                $sql .= " WHERE id = ?";
                $params[] = $id;

                $stmt = $this->conn->prepare($sql);
                $result = $stmt->execute($params);

                if ($result) {
                    $_SESSION['success'] = "Le candidat a été modifié avec succès";
                    header('Location: ' . BASE_URL . '/public/candidats');
                    exit();
                }

            } catch(Exception $e) {
                error_log("Error in CandidatController@update: " . $e->getMessage());
                return $this->view('candidats/edit', [
                    'error' => $e->getMessage(),
                    'candidat' => $_POST,
                    'elections' => $this->conn->query("SELECT id, titre FROM elections")->fetchAll()
                ]);
            }
        }

        header('Location: ' . BASE_URL . '/public/candidats');
        exit();
    }

    public function validate($id) {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/public/auth/login');
            exit();
        }

        try {
            // Debug log
            error_log("Validating candidate with ID: " . $id);

            // Check if candidate exists
            $stmt = $this->conn->prepare("SELECT id FROM candidats WHERE id = ?");
            $stmt->execute([$id]);
            
            if (!$stmt->fetch()) {
                $_SESSION['error'] = "Candidat non trouvé";
                header('Location: ' . BASE_URL . '/public/candidats');
                exit();
            }

            // Update candidate status
            $stmt = $this->conn->prepare("UPDATE candidats SET valide = TRUE WHERE id = ?");
            $result = $stmt->execute([$id]);

            if ($result) {
                $_SESSION['success'] = "Le candidat a été validé avec succès";
            } else {
                $_SESSION['error'] = "Erreur lors de la validation du candidat";
            }

        } catch(PDOException $e) {
            error_log("Error validating candidate: " . $e->getMessage());
            $_SESSION['error'] = "Une erreur est survenue lors de la validation";
        }

        header('Location: ' . BASE_URL . '/public/candidats');
        exit();
    }

    public function delete($id) {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $_SESSION['error'] = "Accès non autorisé";
            header('Location: ' . BASE_URL . '/public/auth/login');
            exit();
        }

        try {
            // Debug log
            error_log("Attempting to delete candidate with ID: " . $id);

            // Vérifier si le candidat existe
            $stmt = $this->conn->prepare("SELECT * FROM candidats WHERE id = ?");
            $stmt->execute([$id]);
            $candidat = $stmt->fetch();

            if (!$candidat) {
                $_SESSION['error'] = "Candidat non trouvé";
                header('Location: ' . BASE_URL . '/public/candidats');
                exit();
            }

            // Supprimer la photo si elle existe
            if (!empty($candidat['photo'])) {
                $photoPath = dirname(__DIR__) . '/public/' . $candidat['photo'];
                if (file_exists($photoPath)) {
                    unlink($photoPath);
                }
            }

            // Supprimer le candidat
            $stmt = $this->conn->prepare("DELETE FROM candidats WHERE id = ?");
            if ($stmt->execute([$id])) {
                $_SESSION['success'] = "Le candidat a été supprimé avec succès";
            } else {
                $_SESSION['error'] = "Erreur lors de la suppression du candidat";
            }

        } catch(PDOException $e) {
            error_log("Error deleting candidate: " . $e->getMessage());
            $_SESSION['error'] = "Une erreur est survenue lors de la suppression";
        }

        header('Location: ' . BASE_URL . '/public/candidats');
        exit();
    }
}