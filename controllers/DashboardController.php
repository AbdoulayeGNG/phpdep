<?php

class DashboardController extends Controller {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/public/auth/login');
            exit();
        }
    }

    public function index() {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/auth/login');
            exit;
        }

        // Récupérer le rôle de l'utilisateur
        $role = $_SESSION['user_role'];

        // Rediriger vers le dashboard approprié
        switch ($role) {
            case 'admin':
                return $this->view('dashboard/admin');
            case 'electeur':
                return $this->view('dashboard/electeur');
            case 'observateur':
                return $this->view('dashboard/observateur');
            default:
                header('Location: ' . BASE_URL . '/auth/login');
                exit;
        }
    }

    public function adminDashboard() {
        if ($_SESSION['user_role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/public/auth/login');
            exit();
        }
        return $this->view('dashboard/admin');
    }

    public function userDashboard() {
        return $this->view('dashboard/user');
    }
}