<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Depot</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?= $this->include('partials/client_nav') ?>
<div class="container">
    <?= $this->include('partials/flash') ?>
    <h1>Faire un depot</h1>
    <form action="<?= base_url('/user/depot') ?>" method="post" class="col-md-4">
        <div class="mb-3">
            <label for="montant" class="form-label">Montant (Ar)</label>
            <input type="number" step="0.01" min="100" max="2000000" id="montant" name="montant" class="form-control" required value="<?= old('montant') ?>">
        </div>
        <button type="submit" class="btn btn-primary">Deposer</button>
    </form>
</div>
</body>
</html>
