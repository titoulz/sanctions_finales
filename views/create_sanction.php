<head>
    
<title>Gestion des Sanctions</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/styles.css">

</head>
<h1>Créer une sanction</h1>
<?php if (isset($error)): ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php endif; ?>
<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<form method="POST" action="/sanction/create" class="container mt-4">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="eleve" class="form-label">Élève sanctionné :</label>
                <select id="eleve" name="eleve" class="form-control" required>
                    <option value="">Sélectionnez un élève</option>
                    <?php foreach ($etudiants as $etudiant): ?>
                        <option value="<?php echo $etudiant->getId(); ?>" 
                                data-promotion="<?php echo $etudiant->getPromotion()->getId(); ?>">
                            <?php echo htmlspecialchars($etudiant->getNom() . ' ' . $etudiant->getPrenom()); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="professeur" class="form-label">Nom du professeur :</label>
                <input type="text" id="professeur" name="professeur" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="motif" class="form-label">Motif de la sanction :</label>
                <input type="text" id="motif" name="motif" class="form-control" required>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group mb-3">
                <label for="description" class="form-label">Description de la sanction :</label>
                <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
            </div>

            <div class="form-group mb-3">
                <label for="date_incident" class="form-label">Date de l'incident :</label>
                <input type="date" id="date_incident" name="date_incident" class="form-control" required>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Créer la sanction</button>
            <a href="/" class="btn btn-secondary ms-2">Annuler</a>
        </div>
    </div>
</form>

<script>
document.getElementById('promotion').addEventListener('change', function() {
    var promotionId = this.value;
    var eleveSelect = document.getElementById('eleve');
    var options = eleveSelect.querySelectorAll('option');

    options.forEach(function(option) {
        if (option.getAttribute('data-promotion') === promotionId || option.value === '') {
            option.style.display = 'block';
        } else {
            option.style.display = 'none';
        }
    });

    eleveSelect.value = ''; // Réinitialiser la sélection de l'élève
});
</script>
<script src="/js/bootstrap.min.js"></script>