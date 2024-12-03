<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0 text-center text-secondary">Importer des étudiants</h2>
                </div>
                <div class="card-body">
                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success">
                            <?= htmlspecialchars($success) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($errors['general'])): ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($errors['general']) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="promotion_id" class="form-label">Promotion</label>
                            <select
                                class="form-select <?= isset($errors['promotion']) ? 'is-invalid' : '' ?>"
                                id="promotion_id"
                                name="promotion_id"
                                required
                            >
                                <option value="">Sélectionner une promotion</option>
                                <?php foreach ($promotions as $promotion): ?>
                                    <option value="<?= $promotion->getId() ?>">
                                        <?= htmlspecialchars($promotion->getLibelle()) ?> - <?= htmlspecialchars($promotion->getAnnee()) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['promotion'])): ?>
                                <div class="invalid-feedback">
                                    <?= htmlspecialchars($errors['promotion']) ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="csv_file" class="form-label">Fichier CSV</label>
                            <input
                                type="file"
                                class="form-control <?= isset($errors['file']) ? 'is-invalid' : '' ?>"
                                id="csv_file"
                                name="csv_file"
                                accept=".csv"
                                required
                            >
                            <?php if (isset($errors['file'])): ?>
                                <div class="invalid-feedback">
                                    <?= htmlspecialchars($errors['file']) ?>
                                </div>
                            <?php endif; ?>
                            <div class="form-text mt-3 text-secondary">
                                Fichier CSV sous forme : [Prenom, Nom]
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-warning">Importer les étudiants</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>