<?php
require_once __DIR__ . '/../core/Controller.php';

class AuthController extends Controller {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function showLogin() {
        return $this->view('auth/login');
    }

    // Removed duplicate showRegister method to resolve the error.

    public function login() {
        // Si déjà connecté, rediriger vers le dashboard approprié
        if (isset($_SESSION['user_id'])) {
            if ($_SESSION['user_role'] === 'admin') {
                header('Location: ' . BASE_URL . '/public/dashboard/admin');
            } else {
                header('Location: ' . BASE_URL . '/public/dashboard/user');
            }
            exit();
        }

        // Traitement du formulaire de connexion
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $identifiant = filter_input(INPUT_POST, 'identifiant', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];

            try {
                // Debug log
                error_log("Login attempt for identifiant: {$identifiant}, email: {$email}");

                $stmt = $this->conn->prepare(
                    "SELECT * FROM utilisateurs 
                     WHERE email = ? 
                     AND Nid = ? 
                     AND statut = TRUE"
                );
                $stmt->execute([$email, $identifiant]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($password, $user['mot_de_passe'])) {
                    // Set session data
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['nom'];
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['user_identifiant'] = $user['identifiant'];
                    $_SESSION['logged_in'] = true;

                    // Debug log
                    error_log("User authenticated successfully. Role: " . $user['role']);
                    error_log("Session data: " . print_r($_SESSION, true));

                    // Redirect based on role
                    switch($user['role']) {
                        case 'admin':
                            header('Location: ' . BASE_URL . '/public/dashboard/admin');
                            break;
                        case 'electeur':
                            header('Location: ' . BASE_URL . '/public/dashboard/user');
                            break;
                        default:
                            header('Location: ' . BASE_URL . '/public/dashboard/user');
                    }
                    exit();
                }

                // Invalid credentials
                error_log("Login failed for identifiant: {$identifiant}, email: {$email}");
                return $this->view('auth/login', [
                    'error' => 'Identifiant, email ou mot de passe incorrect'
                ]);

            } catch(PDOException $e) {
                error_log("Database error during login: " . $e->getMessage());
                return $this->view('auth/login', [
                    'error' => 'Erreur lors de la connexion'
                ]);
            }
        }

        // Afficher le formulaire de connexion
        return $this->view('auth/login');
    }

    public function showRegister() {
        return $this->view('auth/register');
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Sanitize and validate input
                $identifiant = filter_input(INPUT_POST, 'identifiant', FILTER_SANITIZE_STRING);
                $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
                $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
                $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
                $date_naissance = $_POST['date_naissance'];
                $sexe = $_POST['sexe'];
                // Set default role as 'electeur'
                $rol = $_POST['role'];

                // Debug log
                error_log("Processing registration for email: " . $email);

                // Vérifier si l'email existe déjà
                $stmt = $this->conn->prepare("SELECT COUNT(*) FROM utilisateurs WHERE email = ?");
                $stmt->execute([$email]);
                $emailExists = $stmt->fetchColumn();

                if ($emailExists) {
                    error_log("Email already exists: " . $email);
                    return $this->view('auth/register', [
                        'error' => 'Cet email est déjà utilisé.'
                    ]);
                }

                // Insérer l'utilisateur dans la base de données
                $sql = "INSERT INTO utilisateurs (Nid, nom, email, mot_de_passe, date_naissance, sexe, role, statut) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, TRUE)";
                
                $stmt = $this->conn->prepare($sql);
                $result = $stmt->execute([
                    $identifiant,
                    $nom,
                    $email,
                    $mot_de_passe,
                    $date_naissance,
                    $sexe,
                    $rol
                ]);

                if ($result) {
                    error_log("User registered successfully: " . $email);
                    $_SESSION['success'] = "Inscription réussie. Veuillez vous connecter.";
                    header('Location: ' . BASE_URL . '/public/auth/login');
                    exit();
                } else {
                    error_log("Database insert failed for email: " . $email);
                    throw new PDOException("Database insert failed");
                }

            } catch(PDOException $e) {
                error_log("Registration error: " . $e->getMessage());
                return $this->view('auth/register', [
                    'error' => 'Une erreur est survenue lors de l\'inscription'
                ]);
            }
        }
        return $this->view('auth/register');
    }

    public function logout() {
        // Clear session
        session_destroy();
        
        // Clear remember me cookie if exists
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/');
        }
        
        return $this->redirect('/auth/login');
    }
}