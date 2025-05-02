<?php 
require_once __DIR__ . '/../../bootstrap.php';
$pageTitle = "Candidats - " . htmlspecialchars($election['titre']);
require_once __DIR__ . '/../layout/header.php';
?>

<div class="container-fluid py-5 bg-custom" style="margin-top: 70px;">
    <div class="content-wrapper">
        <div class="container">
            <!-- En-tête de l'élection -->
            <div class="card border-0 rounded-4 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h1 class="h3 fw-bold mb-3"><?= htmlspecialchars($election['titre']) ?></h1>
                    <p class="text-muted mb-3"><?= htmlspecialchars($election['description']) ?></p>
                    <div class="d-flex align-items-center gap-3">
                        <span class="badge bg-danger rounded-pill px-3 py-2">
                            <i class="bi bi-clock me-1"></i>
                            <span class="countdown" data-end="<?= $election['date_fin'] ?>">
                                <?= date('d/m/Y H:i', strtotime($election['date_fin'])) ?>
                            </span>
                        </span>
                        <?php if($a_vote): ?>
                            <span class="badge bg-success rounded-pill px-3 py-2">
                                <i class="bi bi-check2-circle me-1"></i>
                                Vote effectué
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Liste des candidats -->
            <div class="row g-4">
                <?php foreach($candidats as $candidat): ?>
                    <div class="col-md-4">
                        <div class="card border-0 rounded-4 shadow-sm h-100 hover-shadow">
                            <div class="card-body p-4">
                                <div class="text-center mb-3">
                                    <?php if($candidat['photo']): ?>
                                        <img src="<?= BASE_URL ?>/public/uploads/candidats/<?= htmlspecialchars($candidat['photo']) ?>" 
                                             alt="Photo de <?= htmlspecialchars($candidat['nom']) ?>"
                                             class="rounded-circle mb-3"
                                             style="width: 120px; height: 120px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3"
                                             style="width: 120px; height: 120px;">
                                            <i class="bi bi-person display-4 text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                    <h3 class="h5 fw-bold mb-1"><?= htmlspecialchars($candidat['nom']) ?></h3>
                                    <p class="text-muted small mb-3"><?= htmlspecialchars($candidat['parti']) ?></p>
                                </div>
                                <p class="text-muted mb-4"><?= htmlspecialchars($candidat['programme']) ?></p>
                                
                                <?php if(!$a_vote): ?>
                                    <div class="d-grid">
                                        <a href="<?= BASE_URL ?>/public/vote/election/<?= $election['id'] ?>" 
                                           class="btn btn-primary rounded-pill">
                                            <i class="bi bi-check2-circle me-2"></i>
                                            Voter pour ce candidat
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer1.php'; ?>