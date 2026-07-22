<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier mon Epargne</title>
    <link href="/assets/bootstrap/bootstrap.min.css" rel="stylesheet">
    <?= $this->include('partials/styles') ?>
</head>
<body>
<?= $this->include('partials/client_nav') ?>
<div class="container">
    <?= $this->include('partials/flash') ?>
    <h1>Modifier la commission</h1>
    <form action="<?= base_url('/user/epargne/edit/' . $epargne['id']) ?>" method="post" class="col-md-4">
        <div class="mb-3">
            <label class="form-label">Epargne (%)</label>
            <input type="number" step="0.1" min="0" max="100" name="pourcentage" class="form-control" required value="<?= old('pourcentage', $epargne['pourcentage']) ?>">
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="<?= base_url('/user') ?>" class="btn btn-link">Annuler</a>
    </form>
</div>
</body>
</html>