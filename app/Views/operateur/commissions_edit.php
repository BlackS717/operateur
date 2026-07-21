<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une commission</title>
    <link href="/assets/bootstrap/bootstrap.min.css" rel="stylesheet">
    <?= $this->include('partials/styles') ?>
</head>
<body>
<?= $this->include('partials/operateur_nav') ?>
<div class="container">
    <?= $this->include('partials/flash') ?>
    <h1>Modifier la commission</h1>
    <form action="<?= base_url('/admin/commissions/edit/' . $commission['id']) ?>" method="post" class="col-md-4">
        <div class="mb-3">
            <label class="form-label">Source</label>
            <select name="source" class="form-select" required>
                <?php foreach ($operateurs as $o): ?>
                    <option value="<?= $o['id'] ?>" <?= (int) $o['id'] === (int) $commission['source'] ? 'selected' : '' ?>>
                        <?= esc($o['labelle']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Destinataire</label>
            <select name="destinataire" class="form-select" required>
                <?php foreach ($operateurs as $o): ?>
                    <option value="<?= $o['id'] ?>" <?= (int) $o['id'] === (int) $commission['destinataire'] ? 'selected' : '' ?>>
                        <?= esc($o['labelle']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Commission (%)</label>
            <input type="number" step="0.1" min="0" max="100" name="pourcentage" class="form-control" required value="<?= old('pourcentage', $commission['pourcentage']) ?>">
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="<?= base_url('/admin/commissions') ?>" class="btn btn-link">Annuler</a>
    </form>
</div>
</body>
</html>