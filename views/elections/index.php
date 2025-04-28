<?php 
require_once __DIR__ . '/../../bootstrap.php';
$pageTitle = "Liste des élections";
require_once __DIR__ . '/../../views/layout/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php require_once __DIR__ . '/../layout/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Liste des élections</h1>
                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="<?= BASE_URL ?>/public/elections/create" class="btn btn-primary">
                            <i class="bi bi-plus-lg"></i> Nouvelle élection
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($_SESSION['success']) ?>
                    <?php unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Type</th>
                            <th>Date de début</th>
                            <th>Date de fin</th>
                            <th>Statut</th>
                            <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                <th>Actions</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($elections)): ?>
                            <?php foreach ($elections as $election): ?>
                                <tr>
                                    <td><?= htmlspecialchars($election['titre']) ?></td>
                                    <td><?= htmlspecialchars($election['type']) ?></td>
                                    <td><?= (new DateTime($election['date_debut']))->format('d/m/Y H:i') ?></td>
                                    <td><?= (new DateTime($election['date_fin']))->format('d/m/Y H:i') ?></td>
                                    <td>
                                        <span class="badge bg-<?= $election['statut'] === 'en_cours' ? 'success' : 
                                            ($election['statut'] === 'en_attente' ? 'warning' : 'secondary') ?>">
                                            <?= htmlspecialchars($election['statut']) ?>
                                        </span>
                                    </td>
                                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                        <td>
                                            <a href="<?= BASE_URL ?>/public/elections/edit/<?= $election['id'] ?>" 
                                               class="btn btn-sm btn-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button class="btn btn-sm btn-danger delete-election" 
                                                    data-id="<?= $election['id'] ?>"
                                                    data-titre="<?= htmlspecialchars($election['titre']) ?>">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Aucune élection trouvée</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<?php require_once __DIR__ . '/../../views/layout/footer.php'; ?>