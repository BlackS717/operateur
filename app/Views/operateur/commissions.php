<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commissions inter-operateurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <?= $this->include('partials/styles') ?>
</head>
<body>
<?= $this->include('partials/operateur_nav') ?>
<div class="container">
    <?= $this->include('partials/flash') ?>
    <h1>Commissions inter-operateurs</h1>
    <form action="<?= base_url('/admin/commissions') ?>" method="post" class="row g-2 mb-4 align-items-end">
        <div class="col-auto">
            <label class="form-label">Source</label>
            <select name="source" class="form-select" required>
                <option value="">-- Operateur source --</option>
                <?php foreach ($operateurs as $o): ?>
                    <option value="<?= $o['id'] ?>" <?= old('source') == $o['id'] ? 'selected' : '' ?>><?= esc($o['labelle']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-auto">
            <label class="form-label">Destinataire</label>
            <select name="destinataire" class="form-select" required>
                <option value="">-- Operateur destinataire --</option>
                <?php foreach ($operateurs as $o): ?>
                    <option value="<?= $o['id'] ?>" <?= old('destinataire') == $o['id'] ? 'selected' : '' ?>><?= esc($o['labelle']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-auto">
            <label class="form-label">Commission (%)</label>
            <input type="number" step="0.1" min="0" max="100" name="pourcentage" class="form-control" placeholder="Ex: 1.5" required value="<?= old('pourcentage') ?>">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </div>
    </form>
    <table class="table table-striped">
        <thead><tr><th>Source</th><th>Destinataire</th><th>Commission (%)</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($commissions as $c): ?>
            <tr>
                <td><?= esc($c['sourceLabelle']) ?></td>
                <td><?= esc($c['destinataireLabelle']) ?></td>
                <td><?= number_format($c['pourcentage'], 1, ',', ' ') ?> %</td>
                <td>
                    <a class="btn btn-sm btn-secondary" href="<?= base_url('/admin/commissions/edit/' . $c['id']) ?>">Modifier</a>
                    <a class="btn btn-sm btn-danger" href="<?= base_url('/admin/commissions/delete/' . $c['id']) ?>">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>