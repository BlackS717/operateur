<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon solde</title>
    <link href="/assets/bootstrap/bootstrap.min.css" rel="stylesheet">
    <?= $this->include('partials/styles') ?>
</head>
<body>
<?= $this->include('partials/client_nav') ?>
<div class="container">
    <?= $this->include('partials/flash') ?>
    <div class="card">
        <div class="card-body">
            <h1 class="card-title">Solde actuel</h1>
            <p class="display-5"><?= number_format($solde, 0, ',', ' ') ?> Ar</p>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h1 class="card-title">Epargne actuel</h1>
            <p class="display-5"><?= number_format($epargne, 0, ',', ' ') ?> Ar</p>
        </div>
    </div>
</div>
</body>
</html>
