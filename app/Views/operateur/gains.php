<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Situation des gains</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <?= $this->include('partials/styles') ?>
</head>
<body>
<?= $this->include('partials/operateur_nav') ?>
<div class="container">
    <?= $this->include('partials/flash') ?>
    <h1>Situation des gains</h1>
    <table class="table table-striped">
        <thead><tr><th>Type</th><th>Nombre d'operations</th><th>Total des frais</th></tr></thead>
        <tbody>
        <?php foreach ($parType as $ligne): ?>
            <tr>
                <td><?= esc($ligne['typeNom']) ?></td>
                <td><?= (int) $ligne['nombre'] ?></td>
                <td><?= number_format($ligne['total'], 0, ',', ' ') ?> Ar</td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr><th colspan="2">Total general</th><th><?= number_format($total, 0, ',', ' ') ?> Ar</th></tr>
        </tfoot>
    </table>
</div>
</body>
</html>
