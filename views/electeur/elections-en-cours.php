<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container-fluid">
    <div class="row">

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Élections en cours</h1>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if (empty($elections)): ?>
                <div class="alert alert-info" role="alert">
                    Aucune élection n'est en cours actuellement.
                </div>
            <?php else: ?>
                <div class="row row-cols-1 row-cols-md-2 g-4">
                    <?php foreach ($elections as $election): ?>
                        <div class="col">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($election['titre']); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars($election['description']); ?></p>
                                    
                                    <div class="mb-3">
                                        <small class="text-muted">
                                            Fin dans: <span class="countdown" data-end="<?php echo $election['date_fin']; ?>"></span>
                                        </small>
                                    </div>

                                    <?php if (isset($election['a_vote']) && $election['a_vote']): ?>
                                        <div class="alert alert-success">
                                            Vous avez déjà voté pour cette élection
                                        </div>
                                    <?php else: ?>
                                        <button type="button" 
                                                class="btn btn-primary" 
                                                onclick="window.location.href='<?= BASE_URL ?>/public/elections/voter/<?= $election['id'] ?>'">
                                            Voter
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Vote Modal -->
                        <?php if (!isset($election['a_vote']) || !$election['a_vote']): ?>
                            <div class="modal fade" id="voteModal<?php echo $election['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Voter - <?php echo htmlspecialchars($election['titre']); ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="voteForm<?php echo $election['id']; ?>" 
                                                  action="<?php echo BASE_URL; ?>/public/vote/submit" 
                                                  method="POST">
                                                <input type="hidden" name="election_id" value="<?php echo $election['id']; ?>">
                                                
                                                <?php foreach ($election['candidats'] as $candidat): ?>
                                                    <div class="form-check mb-3">
                                                        <input class="form-check-input" type="radio" 
                                                               name="candidat_id" 
                                                               id="candidat<?php echo $candidat['id']; ?>" 
                                                               value="<?php echo $candidat['id']; ?>" required>
                                                        <label class="form-check-label" for="candidat<?php echo $candidat['id']; ?>">
                                                            <?php echo htmlspecialchars(isset($candidat['nom']) ? $candidat['nom'] : $candidat['nom_candidat']); ?>
                                                        </label>
                                                    </div>
                                                <?php endforeach; ?>

                                                <button type="submit" class="btn btn-primary">Confirmer le vote</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<script>
// Update countdown timers
function updateCountdowns() {
    document.querySelectorAll('.countdown').forEach(element => {
        const endTime = new Date(element.dataset.end).getTime();
        const now = new Date().getTime();
        const distance = endTime - now;

        if (distance < 0) {
            element.innerHTML = 'Terminé';
            return;
        }

        const hours = Math.floor(distance / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        element.innerHTML = `${hours}h ${minutes}m`;
    });
}

setInterval(updateCountdowns, 60000); // Update every minute
updateCountdowns(); // Initial update
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>