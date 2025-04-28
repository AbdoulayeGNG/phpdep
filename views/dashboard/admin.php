<?php 
require_once __DIR__ . '/../../bootstrap.php';
$pageTitle = "Dashboard Administrateur";
require_once __DIR__ . '/../../views/layout/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php require_once __DIR__ . '/../layout/sidebar.php'; ?>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Tableau de bord Administrateur</h1>
            </div>

            <!-- Statistiques -->
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Électeurs inscrits</h5>
                            <p class="card-text display-6">150</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Élections en cours</h5>
                            <p class="card-text display-6">2</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5 class="card-title">Votes enregistrés</h5>
                            <p class="card-text display-6">89</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            Actions rapides
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                <a href="<?= BASE_URL ?>/elections/create" class="list-group-item list-group-item-action">
                                    <i class="bi bi-plus-circle"></i> Créer une nouvelle élection
                                </a>
                                <a href="<?= BASE_URL ?>/users/manage" class="list-group-item list-group-item-action">
                                    <i class="bi bi-people"></i> Gérer les utilisateurs
                                </a>
                                <a href="<?= BASE_URL ?>/reports" class="list-group-item list-group-item-action">
                                    <i class="bi bi-file-text"></i> Voir les rapports
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require_once __DIR__ . '/../../views/layout/footer.php'; ?>