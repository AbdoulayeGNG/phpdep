<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page non trouvée</title>
    <link href="<?php echo BASE_URL; ?>/public/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>/public/assets/css/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div class="card shadow">
                    <div class="card-body">
                        <h1 class="display-1 text-danger">404</h1>
                        <h2 class="mb-4">Page non trouvée</h2>
                        <p class="mb-4">La page que vous recherchez n'existe pas ou a été déplacée.</p>
                        <a href="<?php echo BASE_URL; ?>/public" class="btn btn-primary">
                            <i class="bi bi-house-door"></i> Retour à l'accueil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>