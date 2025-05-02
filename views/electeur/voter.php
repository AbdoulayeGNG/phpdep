<?php 
require_once __DIR__ . '/../../bootstrap.php';
$pageTitle = "Voter - " . htmlspecialchars($election['titre']);
require_once __DIR__ . '/../layout/header.php';
?>

<div class="container py-5">
    <?php if (isset($error)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php else: ?>
        <h1 class="mb-4"><?php echo htmlspecialchars($election['titre']); ?></h1>
        <p class="lead mb-4"><?php echo htmlspecialchars($election['description']); ?></p>

        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($candidats as $candidat): ?>
                <div class="col">
                    <div class="card h-100">
                        <?php if (!empty($candidat['photo'])): ?>
                            <img src="<?= BASE_URL ?>/public/<?= htmlspecialchars($candidat['photo']) ?>" 
                                 class="card-img-top" alt="Photo du candidat">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($candidat['nom']); ?></h5>
                            <p class="card-text">
                                <small class="text-muted">NID: <?php echo htmlspecialchars($candidat['Nid']); ?></small>
                            </p>
                            <p class="card-text"><?php echo htmlspecialchars($candidat['programme']); ?></p>
                            
                            <form action="<?= BASE_URL ?>/public/elections/voter" method="POST" class="mt-3">
                                <input type="hidden" name="election_id" value="<?php echo $election['id']; ?>">
                                <input type="hidden" name="candidat_id" value="<?php echo $candidat['id']; ?>">
                                <button type="submit" class="btn btn-primary btn-block">
                                    Voter pour ce candidat
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>