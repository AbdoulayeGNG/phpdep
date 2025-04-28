<?php 
require_once __DIR__ . '/../../bootstrap.php';
$pageTitle = "Créer une nouvelle élection";
require_once __DIR__ . '/../../views/layout/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php require_once __DIR__ . '/../layout/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Créer une nouvelle élection</h1>
            </div>

            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <form action="<?= BASE_URL ?>/public/elections/store" method="POST" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="titre" class="form-label">Titre de l'élection</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="titre" 
                                   name="titre" 
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" 
                                      id="description" 
                                      name="description" 
                                      rows="3" 
                                      required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Type d'élection</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="">Choisir...</option>
                                <option value="presidentielle">Présidentielle</option>
                                <option value="legislative">Législative</option>
                                <option value="municipale">Municipale</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date_debut" class="form-label">Date de début</label>
                                <input type="datetime-local" 
                                       class="form-control" 
                                       id="date_debut" 
                                       name="date_debut" 
                                       required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="date_fin" class="form-label">Date de fin</label>
                                <input type="datetime-local" 
                                       class="form-control" 
                                       id="date_fin" 
                                       name="date_fin" 
                                       required>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                             <a href="<?= BASE_URL ?>/public/elections" class="btn btn-secondary me-md-2">Annuler</a>
                             <button type="submit" class="btn btn-primary">Créer l'élection</button>
                       </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
// Validation des formulaires Bootstrap
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()

// Validation des dates
document.getElementById('date_fin').addEventListener('change', function() {
    var dateDebut = new Date(document.getElementById('date_debut').value);
    var dateFin = new Date(this.value);
    
    if (dateFin <= dateDebut) {
        this.setCustomValidity('La date de fin doit être postérieure à la date de début');
    } else {
        this.setCustomValidity('');
    }
});
</script>

<?php require_once __DIR__ . '/../../views/layout/footer.php'; ?>