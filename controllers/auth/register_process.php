<?php
require_once __DIR__ . '/../../bootstrap.php';
require_once __DIR__ . '/../../config/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input data
    $idenfifiant = filter_input(INPUT_POST, 'identifiant', FILTER_SANITIZE_STRING);
    if (empty($idenfifiant)) {
        $_SESSION['error'] = "L'identifiant est requis.";
        header("Location: " . BASE_URL . "/views/auth/register.php");
        exit();
    }
    $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
    $date_naissance = $_POST['date_naissance'];
    $sexe = $_POST['sexe'];
    $role = $_POST['role'];

    try {
        $db = new Database();
        $conn = $db->getConnection();

        // Check if email already exists
        $check_email = $conn->prepare("SELECT id FROM utilisateurs WHERE email = ?");
        $check_email->execute([$email]);

        if ($check_email->rowCount() > 0) {
            $_SESSION['error'] = "Cette adresse email existe déjà.";
            header("Location: " . BASE_URL . "/views/auth/register.php");
            exit();
        }

        // Prepare and execute the INSERT query
        $sql = "INSERT INTO utilisateurs (Nid,nom, email, mot_de_passe, role, date_naissance, sexe, statut) 
                VALUES (:identifiant,:nom, :email, :mot_de_passe, :role, :date_naissance, :sexe, TRUE)";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':identifiant' => $idenfifiant,
            ':nom' => $nom,
            ':email' => $email,
            ':mot_de_passe' => $mot_de_passe,
            ':role' => $role,
            ':date_naissance' => $date_naissance,
            ':sexe' => $sexe
        ]);

        $_SESSION['success'] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
        header("Location: " . BASE_URL . "/views/auth/login.php");
        exit();

    } catch (PDOException $e) {
        $_SESSION['error'] = "Une erreur est survenue lors de l'inscription.";
        header("Location: " . BASE_URL . "/views/auth/register.php");
        exit();
    }
} else {
    header("Location: " . BASE_URL . "/views/auth/register.php");
    exit();
}