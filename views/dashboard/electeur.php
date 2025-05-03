<?php 
require_once __DIR__ . '/../../bootstrap.php';
$pageTitle = "Accueil - Espace Électeur";
require_once __DIR__ . '/../layout/header.php';
?>

<!-- Top Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
    <div class="container-fluid px-4">
        <a class="navbar-brand d-flex align-items-center" href="<?= BASE_URL ?>">
            <img src="<?= BASE_URL ?>/public/assets/images/logo.jpg" alt="Logo" height="40" class="rounded-circle border border-light">
            <span class="ms-3 fw-bold">phpProject</span>
        </a>
        
        <!-- Profile Dropdown -->
        <div class="ms-auto">
            <div class="dropdown">
                <button class="btn btn-light rounded-pill shadow-sm dropdown-toggle d-flex align-items-center" 
                        type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle fs-5 me-2"></i>
                    <span class="fw-medium"><?= htmlspecialchars($user['nom']) ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" aria-labelledby="profileDropdown">
                    <!-- <li class="px-4 py-3">
                        <div class="d-flex flex-column">
                            <span class="text-dark fw-semibold mb-2">
                                <i class="bi bi-envelope-fill text-primary me-2"></i>
                                <?= htmlspecialchars($user['email']) ?>
                            </span>
                            <span class="text-muted">
                                <i class="bi bi-person-vcard-fill text-primary me-2"></i>
                                <?= htmlspecialchars($user['Nid']) ?>
                            </span>
                        </div>
                    </li> -->
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item py-2 fw-medium" href="<?= BASE_URL ?>/public/profile/edit">
                            <i class="bi bi-gear-fill me-2 text-secondary"></i>
                            Modifier le profil
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item py-2 fw-medium text-danger" href="<?= BASE_URL ?>/public/auth/logout">
                            <i class="bi bi-box-arrow-right me-2"></i>
                            Déconnexion
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container-fluid py-5 bg-custom" style="margin-top: 70px;">
    <div class="content-wrapper">
        <!-- Welcome Banner -->
        <div class="container mb-5">
            <div class="row">
                <div class="col-12">
                    <div class="bg-white rounded-4 shadow-sm p-5 text-center">
                        <h1 class="display-5 fw-bold text-gradient mb-3">Bienvenue dans votre espace électeur</h1>
                        <p class="lead text-muted mb-4">Participez aux élections en cours et suivez vos votes</p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="<?= BASE_URL ?>/public/elections/en-cours" 
                               class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm">
                                <i class="bi bi-check2-circle me-2"></i>
                                Participer aux élections
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="container mb-5">
            <div class="row g-4">
                <!-- Élections en cours -->
                <div class="col-md-4">
                    <div class="card border-0 rounded-4 shadow-sm h-100 hover-shadow">
                        <div class="card-body text-center p-4">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3 d-inline-block mb-3">
                                <i class="bi bi-calendar-check text-primary display-6"></i>
                            </div>
                            <h3 class="card-title h5 fw-bold mb-3">Élections en cours</h3>
                            <p class="display-6 fw-bold text-primary mb-0">
                                <?= count($elections_en_cours) ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Votes effectués -->
                <div class="col-md-4">
                    <div class="card border-0 rounded-4 shadow-sm h-100 hover-shadow">
                        <div class="card-body text-center p-4">
                            <div class="rounded-circle bg-success bg-opacity-10 p-3 d-inline-block mb-3">
                                <i class="bi bi-check2-square text-success display-6"></i>
                            </div>
                            <h3 class="card-title h5 fw-bold mb-3">Votes effectués</h3>
                            <p class="display-6 fw-bold text-success mb-0">
                                <?= $nombre_votes ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Prochaines élections -->
                <div class="col-md-4">
                    <div class="card border-0 rounded-4 shadow-sm h-100 hover-shadow">
                        <div class="card-body text-center p-4">
                            <div class="rounded-circle bg-info bg-opacity-10 p-3 d-inline-block mb-3">
                                <i class="bi bi-calendar-plus text-info display-6"></i>
                            </div>
                            <h3 class="card-title h5 fw-bold mb-3">Prochaines élections</h3>
                            <p class="display-6 fw-bold text-info mb-0">
                                <?= count($elections_a_venir) ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Elections Section -->
        <div class="container">
            <h2 class="h3 fw-bold mb-4">
                <i class="bi bi-clipboard2-check text-primary me-2"></i>
                Élections en cours
            </h2>
            
            <?php if(!empty($elections_en_cours)): ?>
                <div class="row g-4">
                    <?php foreach($elections_en_cours as $election): ?>
                        <div class="col-md-6">
                            <div class="card border-0 rounded-4 shadow-sm h-100 hover-shadow">
                                <div class="card-body p-4">
                                    <h3 class="h5 fw-bold mb-3">
                                        <?= htmlspecialchars($election['titre']) ?>
                                    </h3>
                                    <p class="text-muted mb-4">
                                        <?= htmlspecialchars(substr($election['description'], 0, 150)) ?>...
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center gap-3">
                                        <span class="badge bg-danger rounded-pill px-3 py-2">
                                            <i class="bi bi-clock me-1"></i>
                                            <span class="countdown" data-end="<?= $election['date_fin'] ?>">
                                                <?= date('d/m/Y H:i', strtotime($election['date_fin'])) ?>
                                            </span>
                                        </span>
                                        <div class="d-flex gap-2">
                
                                            <?php if($election['a_vote']): ?>
                                                <span class="badge bg-success rounded-pill px-3 py-2">
                                                    <i class="bi bi-check2-circle me-1"></i>
                                                    Vote effectué
                                                </span>
                                            <?php else: ?>
                                               
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <div class="text-muted mb-4">
                        <i class="bi bi-calendar-x display-1"></i>
                    </div>
                    <p class="lead text-muted">Aucune élection n'est en cours actuellement.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Custom CSS -->
<style>
.bg-custom {
    background-image: url('<?= BASE_URL ?>/public/assets/images/vote-bg.jpg');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    position: relative;
}

.bg-custom::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.93);
    z-index: 0;
}

.hover-shadow {
    transition: all 0.3s ease;
}

.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;
}

.text-gradient {
    /* background: linear-gradient(45deg, #0d6efd, #0dcaf0); */
    /* -webkit-background-clip: text; */
    -webkit-text-fill-color: transparent;
}

.content-wrapper {
    position: relative;
    z-index: 1;
}
</style>

<!-- Add this script before the existing scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all dropdowns
    var dropdowns = document.querySelectorAll('.dropdown-toggle');
    dropdowns.forEach(function(dropdown) {
        new bootstrap.Dropdown(dropdown);
    });
});
</script>

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

        element.innerHTML = `<i class="bi bi-clock"></i> Se termine dans ${days}j ${hours}h ${minutes}m`;
    });
}

setInterval(updateCountdowns, 60000);
updateCountdowns();
</script>

<?php require_once __DIR__ . '/../layout/footer1.php'; ?>