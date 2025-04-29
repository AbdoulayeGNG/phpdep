<?php 
require_once __DIR__ . '/../../views/layout/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php require_once __DIR__ . '/../layout/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Paramètres du système</h1>
            </div>

            <!-- Alerts -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Paramètres Form -->
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#general">Général</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#apparence">Apparence</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#election">Élection</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#maintenance">Maintenance</a>
                        </li>
                    </ul>

                    <form action="<?php echo BASE_URL; ?>/public/parametres/update" method="POST" class="needs-validation" enctype="multipart/form-data" novalidate>
                        <div class="tab-content">
                            <!-- Général -->
                            <div class="tab-pane fade show active" id="general">
                                <div class="mb-3">
                                    <label for="nom_site" class="form-label">Nom du site</label>
                                    <input type="text" class="form-control" id="nom_site" name="nom_site" 
                                           value="<?php echo htmlspecialchars(isset($parametres['nom_site']) ? $parametres['nom_site'] : ''); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"
                                            required><?php echo htmlspecialchars(isset($parametres['description']) ? $parametres['description'] : ''); ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="email_contact" class="form-label">Email de contact</label>
                                    <input type="email" class="form-control" id="email_contact" name="email_contact"
                                           value="<?php echo htmlspecialchars(isset($parametres['email_contact']) ? $parametres['email_contact'] : ''); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="notifications_email" name="notifications_email"
                                               <?php echo (isset($parametres['notifications_email']) && $parametres['notifications_email']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="notifications_email">Activer les notifications par email</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Apparence -->
                            <div class="tab-pane fade" id="apparence">
                                <div class="mb-3">
                                    <label for="logo" class="form-label">Logo du site</label>
                                    <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                                    <?php if (isset($parametres['logo_path']) && !empty($parametres['logo_path'])): ?>
                                        <img src="<?php echo BASE_URL . '/public/uploads/' . $parametres['logo_path']; ?>" 
                                             alt="Logo actuel" class="mt-2" style="max-height: 100px;">
                                    <?php endif; ?>
                                </div>

                                <div class="mb-3">
                                    <label for="theme_couleur" class="form-label">Couleur du thème</label>
                                    <input type="color" class="form-control form-control-color" id="theme_couleur" name="theme_couleur"
                                           value="<?php echo htmlspecialchars(isset($parametres['theme_couleur']) ? $parametres['theme_couleur'] : '#0d6efd'); ?>">
                                </div>
                            </div>

                            <!-- Élection -->
                            <div class="tab-pane fade" id="election">
                                <div class="mb-3">
                                    <label for="duree_vote" class="form-label">Durée du vote (en minutes)</label>
                                    <input type="number" class="form-control" id="duree_vote" name="duree_vote"
                                           value="<?php echo htmlspecialchars(isset($parametres['duree_vote']) ? $parametres['duree_vote'] : '30'); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="delai_validation_candidat" class="form-label">Délai de validation des candidatures (en heures)</label>
                                    <input type="number" class="form-control" id="delai_validation_candidat" name="delai_validation_candidat"
                                           value="<?php echo htmlspecialchars(isset($parametres['delai_validation_candidat']) ? $parametres['delai_validation_candidat'] : '48'); ?>" required>
                                </div>
                            </div>

                            <!-- Maintenance -->
                            <div class="tab-pane fade" id="maintenance">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="maintenance_mode" name="maintenance_mode"
                                               <?php echo (isset($parametres['maintenance_mode']) && $parametres['maintenance_mode']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="maintenance_mode">Activer le mode maintenance</label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="maintenance_message" class="form-label">Message de maintenance</label>
                                    <textarea class="form-control" id="maintenance_message" name="maintenance_message" rows="3"
                                    ><?php echo htmlspecialchars(isset($parametres['maintenance_message']) ? $parametres['maintenance_message'] : 'Site en maintenance. Veuillez réessayer plus tard.'); ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require_once __DIR__ . '/../../views/layout/footer.php'; ?>