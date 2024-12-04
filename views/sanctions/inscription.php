<?php 
require_once __DIR__ . '/../../src/Controller/SanctionsController.php';
//declarer les variables des values
$prenom = $prenom ?? '';
$nom = $nom ?? '';
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
    <h1>Register</h1>
    <?php if (!empty($erreurs)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($erreurs) ?>
        </div>
    <?php endif; ?>
</div>

    <form action="" method="POST" class="mt-4">
        <div class="mb-3">
            <label for="prenom" class="form-label">Votre Prénom</label>
            <input type="text" class="form-control" name="prenom" id="prenom" placeholder="Votre prénom" value="<?= htmlspecialchars($prenom ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="nom" class="form-label">Votre Nom</label>
            <input type="text" class="form-control" name="nom" id="nom" placeholder="Votre nom" value="<?= htmlspecialchars($nom ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Votre adresse Email</label>
            <input type="email" class="form-control" name="email" id="email" placeholder="email@domaine.com" value="<?= htmlspecialchars($email ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Votre Mot de passe</label>
            <input type="password" class="form-control" name="password" id="password" placeholder="Mot de passe">
        </div>
        <div class="mb-3">
            <label for="confirmPassword" class="form-label">Confirmation mot de passe</label>
            <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" placeholder="Confirmer votre mot de passe">
        </div>
        <button type="submit" class="btn btn-primary">Soumettre</button>
    </form>
    <a href="/connexion" class="text-white btn btn-link mt-3">Déjà inscrit ? Connectez-vous</a>
</div>
</body>
</html>
    <a href="/connexion" class="text-white btn btn-link mt-3">Déjà inscrit ? Connectez-vous</a>
</div>
</body>
</html>
