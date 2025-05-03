<?php 
require_once __DIR__ . '/../../bootstrap.php';
$pageTitle = "Résultats des élections";
// Utiliser la navbar appropriée
if (!isset($_SESSION['user_id'])) {
    require_once __DIR__ . '/../layout/navbar-public.php';
} else {
    require_once __DIR__ . '/../layout/header.php';
}
?>

<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <main class="col-md-10 col-lg-8 px-md-4">
            <?php if (!isset($_SESSION['user_id'])): ?>
                <div class="alert alert-info rounded-4 shadow-sm text-center mb-4" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    Bienvenue sur notre système de vote en ligne ! 
                    <strong><a href="<?= BASE_URL ?>/auth/login" class="alert-link">Connectez-vous</a></strong> 
                    pour participer aux élections en cours.
                </div>
            <?php endif; ?>

            <!-- Page Header -->
            <div class="d-flex justify-content-center flex-wrap flex-md-nowrap align-items-center pb-3 mb-4">
                <h1 class="h2 text-primary text-center position-relative">
                    <i class="bi bi-graph-up me-2"></i>
                    Résultats des élections
                    <span class="underline"></span>
                </h1>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger rounded-4 shadow-sm text-center" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if (empty($elections)): ?>
                <div class="alert alert-info rounded-4 shadow-sm text-center" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    Aucune élection terminée n'est disponible.
                </div>
            <?php else: ?>
                <div class="row row-cols-1 row-cols-md-2 g-4">
                    <?php foreach ($elections as $election): ?>
                        <div class="col">
                            <div class="card h-100 border-0 rounded-4 shadow-sm hover-shadow">
                                <div class="card-body p-4">
                                    <h5 class="card-title text-primary mb-3">
                                        <?php echo htmlspecialchars($election['titre']); ?>
                                    </h5>
                                    
                                    <div class="d-flex justify-content-between mb-3">
                                        <div class="text-muted">
                                            <i class="bi bi-people me-2"></i>
                                            <?php echo number_format($election['participants']); ?> participants
                                        </div>
                                        <div class="text-muted">
                                            <i class="bi bi-check2-square me-2"></i>
                                            <?php echo number_format($election['nombre_votes']); ?> votes
                                        </div>
                                    </div>

                                    <a href="<?= BASE_URL ?>/elections/resultats/<?= $election['id'] ?>" 
                                       class="btn btn-primary w-100">
                                        <i class="bi bi-graph-up me-2"></i>
                                        Voir les résultats détaillés
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>
<style>
/* Styles pour le bouton de connexion */
.btn-light {
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-light:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15)!important;
}

/* Style pour la navbar */
.navbar-brand {
    font-weight: 600;
    font-size: 1.25rem;
}

/* Style pour l'alert d'information */
.alert-info {
    background-color: rgba(var(--bs-info-rgb), 0.1);
    border: none;
}

.alert-link {
    text-decoration: none;
    font-weight: 600;
}

.alert-link:hover {
    text-decoration: underline;
}

.navbar {
    padding-top: 1rem;
    padding-bottom: 1rem;
}

.btn-outline-light:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15)!important;
}
</style>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>