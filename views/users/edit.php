<?php 
require_once __DIR__ . '/../../bootstrap.php';
$pageTitle = "Modifier l'utilisateur";
require_once __DIR__ . '/../../views/layout/header.php';

// Initialiser $user si non défini
if (!isset($user)) {
    $user = array(
        'id' => '',
        'Nid' => '',
        'nom' => '',
        'email' => '',
        'date_naissance' => '',
        'sexe' => '',
        'role' => 'electeur',
        'statut' => 1
    );
}

// Debug - Afficher les données utilisateur
error_log("User data in edit view: " . print_r($user, true));
?>

<div class="container-fluid">
    <div class="row">
        <?php require_once __DIR__ . '/../layout/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Modifier l'utilisateur</h1>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <!-- Corriger l'action du formulaire pour inclure l'ID -->
                    <form action="<?php echo BASE_URL . '/public/users/update/'. htmlspecialchars($user['id']); ?>" 
                          method="POST" 
                          class="needs-validation" 
                          novalidate>
                        <div class="mb-3">
                            <label for="Nid" class="form-label">Identifiant</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="Nid" 
                                   name="Nid" 
                                   value="<?php echo htmlspecialchars($user['Nid']); ?>"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom complet</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="nom" 
                                   name="nom" 
                                   value="<?php echo htmlspecialchars($user['nom']); ?>"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse email</label>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   value="<?php echo htmlspecialchars($user['email']); ?>"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="date_naissance" class="form-label">Date de naissance</label>
                            <input type="date" 
                                   class="form-control" 
                                   id="date_naissance" 
                                   name="date_naissance" 
                                   value="<?php echo htmlspecialchars($user['date_naissance']); ?>"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="sexe" class="form-label">Sexe</label>
                            <select class="form-select" id="sexe" name="sexe" required>
                                <option value="">Sélectionner...</option>
                                <option value="M" <?php echo $user['sexe'] === 'M' ? 'selected' : ''; ?>>Homme</option>
                                <option value="F" <?php echo $user['sexe'] === 'F' ? 'selected' : ''; ?>>Femme</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Rôle</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="">Sélectionner...</option>
                                <option value="electeur" <?php echo $user['role'] === 'electeur' ? 'selected' : ''; ?>>Électeur</option>
                                <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Administrateur</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="statut" 
                                       name="statut" 
                                       value="1" 
                                       <?php echo $user['statut'] ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="statut">Compte actif</label>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?php echo BASE_URL; ?>/public/users" class="btn btn-secondary me-md-2">Annuler</a>
                            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require_once __DIR__ . '/../../views/layout/footer.php'; ?>