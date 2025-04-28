<?php 
require_once __DIR__ . '/../../bootstrap.php';
$pageTitle = "Connexion - Système de Gestion des Élections";
require_once __DIR__ . '/../../views/layout/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <h3>Connexion</h3>
                </div>
                <div class="card-body">
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    
                    <form action="<?= BASE_URL ?>/public/auth/login" method="POST">
                        <div class="mb-3">
                            <label for="identifiant" class="form-label">Identifiant</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="identifiant"
                                   name="identifiant" 
                                   required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password" 
                                   name="password" 
                                   required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Se souvenir de moi</label>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Se connecter</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <p class="mb-0">Pas encore de compte ? 
                        <a href="<?= BASE_URL ?>/public/auth/register">S'inscrire</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../views/layout/footer.php'; ?>