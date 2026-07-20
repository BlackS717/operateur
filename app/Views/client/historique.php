<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?= $this->include('partials/client_nav') ?>
<div class="container">
    <?= $this->include('partials/flash') ?>
    <h1>Historique des operations</h1>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Date</th>
            <th>Type</th>
            <th>Sens</th>
            <th>Montant</th>
            <th>Frais</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($transactions as $t): ?>
            <tr>
                <td><?= esc($t['dateTransaction']) ?></td>
                <td><?= esc($t['typeNom']) ?></td>
                <td><?= (int) $t['utilisateurId'] === $userId ? 'Envoye' : 'Recu' ?></td>
                <td><?= number_format($t['montant'], 0, ',', ' ') ?> Ar</td>
                <td><?= number_format($t['frais'], 0, ',', ' ') ?> Ar</td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($transactions)): ?>
            <tr><td colspan="5">Aucune operation pour le moment.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
