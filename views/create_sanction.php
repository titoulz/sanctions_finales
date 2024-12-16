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
<form method="POST" action="/sanction/create">
    <label for="promotion">Promotion:</label>
    <select id="promotion" name="promotion" required>
        <option value="">Sélectionnez une promotion</option>
        <?php foreach ($promotions as $promotion): ?>
                <!-- Modifier l'accès aux propriétés de l'objet Promotion -->
                <option value="<?php echo $promotion->getId(); ?>">
                    <?php echo htmlspecialchars($promotion->getLibelle()); ?>
                </option>
            <?php endforeach; ?>
    </select><br>

    <label for="eleve">Élève sanctionné:</label>
    <select id="eleve" name="eleve" required>
        <option value="">Sélectionnez un élève</option>
        <?php foreach ($etudiants as $etudiant): ?>
            <option value="<?php echo $etudiant->getId(); ?>" 
                    data-promotion="<?php echo $etudiant->getPromotion()->getId(); ?>">
                <?php echo htmlspecialchars($etudiant->getNom() . ' ' . $etudiant->getPrenom()); ?>
            </option>
        <?php endforeach; ?>
    </select><br>

    <label for="professeur">Nom du professeur:</label>
    <input type="text" id="professeur" name="professeur" required><br>

    <label for="motif">Motif de la sanction:</label>
    <input type="text" id="motif" name="motif" required><br>

    <label for="description">Description de la sanction:</label>
    <textarea id="description" name="description" required></textarea><br>

    <label for="date_incident">Date de l'incident:</label>
    <input type="date" id="date_incident" name="date_incident" required><br>

    <button type="submit">Créer la sanction</button>
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