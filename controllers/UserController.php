<?php

require_once __DIR__ . '/../core/Controller.php';

class UserController extends Controller {
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
            // La connexion est déjà initialisée dans le constructeur, pas besoin de la réinitialiser
            $sql = "SELECT id, Nid, nom, email, role, statut, date_naissance 
                    FROM utilisateurs 
                    ORDER BY date_naissance DESC";
            
            // Debug log
            error_log("Executing SQL query in UserController@index");
            
            $stmt = $this->conn->query($sql);
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Debug log
            error_log("Number of users found: " . count($users));
            error_log("Users data: " . print_r($users, true));

            return $this->view('users/index', [
                'users' => $users,
                'success' => isset($_SESSION['success']) ? $_SESSION['success'] : null
            ]);

        } catch(PDOException $e) {
            error_log("Database error in UserController@index: " . $e->getMessage());
            return $this->view('users/index', [
                'error' => 'Erreur lors de la récupération des utilisateurs: ' . $e->getMessage(),
                'users' => []
            ]);
        } finally {
            // Clear any success message
            if (isset($_SESSION['success'])) {
                unset($_SESSION['success']);
            }
        }
    }

    public function create() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/public/auth/login');
            exit();
        }

        return $this->view('users/create');
    }

    public function store() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/public/auth/login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validation des champs requis
                if (empty($_POST['Nid'])) throw new Exception("L'identifiant est requis");
                if (empty($_POST['nom'])) throw new Exception("Le nom est requis");
                if (empty($_POST['email'])) throw new Exception("L'email est requis");
                if (empty($_POST['mot_de_passe'])) throw new Exception("Le mot de passe est requis");
                if (empty($_POST['date_naissance'])) throw new Exception("La date de naissance est requise");
                if (empty($_POST['sexe'])) throw new Exception("Le sexe est requis");
                if (empty($_POST['role'])) throw new Exception("Le rôle est requis");

                // Sanitize and validate input
                $Nid = filter_input(INPUT_POST, 'Nid', FILTER_SANITIZE_STRING);
                $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
                $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
                
                // Validation de l'email
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new Exception("L'adresse email n'est pas valide");
                }

                $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
                $date_naissance = $_POST['date_naissance'];
                $sexe = $_POST['sexe'];
                $role = $_POST['role'];

                // Debug log
                error_log("Processing user creation - Data received: " . print_r($_POST, true));

                // Vérifier si l'identifiant existe déjà
                $stmt = $this->conn->prepare("SELECT COUNT(*) FROM utilisateurs WHERE Nid = ?");
                $stmt->execute([$Nid]);
                if ($stmt->fetchColumn() > 0) {
                    throw new Exception("Cet identifiant est déjà utilisé");
                }

                // Vérifier si l'email existe déjà
                $stmt = $this->conn->prepare("SELECT COUNT(*) FROM utilisateurs WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetchColumn() > 0) {
                    throw new Exception("Cet email est déjà utilisé");
                }

                // Insérer l'utilisateur dans la base de données
                $sql = "INSERT INTO utilisateurs (Nid, nom, email, mot_de_passe, date_naissance, sexe, role, statut) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, TRUE)";
                
                $stmt = $this->conn->prepare($sql);
                $result = $stmt->execute([
                    $Nid,
                    $nom,
                    $email,
                    $mot_de_passe,
                    $date_naissance,
                    $sexe,
                    $role
                ]);

                if ($result) {
                    error_log("User created successfully: " . $email);
                    $_SESSION['success'] = "L'utilisateur a été créé avec succès";
                    header('Location: ' . BASE_URL . '/public/users');
                    exit();
                } else {
                    throw new Exception("Erreur lors de l'insertion dans la base de données");
                }

            } catch(Exception $e) {
                error_log("User creation error: " . $e->getMessage());
                return $this->view('users/create', [
                    'error' => $e->getMessage(),
                    'old' => $_POST
                ]);
            }
        }
        return $this->view('users/create');
    }

    public function edit($id) {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/public/auth/login');
            exit();
        }

        try {
            // Debug - Afficher l'ID reçu
            error_log("Editing user with ID: " . $id);

            $stmt = $this->conn->prepare("SELECT id, Nid, nom, email, date_naissance, sexe, role, statut 
                                        FROM utilisateurs 
                                        WHERE id = ?");
            $stmt->execute([$id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                $_SESSION['error'] = "Utilisateur non trouvé";
                header('Location: ' . BASE_URL . '/public/users');
                exit();
            }

            // Debug - Afficher les données récupérées
            error_log("User data found: " . print_r($user, true));

            return $this->view('users/edit', ['user' => $user]);

        } catch (PDOException $e) {
            error_log("Database error in edit: " . $e->getMessage());
            $_SESSION['error'] = "Erreur lors du chargement de l'utilisateur";
            header('Location: ' . BASE_URL . '/public/users');
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
                // Validation des champs requis
                if (empty($_POST['Nid'])) throw new Exception("L'identifiant est requis");
                if (empty($_POST['nom'])) throw new Exception("Le nom est requis");
                if (empty($_POST['email'])) throw new Exception("L'email est requis");
                if (empty($_POST['date_naissance'])) throw new Exception("La date de naissance est requise");
                if (empty($_POST['sexe'])) throw new Exception("Le sexe est requis");
                if (empty($_POST['role'])) throw new Exception("Le rôle est requis");

                // Sanitize and validate input
                $Nid = filter_input(INPUT_POST, 'Nid', FILTER_SANITIZE_STRING);
                $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
                $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
                $date_naissance = $_POST['date_naissance'];
                $sexe = filter_input(INPUT_POST, 'sexe', FILTER_SANITIZE_STRING);
                $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING);
                $statut = isset($_POST['statut']) ? 1 : 0;

                // Validate email format
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new Exception("L'adresse email n'est pas valide");
                }

                // Vérifier si l'identifiant existe déjà pour un autre utilisateur
                $stmt = $this->conn->prepare("SELECT COUNT(*) FROM utilisateurs WHERE Nid = ? AND id != ?");
                $stmt->execute([$Nid, $id]);
                if ($stmt->fetchColumn() > 0) {
                    throw new Exception("Cet identifiant est déjà utilisé par un autre utilisateur");
                }

                // Vérifier si l'email existe déjà pour un autre utilisateur
                $stmt = $this->conn->prepare("SELECT COUNT(*) FROM utilisateurs WHERE email = ? AND id != ?");
                $stmt->execute([$email, $id]);
                if ($stmt->fetchColumn() > 0) {
                    throw new Exception("Cet email est déjà utilisé par un autre utilisateur");
                }

                $sql = "UPDATE utilisateurs 
                        SET Nid = ?, nom = ?, email = ?, date_naissance = ?, 
                            sexe = ?, role = ?, statut = ? 
                        WHERE id = ?";
                
                $stmt = $this->conn->prepare($sql);
                $result = $stmt->execute([
                    $Nid,
                    $nom,
                    $email,
                    $date_naissance,
                    $sexe,
                    $role,
                    $statut,
                    $id
                ]);

                if ($result) {
                    $_SESSION['success'] = "L'utilisateur a été modifié avec succès";
                    header('Location: ' . BASE_URL . '/public/users');
                    exit();
                } else {
                    throw new Exception("Erreur lors de la modification de l'utilisateur");
                }

            } catch(Exception $e) {
                error_log("User update error: " . $e->getMessage());
                return $this->view('users/edit', [
                    'error' => $e->getMessage(),
                    'user' => $_POST
                ]);
            }
        }
        
        header('Location: ' . BASE_URL . '/public/users');
        exit();
    }

    public function delete($id) {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/public/auth/login');
            exit();
        }

        try {
            $stmt = $this->conn->prepare("DELETE FROM utilisateurs WHERE id = ? AND role != 'admin'");
            $result = $stmt->execute([$id]);

            if ($result) {
                $_SESSION['success'] = "L'utilisateur a été supprimé avec succès";
            } else {
                $_SESSION['error'] = "Impossible de supprimer cet utilisateur";
            }
        } catch(PDOException $e) {
            error_log("User deletion error: " . $e->getMessage());
            $_SESSION['error'] = "Erreur lors de la suppression de l'utilisateur";
        }

        header('Location: ' . BASE_URL . '/public/users');
        exit();
    }
}