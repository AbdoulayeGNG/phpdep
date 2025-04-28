<?php 
require_once __DIR__ . '/../../bootstrap.php';
$pageTitle = "Gestion des utilisateurs";
require_once __DIR__ . '/../../views/layout/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php require_once __DIR__ . '/../layout/sidebar.php'; ?>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Gestion des utilisateurs</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="<?php echo BASE_URL; ?>/public/users/create" class="btn btn-primary">
                        <i class="bi bi-person-plus"></i> Nouvel utilisateur
                    </a>
                </div>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($_SESSION['success']); ?>
                    <?php unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Identifiant</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Statut</th>
                            <th>Date de naissance</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        // Debug - Afficher le nombre d'utilisateurs
                        error_log("View - Number of users: " . (isset($users) ? count($users) : 'none'));
                        ?>
                        
                        <?php if (isset($users) && !empty($users)): ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars(isset($user['Nid']) ? $user['Nid'] : 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars(isset($user['nom']) ? $user['nom'] : ''); ?></td>
                                    <td><?php echo htmlspecialchars(isset($user['email']) ? $user['email'] : ''); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo (isset($user['role']) && $user['role'] === 'admin') ? 'danger' : 'primary'; ?>">
                                            <?php echo htmlspecialchars(isset($user['role']) ? $user['role'] : 'electeur'); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo (isset($user['statut']) && $user['statut']) ? 'success' : 'danger'; ?>">
                                            <?php echo (isset($user['statut']) && $user['statut']) ? 'Actif' : 'Inactif'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php 
                                        echo isset($user['date_naissance']) 
                                            ? date('d/m/Y', strtotime($user['date_naissance'])) 
                                            : 'N/A'; 
                                        ?>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?php echo BASE_URL; ?>/views/users/edit/<?php echo $user['id']; ?>" 
                                               class="btn btn-sm btn-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <?php if (!isset($user['role']) || $user['role'] !== 'admin'): ?>
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger delete-user" 
                                                        data-id="<?php echo $user['id']; ?>"
                                                        data-nom="<?php echo htmlspecialchars(isset($user['nom']) ? $user['nom'] : ''); ?>">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Aucun utilisateur trouvé</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<?php require_once __DIR__ . '/../../views/layout/footer.php'; ?>