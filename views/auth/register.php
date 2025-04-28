<?php 
require_once __DIR__ . '/../../bootstrap.php';
$pageTitle = "Inscription - Système de Gestion des Élections";
require_once __DIR__ . '/../../views/layout/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                    <h3>Inscription</h3>
                </div>
                <div class="card-body">
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>
                    
                    <form action="<?= BASE_URL ?>/public/auth/register" method="POST">
                        <div class="mb-3">
                            <label for="identifiant" class="form-label">Identifiant:</label>
                            <input type="text" class="form-control" id="identifiant" name="identifiant" required>
                        </div>
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom complet</label>
                            <input type="text" class="form-control" id="nom" name="nom" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="mb-3">
                            <label for="mot_de_passe" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
                        </div>

                        <div class="mb-3">
                            <label for="date_naissance" class="form-label">Date de naissance</label>
                            <input type="date" class="form-control" id="date_naissance" name="date_naissance" required>
                        </div>

                        <div class="mb-3">
                            <label for="sexe" class="form-label">Sexe</label>
                            <select class="form-select" id="sexe" name="sexe" required>
                                <option value="">Sélectionnez votre sexe</option>
                                <option value="Homme">Homme</option>
                                <option value="Femme">Femme</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Rôle</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="electeur">Électeur</option>
                                <option value="observateur">Observateur</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">S'inscrire</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <p class="mb-0">Déjà inscrit ? 
                        <a href="<?= BASE_URL ?>/public/auth/login">Se connecter</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../views/layout/footer.php'; ?>