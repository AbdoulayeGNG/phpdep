<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="<?= BASE_URL ?>/views//dashboard/admin">
                    <i class="bi bi-house-door"></i>
                    Tableau de bord
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= BASE_URL ?>/public/elections">
                    <i class="bi bi-calendar3"></i>
                    Élections
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?= BASE_URL ?>/public/users">
                    <i class="bi bi-people"></i>
                    Utilisateurs
                </a>
            </li>
            <!-- Ajout du bouton pour la gestion des candidats -->
            <li class="nav-item">
                <a class="nav-link" href="<?= BASE_URL ?>/public/candidats">
                    <i class="bi bi-person-badge"></i>
                    Candidats
                </a>
            </li>
            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], 'rapports') !== false ? 'active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>/public/rapports">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                        Rapports
                    </a>
                </li>
            <?php endif; ?>
            <li class="nav-item">
                <a class="nav-link" href="<?= BASE_URL ?>/settings">
                    <i class="bi bi-gear"></i>
                    Paramètres
                </a>
            </li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Actions rapides</span>
        </h6>
        <ul class="nav flex-column mb-2">
            <li class="nav-item">
                <a class="nav-link" href="<?= BASE_URL ?>/elections/create">
                    <i class="bi bi-plus-circle"></i>
                    Nouvelle élection
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-danger" href="<?= BASE_URL ?>/auth/logout">
                    <i class="bi bi-box-arrow-right"></i>
                    Déconnexion
                </a>
            </li>
        </ul>
    </div>
</nav>