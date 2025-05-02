<footer class="footer mt-auto py-4 bg-primary text-white">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-md-4 text-center text-md-start">
                <h5 class="mb-3">phpProject</h5>
                <p class="small mb-0">
                    Plateforme de vote électronique sécurisée pour une démocratie transparente
                </p>
            </div>
            <div class="col-md-4 text-center">
                <div class="mb-2">
                    <i class="bi bi-shield-check fs-1"></i>
                </div>
                <p class="small mb-0">
                    Vos votes sont sécurisés et anonymes
                </p>
            </div>
            <div class="col-md-4 text-center text-md-end">
                <h6 class="mb-3">Liens rapides</h6>
                <ul class="list-unstyled small">
                    <li><a href="<?= BASE_URL ?>/public/elections/en-cours" class="text-white text-decoration-none">Élections en cours</a></li>
                    <li><a href="<?= BASE_URL ?>/public/mes-votes" class="text-white text-decoration-none">Mes votes</a></li>
                    <li><a href="<?= BASE_URL ?>/public/aide" class="text-white text-decoration-none">Aide</a></li>
                </ul>
            </div>
        </div>
        <hr class="my-4 border-light opacity-25">
        <div class="row">
            <div class="col-12 text-center">
                <p class="small mb-0">
                    &copy; <?= date('Y') ?> <?= APP_NAME ?> - Tous droits réservés
                </p>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom Scripts -->
<script src="<?= BASE_URL ?>/public/assets/js/register.js"></script>

<!-- Add countdown script if on election pages -->
<?php if (strpos($_SERVER['REQUEST_URI'], 'election') !== false): ?>
<script src="<?= BASE_URL ?>/public/assets/js/countdown.js"></script>
<?php endif; ?>

</body>
</html>