<?php
require_once __DIR__ . '/../../bootstrap.php';
$pageTitle = "Modifier le candidat";
require_once __DIR__ . '/../../views/layout/header.php';

// Debug information
error_log('Edit view - Candidat data: ' . print_r(isset($candidat) ? $candidat : 'Not set', true));
error_log('Edit view - Elections data: ' . print_r(isset($elections) ? $elections : 'Not set', true));

// Initialize empty candidat if not set
if (!isset($candidat)) {
    $candidat = array(
        'id' => '',
        'election_id' => '',
        'parti_politique' => '',
        'programme' => '',
        'photo' => '',
        'valide' => false
    );
}

// Debug log
error_log("Candidat data in view: " . print_r($candidat, true));
?>

<div class="container-fluid">
    <div class="row">
        <?php require_once __DIR__ . '/../layout/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Modifier le candidat</h1>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <form action="<?php echo BASE_URL; ?>/public/candidats/update/<?php echo htmlspecialchars($candidat['id']); ?>" 
                          method="POST" 
                          enctype="multipart/form-data" 
                          class="needs-validation" 
                          novalidate>

                        <div class="mb-3">
                            <label for="election_id" class="form-label">Élection</label>
                            <select class="form-select" id="election_id" name="election_id" required>
                                <option value="">Sélectionner une élection...</option>
                                <?php if (isset($elections) && !empty($elections)): ?>
                                    <?php foreach ($elections as $election): ?>
                                        <option value="<?php echo $election['id']; ?>"
                                                <?php echo ($candidat['election_id'] == $election['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($election['titre']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <div class="invalid-feedback">Veuillez sélectionner une élection</div>
                        </div>

                        <div class="mb-3">
                            <label for="parti_politique" class="form-label">Parti politique</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="parti_politique" 
                                   name="parti_politique"
                                   value="<?php echo htmlspecialchars($candidat['parti_politique']); ?>"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="programme" class="form-label">Programme</label>
                            <textarea class="form-control" 
                                      id="programme" 
                                      name="programme" 
                                      rows="5" 
                                      required><?php echo htmlspecialchars($candidat['programme']); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="photo" class="form-label">Photo</label>
                            <?php if (!empty($candidat['photo'])): ?>
                                <div class="mb-2">
                                    <img src="<?php echo BASE_URL . '/public/' . htmlspecialchars($candidat['photo']); ?>" 
                                         alt="Photo actuelle" 
                                         class="img-thumbnail" 
                                         style="max-width: 200px;">
                                </div>
                            <?php endif; ?>
                            <input type="file" 
                                   class="form-control" 
                                   id="photo" 
                                   name="photo"
                                   accept="image/*">
                            <div class="form-text">Format accepté : JPG, PNG. Taille maximale : 2MB</div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="valide" 
                                       name="valide" 
                                       value="1"
                                       <?php echo $candidat['valide'] ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="valide">
                                    Valider la candidature
                                </label>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?php echo BASE_URL; ?>/public/candidats" class="btn btn-secondary me-md-2">Annuler</a>
                            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require_once __DIR__ . '/../../views/layout/footer.php'; ?>