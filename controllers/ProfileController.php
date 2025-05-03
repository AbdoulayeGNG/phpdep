<?php

class ProfileController extends Controller {
    protected $conn;

    public function __construct() {
        parent::__construct();
        // Initialize database connection
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function edit() {
        try {
            if (!isset($_SESSION['user_id'])) {
                header('Location: ' . BASE_URL . '/auth/login');
                exit;
            }

            $stmt = $this->conn->prepare("SELECT * FROM utilisateurs WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                throw new Exception('Utilisateur non trouvé');
            }

            return $this->view('profile/edit', ['user' => $user]);

        } catch (Exception $e) {
            error_log("Erreur dans edit profile : " . $e->getMessage());
            return $this->view('profile/edit', [
                'error' => 'Une erreur est survenue lors du chargement du profil'
            ]);
        }
    }

    public function update() {
        try {
            if (!isset($_SESSION['user_id'])) {
                header('Location: ' . BASE_URL . '/auth/login');
                exit;
            }

            // Using isset() instead of null coalescing operator
            $nom = isset($_POST['nom']) ? $_POST['nom'] : '';
            $email = isset($_POST['email']) ? $_POST['email'] : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            $currentPassword = isset($_POST['current_password']) ? $_POST['current_password'] : '';

            // Validate required fields
            if (empty($nom) || empty($email) || empty($currentPassword)) {
                return $this->view('profile/edit', [
                    'error' => 'Veuillez remplir tous les champs obligatoires'
                ]);
            }

            // Verify current password
            $stmt = $this->conn->prepare("SELECT mot_de_passe FROM utilisateurs WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!password_verify($currentPassword, $user['mot_de_passe'])) {
                return $this->view('profile/edit', [
                    'error' => 'Le mot de passe actuel est incorrect'
                ]);
            }

            // Update profile
            if (!empty($password)) {
                $stmt = $this->conn->prepare(
                    "UPDATE utilisateurs 
                     SET nom = ?, email = ?, mot_de_passe = ? 
                     WHERE id = ?"
                );
                $stmt->execute([
                    $nom,
                    $email,
                    password_hash($password, PASSWORD_DEFAULT),
                    $_SESSION['user_id']
                ]);
            } else {
                $stmt = $this->conn->prepare(
                    "UPDATE utilisateurs 
                     SET nom = ?, email = ? 
                     WHERE id = ?"
                );
                $stmt->execute([
                    $nom,
                    $email,
                    $_SESSION['user_id']
                ]);
            }

            // Fetch updated user data
            $stmt = $this->conn->prepare("SELECT * FROM utilisateurs WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $updatedUser = $stmt->fetch(PDO::FETCH_ASSOC);

            return $this->view('dashboard/electeur', [
                'success' => 'Profil mis à jour avec succès',
                'user' => $updatedUser
            ]);

        } catch (PDOException $e) {
            error_log("Erreur dans update profile : " . $e->getMessage());
            return $this->view('profile/edit', [
                'error' => 'Une erreur est survenue lors de la mise à jour du profil'
            ]);
        }
    }
}