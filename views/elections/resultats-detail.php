<?php 
require_once __DIR__ . '/../../bootstrap.php';
$pageTitle = "Résultats - " . htmlspecialchars($election['titre']);
// Utiliser la navbar publique si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user_id'])) {
    require_once __DIR__ . '/../layout/navbar-public.php';
} else {
    require_once __DIR__ . '/../layout/header.php';
}
?>

<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <main class="col-md-10 col-lg-8 px-md-4">
            <!-- Ajout d'un bandeau d'information pour les visiteurs -->
            <?php if (!isset($_SESSION['user_id'])): ?>
                <div class="alert alert-info rounded-4 shadow-sm text-center mb-4" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Connectez-vous</strong> pour participer aux élections en cours et voter pour vos candidats préférés.
                </div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger rounded-4 shadow-sm text-center" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php else: ?>
                <!-- En-tête -->
                <div class="text-center mb-5">
                    <h1 class="display-4 fw-bold text-primary mb-3">
                        <?php echo htmlspecialchars($election['titre']); ?>
                    </h1>
                    <p class="lead text-muted">
                        Résultats finaux
                    </p>
                    <div class="d-flex justify-content-center gap-4 mt-4">
                        <div class="text-center">
                            <h3 class="h4 mb-0"><?php echo number_format($election['participants']); ?></h3>
                            <small class="text-muted">Participants</small>
                        </div>
                        <div class="text-center">
                            <h3 class="h4 mb-0"><?php echo number_format($election['nombre_votes']); ?></h3>
                            <small class="text-muted">Votes totaux</small>
                        </div>
                    </div>
                </div>

                <!-- Résultats des candidats -->
                <h2 class="h4 mb-4">Résultats par candidat</h2>
                <div class="row row-cols-1 row-cols-md-2 g-4 mb-5">
                    <?php foreach ($resultats as $index => $resultat): ?>
                        <div class="col">
                            <div class="card h-100 border-0 rounded-4 shadow-sm">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <?php if (!empty($resultat['photo'])): ?>
                                            <img src="<?= BASE_URL ?>/public/<?= htmlspecialchars($resultat['photo']) ?>" 
                                                 class="rounded-circle me-3"
                                                 style="width: 60px; height: 60px; object-fit: cover;"
                                                 alt="">
                                        <?php endif; ?>
                                        <div>
                                            <h5 class="card-title mb-0">
                                                <?php echo htmlspecialchars($resultat['nom_candidat']); ?>
                                            </h5>
                                            <?php if ($index === 0): ?>
                                                <span class="badge bg-success">Gagnant</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="progress mb-3" style="height: 25px;">
                                        <div class="progress-bar" 
                                             role="progressbar"
                                             style="width: <?php echo $resultat['pourcentage']; ?>%;"
                                             aria-valuenow="<?php echo $resultat['pourcentage']; ?>"
                                             aria-valuemin="0"
                                             aria-valuemax="100">
                                            <?php echo $resultat['pourcentage']; ?>%
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <h6 class="mb-0">
                                            <?php echo number_format($resultat['nombre_votes']); ?> votes
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Statistiques géographiques -->
                <h2 class="h4 mb-4">Répartition géographique des votes</h2>
                <div class="card border-0 rounded-4 shadow-sm">
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Département</th>
                                        <th>Nombre de votes</th>
                                        <th>Pourcentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($stats_geo as $stat): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($stat['departement']); ?></td>
                                            <td><?php echo number_format($stat['nombre_votes']); ?></td>
                                            <td><?php echo $stat['pourcentage']; ?>%</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<style>
.progress-bar {
    background-color: var(--bs-primary);
    transition: width 1.5s ease-in-out;
}

.hover-shadow {
    transition: all 0.3s ease;
}

.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;
}
</style>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>