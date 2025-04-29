<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle : 'Système de Gestion des Élections' ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="<?= BASE_URL ?>/public/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/public/assets/css/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="<?= BASE_URL ?>/public/assets/css/style.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/public/assets/css/dashboard.css" rel="stylesheet">
    
    <!-- Bootstrap JS and dependencies -->
    <script src="<?= BASE_URL ?>/public/assets/js/jquery-3.6.0.min.js"></script>
    <script src="<?= BASE_URL ?>/public/assets/js/popper.min.js"></script>
    <script src="<?= BASE_URL ?>/public/assets/js/bootstrap.min.js"></script>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/icons/bootstrap-icons.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="<?= BASE_URL ?>"><?= APP_NAME ?></a>
    </div>
</nav>
</body>
</html>