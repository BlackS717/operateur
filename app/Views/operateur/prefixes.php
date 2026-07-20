<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prefixes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?= $this->include('partials/operateur_nav') ?>
<div class="container">
    <?= $this->include('partials/flash') ?>
    <h1>Prefixes valables</h1>
    <form action="<?= base_url('/admin/prefixes') ?>" method="post" class="row g-2 mb-4">
        <div class="col-auto">
            <input type="text" name="nom" class="form-control" placeholder="Ex: 033" pattern="[0-9]{2,4}" maxlength="4" required value="<?= old('nom') ?>">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </div>
    </form>
    <table class="table table-striped">
        <thead><tr><th>Prefixe</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($prefixes as $p): ?>
            <tr>
                <td><?= esc($p['nom']) ?></td>
                <td><a class="btn btn-sm btn-danger" href="<?= base_url('/admin/prefixes/delete/' . $p['id']) ?>">Supprimer</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
