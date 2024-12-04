<div class="container mt-5">
    <h1>Créer une promotion</h1>
    <?php if (!empty($erreurs)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($erreurs) ?>
        </div>
    <?php endif; ?>
    
    <form action="/promotion" method="POST" class="mt-4">
        <div class="mb-3">
            <label for="libelle" class="form-label">Libellé</label>
            <input type="text" class="form-control" name="libelle" id="libelle" placeholder="BTS SIO2" required>
        </div>
        <div class="mb-3">
            <label for="annee" class="form-label">Année</label>
            <input type="text" class="form-control" name="annee" id="annee" placeholder="2024" required>
        </div>
        <button type="submit" class="btn btn-primary">Créer</button>
    </form>
</div>
