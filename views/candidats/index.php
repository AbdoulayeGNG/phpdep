<?php 
require_once __DIR__ . '/../../bootstrap.php';
$pageTitle = "Gestion des candidats";
require_once __DIR__ . '/../../views/layout/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php require_once __DIR__ . '/../layout/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Gestion des candidats</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="<?php echo BASE_URL; ?>/public/candidats/create" class="btn btn-primary">
                        <i class="bi bi-person-plus"></i> Nouveau candidat
                    </a>
                </div>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?php 
                    echo htmlspecialchars($_SESSION['success']); 
                    unset($_SESSION['success']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Photo</th>
                                    <th>Candidat</th>
                                    <th>Élection</th>
                                    <th>Parti politique</th>
                                    <th>Programme</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($candidats) && !empty($candidats)): ?>
                                    <?php foreach ($candidats as $candidat): ?>
                                        <tr>
                                            <td>
                                                <?php if ($candidat['photo']): ?>
                                                    <img src="<?php echo BASE_URL . '/public/' . htmlspecialchars($candidat['photo']); ?>" 
                                                         alt="Photo de <?php echo htmlspecialchars($candidat['nom_utilisateur']); ?>"
                                                         class="rounded-circle"
                                                         width="50"
                                                         height="50">
                                                <?php else: ?>
                                                    <img src="<?php echo BASE_URL; ?>/public/assets/images/default-avatar.png" 
                                                         alt="Photo par défaut"
                                                         class="rounded-circle"
                                                         width="50"
                                                         height="50">
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($candidat['nom_utilisateur']); ?></td>
                                            <td><?php echo htmlspecialchars($candidat['titre_election']); ?></td>
                                            <td><?php echo htmlspecialchars($candidat['parti_politique']); ?></td>
                                            <td>
                                                <?php 
                                                $programme = htmlspecialchars($candidat['programme']);
                                                echo strlen($programme) > 50 ? substr($programme, 0, 47) . '...' : $programme;
                                                ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo $candidat['valide'] ? 'success' : 'warning'; ?>">
                                                    <?php echo $candidat['valide'] ? 'Validé' : 'En attente'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="<?php echo rtrim(BASE_URL, '/'); ?>/public/candidats/edit/<?php echo $candidat['id']; ?>" 
                                                       class="btn btn-sm btn-primary"
                                                       title="Modifier">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <?php if (!$candidat['valide']): ?>
                                                        <a href="<?php echo BASE_URL; ?>/public/candidats/validate/<?php echo $candidat['id']; ?>" 
                                                           class="btn btn-sm btn-success"
                                                           title="Valider"
                                                           onclick="return confirm('Êtes-vous sûr de vouloir valider ce candidat ?');">
                                                            <i class="bi bi-check-lg"></i>
                                                            <span class="d-none d-md-inline"></span>
                                                        </a>
                                                    <?php endif; ?>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-danger"
                                                            title="Supprimer"
                                                            onclick="deleteCandidat(<?php echo $candidat['id']; ?>, '<?php echo addslashes($candidat['nom_utilisateur']); ?>')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">Aucun candidat trouvé</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
function deleteCandidat(id, nom) {
    if (confirm(`Êtes-vous sûr de vouloir supprimer le candidat ${nom} ?`)) {
        window.location.href = `${BASE_URL}/public/candidats/delete/${id}`;
    }
}
</script>

<?php require_once __DIR__ . '/../../views/layout/footer.php'; ?>