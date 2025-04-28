<?php 
require_once __DIR__ . '/../../bootstrap.php';
$pageTitle = "Tableau de Bord Électeur";
require_once __DIR__ . '/../layout/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php require_once __DIR__ . '/../layout/sidebar1.php'; ?>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Tableau de bord Électeur</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <a href="<?= BASE_URL ?>/profile" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-person"></i> Mon Profil
                        </a>
                    </div>
                </div>
            </div>

            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <!-- Élections en cours -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Élections en cours</h4>
                </div>
                <div class="card-body">
                    <?php if(!empty($elections_en_cours)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Description</th>
                                        <th>Se termine le</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($elections_en_cours as $election): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($election['titre']) ?></td>
                                            <td><?= htmlspecialchars($election['description']) ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($election['date_fin'])) ?></td>
                                            <td>
                                                <a href="<?= BASE_URL ?>/vote/election/<?= $election['id'] ?>" 
                                                   class="btn btn-primary btn-sm">
                                                    <i class="bi bi-check2-square"></i> Voter
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Aucune élection en cours.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Élections à venir -->
            <div class="card">
                <div class="card-header">
                    <h4>Prochaines élections</h4>
                </div>
                <div class="card-body">
                    <?php if(!empty($elections_a_venir)): ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Description</th>
                                        <th>Débute le</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($elections_a_venir as $election): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($election['titre']) ?></td>
                                            <td><?= htmlspecialchars($election['description']) ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($election['date_debut'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">Aucune élection à venir.</p>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>