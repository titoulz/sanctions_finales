<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="/assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Créer une sanction</title>
</head>
<body>
<div class="container mt-5">
    <h1>Créer une sanction</h1>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST" class="mt-4" id="sanctionForm">
        <div class="mb-3">
            <label for="promotion_id" class="form-label">Sélectionner une promotion</label>
            <select name="promotion_id" id="promotion_id" class="form-select">
                <option value="">-- Choisir une promotion --</option>
                <?php foreach ($promotions as $promotion): ?>
                    <option value="<?= $promotion->getId() ?>" <?= ($selectedPromotionId == $promotion->getId()) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($promotion->getLibelle()) ?> - <?= htmlspecialchars($promotion->getAnnee()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <?php if (!empty($etudiants)): ?>
            <div class="mb-3">
                <label for="eleve" class="form-label">Élève</label>
                <select name="eleve" id="eleve" class="form-select">
                    <option value="">-- Choisir un élève --</option>
                    <?php foreach ($etudiants as $etudiant): ?>
                        <option value="<?= $etudiant->getId() ?>">
                            <?= htmlspecialchars($etudiant->getNom()) ?> <?= htmlspecialchars($etudiant->getPrenom()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <label for="professeur" class="form-label">Professeur</label>
            <input type="text" class="form-control" name="professeur" id="professeur" required>
        </div>
        <div class="mb-3">
            <label for="motif" class="form-label">Motif</label>
            <input type="text" class="form-control" name="motif" id="motif" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" name="description" id="description" required></textarea>
        </div>
        <div class="mb-3">
            <label for="date_incident" class="form-label">Date de l'incident</label>
            <input type="date" class="form-control" name="date_incident" id="date_incident" required>
        </div>
        <button type="submit" name="submit_sanction" class="btn btn-primary">Créer la sanction</button>
    </form>
</div>
<script>
    document.getElementById('promotion_id').addEventListener('change', function() {
        document.getElementById('sanctionForm').submit();
    });
</script>
</body>
</html>
