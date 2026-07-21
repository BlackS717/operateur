<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retrait</title>
    <link href="/assets/bootstrap/bootstrap.min.css" rel="stylesheet">
    <?= $this->include('partials/styles') ?>
</head>
<body>
<?= $this->include('partials/client_nav') ?>
<div class="container">
    <?= $this->include('partials/flash') ?>
    <h1>Faire un retrait</h1>
    <form action="<?= base_url('/user/retrait') ?>" method="post" class="col-md-4">
        <div class="mb-3">
            <label for="montant" class="form-label">Montant (Ar)</label>
            <input type="number" step="0.01" min="100" max="2000000" id="montant" name="montant" class="form-control" required value="<?= old('montant') ?>">
        </div>
        <button type="submit" class="btn btn-primary">Retirer</button>
    </form>
</div>
</body>
</html>
