<?php
class ParametreController extends Controller {
    private $conn;
    private $uploadsDir;

    public function __construct() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/public/auth/login');
            exit();
        }
        $db = new Database();
        $this->conn = $db->getConnection();
        $this->uploadsDir = dirname(__DIR__) . '/public/uploads/';
    }

    public function index() {
        try {
            $stmt = $this->conn->query("SELECT * FROM parametres");
            $parametres = $stmt->fetch(PDO::FETCH_ASSOC);

            return $this->view('parametres/index', [
                'parametres' => $parametres,
                'pageTitle' => 'Paramètres du système'
            ]);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des paramètres: " . $e->getMessage());
            return $this->view('parametres/index', [
                'error' => 'Erreur lors de la récupération des paramètres'
            ]);
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/public/parametres');
            exit();
        }

        try {
            // Traitement du logo
            $logo_path = isset($parametres['logo_path']) ? $parametres['logo_path'] : null;
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                $logo_path = $this->handleLogoUpload($_FILES['logo']);
            }

            // Récupération et validation des données
            $data = [
                'nom_site' => filter_input(INPUT_POST, 'nom_site', FILTER_SANITIZE_STRING),
                'description' => filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING),
                'email_contact' => filter_input(INPUT_POST, 'email_contact', FILTER_VALIDATE_EMAIL),
                'duree_vote' => filter_input(INPUT_POST, 'duree_vote', FILTER_VALIDATE_INT),
                'theme_couleur' => filter_input(INPUT_POST, 'theme_couleur', FILTER_SANITIZE_STRING),
                'delai_validation_candidat' => filter_input(INPUT_POST, 'delai_validation_candidat', FILTER_VALIDATE_INT),
                'notifications_email' => isset($_POST['notifications_email']) ? 1 : 0,
                'maintenance_mode' => isset($_POST['maintenance_mode']) ? 1 : 0,
                'maintenance_message' => filter_input(INPUT_POST, 'maintenance_message', FILTER_SANITIZE_STRING),
                'logo_path' => $logo_path
            ];

            if (!$data['email_contact']) {
                throw new Exception("L'adresse email n'est pas valide");
            }

            // Mise à jour des paramètres
            $sql = "UPDATE parametres SET 
                    nom_site = :nom_site, 
                    description = :description, 
                    email_contact = :email_contact,
                    duree_vote = :duree_vote,
                    theme_couleur = :theme_couleur,
                    delai_validation_candidat = :delai_validation_candidat,
                    notifications_email = :notifications_email,
                    maintenance_mode = :maintenance_mode,
                    maintenance_message = :maintenance_message,
                    logo_path = :logo_path";

            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute($data);

            if ($result) {
                $_SESSION['success'] = "Paramètres mis à jour avec succès";
            } else {
                throw new Exception("Erreur lors de la mise à jour des paramètres");
            }

        } catch (Exception $e) {
            error_log("Erreur lors de la mise à jour des paramètres: " . $e->getMessage());
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: ' . BASE_URL . '/public/parametres');
        exit();
    }

    private function handleLogoUpload($file) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception("Type de fichier non autorisé. Utilisez JPG, PNG ou GIF.");
        }

        if ($file['size'] > $maxSize) {
            throw new Exception("Le fichier est trop volumineux. Taille maximum : 5MB");
        }

        $filename = uniqid() . '_' . basename($file['name']);
        $targetPath = $this->uploadsDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new Exception("Erreur lors du téléchargement du logo");
        }

        return $filename;
    }
}