<?php 
require_once __DIR__ . '/../../bootstrap.php';
$pageTitle = "Mon Profil";
require_once __DIR__ . '/../layout/header.php';
?>

<div class="container-fluid py-5 bg-custom" style="margin-top: 70px;">
    <div class="content-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card border-0 rounded-4 shadow-sm">
                        <div class="card-body p-4">
                            <h2 class="card-title h4 fw-bold mb-4">
                                <i class="bi bi-person-circle text-primary me-2"></i>
                                Mon Profil
                            </h2>

                            <?php if(isset($_SESSION['error'])): ?>
                                <div class="alert alert-danger">
                                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                                </div>
                            <?php endif; ?>

                            <?php if(isset($_SESSION['success'])): ?>
                                <div class="alert alert-success">
                                    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                                </div>
                            <?php endif; ?>

                            <form action="<?= BASE_URL ?>/public/profile/update" method="POST">
                                <div class="mb-3">
                                    <label for="nom" class="form-label">Nom complet</label>
                                    <input type="text" class="form-control" id="nom" name="nom" 
                                           value="<?= isset($user['nom']) ? htmlspecialchars($user['nom']) : '' ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?= isset($user['email']) ? htmlspecialchars($user['email']) : '' ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="Nid" class="form-label">Numéro d'identification</label>
                                    <input type="text" class="form-control" id="Nid" 
                                           value="<?= isset($user['Nid']) ? htmlspecialchars($user['Nid']) : '' ?>" readonly>
                                </div>

                                <hr class="my-4">

                                <h5 class="fw-bold mb-3">Changer le mot de passe</h5>
                                
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Mot de passe actuel</label>
                                    <input type="password" class="form-control" id="current_password" name="current_password">
                                </div>

                                <div class="mb-3">
                                    <label for="new_password" class="form-label">Nouveau mot de passe</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password">
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="<?= BASE_URL ?>/public/dashboard/electeur" 
                                       class="btn btn-outline-secondary me-2">
                                        Annuler
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check2-circle me-2"></i>
                                        Enregistrer les modifications
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>