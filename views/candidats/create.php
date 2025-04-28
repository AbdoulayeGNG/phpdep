<?php 
require_once __DIR__ . '/../../bootstrap.php';
$pageTitle = "Ajouter un candidat";
require_once __DIR__ . '/../../views/layout/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php require_once __DIR__ . '/../layout/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Ajouter un candidat</h1>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <form action="<?php echo BASE_URL; ?>/public/candidats/store" 
                          method="POST" 
                          enctype="multipart/form-data" 
                          class="needs-validation" 
                          novalidate>
                        
                        <div class="mb-3">
                            <label for="utilisateur_id" class="form-label">Utilisateur</label>
                            <select class="form-select" id="utilisateur_id" name="utilisateur_id" required>
                                <option value="">Sélectionner un utilisateur...</option>
                                <?php foreach ($utilisateurs as $utilisateur): ?>
                                    <option value="<?php echo $utilisateur['id']; ?>"
                                            <?php echo (isset($old['utilisateur_id']) && $old['utilisateur_id'] == $utilisateur['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($utilisateur['nom']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Veuillez sélectionner un utilisateur</div>
                        </div>

                        <div class="mb-3">
                            <label for="election_id" class="form-label">Élection</label>
                            <select class="form-select" id="election_id" name="election_id" required>
                                <option value="">Sélectionner une élection...</option>
                                <?php foreach ($elections as $election): ?>
                                    <option value="<?php echo $election['id']; ?>"
                                            <?php echo (isset($old['election_id']) && $old['election_id'] == $election['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($election['titre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Veuillez sélectionner une élection</div>
                        </div>

                        <div class="mb-3">
                            <label for="parti_politique" class="form-label">Parti politique</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="parti_politique" 
                                   name="parti_politique"
                                   value="<?php echo isset($old['parti_politique']) ? htmlspecialchars($old['parti_politique']) : ''; ?>"
                                   required>
                            <div class="invalid-feedback">Veuillez entrer le nom du parti politique</div>
                        </div>

                        <div class="mb-3">
                            <label for="programme" class="form-label">Programme</label>
                            <textarea class="form-control" 
                                      id="programme" 
                                      name="programme" 
                                      rows="5" 
                                      required><?php echo isset($old['programme']) ? htmlspecialchars($old['programme']) : ''; ?></textarea>
                            <div class="invalid-feedback">Veuillez entrer le programme du candidat</div>
                        </div>

                        <div class="mb-3">
                            <label for="photo" class="form-label">Photo</label>
                            <input type="file" 
                                   class="form-control" 
                                   id="photo" 
                                   name="photo"
                                   accept="image/*">
                            <div class="form-text">Format accepté : JPG, PNG. Taille maximale : 2MB</div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?php echo BASE_URL; ?>/public/candidats" class="btn btn-secondary me-md-2">Annuler</a>
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
// Form validation
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
})()
</script>

<?php require_once __DIR__ . '/../../views/layout/footer.php'; ?>