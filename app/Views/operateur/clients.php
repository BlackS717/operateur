<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comptes clients</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?= $this->include('partials/operateur_nav') ?>
<div class="container">
    <?= $this->include('partials/flash') ?>
    <h1>Situation des comptes clients</h1>
    <table class="table table-striped">
        <thead><tr><th>Numero</th><th>Date de creation</th><th>Solde</th></tr></thead>
        <tbody>
        <?php foreach ($clients as $c): ?>
            <tr>
                <td><?= esc($c['numero']) ?></td>
                <td><?= esc($c['dateCreation']) ?></td>
                <td><?= number_format($c['solde'] ?? 0, 0, ',', ' ') ?> Ar</td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
