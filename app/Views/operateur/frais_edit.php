<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un bareme</title>
    <link href="/assets/bootstrap/bootstrap.min.css" rel="stylesheet">
    <?= $this->include('partials/styles') ?>
</head>
<body>
<?= $this->include('partials/operateur_nav') ?>
<div class="container">
    <?= $this->include('partials/flash') ?>
    <h1>Modifier le bareme</h1>
    <form action="<?= base_url('/admin/frais/edit/' . $frais['id']) ?>" method="post" class="col-md-4">
        <div class="mb-3">
            <label class="form-label">Type</label>
            <select name="typeTransactionId" class="form-select" required>
                <?php foreach ($types as $t): ?>
                    <option value="<?= $t['id'] ?>" <?= (int) $t['id'] === (int) $frais['typeTransactionId'] ? 'selected' : '' ?>>
                        <?= esc($t['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Minimum</label>
            <input type="number" step="0.01" name="minimum" class="form-control" required value="<?= old('minimum', $frais['minimum']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Maximum</label>
            <input type="number" step="0.01" name="maximum" class="form-control" required value="<?= old('maximum', $frais['maximum']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Frais</label>
            <input type="number" step="0.01" name="valeur" class="form-control" required value="<?= old('valeur', $frais['valeur']) ?>">
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="<?= base_url('/admin/frais') ?>" class="btn btn-link">Annuler</a>
    </form>
</div>
</body>
</html>
