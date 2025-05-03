<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <main class="col-md-10 col-lg-8 px-md-4">
            <!-- Page Header -->
            <div class="d-flex justify-content-center flex-wrap flex-md-nowrap align-items-center pb-3 mb-4">
                <h1 class="h2 text-primary text-center position-relative">
                    <i class="bi bi-calendar2-check me-2"></i>
                    Élections en cours
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
                    Aucune élection n'est en cours actuellement.
                </div>
            <?php else: ?>
                <div class="row row-cols-1 row-cols-md-2 g-4 justify-content-center">
                    <?php foreach ($elections as $election): ?>
                        <div class="col">
                            <div class="card h-100 border-0 rounded-4 shadow-sm hover-shadow">
                                <div class="card-body p-4 d-flex flex-column">
                                    <!-- En-tête de la carte -->
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="card-title text-primary mb-0 fs-4 fw-bold">
                                            <?php echo htmlspecialchars($election['titre']); ?>
                                        </h5>
                                        <span class="badge bg-primary rounded-pill px-3 py-2">En cours</span>
                                    </div>
                                    
                                    <!-- Description -->
                                    <p class="card-text text-muted mb-4 flex-grow-1">
                                        <?php echo htmlspecialchars($election['description']); ?>
                                    </p>
                                    
                                    <!-- Pied de la carte -->
                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <div class="text-muted fs-6">
                                                <i class="bi bi-clock me-1"></i>
                                                Fin dans: <span class="countdown fw-bold text-primary" 
                                                            data-end="<?php echo $election['date_fin']; ?>">
                                                </span>
                                            </div>
                                            <div class="text-muted fs-6">
                                                <i class="bi bi-people me-1"></i>
                                                <?php 
                                                $candidats = isset($election['candidats']) ? $election['candidats'] : array();
                                                echo count($candidats); 
                                                ?> candidats
                                            </div>
                                        </div>

                                        <?php if (isset($election['a_vote']) && $election['a_vote']): ?>
                                            <div class="alert alert-success rounded-3 mb-0 d-flex align-items-center justify-content-center">
                                                <i class="bi bi-check-circle me-2"></i>
                                                Vous avez déjà voté pour cette élection
                                            </div>
                                        <?php else: ?>
                                            <button type="button" 
                                                    class="btn btn-primary w-100 rounded-3 py-3 d-flex align-items-center justify-content-center"
                                                    onclick="window.location.href='<?= BASE_URL ?>/public/elections/voter/<?= $election['id'] ?>'">
                                                <i class="bi bi-check2-circle me-2"></i>
                                                Voter
                                            </button>
                                        <?php endif; ?>
                                    </div>
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
/* Variables CSS */
:root {
    --primary-color: #0d6efd;
    --card-height: 400px;
    --transition-duration: 0.3s;
}

/* Conteneur principal */
.container-fluid {
    max-width: 1400px;
    margin: 0 auto;
}

/* Style des cartes */
.card {
    min-height: var(--card-height);
    max-width: 600px;
    margin: 0 auto;
    background: #ffffff;
}

.hover-shadow {
    transition: all var(--transition-duration) cubic-bezier(0.165, 0.84, 0.44, 1);
}

.hover-shadow:hover {
    transform: translateY(-8px);
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.15) !important;
}

/* Titre de la page */
.h2.text-primary {
    margin-bottom: 2rem;
    display: inline-block;
}

.h2.text-primary .underline {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--primary-color);
    border-radius: 3px;
    margin: 0 auto;
    width: 100px;
}

/* Badges et boutons */
.badge {
    font-size: 0.9rem;
    font-weight: 500;
}

.btn-primary, .alert {
    min-height: 60px;
    font-size: 1.1rem;
}

/* Texte et polices */
.countdown {
    font-family: 'SF Mono', 'Fira Code', monospace;
    font-size: 1.1rem;
}

.card-text {
    font-size: 1.1rem;
    line-height: 1.6;
    min-height: 80px;
}

/* États des cartes */
.alert-success {
    background-color: #d1e7dd;
    border-color: #badbcc;
    color: #0f5132;
}
</style>

<script>
function updateCountdowns() {
    document.querySelectorAll('.countdown').forEach(element => {
        const endTime = new Date(element.dataset.end).getTime();
        const now = new Date().getTime();
        const distance = endTime - now;

        if (distance < 0) {
            element.innerHTML = '<span class="text-danger">Terminé</span>';
            return;
        }

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));

        if (days > 0) {
            element.innerHTML = `${days}j ${hours}h ${minutes}m`;
        } else {
            element.innerHTML = `${hours}h ${minutes}m`;
        }
    });
}

// Mettre à jour toutes les minutes
setInterval(updateCountdowns, 60000);
updateCountdowns();
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>