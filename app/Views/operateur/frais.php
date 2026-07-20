<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baremes de frais</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <?= $this->include('partials/styles') ?>
</head>
<body>
<?= $this->include('partials/operateur_nav') ?>
<div class="container">
    <?= $this->include('partials/flash') ?>
    <h1>Baremes de frais</h1>
    <form action="<?= base_url('/admin/frais') ?>" method="post" class="row g-2 mb-4 align-items-end">
        <div class="col-auto">
            <label class="form-label">Type</label>
            <select name="typeTransactionId" class="form-select" required>
                <?php foreach ($types as $t): ?>
                    <option value="<?= $t['id'] ?>"><?= esc($t['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-auto">
            <label class="form-label">Minimum</label>
            <input type="number" step="0.01" min="0" name="minimum" class="form-control" required>
        </div>
        <div class="col-auto">
            <label class="form-label">Maximum</label>
            <input type="number" step="0.01" min="0.01" name="maximum" class="form-control" required>
        </div>
        <div class="col-auto">
            <label class="form-label">Frais</label>
            <input type="number" step="0.01" min="0" name="valeur" class="form-control" required>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </div>
    </form>
    <table class="table table-striped">
        <thead><tr><th>Type</th><th>Minimum</th><th>Maximum</th><th>Frais</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($frais as $f): ?>
            <tr>
                <td><?= esc($f['typeNom']) ?></td>
                <td><?= number_format($f['minimum'], 0, ',', ' ') ?></td>
                <td><?= number_format($f['maximum'], 0, ',', ' ') ?></td>
                <td><?= number_format($f['valeur'], 0, ',', ' ') ?></td>
                <td>
                    <a class="btn btn-sm btn-secondary" href="<?= base_url('/admin/frais/edit/' . $f['id']) ?>">Modifier</a>
                    <a class="btn btn-sm btn-danger" href="<?= base_url('/admin/frais/delete/' . $f['id']) ?>">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
