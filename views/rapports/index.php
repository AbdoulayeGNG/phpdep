<?php 
$pageTitle = "Rapports";
require_once __DIR__ . '/../../views/layout/header.php'; 
?>

<div class="container-fluid">
    <div class="row">
        <?php require_once __DIR__ . '/../../views/layout/sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Rapports et Statistiques</h1>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="row">
                <!-- Elections Statistics -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">Élections</h5>
                        </div>
                        <div class="card-body">
                            <p>Total: <?php echo $elections['total']; ?></p>
                            <p>En cours: <?php echo $elections['en_cours']; ?></p>
                            <p>Terminées: <?php echo $elections['terminees']; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Candidates Statistics -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="card-title mb-0">Candidats</h5>
                        </div>
                        <div class="card-body">
                            <p>Total: <?php echo $candidats['total']; ?></p>
                            <p>Validés: <?php echo $candidats['valides']; ?></p>
                            <p>En attente: <?php echo $candidats['total'] - $candidats['valides']; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require_once __DIR__ . '/../../views/layout/footer.php'; ?>