<?php 
require_once __DIR__ . '/../../bootstrap.php';
$pageTitle = "Modifier mon profil";
require_once __DIR__ . '/../layout/header.php';
?>

<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 rounded-4 shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h3 text-center mb-4">
                        <i class="bi bi-person-gear text-primary me-2"></i>
                        Modifier mon profil
                    </h1>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger rounded-3">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($success)): ?>
                        <div class="alert alert-success rounded-3">
                            <i class="bi bi-check-circle me-2"></i>
                            <?php echo htmlspecialchars($success); ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= BASE_URL ?>/public/profile/update" method="POST" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom complet</label>
                            <input type="text" 
                                   class="form-control form-control-lg" 
                                   id="nom" 
                                   name="nom" 
                                   value="<?= htmlspecialchars($user['nom']) ?>" 
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" 
                                   class="form-control form-control-lg" 
                                   id="email" 
                                   name="email" 
                                   value="<?= htmlspecialchars($user['email']) ?>" 
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Mot de passe actuel</label>
                            <input type="password" 
                                   class="form-control form-control-lg" 
                                   id="current_password" 
                                   name="current_password" 
                                   required>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Nouveau mot de passe (optionnel)</label>
                            <input type="password" 
                                   class="form-control form-control-lg" 
                                   id="password" 
                                   name="password">
                            <div class="form-text">
                                Laissez vide pour garder votre mot de passe actuel
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check2-circle me-2"></i>
                                Enregistrer les modifications
                            </button>
                            <a href="<?= BASE_URL ?>/public/dashboard/electeur" class="btn btn-light btn-lg">
                                <i class="bi bi-arrow-left me-2"></i>
                                Retour au tableau de bord
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Form validation
(function() {
    'use strict';
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>