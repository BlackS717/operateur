<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion operateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container" style="max-width: 420px; margin-top: 80px;">
    <h1 class="mb-4">Connexion operateur</h1>

    <?php if ($reports = session()->getFlashdata('reports')) { ?>
        <div class="alert alert-danger">
            <?php foreach ($reports as $report) { ?>
                <p class="mb-0"><?= esc($report) ?></p>
            <?php } ?>
        </div>
    <?php } ?>

    <form action="<?= base_url('/admin/login') ?>" method="post">
        <div class="mb-3">
            <label for="labelle" class="form-label">Operateur</label>
            <input type="text" id="labelle" name="labelle" class="form-control" required value="<?= old('labelle') ?>">
        </div>
        <div class="mb-3">
            <label for="motDePasse" class="form-label">Mot de passe</label>
            <input type="password" id="motDePasse" name="motDePasse" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-dark w-100">Se connecter</button>
    </form>
</div>
</body>
</html>
