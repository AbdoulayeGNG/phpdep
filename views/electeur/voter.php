<?php 
require_once __DIR__ . '/../../bootstrap.php';
$pageTitle = "Voter - " . htmlspecialchars($election['titre']);
require_once __DIR__ . '/../layout/header.php';
?>

<div class="container py-5">
    <?php if (isset($error)): ?>
        <div class="alert alert-danger rounded-4 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php else: ?>
        <!-- En-tête de l'élection -->
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold text-primary mb-3">
                <?php echo htmlspecialchars($election['titre']); ?>
            </h1>
            <p class="lead text-muted mb-0">
                <?php echo htmlspecialchars($election['description']); ?>
            </p>
        </div>

        <div class="row row-cols-1 row-cols-md-3 g-4 justify-content-center">
            <?php foreach ($candidats as $candidat): ?>
                <div class="col">
                    <div class="card h-100 border-0 rounded-4 shadow-sm hover-shadow">
                        <?php if (!empty($candidat['photo'])): ?>
                            <div class="position-relative text-center mt-4">
                                <img src="<?= BASE_URL ?>/public/<?= htmlspecialchars($candidat['photo']) ?>" 
                                     class="rounded-circle candidate-photo shadow-sm" 
                                     alt="Photo de <?= htmlspecialchars($candidat['nom']) ?>">
                            </div>
                        <?php endif; ?>
                        
                        <div class="card-body p-4">
                            <!-- En-tête du candidat -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title mb-0 h4 fw-bold">
                                    <?php echo htmlspecialchars($candidat['nom']); ?>
                                </h5>
                                <span class="badge bg-primary rounded-pill px-3 py-2">
                                    Candidat
                                </span>
                            </div>

                            <!-- Informations du candidat -->
                            <div class="mb-3">
                                <p class="text-muted mb-2">
                                    <i class="bi bi-person-vcard me-2"></i>
                                    NID: <?php echo htmlspecialchars($candidat['Nid']); ?>
                                </p>
                            </div>

                            <!-- Programme du candidat -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-2">Programme :</h6>
                                <p class="card-text text-muted">
                                    <?php echo nl2br(htmlspecialchars($candidat['programme'])); ?>
                                </p>
                            </div>
                            
                            <!-- Formulaire de vote -->
                            <form action="<?= BASE_URL ?>/elections/voter" method="POST" class="mt-auto">
                                <input type="hidden" name="election_id" value="<?php echo $election['id']; ?>">
                                <input type="hidden" name="candidat_id" value="<?php echo $candidat['id']; ?>">
                                <button type="submit" 
                                        class="btn btn-primary btn-lg w-100 rounded-3 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-check2-circle me-2"></i>
                                    Voter pour ce candidat
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
/* Styles des cartes */
.card {
    transition: all 0.3s ease;
    min-height: 500px;
}

.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;
}

/* Style du titre */
.display-4 {
    font-size: 2.5rem;
}
/* Style de la photo du candidat */
.candidate-photo {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border: 4px solid #fff;
    margin-bottom: 1rem;
}

.position-relative {
    z-index: 1;
}

/* Effet de survol sur la photo */
.candidate-photo:hover {
    transform: scale(1.05);
    transition: transform 0.3s ease;
}

/* Style des badges */
.badge {
    font-weight: 500;
    font-size: 0.9rem;
}

/* Style des boutons */
.btn-lg {
    padding: 1rem 1.5rem;
    font-size: 1.1rem;
}

/* Style du texte du programme */
.card-text {
    font-size: 1rem;
    line-height: 1.6;
    max-height: 150px;
    overflow-y: auto;
}

/* Personnalisation de la barre de défilement */
.card-text::-webkit-scrollbar {
    width: 5px;
}

.card-text::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.card-text::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

.card-text::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>