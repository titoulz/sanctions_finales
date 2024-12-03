<?php
//cette page sera la vue de la page de connexion
require_once __DIR__ . '/../../src/Controller/SanctionsController.php';
$email = $email ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="/assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Connexion</h1>
    <?php if (!empty($erreurs)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($erreurs) ?>
        </div>
    <?php endif; ?>
    <form action="" method="POST" class="mt-4">
        <div class="mb-3">
            <label for="email" class="form-label">Votre adresse Email</label>
            <input type="email" class="form-control" name="email" id="email" placeholder="email" values="<?php echo $email ?>">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Votre Mot de passe</label>
            <input type="password" class="form-control" name="password" id="password" placeholder="Mot de passe" >
        </div>
        <button type="submit" class="btn btn-primary">Soumettre</button>
    </form>
    <a href="/inscription" class="text-white btn btn-link mt-3">Pas encore inscrit ? Inscrivez-vous</a>
</div>
</body>
</html>
