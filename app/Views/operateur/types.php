<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Types d'operation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <?= $this->include('partials/styles') ?>
</head>
<body>
<?= $this->include('partials/operateur_nav') ?>
<div class="container">
    <?= $this->include('partials/flash') ?>
    <h1>Types d'operation</h1>
    <form action="<?= base_url('/admin/types') ?>" method="post" class="row g-2 mb-4">
        <div class="col-auto">
            <input type="text" name="nom" class="form-control" placeholder="Ex: Depot" minlength="2" maxlength="50" required value="<?= old('nom') ?>">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </div>
    </form>
    <table class="table table-striped">
        <thead><tr><th>Nom</th></tr></thead>
        <tbody>
        <?php foreach ($types as $t): ?>
            <tr><td><?= esc($t['nom']) ?></td></tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
