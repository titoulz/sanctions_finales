<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Importer des étudiants</title>
</head>
<body>
    <h1>Importer des étudiants</h1>
    <form action="/import/process" method="post" enctype="multipart/form-data">
        <label for="csv_file">Fichier CSV :</label>
        <input type="file" name="csv_file" id="csv_file" accept=".csv" required>
        <button type="submit">Importer</button>
    </form>
</body>
</html>