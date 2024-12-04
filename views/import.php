<!-- views/templates/import.php -->
<h1>Import Etudiant</h1>
<?php if (!empty($errors)): ?>
    <div class="errors">
        <?php foreach ($errors as $error): ?>
            <p><?php echo htmlspecialchars($error); ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="success">
        <p><?php echo htmlspecialchars($success); ?></p>
    </div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <label for="promotion_id">Promotion:</label>
    <select name="promotion_id" id="promotion_id">
        <?php foreach ($promotions as $promotion): ?>
            <option value="<?php echo $promotion->getId(); ?>">
                <?php echo htmlspecialchars($promotion->getLibelle()); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <br>
    <label for="file">File:</label>
    <input type="file" name="file" id="file">
    <br>
    <button type="submit">Import</button>
</form>
<style>
    .errors {
        color: red;
    }
    .success {
        color: green;
    }
</style>