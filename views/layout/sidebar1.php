<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <!-- User Profile Section -->
        <div class="user-profile px-3 pb-4 border-bottom">
            <div class="d-flex align-items-center mb-3">
                <div class="flex-shrink-0">
                    <i class="bi bi-person-circle fs-1"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="mb-0"><?= isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Électeur' ?></h6>
                    <small class="text-muted">Électeur</small>
                </div>
            </div>
        </div>

        <!-- Main Navigation -->
        <ul class="nav flex-column mt-3">
            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'dashboard/electeur') !== false ? 'active' : '' ?>" 
                   href="<?= BASE_URL ?>/dashboard/electeur">
                    <i class="bi bi-speedometer2"></i>
                    <span>Tableau de bord</span>
                </a>
            </li>

            <!-- Elections Section -->
            <li class="nav-section mt-3">
                <h6 class="sidebar-heading px-3 mt-4 mb-1 text-muted text-uppercase">
                    <span>Élections</span>
                </h6>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'elections/en-cours') !== false ? 'active' : '' ?>" 
                   href="<?= BASE_URL ?>/elections/en-cours">
                    <i class="bi bi-check2-square"></i>
                    <span>Élections en cours</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'elections/a-venir') !== false ? 'active' : '' ?>" 
                   href="<?= BASE_URL ?>/elections/a-venir">
                    <i class="bi bi-calendar-event"></i>
                    <span>Élections à venir</span>
                </a>
            </li>

            <!-- Votes Section -->
            <li class="nav-section mt-3">
                <h6 class="sidebar-heading px-3 mt-4 mb-1 text-muted text-uppercase">
                    <span>Mes Activités</span>
                </h6>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'mes-votes') !== false ? 'active' : '' ?>" 
                   href="<?= BASE_URL ?>/mes-votes">
                    <i class="bi bi-list-check"></i>
                    <span>Historique des votes</span>
                </a>
            </li>

            <!-- Settings Section -->
            <li class="nav-section mt-3">
                <h6 class="sidebar-heading px-3 mt-4 mb-1 text-muted text-uppercase">
                    <span>Paramètres</span>
                </h6>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'profile') !== false ? 'active' : '' ?>" 
                   href="<?= BASE_URL ?>/profile">
                    <i class="bi bi-person"></i>
                    <span>Mon profil</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'notifications') !== false ? 'active' : '' ?>" 
                   href="<?= BASE_URL ?>/notifications">
                    <i class="bi bi-bell"></i>
                    <span>Notifications</span>
                </a>
            </li>

            <!-- Logout -->
            <li class="nav-item mt-4">
                <a class="nav-link text-danger" href="<?= BASE_URL ?>/auth/logout">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Déconnexion</span>
                </a>
            </li>
        </ul>
    </div>
</nav>

<!-- Add Bootstrap Icons CSS if not already included -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">

<!-- Add custom CSS for sidebar -->
<style>
.sidebar {
    position: fixed;
    height: 100vh;
    padding-top: 1rem;
    background-color: #f8f9fa;
    border-right: 1px solid #dee2e6;
    z-index: 100;
}

.sidebar .nav-link {
    color: #333;
    padding: .75rem 1rem;
    font-weight: 500;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.sidebar .nav-link:hover {
    background-color: rgba(0, 0, 0, .05);
    border-radius: 5px;
    padding-left: 1.25rem;
}

.sidebar .nav-link.active {
    color: #0d6efd;
    background-color: rgba(13, 110, 253, .1);
    border-radius: 5px;
}

.sidebar .nav-link i {
    margin-right: .75rem;
    font-size: 1.1rem;
    width: 1.5rem;
    text-align: center;
}

.sidebar-heading {
    font-size: 0.75rem;
    font-weight: 600;
}

.user-profile {
    transition: all 0.3s ease;
}

.user-profile:hover {
    background-color: rgba(0, 0, 0, .02);
}

@media (max-width: 767.98px) {
    .sidebar {
        position: static;
        height: auto;
        padding-top: 0;
    }
}
</style>