<?php 
require_once __DIR__ . '/../../bootstrap.php';
$pageTitle = "Ajouter un utilisateur";
require_once __DIR__ . '/../../views/layout/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php require_once __DIR__ . '/../layout/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Ajouter un utilisateur</h1>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <form action="<?php echo BASE_URL; ?>/public/users/store" method="POST" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="Nid" class="form-label">Identifiant</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="Nid" 
                                   name="Nid" 
                                   value="<?php echo isset($old['Nid']) ? htmlspecialchars($old['Nid']) : ''; ?>"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="nom" 
                                   name="nom" 
                                   value="<?php echo isset($old['nom']) ? htmlspecialchars($old['nom']) : ''; ?>"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   value="<?php echo isset($old['email']) ? htmlspecialchars($old['email']) : ''; ?>"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="mot_de_passe" class="form-label">Mot de passe</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="mot_de_passe" 
                                   name="mot_de_passe" 
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="date_naissance" class="form-label">Date de naissance</label>
                            <input type="date" 
                                   class="form-control" 
                                   id="date_naissance" 
                                   name="date_naissance" 
                                   value="<?php echo isset($old['date_naissance']) ? htmlspecialchars($old['date_naissance']) : ''; ?>"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="sexe" class="form-label">Sexe</label>
                            <select class="form-select" id="sexe" name="sexe" required>
                                <option value="">Choisir...</option>
                                <option value="Homme" <?php echo (isset($old['sexe']) && $old['sexe'] === 'Homme') ? 'selected' : ''; ?>>Homme</option>
                                <option value="Femme" <?php echo (isset($old['sexe']) && $old['sexe'] === 'Femme') ? 'selected' : ''; ?>>Femme</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Rôle</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="">Choisir...</option>
                                <option value="electeur" <?php echo (isset($old['role']) && $old['role'] === 'electeur') ? 'selected' : ''; ?>>Électeur</option>
                                <option value="admin" <?php echo (isset($old['role']) && $old['role'] === 'admin') ? 'selected' : ''; ?>>Administrateur</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?php echo BASE_URL; ?>/public/users" class="btn btn-secondary me-md-2">Annuler</a>
                            <button type="submit" class="btn btn-primary">Créer l'utilisateur</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require_once __DIR__ . '/../../views/layout/footer.php'; ?>