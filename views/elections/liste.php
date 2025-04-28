<?php 
require_once __DIR__ . '/../../bootstrap.php';
$pageTitle = "Gestion des Élections";
require_once __DIR__ . '/../../views/layout/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php require_once __DIR__ . '/../layout/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Gestion des Élections</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="<?= BASE_URL ?>/views/elections/create" class="btn btn-primary">
                        <i class="bi bi-plus"></i> Nouvelle Élection
                    </a>
                </div>
            </div>

            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Description</th>
                            <th>Date de début</th>
                            <th>Date de fin</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($elections) && !empty($elections)): ?>
                            <?php foreach($elections as $election): ?>
                                <tr>
                                    <td><?= htmlspecialchars($election['titre']) ?></td>
                                    <td><?= htmlspecialchars($election['description']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($election['date_debut'])) ?></td>
                                    <td><?= date('d/m/Y', strtotime($election['date_fin'])) ?></td>
                                    <td>
                                        <span class="badge bg-<?= getStatusBadgeClass($election['statut']) ?>">
                                            <?= getStatusLabel($election['statut']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= BASE_URL ?>/public/elections/edit/<?= $election['id'] ?>" 
                                           class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>/elections/delete/<?= $election['id'] ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette élection ?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
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

<?php 
function getStatusBadgeClass($status) {
    switch($status) {
        case 'en_cours': return 'success';
        case 'en_attente': return 'warning';
        case 'terminee': return 'secondary';
        default: return 'primary';
    }
}

function getStatusLabel($status) {
    switch($status) {
        case 'en_cours': return 'En cours';
        case 'en_attente': return 'En attente';
        case 'terminee': return 'Terminée';
        default: return 'Inconnu';
    }
}
?>

<?php require_once __DIR__ . '/../../views/layout/footer.php'; ?>